<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use PDF;
use DB;

class EnrollmentStatisticsController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($school_year, $period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ACCTNG_HEAD')|| Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN')){
            //$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
            //$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $academic_programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->where('is_display',1)->get(['program_code', 'program_name','is_display']);
            $hide_academic_programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->where('is_display',0)->get(['program_code', 'program_name','is_display']);
            $departments = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['department']);

            return view('reg_college.reports.enrollment_statistics', compact('school_year', 'period', 'academic_programs', 'departments','hide_academic_programs'));
        }
    }

    function print_statistics($school_year, $period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')|| Auth::user()->accesslevel == env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN')) {
            //$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
            //$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $academic_programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->where('is_display',1)->get(['program_code', 'program_name','is_display']);
            $departments = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['department']);

            $pdf = PDF::loadView('reg_college.reports.print_enrollment_statistics', compact('school_year', 'period', 'academic_programs', 'departments'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }
    }

    function print_official($school_year, $period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('DEAN')) {
            //$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
            //$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $academic_programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            $departments = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['department']);

            $pdf = PDF::loadView('reg_college.reports.print_enrollment_official', compact('school_year', 'period', 'academic_programs', 'departments'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->stream("student_list_.pdf");
        }
    }
    
    function update_display($program_code,$school_year,$period){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('DEAN')) {
            $update_programs = \App\CtrAcademicProgram::where('program_code',$program_code)->get();
            foreach($update_programs as $update_program){
                if($update_program->is_display == 1){
                    $update_program->is_display = 0;
                }else{
                    $update_program->is_display = 1;
                }
                $update_program->save();
            }
            
            return redirect("/registrar_college/reports/enrollment_statistics/$school_year/$period");

        }
        
    }

}
