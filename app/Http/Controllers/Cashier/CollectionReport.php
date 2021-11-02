<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class CollectionReport extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function collection($date_from, $date_to, $posted_by) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', Auth::user()->idno)->get();
            $credits = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', Auth::user()->idno)->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $debits = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', Auth::user()->idno)->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $credits_summary = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                            ->where('posted_by', Auth::user()->idno)->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $debits_summary = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                            ->where('posted_by', Auth::user()->idno)->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $debits_summary_less = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 0)
                            ->where("receipt_details",'!=','Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
        }

        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
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
        }
        return view('cashier.collection_report', compact('payments', 'date_from', 'date_to', 'debits', 'credits', 'posted_by', 'debits_summary', 'credits_summary','debits_summary_less'));
    }

    function list_of_checks($date_from, $date_to) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', Auth::user()->idno)->where('check_amount', '>', '0')->get();
        }

        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->orderBy('posted_by')->where('check_amount', '>', '0')->get();
        }

        return view('cashier.list_of_checks', compact('payments', 'date_from', 'date_to'));
    }

    function credit_cards($date_from, $date_to) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', Auth::user()->idno)->where('credit_card_amount', '>', '0')->get();
        }
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', "!=", "Paynamics")
                            ->orderBy('posted_by')->where('credit_card_amount', '>', '0')->get();
        }

        return view('cashier.credit_cards', compact('payments', 'date_from', 'date_to'));
    }

    function bank_deposits($date_from, $date_to) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->where('posted_by', Auth::user()->idno)->where('deposit_amount', '>', '0')->get();
        }
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))
                            ->orderBy('posted_by')->where('deposit_amount', '>', '0')->get();
        }

        return view('cashier.bank_deposits', compact('payments', 'date_from', 'date_to'));
    }

    function set_receipt() {
        $idno = Auth::user()->idno;
        return view('cashier.set_receipt', compact('idno'));
    }

    function deposit_slip($transaction_date) {
        $payments = \App\Payment::where('posted_by', Auth::user()->idno)
                ->where('transaction_date', $transaction_date)
                ->where('is_reverse', '0')
                ->selectRaw('sum(cash_amount) as cash_amount, '
                        . 'sum(check_amount) as check_amount, '
                        . 'sum(deposit_amount) as deposit_amount, '
                        . 'sum(credit_card_amount) as credit_card_amount')
                ->first();

        $deposit_cash = \App\DepositSlip::where('idno', Auth::user()->idno)
                ->where('transaction_date', $transaction_date)->where('deposit_type', '0')
                ->get();
        $deposit_check = \App\DepositSlip::where('idno', Auth::user()->idno)
                ->where('transaction_date', $transaction_date)->where('deposit_type', '1')
                ->get();

        //$total_deposit = $deposits->selectRaw('sum(amount) as amount');
        //return $payments;
        return view('cashier.deposit_slip', compact('payments', 'deposit_cash', 'transaction_date', 'deposit_check'));
    }

    function post_deposit_slip(Request $request) {
        $adddeposit = new \App\DepositSlip;
        $adddeposit->idno = Auth::user()->idno;
        $adddeposit->transaction_date = date('Y-m-d');
        $adddeposit->deposit_amount = $request->amount;
        $adddeposit->deposit_type = $request->deposit_type;
        $adddeposit->particular = $request->particular;
        $adddeposit->save();
        return redirect(url('/cashier', array('deposit_slip', date('Y-m-d'))));
    }

    function remove_deposit($id) {
        $find = \App\DepositSlip::find($id);
        if ($find->idno == Auth::user()->idno) {
            $find->delete();
        }
        return redirect(url('/cashier', array('deposit_slip', date('Y-m-d'))));
    }

    function cancelled_or($date_from, $date_to, $posted_by) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $payments = \App\Payment::whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                            ->where('posted_by', Auth::user()->idno)->get();
            $credits = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                            ->where('posted_by', Auth::user()->idno)->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $debits = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                            ->where('posted_by', Auth::user()->idno)->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $credits_summary = \App\Accounting::selectRaw('sum(credit) as credit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                            ->where('posted_by', Auth::user()->idno)->where('credit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $debits_summary = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                            ->where('posted_by', Auth::user()->idno)->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
            $debits_summary_less = \App\Accounting::selectRaw('sum(debit) as debit, receipt_details')->whereBetween('transaction_date', array($date_from, $date_to))->where('is_reverse', 1)
                            ->where("receipt_details",'!=','Cash')->where('debit', '>', '0')->where('accounting_type', '1')->groupBy('receipt_details')->get();
        }

        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
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
        }
        return view('cashier.cancelled_or', compact('payments', 'date_from', 'date_to', 'debits', 'credits', 'posted_by', 'debits_summary', 'credits_summary','debits_summary_less'));
    }

}
