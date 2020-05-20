<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;

class Reservations extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ADMISSION_HED")) {
            return view('accounting.reservations');
        }
    }
    
    function print_reservationsPDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD') || Auth::user()->accesslevel == env("ADMISSION_HED")) {

            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if($department == "Senior High School" || $department == "College Department"){
                if($period == "1st Semester"){
                $school_year2 = $school_year - 1;
                    $period2 = "2nd Semester";
                    $period3 = "Summer";
                }else if($period == "2nd Semester"){
                $school_year2 = $school_year;
                    $period2 = "1st Semester";
                    $period3 = "1st Semester";
                }else if($period == "Summer"){
                $school_year2 = $school_year;
                    $period2 = "2nd Semester";
                    $period3 = "2nd Semester";
                }else{
                    $period2 = $period;
                    $period3 = $period2;
                }
            }else{
                $school_year2 = $school_year - 1;
            }
            
            if ($department == "Senior High School") {
                $lists = \App\Reservation::selectRaw("users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else if ($department == "College Department") {
                $lists = \App\Reservation::selectRaw("users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount, statuses.program_code")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else {
                $dep = $department;
                $lists = \App\Reservation::selectRaw("users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->orderBy('statuses.level', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            }
            if($school_year == 2018){
                if ($department == "Senior High School") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else if ($department == "College Department") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else {
                    $dep = $department;
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                }
            }
             \App\Http\Controllers\Admin\Logs::log("Print Unused Reservations for $school_year $period PDF");
            $pdf = PDF::loadView('accounting.print_reservations_pdf', compact('department','school_year','period','lists','heads'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("unused_reservations.pdf");
        }
        
    }
    
    function print_reservationsEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')  || Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if($department == "Senior High School" || $department == "College Department"){
                if($period == "1st Semester"){
                $school_year2 = $school_year - 1;
                    $period2 = "2nd Semester";
                    $period3 = "Summer";
                }else if($period == "2nd Semester"){
                $school_year2 = $school_year;
                    $period2 = "1st Semester";
                    $period3 = "1st Semester";
                }else if($period == "Summer"){
                $school_year2 = $school_year;
                    $period2 = "2nd Semester";
                    $period3 = "2nd Semester";
                }else{
                    $period2 = $period;
                    $period3 = $period2;
                }
            }else{
                $school_year2 = $school_year - 1;
            }
            
            if ($department == "Senior High School") {
                $lists = \App\Reservation::selectRaw("users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else if ($department == "College Department") {
                $lists = \App\Reservation::selectRaw("users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount, statuses.program_code")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else {
                $dep = $department;
                $lists = \App\Reservation::selectRaw("users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->orderBy('statuses.level', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            }
            if($school_year == 2018){
                if ($department == "Senior High School") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else if ($department == "College Department") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else {
                    $dep = $department;
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                }
            }
            
             \App\Http\Controllers\Admin\Logs::log("Download Unused Reservations for $school_year $period Excel");
            
            ob_end_clean();
            Excel::create('Unused Reservations', 
                function($excel) use ($department,$school_year,$period,$lists,$heads) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$school_year,$period, $lists,$heads) {
                    $sheet->loadView('accounting.print_reservations_excel', compact('department','school_year','period','lists','heads'));
                    });
                })->download('xlsx');
            
        }
        
    }
    
    function getReservations($idno, $lastname,$firstname,$middlename, $level, $transaction_date, $amount){
        $array2 = array();
        $array2['idno'] = $idno;
        $array2['lastname'] = $lastname;
        $array2['firstname'] = $firstname;
        $array2['middlename'] = $middlename;
        $array2['level'] = $level;
        $array2['transaction_date'] = $transaction_date;
        $array2['amount'] = $amount;
        
        return $array2;
    }

    function index2() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")  || Auth::user()->accesslevel == env("ADMISSION_HED")) {
            $reservation = \App\Reservation::where('reservation_type', 1)->where('is_consumed', 0)->join('users', 'users.idno', '=', 'reservations.idno')->join('statuses', 'statuses.idno', '=', 'reservations.idno')->orderBy('statuses.level', 'asc')->orderBy('users.lastname', 'asc')->orderBy('reservations.transaction_date', 'asc')->get();
            
            $reservations = collect();
            foreach ($reservation as $reserve){
                $reservations->push($this->getReservations($reserve->idno, $reserve->lastname, $reserve->firstname, $reserve->middlename, $reserve->level, $reserve->transaction_date, $reserve->amount));
            }
            
            \App\Http\Controllers\Admin\Logs::log("Print reservation report");
            $pdf = PDF::loadView('accounting.print_reservations', compact('reservations'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("reservations_report.pdf");
        }
    }
    
    function tag_as_used($school_year,$reference_id){
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {            
            $reservation = \App\Reservation::where('reference_id', $reference_id)->first();
            if($reservation->is_consumed == 0){
                $reservation->is_consumed = 2;
                $reservation->save();
            \App\Http\Controllers\Admin\Logs::log("Reservation/Student Deposit: $reference_id tagged as used.");
            }
        }
        return redirect('cashier/viewledger/'.$school_year.'/'. $reservation->idno);
    }

}
