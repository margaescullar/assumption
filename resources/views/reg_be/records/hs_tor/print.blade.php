<?php

use App\Http\Controllers\BedRegistrar\Ajax\AjaxBatchRanking;
?>
<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-size: 12pt; 
        font-weight: bolder;
    }
    .center {
        margin-left: auto;
        margin-right: auto;
    }
    .strong{
        font-weight: bold;
    }
    body{
        font-size: 9pt;
        font-family: Arial, Helvetica, sans-serif;
    }
    header{
        position: fixed; 
        top: -1cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 
        margin: 0cm 1cm cm 1cm;
    }

</style>
<body>
<div>    
    <div style='float: left; margin-left: 130px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px; font-size: 12px' align='center'><span id="schoolname">ASSUMPTION COLLEGE</span> 
        <br><small> Basic Education Division</small>
        <br><small> San Lorenzo Village, Makati City</small>
        <br><small> PHILIPPINES</small><br><br><br>
        <strong>SECONDARY STUDENT'S PERMANENT RECORD</strong>
    </div>
</div><br><br><br><br><br><br><br><br><br>

<div>
    <table width="100%" border="0">
        <tr>
            <td width="11%">Name :</td>
            <td width="89%" style="border-bottom: 1px solid black" colspan="6"><strong style="font-size: 15pt;">{{strtoupper($user->lastname)}}, {{$user->firstname}} {{$user->middlename}}</strong></td>
        </tr>
        <tr>
            <td>Parents :</td>
            <td colspan="2" width="50%" style="border-bottom: 1px solid black">{{$parents->father}}, {{$parents->mother}}</td>
            <td colspan="2" align="right">Place and Date of Birth :</td>
            <td colspan="2" style="border-bottom: 1px solid black">{{date('F j, Y',strtotime($profile->date_of_birth))}}-{{$profile->place_of_birth}}</td>
        </tr>
        <tr>
            <td>Address :</td>
            <td style="border-bottom: 1px solid black" colspan="6">{{$profile->street}} {{$profile->barangay}} {{$profile->municipality}} {{$profile->province}} {{$profile->zip}}</td>
        </tr>
        <tr>
            <td colspan="2">Elementary Course Completed at :</td>
            <td align="center" style="border-bottom: 1px solid black">{{$tor_details_hs->elementary_course_completed_at}}</td>
            <td align="right">Year :</td>
            <td align="center" style="border-bottom: 1px solid black">{{$tor_details_hs->elementary_year}}</td>
            <td align="right">GWA :</td>
            <td align="center" style="border-bottom: 1px solid black">{{$tor_details_hs->elem_gwa}}</td>
        </tr>
    </table>
</div>

<br><hr>
<!--HS-->
@for($x=7; $x<=10; $x++)


@if($x == 10)

<div>
    <table width="100%" border="0">
        <tr>
            <td width="14%">Name :</td>
            <td colspan="6" style="border-bottom: 1px solid black"><strong style="font-size: 15pt;">{{strtoupper($user->lastname)}}, {{$user->firstname}} {{$user->middlename}} </strong></td>
        </tr>
        <tr>
            <td colspan="2">Junior Highschool Completed at :</td>
            <td align="center" style="border-bottom: 1px solid black">{{$tor_details_hs->jhs_course_completed_at}}</td>
            <td width="8%"align="right">Year :</td>
            <td align="center" style="border-bottom: 1px solid black">{{$tor_details_hs->jhs_year}}</td>
            <td width="8%" align="right">GWA :</td>
            <td align="center" style="border-bottom: 1px solid black">{{$tor_details_hs->jhs_gwa}}</td>
        </tr>
    </table>
</div>
<br><hr>
@endif


<?php
$records = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->whereRaw("(units = 0 or units >= 1)")->get();
?>
@if(count($records)>0)
                <?php $gwa = \App\TorGwa::firstOrCreate(['idno'=>"$idno",'level'=>"Grade $x",'school_year'=>$records->unique('school_year')->first()->school_year]); ?>
    <?php $school_attended = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->first(); ?>
