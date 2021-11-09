<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>

<style>
    body {
        font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
        font-size: 9pt;
    }
    footer {
        font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
        font-size: 8pt;
    }
</style>  
<style type="text/css">
    @page {
        margin: 0cm 0cm; 
    }
    #pageNumber { counter-increment: chapter }
    #pageNumber:after { content: counter(chapter) } 
    body {
        margin-top: 4.4cm;
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 6.4cm;
    }
    footer {
        position: fixed; 
        bottom: 6.3cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 
        margin: 0cm 1cm cm 1cm;
    }
    header{
        position: fixed; 
        top: 4cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 
        margin: 0cm 1cm cm 1cm;
    }

    img {
        display: block;
        max-width:3.5cm;
        max-height:3.5cm;
        width: auto;
        height: auto;
    }
</style>
<body>
    <script type="text/php">
        if ( isset($pdf) ) {
        $x = 515;
        $y = 880;
        $text = "** Page {PAGE_NUM} of {PAGE_COUNT} **";
        $font = $fontMetrics->get_font("courier new");
        $size = 7;
        $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
    <footer>
        <table width='100%'>
            <tr>
                <td valign='top' width='30%'><i>PREPARED BY</i></td>
                <td valign='top' width='30%'><i>VERIFIED BY</i></td>
                <td style="text-align:justify" valign='top' width='40%' rowspan="2" colspan="2">
                    <b>I HEREBY CERTIFY THAT THE FOREGOING RECORDS HAVE BEEN DULY VERIFIED BY ME AND THAT THE TRUE COPIES OF THE OFFICIAL RECORDS SUBSTANTIATING THE SAME ARE KEPT IN THE FILES OF THE COLLEGE.</b>
                </td>
            </tr>
            <tr>
                <td valign='middle'>{{strtoupper(Auth::user()->firstname)}} {{strtoupper(Auth::user()->middlename)}} {{strtoupper(Auth::user()->lastname)}}</td>
                <td valign='middle'>{{strtoupper(env("HED_REGISTRAR_VERIFIED_BY"))}}</td>
            </tr>
            <tr>
                <td  valign='top' colspan='2' rowspan="2">
                </td>
                <td valign='bottom' colspan='2'><i><br>APPROVED BY<br><br><br></i></td>
            </tr>
            <tr>
                <td align='center'>{{strtoupper(env("HED_REGISTRAR"))}}<br>REGISTRAR</td>
                <td align='center'><small>DATE PRINTED</small><br>{{date('F d, Y')}}</td>
            </tr>
        </table>
    </footer>
        <header>
            <table class="table table-condensed hide" width="100%">
        <tr>
            <td valign='top' width='24%'>STUDENT NAME:</td>
            <td><b>{{mb_strtoupper($user->lastname)}}, {{mb_strtoupper($user->firstname)}} {{mb_strtoupper($user->middlename)}}</b></td>
        </tr>
            </table>
        </header>
    <table class="table table-condensed hide" width="100%">
        <tr>
            <td valign='top' width='24%'>STUDENT NUMBER:</td>
            <td>{{$user->idno}}</td>
            
            <td width='10%' valign='top' align='center' rowspan="16">
                <img src="{{public_path('images/PICTURES/'.$user->idno.'.jpg')}}" alt=' '>
            </td>
        </tr>
        <tr>
            <td valign='top'>COURSE:</td>
            <?php $array = explode(' ', $info->program_name) ;?>
            <td>
                @foreach ($array as $key=>$k)
                    @if($array[$key] == "Major")
                        <br>{{strtoupper($array[$key])}}
                    @elseif($array[$key] == "Specialization")
                        <br>{{strtoupper($array[$key])}}
                    @else
                        {{strtoupper($array[$key])}}
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <td>DATE OF ADMISSION:</td>
            <td>@if(!isset($info->date_of_admission)) N/A @else {{strtoupper(date('F d, Y',strtotime($info->date_of_admission)))}} @endif</td>
        </tr>
        <tr>
            <td valign='top'>DATE AND PLACE OF BIRTH:</td>
            <td>@if($info->birthdate == "" || $info->birthdate == NULL) @else {{strtoupper(date('F d, Y',strtotime($info->birthdate)))}}, @endif {{strtoupper($info->place_of_birth)}}</td>
        </tr>
        <tr>
            <td>CITIZENSHIP:</td>
            <td>{{strtoupper($info->nationality)}}</td>
        </tr>
        <tr>
            <td valign='top'>FATHER'S NAME:</td>
            <td>{{strtoupper($info->father)}}</td>
        </tr>
        <tr>
            <td valign='top'>MOTHER'S NAME:</td>
            <td>{{strtoupper($info->mother)}}</td>
        </tr>
        <tr>
            <td valign='top'>ADDRESS:</td>
            <td>{{strtoupper($info->street)}} {{strtoupper($info->barangay)}} {{strtoupper($info->municipality)}}</td>
        </tr>
        <tr>
            <td valign='top'>GRADE SCHOOL:</td>
            <td>{{strtoupper($info->gradeschool)}} {{strtoupper($info->gradeschool_address)}}</td>
        </tr>
        <tr>
            <td valign='top'>HIGH SCHOOL:</td>
            <td>{{strtoupper($info->highschool)}} {{strtoupper($info->highschool_address)}}</td>
        </tr>
        <tr>
            <td valign='top'>SENIOR HIGH SCHOOL:</td>
            <td>@if(!isset($info->senior_highschool)) N/A @else {{strtoupper($info->senior_highschool)}} {{strtoupper($info->senior_highschool_address)}} @endif</td>
        </tr>
        <tr>
            <td valign='top'>TERTIARY SCHOOL:</td>
            <td>@if(!isset($info->tertiary)) N/A @else {{strtoupper($info->tertiary)}} @endif</td>
        </tr>
        <tr>
            <td valign='top'>DEGREE EARNED:</td>
            @if(!isset($info->date_of_grad))
            <td>N/A</td> 
            @else 
            <?php $array = explode(' ', $info->program_name) ;?>
            <td>
                @foreach ($array as $key=>$k)
                    @if($array[$key] == "Major")
                        <br>{{strtoupper($array[$key])}}
                    @elseif($array[$key] == "Specialization")
                        <br>{{strtoupper($array[$key])}}
                    @else
                        {{strtoupper($array[$key])}}
                    @endif
                @endforeach
            </td>
            @endif
        </tr>
        <tr>
            <td valign='top'>AWARD:</td>
            <td>@if(!isset($info->award)) N/A @else {{strtoupper($info->award)}} @endif</td>
        </tr>
        <tr>
            <td>DATE OF GRADUATION:</td>
            <td>@if(!isset($info->date_of_grad)) N/A @else {{strtoupper(date('F d, Y', strtotime($info->date_of_grad)))}} @endif</td>
        </tr>
        <tr>
            <td>S.O. NUMBER:</td>
            <td>EXEMPTED</td>
        </tr>
        <tr>
            <td valign='top'>REMARKS:</td>
            <td>{{$info->remarks}}</td>
        </tr>
    </table>
    <hr>
    
    
    <table width='100%' cellpadding="2" style=" border-collapse: collapse">
<!--            <thead>
                <tr>
                    <td colspan="5">STUDENT NAME : <b>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</b></td>
                </tr>
            </thead>-->
        <thead>
            <tr>
                <th width='12%' align='center' style="border:2px solid black;"><b>COURSE CODE</b></th>
                <th width='60%' align='center' style="border:2px solid black;"><b>DESCRIPTIVE TITLE</b></th>
                <th width='10%' align='center' style="border:2px solid black;"><b>GRADE</b></th>
                <th width='10%' align='center' style="border:2px solid black;"><b>COMPLETION</b></th>
                <th width='10%' align='center' style="border:2px solid black;"><b>CREDITS</b></th>
            </tr>
        </thead>


        <?php $with_credit=0; ?>
        <?php $school_name = ""; ?>
        <?php $credit_sy = \App\CollegeCredit::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
        @if(count($credit_sy)>0)
        @foreach($credit_sy as $sy)
        <?php $credit_pr = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
        @foreach ($credit_pr as $pr)
        <?php $with_credit = 1; $credit_school = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->orderBy('school_name', 'asc')->get(['school_name']); ?>
        @foreach ($credit_school as $sr)
        @if($school_name == $sr->school_name)
        <?php $school_name = $school_name;?>
        <?php $with_credit = 0; ?>
        @else
        <?php $with_credit = 1; ?>
        <?php $school_name = $sr->school_name; ?>
        @endif
        <?php $grades = \App\CollegeCredit::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->where('school_name', $sr->school_name)->get(); ?>
        <tr>
            <td></td>
            <td align='center'>
                <b>
                    @if($with_credit == 1){{strtoupper($school_name)}}<br> @endif 
                        @if($pr->period == "1st Semester") FIRST SEMESTER 
                        @elseif($pr->period == "2nd Semester") SECOND SEMESTER 
                        @elseif($pr->period == "Summer") SUMMER  
                        @elseif($pr->period == "1st Quarter") FIRST QUARTER 
                        @elseif($pr->period == "2nd Quarter") SECOND QUARTER 
                        @elseif($pr->period == "3rd Quarter") THIRD QUARTER
                        @elseif($pr->period == "4th Quarter") FOURTH QUARTER
                        @elseif($pr->period == "1st Term") FIRST TERM 
                        @elseif($pr->period == "2nd Term") SECOND TERM 
                        @elseif($pr->period == "3rd Term") THIRD TERM
                    @endif, S.Y. {{$sy->school_year}}-{{$sy->school_year+1}}
                </b>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($grades as $grade)
        <?php
        $is_x=0;
        $display_final_grade = $grade->finals;
        $display_final_completion = $grade->completion;
        if(stripos($grade->course_code, "NSTP") !== FALSE){
            $gpa = $gpa;
            $count = $count;
            $credit = $credit;
        if($grade->finals == "FAILED" || $grade->finals == "FA" || $grade->finals == "UD"  || $grade->finals == "4.00"){
                $is_x = 1;
            }
            elseif($grade->finals == "INC"){
                if ($grade->completion == "PASSED") {
                $is_x = 0;
                } else {
                    if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                        $is_x = 1;
                    }
                }
            }
            else{
                $is_x = 0;
            }
        }else{
            if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                $gpa = $gpa;
                $count = $count;
                $credit = $credit;
                    if($grade->finals != "PASSED"){
                    $is_x = 1;
                    }
            } else if ($grade->finals == "INC") {
                if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                    $gpa = $gpa;
                    $credit = $credit;
                    $count = $count;
                    if($grade->completion != "PASSED"){
                    $is_x = 1;
                    }
                } else {

                    if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                        $grade->completion = "4.00";
                        $is_x = 1;
                    }

                    $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                    $count = $count + $grade->lec + $grade->lab;
                }
            } else {
                if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
                    $grade->finals = "4.00";
                        $is_x = 1;
                }
                $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
        }
        ?>
        <?php
        if (stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE) {
            $credit = $grade->lec + $grade->lab;
            $credit = "(" . $credit . ")";
                    if($is_x == 1){
                        $credit = "(x)";
                    }
        } else {
            $credit = $grade->lec + $grade->lab;
                    if($is_x == 1){
                        $credit = "x";
                    }
        }
        ?>
        <tr>
            <td valign='top'>{{strtoupper($grade->credit_code)}}</td>
            <td valign='top'>{{strtoupper($grade->credit_name)}}</td>
            <td valign='top' align='center'>{{$display_final_grade}}</td>
            <td valign='top' align='center'>{{$display_final_completion}}</td>
            <td valign='top' align='center'>{{$credit}}</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
        @endforeach
        <?php $with_credit = 1; ?>
        @endif



        <?php $pinnacle_sy = \App\CollegeGrades2018::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
        @if(count($pinnacle_sy)>0)
        @foreach ($pinnacle_sy as $pin_sy)
        <?php $pinnacle_period = \App\CollegeGrades2018::distinct()->where('idno', $idno)->where('school_year', $pin_sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
        @foreach($pinnacle_period as $pin_pr)
        <?php $pinnacle_grades = \App\CollegeGrades2018::where('idno', $idno)->where('school_year', $pin_sy->school_year)->where('period', $pin_pr->period)->where('course_code', 'not like', "%+%")->get(); ?>
        @if (count($pinnacle_grades)==1)
            @foreach($pinnacle_grades as $pin_grades)
                @if (stripos($pin_grades->course_code, "+") !== FALSE)
                @else
                <tr>
                    <td></td>
                    <td align='center'><b>@if($with_credit == 1) ASSUMPTION COLLEGE <br> <?php $with_credit=0;?> @endif @if($pin_pr->period == "1st Semester") FIRST SEMESTER @elseif($pin_pr->period == "2nd Semester") SECOND SEMESTER @elseif($pin_pr->period == "Summer") SUMMER @endif, S.Y. {{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            @endforeach
        @elseif (count($pinnacle_grades)==0)
        @else
        <tr>
            <td></td>
            <td align='center'><b>@if($with_credit == 1) ASSUMPTION COLLEGE <br> <?php $with_credit=0;?> @endif @if($pin_pr->period == "1st Semester") FIRST SEMESTER @elseif($pin_pr->period == "2nd Semester") SECOND SEMESTER @elseif($pin_pr->period == "Summer") SUMMER @endif, S.Y. {{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endif
        @foreach($pinnacle_grades as $pin_grades)
        @if (stripos($pin_grades->course_code, "+") !== FALSE)

        @else
        <?php
        $is_x=0;
        $display_final_grade = $pin_grades->finals;
        $display_final_completion = $pin_grades->completion;
        if(stripos($pin_grades->course_code, "NSTP") !== FALSE){
            $gpa = $gpa;
            $count = $count;
            $credit = $credit;
        if($pin_grades->finals == "FAILED" || $pin_grades->finals == "FA" || $pin_grades->finals == "UD"  || $pin_grades->finals == "4.00"){
                $is_x = 1;
            }else{
                $is_x = 0;
                if ($pin_grades->completion == "" || $pin_grades->completion == "AUDIT" || $pin_grades->completion == "NA" || $pin_grades->completion == "NG" || $pin_grades->completion == "W" || $pin_grades->completion == "PASSED") {
                $is_x = 0;
                } else {
                    if ($pin_grades->completion == "FA" || $pin_grades->completion == "UD" || $pin_grades->completion == "FAILED" || $pin_grades->completion == "4.00") {
                        $is_x = 1;
                    }
                }
            }
        }else{
            if ($pin_grades->finals == "" || $pin_grades->finals == "AUDIT" || $pin_grades->finals == "NA" || $pin_grades->finals == "NG" || $pin_grades->finals == "W" || $pin_grades->finals == "PASSED") {
                $gpa = $gpa;
                $count = $count;
                $credit = $credit;
                    if($pin_grades->finals != "PASSED"){
                    $is_x = 1;
                    }
            } else if ($pin_grades->finals == "INC") {
                if ($pin_grades->completion == "" || $pin_grades->completion == "AUDIT" || $pin_grades->completion == "NA" || $pin_grades->completion == "NG" || $pin_grades->completion == "W" || $pin_grades->completion == "PASSED") {
                    $gpa = $gpa;
                    $credit = $credit;
                    $count = $count;
                    if($pin_grades->completion != "PASSED"){
                    $is_x = 1;
                    }
                } else {

                    if ($pin_grades->completion == "FA" || $pin_grades->completion == "UD" || $pin_grades->completion == "FAILED" || $pin_grades->completion == "4.00") {
                        $pin_grades->completion = "4.00";
                        $is_x = 1;
                    }
                    $gpa = $gpa + ($pin_grades->completion * ($pin_grades->lec + $pin_grades->lab));
                    $count = $count + $pin_grades->lec + $pin_grades->lab;
                }
            } else {
                if ($pin_grades->finals == "FA" || $pin_grades->finals == "UD" || $pin_grades->finals == "FAILED" || $pin_grades->finals == "4.00") {
                    $pin_grades->finals = "4.00";
                    $is_x = 1;
                }
                $gpa = $gpa + ($pin_grades->finals * ($pin_grades->lec + $pin_grades->lab));
                $count = $count + $pin_grades->lec + $pin_grades->lab;
            }
        }
        ?>
        <tr>
            <td valign='top'>{{strtoupper($pin_grades->course_code)}}</td>
            <td valign='top'>{{strtoupper($pin_grades->course_name)}}</td>
            <td valign='top' align='center'>{{$display_final_grade}}</td>
            <td valign='top' align='center'>{{$display_final_completion}}</td>
            <td valign='top' align='center'>
                <?php
                if (stripos($pin_grades->course_code, "MME") !== FALSE || stripos($pin_grades->course_code, "THEO") !== FALSE || stripos($pin_grades->course_code, "NSTP") !== FALSE || stripos($pin_grades->course_code, "PE") !== FALSE) {
                    $credit = $pin_grades->lec + $pin_grades->lab;
                    $credit = "(" . $credit . ")";
                    if($is_x == 1){
                        $credit = "(x)";
                    }
                } else {
                    $credit = $pin_grades->lec + $pin_grades->lab;
                    if($is_x == 1){
                        $credit = "x";
                    }
                }
                ?>
                {{$credit}}</td>
        </tr>
        @endif
        @endforeach
        @endforeach
        @endforeach



        <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->where('finals_status', 3)->get(['school_year']); ?>
        @if(count($grades_sy)>0)
        @foreach($grades_sy as $sy)
        <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->where('finals_status', 3)->get(['period']); ?>
        @foreach ($grades_pr as $pr)
        <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->where('finals_status', 3)->get(); ?>
        <tr>
            <td></td>
            <td align='center'><b>@if($with_credit == 1) ASSUMPTION COLLEGE <br> <?php $with_credit=0;?> @endif @if($pr->period == "1st Semester") FIRST SEMESTER @elseif($pr->period == "2nd Semester") SECOND SEMESTER @elseif($pr->period == "Summer") SUMMER @endif, S.Y. {{$sy->school_year}}-{{$sy->school_year+1}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($grades as $grade)
        <?php
        $is_x=0;
        $display_final_grade = $grade->finals;
        $display_final_completion = $grade->completion;
        if(stripos($grade->course_code, "NSTP") !== FALSE){
            $gpa = $gpa;
            $count = $count;
            $credit = $credit;
        if($grade->finals == "FAILED" || $grade->finals == "FA" || $grade->finals == "UD"  || $grade->finals == "4.00"){
                $is_x = 1;
            }else{
                $is_x = 0;
                if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                $is_x = 0;
                } else {
                    if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                        $is_x = 1;
                    }
                }
            }
        }else{
            if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                $gpa = $gpa;
                $count = $count;
                $credit = $credit;
                    if($grade->finals != "PASSED"){
                    $is_x = 1;
                    }
            } else if ($grade->finals == "INC") {
                if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                    $gpa = $gpa;
                    $credit = $credit;
                    $count = $count;
                    if($grade->completion != "PASSED"){
                    $is_x = 1;
                    }
                } else {

                    if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                        $grade->completion = "4.00";
                        $is_x = 1;
                    }

                    $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                    $count = $count + $grade->lec + $grade->lab;
                }
            } else {
                if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
                    $grade->finals = "4.00";
                        $is_x = 1;
                }
                $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
        }
        ?>
        <?php
        if (stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE) {
            $credit = $grade->lec + $grade->lab;
            $credit = "(" . $credit . ")";
                    if($is_x == 1){
                        $credit = "(x)";
                    }
        } else {
            $credit = $grade->lec + $grade->lab;
                    if($is_x == 1){
                        $credit = "x";
                    }
        }
        ?>
        <tr>
            <td valign='top'>{{strtoupper($grade->course_code)}}</td>
            <td valign='top'>{{strtoupper($grade->course_name)}}</td>
            <td valign='top' align='center'>{{$display_final_grade}}</td>
            <td valign='top' align='center'>{{$display_final_completion}}</td>
            <td valign='top' align='center'>{{$credit}}</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
        @endif
        @else

        <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->where('finals_status', 3)->get(['school_year']); ?>
        @if(count($grades_sy)>0)
        @foreach($grades_sy as $sy)
        <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->where('finals_status', 3)->get(['period']); ?>
        @foreach ($grades_pr as $pr)
        <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->where('finals_status', 3)->get(); ?>
        <tr>
            <td></td>
            <td align='center'><b>@if($with_credit == 1) ASSUMPTION COLLEGE <br> <?php $with_credit=0;?> @endif @if($pr->period == "1st Semester") FIRST SEMESTER @elseif($pr->period == "2nd Semester") SECOND SEMESTER @elseif($pr->period == "Summer") SUMMER @endif, S.Y. {{$sy->school_year}}-{{$sy->school_year+1}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($grades as $grade)
        <?php
        $is_x=0;
        $display_final_grade = $grade->finals;
        $display_final_completion = $grade->completion;
        if(stripos($grade->course_code, "NSTP") !== FALSE){
            $gpa = $gpa;
            $count = $count;
            $credit = $credit;
        if($grade->finals == "FAILED" || $grade->finals == "FA" || $grade->finals == "UD"  || $grade->finals == "4.00"){
                $is_x = 1;
            }else{
                $is_x = 0;
                if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                $is_x = 0;
                } else {
                    if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                        $is_x = 1;
                    }
                }
            }
        }else{
            if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                $gpa = $gpa;
                $count = $count;
                $credit = $credit;
                    if($grade->finals != "PASSED"){
                    $is_x = 1;
                    }
            } else if ($grade->finals == "INC") {
                if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                    $gpa = $gpa;
                    $credit = $credit;
                    $count = $count;
                    if($grade->completion != "PASSED"){
                    $is_x = 1;
                    }
                } else {

                    if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                        $grade->completion = "4.00";
                        $is_x = 1;
                    }

                    $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                    $count = $count + $grade->lec + $grade->lab;
                }
            } else {
                if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
                    $grade->finals = "4.00";
                        $is_x = 1;
                }
                $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
        }
        ?>
        <?php
        if (stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE) {
            $credit = $grade->lec + $grade->lab;
            $credit = "(" . $credit . ")";
                    if($is_x == 1){
                        $credit = "(x)";
                    }
        } else {
            $credit = $grade->lec + $grade->lab;
                    if($is_x == 1){
                        $credit = "x";
                    }
        }
        ?>
        <tr>
            <td valign='top'>{{strtoupper($grade->course_code)}}</td>
            <td valign='top'>{{strtoupper($grade->course_name)}}</td>
            <td valign='top' align='center'>{{$display_final_grade}}</td>
            <td valign='top' align='center'>{{$display_final_completion}}</td>
            <td valign='top' align='center'>{{$credit}}</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
        @endif
        @endif
        <tr>
            <td colspan='5' align='center'><b>******************************NOTHING FOLLOWS******************************</b></td>
        </tr>
        <tr>
            <td></td>
            <td align='center'><b>GPA</b></td>
            @if($count == 0) <?php $count = 1; ?> @endif
            <td align='center'><b>{{number_format($gpa/$count,4)}}</b></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</body>
