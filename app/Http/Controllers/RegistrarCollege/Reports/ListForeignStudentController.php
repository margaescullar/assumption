<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;

class ListForeignStudentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
        return view('reg_college.reports.list_foreign_student');
        }
    }
    
    function print_foreign_student(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $this->validate($request,[
               'school_year'=>'required',
                'period'=>'required'
            ]);
            $students = \App\CollegeLevel::
                    join('users', 'users.idno','=','college_levels.idno')
                    ->where('users.is_foreign', 1)->where('college_levels.school_year', $request->school_year)->where('college_levels.period', $request->period)->where('college_levels.status',3)->orderBy('users.lastname', 'asc')->get(['college_levels.program_code','college_levels.program_name','college_levels.status','college_levels.level', 'college_levels.period','college_levels.school_year','users.idno']);
            $pdf = PDF::loadView('reg_college.reports.print_foreign_student', compact('students','request'));
            $pdf->setPaper('letter','landscape');
            return $pdf->stream("foreign_student_.pdf");            
        }
    }    
    
    
}

