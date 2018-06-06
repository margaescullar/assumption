<?php

namespace App\Http\Controllers\Accounting\Ajax;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Illuminate\Support\Facades\Input;
use Auth;

class AjaxSetReceipt extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function getsearch_or(){
        if(Request::ajax()){
            $search = Input::get('search');
            $get_receipts = \App\Payment::where('receipt_no', $search)->orWhere('paid_by', 'like', "%$search%")->get();
            
            return view('accounting.ajax.get_or', compact('get_receipts'));
        }
    }
}
