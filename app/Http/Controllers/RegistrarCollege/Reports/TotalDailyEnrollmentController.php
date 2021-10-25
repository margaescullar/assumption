<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;

class TotalDailyEnrollmentController extends Controller
{
    public function __contruct(){
        $this->middleware('auth');
    }

    function index($date_start, $date_end){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {

            $academic_programs = \App\CtrAcademicProgram::distinct()->where('academic_type','College')->get(['program_code','program_name']);
            
            return view('reg_college.reports.total_daily_enrollment_statistics', compact('date_start','date_end','academic_programs'));
        }    
    }
    
    function print_daily_enrollment($date_start, $date_end){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            //$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
            //$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $academic_programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);

            $pdf = PDF::loadView('reg_college.reports.print_total_enrollment_statistics', compact('date_start', 'date_end', 'academic_programs'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }            
        }
    }
