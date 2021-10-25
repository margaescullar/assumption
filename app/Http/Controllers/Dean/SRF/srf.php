<?php

namespace App\Http\Controllers\Dean\SRF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use Session;

class srf extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            $programs = \App\Curriculum::distinct()->get(['program_code', 'program_name']);
            return view('dean.srf.view_srf', compact('programs'));
        }
    }
    
    function modify_srf($period,$course_code){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            $course_name = \App\Curriculum::where('course_code', $course_code)->where('period', $period)->first()->course_name;
            $srf = \App\Curriculum::where('course_code', $course_code)->where('period', $period)->first()->srf;
            $lab_fee = \App\Curriculum::where('course_code', $course_code)->where('period', $period)->first()->lab_fee;
            $srf_group = \App\Curriculum::where('course_code', $course_code)->where('period', $period)->first()->srf_group;
//            return $srf_group;
            return view('dean.srf.modify_srf', compact('course_code', 'course_name', 'srf','lab_fee','period','srf_group'));
        }
    }
    
    function set_srf(Request $request){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            $course_code = $request->course_code;
            $sets = \App\Curriculum::where('course_code', $course_code)->where('period', $request->period)->get();
            $elects = \App\CtrElective::where('course_code', $course_code)->get();
            
            foreach ($sets as $set){
                $set->srf = $request->srf;
                $set->lab_fee = $request->lab_fee;
                $set->srf_group = $request->srf_group;
                $set->save();
            }
            
            foreach ($elects as $elect){
                $elect->srf = $request->srf;
                $elect->lab_fee = $request->lab_fee;
                $elect->srf_group = $request->srf_group;
                $elect->save();
            }
            
            Session::flash('message', "SRF and Laboratory Fee Updated!");
            \App\Http\Controllers\Admin\Logs::log("Set SRF of $course_code to PHP $request->srf and Lab Fee to PHP $request->lab_fee");
            return redirect("dean/srf/modify/$request->period/$course_code");
        }
    }
    
    function print_index(){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            $programs = \App\Curriculum::distinct()->get(['program_code', 'program_name']);
            $curriculum_years = \App\Curriculum::distinct()->get(['curriculum_year']);
            return view('dean.srf.print_index', compact('curriculum_years', 'programs'));
        }
    }
    
    function print_srf_now($program_code, $level, $period, $curriculum_year){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            $programs = \App\Curriculum::where('program_code', $program_code)->where('level', $level)->where('period', $period)->where('curriculum_year', $curriculum_year)->get();
            $program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;

            $pdf = PDF::loadView('dean.srf.print_srf_report', compact('programs', 'program_name', 'program_code', 'level', 'period', 'curriculum_year'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("srf_report.pdf");
            
        }
    }
    
    function student_list(){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            $programs = \App\Curriculum::distinct()->get(['program_code', 'program_name']);
            return view('dean.srf.view_student_srf', compact('programs'));
        }
    }
    
    function print_srf_list_now($school_year, $period, $course_code){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            
            $lists = \App\GradeCollege::where('grade_colleges.school_year', $school_year)->where('grade_colleges.period', $period)->where('grade_colleges.course_code', $course_code)->join('users', 'users.idno','=','grade_colleges.idno')->join('statuses', 'statuses.idno','=','grade_colleges.idno')->orderBy('users.lastname', 'asc')->get();
            $course_name = \App\Curriculum::where('course_code', $course_code)->first();
            if(count($course_name) == 0){
                $course_name = \App\CtrElective::where('course_code', $course_code)->first();
            }
            $course_name = $course_name->course_name;

            $pdf = PDF::loadView('dean.srf.print_srf_student_list', compact('lists', 'school_year','period','course_code','course_name'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("srf_list.pdf");
            
        }
    }
    
    function srf_balances(){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            $programs = \App\Curriculum::distinct()->get(['program_code', 'program_name']);
            return view('dean.srf.srf_balances', compact('programs'));
        }
    }
    
    
    
    function print_srf_balances($school_year, $period,$program_code){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('AA')) {
            
            $number=1;
            $lists = \App\Ledger::distinct('ledgers.idno', 'users.lastname', 'users.firstname', 'users.middlename')
                    ->selectRaw("sum(payment) as total_payment,sum(discount) as total_discount,sum(debit_memo) as total_dm, sum(amount) as total_amount, ledgers.idno, users.lastname, users.firstname, users.middlename")
                    ->where('ledgers.school_year', $school_year)
                    ->where('ledgers.period', $period)
                    ->where('ledgers.program_code', '!=', null)
                    ->where('ledgers.category','SRF')
                    ->where('statuses.program_code', $program_code)
                    ->groupBy('ledgers.idno')
                    ->join('users', 'users.idno','=','ledgers.idno')
                    ->join('statuses', 'statuses.idno','=','ledgers.idno')
                    ->orderBy('users.lastname', 'asc')
                    ->get(array('ledgers.idno','users.firstname','users.lastname','users.middlename','total_dm','total_discount','total_payment','total_amount'));
            
            $pdf = PDF::loadView('dean.srf.print_srf_balances', compact('lists', 'school_year', 'period','number'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("srf_balances_report.pdf");
            
        }
    }
}
