<?php

use App\Http\Controllers\BedRegistrar\Ajax\AjaxBatchRanking;
?>
@extends("reg_be.records.index")
@section('displaygrades')
<div class="col-md-12"> 
    <div class="box">
        <div class="box-header">
            <div class="box-title">GRADES SEVEN TO ELEVEN
            </div>
            <div class="pull-right"><a target="_blank" href="{{url('/print_secondary_record',$idno)}}"><button class="btn btn-success">Print TOR</button></a></div>
        </div>
        <div class="box-body">
            <form method="post" action="{{url('update_tor_details_hs')}}">
                {{csrf_field()}}
                <input type="hidden" value="{{$idno}}" name="idno">
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <label>Elementary Course Completed at:</label>
                        <input type="text" name="elementary_course_completed_at" value="{{$tor_details_hs->elementary_course_completed_at}}" class="form form-control">
                    </div>
                    <div class="col-sm-2">
                        <label>Year:</label>
                        <input type="text" name="elementary_year" value="{{$tor_details_hs->elementary_year}}" class="form form-control">
                    </div>
                    <div class="form form-group col-sm-2">
                        <label>GWA:</label>
                        <input type="text"  name="elem_gwa" value="{{$tor_details_hs->elem_gwa}}" class="form form-control">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form form-group col-sm-4">
                        <label>Junior High School Completed at:</label>
                        <input type="text" name="jhs_course_completed_at" value="{{$tor_details_hs->jhs_course_completed_at}}" class="form form-control">
                    </div>
                    <div class="form form-group col-sm-2">
                        <label>Year:</label>
                        <input type="text" name="jhs_year" value="{{$tor_details_hs->jhs_year}}" class="form form-control">
                    </div>
                    <div class="form form-group col-sm-2">
                        <label>GWA:</label>
                        <input type="text"  name="jhs_gwa" value="{{$tor_details_hs->jhs_gwa}}" class="form form-control">
                    </div>
                </div>
                <div class="col-sm-12">
                <input type="submit" value="Update Details" class="btn btn-success">
                </div>
            </form>
        </div>
        <div class="box-body">

            <!--HS-->
            @for($x=7; $x<=10; $x++)
            <?php
            $records = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->whereRaw("(units = 0 or units >= 1)")->get();
            ?>
            @if(count($records)>0)
            <table class='table table-bordered'>
                <?php $gwa = \App\TorGwa::firstOrCreate(['idno' => "$idno", 'level' => "Grade $x", 'school_year' => $records->unique('school_year')->first()->school_year]); ?>
                <?php $school_attended = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->first(); ?>
                <tr>
                    <td align="center">Level <p class="title-underline">{{"Grade ". $x}}</p></td>
                    <td>School: <p class="title-underline">{{$school_attended->school_name}}</p></td>
                    <td>School Year: <p class="title-underline">{{$records->unique('school_year')->first() == null ? "" : $records->unique('school_year')->first()->school_year}}</p></td>
                </tr>
                <tr>
                    <td class='table-title'>Subjects</td>
                    <td class='table-title'>Final Ratings</td>
                    <td class='table-title'>Action Taken</td>
                    <td class='table-title'>Edit</td>
                </tr>
                @foreach($records as $record)
                <tr>
                    <td>{{$record->card_name}}</td>
                    <td align="center">{{$record->final_grade_letter}} {{($record->final_grade=="") ? "" : "(".$record->final_grade.")" }}</td>
                    <td align="center">P</td>
                    <td align="center"><a href="{{url('/transcript_of_records',array($idno,$record->id,'update'))}}">Edit</a></td>
                </tr>
                @endforeach
                <tr>
                    <td>
                        Days of School: {{($gwa->days_of_school) ? $gwa->days_of_school:"N/A" }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Days Present: {{($gwa->days_present) ? $gwa->days_present:"N/A"}}
                    </td>
                    <td>General Average: <strong>{{$gwa->gwa_letter}}({{$gwa->gwa}})</strong></td>
                    <td></td>
                    <td align="center"><a href="{{url('/transcript_of_records',array($idno,$gwa->id,'update_gwa'))}}">Edit</a></td>
                </tr>
            </table>
            <hr>
            @endif
            @endfor

            @for($x=11; $x<=11; $x++)
            <?php
            $first_letter = "";
            $first_gwa = 0;
            $period = ['1st Semester', '2nd Semester'];
            $genave = [];
            $sy = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->whereRaw("(units = 0 or units >= 1)")->get();
            ?>
            @if(count($sy)>0)
            @for($sem = 0; $sem<=1; $sem++)
            <?php
            $school_year = $sy->unique('school_year')->first->school_year;
            $get_subjects_heads = \App\GradeBasicEd::distinct()->where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $period[$sem])->orderBy('report_card_grouping', 'desc')->get(['report_card_grouping']);
            ?>
            <?php
            $gwa = \App\TorGwa::firstOrCreate(['idno' => "$idno", 'level' => "Grade $x" ,'period' => "$period[$sem]"]);
            ?>
            <table class='table table-bordered'>
                <?php $school_attended = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->first(); ?>
                <tr>
                    <td align="center">Level <p class="title-underline">{{"Grade ". $x}} - {{$gwa->strand}}</p></td>
                    <td>School: <p class="title-underline">{{$school_attended->school_name}}</p></td>
                    <td>School Year: <p class="title-underline">{{$school_year->school_year}} - {{$school_year->school_year+1}}, {{$period[$sem]}}</p></td>
                </tr>
                <tr>
                    <td class='table-title'>Subjects</td>
                    <td class='table-title'>Final Ratings</td>
                    <td class='table-title'>Action Taken</td>
                    <td class='table-title'>Edit</td>
                </tr>
                @if(count($get_subjects_heads)>0)
                @foreach($get_subjects_heads as $subject_heads)
                <?php $records = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->where('period', $period[$sem])->whereRaw("(units = 0 or units >= 1)")->where('group', $subject_heads->report_card_grouping)->get(); ?>
                <tr>
                    <td colspan="4"><strong>{{$subject_heads->report_card_grouping}}</strong></td></tr>                
                @foreach($records as $record)
                <tr>
                    <td>{{$record->card_name}}</td>
                    <td align="center">
                        {{$record->first_grading_letter}} {{$record->second_grading_letter}} {{$record->third_grading_letter}} {{$record->fourth_grading_letter}}
                        {{$record->first_remarks}} {{$record->second_remarks}} {{$record->third_remarks}} {{$record->fourth_remarks}}
                    </td>
                    <td align="center">P</td>
                    <td align="center"><a href="{{url('/transcript_of_records',array($idno,$record->id,'update'))}}">Edit</a></td>
                </tr>
                @endforeach
                @endforeach
                @endif
                <tr>
                    <td>
                        Days of School: {{($gwa->days_of_school) ? $gwa->days_of_school:"N/A" }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Days Present: {{($gwa->days_present) ? $gwa->days_present:"N/A"}}
                    </td>
                    <td colspan='2'>
                        @if($period[$sem] == "1st Semester")
                        <?php
                        $first_letter = $gwa->gwa_letter;
                        $first_gwa = $gwa->gwa;
                        ?>
                        General Average for the {{$period[$sem]}}: <strong>{{$gwa->gwa_letter}}({{number_format($gwa->gwa,2)}})</strong>
                        @else
                        General Average for the {{$period[0]}}: <strong class="pull-right">{{$first_letter}}({{$first_gwa}})</strong><br>
                        General Average for the {{$period[1]}}: <strong class="pull-right">{{$gwa->gwa_letter}}({{$gwa->gwa}})</strong><br>
                        <strong>General Average for the Whole School Year:</strong> <strong class="pull-right">{{$gwa->final_gwa_letter}}({{$gwa->final_gwa}})</strong>
                        @endif
                    </td>
                    <td align="center"><a href="{{url('/transcript_of_records',array($idno,$gwa->id,'update_gwa'))}}">Edit</a></td>
                </tr>
            </table>
            <hr>
            @endfor
            @endif
            @endfor

        </div>    
    </div> 
</div>

@endsection

<style>
    .table-title{
        font-weight: bold;
        text-align: center;
    }
    .title-underline{
        font-weight: bold;
        text-decoration: underline;
    }
</style>
