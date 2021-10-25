<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class ListUnofficiallyEnrolledController extends Controller
{   
    function __construct(){
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->orderBy('program_name')->get(['program_name','program_code']);
            return view('reg_college.reports.list_unofficially_enrolled', compact('programs'));
        }
    }    
    function print_unofficial(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $this->validate($request,[
                'school_year'=> 'required',
                'period' => 'required'
            ]);            
            $students = \App\Status::join('users','users.idno', '=', 'statuses.idno')->where('statuses.academic_type', "College")
                    ->where('statuses.school_year', $request->school_year)->where('statuses.period', $request->period)->where('statuses.status', 2)->orderBy('users.lastname', 'asc')->get(['statuses.program_code','statuses.program_name','statuses.period','statuses.level', 'statuses.period','statuses.school_year','users.idno']); 
            $pdf = PDF::loadView('reg_college.reports.print_unofficially_enrolled', compact('students', 'request'));
            $pdf -> setPaper('letter','portrait');
            return $pdf->stream('unofficial_student_.pdf');
        }
    }
}
