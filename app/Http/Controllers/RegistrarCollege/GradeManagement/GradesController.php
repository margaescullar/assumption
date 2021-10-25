<?php

namespace App\Http\Controllers\RegistrarCollege\GradeManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class GradesController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view_grades($school_year, $period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env("DEAN") || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.grade_management.view_grades', compact('school_year', 'period'));
        }
    }

    function report_card() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.grade_management.report_card');
        }
    }

    function print_card_pdf(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $school_year = $request->school_year;
            $period = $request->period;
            $program_code = $request->program_code;
            $level = $request->level;

            $students = \App\CollegeLevel::where('school_year', $school_year)->where('period', $period)->where('program_code', $program_code)->where('level', $level)->join('users', 'users.idno', 'college_levels.idno')->orderBy('users.lastname')->get();

            $pdf = PDF::loadView('reg_college.grade_management.print_report_card', compact('school_year', 'period', 'program_code', 'level', 'students', 'request'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("report_card.pdf");
        }
    }

    function print_report_card_individually($school_year, $period, $idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $students = \App\CollegeLevel::where('school_year', $school_year)->where('period', $period)->where('college_levels.idno', $idno)->join('users', 'users.idno', 'college_levels.idno')->get();

            $pdf = PDF::loadView('reg_college.grade_management.print_report_card_individually', compact('school_year', 'period', 'idno', 'students'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("report_card.pdf");
        }
    }

    function incomplete_grades($type, $school_year, $period, $term) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env("DEAN")) {

            if ($type == "inc_ng") {
                $incomplete_grades = \App\GradeCollege::where('school_year', $school_year)->where('period', $period)
                                ->where(function ($query) use ($term) {
                                    $query->where($term, 'INC')
                                    ->orWhere($term, 'NG');
                                })->where('completion', NULL)->join('users', 'users.idno', '=', 'grade_colleges.idno')->orderBy('course_name', 'asc', 'lastname', 'asc')->get();
            } else if ($type == "blank") {
                $incomplete_grades = \App\GradeCollege::where('school_year', $school_year)->where('period', $period)
                                ->where(function ($query) use ($term) {
                                    $query->where($term, NULL)
                                    ->orWhere($term, '');
                                })->where('completion', NULL)->join('users', 'users.idno', '=', 'grade_colleges.idno')->orderBy('course_name', 'asc', 'lastname', 'asc')->get();
            }

            return view('reg_college.grade_management.incomplete_grades', compact('school_year', 'period', 'incomplete_grades', 'term', 'type'));
        }
    }

    function print_incomplete_grade($type,$school_year, $period, $term) {
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE')) {

            if ($type == "inc_ng") {
                $incomplete_grades = \App\GradeCollege::where('school_year', $school_year)->where('period', $period)
                                ->where(function ($query) use ($term) {
                                    $query->where($term, 'INC')
                                    ->orWhere($term, 'NG');
                                })->where('completion', NULL)->join('users', 'users.idno', '=', 'grade_colleges.idno')->orderBy('course_name', 'asc', 'lastname', 'asc')->get();
            } else if ($type == "blank") {
                $incomplete_grades = \App\GradeCollege::where('school_year', $school_year)->where('period', $period)
                                ->where(function ($query) use ($term) {
                                    $query->where($term, NULL)
                                    ->orWhere($term, '');
                                })->where('completion', NULL)->join('users', 'users.idno', '=', 'grade_colleges.idno')->orderBy('course_name', 'asc', 'lastname', 'asc')->get();
            }

            \App\Http\Controllers\Admin\Logs::log("Print Incomplete Grade of for SY $school_year-$period PDF");
            $pdf = PDF::loadView('reg_college.grade_management.print_incomplete_grade', compact('school_year', 'period', 'incomplete_grades', 'term','type'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("incomplete_grades.pdf");
        }
    }

    function statistics_of_grades($school_year, $period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env("DEAN")) {
            $subjects = \App\GradeCollege::distinct()->where('school_year', $school_year)->where('period', $period)->orderBy('course_name', 'asc')->get(array('course_code', 'course_name'));
            return view('reg_college.grade_management.statistics_of_grades', compact('school_year', 'period', 'subjects'));
        }
    }

    function print_statistics_of_grade($school_year, $period) {
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $subjects = \App\GradeCollege::distinct()->where('school_year', $school_year)->where('period', $period)->orderBy('course_name', 'asc')->get(array('course_code', 'course_name'));

            \App\Http\Controllers\Admin\Logs::log("Print Statistics of Grades of for SY $school_year-$period PDF");
            $pdf = PDF::loadView('reg_college.grade_management.print_statistics_of_grades', compact('school_year', 'period', 'subjects'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("statistics_of_grades.pdf");
        }
    }

}
