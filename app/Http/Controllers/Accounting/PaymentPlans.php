<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use PDF;
use Excel;

class PaymentPlans extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $school_years = \App\Status::distinct()->orderBy('school_year','asc')->get(['school_year']);
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            return view('accounting.payment_plans.payment_plans',compact('school_years','levels'));
        }
        
    }

    function payment_plans_excel(Request $request) {
            $school_year = $request->school_year;
            $period = $request->period;
            $department = $request->department;

            ob_end_clean();
            Excel::create('Payment Plans - ' .$department, 
                function($excel) use ($department,$school_year,$period) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$school_year,$period) {
                    $sheet->loadView('accounting.payment_plans.payment_plans_excel', compact('department','school_year','period'));
                    });
                })->download('xlsx');
            
//            return view('accounting.payment_plans.payment_plans_excel',compact('school_year','period','department'));
    }

    function payment_plans_pdf(Request $request) {
            $school_year = $request->school_year;
            $period = $request->period;
            $department = $request->department;

            $pdf = PDF::loadView('accounting.payment_plans.payment_plans_pdf', compact('department','school_year','period'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("payment_plans.pdf");
            
//            return view('accounting.payment_plans.payment_plans_pdf',compact('school_year','period','department'));
    }
    
    function payment_plans_student(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $school_years = \App\Status::distinct()->orderBy('school_year','asc')->get(['school_year']);
            $levels = \App\CtrAcademicProgram::distinct()->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            return view('accounting.payment_plans.payment_plans_student',compact('school_years','levels'));
        }
        
    }

    function payment_plans_student_excel(Request $request) {
            $school_year = $request->school_year;
            $period = $request->period;
            $level = $request->level;

            ob_end_clean();
            Excel::create('Payment Plans - ' .$level, 
                function($excel) use ($level,$school_year,$period) { $excel->setTitle($level);
                    $excel->sheet($level, function ($sheet) use ($level,$school_year,$period) {
                    $sheet->loadView('accounting.payment_plans.payment_plans_student_excel', compact('level','school_year','period'));
                    });
                })->download('xlsx');
            
//            return view('accounting.payment_plans.payment_plans_excel',compact('school_year','period','department'));
    }

    function payment_plans_student_pdf(Request $request) {
            $school_year = $request->school_year;
            $period = $request->period;
            $level = $request->level;

            $pdf = PDF::loadView('accounting.payment_plans.payment_plans_student_pdf', compact('level','school_year','period'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("payment_plans.pdf");
            
//            return view('accounting.payment_plans.payment_plans_pdf',compact('school_year','period','department'));
    }
    
}
