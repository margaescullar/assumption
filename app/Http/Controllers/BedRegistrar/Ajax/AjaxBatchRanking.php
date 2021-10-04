<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use PDF;
use Excel;

class AjaxBatchRanking extends Controller {

    //
    function get_students() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $level = Input::get("level");
            $strand = Input::get("strand");
            $selectedPeriod = Input::get("period");

            $lists3 = $this->getStudentCompute($school_year, $level, $strand, $selectedPeriod);

            return view('reg_be.ajax.get_batch_students', compact('lists3', 'school_year'));
        }
    }

    function get_students_excel($level, $strand, $school_year,$selectedPeriod) {

        $lists3 = $this->getStudentCompute($school_year, $level, $strand,$selectedPeriod);
        
        if($level == "Grade 11" || $level == "Grade 12"){
            $desc = $school_year . '-' . $selectedPeriod;
        }else{
            $desc = $school_year;
        }

        ob_end_clean();
        Excel::create('batch_ranking-' . $level . '-SY: ' . $desc, function($excel) use ($lists3, $level, $strand, $school_year,$desc) {
            $excel->setTitle($level . "-SY: " . $desc);

            $excel->sheet($level . "-" . $desc, function ($sheet) use ($lists3, $level, $strand, $school_year) {
                $sheet->loadView('reg_be.ajax.get_batch_students_export', compact('lists3', 'level', 'strand', 'school_year'));
            });
        })->download('xlsx');
    }

    function getStudentCompute($school_year, $level, $strand, $selectedPeriod) {
        if ($level == "Grade 11" or $level == "Grade 12") {
            if ($selectedPeriod == "1st Semester") {
                $period = "1st Semester";
            }else{
                $period = "2nd Semester";
            }
            
            if ($strand == "All") {
                $list = \App\BedLevel::where('level', $level)->where('school_year', $school_year)->where('status', env('ENROLLED'))->where('period', $period)->get();
            } else {
                $list = \App\BedLevel::where('strand', $strand)->where('level', $level)->where('school_year', $school_year)->where('status', env('ENROLLED'))->where('period', $period)->get();
            }
            
        } else {
            $period = "";
            $list = \App\BedLevel::where('level', $level)->where('school_year', $school_year)->where('status', env('ENROLLED'))->get();
        }
        $lists = collect();
        foreach ($list as $lists2) {
            $lists->push($this->getLists($lists2->idno, $school_year, $period, $level, $lists2,$selectedPeriod));
        }
        return $lists3 = $lists->sortByDesc('gpa');
    }

    function getLists($idno, $school_year, $period, $level, $lists2,$selectedPeriod) {
        $user = \App\User::where('idno', $idno)->first();
        $array2 = array();
        $array2['idno'] = $idno;
        $array2['lastname'] = $user->lastname;
        $array2['firstname'] = $user->firstname;
        $array2['middlename'] = $user->middlename;
        $array2['section'] = $lists2->section;
        $array2['strand'] = $lists2->strand;
        if ($level == "Grade 11" or $level == "Grade 12") {
            $array2['gpa'] = $this->get_gpa_shs($idno, $school_year, $period,$selectedPeriod);
        } else {
            $array2['gpa'] = $this->get_gpa_bed($idno, $school_year, $period);
        }

        return $array2;
    }

    function get_gpa_shs($idno, $school_year, $period, $selectedPeriod) {
        if ($school_year == "2019") {
            $get_first_sem_final_ave = \App\ShsOldAveGrade::where('idno', $idno)->first();
            $get_second_sem_final_ave = \App\Http\Controllers\BedRegistrar\ReportCardController::getSHS2ndAve($idno, $school_year);
        } else {
            $get_first_sem_final_ave = \App\Http\Controllers\BedRegistrar\ReportCardController::getSHS1stAve($idno, $school_year);
            $get_second_sem_final_ave = \App\Http\Controllers\BedRegistrar\ReportCardController::getSHS2ndAve($idno, $school_year);
        }
        if($selectedPeriod == "Whole Year"){
            return round(($get_first_sem_final_ave->final_grade + $get_second_sem_final_ave->final_grade) / 2, 3);
        }elseif($selectedPeriod == "1st Semester"){
            return round($get_first_sem_final_ave->final_grade, 3);
        }elseif($selectedPeriod == "2nd Semester"){
            return round($get_second_sem_final_ave->final_grade, 3);
        }
    }

    public static function get_gpa_bed($idno, $school_year, $period) {

        $get_regular_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->where('is_alpha', 0)->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $get_regular_alpha_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 0)->whereRaw('is_alpha between 1 and 2 ')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $get_group_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 1)->orderBy('sort_to', 'asc')->where('is_display_card', 1)->get();
        $get_split_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'not like', "SA%")->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();
        $group_split_subjects = \App\GradeBasicEd::distinct()->where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'not like', "SA%")->where('is_display_card', 1)->get(['group_code']);
        $get_sa_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', 2)->where('subject_code', 'like', "SA%")->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get();

        $get_grouping_subjects = \App\GradeBasicEd::SelectRaw('letter_grade_type,report_card_grouping as subject_name')->where('is_display_card', 1)->where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', "!=", "")->groupBy('report_card_grouping', 'letter_grade_type')->orderBy('subject_name', 'DESC')->get();

        if ($school_year == "2019") {
            $get_gpa = \App\Http\Controllers\BedRegistrar\ReportCardController::getBedAve($idno, $school_year, $get_grouping_subjects, $get_sa_subjects, $group_split_subjects, $get_split_subjects, $get_group_subjects, $get_regular_subjects, $get_regular_alpha_subjects);
        } else {
            $get_gpa = \App\Http\Controllers\BedRegistrar\ReportCardController::getBedAve($idno, $school_year, $get_grouping_subjects, $get_sa_subjects, $group_split_subjects, $get_split_subjects, $get_group_subjects, $get_regular_subjects, $get_regular_alpha_subjects);
        }

        return round($get_gpa->final_ave, 3);
    }

    function getGrades($subject, $idno, $school_year, $period) {
        $getsubjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', $subject->subject_name)->get();
        $final_grade = 0;
        foreach ($getsubjects as $get) {
            switch ($period) {
                case "1":
                    if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8") {
                        $final_grade += $get->first_grading * ($get->units);
                        if ($get->group_code == "EPP5" || $get->group_code == "EPP4" || $get->group_code == "EPP6" || $get->group_code == "TLE7" || $get->group_code == "TLE8" || $get->group_code == "TLE9" || $get->group_code == "TLE10") {
                            $final_grade = $final_grade + ($get->first_grading * (1 - $get->units));
                            return $final_grade;
                        }
                    } else {
                        $final_grade += 100 * ($get->units);
                    }
                    break;
                case "2":
                    if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8") {
                        $final_grade += $get->second_grading * ($get->units);
                        if ($get->group_code == "EPP5" || $get->group_code == "EPP4" || $get->group_code == "EPP6" || $get->group_code == "TLE7" || $get->group_code == "TLE8" || $get->group_code == "TLE9" || $get->group_code == "TLE10") {
                            $final_grade = $final_grade + ($get->second_grading * (1 - $get->units));
                            return $final_grade;
                        }
                    } else {
                        $final_grade += 100 * ($get->units);
                    }
                    break;
                case "3":
                    $final_grade += $get->third_grading * ($get->units);
                    break;
                case "4":
                    $final_grade += $get->fourth_grading * ($get->units);
                    break;
            }
        }
        return $final_grade;
    }

    function getUnits($subject, $idno, $school_year) {
        $getsubjects = \App\GradeBasicEd::selectRaw('sum(units) as units')->where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', $subject->subject_name)->first();

        return $getsubjects->units;
    }

//get final rating for grouping
    function getFinalRating($grade, $letter_grade_type) {
        $round = round($grade);
        $round2 = round($grade, 2);
        $final_letter_grade = \App\CtrTransmuLetter::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->first();
        $letter = $final_letter_grade['letter_grade'];
        return "$letter($round2)";
    }

//get letter grade transmutation
    function getLetterGrade($grade, $letter_grade_type) {
        $round = round($grade);
        $final_letter_grade = \App\CtrTransmuLetter::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->first();
        $letter = $final_letter_grade['letter_grade'];
        return "$letter";
    }

}
