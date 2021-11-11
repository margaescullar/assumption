<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;
use PDF;
use Excel;

class GetStudentList2 extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE")) {
                $search = Input::get('search');
                $lists = \App\User::where(function ($query) use ($search){
                        $query->where("lastname","like","%$search%")
                              ->orWhere("firstname","like","%$search%")
                              ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"),"like","%$search%")
                              ->orWhere("idno",$search);
                    })->get();
//                $lists = \App\User::Where("lastname", "like", "%$search%")
//                                ->orWhere("firstname", "like", "%$search%")->orWhere("idno", $search)->get();
                return view('reg_be.ajax.getstudentlist', compact('lists'));
            }
        }
    }

    function view_list() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env('ADMISSION_BED') || Auth::user()->accesslevel == env("EDUTECH") || Auth::user()->accesslevel == env("BED_CL")) {
                $schoolyear = Input::get('school_year');
                $level = Input::get('level');
                $section = Input::get('section');
                $period = Input::get('period');

                $strand = Input::get("strand");
                if ($level == "Grade 11" || $level == "Grade 12") {
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
			$students = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
                        $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and promotions.section = '$section' and promotions.strand = '$strand' order by lastname, firstname, middlename");
                    }
                } else {
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
                        $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and promotions.section = '$section' order by lastname, firstname, middlename");
                    }
                }
                return view("reg_be.ajax.view_list", compact("status", "level", "section", 'strand', 'schoolyear', 'period','students'));
            }
        }
    }

    function getsection() {
        if (Request::ajax()) {
            $level = Input::get("level");
            if ($level == "Grade 11" || $level == "Grade 12") {
                $strand = Input::get("strand");
                $sections = \App\CtrSectioning::where('level', $level)->where('strand', $strand)->orderBy('section')->get();
            } else {
                $sections = \App\CtrSectioning::where('level', $level)->orderBy('section')->get();
            }
            return view('reg_be.ajax.getsection', compact('sections'));
        }
    }

    function print_student_list($level, $strand, $section, $schoolyear, $period, $value) {
        if ($level == "Grade 11" || $level == "Grade 12") {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        } else {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        }
        $pdf = PDF::loadView("reg_be.view_list", compact("status", "level", "section", 'strand', 'value', 'schoolyear', 'period'));
        $pdf->setPaper(array(0, 0, 612, 936));
        return $pdf->stream();
    }

    function print_new_student_list($level, $strand, $section, $schoolyear, $period, $value) {
        if ($level == "Grade 11" || $level == "Grade 12") {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.is_new  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' and bed_levels.is_new = 1 "
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.is_new  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' and bed_levels.is_new = 1  "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        } else {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.is_new  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.is_new = 1"
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.is_new  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.is_new = 1  "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        }
        $pdf = PDF::loadView("reg_be.view_list", compact("status", "level", "section", 'strand', 'value', 'schoolyear', 'period'));
        $pdf->setPaper(array(0, 0, 612, 936));
        return $pdf->stream();
    }

    function studentlevel() {
        if (Request::ajax()) {
            $strand = "";
            $level = Input::get('level');
            $section = Input::get('section');
            $type = Input::get('type');
            
            if (Auth::user()->accesslevel == env('REG_BE') or $type != "pre_sectioning") {
                if ($level == "Grade 11" || $level == "Grade 12") {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first();
                    $schoolyear = $school_year->school_year;
                    $period = $school_year->period;
                    $strand = Input::get('strand');
                    //$students =  \App\BedLevel::where('level',$level)->where('strand',$strand)->where('school_year',$school_year->school_year)->where('section','!=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                                    . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                                    . " and bed_levels.level = '$level' and bed_levels.period = '$period' and bed_levels.school_year = '$schoolyear' and (bed_levels.section != '$section' or bed_levels.section is null) and bed_levels.strand= '$strand' order by lastname, firstname, middlename");
                } else {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first();
                    $schoolyear = $school_year->school_year;
                    //$students =  \App\BedLevel::where('level',$level)->where('school_year',$school_year->school_year)->where('section','!=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                                    . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                                    . " and bed_levels.level = '$level' and  bed_levels.school_year = '$schoolyear' and (bed_levels.section != '$section' or bed_levels.section is null)  order by lastname, firstname, middlename");
                }
            } else if (Auth::user()->accesslevel == env('GUIDANCE_BED') and $type == "pre_sectioning") {
                if ($level == "Grade 11" || $level == "Grade 12") {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first();
                    $schoolyear = $school_year->school_year;
                    $period = $school_year->period;
                    $strand = Input::get('strand');
                    //$students =  \App\BedLevel::where('level',$level)->where('strand',$strand)->where('school_year',$school_year->school_year)->where('section','!=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and (promotions.section != '$section' or promotions.section is null) and promotions.strand= '$strand' order by lastname, firstname, middlename");
                } else {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first();
                    $schoolyear = $school_year->school_year;
                    //$students =  \App\BedLevel::where('level',$level)->where('school_year',$school_year->school_year)->where('section','!=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and (promotions.section != '$section' or promotions.section is null)  order by lastname, firstname, middlename");
                }
            }
            return view('reg_be.ajax.studentlevel_list', compact('level', 'strand', 'students', 'school_year'));
        }
    }

    function sectioncontrol() {
        if (Request::ajax()) {
            $strand = "";
            $level = Input::get('level');
            if ($level == "Grade 11" || $level == "Grade 12") {
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first();
                $strand = Input::get('strand');
                $sections = \App\CtrSectioning::where('level', $level)->where('strand', $strand)->get();
            } else {
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first();
                $sections = \App\CtrSectioning::where('level', $level)->get();
            }
            return view('reg_be.ajax.sectioncontrol', compact('level', 'strand', 'sections'));
        }
    }

    function pop_section_list() {
        if (Request::ajax()) {
            $strand = "";
            $level = Input::get('level');
            $section = Input::get('section');
            $type = Input::get('type');
            
            if (Auth::user()->accesslevel == env('REG_BE') or $type != "pre_sectioning") {
                if ($level == "Grade 11" || $level == "Grade 12") {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first();
                    $schoolyear = $school_year->school_year;
                    $period = $school_year->period;
                    $strand = Input::get('strand');
                    //$students =  \App\BedLevel::where('level',$level)->where('strand',$strand)->where('school_year',$school_year->school_year)->where('section','=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                                    . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                                    . " and bed_levels.level = '$level' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.section = '$section' and bed_levels.strand= '$strand' order by lastname, firstname, middlename");
                } else {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first();
                    $schoolyear = $school_year->school_year;
                    //$students =  \App\BedLevel::where('level',$level)->where('school_year',$school_year->school_year)->where('section','=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  bed_levels.idno as idno, "
                                    . " bed_levels.level as level, bed_levels.strand as strand, bed_levels.section as section from users, bed_levels where users.idno = bed_levels.idno "
                                    . " and bed_levels.level = '$level' and bed_levels.school_year = '$schoolyear' and bed_levels.section = '$section' order by lastname, firstname, middlename");
                }
            } else if (Auth::user()->accesslevel == env('GUIDANCE_BED') and $type == "pre_sectioning") {
                if ($level == "Grade 11" || $level == "Grade 12") {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first();
                    $schoolyear = $school_year->school_year;
                    $period = $school_year->period;
                    $strand = Input::get('strand');
                    //$students =  \App\BedLevel::where('level',$level)->where('strand',$strand)->where('school_year',$school_year->school_year)->where('section','=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where users.idno = promotions.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and promotions.section = '$section' and promotions.strand= '$strand' order by lastname, firstname, middlename");
                } else {
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first();
                    $schoolyear = $school_year->school_year;
                    //$students =  \App\BedLevel::where('level',$level)->where('school_year',$school_year->school_year)->where('section','=',$section)->get();
                    $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and promotions.section = '$section' order by lastname, firstname, middlename");
                }
            }
            return view('reg_be.ajax.studentlevel', compact('level', 'strand', 'students', 'school_year'));
        }
    }

    function change_section() {
        if (Request::ajax()) {
            $idno = Input::get('idno');
            $level = Input::get('level');
            $section = Input::get('section');
            $type = Input::get('type');
            
            if (Auth::user()->accesslevel == env('REG_BE')) {
                $status = \App\Status::where('idno', $idno)->first();
                $status->section = $section;
                $status->update();
                $bedlevel = \App\BedLevel::where('idno', $idno)->where('level', $level)->where('school_year', $status->school_year)->where('period', $status->period)->first();
                if(count($bedlevel)>0){
                    $bedlevel->section = $section;
                    $bedlevel->update();
                }
                $sections = \App\Promotion::where('idno', $idno)->first();
                $sections->section = $section;
                $sections->update();
            \App\Http\Controllers\Admin\Logs::log("Update bedlevel/ promotions section of $idno to $section");
            } else if (Auth::user()->accesslevel == env('GUIDANCE_BED')) {
                $message = "";
                if($type != "pre_sectioning"){
                    $status = \App\Status::where('idno', $idno)->first();
                    $status->section = $section;
                    $status->update();
                    $bedlevel = \App\BedLevel::where('idno', $idno)->where('level', $level)->where('school_year', $status->school_year)->where('period', $status->period)->first();
                    if(count($bedlevel)>0){
                        $bedlevel->section = $section;
                        $bedlevel->update();
                    }
                    $message = "bedlevel/";
                }
                
                $sections = \App\Promotion::where('idno', $idno)->first();
                $sections->section = $section;
                $sections->save();
            \App\Http\Controllers\Admin\Logs::log("Update $message promotions section of $idno to $section by guidance account.");
            }
        }
    }

    function export_student_list($level, $strand, $section, $schoolyear, $period, $value) {
        if ($level == "Grade 11" || $level == "Grade 12") {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        } else {
            if ($section == "All") {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            } else {

                $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
            }
        }
        ob_end_clean();
        Excel::create('Student List-' . $level . '-' . $section, function($excel) use ($status, $level, $section, $strand, $value, $schoolyear, $period) {
            $excel->setTitle($level . "-" . $section);

            $excel->sheet($level . "-" . $section, function ($sheet) use ($status, $level, $section, $strand, $value, $schoolyear, $period) {
                $sheet->loadView('reg_be.view_list_export', compact('status', 'level', 'section', 'strand', 'value', 'schoolyear', 'period'));
            });
        })->download('xlsx');
    }

