<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class CurriculumController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.curriculum');
        }
    }

    function viewcurricula($program_code) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            $curricula = \App\Curriculum::distinct()->where('program_code', $program_code)->get(array('curriculum_year'));
            return view('reg_college.curriculum_management.view_curricula', compact('curricula', 'program_code'));
        }
    }

    function listcurriculum($program_code, $curriculum_year) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            return view('reg_college.curriculum_management.list_curriculum', compact('program_code', 'curriculum_year'));
        }
    }

}
