<?php

use App\Http\Controllers\BedRegistrar\Ajax\AjaxBatchRanking;
?>
@extends("reg_be.records.index")
@section('displaygrades')
<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <div class="box-title">PRE-SCHOOL AND GRADES ONE TO THREE</div>
        </div>
        <div class="box-body">
            <!--Pre-School-->
            <table class='table table-bordered'>
                <tr>
                    <td colspan='6' class='table-title'>PRE-SCHOOL AND GRADES ONE TO THREE</td>
                </tr>
                <tr>
                    <td class='table-title'>School Year</td>
                    <td class='table-title'>School Attended</td>
                    <td class='table-title'>Grade Level</td>
                    <td class='table-title'>Days Present</td>
                    <td class='table-title'>Final Rating</td>
                    <td class='table-title'>Promoted or Retained</td>
                    <td class='table-title'>Edit</td>
                </tr>
                @for($x=0; $x<=3; $x++)
                <?php
                $total_absent = "0";

                if ($x == 0) {
                    $level = \App\BedLevel::where('level', "Kinder")->where('idno', $idno)->first();
                } else {
                    $level = \App\BedLevel::where('level', "Grade $x")->where('idno', $idno)->first();
                }
                ?>
                @if($level)
                <?php
                $school_days = \App\CtrSchoolDay::where('academic_type', 'BED')->where('school_year', $level->school_year)->value('school_days');
                $total_absent = \App\Absent::SelectRaw('sum(is_absent)as total')->where('school_year', $level->school_year)->where('idno', $idno)->first()->total;

                $school_year = $level->school_year;
                $school_attended = "Assumption College";
                $grade_level = $level->level;
                $days_present = $school_days - $total_absent;
                $final_rating = AjaxBatchRanking::get_gpa_bed($idno, $school_year, "");
                $status = "Promoted";
                ?>
                <tr>
                    <td align='center'>{{$school_year}}-{{$school_year+1}}</td>
                    <td align='center'>{{$school_attended}}</td>
                    <td align='center'>{{$grade_level}}</td>
                    <td align='center'>{{$days_present > 0 ? $days_present : "-"}}</td>
                    <td align='center'>{{$final_rating > 0 ? $final_rating : "-"}}</td>
                    <td align='center'>{{$status}}</td>
                    <td align='center'>Edit</td>
                </tr>
                @endif
                @endfor
            </table>
        </div>    
    </div> 
    <div class="box">
        <div class="box-header">
            <div class="box-title">GRADES FOUR TO SIX</div>
        </div>
        <div class="box-body">

            <!--Elementary-->
            @for($x=1; $x<=6; $x++)
            <?php
            $records = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->whereRaw("(units = 0 or units >= 1)")->get();
            ?>
                @if(count($records)>0)
            <table class='table table-bordered'>
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
                    <td align="center">{{$record->final_grade_letter}}({{$record->final_grade}})</td>
                    <td align="center">P</td>
                    <td align="center"><a href="{{url('/transcript_of_records',array($idno,$record->id,'update'))}}">Edit</a></td>
                </tr>
                @endforeach
            </table>
            <hr>
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
