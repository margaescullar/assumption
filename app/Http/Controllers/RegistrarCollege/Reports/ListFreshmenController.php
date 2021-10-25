<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Illuminate\Support\Facades\Input;
use Auth;
use PDF;

class ListFreshmenController extends Controller
{
        public function __construct(){
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
        return view('reg_college.reports.list_freshmen_student');
        }
    }    

    function get_freshmen(){
        if (Request::ajax()) {
            $school_year = Input::get('school_year');
            $lists = \App\CollegeLevel::distinct()->where('college_levels.school_year', $school_year)->where('college_levels.status', 3)->where('college_levels.level', "1st Year")->get(['idno', 'level', 'program_code']);
            return view('reg_college.reports.ajax.getfreshmen', compact('lists', 'school_year'));
        }
    }

    function print_freshmen($school_year){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $lists = \App\CollegeLevel::distinct()->where('college_levels.school_year', $school_year)->where('college_levels.status', 3)->where('college_levels.level', "1st Year")->get(['idno', 'level', 'program_code']); 
//            $list =  new \App\FreshmenStudentList($school_year);
//            dd($list);
            $pdf = PDF::loadView('reg_college.reports.print_freshmen_student', compact('school_year', 'lists'));
            $pdf->setPaper('letter','landscape');
            $pdf->stream("student_freshmen_.pdf");
            
        }
    }
}
