<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class EditLedgerLogs extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($start_date=null,$end_date=null) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            if($start_date==null || $end_date==null){
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d');
            }
            
            $logs = \App\Log::whereRaw("action like 'Update Ledger of%' ")->where('datetime', '>=', $start_date. " 00:00:00")->where('datetime', '<=', $end_date. " 23:59:59")->get();
            return view('accounting.editLedger.logs', compact('end_date', 'start_date', 'logs'));
        }
    }
}
