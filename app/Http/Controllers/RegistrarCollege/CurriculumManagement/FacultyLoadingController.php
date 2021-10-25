<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Session;

class FacultyLoadingController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.faculty_loading');
        }
    }

    function edit_faculty_loading($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.edit_faculty_loading', compact('idno'));
        }
    }
    
    function add_faculty_loading(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $count=0;
            $instructor_id = $request->instructor_id;
            $schedule_id = $request->schedule_id;
            
            $school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
            
            $selected_schedules = \App\ScheduleCollege::where('schedule_id', $schedule_id)->get();
                       
            foreach($selected_schedules as $selected){
            $is_conflict = \App\ScheduleCollege::
                    where('schedule_colleges.instructor_id', $instructor_id)
                    ->where('schedule_colleges.school_year', $school_year->school_year)
                    ->where('schedule_colleges.period', $school_year->period)
                    ->where('schedule_colleges.schedule_id', '!=',$schedule_id)
//                    ->where('schedule_college.schedule_id', $info_course_offering->schedule_id)
                    ->where('schedule_colleges.day', $selected->day)
                    ->where(function($q) use ($selected) {
                        $q->whereBetween('time_start', array(date("H:i:s", strtotime($selected->time_start)), date("H:i:s", strtotime($selected->time_end))))
                        ->orwhereBetween('time_end', array(date("H:i:s", strtotime($selected->time_start)), date("H:i:s", strtotime($selected->time_end))));
                    })
                    ->get();
            $count = $count + count($is_conflict);
            }
            //for bypassing the conflict checker
                $count=0;
                
            if ($count <= 0){            
                $updatecourse_offering = \App\ScheduleCollege::where('schedule_id', $schedule_id)->get();
                foreach($updatecourse_offering as $updatesched){
                $updatesched->instructor_id = "$instructor_id";
                $updatesched->save();
                }

                \App\Http\Controllers\Admin\Logs::log("Assign loading this schedule:$schedule_id to $instructor_id");
                
            }else{
                Session::flash('message', "There is a conflict in schedule!");
            }
            return redirect("/registrar_college/curriculum_management/edit_faculty_loading/$instructor_id");
        }
    }
    
    function remove_faculty_loading($schedule_id, $idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $updatecourse_offering = \App\ScheduleCollege::where('schedule_id', $schedule_id)->get();
            foreach($updatecourse_offering as $updatesched){
            $updatesched->instructor_id = NULL;
            $updatesched->save();
            }
            \App\Http\Controllers\Admin\Logs::log("Remove loading this schedule:$schedule_id");
            
            return redirect("/registrar_college/curriculum_management/edit_faculty_loading/$idno");
        }
    }
    function print_faculty_loading($idno){
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
            $faculty = \App\User::where('idno', $idno)->first();
            
            $user = \App\User::where('idno', $idno)->first();
            $loads = \App\ScheduleCollege::distinct()->where('instructor_id', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get(['schedule_id', 'course_code']);
            $courses = \App\CourseOffering::distinct()->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get(['course_name', 'course_code']);
            
            $pdf = PDF::loadView('reg_college.curriculum_management.print_faculty_loading',compact('courses','school_year','faculty','user','loads'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('faculty_loading.pdf'); 
        }
    }

}
