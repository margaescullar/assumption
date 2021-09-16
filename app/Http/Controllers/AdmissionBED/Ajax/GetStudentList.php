<?php

namespace App\Http\Controllers\AdmissionBED\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;

class GetStudentList extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
                $search = Input::get('search');
                $lists = \App\User::where('academic_type', "!=", 'College')
                                ->where(function ($query) use ($search) {
                                    $query->where("lastname", "like", "%$search%")
                                    ->orWhere("firstname", "like", "%$search%")
                                    ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), "like", "%$search%")
                                    ->orWhere("idno", $search);
                                })->get();

//                $lists = \App\User::Where("users.lastname", "like", "%$search%")
//                                ->orWhere("users.firstname", "like", "%$search%")->orWhere("users.idno", $search)->get();
                return view('admission-bed.ajax.getstudentlist', compact('lists'));
            }
        }
    }

    function view_list() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
                $schoolyear = Input::get('school_year');
                $level = Input::get('level');
                $section = Input::get('section');
                $strand = Input::get("strand");
                if ($level == "Grade 11" || $level == "Grade 12") {
                    $status = \App\BedLevel::where('level', $level)->where('section', $section)
                            ->where('strand', $strand)->where('school_year', $schoolyear)
                            ->get();
                } else {
                    $status = \App\BedLevel::where('level', $level)->where('section', $section)
                            ->where('school_year', $schoolyear)
                            ->get();
                }
                return view("reg_be.ajax.view_list", compact("status", "level", "section", 'strand'));
            }
        }
    }

    function updateSched() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
                $idno = Input::get('idno');
                $testing_id = Input::get('testing_id');

                if ($testing_id == "Select Schedule") {
                    $update = \App\TestingStudent::where('idno', $idno)->first();
                    $update->schedule_id = NULL;
                    $update->save();
                } else {
                    $update = \App\TestingStudent::where('idno', $idno)->first();
                    $update->schedule_id = $testing_id;
                    $update->save();
                }
            }
        }
    }

    function updateSchedInterview() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
                $idno = Input::get('idno');
                $interview_id = Input::get('interview_id');

                if ($interview_id == "Select Schedule") {
                    $update = \App\InterviewStudent::where('idno', $idno)->first();
                    $update->schedule_id = NULL;
                    $update->save();
                } else {
                    $update = \App\InterviewStudent::where('idno', $idno)->first();
                    $update->schedule_id = $interview_id;
                    $update->save();
                }
            }
        }
    }

    function updateSchedGroup() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
                $idno = Input::get('idno');
                $interview_id = Input::get('interview_id');

                if ($interview_id == "Select Schedule") {
                    $update = \App\GroupStudent::where('idno', $idno)->first();
                    $update->schedule_id = NULL;
                    $update->save();
                } else {
                    $update = \App\GroupStudent::where('idno', $idno)->first();
                    $update->schedule_id = $interview_id;
                    $update->save();

                    $update = \App\IndividualStudents::where('idno', $idno)->first();
                    $update->schedule_id = NULL;
                    $update->save();
                }
            }
        }
    }

    function updateSchedIndividual() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
                $idno = Input::get('idno');
                $interview_id = Input::get('interview_id');

                if ($interview_id == "Select Schedule") {
                    $update = \App\IndividualStudents::where('idno', $idno)->first();
                    $update->schedule_id = NULL;
                    $update->save();
                } else {
                    $update = \App\IndividualStudents::where('idno', $idno)->first();
                    $update->schedule_id = $interview_id;
                    $update->save();

                    $update = \App\GroupStudent::where('idno', $idno)->first();
                    $update->schedule_id = NULL;
                    $update->save();
                }
            }
        }
    }

    function change_applied_for() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED") || Auth::user()->accesslevel == env("ADMISSION_SHS")) {
                $idno = Input::get('idno');
                $level = Input::get('level');
                $strand = Input::get('strand');
                $type = Input::get('type');

                if ($type == "level") {
                    if ($level == "Grade 11") {
                        $academic_type = "SHS";
                    } else {
                        $academic_type = "BED";
                    }

                    $status = \App\Status::where('idno', $idno)->first();
                    $status->level = $level;
                    $status->academic_type = $academic_type;
                    $status->save();

                    $bedinfo = \App\BedProfile::where('idno', $idno)->first();
                    $bedinfo->applied_for = $level;
                    $bedinfo->save();

                    $promotion = \App\Promotion::where('idno', $idno)->first();
                    $promotion->level = $level;
                    $promotion->save();
                    
                    if ($level == "Grade 11") {
                        $status = \App\Status::where('idno', $idno)->first();
                        $status->strand = $strand;
                        $status->save();

                        $bedinfo = \App\BedProfile::where('idno', $idno)->first();
                        $bedinfo->applied_for_strand = "$strand";
                        $bedinfo->save();

                        $promotion = \App\Promotion::where('idno', $idno)->first();
                        $promotion->strand = $strand;
                        $promotion->save();
                    } else {
                        $status = \App\Status::where('idno', $idno)->first();
                        $status->strand = null;
                        $status->save();

                        $bedinfo = \App\BedProfile::where('idno', $idno)->first();
                        $bedinfo->applied_for_strand = null;
                        $bedinfo->save();

                        $promotion = \App\Promotion::where('idno', $idno)->first();
                        $promotion->strand = null;
                        $promotion->save();
                    }
                } else {
                    if ($level == "Grade 11") {
                        $status = \App\Status::where('idno', $idno)->first();
                        $status->strand = $strand;
                        $status->save();

                        $bedinfo = \App\BedProfile::where('idno', $idno)->first();
                        $bedinfo->applied_for_strand = $strand;
                        $bedinfo->save();

                        $promotion = \App\Promotion::where('idno', $idno)->first();
                        $promotion->strand = $strand;
                        $promotion->save();
                    } else {
                        $status = \App\Status::where('idno', $idno)->first();
                        $status->strand = null;
                        $status->save();

                        $bedinfo = \App\BedProfile::where('idno', $idno)->first();
                        $bedinfo->applied_for_strand = null;
                        $bedinfo->save();

                        $promotion = \App\Promotion::where('idno', $idno)->first();
                        $promotion->strand = null;
                        $promotion->save();
                    }
                }
            }
        }
    }

    function update_admission_sy() {
       
        if (Request::ajax()) {
                $applying_for = Input::get('applying_for');
                $school_year = Input::get('school_year');
                $update = \App\CtrAdmissionSchoolYear::where('applying_for',$applying_for)->first();
                $update->school_year = $school_year;
                $update->save();
        }
    }
    
    
    
    

    function getProvince() {
        if (Request::ajax()) {
            $region = Input::get('region');
            $data = "<option value=''>Please select..</option>";
            $region_detail = \App\CtrRegion::where('region', $region)->first();
            $provinces = \App\CtrProvince::where('region_code', $region_detail->region_code)->get();

            if (!$provinces->isEmpty()) {
                foreach ($provinces as $province) {
                    $data .= "<option value='" . $province->province . "'>" . $province->province . "</option>";
                }
            }

            return $data;
        }
    }

    function getMunicipality() {
        if (Request::ajax()) {
            $region = Input::get('region');
            $province = Input::get('province');
            $region_detail = \App\CtrRegion::where('region', $region)->first();
            $data = "<option value=''>Please select..</option>";

            if ($province == "") {
                $municipalities = \App\CtrMunicipality::where('region_code', $region_detail->region_code)->get();
            } else {
                $province_detail = \App\CtrProvince::where('province', $province)->first();
                $municipalities = \App\CtrMunicipality::where('province_code', $province_detail->province_code)->where('region_code', $region_detail->region_code)->get();
            }

            if (!$municipalities->isEmpty()) {
                foreach ($municipalities as $city) {
                    $data .= "<option value='" . $city->municipality . "'>" . $city->municipality . "</option>";
                }
            }

            return $data;
        }
    }

    function getBarangay() {
        if (Request::ajax()) {
            $municipality = Input::get('municipality');
            $data = "<option value=''>Please select..</option>";
            $municipality_detail = \App\CtrMunicipality::where('municipality', $municipality)->first();
            $brgys = \App\CtrBrgy::where('municipality_code', $municipality_detail->municipality_code)->get();

            if (!$brgys->isEmpty()) {
                foreach ($brgys as $brgy) {
                    $data .= "<option value='" . $brgy->brgy . "'>" . $brgy->brgy . "</option>";
                }
            }

            return $data;
        }
    }

}
