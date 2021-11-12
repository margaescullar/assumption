<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxAccoReportsController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function getoustanding_balance() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
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
            return view('accounting.ajax.getoutstanding_balance', compact('department', 'lists','heads','school_year','period'));
        }
    }
    
    function get_student_per_account(){
        if(Request::ajax()){
            $account = Input::get('account');
            $school_year = Input::get('school_year');
            $department = Input::get('department');
            $period = Input::get('period');
            
            if ($department == "College Department") {
                $dep = '%Department';
//                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' and period = '$period' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, college_levels s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' and period = '$period' GROUP BY accounting_code,idno)and s.school_year = '$school_year' and s.period = '$period' and s.department LIKE '$dep' and s.status = '".env("ENROLLED")."' ORDER BY u.lastname,s.program_code,s.level,s.section");
            } 
            else {
                $dep = $department;
                if($department == "Senior High School"){
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section FROM users u, bed_levels s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' and period = '$period' GROUP BY accounting_code,idno) and s.school_year = '$school_year' and s.period = '$period' and s.department LIKE '$dep' and s.status = '".env("ENROLLED")."' ORDER BY u.lastname,s.level,s.section");
                }
                else{
                    //$lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
                    $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.level,s.section FROM users u, bed_levels s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' GROUP BY accounting_code,idno) and s.department LIKE '$dep' and s.status = '".env("ENROLLED")."' ORDER BY u.lastname,s.level,s.section");
                }
            }
            
            $info = \App\ChartOfAccount::where('accounting_code',$account)->first();
            return view('accounting.ajax.get_student_per_account', compact('info', 'lists','account','department','school_year','period'));
        
        }
    }
    
    function get_sibling_discount_list(){
        if(Request::ajax()){
            $dep = "";
            $department = Input::get('department');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            $subsidiary = "Student Development Fee";
            
            if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' and (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.amount != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' and (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            return view('accounting.ajax.get_sibling_discount', compact('lists','heads','department','school_year','period','subsidiary'));
        
        }
    }

    function getstudentrelatedfees() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            if ($department == "College Department") {
                $dep = '%Department';
                    $levels = array('1st Year', '2nd Year', '3rd Year', '4th Year');
                    $groups = array('General Education','Laboratory','Thesis', 'Business Department', 'Communication Department', 'Performing Department', 'Education Department', 'Psychology Department','Interior Design Department',NULL);
            } else {
                $dep = $department;
                if($dep == 'Senior High School'){
                    $levels = array("Grade 11", 'Grade 12');
                }
            }
            return view('accounting.srf.ajax.getstudentrelatedfees', compact('department','school_year','period', 'levels','groups'));
        }
    }
    
    
    function getTrialBalance(){
        $date_to = Input::get('date_to');
        $date_from = Input::get('date_from');
        $finalStartDate = "$date_from";
        $finalEndDate = "$date_to";
        
        $lists = \App\Accounting::join('chart_of_accounts','accountings.accounting_code','chart_of_accounts.accounting_code')
                ->selectRaw('accountings.accounting_code, chart_of_accounts.accounting_name, case when (sum(debit) - sum(credit)) > 0 then sum(debit) - sum(credit) end as debit,case when (sum(debit) - sum(credit)) < 0 then sum(debit) - sum(credit) end as credit')
                ->where('is_reverse',0)->where('accounting_type','>', 10)->whereBetween('transaction_date', [$finalStartDate, $finalEndDate])
                ->groupBy('accountings.accounting_code')->get();
        return view('accounting.ajax.display_trial_balance', compact('lists', 'finalStartDate','finalEndDate'));
    }
    
    function getGeneralLedger(){
        $date_to = Input::get('date_to');
        $date_from = Input::get('date_from');
        $accounting_code = Input::get('code');
        $finalStartDate = "$date_from";
        $finalEndDate = "$date_to";
        
        $entries = \App\Accounting::where('accounting_code',$accounting_code)
                ->where('is_reverse',0)->where('accounting_type','>', 10)->whereBetween('transaction_date', [$finalStartDate, $finalEndDate])
                ->orderBy('transaction_date')->get();
        $account = \App\ChartOfAccount::where('accounting_code',$accounting_code)->first();
        return view('accounting.ajax.display_general_ledger', compact('entries','account', 'finalStartDate','finalEndDate'));
    }

}
