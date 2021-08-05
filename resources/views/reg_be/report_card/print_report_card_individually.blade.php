<style>
    table tr td {
        font: 9pt !important;
    }
    .legend td {
        font: 12pt !important;
    }
</style>
<?php
$previous_group = "";
$skip = 0;

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

//get grades for grouping like mapeh and tle
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
        case "Grade 7":
            return "College";
            break;
    }
}
?>
<div  style="position:absolute; top:125px; bottom:0; left:20px; right:0;">
    <img style="opacity: 0.2" width="400px" src="{{public_path('/images/assumption-logo.png')}}">
</div>
<table width="50%">
    <tr>
        <td>Name</td><td colspan="3" style="border-bottom: 1px solid black">{{$user->getFullNameAttribute()}}</td>
    </tr>
    <tr>
        <td>Grade & Section</td><td style="border-bottom: 1px solid black">{{$status->level}} - {{$status->section}}</td><td align="right">School Year</td><td align="center"style="border-bottom: 1px solid black">{{$status->school_year}}-{{$status->school_year+1}}</td>
    </tr>
    <tr>
        <td>Class Adviser</td><td colspan="3" style="border-bottom: 1px solid black">@if($adviser){{$adviser->getFullNameAttribute()}}@endif</td>
    </tr>
</table>
<br>
<table border = 1 cellpadding = 6 cellspacing =0 width="50%">
    <tr style = "background: darkblue; color: white; font:bold">
        <td rowspan="2" align="center" width="60%" style="border:1px solid black;">LEARNING AREAS</td><td colspan="4" align="center" style="border-top:1px solid black;">QUARTER</td>
        <!--<td rowspan="2" align="center"  style="border:1px solid black;">FINAL<br>RATING</td>-->
        <td rowspan="2" align="center"  style="border:1px solid black;">REMARKS</td>
    </tr>
    <tr style = "background: darkblue; color: white; font:bold;">
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
    </tr>
    <?php
    $total_units = 0;
    $total_final_grade = 0;
    ?>
    @if(count($get_all_subjects)>0)
    @foreach($get_all_subjects as $get_all_subject)
    
    
    
    @foreach($get_all_subjects->where('subject_code', $get_all_subject->subject_code)->where('subject_type', 0)->where('is_alpha', 0) as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->fourth_remarks}}@else{{$subject->fourth_grading_letter}}@endif</td>

        @if($subject->final_grade != "")
        <td align="center">{{$subject->final_remarks}}({{$subject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">@if($subject->final_grade >= 74)Promoted @endif</td>
        @endif
        @else
        <td align="center">Pass</td>
        @endif


        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach
    
    
    

    @foreach($get_all_subjects->where('subject_code', $get_all_subject->subject_code)->where('subject_type', 1) as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->fourth_remarks}}@else{{$subject->fourth_grading_letter}}@endif</td>

        @if($subject->final_grade != "")
        <td align="center">{{$subject->final_remarks}}({{$subject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">@if($subject->final_grade >= 74)Promoted @endif</td>
        @endif
        @else
        <!--<td></td>-->
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Pass</td>
        @endif
        @endif
        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach
    
    
    
    <?php
    if($get_all_subject->subject_type == 2){
        $current_group = $get_all_subject->group_code;
        if($current_group == $previous_group){
            $skip = 1;
        }else{
            $skip = 0;
        }
    }
    ?>
    @if($skip == 0)
    @foreach($get_all_subjects->where('group_code', $get_all_subject->group_code)->where('subject_type', 2) as $group_code)
    @if($loop->first)
    @if(!fnmatch("SA[1,2,3,4,5,6,7,8,9,10]", $group_code->subject_code))
    <?php
    $get_split_subjects = \App\GradeBasicEd::selectRaw("max(final_grade) as final_grade, max(final_remarks) as final_remarks, max(display_subject_code) as display_subject_code,max(is_alpha) as is_alpha, max(first_grading_letter) as first_grading_letter,max(second_grading_letter) as second_grading_letter,max(third_grading_letter) as third_grading_letter,max(fourth_grading_letter) as fourth_grading_letter")
            ->where('idno', $idno)->where('school_year', $school_year)
            ->where('subject_type', 2)
            ->where('group_code', $get_all_subject->group_code)
            ->where('is_display_card', 1)
            ->get();
    ?>
    
    @if(count($get_split_subjects)>0)
    @foreach($get_split_subjects as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        @if(!fnmatch("SA[1,2,3,4,5,6,7,8,9,10]", $subject->group_code))
        <td align="center">@if($subject->is_alpha == 0){{$subject->fourth_remarks}}@else{{$subject->fourth_grading_letter}}@endif</td>
        @else
        <td align="center" style="font:10pt"></td>
        @endif

        @if($subject->final_grade != "" || $subject->final_remarks != "")
        @if($subject->group_code == "SA9" || $subject->group_code == "SA8")
        <td align="center">{{$subject->final_remarks}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif

        @else
        <td align="center">{{$subject->final_remarks}}@if($subject->final_grade != "")({{$subject->final_grade}})@endif</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">@if($subject->final_grade != "") @if($subject->final_grade >= 74) Promoted @endif @endif </td>
        @endif
        @endif
        @else
        <td align="center">Pass</td>
        @endif

        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach
    @endif
    @endif
    @endif
    @endforeach
    @endif
    <?php
    if($get_all_subject->subject_type == 2){
        $previous_group = $get_all_subject->group_code;
    }
    ?>
    
    
    
    <?php
    $grade1 = 0;
    $grade2 = 0;
    $grade3 = 0;
    ?>
    @if($get_all_subject->report_card_grouping != null)
    @foreach($get_grouping_subjects->where('subject_name',$get_all_subject->report_card_grouping) as $subject)
    @if(count($subject)>0)
    <?php $total_units += getUnits($subject, $idno, $school_year); ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">{{getLetterGrade($grade1=getGrades($subject,$idno,$school_year,'1'),$subject->letter_grade_type)}}</td>
        <td align="center">{{getLetterGrade($grade2=getGrades($subject,$idno,$school_year,'2'),$subject->letter_grade_type)}}</td>
        <td align="center">{{getLetterGrade($grade3=getGrades($subject,$idno,$school_year,'3'),$subject->letter_grade_type)}}</td>
        <td align="center">{{getLetterGrade($grade4=getGrades($subject,$idno,$school_year,'4'),$subject->letter_grade_type)}}</td>

        <?php $grade = ($grade1 + $grade2 + $grade3 + $grade4) / 4; ?>


        <td align="center">{{getFinalRating($grade, $subject->letter_grade_type)}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @if($total_units>0)
        <?php $total_final_grade += $grade; ?>
        @endif
    </tr>
    @if($subject->subject_name != "Technology and Livelihood Education" && $subject->subject_name != "Edukasyong Pantahanan At Pangkabuhayan")
    <?php $get_group_split_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', '>', 2)->orderBy('sort_to', 'asc')->where('report_card_grouping', $subject->subject_name)->get(); ?>
    @if(count($get_group_split_subjects)>0)
    @foreach($get_group_split_subjects as $splitsubject)
    <tr>
        <td>{{$splitsubject->display_subject_code}}</td>
        <td align="center">@if($splitsubject->is_alpha == 0){{$splitsubject->first_remarks}}@else{{$splitsubject->first_grading_letter}}@endif</td>
        <td align="center">@if($splitsubject->is_alpha == 0){{$splitsubject->second_remarks}}@else{{$splitsubject->second_grading_letter}}@endif</td>
        <td align="center">@if($splitsubject->is_alpha == 0){{$splitsubject->third_remarks}}@else{{$splitsubject->third_grading_letter}}@endif</td>
        <td align="center">@if($splitsubject->is_alpha == 0){{$splitsubject->fourth_remarks}}@else{{$splitsubject->fourth_grading_letter}}@endif</td>
        <td align="center" style="font:10pt">Pass</td>

        @if($splitsubject->final_grade != "")
        <td align="center">{{$splitsubject->final_remarks}}({{$splitsubject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">@if($splitsubject->final_grade >= 74) Promoted @endif</td>
        @endif
        @else
        <td align="center">Pass</td>
        @endif
    </tr>
    @endforeach
    @endif
    @endif

    @endif
    @endforeach
    @endif



    @foreach($get_all_subjects->where('group_code', $get_all_subject->group_code)->where('subject_type', 2) as $group_code)
    @if(fnmatch("SA[1,2,3,4,5,6,7,8,9,10]", $group_code->subject_code))
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->fourth_remarks}}@else{{$subject->fourth_grading_letter}}@endif</td>
        @if(!fnmatch("SA[1,2,3,4,5,6,7,8,9]", $subject->group_code) && !$subject->group_code == "SA10")<td align="center" style="font:10pt">Pass</td>
        @else<td align="center" style="font:10pt"></td>

        @endif

        @if($subject->final_grade != "" || $subject->final_remarks != "")
        @if($subject->group_code == "SA9" || $subject->group_code == "SA8")
        <td align="center">{{$subject->final_remarks}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif

        @else
        <td align="center">{{$subject->final_remarks}}@if($subject->final_grade != "")({{$subject->final_grade}})@endif</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @endif
        @else
        <td align="center">Pass</td>
        @endif

        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endif
    @endforeach

    @foreach($get_all_subjects->where('subject_code', $get_all_subject->subject_code)->where('subject_type', 0)->where('is_alpha','!=',0) as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->fourth_remarks}}@else{{$subject->fourth_grading_letter}}@endif</td>

        @if($subject->final_grade_letter != "")
        <td align="center">{{$subject->final_grade_letter}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @else
        <td align="center">@if($subject->final_remarks != null){{$subject->final_remarks}} @else Pass @endif </td>
        @endif
        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach

    @endforeach
    @endif
<!--    <tr>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td style = "background: darkblue; color: white; font:bold; border:1px solid black;" colspan="5">GENERAL AVERAGE</td>
        <td align="center">
            <strong>{{getLetterGrade(round($total_final_grade/$total_units,2),"Regular")}}  @if(round($total_final_grade/$total_units,3)>0){{(round($total_final_grade/$total_units,3))}} @endif</strong>
        </td>
        @else
        <td style = "background: darkblue; color: white; font:bold; border:1px solid black;" colspan="5">GENERAL AVERAGE</td>
        <td align="center">
            <strong>{{getLetterGrade(round($total_final_grade/$total_units,2),"Regular")}}  @if(round($total_final_grade/$total_units,3)>0){{(round($total_final_grade/$total_units,3))}} @endif</strong>
        </td>
        @endif
    </tr>-->
</table>
<br>
<div style="position:absolute; top:510px; bottom:0; left:0; right:0;">
    <table border = 1 cellpadding = 1 cellspacing =0 width="50%">
        <tr>
            <td align="center" rowspan="2">ATTENDANCE</td>
            <td align="center" rowspan="2">Aug</td>
            <td align="center" rowspan="2">Sep</td>
            <td align="center" rowspan="2">Oct</td>
            <td align="center" rowspan="2">Nov</td>
            <td align="center" rowspan="2">Dec</td>
            <td align="center" rowspan="2">Jan</td>
            <td align="center" rowspan="2">Feb</td>
            <td align="center" rowspan="2">Mar</td>
            <td align="center" rowspan="2">Apr</td>
            <td align="center" rowspan="2">May</td>
            <td align="center" style="font:7pt !important;" width="16%">Days of School</td>
        </tr>
<?php $school_days = \App\CtrSchoolDay::where('academic_type','BED')->where('school_year',$school_year)->value('school_days');
?>
        <tr>
            <td align="center">{{$school_days}}</td>
        </tr><?php $total_absent = 0; ?>
        </tr><?php $totalab = 0; ?>
        <tr>
            <td align="center">Absences</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('08', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('09', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('10', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('11', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('12', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
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
            <td align="center"><?php $tardy = getAttendances('08', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('09', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('10', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('11', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('12', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('01', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('02', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('03', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('04', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('05', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
        </tr>
    </table>
</div>
<!--LEGEND-->
<!--<div style="position:absolute; top:0px; bottom:0; left:480px; right:0; font:11pt !important;">
    LEGEND:
    <ul style="list-style: none;">
        <li><span>A(Advance)</span>...............................................<span>90% and more</span>
        <li><span>P(Proficient)</span>..............................................<span>85% - 89%</span>
        <li><span>AP(Advance Proficiency)</span>.........................<span>80% - 84%</span>
        <li><span>D(Developing)</span>..........................................<span>75% - 79</span>
        <li><span>B(Beginning)</span>............................................<span>74% and below</span>
    </ul>
</div>-->

<div style="position:absolute; top:40px; bottom:0; left:580px; right:0;">
    LEGEND:
    <table style=" font:11pt !important;">
<!--        <tr class="legend"><td>O</td><td>-</td><td>Outstanding</td></tr>
        <tr class="legend"><td>HS</td><td>-</td><td>Highly Satisfactory</td></tr>
        <tr class="legend"><td>S</td><td>-</td><td>Satisfactory</td></tr>
        <tr class="legend"><td>MS</td><td>-</td><td>Moderately Satisfactory</td></tr>
        <tr class="legend"><td>NI</td><td>-</td><td>Needs Improvement</td></tr>
        <tr class="legend"><td>U</td><td>-</td><td>Unsatisfactory</td></tr>-->
        <tr class="legend"><td>EE</td><td>-</td><td>Exceeds Expectations</td></tr>
        <tr class="legend"><td>ME</td><td>-</td><td>Meets Expectations</td></tr>
        <tr class="legend"><td>NI</td><td>-</td><td>Needs Improvement</td></tr>
    </table>
</div>

<div style="position:absolute; top:190px; bottom:0; left:480px; right:0; font:11pt; text-align: justify">
    <strong>CERTIFICATE OF TRANSFER</strong><br>
<!--    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
    She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.-->
    
    
    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
    @if($status->idno == "2021101" or $status->idno == "1010451" or $status->idno == "1010478")
    She is eligible for transfer and should be admitted to <strong>{{$status->level}}</strong>.
    @else
    She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.
    @endif
    
    <br>
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    <i>May 28, 2021</i><br>
    Date
</div>

<div style="position:absolute; top:440px; bottom:0; left:480px; right:0; font:11pt; text-align: justify">
    <strong>CANCELLATION OF TRANSFER ELIGIBILITY</strong><br>
    Has been admitted to __________________________________<br>
    ____________________________________________________.
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    Date
</div>

