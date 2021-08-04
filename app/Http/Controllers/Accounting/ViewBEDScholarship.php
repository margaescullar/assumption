<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;
use App\Http\Controllers\BedRegistrar\SiblingsBenefits;

class ViewBEDScholarship extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_BED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $scholar = \App\BedScholarship::where('idno', $idno)->first();
            if(!isset($scholar)){
                $new_scholar = new \App\BedScholarship;
                $new_scholar->idno=$idno;
                $new_scholar->save();
            }
            $scholar = \App\BedScholarship::where('idno', $idno)->first();
            return view('accounting.benefit_scholar.bedview', compact('scholar', 'idno'));
        }
    }
    
//    function print_scholarship($idno){
//        if (Auth::user()->accesslevel == env("SCHOLARSHIP_HED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
//            $info = \App\User::where('idno',$idno)->first();
//            $status = \App\Status::where('idno',$idno)->first();
//            $enrollment_sy = \App\CtrEnrollmentSchoolYear::where('academic_type',"College")->first();
//            $scholar = \App\CollegeScholarship::where('idno', $idno)->first();
//            
//            $pdf = PDF::loadView('scholarship_hed.view_scholarship.print_scholarship',compact('scholar','idno','info','status', 'enrollment_sy'));
//            $pdf->setPaper('letter','portrait');
//          return $pdf->stream('scholarship_cerfiticate.pdf');
//        }
//    }

    function update_now(Request $request) {
        if (Auth::user()->accesslevel == env("SCHOLARSHIP_BED") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            DB::beginTransaction();
            SiblingsBenefits::remove_benefits($request->idno);
            $scholar = \App\BedScholarship::where('idno', $request->idno)->first();
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
                $scholar->remarks = "";
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
                $scholar->remarks = $request->remarks;
            }
            $scholar->save();
            \App\Http\Controllers\Accounting\SetReceiptController::log("Update scholarship of " . $request->idno . "TF:$request->tf, OF:$request->of, NonD:$request->non_discounted, SRF: $request->srf");
            DB::commit();
            return redirect(url('/accounting/bed_view_scholar/' . $request->idno));
        }
    }

}
