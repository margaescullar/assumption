<?php

namespace App\Http\Controllers\BedRegistrar\Records;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class indexController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            return view('reg_be.records.index',compact('idno'));
        }
    }

}
