<?php

namespace App\Http\Controllers\BedRegistrar\Records;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class elemController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            
            return view('reg_be.records.elem_tor.view',compact('idno','grades'));
        }
    }
    
    function sample(){
        
        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
//        $bed_levels = \App\BedLevel::where('idno',$idno)->get();
        if ($status->department != 'Senior High School') {
            $bed_levels = \App\GradeElem::where('idno', $idno)->get()->unique('school_year')->sortBy('school_year');
        } else {
            $bed_levels = collect(DB::Select('SELECT idno, level, period, school_year FROM grade_elems '
                            . "WHERE idno = '$idno' "
                            . 'GROUP BY level, period'));
        }
//dd($bed_levels);
        $school_year = \App\CtrAcademicSchoolYear::first()->school_year;
        $tor_fields = \App\TorFields::where('idno', $idno)->first();
        $info = \App\StudentInfo::where('idno', $idno)->first();
        $parent_relations = \App\StudentParentRelation::where('idno', $idno)->where('parent_id', '!=', null)->get();

        foreach ($parent_relations as $pr) {
            $parent_info = \App\StudentInfoParent::where('id', $pr->parent_id)->first();
            if ($parent_info) {
                $pr->parentname = $parent_info->name;
            }
        }

        $this->get_final_ave($idno, $bed_levels);

        if ($status->department == 'Elementary') {
            if ($type == 1) {
                return view('bedregistrar.transcript.index_elementary', compact('parent_relations', 'info', 'tor_fields', 'status', 'idno', 'bed_levels', 'school_year', 'user', 'type'));
            } else {
                $pdf = PDF::loadView('bedregistrar.print.print_transcript_elementary', compact('parent_relations', 'info', 'tor_fields', 'status', 'idno', 'bed_levels', 'school_year', 'user', 'type'));
                $pdf->setPaper('letter', 'portrait');
                return $pdf->stream("tor-$idno.pdf");
            }
        } elseif ($status->department == 'Junior High School') {
            if ($type == 1) {
                return view('bedregistrar.transcript.index_jhs', compact('parent_relations', 'info', 'tor_fields', 'status', 'idno', 'bed_levels', 'school_year', 'user', 'type'));
            } else {
                $pdf = PDF::loadView('bedregistrar.print.print_transcript_jhs', compact('parent_relations', 'info', 'tor_fields', 'status', 'idno', 'bed_levels', 'school_year', 'user', 'type'));
                $pdf->setPaper('letter', 'portrait');
                return $pdf->stream("tor-$idno.pdf");
            }
        } elseif ($status->department == 'Senior High School') {
            if ($type == 1) {

                return view('bedregistrar.transcript.index_shs', compact('parent_relations', 'info', 'tor_fields', 'status', 'idno', 'bed_levels', 'school_year', 'user', 'type'));
            } else {
                $pdf = PDF::loadView('bedregistrar.print.print_transcript_shs_new', compact('parent_relations', 'info', 'tor_fields', 'status', 'idno', 'bed_levels', 'school_year', 'user', 'type'));
                $pdf->setPaper('letter', 'portrait');
                return $pdf->stream("tor-$idno.pdf");
            }
        }
    }
}
