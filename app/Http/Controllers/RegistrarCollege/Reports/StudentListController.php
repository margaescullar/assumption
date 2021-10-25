<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use PDF;
use DB;

class StudentListController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function search() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            return view('reg_college.reports.student_list.search', compact('school_years', 'programs'));
        }
    }

    function print_search($school_years, $periods, $levels, $program_codes) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {

            $school_year = $school_years;
            $period = $periods;
            $level = $levels;
            $program_code = $program_codes;
            
            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and school_year = '" . $school_year . "'";
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and period = '" . $period . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and level = '" . $level . "'";
            }

            if ($program_code == "all") {
                $program_code = "";
            } else {
                $program_code = "and program_code = '" . $program_code . "'";
            }
            
            $lists = DB::Select("Select college_levels.id, college_levels.idno from college_levels join users on users.idno = college_levels.idno where college_levels.status=3 $school_year $period $level $program_code order by users.lastname");
            
            $pdf = PDF::loadView('reg_college.reports.student_list.print_search', compact('lists', 'school_years', 'periods', 'levels', 'program_codes'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }
    }

    function per_course() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            return view('reg_college.reports.student_list.per_course', compact('school_years', 'programs'));
        }
    }

    function print_per_course($course_codes, $schedule_ids, $school_years, $periods, $levels, $program_codes) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $course_code = $course_codes;
            $schedule_id = $schedule_ids;
            $school_year = $school_years;
            $period = $periods;
            $level = $levels;
            $program_code = $program_codes;
            
            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and grade_colleges.school_year = '" . $school_year . "'";
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and grade_colleges.period = '" . $period . "'";
            }

            if ($course_code == "all") {
                $course_code = "";
                $course_names = "";
            } else {
                $course_code = "and grade_colleges.course_code = '" . $course_code . "'";
                $course_names = \App\CourseOffering::where('course_code', $course_codes)->first()->course_name;
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and statuses.level = '" . $level . "'";
            }

            if ($schedule_id == "all") {
                $schedule_id = "";
                $join = "";
            } else {
                $schedule_id = "and course_offerings.schedule_id = '" . $schedule_id . "'";
                $join = "join course_offerings on course_offerings.id = grade_colleges.course_offering_id ";
            }

            if ($program_code == "all") {
                $program_code = "";
            } else {
                $program_code = "and statuses.program_code = '" . $program_code . "'";
            }
            
            
//            $list_per_courses = DB::Select("Select distinct users.idno, users.lastname, users.firstname, users.middlename from grade_colleges join users on users.idno = grade_colleges.idno join statuses on statuses.idno = grade_colleges.idno join course_offerings on course_offerings.id = grade_colleges.course_offering_id where grade_colleges.id is not null $school_year $period $level $program_code $course_code $section_name $section and statuses.status = 3 order by users.lastname");
            $list_per_courses = DB::Select("Select distinct users.idno, users.lastname, users.firstname, users.middlename from grade_colleges join users on users.idno = grade_colleges.idno join statuses on statuses.idno = grade_colleges.idno $join where grade_colleges.id is not null $school_year $period $level $program_code $course_code $schedule_id and statuses.status = 3 order by users.lastname");

            $pdf = PDF::loadView('reg_college.reports.student_list.print_per_course', compact('course_codes','course_names','list_per_courses', 'schedule_ids','school_years', 'periods', 'levels', 'program_codes'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }
    }

    function per_instructor() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('ADMISSION_HED') || Auth::user()->accesslevel==env('DEAN') || Auth::user()->accesslevel==env('AA')) {
            $instructors = \App\User::where('accesslevel', env('INSTRUCTOR'))->get();
            return view('reg_college.reports.student_list.per_instructor', compact('instructors'));
        }
    }
    
    function print_per_instructor($instructor_id, $school_year, $period, $schedule_id, $course_code){
        
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
        
        $pdf = PDF::loadView('reg_college.reports.student_list.print_per_instructor', compact('courses_id','course_code','schedule_id','course_name', 'school_year','period', 'instructor_id'));
        $pdf->setPaper(array(0, 0, 612.00, 792.0));
        return $pdf->stream("student_list_.pdf");
    }
    
}
