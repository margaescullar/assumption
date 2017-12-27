<?php

namespace App\Http\Controllers\Dean\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class Assessment extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function assess($idno) {
        if (Auth::user()->accesslevel == env('DEAN')) {
            $status = \App\Status::where('idno', $idno)->first();
            if ($status->status == 0) {
                return view('dean.assessment.assess', compact('status', 'idno'));
            } else if ($status->status == 1 || $status->status == 2) {
                return redirect(url('dean', array('assessment', 'confirm_advised', $idno, $status->program_code, $status->level)));
            } else if ($status->status == 3) {
                return view('dean.assessment.enrolled', compact('status', 'idno'));
            } else {
                return view('dean.assessment.enrolled', compact('status', 'idno'));
            }
        }
    }

    function confirm_advised($idno, $program_code, $level) {
        if (Auth::user()->accesslevel == env('DEAN')) {
            
            $program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;
            
            $updatestatus = \App\Status::where('idno', $idno)->first();
            $updatestatus->status = 1;
            $updatestatus->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
            $updatestatus->program_code = "$program_code";
            $updatestatus->program_name = "$program_name";
            $updatestatus->level = "$level";
            $updatestatus->save();

            return view('dean.assessment.confirmed_advised', compact('idno'));
        }
    }

    function print_advising_slip($idno) {
        if (Auth::user()->accesslevel == env('DEAN')) {

            $pdf = PDF::loadView('dean.assessment.advising_slip', compact('idno'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("advising_slip_". $idno .".pdf");
        }
    }

}
