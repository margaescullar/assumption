<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;

class AllTermSummaryAjax extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function all_term_view_list() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("BED_ACADEMIC_DIRECTOR")) {
                $school_year = Input::get('school_year');
                $level = Input::get('level');
                $section = Input::get('section');
                $period = Input::get('period');
                $strand = Input::get("strand");
                $is_ee = Input::get('is_ee');
                $type= "default";
                if($section == "All"){
                    $section = '%%';
                }

                $lists = self::getListSubjectHeads($school_year, $level, $section, $period, $strand, 'lists',$is_ee);
                $subject_heads = self::getListSubjectHeads($school_year, $level, $section, $period, $strand, 'subject_heads',$is_ee);

                return view("reg_be.ajax.all_term_view_list", compact('school_year', 'level', 'section', 'period', 'strand', 'lists', 'subject_heads', 'is_ee','type'));
            }
        }
    }

    public static function getListSubjectHeads($school_year, $level, $section, $period, $strand, $display,$is_ee) {
        if ($level == "Grade 11" || $level == "Grade 12") {
            $lists = \App\BedLevel::where('school_year', $school_year)->where('period', $period)->where('strand', $strand)->where('level', $level)->where('section', 'like', $section)->where('status', env('ENROLLED'))->join('users','users.idno','=','bed_levels.idno')->orderBy('lastname','asc')->get();
            $subject_heads = \App\GradeBasicEd::distinct()->where('school_year', $school_year)->where('period', $period)->where('strand', $strand)->where('level', $level)->get(['group_code']);
        } elseif ($level == "Pre-Kinder" || $level == "Kinder") {
            dd('not yet started...');
        } else {
            $lists = \App\BedLevel::where('school_year', $school_year)->where('level', $level)->where('section', 'like',$section)->where('bed_levels.status', env('ENROLLED'))->join('users','users.idno','=','bed_levels.idno')->orderBy('lastname','asc')->get();
            $subject_heads = \App\GradeBasicEd::distinct()->where('school_year', $school_year)->where('level', $level)->orderBy('sort_to')->get(['group_code']);
        }

        if (!$lists->isEmpty()) {
            foreach ($lists as $key => $list) {
                $list->firstname = \App\User::where('idno', $list->idno)->first()->firstname;
                $list->lastname = \App\User::where('idno', $list->idno)->first()->lastname;
                $grades = \App\GradeBasicEd::distinct()->where('school_year', $school_year)->where('level', $level)->where('idno', $list->idno)->get();

                if (!$grades->isEmpty()) {
                    $list->grades = $grades;
                } else {
                    $list->grades = null;
                }
                if($is_ee == 1){
                    if (!self::checkEEgrades($grades)) {
                        unset($lists[$key]);
                    }
                }
            }
        }
        if ($display == "lists") {
            return $lists;
        } elseif ($display == "subject_heads") {
            return $subject_heads;
        }
    }

    static function checkEEgrades($grades) {
        foreach ($grades as $grade) {
            $first = $grade->first_grading_letter;
            $second = $grade->second_grading_letter;
            $third = $grade->third_grading_letter;
            $fourth = $grade->fourth_grading_letter;

            
//            if ($first == null ) {
//            }elseif($first != "EE"){
//                return false;
//            }elseif($first ==""){}
            
            if ($second == null ) {
            }elseif($second != "EE"){
                return false;
            }elseif($second ==""){}
            
            if ($third == null ) {
            }elseif($third != "EE"){
                return false;
            }elseif($third ==""){}
            
            if ($fourth == null ) {
            }elseif($fourth != "EE"){
                return false;
            }elseif($fourth ==""){}
            
        }
        return true;
        
    }

}