//    function print_to_excel() {
//        $row = 10;
//        $ctr = 0;
//        $student_data = \App\User::where('academic_type', "College")->get();
//        $student_array[] = array('ID Number', 'Name');
//        foreach ($student_data as $student) {
//            $student_array[] = array(
//                'ID Number' => $student->idno,
//                'Name' => $student->lastname . ", " . $student->firstname
//            );
//        }
//        Excel::load('public/myFile.csv', function($excel) {
//            $excel->sheet('Sheet1', function ($sheet) use ($excel) {
//                $sheet->appendRow(1,[
//                    'test1',
//                ]);
////             foreach ($student_data as $key => $value){
////                   $sheet->setCellValue('A'.$row, $student_data[$ctr]->idno);
////                   $row++;
////                   $ctr++;
////             }
////           $sheet->fromArray($student_data);
//            });
//        })->download('csv');
//    }
    

    function view_withdrawn() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("EDUTECH") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
                $schoolyear = Input::get('school_year');
                $period = Input::get('period');
                $department = Input::get('department');
                
                if ($department == "Senior High School") {
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and (bed_levels.level = 'Grade 11' or bed_levels.level = 'Grade 12')"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                } else if($department == "All Departments") {
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                }else{
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and (bed_levels.level != 'Grade 11' and bed_levels.level != 'Grade 12')"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                }
                return view("reg_be.ajax.view_withdrawn", compact("status", 'schoolyear', 'period'));
            }
        }
    }
    function print_withdrawn_list($department, $schoolyear, $period) {
            if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("EDUTECH") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
                
                if ($department == "Senior High School") {
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and (bed_levels.level = 'Grade 11' or bed_levels.level = 'Grade 12')"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                } else if($department == "All Departments") {
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                }else{
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and (bed_levels.level != 'Grade 11' and bed_levels.level != 'Grade 12')"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                }
        $pdf = PDF::loadView("reg_be.view_withdrawn", compact("status", 'schoolyear', 'period', 'department'));
        $pdf->setPaper(array(0, 0, 612, 936));
        return $pdf->stream();
            }
    }
    function export_withdrawn_list($department, $schoolyear, $period) {
            if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("EDUTECH") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
                
                if ($department == "Senior High School") {
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and (bed_levels.level = 'Grade 11' or bed_levels.level = 'Grade 12')"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                } else if($department == "All Departments") {
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                }else{
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section, bed_levels.level  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and (bed_levels.level != 'Grade 11' and bed_levels.level != 'Grade 12')"
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = ". env("WITHDRAWN") ." order by users.lastname, users.firstname, users.middlename");
                }
//        $pdf = PDF::loadView("reg_be.view_withdrawn", compact("status", 'schoolyear', 'period', 'department'));
        
        ob_end_clean();
        Excel::create('Withdrawn Student List', function($excel) use ($status, $schoolyear, $period, $department) {
            $excel->setTitle("Withdrawn Student List");

            $excel->sheet("Withdrawn Student List", function ($sheet) use ($status, $schoolyear, $period, $department) {
                $sheet->loadView('reg_be.view_withdrawn_export', compact("status", 'schoolyear', 'period', 'department'));
            });
        })->download('xlsx');
            }
    }

    function getSiblings() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE")) {
                $search = Input::get('search');
                $lists = \App\User::Where("lastname", "like", "%$search%")
                                ->orWhere("firstname", "like", "%$search%")->orWhere("idno", $search)->get();
                return view('reg_be.ajax.getstudentlist_siblings', compact('lists'));
            }
        }
    }

    function getBenefits() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE")) {
                $search = Input::get('search');
                $lists = \App\User::Where("lastname", "like", "%$search%")
                                ->orWhere("firstname", "like", "%$search%")->orWhere("idno", $search)->get();
                return view('reg_be.ajax.getstudentlist_benefits', compact('lists'));
            }
        }
    }

    function report_card_view_list() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env('ADMISSION_BED')) {
                $schoolyear = Input::get('school_year');
                $level = Input::get('level');
                $section = Input::get('section');
                $period = Input::get('period');

                $strand = Input::get("strand");
                if ($level == "Grade 11" || $level == "Grade 12") {
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
			$students = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' order by users.lastname, users.firstname, users.middlename");
                    }
                } else {
                    $period = "";
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
                        $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and promotions.section = '$section' order by lastname, firstname, middlename");
                    }
                }
                return view("reg_be.ajax.report_card_view_list", compact("status", "level", "section", 'strand', 'schoolyear', 'period','students'));
            }
        }
    }
}
