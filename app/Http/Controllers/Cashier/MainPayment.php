<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Cashier\StudentLedger;
use App\Http\Controllers\Cashier\StudentReservation;
use Mail;

class MainPayment extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function main_payment($idno) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $user = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            $receipt_number = StudentLedger::getreceipt();
            $ending_receipt_number = StudentLedger::getending_receipt();
            $total_other = 0.00;
            if ($receipt_number <= $ending_receipt_number) {
                $check_or = \App\Payment::where('receipt_no', $receipt_number)->get();
                if(count($check_or)>0){
                    return view('cashier.ORDuplicate')->with('receipt_number',$receipt_number);
                }else{
                //Other Fee Total
                $other_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_FEE"))
                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                        ->first();
                //Miscellaneous Fee Total
                $miscellaneous_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("MISC_FEE"))
                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                        ->first();
                ///Depository Fee Total
                $depository_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("DEPOSITORY_FEE"))
                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                        ->first();
                //Subject Related Fee Total
                $srf_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("SRF_FEE"))
                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                        ->first();

                //Tuion Fee Total
                $tuition_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("TUITION_FEE"))
                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                        ->first();
                //Optional Fee Total
                $optional_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("OPTIONAL_FEE"))
                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                        ->first();
                //Previous Balances
//                $previous_total = \App\Ledger::where('idno', $idno)->where('category_switch', '>=', '10')
//                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
//                        ->first();
                
                $previous_total = \App\Ledger::where('idno', $idno)->where('category_switch', '>=', '10')
                        ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance, school_year")
                        ->whereRaw(" amount-discount-debit_memo-payment > 0 ")
                        ->groupBy("school_year")
                        ->get();
                
                //Other Fee
                $other_misc = \App\Ledger::where('idno', $idno)->whereRaw('amount-discount-debit_memo-payment > 0 And (category_switch=7)')->get();

                if (count($other_misc) > 0) {
                    foreach ($other_misc as $om) {
                        $total_other = $total_other + $om->amount - $om->discount - $om->debit_memo - $om->payment;
                    }
                }
