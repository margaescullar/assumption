<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Barryvdh\DomPDF\PDF;
use PDF;
use Excel;

class OutstandingBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function outstanding_balance(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD') || Auth::user()->accesslevel == env('REG_BE')) {
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            return view('accounting.outstanding_balance',compact('levels'));
        }
    }
    
    function print_outstanding_balancePDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD') || Auth::user()->accesslevel == env('REG_BE')) {
            
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if ($department == "College Department") {
                $dep = '%Department';
            
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l ,college_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."')  AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section");
                $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,college_levels s WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '$school_year' AND s.period = '".$period."' and (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
            } 
            else {
                $dep = $department;
                if($dep == 'Senior High School'){
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section ");
                    $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount)) - sum(payment)) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,bed_levels s WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '$school_year' AND s.period = '".$period."' AND  (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
                }
                else{
                    $period = "";
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND  (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') AND s.school_year = '$school_year' ORDER BY u.lastname,s.level,s.section ");
                    $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,bed_levels s WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '$school_year' AND  (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
                }
            }
            
             \App\Http\Controllers\Admin\Logs::log("Print Outstanding Balance PDF");
            $pdf = PDF::loadView('accounting.print_outstanding_balance', compact('department','lists','heads','school_year','period'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("outstanding_balance.pdf");
        }
        
    }
    
    function print_outstanding_balanceEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD') || Auth::user()->accesslevel == env('REG_BE')) {
            $dep = "";
            $date = date("F d, Y");
            
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
             \App\Http\Controllers\Admin\Logs::log("Download Outstanding Balance EXCEL");
            
            
            if ($department == "College Department") {
                $dep = '%Department';
            
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l ,college_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."')  AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section");
                $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,college_levels s WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '$school_year' AND s.period = '".$period."' and (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
            } 
            else {
                $dep = $department;
                if($dep == 'Senior High School'){
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND s.status = '".env('ENROLLED')."' AND s.school_year = '$school_year' AND s.period = '".$period."' ORDER BY u.lastname,s.level,s.section ");
                    $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount)) - sum(payment)) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' AND period = '".$period."' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,bed_levels s WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '$school_year' AND s.period = '".$period."' AND  (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
                }
                else{
                    $period = "";
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section,s.type_of_plan, l.balance FROM users u,(SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' GROUP BY idno) l ,bed_levels s WHERE s.idno = u.idno and l.balance != 0.00 and u.idno = l.idno and s.department LIKE '$dep' AND  (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') AND s.school_year = '$school_year' ORDER BY u.lastname,s.level,s.section ");
                    $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` WHERE school_year = '$school_year' GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr,bed_levels s WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level AND s.school_year = '$school_year' AND  (s.status = '".env('ENROLLED')."' or s.status = '".env('WITHDRAWN')."') GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
                }
            }
            
            ob_end_clean();
            Excel::create('Outstanding Balances - '.$date, 
                function($excel) use ($department,$lists,$heads,$school_year,$period) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$lists,$heads,$school_year,$period) {
                    $sheet->loadView('accounting.print_outstanding_balance_excel', compact('department','lists','heads','school_year','period'));
                    });
                })->download('xlsx');
            
        }
        
    }
}

