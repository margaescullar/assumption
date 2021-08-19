<?php

namespace App\Accounting;

use Illuminate\Database\Eloquent\Model;

class CashReceipts extends Model {

    
    public function __construct($reference_id) {
        $this->receipt_no = $this->receipt_no($reference_id);
        $this->amount_received = $this->amount_received($reference_id);
        $this->transaction_date = $this->transaction_date($reference_id);
        $this->debits = $this->debits($reference_id);
    }
    
    function receipt_no($reference_id){
        $receipt_no = \App\Payment::where('reference_id', $reference_id)->first()->reference_id;
        return $receipt_no;
    }
    
    function amount_received($reference_id){
        $amount_received = \App\Payment::where('reference_id', $reference_id)->first()->amount_received;
        return $amount_received;
    }
    
    function transaction_date($reference_id){
        $transaction_date = \App\Payment::where('reference_id', $reference_id)->first()->transaction_date;
        return $transaction_date;
    }
    
    function debits($reference_id){
        $debits = \App\Accounting::where('debit','>',0)->where('reference_id', $reference_id)->sum('debit');
        return $debits;
    }

}