//      // Total Due Main
            $downpayment = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->where('due_switch', '0')->selectRaw('sum(amount) as amount')->first();
            $duetoday = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->where('due_date', '<=', date('Y-m-d'))->where('due_switch', '1')->selectRaw('sum(amount) as amount')->first();
                //Total Payment Main
                $payment = \App\Ledger::where('idno', $idno)->where('category_switch', '<=', '6')
                                ->selectRaw('sum(debit_memo)+sum(payment) as payment')->first();
                //
                if ($downpayment->amount + $duetoday->amount - $payment->payment > 0) {
                    if($status->status > 0){
                        $due_total = $downpayment->amount + $duetoday->amount - $payment->payment;
                    }else{
                        $due_total = 0;
                    }
                } else {
                    $due_total = 0;
                }
                //reservation
                $reservation = \App\Reservation::where('idno', $idno)->where('reservation_type', '1')
                                ->where('is_consumed', '0')->selectRaw('sum(amount) as amount')->first();
                //Srudent Deposit
                $deposit = \App\Reservation::where('idno', $idno)->where('reservation_type', '2')
                                ->where('is_consumed', '0')->selectRaw('sum(amount) as amount')->first();

                return view('cashier.main_payment', compact('user', 'other_fee_total', 'miscellaneous_fee_total', 'depository_fee_total', 'srf_total', 'tuition_fee_total', 'previous_total', 'other_misc', 'reservation', 'deposit', 'receipt_number', 'due_total', 'optional_fee_total', 'status'));
                }
                
            } else {
            return view('cashier.ORUsed');
            }
            
        }
    }

    function post_main_payment(Request $request) {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            DB::beginTransaction();
            $reference_id = uniqid();
            StudentReservation::postPayment($request, $reference_id);
            $this->postAccounting($request, $reference_id);
            StudentReservation::postCashDebit($request, $reference_id);
            StudentLedger::updatereceipt();
            $this->checkStatus($request, $reference_id);
            \App\Http\Controllers\Admin\Logs::log("Post main payment for  - $request->idno with reference id:$reference_id.");
            DB::commit();
            return redirect(url('/cashier', array('viewreceipt', $reference_id)));
        }
        //return $request;
    }

    function checkStatus($request, $reference_id) {
        if ($request->main_due > "0") {
            $status = \App\Status::where('idno', $request->idno)->first();
            if ($status->status == env("ASSESSED")) {
//                $this->addUnrealizedEntry($request, $reference_id);
                $idno = $this->changeStatus($request->idno);
                $this->addLevels($idno);
                //$this->notifyStudent($request, $reference_id);
            }
        }
    }
    
    function manualMark($idno) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == env("ASSESSED")){
                $idno = $this->changeStatus($idno);
                $this->addLevels($idno);
                \App\Http\Controllers\Admin\Logs::log("Manually marked as enrolled - $idno");
            }
            return redirect(url('registrar_college',array('assessment',$idno)));
        }else if(Auth::user()->accesslevel == env("SCHOLARSHIP_HED")){
            $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == env("ASSESSED")){
                $idno = $this->changeStatus($idno);
                $this->addLevels($idno);
                \App\Http\Controllers\Admin\Logs::log("Manually marked as enrolled - $idno");
            }
            return redirect(url('scholarship_college',array('view_scholar',$idno)));
        }else if(Auth::user()->accesslevel == env("REG_BE")){            
            $idno = $this->changeStatus($idno);
            $this->addLevels($idno);
            \App\Http\Controllers\Admin\Logs::log("Manually marked as enrolled - $idno");
            return redirect(url('bedregistrar',array('assess',$idno)));
        }
    }

    public static function addUnrealizedEntry($request, $reference_id) {
        $level = \App\Status::where('idno', $request->idno)->first()->level;
        $totaltuition = \App\Ledger::where('idno', $request->idno)->where('category_switch', env("TUITION_FEE"))
                        ->selectRaw("sum(amount) as amount")->first();
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $dept = \App\CtrAcademicProgram::where('level', $level)->first();
        $department = $dept->department; //\App\Status::where('idno',$request->idno)->first()->department;
        //add debit tuition fee ar
        $addacct = new \App\Accounting;
        $addacct->transaction_date = $request->date;
        $addacct->reference_id = $reference_id;
        $addacct->accounting_type = env("COMPUTER");
        $addacct->category = env("AR_TUITION_NAME");
        $addacct->subsidiary = $request->idno;
        $addacct->receipt_details = env("AR_TUITION_NAME");
        $addacct->particular = "Unrealized Tuition Fee For " . $request->idno;
        $addacct->accounting_code = env("AR_TUITION_CODE");
        $addacct->department = $department;
        $addacct->accounting_name = env("AR_TUITION_NAME");
        $addacct->fiscal_year = $fiscal_year;
        $addacct->debit = $totaltuition->amount;
        $addacct->posted_by = Auth()->user()->idno;
        $addacct->save();

        //add credit unearned
        $addacct = new \App\Accounting;
        $addacct->transaction_date = $request->date;
        $addacct->reference_id = $reference_id;
        $addacct->accounting_type = env("COMPUTER");
        $addacct->category = env("UNEARNED_NAME");
        $addacct->subsidiary = $request->idno;
        $addacct->receipt_details = env("UNEARNED_NAME");
        $addacct->particular = "Unrealized Tuition Fee For " . $request->idno;
        $addacct->accounting_code = env("UNEARNED_CODE");
        $addacct->accounting_name = env("UNEARNED_NAME");
        $addacct->department = $department;
        $addacct->fiscal_year = $fiscal_year;
        $addacct->credit = $totaltuition->amount;
        $addacct->posted_by = Auth()->user()->idno;
        $addacct->save();
    }

    public static function changeStatus($idno) {
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
            self::emailIdno($no);
        } else {
            $status = \App\Status::where('idno', $idno)->first();
            $status->is_new = 0;
            $status->update();
        }
        return $no;
    }
    
    public static function emailIdno($no){
        try {
            $user = \App\User::where('idno',$no)->first();
                $data = array('name' => $user->firstname . " " . $user->lastname, 'email' => $user->email);
                Mail::send('email.new_idno', compact('user'), function($message) use($user) {
                    $message->to($user->email, $user->firstname . " " . $user->lastname)
                            ->subject('AC Online Enrollment');
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                });
            } catch (\Exception $e) {
            }
    }

    public static function getIdno($idno) {
        $status = \App\Status::where('idno', $idno)->first();
        if ($status->academic_type == "College") {
            $id_no = \App\CtrStudentNumber::where('academic_type', "College")->first();
            $idNumber = $id_no->idno;
            $id_no->idno = $id_no->idno + 1;
            $id_no->update();
            for ($i = strlen($idNumber); $i <= 2; $i++) {
                $idNumber = "0" . $idNumber;
            }
            $pre = \App\CtrEnrollmentSchoolYear::where('academic_type', $status->academic_type)->first();
            $pre_number = $pre->school_year;
            return substr($pre_number, 2, 2) . $idNumber;
        } else {
            $id_no = \App\CtrStudentNumber::where('academic_type','BED')->first();
            $idNumber = $id_no->idno;
            $id_no->idno = $id_no->idno + 1;
            $id_no->update();
            for ($i = strlen($idNumber); $i <= 2; $i++) {
                $idNumber = "0" . $idNumber;
            }
            $pre = \App\CtrEnrollmentSchoolYear::where('academic_type', $status->academic_type)->first();
            $pre_number = $pre->school_year;
            $pre_number2 = $pre->school_year + 1;
            return substr($pre_number, 2, 2) . substr($pre_number2, 2, 2) . $idNumber;
        }
    }

    function notifyStudent($request, $reference_id) {
        
    }

    function postAccounting($request, $reference_id) {

        if($request->over_payment > 0){
            $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
            $addacct = new \App\Accounting;
            $addacct->transaction_date = $request->date;
            $addacct->reference_id = $reference_id;
            $addacct->accounting_type = env("CASH");
            $addacct->category = "Overpayment";
            $addacct->subsidiary = "Overpayment";
            $addacct->receipt_details = "Overpayment";
            $addacct->particular = "Overpayment";
            $addacct->accounting_code = env("OVER_PAYMENT_CODE");
            $addacct->accounting_name = env("OVER_PAYMENT_NAME");
            $addacct->department = "NONE";
            $addacct->fiscal_year = $fiscal_year;
            $addacct->credit = $request->over_payment;
            $addacct->posted_by = Auth::user()->idno;
            $addacct->save();
        }
        
//        if ($request->main_due > 0) {
//            $totalpayment = $request->main_due;
//            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", '<=', '6')->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
//            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
//            
//        }
        if ($request->miscellaneous > 0) {
            $totalpayment = $request->miscellaneous;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('MISC_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
            
        }
        if ($request->other_fee > 0) {
            $totalpayment = $request->other_fee;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('OTHER_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
            
        }
        if ($request->depository > 0) {
            $totalpayment = $request->depository;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('DEPOSITORY_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
            
        }
        if ($request->srf > 0) {
            $totalpayment = $request->srf;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('SRF_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
            
        }
        if ($request->optional > 0) {
            $totalpayment = $request->optional;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch",  env('OPTIONAL_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
            
        }
        if ($request->tuition > 0) {
            $totalpayment = $request->tuition;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch",  env('TUITION_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
            
        }

//        if ($request->previous_balance > 0) {
//            $totalpayment = $request->previous_balance;
//            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", '>=', '10')->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('id')->orderBy('category_switch')->get();
//            $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
//        }
        if (count($request->other_misc) > 0) {
            foreach ($request->other_misc as $key => $totalpayment) {
                $ledgers = \App\Ledger::where('id', $key)->get();
                $ledgers_check = \App\Ledger::where('id', $key)->first();
                $this->checkCredentialRequest($ledgers_check,$reference_id);
                $this->checkCredentialRequestID($ledgers_check,$reference_id);
                $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
            }
        }
        
        if(count($request->previous_sy) > 0){
            foreach ($request->previous_sy as $sy => $totalpayment) {
                if($request->previous_sy[$sy] > 0){
                    $totalpayment = $request->previous_balance;
                    $ledgers = \App\Ledger::where("school_year", $sy)->where('idno', $request->idno)->where("category_switch", '>=', '10')->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('id')->orderBy('category_switch')->get();
                    $this->processAccounting($request, $reference_id, $totalpayment, $ledgers, env("CASH"));
                }
            }
        }
    }
    function checkCredentialRequest($ledgers_check,$reference_id){
        if($ledgers_check->request_id != null){
            $getOR= \App\Payment::where('reference_id', $reference_id)->first()->receipt_no;
            $updateRequestForm = \App\FormRequest::where('reference_id', $ledgers_check->request_id)->first();
            if(isset($updateRequestForm)){
                $updateRequestForm->or_number = $getOR;
                $updateRequestForm->status=1;
                $updateRequestForm->save();
            }
        }
    }
    function checkCredentialRequestID($ledgers_check,$reference_id){
        if($ledgers_check->request_id != null){
            $getOR= \App\Payment::where('reference_id', $reference_id)->first()->receipt_no;
            $updateRequestForm = \App\IdRequest::where('reference_id', $ledgers_check->request_id)->first();
            if(isset($updateRequestForm)){
                $updateRequestForm->or_number = $getOR;
                $updateRequestForm->status=1;
                $updateRequestForm->save();
            }
        }
    }

    public static function processDiscount($request, $reference_id, $discount, $discount_code, $accounting_type) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $discount_ref = \App\CtrDiscount::where('discount_code', $discount_code)->first();
        $dept = \App\CtrAcademicProgram::where('level', $request->level)->first();
        //$department = $dept->department; 
        $department=\App\Status::where('idno',$request->idno)->first()->department;
        $addacct = new \App\Accounting;
        $addacct->transaction_date = $request->date;
        $addacct->reference_id = $reference_id;
        $addacct->accounting_type = $accounting_type;
        $addacct->category = $discount_ref->discount_description;
        $addacct->subsidiary = $discount_ref->discount_description;
        $addacct->receipt_details = $discount_ref->discount_description;
        $addacct->particular = $discount_ref->discount_description;
        $addacct->accounting_code = $discount_ref->accounting_code;
        $addacct->department = $department;
        $addacct->accounting_name = $discount_ref->accounting_name;
        $addacct->fiscal_year = $fiscal_year;
        $addacct->debit = $discount;
        $addacct->posted_by = Auth::user()->idno;
        $addacct->save();
    }

    public static function processAccounting($request, $reference_id, $totalpayment, $ledgers, $accounting_type) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
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
                            $totalpayment = 0;
                        }
                    }
                }
            }
        }
    }

    public static function addLevels($idno) {
        $status = \App\Status::where('idno', $idno)->first();
        if (count($status) > 0) {
            if ($status->academic_type == "BED" || $status->academic_type == "SHS") {
                $addbed = new \App\BedLevel;
                $addbed->idno = $status->idno;
                $addbed->levels_reference_id = $status->levels_reference_id;
                $addbed->date_registered = $status->date_registered;
                $addbed->date_enrolled = date('Y-m-d');
                $addbed->department = $status->department;
                $addbed->strand = $status->strand;
                $addbed->track = $status->track;
                $addbed->level = $status->level;
                $addbed->section = $status->section;
                $addbed->status = $status->status;
                if ($status->academic_type == 'SHS') {
                    $addbed->period = $status->period;
                }
                $addbed->school_year = $status->school_year;
                $addbed->type_of_plan = $status->type_of_plan;
                $addbed->type_of_account = $status->type_of_account;
                $addbed->type_of_discount = $status->type_of_discount;
                $addbed->esc = $status->esc;
                $addbed->registration_no = $status->registration_no;
                $addbed->remarks = $status->remarks;
                $addbed->is_new = $status->is_new;
                $addbed->save();
            } else {
                $addbed = new \App\CollegeLevel;
                $addbed->idno = $status->idno;
                $addbed->date_advised = $status->date_advised;
                $addbed->date_registered = $status->date_registered;
                $addbed->date_enrolled = date('Y-m-d');
                $addbed->department = $status->department;
                $addbed->level = $status->level;
                $addbed->program_code = $status->program_code;
                $addbed->program_name = $status->program_name;
                $addbed->status = $status->status;
                $addbed->school_year = $status->school_year;
                $addbed->period = $status->period;
                $addbed->type_of_plan = $status->type_of_plan;
                $addbed->type_of_account = $status->type_of_account;
                $addbed->type_of_discount = $status->type_of_discount;
                $addbed->esc = $status->esc;
                $addbed->registration_no = $status->registration_no;
                $addbed->remarks = $status->remarks;
                $addbed->is_new = $status->is_new;
                $addbed->is_audit = $status->is_audit;
                $addbed->advised_by = $status->advised_by;
                $addbed->save();
            }
        }
    }

    function getDepartment($level) {
        $dept = \App\CtrAcademicProgram::where('level', $level)->first();
        return $dept->department;
    }

}
