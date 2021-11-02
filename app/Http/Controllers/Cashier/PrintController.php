<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;
use App\Http\Controllers\Cashier\StudentReservation;
use PDF;

class PrintController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    function printreceipt ($reference_id){
        $payment= \App\Payment::where('reference_id',$reference_id)->first();
        $status=  \App\Status::where('idno',$payment->idno)->first();
        $accountings = \App\Accounting::where('reference_id',$reference_id)->where('accounting_type','1')->get();
        $receipt_details = DB::Select("Select receipt_details, sum(credit) as credit from accountings where reference_id = "
                . "'$reference_id' and credit > '0' and accounting_type = '1' group by reference_id");
        $receipt_less = DB::Select("Select receipt_details, sum(debit) as debit from accountings where reference_id = "
                . "'$reference_id' and receipt_details != 'Cash' and debit > '0' and accounting_type='1'  group by receipt_details, reference_id");
        
        
        $pdf = PDF::loadView('cashier.print_receipt', compact('payment','status','accountings','receipt_details','receipt_less'));
        $pdf->setPaper(array(0, 0, 380.00, 468.0));
        return $pdf->stream();
    }
    
    function print_collection_report($date_from,$date_to,$posted_by){
        if ($posted_by == "all") {
                $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                                ->orderBy('posted_by')->get();
                $credits = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))
                                ->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))
                                ->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $credits_summary = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                                ->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                                ->where("receipt_details",'Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary_less = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                                ->where("receipt_details",'!=','Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            } else {
                $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                                ->where('posted_by', $posted_by)->get();
                $credits = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))
                                ->where('posted_by', $posted_by)->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))
                                ->where('posted_by', $posted_by)->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $credits_summary = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                                ->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                                ->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary_less = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                                ->where("receipt_details",'!=','Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            }
         $pdf=PDF::loadview('cashier.print_collection_report',compact('payments','date_from','date_to','credits','debits','posted_by','credits_summary','debits_summary','debits_summary_less'));
         $pdf->setPaper('legal','landscape');
         return $pdf->stream();
    }
    
    function print_list_of_checks($date_from,$date_to){
        if(Auth::user()->accesslevel==env("CASHIER")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('posted_by',Auth::user()->idno)->where('check_amount','>','0')
                    ->where('is_reverse','0')->get();
            $pdf=PDF::loadView('cashier.print_list_of_checks',compact('payments','date_from','date_to'));
            $pdf->setPaper('legal','landscape');
            return $pdf->stream();        
        }
        if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('check_amount','>','0')
                    ->where('is_reverse','0')->get();
            $pdf=PDF::loadView('cashier.print_list_of_checks',compact('payments','date_from','date_to'));
            $pdf->setPaper('legal','landscape');
            return $pdf->stream();        
        }
        
    }
    function print_credit_cards($date_from, $date_to){
         if(Auth::user()->accesslevel==env("CASHIER")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('posted_by',Auth::user()->idno)->where('credit_card_amount','>','0')->get();
            $pdf=PDF::loadView('cashier.print_credit_cards',compact('payments','date_from','date_to'));
            $pdf->setPaper('legal','landscape');
            return $pdf->stream(); 
         }
         if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->orderBy('posted_by')->where('credit_card_amount','>','0')->get();
            $pdf=PDF::loadView('cashier.print_credit_cards',compact('payments','date_from','date_to'));
            $pdf->setPaper('legal','landscape');
            return $pdf->stream(); 
         }
        
        
    }
    function print_bank_deposits($date_from, $date_to){
         if(Auth::user()->accesslevel==env("CASHIER")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->where('posted_by',Auth::user()->idno)->where('deposit_amount','>','0')->get();
            $pdf=PDF::loadView('cashier.print_bank_deposits',compact('payments','date_from','date_to'));
            $pdf->setPaper('legal','landscape');
            return $pdf->stream(); 
         }  
         if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")){
            $payments = \App\Payment::whereBetween('transaction_date',array($date_from,$date_to))
                    ->orderBy('posted_by')->where('deposit_amount','>','0')->get();
            $pdf=PDF::loadView('cashier.print_bank_deposits',compact('payments','date_from','date_to'));
            $pdf->setPaper('legal','landscape');
            return $pdf->stream(); 
         }
        
    }
    function print_online_payments($date_from, $date_to){
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', "Paynamics")->where('credit_card_amount', '>', '0')->get();
            $pdf=PDF::loadView('cashier.print_online_payments',compact('payments','date_from','date_to'));
            $pdf->setPaper('legal','landscape');
            return $pdf->stream(); 
        
    }
    
    function print_cancelled_or ($date_from,$date_to,$posted_by){
        if ($posted_by == "all") {
                $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->orderBy('posted_by')->get();
                $credits = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $credits_summary = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where("receipt_details",'Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary_less = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where("receipt_details",'!=','Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            } else {
                $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('posted_by', $posted_by)->get();
                $credits = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('posted_by', $posted_by)->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('posted_by', $posted_by)->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $credits_summary = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
                $debits_summary_less = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                                ->where("receipt_details",'!=','Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            }
         $pdf=PDF::loadview('cashier.print_cancelled_or',compact('payments','date_from','date_to','credits','debits','posted_by','credits_summary','debits_summary','debits_summary_less'));
         $pdf->setPaper('legal','landscape');
         return $pdf->stream();
    }
}
