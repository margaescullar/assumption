<?php

namespace App\Http\Controllers\RegistrarCollege\GradeManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class GradingSY extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function read() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $school_year = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first();
            
            return view('reg_college.grade_management.grading_sy', compact('school_year'));
        }
    }

    function update(Request $request, $id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $update = \App\CtrGradeSchoolYear::find($id);
            $update->school_year = $request->school_year;
            $update->period = $request->period;
            $update->save();
            
            \App\Http\Controllers\Admin\Logs::log("Update Grading School Period to: $request->school_year, $request->period");
            
            return redirect('/registrar_college/grade_management/grading_sy');
        }
    }
}
