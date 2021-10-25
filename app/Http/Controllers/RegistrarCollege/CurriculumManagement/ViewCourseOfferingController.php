<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class ViewCourseOfferingController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.view_full_course_offering');
        }
    }
    
    function index2() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.view_full_course_offering_room');
        }
    }
    
    function index3() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.view_full_course_offering_general');
        }
    }
    
    function index4() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.view_full_course_offering_course');
        }
    }
    
    function index5() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.view_full_course_offering_per_day');
        }
    }
    
    function print_offerings(Request $request){
 
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')){
            $courses = \App\CourseOffering::where('school_year', $request->school_year)->where('period', $request->period)->where('program_code', $request->program_code)->where('level', $request->level)->where('section_name', $request->section)->get();
//            $schedules = \App\CourseOffering::where('school_year', $request->school_year)->where('period', $request->period)->where('program_code', $request->program_code)->where('level', $request->level)->where('section_name', $request->section)->get();
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings',compact('courses','request'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings.pdf');           
        }        
    }
    
    function print_offerings_room(Request $request){
 
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')){
            $courses = \App\ScheduleCollege::distinct()->where('school_year', $request->school_year)->where('period', $request->period)->where('room', $request->room)->get(['schedule_id','course_code','course_offering_id']);
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings_room',compact('courses','request'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings.pdf');           
        }        
    }

    function print_offerings_general(Request $request){
 
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')){
            $courses = \App\CourseOffering::where('school_year', $request->school_year)->where('period', $request->period)->orderBy('course_code')->get();
                       
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings_general',compact('courses','request'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings_general.pdf');           
        }        
    }   
    
    function print_offerings_course(Request $request){
 
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')){
            $courses = \App\ScheduleCollege::distinct()->where('course_code', $request->course_code)->where('school_year', $request->school_year)->where('period', $request->period)->get(['schedule_id','course_code']);
            $number_of_students = \App\ScheduleCollege::distinct()->where('schedule_colleges.course_code', $request->course_code)->where('schedule_colleges.school_year', $request->school_year)->where('schedule_colleges.period', $request->period)->join('course_offerings', 'course_offerings.schedule_id','schedule_colleges.schedule_id')->join('grade_colleges','grade_colleges.course_offering_id', 'course_offerings.id')->join('statuses', 'statuses.idno', 'grade_colleges.idno')->where('statuses.status', env("ENROLLED"))->get(['grade_colleges.idno']);
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings_course',compact('courses','request','number_of_students'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings.pdf');           
        }        
    }   
    
    function print_offerings_per_day(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')){
            $courses = \App\ScheduleCollege::distinct()->where('day', $request->day)->where('school_year', $request->school_year)->where('period', $request->period)->orderBy('course_code','asc')->get(['schedule_id','course_code']);
            $number_of_students = \App\ScheduleCollege::distinct()->where('day', $request->day)->where('schedule_colleges.school_year', $request->school_year)->where('schedule_colleges.period', $request->period)->join('course_offerings', 'course_offerings.schedule_id','schedule_colleges.schedule_id')->join('grade_colleges','grade_colleges.course_offering_id', 'course_offerings.id')->join('statuses', 'statuses.idno', 'grade_colleges.idno')->where('statuses.status', env("ENROLLED"))->get(['grade_colleges.idno']);
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings_per_day',compact('courses','request','number_of_students'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings.pdf');           
        }        
    }    

    function viewofferings_free_section() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(array('program_code', 'program_name'));
            return view('reg_college.curriculum_management.view_course_offering_free_section', compact('programs'));
        }
    }  

    function viewofferings_tutorials() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(array('program_code', 'program_name'));
            return view('reg_college.curriculum_management.view_course_offering_tutorials', compact('programs'));
        }
    }
}
