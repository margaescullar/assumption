<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class BreakdownOfFees extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }
    function index($idno){
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD") || Auth::user()->accesslevel==env("CASHIER")) {
            $user = \App\User::where('idno', $idno)->first();
            $ledger_sy = \App\Ledger::distinct()->where('idno', $idno)->get(['school_year']);
            return view('accounting.breakdown_of_fees', compact('ledger_sy', 'idno', 'user'));
        }
    }
}
