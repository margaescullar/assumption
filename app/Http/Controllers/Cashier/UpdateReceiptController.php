<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class UpdateReceiptController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($reference_id) {
        if (Auth::user()->accesslevel == env("CASHIER") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $payment = \App\Payment::where('reference_id', $reference_id)->first();
            if($payment->idno != 999999){
                if(count($payment->accounting->where('reference_number',"!=",null))>0){
                    //main payment
                    $ledger_particulars = \App\Ledger::where('idno', $payment->idno)->get();
                    $ledger_school_years = \App\Ledger::where('idno', $payment->idno)->groupBy('school_year')->get(['school_year']);
                    return view('cashier.update_receipt.index', compact('reference_id', 'payment', 'ledger_particulars', 'ledger_school_years'));
                }else{
                    //other payment
                    //same process as non student payment
                    //no separate function
                    $particulars = \App\OtherPayment::get();
                    $accounting_particulars = \App\Accounting::where('reference_id', $reference_id)->where('debit',0)->get();
                    return view('cashier.update_receipt.index_nonstudent', compact('reference_id', 'payment','accounting_particulars','particulars'));
                }
            }else{
                //non student payment
                $particulars = \App\OtherPayment::get();
                $accounting_particulars = \App\Accounting::where('reference_id', $reference_id)->where('debit',0)->get();
                return view('cashier.update_receipt.index_nonstudent', compact('reference_id', 'payment','accounting_particulars','particulars'));
            }
        }
    }

    function update(Request $request) {
//        return $request;
        DB::beginTransaction();
        $this->update_payment($request);
        $this->reverse_ledger($request->reference_id, env("CASH"));
        $this->postAccounting($request);
        $this->postCashDebit($request);
        DB::commit();
        
        \App\Http\Controllers\Admin\Logs::log("Update Receipt Details of $request->reference_id");
        return redirect(url('/cashier',array('viewreceipt',$request->reference_id)));
    }

    function update_payment($request) {
        $update_payment = \App\Payment::where('reference_id', $request->reference_id)->first();
        $update_payment->receipt_no = $request->receipt_no;
        $update_payment->amount_received = $request->amount_received;
        $update_payment->cash_amount = $request->amount_received-$request->change;
        $update_payment->credit_card_bank = $request->credit_card_bank;
        $update_payment->credit_card_type = $request->credit_card_type;
        $update_payment->credit_card_number = $request->credit_card_number;
        $update_payment->credit_card_amount = $request->credit_card_amount;
        $update_payment->bank_name = $request->bank_name;
        $update_payment->check_number = $request->check_number;
        $update_payment->check_amount = $request->check_amount;
        $update_payment->deposit_reference = $request->deposit_reference;
        $update_payment->deposit_amount = $request->deposit_amount;
        $update_payment->remarks = $request->remarks;
        $update_payment->save();
    }

    function reverse_ledger($reference_id, $entry_type) {
        $accountings = \App\Accounting::where('reference_id', $reference_id)->where('credit', '>', '0')->where('accounting_type', $entry_type)->get();
        if (count($accountings) > 0) {
            foreach ($accountings as $accounting) {
                $ledger = \App\Ledger::find($accounting->reference_number);
                if (count($ledger) > 0) {
                    if ($accounting->is_reverse == 0) {
                        $ledger->payment = $ledger->payment - $accounting->credit;
                    } else {
                        $ledger->payment = $ledger->payment + $accounting->credit;
                    }
                    $ledger->update();
                }
            }
        }
    }

    function postAccounting($request) {
        $remove_accountings = \App\Accounting::where('reference_id', $request->reference_id)->where('accounting_type', env('CASH'))->get();
        
        $this->processAccounting($request, env("CASH"),$remove_accountings);
    }

    public static function processAccounting($request, $accounting_type,$remove_accountings) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;

        if($request->reference_number != null){
            //FOR MAIN PAYMENT
            //
            //remove accountings
            foreach ($remove_accountings as $remove_accounting) {
            $remove_accounting->delete();
            }
            //process accountings
            foreach ($request->reference_number as $reference_number => $value) {
                $ledger = \App\Ledger::where('id', $reference_number)->first();
                $amount = $request->reference_number[$reference_number];
                $ledger->payment = $ledger->payment + $amount;
                $ledger->update();
                if ($amount > 0) {
                    $addacct = new \App\Accounting;
                    $addacct->transaction_date = $request->transaction_date;
                    $addacct->reference_id = $request->reference_id;
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
                    $addacct->posted_by = $request->posted_by;
                    $addacct->save();
                }
            }
        }else{
            //FOR NON STUDENT PAYMENT
            $dept=\App\Status::where('idno',$request->idno)->first();
            if(count($dept)>0){
            $department = $dept->department;
            } else {
            $department="None";    
            }
            //remove accountings
                if($request->particular[0] != "Select Particular"){
                    foreach ($remove_accountings as $remove_accounting) {
                        $remove_accounting->delete();
                    }
                }
            if(count($request->particular)>0 and $request->particular[0] != "Select Particular"){
                
                //process accountings
                for($i=0;$i<count($request->particular);$i++){
                    $addaccounting = new \App\Accounting;
                    $addaccounting->transaction_date=$request->transaction_date;
                    $addaccounting->reference_id=$request->reference_id;
                    $addaccounting->accounting_type=1;
                    $addaccounting->category="Other Payment";
                    $addaccounting->subsidiary=$request->particular[$i];
                    $addaccounting->receipt_details=$request->particular[$i];
                    $addaccounting->particular=$request->particular[$i];
                    $addaccounting->accounting_code=self::getParticularAccounting($request->particular[$i])->accounting_code;
                    $addaccounting->accounting_name=self::getParticularAccounting($request->particular[$i])->accounting_name;
                    $addaccounting->department = $department;
                    $addaccounting->fiscal_year=$fiscal_year;
                    $addaccounting->credit=$request->other_amount[$i];
                    $addaccounting->posted_by=$request->posted_by;
                    $addaccounting->save();
                }
            }
        }
    }
    
    public static function postCashDebit($request){
        $addaccounting = new \App\Accounting;
        $fiscal_year= \App\CtrFiscalYear::first()->fiscal_year;
        $dept=  \App\CtrAcademicProgram::where('level',$request->level)->first();
        if(count($dept)>0){
        $department = $dept->department;
        } else {
        $department="None";    
        }
        $totalamount=0;
        if($request->amount_received != ""){
            $totalamount=$totalamount+$request->amount_received-$request->change;
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
        $addaccounting->transaction_date=$request->transaction_date;
        $addaccounting->reference_id=$request->reference_id;
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
        $addaccounting->posted_by=$request->posted_by;
        $addaccounting->save();
        
    }

    function update_nonstudent(Request $request) {
//        return $request;
        DB::beginTransaction();
        $this->update_payment($request);
        $this->postAccounting($request);
        $this->postCashDebit($request);
        DB::commit();
        
        \App\Http\Controllers\Admin\Logs::log("Update Receipt Details of $request->reference_id");
        return redirect(url('/cashier',array('viewreceipt',$request->reference_id)));
    }
    
    public static function getParticularAccounting($subsidiary){
        return \App\OtherPayment::where('subsidiary',$subsidiary)->first();
    }

}
