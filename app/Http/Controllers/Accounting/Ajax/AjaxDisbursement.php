<?php

namespace App\Http\Controllers\Accounting\Ajax;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Request;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Support\Facades\Auth;

class AjaxDisbursement extends Controller
{
    function save_entries() {
        if (Request::ajax()) {
            $voucher_type = 0;
            $voucher_no = Input::get('voucher_no');
            $reference = Input::get('reference');
            $code = Input::get('code');
            $particular = Input::get('particular');
            $type = Input::get('type');
            $amount = str_replace(",","",Input::get('amount'));
            
            $fiscal_year = \App\CtrFiscalYear::first();
            
            $saveEntry = new \App\DisbursementData;
            $saveEntry->transaction_date = Carbon::now();
            $saveEntry->reference_id = $reference;
            $saveEntry->voucher_no = $voucher_no;
            $saveEntry->type = $voucher_type;
            $saveEntry->paid_by = $particular;
            $saveEntry->category = $this->getAccountingName($code);
            $saveEntry->description = $particular;
            $saveEntry->receipt_details = $this->getAccountingName($code);
            $saveEntry->accounting_code = $code;
            $saveEntry->category_switch = 7;
            $saveEntry->entry_type = env('DISBURSEMENT');
            $saveEntry->fiscal_year = $fiscal_year->fiscal_year;
            $saveEntry->receipt_type = "D";
            if($type == "Debit"){
                $saveEntry->debit = $amount;
                $saveEntry->credit = 0;
            }
            else{
                $saveEntry->debit = 0;
                $saveEntry->credit = $amount;
            }
            $saveEntry->particular = $particular;
            $saveEntry->isreverse = 1;
            $saveEntry->posted_by = Auth::user()->idno;
            $saveEntry->save();
            $this->check_entries($reference,$voucher_type,$voucher_no);
            return $this->display_entries($reference,$voucher_type);
        }
    }
    
    function check_entries($reference,$category,$voucher_no){
        $entries = \App\DisbursementData::where('type',$category)
                ->where('voucher_no',$voucher_no)->get();
        if(count($entries) > 0){
            foreach($entries as $entry){
                $entry->reference_id = $reference;
                $entry->save();
            }
        }
//        dd($entries);
        return;
    }
    
    function getAccountingName($code){
        $acctcode = \App\ChartOfAccount::where('accounting_code',$code)->first();
        return $acctcode->accounting_name;
    }
    
    function display_entries($reference,$category){
        $accountings = \App\DisbursementData::where('reference_id',$reference)->get();
        $accounting_entry = \App\ChartOfAccount::get();
        if($category == 0){
            return view('accounting.ajax.disbursement_entries',compact('reference','accountings','accounting_entry'));
        }
        else{
            return view('accounting.ajax.petty_cash_entries',compact('reference','accountings','accounting_entry'));
        }
    }
    
    function remove_entries(){
        $category = Input::get('category');
        $reference = Input::get('reference');
        $id = Input::get('id');
        $accountings = \App\DisbursementData::where('id',$id)->delete();
        
        return $this->display_entries($reference,$category);
    }
    
    
    function get_disbursements() {
        if (Request::ajax()) {
            $date_to = Input::get('date_to');
            $date_from = Input::get('date_from');
            $startDate = "$date_to";
            $dateEnd = "$date_from";
            $lists = \App\Disbursement::whereBetween('transaction_date', [$startDate, $dateEnd])->orderBy('transaction_date','asc')->get();
            return view('accounting.disbursement.ajaxdisplay', compact('lists'));
        }
    }
    
    
        
}