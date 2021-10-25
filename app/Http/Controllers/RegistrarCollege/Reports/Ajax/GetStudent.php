<?php

namespace App\Http\Controllers\RegistrarCollege\Reports\Ajax;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Illuminate\Support\Facades\Input;
use Auth;
use PDF;

class GetStudent extends Controller
{
    function getstudent(){
        if(Request::ajax()){
            $programs = Input::get('programs');           
                 $college_levels = \App\CollegeLevel::where('program_name',$programs)->orderBy('idno')->get();
             return view ("reg_college.reports.ajax.getstudent", compact('programs','college_levels'));
        }
    }

    function print_freshmen($school_year){
        if(Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')){
//            $school_year = \App\CollegeLevel::distinct()->where('college_levels.school_year')->where('college_levels.status', 3)->where('college_levels.level', "1st Year")->get(['idno', 'level', 'program_code']);
//            $lists = \App\CollegeLevel::distinct()->where('college_levels.school_year', $school_year)->where('college_levels.status', 3)->where('college_levels.level', "1st Year")->get(['idno', 'level', 'program_code']); 
            
            $lists = new \App\FreshmenStudentList($school_year);
            $pdf = PDF::loadView('reg_college.reports.print_freshmen_student', compact('school_year', 'lists'));
            $pdf->setPaper('letter','landscape');
            return $pdf->stream("student_freshmen_.pdf");
        }
    }
}