<div style="text-align: center;  width: 100%;">
        <strong><u>{{"Grade ". $x}}</u></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        School: &nbsp;&nbsp;&nbsp;&nbsp;<strong><u>{{$school_attended->school_name}}</u></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        School Year: &nbsp;&nbsp;&nbsp;&nbsp;<strong><u>
                @if ($records->unique('school_year')->first() != null)
                {{$records->unique('school_year')->first()->school_year}} - {{$records->unique('school_year')->first()->school_year+1}}
                @endif
            </u></strong>
</div>
<br>
   <table class='table center' border="1" width="90%" cellspacing='0' cellpadding='0'>
    <tr>
        <td align="center" class='table-title strong'>SUBJECTS</td>
        <td align="center" class='table-title strong'>FINAL RATINGS</td>
        <td align="center" class='table-title strong'>ACTION TAKEN</td>
    </tr>
    @foreach($records as $record)
    <tr>
        <td>{{$record->card_name}}</td>
        <td align="center">{{$record->final_grade_letter}} {{($record->final_grade=="") ? "" : "(".$record->final_grade.")" }}</td>
        <td align="center">P</td>
    </tr>
    @endforeach
</table>
<div style="text-align: center;  width: 100%;">
        Days of School: {{($gwa->days_of_school) ? $gwa->days_of_school:"N/A" }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Days Present: {{($gwa->days_present) ? $gwa->days_present:"N/A"}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <i>General Average: <strong>{{$gwa->gwa_letter}}({{$gwa->gwa}})</strong></i>
</div>
<br>
<hr>
@endif
@if($x == 9)
<div style="page-break-after: always;"></div> 
@endif
@endfor

@for($x=11; $x<=11; $x++)
<?php 
$first_letter = "";
$first_gwa = 0;
$period = ['1st Semester','2nd Semester'];
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
$records = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->where('period',$period[$sem])->whereRaw("(units = 0 or units >= 1)")->get();
$gwa = \App\TorGwa::firstOrCreate(['idno'=>"$idno",'level'=>"Grade $x",'period'=>"$period[$sem]"]);
?>
<?php $school_attended = \App\TranscriptOfRecord::where('idno', $idno)->where('period',$period[$sem])->where('level', "Grade $x")->first(); ?>
<div>
    <strong><u>{{"Grade ". $x}}<br>
    STRAND: {{$gwa->strand}}  </u></strong>
</div>
<div style="text-align: center;  width: 100%;">
        School: &nbsp;&nbsp;&nbsp;&nbsp;<strong><u>{{$school_attended->school_name}}</u></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        School Year: &nbsp;&nbsp;&nbsp;&nbsp;<strong><u>
                @if ($records->unique('school_year')->first() != null)
                {{$records->unique('school_year')->first()->school_year}} - {{$records->unique('school_year')->first()->school_year+1}}, {{$period[$sem]}}
                @endif
            </u></strong>
</div>
<br>
   <table class='table center' border="1" width="90%" cellspacing='0' cellpadding='0'>
    <tr>
        <td align="center" class='table-title strong'>SUBJECTS</td>
        <td align="center" class='table-title strong'>FINAL RATINGS</td>
        <td align="center" class='table-title strong'>ACTION TAKEN</td>
    </tr>
    @if(count($get_subjects_heads)>0)
    @foreach($get_subjects_heads as $subject_heads)
    <?php $records = \App\TranscriptOfRecord::where('idno', $idno)->where('level', "Grade $x")->where('period',$period[$sem])->whereRaw("(units = 0 or units >= 1)")->where('group',$subject_heads->report_card_grouping)->get(); ?>
    <tr>
<td colspan="3" style="background-color:  lightgray"><strong>{{$subject_heads->report_card_grouping}}</strong></td></tr>                
    @foreach($records as $record)
    <tr>
        <td>{{$record->card_name}}</td>
        <td align="center">
            {{$record->first_grading_letter}} {{$record->second_grading_letter}} {{$record->third_grading_letter}} {{$record->fourth_grading_letter}}
            {{$record->first_remarks}} {{$record->second_remarks}} {{$record->third_remarks}} {{$record->fourth_remarks}}
        </td>
        <td align="center">P</td>
    </tr>
    @endforeach
    @endforeach
    @endif
    
    @if($period[$sem] == "1st Semester")
</table>
    <div style="text-align: center;  width: 100%;">
        Days of School: {{($gwa->days_of_school) ? $gwa->days_of_school:"N/A" }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Days Present: {{($gwa->days_present) ? $gwa->days_present:"N/A"}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <i>General Average for the {{$period[$sem]}}: <strong>{{$gwa->gwa_letter}}({{$gwa->gwa}})</strong></i>
    </div>
            <?php 
            $first_letter = $gwa->gwa_letter;
            $first_gwa = $gwa->gwa;
            ?>
    @else
    <tr>
        <td colspan="2" align="right">General Average for the {{$period[0]}}: </td>
        <td align="center"><strong>{{$first_letter}}({{$first_gwa}})</strong></td>
    </tr>
    <tr>
        <td colspan="2" align="right">General Average for the {{$period[1]}}: </td>
        <td align="center"><strong>{{$gwa->gwa_letter}}({{$gwa->gwa}})</strong></td>
    </tr>
    <tr>
        <td colspan="2" align="right"><strong>General Average for the Whole School Year: </strong></td>
        <td align="center"><strong>{{$gwa->final_gwa_letter}}({{$gwa->final_gwa}})</strong></td>
    </tr>
</table>
<div style="text-align: left; margin-left: 55px;  width: 100%;">
        Days of School: {{($gwa->days_of_school) ? $gwa->days_of_school:"N/A" }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Days Present: {{($gwa->days_present) ? $gwa->days_present:"N/A"}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </div>
    @endif
 
{!! ($sem==0)? "<hr>" : "" !!}

@endfor
@endif
@endfor

<br>
<table style="font-size: 6pt" class='table center' border="0" width="90%" cellspacing='0' cellpadding='0'>
    <tr>
        <td valign="top">Legend:</td>
        <td>A (Advanced) – 90 and above; P (Proficient) – 85-89; AP (Approaching Proficiency) – 80-84; D (Developing) – 75-79; B (Beginning) – 74 and below
O (Outstanding) 90 – 100, VS (Very Satisfactory) 85 – 89, S (Satisfactory) 80 – 84, FS (Fairly Satisfactory) 75 – 79, DNME (Did Not Meet Expectations) 74 and Below
Outstanding (95-100,5), Highly Satisfactory (90-94,4), Satisfactory (85-89,3), Moderately Satisfactory (80-84,2), Needs Improvement (75-79,1), Unsatisfactory (74 and Below,0)</td>
    </tr>
</table>
<hr>
<table style="font-size: 8pt; font-style: italic" class='table center' border="0" width="90%" cellspacing='0' cellpadding='0'>
    <tr>
        <td align="center">"Due to the declaration of Enhanced Community Quarantine (ECQ) because of COVID-19 Pandemic, the grades for the second
half of the second semester / 4th quarter are Pass or Fail and there is only one Conduct grade for the 1st & 2nd Semesters."</td>
    </tr>
</table>
<p width="90%" class="center" style="font-size: 10pt;">
    Copy of this record is for <u><strong>evaluation purposes only.</strong></u>
</p>
<br>
<br>
<br>
<table style="font-size: 10pt; text-align: center" class='table center' border="0" width="90%" cellspacing='0' cellpadding='0'>
    <tr>
        <td><u>{{date('F j, Y')}}</u></td>
        <td><u>Ma. Catalina M. Paralejas</u></td>
    </tr>
    <tr>
        <td><i>Date</i></td>
        <td><i>Registrar, Basic Education</i></td>
    </tr>
</table>
</body>
