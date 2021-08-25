<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;
use Mail;

class DirectoryController extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }
    
    

    function student_list() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env('ADMISSION_BED') || Auth::user()->accesslevel == env('BED_CL') || Auth::user()->accesslevel == env('EDUTECH') || Auth::user()->accesslevel == env('OSA')) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("reg_be.directory", compact('students'));
        }
    }
}
