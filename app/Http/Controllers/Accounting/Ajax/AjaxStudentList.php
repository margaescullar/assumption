<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxStudentList extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function get_studentlist() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');
            $school_year = Input::get("school_year");
            $period = Input::get("period");

            if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND c.status = '3' ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.assessment != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND c.status = '3' GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $dep = $department;                
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            return view('accounting.ajax.get_studentlist', compact('department','school_year','period','lists','heads'));
        }
    }
}
