<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class coursescheduling_ajax extends Controller {

    //
    function listcourse_to_schedule($program_code) {
        if (Request::ajax()) {

            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $section = Input::get("level");
            $level = Input::get("section");

            $courses = \App\CourseOffering::where('program_code', $program_code)
                    ->where('school_year', $school_year)
                    ->where('section', $section)
                    ->where('level', $level)
                    ->where('period', $period)
                    ->get();

            return view('reg_college.curriculum_management.ajax.courses_to_schedule', compact('courses'));
        }
    }

    function show_available_rooms() {
        if (Request::ajax()) {

            $day = Input::get("day");
            $time_start = Input::get("time_start");
            $time_end = Input::get("time_end");
            $course_offering_id = Input::get("course_offering_id");

            $info_course_offering = \App\CourseOffering::where('id', $course_offering_id)->first();


            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', "College")->first();
            $is_conflict = \App\ScheduleCollege::
                    join('course_offerings', 'schedule_colleges.course_offering_id', '=', 'course_offerings.id')
                    ->where('course_offerings.program_code', $info_course_offering->program_code)
                    ->where('course_offerings.level', $info_course_offering->level)
                    ->where('course_offerings.section', $info_course_offering->section)
                    ->where('schedule_colleges.school_year', $school_year->school_year)
                    ->where('schedule_colleges.period', $school_year->period)
                    ->where('schedule_colleges.day', $day)
                    ->where(function($q) use ($time_start, $time_end) {
                        $q->whereBetween('time_start', array(date("H:i:s", strtotime($time_start)), date("H:i:s", strtotime($time_end))))
                        ->orwhereBetween('time_end', array(date("H:i:s", strtotime($time_start)), date("H:i:s", strtotime($time_end))));
                    })
                    ->get(['schedule_colleges.school_year']);

            $rooms = \App\ScheduleCollege::distinct()
                    ->where('school_year', $school_year->school_year)
                    ->where('period', $school_year->period)
                    ->where('day', $day)
                    ->where(function($q) use ($time_start, $time_end) {
                        $q->whereBetween('time_start', array(date("H:i:s", strtotime($time_start)), date("H:i:s", strtotime($time_end))))
                        ->orwhereBetween('time_end', array(date("H:i:s", strtotime($time_start)), date("H:i:s", strtotime($time_end))));
                    })
                    ->get(array('room'));

            if (count($rooms) > 0) {
                $sql = "id is not null";
                foreach ($rooms as $room) {
                    $sql = $sql . " and room != '" . $room->room . "'";
                }
                $available_rooms = \App\CtrRoom::whereRaw($sql)->get();
            } else {
                $available_rooms = \App\CtrRoom::all();
            }

            return view('reg_college.curriculum_management.ajax.show_available_rooms', compact('day', 'time_start', 'time_end', 'course_offering_id', 'school_year', 'rooms', 'available_rooms', 'is_conflict'));
        }
    }
    
    function get_section() {
        if (Request::ajax()) {
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            
            $sections = \App\CourseOffering::distinct()->where('level', $level)->where('program_code', $program_code)->where('school_year', $school_year)->where('period', $period)->orderBy('section')->get(['section', 'section_name']);
            
            $data = "<div class='form-group'><label>Section</level>"
                    . "<select id='section' class='form-control select2' style='width: 100%;' onchange='courses_offered(program_code.value)'>"
                    . "<option value=''>Select Section</option>";
            foreach($sections as $section){
                $data = $data."<option value=".$section->section.">".$section->section_name."</option>";
            }
            $data = $data."</select></div>";
            
            return $data;
        }
    }

}