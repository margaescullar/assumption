<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\MainPayment;

class Assess extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function assess($idno){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $user=  \App\User::where('idno',$idno)->first();
            if($user->academic_type=="BED"){
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type','BED')->first()->school_year;
                $status = \App\Status::where('idno',$idno)->first();
                $ledgers = \App\Ledger::where('idno',$idno)->where('category_switch','<=',env("TUITION_FEE"))->get();
                $level = \App\BedLevel::where('idno',$idno)->where('school_year',$school_year)->first();
                if(count($status)>0){
                    if($status->status < env("ASSESSED")){
                    return view('reg_be.assess',compact('user','status','ledgers','level'));    
                    } else {
                    return view('reg_be.assessed_enrolled',  compact('idno'));
                    }
                } 
                
                }
                    
            }
        }
    
    
    function post_assess(Request $request){
        $validation = $this->validate($request,[
                'plan' => 'required',
            ]);
        if($validation){
        if(Auth::user()->accesslevel == env("REG_BE")){
            $idno=$request->idno;
            $user = \App\User::where("idno",$request->idno)->first();
                if($user->academic_type == "BED"){
                    $status = \App\Status::where('idno',$request->idno)->first();
                    
                    if($status->status == 0 ){
                        
                        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type',"BED")->first();
                        DB::beginTransaction();
                        $this->addGrades($request, $schoolyear->school_year);
                        $this->addLedger($request, $schoolyear->school_year);
                        $this->addOtherCollection($request, $schoolyear->school_year);
                        $this->addOptionalFee($request);
                        $this->addSRF($request,$schoolyear->school_year);
                        $this->addDueDates($request,$schoolyear->school_year);
                        $this->modifyStatus($request,$schoolyear->school_year);
                        $this->checkReservations($request, $schoolyear->school_year);
                        
                        //$this->addBooks($request,$schoolyear);
                        DB::commit();
                        return redirect(url('/bedregistrar',array('assess',$idno)));
                        //return view(url('begregistrar',array('viewregistration',$request->idno)));
                        
                        //return $request;
                        }
                         
                    else if($status->status >= env('ASSESSED')){
                        return view(url('begregistrar',array('viewregistration',$request->idno)));
                    }
                else{
                    view('unauthorized');
                }    
            }  
            
        }}
    }
    function changeStatus($id){
        
    }
    function addGrades($request, $schoolyear){
        if($request->level=="Grade 11" || $request->level=="Grade 12"){
        $subjects = \App\BedCurriculum::where('level',$request->level)->where('strand',$request->strand)->get();
        } else {
        $subjects = \App\BedCurriculum::where('level',$request->level)->get();    
        }
       if(count($subjects)>0){
            foreach($subjects as $subject){
                $addsubject = new \App\GradeBasicEd;
                $addsubject->idno = $request->idno;
                $addsubject->school_year = $schoolyear;
                $addsubject->strand = $subject->strand;
                $addsubject->level = $request->level;
                $addsubject->subject_code = $subject->subject_code;
                $addsubject->subject_name = $subject->subject_name;
                $addsubject->group_name = $subject->group_name;
                $addsubject->units = $subject->units;
                $addsubject->display_subject_code = $subject->display_subject_code;
                $addsubject->weighted = $subject->weighted;
                $addsubject->encoded_by = Auth::user()->idno;
                $addsubject->save();
            }
                    
        }
        
    }
    
    function addLedger($request,$schoolyear){
        $discount_code=0;
        $discount_description="";
        $discount_tuition=0;
        $discount_other=0;
        $discount_depository=0;
        $discount_misc=0;
        $discount_srf=0;
        $discount = \App\CtrDiscount::where('discount_code',$request->discount)->first();
        if(count($discount)>0){
        $discount_code=$discount->discount_code;
        $discount_description=$discount->discount_description;
        $discount_tuition=$discount->tuition_fee;
        $discount_other=$discount->other_fee;
        $discount_depository=$discount->depository_fee;
        $discount_misc=$discount->misc_fee;
        }
        $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
        $fees = \App\CtrBedFee::where('level',$request->level)->get();
        if(count($fees)>0){
            foreach($fees as $fee){
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if($request->level=="Grade 11" || $request->level=="Grade 12"){
                $addledger->strand = $request->strand;    
                }
                $addledger->school_year=$schoolyear;
                $addledger->category=$fee->category;
                $addledger->subsidiary=$fee->subsidiary;
                $addledger->receipt_details = $fee->receipt_details;
                $addledger->accounting_code=$fee->accounting_code;
                $addledger->accounting_name =$this->getAccountingName($fee->accounting_code);
                $addledger->category_switch=$fee->category_switch;
              
                $amount = $fee->amount;
                $discount_amount=0;
                switch ($fee->category_switch){
                    case env("MISC_FEE"):
                        $amount = $fee->amount;
                        $discount_amount = $fee->amount * $discount_misc/100;
                        break;
                    case env("OTHER_FEE"):
                        $amount=$fee->amount;
                        $discount_amount = $fee->amount * $discount_other/100;
                        break;
                    case env("DEPOSITORY_FEE"):
                        $amount=$fee->amount;
                        $discount_amount = $fee->amount * $discount_depository/100;
                        break;
                    case env("TUITION_FEE"):
                        $addpercent = $this->addPercentage($request->plan);
                        $amount = ($fee->amount + ($fee->amount * $addpercent/100));
                        $discount_amount = $amount * $discount_tuition/100;
                }
                
                $addledger->amount = $amount;
                $addledger->discount_code = $discount_code;
                $addledger->discount = $discount_amount;
                $addledger->save();
            }
        }
        
        
    }
    
    function enrollment_statistics($school_year){
        $kinder = \App\BedLevel::selectRaw("level,section,count(*)as count")
                ->whereRaw("school_year=$school_year AND level='Kinder'")->groupBy('level','section');
        
        $statistics = \App\BedLevel::selectRaw("level, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND status='3'")->groupBy('level','section')
                ->orderBy('level','section')->get();
        
        $abm =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'ABM' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $humms=\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'HUMMS' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $stem =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'STEM' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
      
        return view('reg_be.enrollment_statistics',compact('statistics','abm','humms','stem','school_year','kinder'));
    }
    
    function addSRF($request,$schoolyear){
        if($request->level == "Grade 11" || $request->level == "Grade 12"){
             $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
            $srf = \App\CtrBedSrf::where('level',$request->level)->where('strand',$request->strand)->first();
            if(count($srf)>0){
                $add = new \App\Ledger;
                $add->idno = $request->idno;
                $add->department = $department->department;
                $add->strand = $request->strand;
                $add->level = $request->level;
                $add->school_year = $schoolyear;
                $add->category = $srf->category;
                $add->subsidiary = $srf->subsidiary;
                $add->receipt_details = $srf->receipt_details;
                $add->accounting_code = $srf->accounting_code;
                $add->category_switch = $srf->category_switch;
                $add->accounting_name = $this->getAccountingName($srf->accounting_code);
                $add->amount=$srf->amount;
                $add->save();
            }
        }
        
    }
    
    function addOptionalFee($request){
    if(count($request->qty_books)>0){    
        $this->processOptional($request->qty_books,$request,'books');
    }
    if(count($request->qty_materials)>0){
        $this->processOptional($request->qty_materials,$request,'materials');
    }
    if(count($request->qty_other_materials)>0){
        $this->processOptional($request->qty_other_materials,$request,'other_materials');
    }
    if(count($request->qty_pe_uniforms)>0){
        $this->processOptional($request->qty_pe_uniforms,$request,'pe_uniform');
    }
    $this->processUniform($request, $request->tshirt_qty, $request->tshirt_size);
    $this->processUniform($request, $request->jogging_qty, $request->jogging_size);
    $this->processUniform($request, $request->socks_qty, $request->socks_size);
    $this->processUniform($request, $request->dengue_qty, $request->dengue_size);
    }
    
    function addPercentage($plan){
        switch ($plan){
            case "Annual":
                return 0;
                break;
            case "Semestral":
                return 1;
                break;
            case "Quarterly":
                return 2;
                break;
            case "Monthly":
                return 3;
                break;
        } 
    }
    
    function getAccountingName($accounting_code){
       $accounting_name =  \App\ChartOfAccount::where('accounting_code',$accounting_code)->first();
       if(count($accounting_name)>0){
           return $accounting_name->accounting_name;
       } else {
           return "Not Found in Chart of Account";
       }
    }
    
    function processUniform($request, $qty , $size){
        $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type','BED')->first();
        if($size != ""){
            $tshirt = \App\CtrUniformSize::find($size);
            $amount = $qty * $tshirt->amount;
            if($amount > 0){
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                $addledger->school_year=$schoolyear->school_year;
                $addledger->category=$tshirt->category;
                $addledger->subsidiary=$tshirt->subsidiary;
                $addledger->receipt_details = $tshirt->receipt_details;
                $addledger->accounting_code=$tshirt->accounting_code;
                $addledger->accounting_name =$this->getAccountingName($tshirt->accounting_code);
                $addledger->category_switch=$tshirt->category_switch;
                $addledger->amount = $amount;
                $addledger->qty = $qty;
                $addledger->save();
            }
        }
    }
    
    function processOptional($optional,$request,$material){
        $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
        $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type','BED')->first();
        foreach($optional as $key=>$value){
            
        $item = \App\CtrOptionalFee::find($key);
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                $addledger->school_year=$schoolyear->school_year;
                $addledger->category=$item->category;
                $addledger->subsidiary=$item->subsidiary;
                $addledger->receipt_details = $item->receipt_details;
                $addledger->accounting_code=$item->accounting_code;
                $addledger->accounting_name =$this->getAccountingName($item->accounting_code);
                $addledger->category_switch=$item->category_switch;
                $addledger->amount = $item->amount;
                $addledger->qty = '1';
                $addledger->save();
        
                  
        }  
    }
    
  function addDueDates($request,$schoolyear){
      if($request->plan=="Annual"){
          $total = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno',$request->idno)
                  ->where('category_switch','<=',env("TUITION_FEE"))->groupBy('idno')->first();
          $addduedate = new \App\LedgerDueDate;
          $addduedate->idno = $request->idno;
          $addduedate->school_year = $schoolyear;
          $addduedate->due_date = Date('Y-m-d');
          $addduedate->due_switch = 0;
          $addduedate->amount = $total->total;
          $addduedate->save();
      } else { 
          $duedates = \App\CtrDueDateBed::where('plan',$request->plan)->get();
          $count = count($duedates)+1;
          $duetuition = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno',$request->idno)
                  ->where('category_switch',env('TUITION_FEE'))->groupBy('idno')->first();
          $dueamount = $duetuition->total/$count;
          
          $dueothers = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno',$request->idno)
                  ->where('category_switch','<',env("TUITION_FEE"))->groupBy('idno')->first();
          $addduedate = new \App\LedgerDueDate;
          $addduedate->idno = $request->idno;
          $addduedate->school_year = $schoolyear;
          $addduedate->due_date = Date('Y-m-d');
          $addduedate->due_switch = 0;
          $addduedate->amount = $dueothers->total + $dueamount;
          $addduedate->save();
          foreach($duedates as $duedate){
          $addduedate = new \App\LedgerDueDate;
          $addduedate->idno = $request->idno;
          $addduedate->school_year = $schoolyear;
          $addduedate->due_date = $duedate->due_date;
          $addduedate->due_switch = 1;
          $addduedate->amount = $dueamount;
          $addduedate->save(); 
          }
      }
      
  }
  
  function modifyStatus($request,$schoolyear){
      $department = \App\CtrAcademicProgram::where('level',$request->level)->first();
      $status = \App\Status::where('idno',$request->idno)->first();
      $status->status = env("ASSESSED");
      $status->level = $request->level;
      if($request->level=="Grade 11" || $request->level=="Grade 12"){
         $status->strand = $request->strand; 
      }
      $status->school_year=$schoolyear;
      $status->section = $request->section;
      $status->department = $department->department;
      $status->date_registered = date('Y-m-d');
      $status->type_of_plan=$request->plan;
      $status->update();
  }
  
  function reassess($idno){
      if(Auth::user()->accesslevel == env("REG_BE")){
          $status = \App\Status::where('idno',$idno)->first();
          $schoolyear = \App\CtrEnrollmentSchoolYear::where('academic_type',"BED")->first();
          if($status->status==env("ASSESSED")){
              DB::beginTransaction();
              $this->removeLedger($idno,$schoolyear->school_year);
              $this->removeLedgerDueDate($idno,$schoolyear->school_year);
              $this->removeGrades($idno,$schoolyear->school_year);
              $this->returnStatus($idno,$schoolyear->school_year);
              DB::commit();
          }
                 
      }
      
      return redirect(url('/bedregistrar',array('assess',$idno)));
  }
  
  function removeLedger($idno,$schoolyear){
    \App\Ledger::where('idno',$idno)->where('category_switch','<=',env("TUITION_FEE"))->where('school_year',$schoolyear)->delete();
  }
  function removeLedgerDueDate($idno,$schoolyear){
      \App\LedgerDueDate::where('idno',$idno)->where('school_year',$schoolyear)->delete();
  }
  function removeGrades($idno,$schoolyear){
      \App\GradeBasicEd::where('idno',$idno)->where('school_year',$schoolyear)->delete();
  }
  function returnStatus($idno,$schoolyear){
      $status =  \App\Status::where('idno',$idno)->first();
      $assignlevel=$status->level;
      switch ($status->level){
          case "Kinder":
              $assignlevel="Pre-Kinder";
              break;
          case "Grade 1":
              $assignlevel="Kinder";
              break;
          case "Grade 2":
              $assignlevel="Grade 1";
              break;
          case "Grade 3":
              $assignlevel="Grade 2";
              break;
          case "Grade 4":
              $assignlevel="Grade 3";
              break;
          case "Grade 5":
              $assignlevel="Grade 4";
              break;
          case "Grade 6":
              $assignlevel="Grade 5";
              break;
          case "Grade 7":
              $assignlevel="Grade 6";
              break;
          case "Grade 8":
              $assignlevel="Grade 7";
              break;
          case "Grade 9":
              $assignlevel="Grade 8";
              break;
          case "Grade 10":
              $assignlevel="Grade 9";
              break;
          case "Grade 11":
              $assignlevel="Grade 10";
              break;
          case "Grade 12":
              $assignlevel="Grade 11";
              break;
      }
      $status->level = $assignlevel;
      $status->status = 0;
      $status->update();
  }
  function checkReservations($request,$school_year) {
        $checkreservations = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $reference_id = uniqid();
            $ledgers = \App\Ledger::where('idno',$request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch','<=',env("TUITION_FEE"))->get();

            MainPayment::addUnrealizedEntry($request, $reference_id);
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers,env("DEBIT_MEMO"));
            $this->postDebit($request, $reference_id, $totalpayment);
            
            $changestatus = \App\Status::where('idno',$request->idno)->first();
            $changestatus->status=env("ENROLLED");
            $changestatus->update();
            $changereservation = \App\Reservation::where('idno',$request->idno)->get();
            if(count($changereservation)>0){
                foreach($changereservation as $change){
                $change->is_consumed = '1';
                $change->consume_sy=$school_year;
                $change->update();
                }
            }
        }
    }

    function postDebit($request,$reference_id){
    $fiscal_year=  \App\CtrFiscalYear::first()->fiscal_year;    
    $reservations = \App\Reservation::where('idno',$request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
    $dept = \App\CtrAcademicProgram::where('level',$request->level)->first();
    $department=$dept->department;//\App\Status::where('idno',$idno)->first()->department;
    $totalReserved = 0;
    if(count($reservations)>0){
        foreach($reservations as $ledger){
                    $addacct=new \App\Accounting;
                    $addacct->transaction_date = date('Y-m-d');
                    $addacct->reference_id=$reference_id;
                    //$addacct->reference_number=$ledger->id;
                    $addacct->accounting_type = env("DEBIT_MEMO");
                    $addacct->subsidiary=$ledger->idno;
                    $department = $dept->department;  
                    $addacct->department=$department;
                    if($ledger->reservation_type==1){
                     $category = "Reservation";
                     $accounting_code = env("RESERVATION_CODE");
                     $accounting_name = env("RESERVATION_NAME");
                    } else if($ledger->reservation_type==2){
                     $category = "Student Deposit";  
                     $accounting_code = env("STUDENT_DEPOSIT_CODE");
                     $accounting_name = env("STUDENT_DEPOSIT_NAME");
                    }
                    $addacct->category=$category;
                    $addacct->receipt_details=$category;
                    $addacct->particular=$category;
                    $addacct->accounting_code= $accounting_code;
                    $addacct->accounting_name=$accounting_name;
                    $addacct->department=$department;
                    $addacct->fiscal_year=$fiscal_year;
                    $addacct->debit=$ledger->amount;
                    $addacct->posted_by=Auth::user()->idno;
                    $addacct->save();
                    $ledger->is_consumed=1;
                    $totalReserved=$totalReserved+$ledger->amount;
            
                   }
            $this->postDebitMemo($request->idno, $reference_id,$totalReserved);
            }
    }
    function postDebitMemo($idno,$reference_id,$totalReserved){
        $school_year=  \App\CtrEnrollmentSchoolYear::where('academic_type','BED')->first();
        $debit_memo = new \App\DebitMemo;
        $debit_memo->idno = $idno;
        $debit_memo->transaction_date = date("Y-m-d");
        $debit_memo->reference_id=$reference_id;
        $debit_memo->dm_no=$this->getDMNumber();
        $debit_memo->explanation="Reversal of Reservation/Student Deposit";
        $debit_memo->amount=$totalReserved;
        $debit_memo->reservation_sy=$school_year->school_year;
        $debit_memo->posted_by=Auth::user()->idno;
        $debit_memo->save();
    }
        
    function getDMNumber(){
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
    
    function addOtherCollection($request,$schoolyear){
        $adds = \App\OtherCollection::get();
        $dept = \App\CtrAcademicProgram::where('level',$request->level)->first();
        if(count($adds)>0){
            foreach($adds as $add){
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $dept->department;
                $addledger->level = $request->level;
                if($request->level=="Grade 11" || $request->level=="Grade 12"){
                $addledger->strand = $request->strand;    
                }
                $addledger->school_year=$schoolyear;
                $addledger->category=$add->category;
                $addledger->subsidiary=$add->subsidiary;
                $addledger->receipt_details = $add->receipt_details;
                $addledger->accounting_code=$add->accounting_code;
                $addledger->accounting_name =$this->getAccountingName($add->accounting_code);
                $addledger->category_switch=$add->category_switch;
                $disc_other = $this->getOtherDiscount($request->idno, $add->subsidiary);
                $addledger->amount = $add->amount - $disc_other;
                $addledger->save();
            }
        }
    }
    
    function getOtherDiscount($idno,$subsidiary){
        $disc = \App\DiscountCollection::where('idno',$idno)->where('subsidiary',$subsidiary)->first();
        if(count($disc)>0){
            return $disc->discount_amount;
        } else {
            return 0;
        }
    }
    
}
