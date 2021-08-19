<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;


class ChedEnrollmentReportsController extends Controller
{
    public function __contruct(){
        $this->middleware('auth');
    }
    
    function index(){
        if(Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('DEAN')){
           $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->orderBy('program_name')->get(['program_name','program_code']);
//           $levels= \App\CollegeLevel::distinct()->where('academic_type', 'College')->orderBy('level')->get(['level']);
//           $periods= \App\CollegeLevel::distinct()->where('academic_type', 'College')->orderBy('period')->get(['period']);
           return view('reg_college.reports.ched_enrollment_reports', compact('programs'));
        }
    }
    
    function print_report(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('DEAN')) {           
            $this->validate($request, [
                'program_code'=>'required',
                'level' => 'required',
                'school_year' => 'required',
                'period' => 'required'
            ]);
            $students = \App\CollegeLevel::where('program_code', $request->program_code)->where('level', $request->level)->where('school_year', $request->school_year)->where('period', $request->period)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('college_levels.status',3)->join('users', 'users.idno','=','college_levels.idno')->orderBy('users.lastname', 'asc')->get(['college_levels.status', 'college_levels.period','college_levels.school_year','college_levels.level','college_levels.program_code','users.idno']);
            if($request->school_year < 2021){
            $pdf = PDF::loadView('reg_college.reports.print_ched_enrollment_report', compact('students','request'));           
            }else{
            $pdf = PDF::loadView('reg_college.reports.print_ched_enrollment_report_v2', compact('students','request'));           
            }
            $pdf->setPaper('letter','landscape');
            return $pdf->stream("$request->program_code-$request->level.pdf");
        }
    }        
    
}
