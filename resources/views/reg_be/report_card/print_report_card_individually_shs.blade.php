<style>
    table tr td {
        font: 9pt !important;
    }
    .legend td {
        font: 12pt !important;
    }
</style>
<?php
use App\Http\Controllers\BedRegistrar\Records\indexController as indexController;

function getAttendances($month, $school_year, $idno, $type) {
    if ($type == 'absences') {
        $getAttendances = \App\Absent::SelectRaw('sum(is_absent)as total')->where('school_year', $school_year)->where('idno', $idno)->where('date', 'like', "%-$month-%")->first();
    } else {
        $getAttendances = \App\Absent::SelectRaw('sum(is_tardy)as total')->where('school_year', $school_year)->where('idno', $idno)->where('date', 'like', "%-$month-%")->first();
    }
    if ($getAttendances->total > 0) {
        return $getAttendances->total;
    } else {
        return 0;
    }
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
function getLetterGrade($grade, $letter_grade_type, $school_year) {
    $round = round($grade);
    $final_letter_grade = \App\CtrTransmuLetterArchive::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->where('school_year', $school_year)->first();
    $letter = $final_letter_grade['letter_grade'];
    return "$letter";
}

function getPromotion($level) {
    switch ($level) {
        case "Pre Kinder":
            return "Kinder";
            break;
        case "Kinder":
            return "Grade 1";
            break;
        case "Grade 1":
            return "Grade 2";
            break;
        case "Grade 2":
            return "Grade 3";
            break;
        case "Grade 3":
            return "Grade 4";
            break;
        case "Grade 4":
            return "Grade 5";
            break;
        case "Grade 5":
            return "Grade 6";
            break;
        case "Grade 6":
            return "Grade 7";
            break;
        case "Grade 7":
            return "Grade 8";
            break;
        case "Grade 8":
            return "Grade 9";
            break;
        case "Grade 9":
            return "Grade 10";
            break;
        case "Grade 10":
            return "Grade 11";
            break;
        case "Grade 11":
            return "Grade 12";
            break;
        case "Grade 12":
            return "1st Year College";
            break;
    }
}
?>

<div  style="position:absolute; top:125px; bottom:0; left:20px; right:0;">
    <img style="opacity: 0.2" width="400px" src="{{public_path('/images/assumption-logo.png')}}">
</div>
<table width="50%" cellpadding="0" cellspacing="0">
    <tr>
        <td>Name</td><td colspan="5" style="border-bottom: 1px solid black">{{$user->getFullNameAttribute()}}</td>
    </tr>
    <tr>
        <td>Grade & Section</td><td colspan="2" style="border-bottom: 1px solid black">{{$status->level}} - 
            @if($status->strand == "AD")AD 1 @elseif($status->strand == "HUMSS")H {{$status->section}} @elseif($status->strand == "ABM")B {{$status->section}} @elseif($status->strand == "STEM")S {{$status->section}} @endif</td>
        <td align="right">Strand</td><td colspan="2" align="center" style="border-bottom: 1px solid black">@if($status->strand == "PA") Arts and Design @else{{$status->strand}}@endif</td>
    </tr>
    <tr>
        <td>Class Adviser</td><td colspan="2" style="border-bottom: 1px solid black">@if($adviser){{$adviser->getFullNameAttribute()}}@endif</td>
        <td align="right">School Year</td><td  colspan="2" align="center"style="border-bottom: 1px solid black">{{$school_year}}-{{$school_year+1}}, {{$period}}</td>
    </tr>
</table>
<br>
<table border = 1 cellpadding = 4 cellspacing =0 width="50%">
    <tr style = "font:bold">
        <td rowspan="2" align="center" width="60%">SUBJECTS</td>
        <td colspan="2" align="center">FINAL RATING</td>
        <td rowspan="2" align="center">REMARKS</td>
    </tr>
    <tr style = "font:bold">
        <td align="center">Module <br>1</td>
        <td align="center">Module <br>2</td>
    </tr>
    <?php
    $total_units = 0;
    $total_final_grade = 0;
    ?>

    @if(count($get_subjects_heads)>0)
    @foreach($get_subjects_heads as $subject_heads)
    <tr>
        <td colspan="4" style="background: darkblue; border:1px solid black; color: white; font:bold">{{$subject_heads->report_card_grouping}}</td>
    </tr>
    <?php $get_subjects = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_name', 'not like', "%Student Activit%")->where('subject_code', 'not like', "%PEH%")->where('is_alpha', 0)->where('is_display_card', 1)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->get(); ?>
    <?php $get_pe_2nd = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', "2nd Semester")->where('subject_code', 'like', "%PEH%")->where('is_alpha', 0)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get(); ?>
    <?php $get_pe_1st = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', "1st Semester")->where('subject_code', 'like', "%PEH%")->where('is_alpha', 0)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->first(); ?>
    <?php $get_sa = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_name', 'like', "%Student Activit%")->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get(); ?>
    <?php $get_conduct = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('is_alpha', 1)->orderBy('report_card_grouping', 'desc')->where('is_display_card', 1)->orderBy('sort_to', 'asc')->get(); ?>

    @if(count($get_subjects)>0)
            @foreach($get_subjects as $subject)
            <?php
            indexController::fetch_grades($idno,$subject);
            ?>
            <tr>
                <td>{{$subject->display_subject_code}}</td>
<!--3rd Quarter-->
                <td align="center">
                    @if($subject->is_alpha == 0)
                        @if($display_type == 0)
                        {{$subject->third_remarks}}
                        @elseif($display_type == 1)
                        @if($subject->third_grading != null)
                        {{$subject->third_remarks}}({{$subject->third_grading}})
                        @endif
                        @endif
                    @else
                    {{$subject->third_grading_letter}}
                    @endif
                </td>
<!--4th Quarter-->
                <td align="center">
                    @if($subject->is_alpha == 0)
                        @if($display_type == 0)
                        {{$subject->fourth_remarks}}
                        @elseif($display_type == 1)
                        @if($subject->fourth_grading != null)
                        {{$subject->fourth_remarks}}({{$subject->fourth_grading}})
                        @endif
                        @endif
                    @else
                    {{$subject->fourth_grading_letter}}
                    @endif
                </td>
<!--Remarks-->
                @if($subject->third_grading+$subject->fourth_grading/1 >74)
                <td align="center">Pass</td>
                @elseif($subject->third_grading+$subject->fourth_grading/1 >0 and $subject->third_grading+$subject->fourth_grading/1< 74)
                <td align="center">Fail</td>
                @else
                <td></td>
                @endif
            </tr>
            @endforeach
        @endif

    @if(count($get_pe_2nd)>0)
    @foreach($get_pe_2nd as $subject)
            <?php
            indexController::fetch_grades($idno,$subject);
            ?>
    <tr>
                <td>{{$subject->display_subject_code}}</td>
                <!--third grading-->
                <td align="center">
                    @if($subject->is_alpha == 0)
                        @if($display_type == 0)
                        {{$subject->third_remarks}}
                        @elseif($display_type == 1)
                            @if($subject->third_grading != null)
                            {{$subject->third_remarks}}({{$subject->third_grading}})
                            @endif
                        @endif
                    @else
                    {{$subject->third_grading_letter}}
                    @endif
                </td>
                
                <!--fourth grading-->
                <td align="center">
                    @if($subject->is_alpha == 0)
                        @if($display_type == 0)
                        {{$subject->fourth_remarks}}
                        @elseif($display_type == 1)
                            @if($subject->fourth_grading != null)
                            {{$subject->fourth_remarks}}({{$subject->fourth_grading}})
                            @endif
                        @endif
                    @else
                    {{$subject->fourth_grading_letter}}
                    @endif
                </td>
                
                @if($subject->third_grading+$subject->fourth_grading/1 >74)
                <td align="center">Pass</td>
                @elseif($subject->third_grading+$subject->fourth_grading/1 >0 and $subject->third_grading+$subject->fourth_grading/1< 74)
                <td align="center">Fail</td>
                @else
                <td></td>
                @endif
            </tr>
    @endforeach
    @endif


    @if(count($get_sa)>0)
            @foreach($get_sa as $subject)
            <?php
            indexController::fetch_grades($idno,$subject);
            ?>
            <tr>
                <td>{{$subject->display_subject_code}}</td>
                <td align="center">
                    {{$subject->third_remarks}}
                </td>
                <td align="center">
                    {{$subject->fourth_remarks}}                
                </td>
                <td align="center" style="font:10pt">Pass</td>
            </tr>
            @endforeach
        @endif

    @if(count($get_conduct)>0)
            @foreach($get_conduct as $subject)
            <?php
            indexController::fetch_grades($idno,$subject);
            ?>
            <tr>
                <td>{{$subject->display_subject_code}}</td>
                <td align="center">{{$subject->third_grading_letter}}</td>
                <td align="center" style="font:10pt"></td>
                <td align="center" style="font:10pt">{{$subject->final_grade_letter}}</td>
                <td align="center" style="font:10pt">Pass</td>
            </tr>
            @endforeach
        @endif

    @endforeach
    @endif


    <tr>
        <td align="right" style = "font:bold" colspan="2">General Average for the First Semester:</td><td align="center" colspan="2"><strong>@if($get_first_sem_final_ave){{$get_first_sem_final_ave->final_letter_grade}}({{$get_first_sem_final_ave->final_grade}})@endif</strong></td>
    </tr>
    <tr>
        <td align="right" style = "font:bold" colspan="2">General Average for the Second Semester:</td><td align="center" colspan="2"><strong>@if($get_second_sem_final_ave){{$get_second_sem_final_ave->final_letter_grade}}({{$get_second_sem_final_ave->final_grade}})@endif</strong></td>
    </tr>
    <tr>
        <td align="right" style = "font:bold" colspan="2">General Average for the Whole School Year:</td><td align="center" colspan="2"><strong>
                @if($get_first_sem_final_ave)
                {{getLetterGrade(round(($get_first_sem_final_ave->final_grade+$get_second_sem_final_ave->final_grade)/2,3),'SHS',$school_year)}}({{round(($get_first_sem_final_ave->final_grade+$get_second_sem_final_ave->final_grade)/2,3)}})
                @else
                {{getLetterGrade(round($get_second_sem_final_ave->final_grade,3),'SHS',$school_year)}}({{round($get_second_sem_final_ave->final_grade,3)}})
                @endif</strong></td>
    </tr>
</table>
<div style="position:absolute; top:580px; bottom:0; left:0; right:0;">
    <table border = 1 cellpadding = 1 cellspacing =0 width="50%">
        <tr>
            <td align="center" rowspan="2">ATTENDANCE</td>
            <td align="center" rowspan="2">Jan</td>
            <td align="center" rowspan="2">Feb</td>
            <td align="center" rowspan="2">Mar</td>
            <td align="center" rowspan="2">Apr</td>
            <td align="center" rowspan="2">May</td>
            <td align="center" style="font:7pt !important;" width="16%">Days of School</td>            
            <?php $school_days = \App\CtrSchoolDay::where('academic_type', 'SHS')->where('school_year', $school_year)->where('period', $period)->value('school_days'); ?>
        </tr>
        <tr>
            <td align="center">{{$school_days}}</td>
        </tr><?php $total_absent = 0; ?>
        </tr><?php $totalab = 0; ?>
        <tr>
            <td align="center">Absences</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('01', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('02', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('03', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('04', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('05', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <!--<td align="center" valign="top" rowspan="2"><span style="font:7pt !important;">Days Present</span><br>{{$school_days-$total_absent}}</td>-->
            <td align="center" valign="top" rowspan="2"><span style="font:7pt !important;">Days Present</span><br>N/A</td>
        </tr>
        <tr>
            <td align="center">Tardiness</td>
            <td align="center"><?php $tardy = getAttendances('01', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('02', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('03', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('04', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('05', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
        </tr>
    </table>
</div>
<!--LEGEND-->
<div style="position:absolute; top:0px; bottom:0; left:480px; right:0; font:11pt !important;">
    LEGEND:
    <ul style="list-style: none;">
        <!--<li><span>O(Outstanding)</span>..................................<span>90% and 100%</span>-->
        <li><span>VS(Very Satisfactory)</span>.......................<span>90% - 100%</span>
        <li><span>S(Satisfactory)</span>...................................<span>80% - 89%</span>
        <li><span>NI(Need Improvements)</span>...................<span>75% - 79%</span>
        <li><span>D(Did Not Meet Expectations)</span>.........<span>74% and below</span>
    </ul>
</div>

<div style="position:absolute; top:130px; bottom:0; left:580px; right:0;">
    <strong>Student Activities</strong>
    <table style=" font:11pt !important;">
        <tr class="legend"><td>O</td><td>-</td><td>Outstanding</td></tr>
        <tr class="legend"><td>HS</td><td>-</td><td>Highly Satisfactory</td></tr>
        <tr class="legend"><td>S</td><td>-</td><td>Satisfactory</td></tr>
        <tr class="legend"><td>MS</td><td>-</td><td>Moderately Satisfactory</td></tr>
        <tr class="legend"><td>NI</td><td>-</td><td>Needs Improvement</td></tr>
        <tr class="legend"><td>U</td><td>-</td><td>Unsatisfactory</td></tr>
    </table>
</div>

<div style="position:absolute; top:305px; bottom:0; left:480px; right:0; font:11pt; text-align: justify">
    <strong>CERTIFICATE OF TRANSFER</strong><br>
    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
    She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.
    <br>
    <img style="display: block;max-width:200px;max-height:95px;width: auto;height: auto; " src="{{public_path('/images/SMV-Signature.png')}}">    
    <br>

</div>
<div style="position:absolute; top:420px; bottom:0; left:480px; right:0; font:11pt; text-align: justify">
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal, Assumption College, Makati City<br><br>
    @if($status->level == "Grade 11")
    June 8, 2021<br>
    @elseif($status->level == "Grade 12")
    June 3, 2021<br>
    @endif
    Date
</div>
<div style="position:absolute; top:520px; bottom:0; left:480px; right:0; font:11pt; text-align: justify">
    <strong>CANCELLATION OF TRANSFER ELIGIBILITY</strong><br>
    Has been admitted to __________________________________<br>
    ____________________________________________________.
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    Date
</div>
<?php
$gwa_records = new App\TorGwa;
$gwa_records->level = $status->level;
$gwa_records->strand = $status->strand;
$gwa_records->school_year = $school_year;
$gwa_records->period = "2nd Semester";
$gwa_records->gwa_letter = $get_second_sem_final_ave->final_letter_grade;
$gwa_records->gwa = round($get_second_sem_final_ave->final_grade,3);
$gwa_records->days_of_school = $school_days;
$gwa_records->final_gwa_letter = getLetterGrade(round(($get_first_sem_final_ave->final_grade+$get_second_sem_final_ave->final_grade)/2,3),'SHS',$school_year);
$gwa_records->final_gwa = round(($get_first_sem_final_ave->final_grade+$get_second_sem_final_ave->final_grade)/2,3);
//$gwa_records->days_present = $school_days-$total_absent;
$gwa_records->days_present = "N/A";
indexController::fetch_gwa($idno,$gwa_records);
?>

