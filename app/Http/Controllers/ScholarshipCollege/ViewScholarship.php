<?php

namespace App\Http\Controllers\ScholarshipCollege;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;

class ViewScholarship extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $scholar = \App\CollegeScholarship::where('idno', $idno)->first();
            return view('scholarship_hed.view_scholarship.view', compact('scholar', 'idno'));
        }
    }
    
    function print_scholarship($idno){
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $info = \App\User::where('idno',$idno)->first();
            $status = \App\Status::where('idno',$idno)->first();
            $enrollment_sy = \App\CtrEnrollmentSchoolYear::where('academic_type',"College")->first();
            $scholar = \App\CollegeScholarship::where('idno', $idno)->first();
            
            $pdf = PDF::loadView('scholarship_hed.view_scholarship.print_scholarship',compact('scholar','idno','info','status', 'enrollment_sy'));
            $pdf->setPaper('letter','portrait');
          return $pdf->stream('scholarship_cerfiticate.pdf');
        }
    }

    function update_now(Request $request) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $scholar = \App\CollegeScholarship::where('idno', $request->idno)->first();
            if ($request->discount_code == "") {
                $scholar->discount_code = "";
                $scholar->discount_description = "";
                $scholar->accounting_code = "";
                $scholar->tuition_fee = 0;
                $scholar->other_fee = 0;
                $scholar->misc_fee = 0;
                $scholar->depository_fee = 0;
                $scholar->non_discounted = 0;
                $scholar->srf = 0;
                $scholar->amount = 0;
                $scholar->dorm = 0;
                $scholar->meal = 0;
                $scholar->remarks = 0;
            } else {
                $scholar->discount_code = "$request->discount_code";
                $scholar->discount_description = \App\CtrDiscount::where('discount_code', $request->discount_code)->first()->discount_description;
                $scholar->accounting_code = \App\CtrDiscount::where('discount_code', $request->discount_code)->first()->accounting_code;
                $scholar->tuition_fee = $request->tf;
                $scholar->other_fee = $request->of;
                $scholar->misc_fee = $request->mf;
                $scholar->depository_fee = $request->df;
                $scholar->non_discounted = $request->non_discounted;
                $scholar->srf = $request->srf;
                $scholar->dorm = $request->dorm;
                $scholar->meal = $request->meal;
                $scholar->remarks = $request->remarks;
            }
            $scholar->save();
            $this->updateAdmissionHED($request);
            \App\Http\Controllers\Admin\Logs::log("Update scholarship of" . $request->idno);
            return redirect(url('/scholarship_college/view_scholar/' . $request->idno));
        }
    }

    function updateAdmissionHED($request) {
        $scholar = \App\AdmissionHed::where('idno', $request->idno)->first();
        if ($request->discount_code == "") {
            $scholar->admission_status = "";
            $scholar->assumption_scholar = "";
        } else {
            $scholar->admission_status = "Scholar";
            $scholar->assumption_scholar = \App\CtrDiscount::where('discount_code', $request->discount_code)->first()->discount_description;
        }
        $scholar->save();
    }

    function view_schedule($idno) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $status = \App\Status::where('idno', $idno)->first();
            $school_year = $status->school_year;
            $period = $status->period;
            if ($status->status >= env('ENROLLED')) {
                return view('scholarship_hed.view_schedule', compact('status', 'idno', 'school_year', 'period'));
            } else {
                return redirect(url('/'));
            }
        }
    }

}
