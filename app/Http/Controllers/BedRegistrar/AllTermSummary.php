<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Excel;

class AllTermSummary extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("BED_ACADEMIC_DIRECTOR")) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("reg_be.grade_summary.student_list_all_term", compact('students'));
        }
    }

    function export_all_term_summary($level, $strand, $section, $school_year, $is_ee,$type,$period = null) {
        if($section == "All"){
            $section = '%%';
        }
        $lists = \App\Http\Controllers\BedRegistrar\Ajax\AllTermSummaryAjax::getListSubjectHeads($school_year, $level, $section, $period, $strand, 'lists',$is_ee);
        $subject_heads = \App\Http\Controllers\BedRegistrar\Ajax\AllTermSummaryAjax::getListSubjectHeads($school_year, $level, $section, $period, $strand, 'subject_heads',$is_ee);
        
        ob_end_clean();
        Excel::create('All Term Summary', function($excel) use ($level, $strand, $section, $school_year,$period, $lists, $subject_heads,$is_ee,$type) {
            $excel->setTitle("All Term Summary");

            $excel->sheet('All Term Summary', function ($sheet) use ($level, $strand, $section, $school_year, $period, $lists, $subject_heads,$is_ee,$type) {
                $sheet->loadView('reg_be.ajax.all_term_view_list', compact('school_year', 'level', 'section', 'period', 'strand', 'lists', 'subject_heads','is_ee','type'));
            });
        })->download('xlsx');
    }
}
