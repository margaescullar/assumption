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

//get grades for grouping like mapeh and tle
function getGrades($subject, $idno, $school_year, $period) {
    $getsubjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', $subject->subject_name)->get();
    $final_grade = 0;
    foreach ($getsubjects as $get) {
        switch ($period) {
            case "1":
                if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8" ) {
                    $final_grade += $get->first_grading * ($get->units);
                    if($get->group_code=="EPP5" || $get->group_code=="EPP4" || $get->group_code=="EPP6" || $get->group_code=="TLE7" || $get->group_code=="TLE8" || $get->group_code=="TLE9" || $get->group_code=="TLE10"){
                    $final_grade = $final_grade + ($get->first_grading * (1-$get->units));
                    return $final_grade;
                    }
                } else {
                    $final_grade += 100 * ($get->units);
                }
                break;
            case "2":
                if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8" ) {
                    $final_grade += $get->second_grading * ($get->units);
                    if($get->group_code=="EPP5" || $get->group_code=="EPP4" || $get->group_code=="EPP6" || $get->group_code=="TLE7" || $get->group_code=="TLE8" || $get->group_code=="TLE9" || $get->group_code=="TLE10"){
                    $final_grade = $final_grade + ($get->second_grading * (1-$get->units));
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

//for tor
function getFinalRatingLetter($grade, $letter_grade_type) {
    $round = round($grade);
    $round2 = round($grade, 2);
    $final_letter_grade = \App\CtrTransmuLetter::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->first();
    $letter = $final_letter_grade['letter_grade'];
    return "$letter";
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
        <td>Class Adviser</td><td colspan="3" style="border-bottom: 1px solid black">{{$adviser->getFullNameAttribute()}}</td>
    </tr>
</table>
<br>
<table border = 1 cellpadding = 6 cellspacing =0 width="50%">
    <tr style = "background: darkblue; color: white; font:bold">
        <td rowspan="2" align="center" width="60%" style="border:1px solid black;">LEARNING AREAS</td><td colspan="4" align="center" style="border-top:1px solid black;">QUARTER</td><td rowspan="2" align="center"  style="border:1px solid black;">FINAL<br>RATING</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td rowspan="2" align="center"  style="border:1px solid black;">REMARKS</td>
        @endif
    </tr>
    <tr style = "background: darkblue; color: white; font:bold">
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
    </tr>
    <?php
    $total_units = 0;
    $total_final_grade = 0;
    ?>
    @if(count($get_regular_subjects)>0)
    @foreach($get_regular_subjects as $subject)
    <?php
    indexController::fetch_grades($idno,$subject);
    ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        
        
        @if($idno == "1718105" || $idno == "1718104" || $idno == "1010478")
            <td align="center" style="font:10pt">{{$subject->fourth_remarks}}</td>
        @else
            <td align="center" style="font:10pt">Pass</td>
        @endif
            @if($subject->final_grade != "")
            <td align="center">{{$subject->final_remarks}}({{$subject->final_grade}})</td>
            @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
            <td align="center">@if($subject->final_grade >= 74)Promoted @endif</td>
            @endif
            @else
            <td></td>
            @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
            <td></td>
            @endif
            @endif
    </tr>
    @endforeach
    @endif

    @if(count($get_group_subjects)>0)
    @foreach($get_group_subjects as $subject)
    <?php
    indexController::fetch_grades($idno,$subject);
    ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        <td align="center" style="font:10pt">Pass</td>

        @if($subject->final_grade != "")
        <td align="center">{{$subject->final_remarks}}({{$subject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">@if($subject->final_grade >= 74)Promoted @endif</td>
        @endif
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
    </tr>
    @endforeach
    @endif

    @if(count($get_split_subjects)>0)
    @foreach($get_split_subjects as $subject)
    <?php
    indexController::fetch_grades($idno,$subject);
    ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        @if(!fnmatch("SA[1,2,3,4,5,6,7,8,9,10]", $subject->group_code))<td align="center" style="font:10pt">Pass</td>
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
            <td align="center">@if($subject->final_grade != "") @if($subject->final_grade >= 74) Promoted @endif @endif </td>
            @endif
        @endif
        @else
        <td></td>
            @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
            <td></td>
            @endif
        @endif
    </tr>
    @endforeach
    @endif

    @if(count($get_grouping_subjects))
    <?php
    $grade1 = 0;
    $grade2 = 0;
    $grade3 = 0;
    ?>
    @foreach($get_grouping_subjects as $subject)
    <?php $total_units += getUnits($subject,$idno,$school_year); ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">{{$first=getLetterGrade($grade1=getGrades($subject,$idno,$school_year,'1'),$subject->letter_grade_type)}}</td>
        <td align="center">{{$second=getLetterGrade($grade2=getGrades($subject,$idno,$school_year,'2'),$subject->letter_grade_type)}}</td>
        <td align="center">{{$third=getLetterGrade($grade3=getGrades($subject,$idno,$school_year,'3'),$subject->letter_grade_type)}}</td>
        <!--<td align="center">{{$first=getGrades($subject,$idno,$school_year,'4')}}</td>-->
        <td align="center" style="font:10pt">Pass</td>
      
@if($idno == 1920295 or $idno == 1920294)        
<?php $grade = ($grade2 + $grade3) / 2; ?>
@else
<?php $grade = ($grade1 + $grade2 + $grade3) / 3; ?>
@endif

    <?php
    $subject->subject_code = $subject->subject_name;
    $subject->display_subject_code = $subject->subject_name;
    $subject->level = $status->level;
    $subject->units = 1;
    $subject->school_year = $school_year;
    $subject->first_remarks = $first;
    $subject->second_remarks = $second;
    $subject->third_remarks = $third;
    $subject->fourth_remarks = "Pass";
    $subject->final_grade = $grade;
    $subject->final_remarks = getFinalRatingLetter($grade, $subject->letter_grade_type);
    indexController::fetch_grades($idno,$subject);
    ?>
        
        
        <td align="center">{{getFinalRating($grade, $subject->letter_grade_type)}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
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
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        <td align="center" style="font:10pt">Pass</td>

        @if($splitsubject->final_grade != "")
        <td align="center">{{$splitsubject->final_remarks}}({{$splitsubject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">@if($splitsubject->final_grade >= 74) Promoted @endif</td>
        @endif
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
    </tr>
    @endforeach
    @endif
    @endif

    @endforeach
    @endif

    

    @if(count($get_sa_subjects)>0)
    @foreach($get_sa_subjects as $subject)
    <?php
    indexController::fetch_grades($idno,$subject);
    ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
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
        <td></td>
            @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
            <td></td>
            @endif
        @endif
    </tr>
    @endforeach
    @endif
    

    @if(count($get_regular_alpha_subjects)>0)
    @foreach($get_regular_alpha_subjects as $subject)
    <?php
    $subject->final_remarks = $subject->final_grade_letter;
    indexController::fetch_grades($idno,$subject);
    ?>
    <tr>
        <td>{{$subject->display_subject_code}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        <td align="center" style="font:10pt"></td>

        @if($subject->final_grade_letter != "")
        <td align="center">{{$subject->final_grade_letter}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
    </tr>
    @endforeach
    @endif

    <tr>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td style = "background: darkblue; color: white; font:bold; border:1px solid black;" colspan="5">GENERAL AVERAGE</td><td align="center" colspan="2"><strong>{{$get_final_grade->final_letter_grade}}({{round($get_final_grade->final_ave,3)}})</strong></td>
        @else
        <td style = "background: darkblue; color: white; font:bold; border:1px solid black;" colspan="5">GENERAL AVERAGE</td><td align="center"><strong>{{$get_final_grade->final_letter_grade}}({{round($get_final_grade->final_ave,3)}})</strong></td>
        @endif
    </tr>
</table>
<br>
@if($idno == 1920295)
<table border = 1 cellpadding = 2 cellspacing =0 width="50%">
    <tr><td><span style="font-style: italic !important">
                First Quarter: Assumption English School-Singapore
            </span></td></tr>
</table>
@endif

@if($idno == 1920294)
<table border = 1 cellpadding = 2 cellspacing =0 width="50%">
    <tr><td><span style="font-style: italic !important">
                First Quarter: St. Theresa's College-Quezon City
            </span></td></tr>
</table>
<br>
@endif
<table border = 1 cellpadding = 2 cellspacing =0 width="50%">
    <tr><td><span style="font-style: italic !important">
                "Due to the declaration of Enhanced Community Quarantine(ECQ) because of the COVID-19 Pandemic, 
                the grades for the second half of the second semester / 4th quarter are Pass or Fail."
            </span></td></tr>
</table>
<br>
<div style="position:absolute; top:630px; bottom:0; left:0; right:0;">
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
        
<?php $school_days = \App\CtrSchoolDay::where('academic_type','BED')->where('school_year',$school_year)->value('school_days'); ?>
        <tr>
            <td align="center">{{$school_days}}</td>
        </tr><?php $total_absent = 0; ?>
        </tr><?php $totalab = 0; ?>
        <tr>
            <td align="center">Absences</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('08',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('09',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('10',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('11',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('12',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('01',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('02',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('03',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('04',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab=getAttendances('05',$school_year,$idno,'absences')?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center" valign="top" rowspan="2"><span style="font:7pt !important;">Days Present</span><br>{{$school_days-$total_absent}}</td>
        </tr>
        <tr>
            <td align="center">Tardiness</td>
            <td align="center"><?php $tardy=getAttendances('08',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('09',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('10',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('11',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('12',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('01',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('02',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('03',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('04',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy=getAttendances('05',$school_year,$idno,'tardiness')?>@if($tardy!=0) {{$tardy}} @endif</td>
        </tr>
    </table>
</div>
<!--LEGEND-->
<div style="position:absolute; top:0px; bottom:0; left:480px; right:0; font:11pt !important;">
    LEGEND:
    <ul style="list-style: none;">
        <li><span>A(Advance)</span>...............................................<span>90% and more</span>
        <li><span>P(Proficient)</span>..............................................<span>85% - 89%</span>
        <li><span>AP(Advance Proficiency)</span>.........................<span>80% - 84%</span>
        <li><span>D(Developing)</span>..........................................<span>75% - 79</span>
        <li><span>B(Beginning)</span>............................................<span>74% and below</span>
    </ul>
</div>

<div style="position:absolute; top:140px; bottom:0; left:580px; right:0;">
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
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    June 8, 2020<br>
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
    June 8, 2020<br>
    Date
</div>

<?php
$gwa_records = new App\TorGwa;
$gwa_records->level = $status->level;
$gwa_records->school_year = $school_year;
//$gwa_records->period = $period;
$gwa_records->gwa_letter = $get_final_grade->final_letter_grade;
$gwa_records->gwa = round($get_final_grade->final_ave,3);
$gwa_records->days_of_school = $school_days;
$gwa_records->days_present = $school_days-$total_absent;
indexController::fetch_gwa($idno,$gwa_records);
?>

