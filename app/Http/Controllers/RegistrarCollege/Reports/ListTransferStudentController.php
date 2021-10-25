<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;

class ListTransferStudentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
        return view('reg_college.reports.list_transfer_student');
        }
    }
    
    function print_transfer_student(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $this->validate($request,[
               'school_year'=>'required',
                'period'=>'required'
            ]);
            $students = \App\CollegeLevel::
                    join('users', 'users.idno','=','college_levels.idno')
                    ->join('admission_heds','admission_heds.idno','=','users.idno')
                    ->join('student_infos', 'student_infos.idno', '=','admission_heds.idno')
                    ->where('college_levels.school_year', $request->school_year)->where('college_levels.period', $request->period)->where('college_levels.status',3)->orderBy('users.lastname', 'asc')->where('admission_heds.tagged_as',2)->get(['college_levels.program_code','college_levels.program_name','college_levels.status','college_levels.level', 'college_levels.period','college_levels.school_year','users.idno','student_infos.last_school_attended']);
            $pdf = PDF::loadView('reg_college.reports.print_transfer_student', compact('students','request'));
            $pdf->setPaper('letter','landscape');
            return $pdf->stream("transfer_student_.pdf");            
        }
    }    
    
    
}

