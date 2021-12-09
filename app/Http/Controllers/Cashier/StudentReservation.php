<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;

class StudentReservation extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
     function reservation($idno){
        if(Auth::user()->accesslevel==env("CASHIER")){
        $user = \App\User::where('idno',$idno)->first();
        $reservations = \App\Reservation::where('idno',$idno)->where('reservation_type','1')->orderBy('transaction_date')->get();
        $deposits = \App\Reservation::where('idno',$idno)->where('reservation_type','2')->orderBy('transaction_date')->get();
        $receipt_no = StudentLedger::getreceipt();
        $ending_receipt_number=  StudentLedger::getending_receipt();
        $total_other=0.00;
        if($receipt_no<=$ending_receipt_number){
            $check_or = \App\Payment::where('receipt_no', $receipt_no)->get();
                if(count($check_or)>0){
                    return view('cashier.ORDuplicate')->with('receipt_number',$receipt_no);
                }else{
        return view('cashier.reservation',compact('user','reservations','deposits','receipt_no'));
                }
        }else{
                return view('cashier.ORUsed');
        }
        } 
    }
    
     function postreservation(Request $request){
        if(Auth::user()->accesslevel==env("CASHIER")){ 
        DB::beginTransaction();
        $reference_id = uniqid();
        $this->postPayment($request,$reference_id);
        $this->postDeposit($request, $reference_id);
        $this->postAccounting($request, $reference_id);
        $this->postCashDebit($request, $reference_id);
        StudentLedger::updatereceipt();
        \App\Http\Controllers\Admin\Logs::log("Place reservation/student deposit to - $request->idno with reference id:$reference_id.");
        DB::commit();
        return redirect(url('/cashier',array('viewreceipt',$reference_id)));
        
        }  
    }
    
    function postAccounting($request, $reference_id){
        $fiscal_year= \App\CtrFiscalYear::first()->fiscal_year;
        $dept=  \App\CtrAcademicProgram::where('level',$request->level)->first();
        if(count($dept)>0){
        $department = $dept->department;
        } else {
        $department="None";    
        }
        if($request->reservation != ""){
        $addaccounting = new \App\Accounting;
        $addaccounting->transaction_date=$request->date;
        $addaccounting->reference_id=$reference_id;
        $addaccounting->accounting_type=1;
        $addaccounting->category="Reservation";
        $addaccounting->subsidiary=$request->idno;
        $addaccounting->receipt_details="Reservation";
        $addaccounting->particular="Reservation";
        $addaccounting->accounting_code=env("RESERVATION_CODE");
        $addaccounting->accounting_name=env("RESERVATION_NAME");
        $addaccounting->department=$department;
        $addaccounting->fiscal_year=$fiscal_year;
        $addaccounting->credit=$request->reservation;
        $addaccounting->posted_by=Auth::user()->idno;
        $addaccounting->save();
        }
        
        if($request->deposit != ""){
        $addaccounting = new \App\Accounting;
        $addaccounting->transaction_date=$request->date;
        $addaccounting->reference_id=$reference_id;
        $addaccounting->accounting_type=1;
        $addaccounting->category="Student Deposit";
        $addaccounting->subsidiary=$request->idno;
        $addaccounting->receipt_details="Student Deposit";
        $addaccounting->particular="Student Deposit";
        $addaccounting->department=$department;
        $addaccounting->accounting_code=env("STUDENT_DEPOSIT_CODE");
        $addaccounting->accounting_name=env("STUDENT_DEPOSIT_NAME");
        $addaccounting->fiscal_year=$fiscal_year;
        $addaccounting->credit=$request->deposit;
        $addaccounting->posted_by=Auth::user()->idno;
        $addaccounting->save();
        }
        
        
    }
    
    function postDeposit($request,$reference_id){
        if($request->reservation != ""){
        $addreservation = new \App\Reservation;
        $addreservation->idno=$request->idno;
        $addreservation->reference_id=$reference_id;
        $addreservation->transaction_date=$request->date;
        $addreservation->amount=$request->reservation;
        $addreservation->reservation_type=1;
        $addreservation->posted_by=Auth::user()->idno;
        $addreservation->save();
        }
        if($request->deposit != ""){
        $addreservation = new \App\Reservation;
        $addreservation->idno=$request->idno;
        $addreservation->reference_id=$reference_id;
        $addreservation->transaction_date=$request->date;
        $addreservation->amount=$request->deposit;
        $addreservation->reservation_type=2;
        $addreservation->posted_by=Auth::user()->idno;
        $addreservation->save();
        }
    }
    public static function postPayment($request,$reference_id){
        $remarks="";
        $paidby = \App\User::where('idno',$request->idno)->first();
        $status = \App\Status::where('idno',$request->idno)->first();
        
        $adddpayment = new \App\Payment;
        if(count($status)>0){
            if($status->status <= env("ASSESSED")){
                
            }
                if($status->academic_type=="College"){
                   $level= \App\Status::where('idno',$request->idno)->where('school_year',$status->school_year)->where('period',$status->period)->first(); 
                   $adddpayment->program_code = $level->program_code;
                   $adddpayment->level = $level->level;
                   
                } else {
                    $level = \App\Status::where('idno',$request->idno)->where('school_year',$status->school_year)->where('period',$status->period)->first(); 
                    $adddpayment->level = $level->level;
                    $adddpayment->section = $level->section;
                }
            }
        $adddpayment->transaction_date = $request->date;
        $adddpayment->receipt_no=  StudentLedger::getreceipt();
        $adddpayment->reference_id=$reference_id;
        $adddpayment->idno=$request->idno;
        $adddpayment->paid_by=$paidby->lastname .", " . $paidby->firstname;
        if($request->check_amount != ""){
            $adddpayment->check_number = $request->check_number;
            $adddpayment->bank_name = $request->bank;
            $adddpayment->check_amount = $request->check_amount;
        }
        if($request->cash_receive != ""){
            $adddpayment->amount_received = $request->cash_receive;
            $adddpayment->cash_amount = $request->cash_receive - $request->change;
        }
        if($request->credit_card_amount != ""){
            $adddpayment->credit_card_bank=$request->credit_card_bank;
            $adddpayment->credit_card_type=$request->credit_card_type;
            $adddpayment->credit_card_number=$request->card_number;
            $adddpayment->approval_number=$request->approval_number;
            $adddpayment->credit_card_amount=$request->credit_card_amount;
        }
        if($request->deposit_amount != ""){
            $adddpayment->deposit_reference=$request->deposit_reference;
            $adddpayment->deposit_amount=$request->deposit_amount;
        }
        if(isset($request->reservation)){
        if($request->reservation > 0){
            $remarks = $remarks."Reservation - ";
        }}
        if(isset($request->deposit)){
        if($request->deposit > 0 ){
            $remarks= $remarks . "Student Deposit - ";
        }}
        
        if(isset($request->remark)){
            $remarks=$remarks . $request->remark;
        }
        
        $adddpayment->remarks=$remarks; 
        $adddpayment->posted_by=Auth::user()->idno;
        $sy = \App\CtrEnrollmentSchoolYear::where('academic_type', $status->academic_type)->first()->school_year;
        $pr = \App\CtrEnrollmentSchoolYear::where('academic_type', $status->academic_type)->first()->period;
        $adddpayment->school_year=$sy; 
        $adddpayment->period=$pr; 
        $adddpayment->save();
    }
    
    public static function postCashDebit($request,$reference_id){
        $addaccounting = new \App\Accounting;
        $fiscal_year= \App\CtrFiscalYear::first()->fiscal_year;
        $dept=  \App\CtrAcademicProgram::where('level',$request->level)->first();
        if(count($dept)>0){
        $department = $dept->department;
        } else {
        $department="None";    
        }
        $totalamount=0;
        if($request->cash_receive != ""){
            $totalamount=$totalamount+$request->cash_receive-$request->change;
        }
        if($request->check_amount != ""){
            $totalamount=$totalamount+$request->check_amount;
        }
        if($request->credit_card_amount != ""){
            $totalamount=$totalamount+$request->credit_card_amount;
        }
        if($request->deposit_amount != ""){
            $totalamount=$totalamount+$request->deposit_amount;
        }
        $addaccounting->transaction_date=$request->date;
        $addaccounting->reference_id=$reference_id;
        $addaccounting->accounting_type=1;
        $addaccounting->category="Cash";
        $addaccounting->subsidiary="None";
        $addaccounting->receipt_details="Cash";
        $addaccounting->particular="Cash";
        $addaccounting->accounting_code=env("CASH_CODE");
        $addaccounting->accounting_name=env("CASH_NAME");
        $addaccounting->department=$department;
        $addaccounting->fiscal_year=$fiscal_year;
        $addaccounting->debit=$totalamount;
        $addaccounting->posted_by=Auth::user()->idno;
        $addaccounting->save();
        
    }
}
