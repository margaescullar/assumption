<?php

namespace App\Http\Controllers\BedRegistrar\Records;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class secondController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $tor_details_hs = \App\TorDetail::firstOrCreate(['idno'=>"$idno"]);
            return view('reg_be.records.hs_tor.view',compact('idno','tor_details_hs'));
        }
    }
    
    function print($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $user = \App\User::where('idno',$idno)->first();
            $parents = \App\BedParentInfo::where('idno',$idno)->first();
            $profile = \App\BedProfile::where('idno',$idno)->first();
            $tor_details_hs = \App\TorDetail::where('idno',$idno)->first();
            $pdf = PDF::loadView('reg_be.records.hs_tor.print', compact('idno','tor_details_hs','user','parents','profile'));
            $pdf->setPaper(array(0, 0, 612, 936));
            return $pdf->stream("TOR-HS-$idno.pdf");
        }
    }
}
