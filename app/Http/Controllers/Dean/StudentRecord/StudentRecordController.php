<?php

namespace App\Http\Controllers\Dean\StudentRecord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class StudentRecordController extends Controller
{
    //
    function view_record ($idno){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('AA')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('dean.view_record.view', compact('idno', 'user', 'info', 'status'));
        }
    }
    function view_transcript($idno){
        if (Auth::user()->accesslevel == env('DEAN') || Auth::user()->accesslevel == env('SCHOLARSHIP_HED') || Auth::user()->accesslevel == env('AA')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('dean.view_record.transcript', compact('idno', 'user', 'info', 'status'));
        }
    }
    function finalize_transcript($idno){
        if (Auth::user()->accesslevel == env('DEAN')) {
            $user = \App\User::where('idno', $idno)->first();
            $level = \App\CollegeLevel::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('dean.view_record.finalize_transcript', compact('idno', 'user', 'level' , 'info', 'status'));
        }
    }    
    
    function print_transcript(Request $request){
        if (Auth::user()->accesslevel == env('DEAN')){
            $idno = $request->idno;
            $user = \App\User::where('idno', $idno)->first();       
            $level = \App\CollegeLevel::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $info->date_of_admission = $request->date_of_admission;
            $info->award = $request->award;
            $info->date_of_grad = $request->date_of_grad;
            $info->remarks = $request->remarks;
            $info->save();
            
            return redirect(url('/college/view_transcript/print_transcript/'.$request->idno));
        }
    }
    function print_now($idno){
        
            $user = \App\User::where('idno', $idno)->first();     
            $level = \App\CollegeLevel::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            
            \App\Http\Controllers\Admin\Logs::log("Print transcript of student: $idno");
            
            $pdf = PDF::loadView('dean.view_record.print_transcript', compact('idno','user','info','level'));
            $pdf->setPaper(array(0,0,612,936));
//            return $request;
            return $pdf->stream("transcript_".$idno.".pdf");
    }
    
    function true_copy_of_grades ($idno){
        if (Auth::user()->accesslevel == env('DEAN')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            
            \App\Http\Controllers\Admin\Logs::log("Print True copy of grades for student: $idno");
            
            $pdf = PDF::loadView('dean.view_record.print_true_copy_of_grades', compact('idno','user','info','level'));
            $pdf->setPaper(array(0,0,612,792));
//            return $request;
            return $pdf->stream("true_copy_of_grades".$idno.".pdf");            
        }
    }    
    
    function print_curriculum_record($idno){
        
            $user = \App\User::where('idno', $idno)->first();     
            $status = \App\Status::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            
            \App\Http\Controllers\Admin\Logs::log("Print curriculum record of student: $idno");
            
            $pdf = PDF::loadView('dean.view_record.print_curriculum_record', compact('idno','user','info','status'));
            $pdf->setPaper(array(0,0,612,936));
//            return $request;
            return $pdf->stream("curriculum_record_".$idno.".pdf");
    }
}
