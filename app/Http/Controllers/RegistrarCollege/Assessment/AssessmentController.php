<?php

namespace App\Http\Controllers\RegistrarCollege\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;

class AssessmentController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $status = \App\Status::where('idno', $idno)->first();
            if ($status->is_advised == 0) {
//                return view('dean', array('advising',$idno), compact('status', 'idno', 'school_year', 'period'));
                return redirect("/dean/advising/$idno");
            } else {
                return view('reg_college.assessment.select_school_year', compact('idno'));
            }
        }
    }

    function index2($idno, $school_year, $period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $checkcollegelevels = \App\CollegeLevel::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->first();
            if (count($checkcollegelevels) > 0) {
                $status = \App\CollegeLevel::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->first();
            } else {
                $status = \App\Status::where('idno', $idno)->first();
            }
            $advising = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
            if ($status->status > 2 && $status->is_advised == 1 && $status->advising_school_year = $school_year && $status->advising_period = $period){
                return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
            } else {
                if ($status->status == env('ADVISING')) {
                    return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
                } else if ($status->status == env('ASSESSED')) {
                    return view('reg_college.assessment.assessed', compact('status', 'idno', 'school_year', 'period'));
                } else if ($status->status >= env('ENROLLED')) {
                    return view('reg_college.assessment.enrolled', compact('status', 'idno', 'school_year', 'period'));
                }else{
                    return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
                }
            }
        }
    }

    function set_up_year(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $school_year = $request->school_year;
            $period = $request->period;
            $idno = $request->idno;

            return redirect("/registrar_college/assessment2/$idno/$school_year/$period");
            //return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
        }
    }

    function checkcourse_offering_id($idno) {
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();
        $check = \App\GradeCollege::where('idno', "$idno")->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('course_offering_id', NULL)->get();

        if (count($check) > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    function readvise($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN')) {

            $status = \App\Status::where('idno', $idno)->first();
//            $status->status = 0;
            $assignlevel = $status->level;
            if ($status->advising_period == "1st Semester") {
                switch ($status->level) {
                    case "5th Year":
                        $assignlevel = "4th Year";
                        break;
                    case "4th Year":
                        $assignlevel = "3rd Year";
                        break;
                    case "3rd Year":
                        $assignlevel = "2nd Year";
                        break;
                    case "2nd Year":
                        $assignlevel = "1st Year";
                        break;
                    case "1st Year":
                        $assignlevel = NULL;
                        break;
                }
            }
            $status->level = $assignlevel;
            $status->status = 0;
            $status->is_advised = 0;
            $status->save();

            \App\Http\Controllers\Admin\Logs::log("Re-advise student $idno");
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return redirect("/registrar_college/assessment/$idno");
        }elseif (Auth::user()->accesslevel == env('DEAN')) {
            return redirect("/dean/advising/$idno");
        }
            
            //return view('reg_college.assessment.view_assessment', compact('idno', 'school_year', 'period'));
        }
    }

    function save_assessment(Request $request) {
        DB::beginTransaction();
        $this->processAssessment($request);
        DB::Commit();
        $user = \App\User::where('idno', $request->idno)->first();
        if (count($user) > 0) {
            return redirect(url('/registrar_college', array('assessment', $request->idno)));
        } else {
            return redirect(url('/'));
        }
    }

    function processAssessment($request) {
        $discounttf = 0;
        $discountof = 0;
        $discountnondiscounted = 0;
        $discountsrf = 0;
        $discounttype = 0;
        $school_year = $request->school_year;
        $period = $request->period;
//        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
//        $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        $idno = $request->idno;
        $plan = $request->plan;
        $discount_code = $request->discount;
        $level = $request->level;
        $type_of_account = $request->type_of_account;
        $program_code = $request->program_code;
        $is_audit = $request->is_audit;
        $tutorial_amount = $request->tutorial_amount;
        $tutorial_units = $request->tutorial_units;
        $request->date = date('Y-m-d');

        ///delete current records if reasssess///
        $this->deletecurrentledgers($idno, $school_year, $period);
        $this->deleteledgerduedate($idno, $school_year, $period);

        //get discount////
        if (!is_null($discount_code)) {
            $discounttype = \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->discount_type;
            if ($discounttype == 0) {
                $discounttf = $this->getdiscountrate('tf', $discount_code, $idno);
                $discountof = $this->getdiscountrate('of', $discount_code, $idno);
                $discountmf = $this->getdiscountrate('mf', $discount_code, $idno);
                $discountdf = $this->getdiscountrate('df', $discount_code, $idno);
                $discountnondiscounted = $this->getdiscountrate('non_discounted', $discount_code, $idno);
                $discountsrf = $this->getdiscountrate('srf', $discount_code, $idno);
            } else if ($discounttype == 1) {
                $discounttf = $this->getdiscount('tf', $discount_code, $idno);
            }
        }
        //get tuition fee rate///
//        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $program_code)->where('period', $period)->where('level', $level)->first();
        //remove program code
        $tfr = \App\CtrCollegeTuitionFee::where('period', $period)->where('level', $level)->first();
        $tuitionrate = $tfr->per_unit;

        //poppulate other fee with discount////
        $course_assessed = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        if (count($course_assessed) > 1) {
            $this->getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code, $request,$discountnondiscounted);
        } else {
            $check_practicum = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)
                    ->where(function($q) {
                        $q->where('course_name', 'like', '%practicum%')
                        ->orWhere('course_name', 'like', '%intern%')
                        ->orWhere('course_name', 'like', '%internship%')
                        ->orWhere('course_name', 'like', '%ojt%')
                        ->orWhere('course_name', 'like', '%practice%');
                    })
                    ->get();
            if (count($check_practicum) == 1) {
                $this->getPracticumOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code,$request,$discountnondiscounted);
            } else {
                $this->getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code,$request,$discountnondiscounted);
            }
        }
        //check enrollment cut off
        //last day of enrollment should be in the enrollment cut off
        $cut_off = \App\CtrEnrollmentCutOff::where('academic_type', "College")->first();
        if (date('Y-m-d') > $cut_off->cut_off) {
            $this->addLatePayment($idno, $school_year, $period, $level, $program_code);
        }
        //populate tuition fee with discount///
        $this->getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan, $discounttf, $discountof, $discount_code, $discounttype, $tutorial_amount, $tutorial_units);
        //populate srf//
        $this->getSRF($idno, $program_code, $school_year, $period, $level,$discount_code,$discountsrf);
        //populate lab fee//
        $this->getLABFEE($idno, $program_code, $school_year, $period, $level,$discount_code,$discountsrf);
        //populate due dates//
        $this->computeLedgerDueDate($idno, $school_year, $period, $plan);
        //change status///
        $this->changeStatus($is_audit, $school_year, $period, $plan, $type_of_account, $idno, $discount_code);
        //check reservation//
        $this->checkReservations($request, $idno, $school_year, $period);
        //check if no payment will be made
        $this->checkLedger($request, $idno, $school_year, $period);
        
        \App\Http\Controllers\Admin\Logs::log("Process assessment of student $idno for S.Y. $school_year, $period");
        /*
          $totalFee = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('amount');
          $totalDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('discount');
          $tuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('amount');
          $tuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('discount');
          $misc = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 1)->sum('amount');
          $miscDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 1)->sum('discount');
          $other = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 2)->sum('amount');
          $otherDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 2)->sum('discount');
          $depo = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 3)->sum('amount');
          $depoDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 3)->sum('discount');
          $srf = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 4)->sum('amount');
          $srfDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 4)->sum('discount');
         */
    }

    function reassess($school_year,$period,$idno) {
        
        DB::beginTransaction();
        $this->deletecurrentledgers($idno, $school_year, $period);
        $this->deleteledgerduedate($idno, $school_year, $period);
        
        $updatestatus = \App\Status::where('idno', $idno)->first();
        $updatestatus->status = 1;
        $updatestatus->save();
        DB::Commit();

        \App\Http\Controllers\Admin\Logs::log("Re-assess assessment of student $idno");
        return redirect("/registrar_college/assessment/$idno");
    }

    function print_registration_form($idno, $school_year, $period) {

        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
        $student_info = \App\StudentInfo::where('idno', $idno)->first();
        //$y = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
        $y_year = $school_year;
        $y_period = $period;

        //$school_year = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        $ledger_due_dates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('due_switch', 1)->get();
        $downpayment = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('due_switch', 0)->first();

            \App\Http\Controllers\Admin\Logs::log("Print Registration Form of student: $idno");
        $pdf = PDF::loadView('reg_college.assessment.registration_form', compact('student_info', 'grades', 'user', 'status', 'school_year', 'period', 'ledger_due_dates', 'downpayment', 'y_year', 'y_period'));
        $pdf->setPaper(array(0, 0, 612.00, 936.0));
        return $pdf->stream("registration_form_$status->registration_no.pdf");

        //return "Printing of Registration form will be here.";
    }

    function print_registration_form_schedule($idno, $school_year, $period) {

        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
        $student_info = \App\StudentInfo::where('idno', $idno)->first();
        //$y = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
        $y_year = $school_year;
        $y_period = $period;

        //$school_year = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        $ledger_due_dates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('due_switch', 1)->get();
        $downpayment = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('due_switch', 0)->first();

            \App\Http\Controllers\Admin\Logs::log("STUDENT's SCHEDULE of student: $idno");
        $pdf = PDF::loadView('reg_college.assessment.registration_form_schedule', compact('student_info', 'grades', 'user', 'status', 'school_year', 'period', 'ledger_due_dates', 'downpayment', 'y_year', 'y_period'));
        $pdf->setPaper(array(0, 0, 612.00, 936.0));
        return $pdf->stream("registration_form_$status->registration_no.pdf");

        //return "Printing of Registration form will be here.";
    }

    function deletecurrentledgers($idno, $school_year, $period) {
        $currentledgers = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<=', '6')->get();
        if (count($currentledgers) > 0) {
            foreach ($currentledgers as $currentledger) {
                $currentledger->delete();
            }
        }
        $deletelatepayments = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subsidiary', 'Late Payment')->get();
        if (count($deletelatepayments) > 0) {
            foreach ($deletelatepayments as $deletelatepayment) {
                $deletelatepayment->delete();
            }
        }
    }

    function deleteledgerduedate($idno, $school_year, $period) {
        $deleteledgerduedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        if (count($deleteledgerduedates) > 0) {
            foreach ($deleteledgerduedates as $deleteledgerduedate) {
                $deleteledgerduedate->delete();
            }
        }
    }

    function getdiscountrate($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->tuition_fee;
        } elseif ($type == 'of') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->other_fee;
        } elseif ($type == 'non_discounted') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->non_discounted;
        } elseif ($type == 'srf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->srf;
         } elseif ($type == 'mf'){
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->misc_fee;
         } elseif ($type == 'df') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->depository_fee;
        }
    }

    function getdiscount($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->amount;
        }
    }

    function addLatePayment($idno, $school_year, $period, $level, $program_code) {
        $latefees = \App\CtrCollegeLatePayment::get();
        if (count($latefees) > 0) {
            foreach ($latefees as $latefee) {
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = $latefee->category;
                $addledger->subsidiary = $latefee->subsidiary;
                $addledger->receipt_details = $latefee->receipt_details;
                $addledger->accounting_code = $latefee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($latefee->accounting_code);
                $addledger->category_switch = $latefee->category_switch;
                $addledger->amount = $latefee->amount;
                $addledger->save();
            }
        }
    }

    function getPracticumOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code,$request,$discountnondiscounted) {
        $otherfees = \App\CtrCollegePracticumFee::get();
        if (count($otherfees) > 0) {
            foreach ($otherfees as $otherfee) {
                if(isset($request->other[$otherfee->id])){
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = $otherfee->category;
                $addledger->subsidiary = $otherfee->subsidiary;
                $addledger->receipt_details = $otherfee->receipt_details;
                $addledger->accounting_code = $otherfee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                $addledger->category_switch = $otherfee->category_switch;
                $addledger->amount = $otherfee->amount;
                $addledger->discount = $otherfee->amount * ($discountof / 100);
                $addledger->discount_code = $discount_code;
                $addledger->save();
                }
            }
        }
        $is_foreign = \App\User::where('idno', $idno)->first();
        if (count($is_foreign) > 0) {
            if ($is_foreign->is_foreign == '1') {
                $checkforeign = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('subsidiary','Foreign Fee')->get();
                if(count($checkforeign) == 0){
                $addfee = \App\CtrCollegePracticumForeignFee::get();
                foreach ($addfee as $fee) {
                if(isset($request->add[$fee->id])){
                    $addledger = new \App\Ledger;
                    $addledger->idno = $idno;
                    $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                    $addledger->program_code = $program_code;
                    $addledger->level = $level;
                    $addledger->school_year = $school_year;
                    $addledger->period = $period;
                    $addledger->category = $fee->category;
                    $addledger->subsidiary = $fee->subsidiary;
                    $addledger->receipt_details = $fee->receipt_details;
                    $addledger->accounting_code = $fee->accounting_code;
                    $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                    $addledger->category_switch = $fee->category_switch;
                    $addledger->amount = $fee->amount;
                    $addledger->discount = $fee->amount * ($discountof / 100);
                    $addledger->discount_code = $discount_code;
                    $addledger->save();
                }
                }
                }
            }
        }
    }

    function getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code,$request, $discountnondiscounted) {
        $is_new = \App\Status::where('idno', $idno)->first()->is_new;
        if($is_new == 0){
//            $otherfees = \App\CtrCollegeOtherFee::where('program_code', $program_code)->where('level', $level)->where('period', $period)->get();
            //remove program
            $otherfees = \App\CtrCollegeOtherFee::where('level', $level)->where('period', $period)->get();
            if (count($otherfees) > 0) {
                foreach ($otherfees as $otherfee) {
                    if(isset($request->other[$otherfee->id])){
                    $addledger = new \App\ledger;
                    $addledger->idno = $idno;
                    $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                    $addledger->program_code = $program_code;
                    $addledger->level = $level;
                    $addledger->school_year = $school_year;
                    $addledger->period = $period;
                    $addledger->category = $otherfee->category;
                    $addledger->subsidiary = $otherfee->subsidiary;
                    $addledger->receipt_details = $otherfee->receipt_details;
                    $addledger->accounting_code = $otherfee->accounting_code;
                    $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                    $addledger->category_switch = $otherfee->category_switch;
                    $addledger->amount = $otherfee->amount;
                    if($otherfee->category_switch==1){
                        $addledger->discount = $otherfee->amount * ($discountmf / 100);
                    } else if($otherfee->category_switch==3){
                        $addledger->discount = $otherfee->amount * ($discountdf / 100);
                    } else {
                        $addledger->discount = $otherfee->amount * ($discountof / 100);
                    }
                    $addledger->discount_code = $discount_code;
                    $addledger->save();
                    }
                }
            }
        }else{
//            $otherfees = \App\CtrCollegeNewOtherFee::where('program_code', $program_code)->where('level', $level)->where('period', $period)->get();
            //remove program
            $otherfees = \App\CtrCollegeNewOtherFee::where('level', $level)->where('period', $period)->get();
            if (count($otherfees) > 0) {
                foreach ($otherfees as $otherfee) {
                    if(isset($request->other[$otherfee->id])){
                    $addledger = new \App\ledger;
                    $addledger->idno = $idno;
                    $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                    $addledger->program_code = $program_code;
                    $addledger->level = $level;
                    $addledger->school_year = $school_year;
                    $addledger->period = $period;
                    $addledger->category = $otherfee->category;
                    $addledger->subsidiary = $otherfee->subsidiary;
                    $addledger->receipt_details = $otherfee->receipt_details;
                    $addledger->accounting_code = $otherfee->accounting_code;
                    $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                    $addledger->category_switch = $otherfee->category_switch;
                    $addledger->amount = $otherfee->amount;
                    $addledger->discount = $otherfee->amount * ($discountof / 100);
                    $addledger->discount_code = $discount_code;
                    $addledger->save();
                    }
                }
            }
        }

        $is_foreign = \App\User::where('idno', $idno)->first();
        if (count($is_foreign) > 0) {
            if ($is_foreign->is_foreign == '1') {
                $checkforeign = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('subsidiary','Foreign Fee')->get();
                if(count($checkforeign) == 0){
                $addfee = \App\CtrCollegeForeignFee::get();
                    foreach ($addfee as $fee) {
                        if(isset($request->add[$fee->id])){
                            $addledger = new \App\Ledger;
                            $addledger->idno = $idno;
                            $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                            $addledger->program_code = $program_code;
                            $addledger->level = $level;
                            $addledger->school_year = $school_year;
                            $addledger->period = $period;
                            $addledger->category = $fee->category;
                            $addledger->subsidiary = $fee->subsidiary;
                            $addledger->receipt_details = $fee->receipt_details;
                            $addledger->accounting_code = $fee->accounting_code;
                            $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                            $addledger->category_switch = $fee->category_switch;
                            $addledger->amount = $fee->amount;
                            $addledger->discount = $fee->amount * ($discountof / 100);
                            $addledger->discount_code = $discount_code;
                            $addledger->save();
                        }
                    }
                }else{
                    $addfee = \App\CtrCollegeForeignFee::where('subsidiary', "!=",'Foreign Fee')->get();
                    foreach ($addfee as $fee) {
                        if(isset($request->add[$fee->id])){
                            $addledger = new \App\Ledger;
                            $addledger->idno = $idno;
                            $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                            $addledger->program_code = $program_code;
                            $addledger->level = $level;
                            $addledger->school_year = $school_year;
                            $addledger->period = $period;
                            $addledger->category = $fee->category;
                            $addledger->subsidiary = $fee->subsidiary;
                            $addledger->receipt_details = $fee->receipt_details;
                            $addledger->accounting_code = $fee->accounting_code;
                            $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                            $addledger->category_switch = $fee->category_switch;
                            $addledger->amount = $fee->amount;
                            $addledger->discount = $fee->amount * ($discountof / 100);
                            $addledger->discount_code = $discount_code;
                            $addledger->save();
                        }
                    }
                }
            }
        }

        //non discounted other fees
        if($is_new == 0){
//            $nondiscountotherfees = \App\CtrCollegeNonDiscountedOtherFee::where('program_code', $program_code)->where('level', $level)->where('period', $period)->get();
            //remove program
            $nondiscountotherfees = \App\CtrCollegeNonDiscountedOtherFee::where('level', $level)->where('period', $period)->get();
            if (count($nondiscountotherfees) > 0) {
                foreach ($nondiscountotherfees as $otherfee) {
                    if(isset($request->nodiscountother[$otherfee->id])){
                        $addledger = new \App\ledger;
                        $addledger->idno = $idno;
                        $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                        $addledger->program_code = $program_code;
                        $addledger->level = $level;
                        $addledger->school_year = $school_year;
                        $addledger->period = $period;
                        $addledger->category = $otherfee->category;
                        $addledger->subsidiary = $otherfee->subsidiary;
                        $addledger->receipt_details = $otherfee->receipt_details;
                        $addledger->accounting_code = $otherfee->accounting_code;
                        $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                        $addledger->category_switch = $otherfee->category_switch;
                        $addledger->amount = $otherfee->amount;
                        $addledger->discount = $otherfee->amount * ($discountnondiscounted / 100);
                        $addledger->discount_code = $discount_code;
                        $addledger->save();
                    }
                }
            }
        }else{
//            $nondiscountotherfees = \App\CtrCollegeNewNonDiscountOtherFee::where('program_code', $program_code)->where('level', $level)->where('period', $period)->get();
            //remove program
            $nondiscountotherfees = \App\CtrCollegeNewNonDiscountOtherFee::where('level', $level)->where('period', $period)->get();
            if (count($nondiscountotherfees) > 0) {
                foreach ($nondiscountotherfees as $otherfee) {
                    if(isset($request->nodiscountother[$otherfee->id])){
                        $addledger = new \App\ledger;
                        $addledger->idno = $idno;
                        $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                        $addledger->program_code = $program_code;
                        $addledger->level = $level;
                        $addledger->school_year = $school_year;
                        $addledger->period = $period;
                        $addledger->category = $otherfee->category;
                        $addledger->subsidiary = $otherfee->subsidiary;
                        $addledger->receipt_details = $otherfee->receipt_details;
                        $addledger->accounting_code = $otherfee->accounting_code;
                        $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                        $addledger->category_switch = $otherfee->category_switch;
                        $addledger->amount = $otherfee->amount;
                        $addledger->discount = $otherfee->amount * ($discountnondiscounted / 100);
                        $addledger->discount_code = $discount_code;
                        $addledger->save();
                    }
                }
            }
        }
    }

    function getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan, $discounttf, $discountof, $discount_code, $discounttype, $tutorial_amount, $tutorial_units) {
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();

//        $interest = $this->getInterest($plan);
        $interest = 1;
        $tuitionfee = 0;
        $tobediscount = 0;
        foreach ($grades as $grade) {
            if ($discounttype == 0) {
                $tobediscount = $tobediscount + ((((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * ($discounttf / 100)) * $interest);
            } else if ($discounttype == 1) {
                $tobediscount = $tobediscount + $discounttf;
            }

            $tuitionfee = $tuitionfee + (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * $interest);
        }
        $addledger = new \App\ledger;
        $addledger->idno = $idno;
        $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
        $addledger->program_code = $program_code;
        $addledger->level = $level;
        $addledger->school_year = $school_year;
        $addledger->period = $period;
        $addledger->category = "Tuition Fee";
        $addledger->subsidiary = "Tuition Fee";
        $addledger->receipt_details = "Tuition Fee";
        $addledger->accounting_code = env("AR_TUITION_CODE");
        $addledger->accounting_name = env("AR_TUITION_NAME");
        $addledger->category_switch = env("TUITION_FEE");
        $addledger->amount = $this->roundOff($tuitionfee);
        $addledger->discount = $this->roundOff($tobediscount);
        $addledger->discount_code = $discount_code;
        $addledger->save();

        $status = \App\Status::where('idno', $idno)->first();
        $addamount = 0;
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $plan_charge = \App\CtrPlanCharge::where('academic_type', $status->academic_type)->first();
        if (count($due_dates) > 0) {
            foreach ($due_dates as $paln) {
                $addamount = $addamount + $plan_charge->amount;
//                $addledger = new \App\ledger;
//                $addledger->idno = $idno;
//                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
//                $addledger->program_code = $program_code;
//                $addledger->level = $level;
//                $addledger->school_year = $school_year;
//                $addledger->period = $period;
//                $addledger->category = "Tuition Fee";
//                $addledger->subsidiary = "Tuition Fee";
//                $addledger->receipt_details = "Tuition Fee";
//                $addledger->accounting_code = env("AR_TUITION_CODE");
//                $addledger->accounting_name = env("AR_TUITION_NAME");
//                $addledger->category_switch = env("TUITION_FEE");
//                $addledger->amount = 300;
//                $addledger->save();
            }
            $updateledger = \App\Ledger::where('idno', $idno)->where('level', $level)->where('school_year', $school_year)->where('period', $period)->where('category_switch', env('TUITION_FEE'))->first();
            $updateledger->amount = $updateledger->amount + $addamount;
            $updateledger->save();
        }
        
        //Compute tutorial amount//
        if($tutorial_amount > 0){
        $this->computeTutorial($idno, $program_code, $school_year, $period, $level,$tuitionrate,$tutorial_units,$tutorial_amount);
        }
    }
    

    function computeTutorial($idno, $program_code, $school_year, $period, $level,$tuitionrate,$tutorial_units,$tutorial_amount) {
        $addledger = new \App\ledger;
        $addledger->idno = $idno;
        $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
        $addledger->program_code = $program_code;
        $addledger->level = $level;
        $addledger->school_year = $school_year;
        $addledger->period = $period;
        $addledger->category = "Tutorial Fee";
        $addledger->subsidiary = "Tutorial Fee";
        $addledger->receipt_details = "Tutorial Fee";
        $addledger->accounting_code = 2011;
        $addledger->accounting_name = "Accounts Payable - Depository Fees";
        $addledger->category_switch = env("SRF_FEE");
        $addledger->amount = $tutorial_amount-($tuitionrate*$tutorial_units);
        $addledger->supply_remarks = $tutorial_units;
        $addledger->save();
    }

    function getSRF($idno, $program_code, $school_year, $period, $level,$discount_code,$discountsrf) {
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('srf', '>', '0')->get();
        if (count($grades) > 0) {
            foreach ($grades as $grade) {
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = "SRF";
                $addledger->subsidiary = $grade->course_code;
                $addledger->receipt_details = "SRF";
                $addledger->accounting_code = env("SRF_CODE");
                $addledger->accounting_name = env("SRF_NAME");
                $addledger->category_switch = env("SRF_FEE");
                $addledger->amount = $grade->srf;
                $addledger->discount =  $grade->srf * ($discountsrf / 100);
                $addledger->discount_code = $discount_code;
                $addledger->srf_group = $grade->srf_group;
                $addledger->save();
            }
        }
    }

    function getLABFEE($idno, $program_code, $school_year, $period, $level,$discount_code,$discountsrf) {
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('lab_fee', '>', '0')->get();
        if (count($grades) > 0) {
            foreach ($grades as $grade) {
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = "SRF";
                $addledger->subsidiary = "Lab Fee-" . $grade->course_code;
                $addledger->receipt_details = "SRF";
                $addledger->accounting_code = env("LAB_FEE_CODE");
                $addledger->accounting_name = env("LAB_FEE_NAME");
                $addledger->category_switch = env("SRF_FEE");
                $addledger->amount = $grade->lab_fee;
                $addledger->discount =  $grade->lab_fee * ($discountsrf / 100);
                $addledger->discount_code = $discount_code;
                $addledger->srf_group = $grade->srf_group;
                $addledger->save();
            }
        }
    }

    function get_percentage_now($plan) {
        if ($plan == "Plan A") {
            $interest = 1;
        } else if ($plan == "Plan B") {
            $interest = .5;
        } else if ($plan == "Plan C") {
            $interest = .35;
        } else if ($plan == "Plan D") {
            $interest = .2;
        }
        return $interest;
    }

    function computeLedgerDueDate($idno, $school_year, $period, $plan) {
        $total_decimal = 0;
        $status = \App\Status::where('idno', $idno)->first();
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $percentage_now = $this->get_percentage_now($plan);

        $totalTuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('amount');
        $totalOtherFees = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('amount');
        $totalTuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('discount');
        $totalOtherFeesDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('discount');
        $totalFees = ($totalTuition + $totalOtherFees) - ($totalTuitionDiscount + $totalOtherFeesDiscount);
        $downpaymentamount = (($totalTuition - $totalTuitionDiscount) * $percentage_now) + ($totalOtherFees - $totalOtherFeesDiscount);
        if ($plan == 'Plan A') {
            $addledgerduedates = new \App\LedgerDueDate;
            $addledgerduedates->idno = $idno;
            $addledgerduedates->school_year = $school_year;
            $addledgerduedates->period = $period;
            $addledgerduedates->due_switch = 0;
            $addledgerduedates->due_date = date('Y-m-d');
            $addledgerduedates->amount = $totalFees;
            $addledgerduedates->save();
        } else {
            $addledgerduedates = new \App\LedgerDueDate;
            $addledgerduedates->idno = $idno;
            $addledgerduedates->school_year = $school_year;
            $addledgerduedates->period = $period;
            $addledgerduedates->due_switch = 0;
            $addledgerduedates->due_date = date('Y-m-d');
            $addledgerduedates->amount = $downpaymentamount;
            $addledgerduedates->save();
            foreach ($due_dates as $paln) {
                $totalFees_percentage = (($totalTuition * ($paln->percentage / 100)) + $totalOtherFees) - (($totalTuitionDiscount * ($paln->percentage / 100)) + $totalOtherFeesDiscount);
                $tf_percentage        = (($totalTuition * ($paln->percentage / 100))                    - (($totalTuitionDiscount * ($paln->percentage / 100))));

                $addledgerduedates = new \App\LedgerDueDate;
                $addledgerduedates->idno = $idno;
                $addledgerduedates->school_year = $school_year;
                $addledgerduedates->period = $period;
                $addledgerduedates->due_switch = 1;
                $addledgerduedates->due_date = $paln->due_date;
                $plan_amount = floor($this->computeplan($downpaymentamount, $totalFees_percentage, $due_dates, $tf_percentage));
                $addledgerduedates->amount = $plan_amount;
                $addledgerduedates->save();
                $total_decimal = $total_decimal + ($this->computeplan($downpaymentamount, $totalFees_percentage, $due_dates, $tf_percentage) - $plan_amount);
            }
            $this->update_due_dates($idno, $total_decimal, $downpaymentamount);
        }
    }

    function update_due_dates($idno, $total_decimal, $downpaymentamount) {
        $update = \App\LedgerDueDate::where('idno', $idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $downpaymentamount + $total_decimal;
        $update->save();
    }

    function getAccountingName($accounting_code) {
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first()->accounting_name;
        return $accounting_name;
    }

    function getInterest($plan) {

        if ($plan == "Plan A") {
            $interest = 1;
        } else if ($plan == "Plan B") {
            $interest = 1.01;
        } else if ($plan == "Plan C") {
            $interest = 1.02;
        } else if ($plan == "Plan D") {
            $interest = 1.03;
        }
        return $interest;
    }

    function computeplan($downpaymentamount, $totalFees, $due_dates, $tf) {
        $planpayment = $tf;
//        $planpayment = ($totalFees - $downpaymentamount) / count($due_dates);
        return $planpayment;
    }

    function changeStatus($is_audit, $school_year, $period, $plan, $type_of_account, $idno, $discount_code) {
        $changestatus = \App\Status::where('idno', $idno)->first();
        $changestatus->date_registered = date('Y-m-d');
        $changestatus->status = env('ASSESSED');
        $changestatus->school_year = $school_year;
        $changestatus->period = $period;
        $changestatus->type_of_account = $type_of_account;
        $changestatus->type_of_plan = $plan;
        $changestatus->school_year = $school_year;
        $changestatus->period = $period;
        $changestatus->type_of_discount = $discount_code;
        $changestatus->is_audit = $is_audit;
        $changestatus->save();
    }

    function checkReservations($request, $idno, $school_year, $period) {
        $levels_reference_id = uniqid();
        $checkreservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $totalamount = 0;
            $firsttotalamount = 0;
            $reference_id = uniqid();
            $ledgers = \App\Ledger::where('idno', $idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->get();

//            MainPayment::addUnrealizedEntry($request, $reference_id);
            $totalamount = $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
            $firsttotalamount = $totalamount;

//            $changestatus = \App\Status::where('idno', $idno)->first();
//            $changestatus->status = env("ENROLLED");
//            $changestatus->update();
            
            $changereservation = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
            if (count($changereservation) > 0) {
                foreach ($changereservation as $change) {
                    if($change->amount == $totalamount){
                        $change->levels_reference_id = $levels_reference_id;
                        $change->is_consumed = '1';
                        $change->consume_sy = $school_year;
                        $change->update();
                    }else if($change->amount >= $totalamount){
                        $change->levels_reference_id = $levels_reference_id;
                        $change->is_consumed = '1';
                        $change->consume_sy = $school_year;
                        $lessreservation = $change->amount - $totalamount; 
                        $change->amount = $totalamount;
                        $change->update();
                       
                        //add remaining reservations
                        $addreservation = new \App\Reservation;
                        $addreservation->idno=$change->idno;
                        $addreservation->reference_id=$change->reference_id;
                        $addreservation->transaction_date=$change->transaction_date;
                        $addreservation->amount=$lessreservation;
                        $addreservation->reservation_type=$change->reservation_type;
                        $addreservation->posted_by= $change->posted_by;
                        $addreservation->save();
                    }else{
                        $change->levels_reference_id = $levels_reference_id;
                        $change->is_consumed = '1';
                        $change->consume_sy = $school_year;
                        $change->update();
                    }
                    $totalamount = $totalamount - $change->amount;
                }
            }
            $this->postDebit($idno, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period,$firsttotalamount);
        }
        $change = \App\Status::where('idno', $request->idno)->first();
        $change->levels_reference_id = $levels_reference_id;
        $change->update();
        
    }

    function processAccounting($request, $reference_id, $totalpayment, $ledgers, $accounting_type) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $totalamount = 0;
        if (count($ledgers) > 0) {
            foreach ($ledgers as $ledger) {
                if ($totalpayment > 0) {
                    //process if there is discount
                    if ($ledger->debit_memo == 0 && $ledger->payment == 0) {
                        if ($ledger->discount > 0) {

                            MainPayment::processDiscount($request, $reference_id, $ledger->discount, $ledger->discount_code, $accounting_type);
                            $addacct = new \App\Accounting;
                            $addacct->transaction_date = $request->date;
                            $addacct->reference_id = $reference_id;
                            $addacct->accounting_type = $accounting_type;
                            $addacct->category = $ledger->category;
                            $addacct->subsidiary = $ledger->subsidiary;
                            $addacct->receipt_details = $ledger->receipt_details;
                            $addacct->particular = $ledger->receipt_details;
                            $addacct->accounting_code = $ledger->accounting_code;
                            $addacct->accounting_name = $ledger->accounting_name;
                            $addacct->department = $ledger->department;
                            $addacct->fiscal_year = $fiscal_year;
                            $addacct->credit = $ledger->discount;
                            $addacct->posted_by = Auth::user()->idno;
                            $addacct->save();
                            $totalamount = $totalamount + $ledger->discount;
                        }
                    }
                    if ($totalpayment >= $ledger->amount - $ledger->discount - $ledger->debit_memo - $ledger->payment) {
                        $amount = $ledger->amount - $ledger->discount - $ledger->debit_memo - $ledger->payment;
                        if ($accounting_type == env("DEBIT_MEMO")) {
                            $ledger->debit_memo = $ledger->debit_memo + $amount;
                        } else {
                            $ledger->payment = $ledger->payment + $amount;
                        }
                        $ledger->update();

                        $addacct = new \App\Accounting;
                        $addacct->transaction_date = $request->date;
                        $addacct->reference_id = $reference_id;
                        $addacct->reference_number = $ledger->id;
                        $addacct->accounting_type = $accounting_type;
                        $addacct->category = $ledger->category;
                        $addacct->subsidiary = $ledger->subsidiary;
                        $addacct->receipt_details = $ledger->receipt_details;
                        $addacct->particular = $ledger->receipt_details;
                        $addacct->accounting_code = $ledger->accounting_code;
                        $addacct->accounting_name = $ledger->accounting_name;
                        $addacct->department = $ledger->department;
                        $addacct->fiscal_year = $fiscal_year;
                        $addacct->credit = $amount;
                        $addacct->posted_by = Auth::user()->idno;
                        $addacct->save();
                        $totalamount = $totalamount + $amount;
                        $totalpayment = $totalpayment - $amount;
                    } else {
                        if ($totalpayment > 0) {
                            if ($accounting_type == env("DEBIT_MEMO")) {
                                $ledger->debit_memo = $ledger->debit_memo + $totalpayment;
                            } else {
                                $ledger->payment = $ledger->payment + $totalpayment;
                            }
                            $ledger->update();
                            $addacct = new \App\Accounting;
                            $addacct->transaction_date = $request->date;
                            $addacct->reference_id = $reference_id;
                            $addacct->reference_number = $ledger->id;
                            $addacct->accounting_type = $accounting_type;
                            $addacct->category = $ledger->category;
                            $addacct->subsidiary = $ledger->subsidiary;
                            $addacct->receipt_details = $ledger->receipt_details;
                            $addacct->particular = $ledger->receipt_details;
                            $addacct->accounting_code = $ledger->accounting_code;
                            $addacct->accounting_name = $ledger->accounting_name;
                            $addacct->fiscal_year = $fiscal_year;
                            $addacct->credit = $totalpayment;
                            $addacct->posted_by = Auth::user()->idno;
                            $addacct->save();
                            $totalamount = $totalamount + $totalpayment;
                            $totalpayment = 0;
                        }
                    }
                }
            }
        }
        return $totalamount;
    }

    function postDebit($idno, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period,$totalamount) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $reservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 1)->where('is_reverse', 0)->where('levels_reference_id', $levels_reference_id)->get();
        $department = \App\Status::where('idno', $idno)->first()->department;
        $totalReserved = 0;
        if (count($reservations) > 0) {
            foreach ($reservations as $ledger) {
                $addacct = new \App\Accounting;
                $addacct->transaction_date = date('Y-m-d');
                $addacct->reference_id = $reference_id;
                //$addacct->reference_number=$ledger->id;
                $addacct->accounting_type = env("DEBIT_MEMO");
                $addacct->subsidiary = $ledger->idno;
                $dept = \App\Status::where('idno', $idno)->first();
                if (count($dept) > 0) {
                    $department = $dept->department;
                } else {
                    $department = "None";
                }
                $addacct->department = $department;
                if ($ledger->reservation_type == 1) {
                    $category = "Reservation";
                    $accounting_code = env("RESERVATION_CODE");
                    $accounting_name = env("RESERVATION_NAME");
                } else if ($ledger->reservation_type == 2) {
                    $category = "Student Deposit";
                    $accounting_code = env("STUDENT_DEPOSIT_CODE");
                    $accounting_name = env("STUDENT_DEPOSIT_NAME");
                }
                $addacct->category = $category;
                $addacct->receipt_details = $category;
                $addacct->particular = $category;
                $addacct->accounting_code = $accounting_code;
                $addacct->accounting_name = $accounting_name;
                $addacct->department = $department;
                $addacct->fiscal_year = $fiscal_year;
//                $addacct->debit = $totalamount/count($reservations);
                $addacct->debit = $ledger->amount;
                $addacct->posted_by = Auth::user()->idno;
                $addacct->save();
                $ledger->is_consumed = 1;
                $totalReserved = $totalReserved + $ledger->amount;
            }
            $this->postDebitMemo($idno, $reference_id, $totalReserved, $levels_reference_id, $school_year, $period,$totalamount);
        }
    }

    function postDebitMemo($idno, $reference_id, $totalReserved, $levels_reference_id, $school_year, $period,$totalamount) {

        $debit_memo = new \App\DebitMemo;
        $debit_memo->idno = $idno;
        $debit_memo->levels_reference_id = $levels_reference_id;
        $debit_memo->transaction_date = date("Y-m-d");
        $debit_memo->reference_id = $reference_id;
        $debit_memo->dm_no = $this->getDMNumber();
        $debit_memo->explanation = "Reversal of Reservation/Student Deposit";
        $debit_memo->amount = $totalamount;
        $debit_memo->reservation_sy = $school_year;
        $debit_memo->posted_by = Auth::user()->idno;
        $debit_memo->school_year = \App\Status::where('idno', $idno)->first()->school_year;
        $debit_memo->period = \App\Status::where('idno', $idno)->first()->period;
        $debit_memo->save();
    }

    function getDMNumber() {
        $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
        $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->dm_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }

        $update = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $update->dm_no = $update->dm_no + 1;
        $update->update();

        return $id . $receipt . $number;
    }

    function checkLedger($request, $idno, $school_year, $period) {
        $checkledger_amount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('amount');
        $checkledger_discount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('discount');
        $checkledger = $checkledger_amount - $checkledger_discount;
        if ($checkledger <= 0) {

            $change = \App\Status::where('idno', $idno)->first();
            $change->status = env("ENROLLED");
            $change->date_enrolled = date('Y-m-d');
            $change->update();
            $no = $idno;
            if (strlen($idno) > 10) {
                $user = \App\User::where('idno', $idno)->first();
                $no = MainPayment::getIdno($idno);
                $user->idno = $no;
                $user->save();
            } else {
                $status = \App\Status::where('idno', $idno)->first();
                $status->is_new = 0;
                $status->update();
            }

            MainPayment::addLevels($no);
        }
    }

    function roundOff($amount) {
        return round($amount);
    }

    function reassess_reservations($idno, $levels_reference_id, $schoolyear, $period) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            $status = \App\Status::where('idno', $idno)->first();
            $user = \App\User::where('idno', $idno)->first();
            if ($status->status == env("ASSESSED")) {
                DB::beginTransaction();
                $this->reverse_reservations($idno, $levels_reference_id);
                $this->removeDM($idno, $levels_reference_id);
                DB::commit();
            }
            return $this->reassess($schoolyear,$period,$idno);
        }
    }

    function reverse_reservations($idno, $levels_reference_id) {
        $reverses = \App\Reservation::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->get();
        foreach ($reverses as $reverse) {
            $old_reservations_amount = \App\Reservation::where('idno', $idno)->where('levels_reference_id', NULL)->where('reference_id', $reverse->reference_id)->first();
            if(count($old_reservations_amount)>0){
            $reverse->amount = $reverse->amount + $old_reservations_amount->amount;
            }
            $reverse->levels_reference_id = NULL;
            $reverse->is_consumed = 0;
            $reverse->consume_sy = "";
            $reverse->save();
            if(count($old_reservations_amount)>0){
            $old_reservations_amount->delete();
            }
        }
    }

    function removeDM($idno, $levels_reference_id) {
        $removeDMs = \App\DebitMemo::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->get();
        foreach ($removeDMs as $removeDM) {
            $reference_id = $removeDM->reference_id;
            $removeDM->delete();
            $this->remove_accountings($reference_id);
        }
    }

    function remove_accountings($reference_id) {
        $remove_accountings = \App\Accounting::where('reference_id', $reference_id)->get();
        foreach ($remove_accountings as $accounting) {
            $accounting->delete();
        }
    }

}
