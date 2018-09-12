<?php

namespace App\Http\Controllers\RegistrarCollege\Reports\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class StudentList_ajax extends Controller {

    //
    function search() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            
            $sy=Input::get("school_year");
            $pr=Input::get("period");

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

            return view('reg_college.reports.student_list.ajax.display_search', compact('lists', 'sy','pr'));
        }
    }

    function select_section() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            $course_code = Input::get("course_code");

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

            if ($course_code == "all") {
                $course_code = "";
            } else {
                $course_code = "and course_code = '" . $course_code . "'";
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

//            $lists = DB::Select("Select distinct section, section_name from course_offerings where id is not null $school_year $period $level $program_code $course_code");
            $lists = DB::Select("Select distinct schedule_id from course_offerings where id is not null $school_year $period $course_code");

            return view('reg_college.reports.student_list.ajax.display_section', compact('lists'));
        }
    }

    function select_course() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");

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

            $courses = DB::Select("Select distinct course_name, course_code from course_offerings where id is not null $school_year $period");

            return view('reg_college.reports.student_list.ajax.display_course', compact('courses'));
        }
    }

    function list_per_course() {
        if (Request::ajax()) {
            $course_code = Input::get("course");
            $schedule_id = Input::get("schedule_id");
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            
            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and grade_colleges.school_year = '" . $school_year . "'";
                $sy = Input::get("school_year");
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and grade_colleges.period = '" . $period . "'";
                $pr = Input::get("period");
            }

            if ($course_code == "all") {
                $course_code = "";
            } else {
                $course_code = "and grade_colleges.course_code = '" . $course_code . "'";
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
            $list_per_courses = DB::Select("Select distinct users.idno, users.lastname, users.firstname, users.middlename from grade_colleges join users on users.idno = grade_colleges.idno join statuses on statuses.idno = grade_colleges.idno $join where grade_colleges.id is not null $school_year $period $level $program_code $course_code $schedule_id  and statuses.status = 3 order by users.lastname");
            
//            $list_per_courses = \App\GradeCollege::where('course_code', $course_code)->where('section_name', $section)->join('users', 'users.idno', '=', 'grade_colleges.idno')->join('statuses', 'statuses.idno', '=', 'grade_colleges.idno')->where('statuses.status', 3)->where('statuses.school_year', $school_year)->where('statuses.period', $period)->orderBy('users.lastname')->get();

            return view('reg_college.reports.student_list.ajax.display_per_course', compact('list_per_courses','pr','sy'));
        }
    }
    
    function get_course(){
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $instructor_id = Input::get("instructor_id");
            
            $courses = \App\ScheduleCollege::distinct()->where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->where('schedule_colleges.instructor_id',$instructor_id)->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->get(['course_offerings.course_code']);
            
            return view('reg_college.reports.student_list.ajax.get_course', compact('courses'));
        }
    }
    
    function get_schedule(){
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $instructor_id = Input::get("instructor_id");
            $course_code = Input::get("course_code");
            
            $scheds = \App\ScheduleCollege::distinct()->where('instructor_id', $instructor_id)->where('school_year', $school_year)->where('period', $period)->where('course_code', $course_code)->get(['schedule_id']);
            
            return view('reg_college.reports.student_list.ajax.get_sched', compact('scheds'));
        }
    }
    
    function getstudentlist(){
        if (Request::ajax()) {
            $schedule_id = Input::get("schedule_id");
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $instructor_id = Input::get("instructor_id");
            $course_code = Input::get("course_code");
            
            $confirm_instructor = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
        
            
            $courses_id = \App\CourseOffering::where('schedule_id',$schedule_id)->get();
            $course_name = \App\CourseOffering::where('schedule_id',$schedule_id)->first()->course_name; 
            
            return view('reg_college.reports.student_list.ajax.getstudentlist', compact('courses_id','schedule_id','course_name', 'school_year','period'));
        }
    }
    
}
