<?php

namespace App\Http\Controllers\BedRegistrar\Records;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class indexController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            return view('reg_be.records.index', compact('idno'));
        }
    }

    function add($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $type = "Add Record";
            $id = null;
            $record = null;
            return view('reg_be.records.add_record', compact('idno', 'type', 'id', 'record'));
        }
    }

    function update($idno, $id) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $record = \App\TranscriptOfRecord::find($id);
            $type = "Update Record";
            return view('reg_be.records.add_record', compact('idno', 'id', 'record', 'type'));
        }
    }

    function post(Request $request, $idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            if ($request->button == "Add Record") {
                $record = new \App\TranscriptOfRecord();
                $record->idno = $request->idno;
                $record->school_name = $request->school_name;
                $record->school_year = $request->school_year;
                $record->period = $request->period;
                $record->subject_code = $request->subject_code;
                $record->subject_name = $request->subject_name;
                $record->card_name = $request->card_name;
                $record->level = $request->level;
                $record->units = $request->units;
                $record->first_grading = $request->first_grading;
                $record->second_grading = $request->second_grading;
                $record->third_grading = $request->third_grading;
                $record->fourth_grading = $request->fourth_grading;
                $record->final_grade = $request->final_grade;
                $record->first_grading_letter = $request->first_grading_letter;
                $record->second_grading_letter = $request->second_grading_letter;
                $record->third_grading_letter = $request->third_grading_letter;
                $record->fourth_grading_letter = $request->fourth_grading_letter;
                $record->final_grade_letter = $request->final_grade_letter;
                $record->save();

                \App\Http\Controllers\Admin\Logs::log("Add TOR Record for $request->idno. Record ID: $record->id");
            } else if ($request->button == "Update Record") {
                $record = \App\TranscriptOfRecord::find($request->id);
                $record->school_name = $request->school_name;
                $record->school_year = $request->school_year;
                $record->period = $request->period;
                $record->subject_code = $request->subject_code;
                $record->subject_name = $request->subject_name;
                $record->card_name = $request->card_name;
                $record->level = $request->level;
                $record->units = $request->units;
                $record->first_grading = $request->first_grading;
                $record->second_grading = $request->second_grading;
                $record->third_grading = $request->third_grading;
                $record->fourth_grading = $request->fourth_grading;
                $record->final_grade = $request->final_grade;
                
                $record->first_remarks = $request->first_remarks;
                $record->second_remarks = $request->second_remarks;
                $record->third_remarks = $request->third_remarks;
                $record->fourth_remarks = $request->fourth_remarks;
                $record->final_remarks = $request->final_remarks;
                
                $record->first_grading_letter = $request->first_grading_letter;
                $record->second_grading_letter = $request->second_grading_letter;
                $record->third_grading_letter = $request->third_grading_letter;
                $record->fourth_grading_letter = $request->fourth_grading_letter;
                $record->final_grade_letter = $request->final_grade_letter;
                $record->save();

                \App\Http\Controllers\Admin\Logs::log("Update TOR Record for $request->idno. Record ID: $record->id");
            }
            return redirect(url("bedregistrar/records/$idno"));
        }
    }

    function update_gwa($idno, $id) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $record = \App\TorGwa::find($id);
            return view('reg_be.records.update_gwa', compact('idno', 'id', 'record'));
        }
    }

    function post_gwa(Request $request, $idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $record = \App\TorGwa::find($request->id);
            $record->school_year = $request->school_year;
            $record->period = $request->period;
            $record->level = $request->level;
            $record->strand = $request->strand;
            $record->gwa_letter = $request->gwa_letter;
            $record->gwa = $request->gwa;
            $record->days_of_school = $request->days_of_school;
            $record->days_present = $request->days_present;
            $record->save();

            \App\Http\Controllers\Admin\Logs::log("Upated GWA Record for $request->idno. Record ID: $record->id");
            return redirect(url("bedregistrar/records/$idno"));
        }
    }

    public static function fetch_grades($idno,$grade) {
        if (Auth::user()->accesslevel == env("REG_BE")) {

                //checking of records in TOR
                if ($grade->level == "Grade 11" or $grade->level == "Grade 12") {
                    $checkRecords = self::checkRecords($grade,$idno,'shs');
                } else {
                    $checkRecords = self::checkRecords($grade,$idno);
                }

                //Saving of Records to TOR
                self::saveRecords($checkRecords,$idno,$grade);
        }
    }

    static function checkRecords($grade,$idno,$type=null) {
        if($type != null){
        $check_records = \App\TranscriptOfRecord::where('subject_code', $grade->subject_code)
                ->where('level', $grade->level)
                ->where('school_year', $grade->school_year)
                ->where('idno', $idno)
                ->first();
        }else{
        $check_records = \App\TranscriptOfRecord::where('subject_code', $grade->subject_code)
                ->where('level', $grade->level)
                ->where('school_year', $grade->school_year)
                ->where('period', $grade->period)
                ->where('idno', $idno)
                ->first();
        }

        return $check_records;
    }

    static function saveRecords($checkRecords,$idno,$grade) {
        if (count($checkRecords) == 0) {
            $saveRecord = new \App\TranscriptOfRecord();
        }else{
            $saveRecord = $checkRecords;
        }
        
            $saveRecord->idno = $idno;
            $saveRecord->school_name = "Assumption College";
            $saveRecord->subject_code = $grade->subject_code;
            $saveRecord->subject_name = $grade->subject_name;
            $saveRecord->card_name = $grade->display_subject_code;
            $saveRecord->group = $grade->report_card_grouping;
            $saveRecord->level = $grade->level;
            $saveRecord->units = $grade->units;
            $saveRecord->school_year = $grade->school_year;
            $saveRecord->period = $grade->period;
            $saveRecord->first_grading = $grade->first_grading;
            $saveRecord->second_grading = $grade->second_grading;
            $saveRecord->third_grading = $grade->third_grading;
            $saveRecord->fourth_grading = $grade->fourth_grading;
            $saveRecord->final_grade = $grade->final_grade;
            
            $saveRecord->first_remarks = $grade->first_remarks;
            $saveRecord->second_remarks = $grade->second_remarks;
            $saveRecord->third_remarks = $grade->third_remarks;
            $saveRecord->fourth_remarks = $grade->fourth_remarks;
            $saveRecord->final_remarks = $grade->final_remarks;
            
            $saveRecord->first_grading_letter = $grade->first_grading_letter;
            $saveRecord->second_grading_letter = $grade->second_grading_letter;
            $saveRecord->third_grading_letter = $grade->third_grading_letter;
            $saveRecord->fourth_grading_letter = $grade->fourth_grading_letter;
            $saveRecord->final_grade_letter = $grade->final_remarks;
            
            $saveRecord->save();
    }
    
    

    public static function fetch_gwa($idno,$gwa) {
        if (Auth::user()->accesslevel == env("REG_BE")) {

                //checking of records in TOR
                if ($gwa->level == "Grade 11" or $gwa->level == "Grade 12") {
                    $checkRecords = self::checkGwa($gwa,$idno);
                } else {
                    $checkRecords = self::checkGwa($gwa,$idno);
                }

                //Saving of Records to TOR
                self::saveGwa($checkRecords,$idno,$gwa);
        }
    }

    static function checkGwa($gwa,$idno) {
        $check_records = \App\TorGwa::where('level', $gwa->level)
                ->where('school_year', $gwa->school_year)
                ->where('period', $gwa->period)
                ->where('idno', $idno)
                ->first();

        return $check_records;
    }

    static function saveGwa($checkRecords,$idno,$gwa) {
        if (count($checkRecords) == 0) {
            $saveRecord = new \App\TorGwa();
        }else{
            $saveRecord = $checkRecords;
        }
        
            $saveRecord->idno = $idno;
            $saveRecord->level = $gwa->level;
            $saveRecord->strand = $gwa->strand;
            $saveRecord->school_year = $gwa->school_year;
            $saveRecord->period = $gwa->period;
            $saveRecord->gwa_letter = $gwa->gwa_letter;
            $saveRecord->gwa = $gwa->gwa;
            $saveRecord->final_gwa_letter = $gwa->final_gwa_letter;
            $saveRecord->final_gwa = $gwa->final_gwa;
            $saveRecord->days_of_school = $gwa->days_of_school;
            $saveRecord->days_present = $gwa->days_present;
            $saveRecord->save();
    }
    
    function update_tor_details(Request $request){
        $tor_details_hs = \App\TorDetail::where('idno',$request->idno)->first();
        $tor_details_hs->elementary_course_completed_at = $request->elementary_course_completed_at; 
        $tor_details_hs->elementary_year = $request->elementary_year;
        $tor_details_hs->elem_gwa = $request->elem_gwa ;
        $tor_details_hs->jhs_course_completed_at = $request->jhs_course_completed_at; 
        $tor_details_hs->jhs_year = $request->jhs_year;
        $tor_details_hs->jhs_gwa = $request->jhs_gwa;
        $tor_details_hs->save();
        return redirect("/view_secondary_record/$request->idno");
    }
}
    