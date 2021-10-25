<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Knp\Snappy\Pdf as Pdfs;

class ViewRoomSchedule extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.view_room_schedules');
        }
    }

    function print_room_schedule($school_year, $period, $room) {
        $selected_room = $room;
        $rooms = \App\ScheduleCollege::distinct()->where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->where('schedule_colleges.room', $selected_room)->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->get(array('schedule_colleges.course_code', 'schedule_colleges.schedule_id', 'room', 'day','time_start','time_end','instructor_id'));
//        $rooms = \App\ScheduleCollege::distinct()->where('schedule_colleges.school_year', $school_year)->where('schedule_colleges.period', $period)->where('grade_colleges.idno','19099')->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->join('grade_colleges', 'grade_colleges.course_offering_id', '=', 'course_offerings.id' )->get(array('schedule_colleges.course_code', 'schedule_colleges.schedule_id', 'room', 'day','time_start','time_end','instructor_id'));

            foreach ($rooms as $key=>$room){
                switch ($room->day) {
                    case "M": $room->day = "monday"; break;
                    case "T": $room->day = "tuesday"; break;
                    case "W": $room->day = "wednesday"; break;
                    case "Th": $room->day = "thursday"; break;
                    case "F": $room->day = "friday"; break;
                    case "S": $room->day = "saturday"; break;
                }
                $color_now = "#".substr($room->schedule_id, -6);
                if($room->instructor_id != NULL){
                $instructor = \App\User::where('idno', $room->instructor_id)->first();
                    $instructor_name = '<br>'.$instructor->firstname.' '. $instructor->lastname;
                } else {
                    $instructor_name = "";
                }
                
                $date = date( 'Y-m-d', strtotime( $room->day.' this week' ) );
                
                $event_array[] = array(
                    'title' => $room->course_code.' '.$instructor_name,
                    'start' => $date.'T'.$room->time_start,
                    'end' => $date.'T'.$room->time_end,
                    'color' => $color_now,
                    "textEscape"=> 'false' ,
                    'textColor' => 'black'
                );

//                $events[$key] = \Calendar::event(
//                        $room->course_code. ' '.$instructor_name,
//                        false,
//                        $date.'T'.$room->time_start,
//                        $date.'T'.$room->time_end,
//                        $room->schedule_id,
//                            [
//                                'color' => "$color_now",
//                                'textColor' => "black",
//                                'textEscape' => 'false'
//                            ]
//                );
            }

            $event_json = json_encode($event_array);
            
            return view('reg_college.curriculum_management.ajax.generateRoom2',compact('selected_room', 'event_json'));

//            $calendar = \Calendar::addEvents($events)
//                            ->setOptions([
//                                'firstDay' => 0,
//                                'header' => false,
//                                'columnFormat' => 'ddd',
//                                'allDaySlot' => false,
//                                'defaultView' => 'agendaWeek',
//                                'minTime' => '07:00:00',
//                                'maxTime' => '20:00:00',
//                                'height' => 650,
//                            ])->setCallbacks([
//            ]);
//        
//            return view('reg_college.curriculum_management.ajax.generateRoom2', array('calendar' => $calendar), compact('selected_room', 'event_json'));
    }

}
