<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}

    if(Auth::user()->accesslevel == env('DEAN')){
    $layout = "layouts.appdean_college";
    } else if(Auth::user()->accesslevel == env('SCHOLARSHIP_HED')){
    $layout = "layouts.appdean_college";
    } else if(Auth::user()->accesslevel == env('AA')){
    $layout = "layouts.appaa";
    }
?>

@extends($layout)
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success"></span>
    </a>
</li>
<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"></span>
    </a>
</li>

<li class="dropdown tasks-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
        <span class="label label-danger"></span>
    </a>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Student's Transcript of Records
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="{{url('registrar_college', array('student_record', $idno))}}">View Record</a></li>
    </ol>
</section>
@endsection
@section('maincontent')

<section class="content">
    <div class="row">
       <div class="col-md-12">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-yellow">
                    <div class="widget-user-image">
                        @if($file_exist==1)
                        <img src="/images/PICTURES/{{$user->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                        @else
                        <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                        @endif
                    </div>
                    <h3 class="widget-user-username">{{$user->firstname}} {{$user->lastname}}</h3>
                    <h5 class="widget-user-desc">{{$user->idno}}</h5>
                    <h5 class="widget-user-desc">
                        <?php
                        switch ($status->status) {
                            case 0:
                                echo "Not Yet Advised or Assessed For This School Year";
                                break;
                            case env("ADVISING"):
                                echo "Already Advised but Not Assessed Yet";
                                break;
                            case env("ASSESSED"):
                                echo "Assessed";
                                break;
                            case env("ENROLLED"):
                                echo "Enrolled";
                                break;
                            case 4:
                                echo "Dropped";
                        }
                        ?>
                    </h5>
                    <h5 class="widget-user-desc">{{$status->level}} - {{$status->program_code}}</h5>
                </div>
            </div>
        </div>
        @if(Auth::user()->accesslevel != env("SCHOLARSHIP_HED"))
            <div class="col-sm-3">
                <a href="{{url('college', array('student_record', $user->idno))}}" class="btn btn-primary col-sm-12">Curriculum Record</a>
            </div>
            <div class="col-sm-3">
                <a href="{{url('academic', array('view_info', $user->idno))}}" class="btn btn-success col-sm-12">Student Information</a>
            </div>
            <div class="col-sm-3">
                <a href="{{url('college', array('view_transcript', $user->idno))}}" class="btn btn-success col-sm-12">Transcript of Records</a>
            </div>
        @if(Auth::user()->accesslevel == "DEAN")
            <div class="col-sm-3">
                <a target="_blank" href="{{url('college', array('true_copy_of_grades', $user->idno))}}" class="btn btn-success col-sm-12">Print Grade File</a>
            </div>
        @endif
        @endif
        <div class="col-sm-12">
            
            <?php $credit_sy = \App\CollegeCredit::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($credit_sy)>0)
            @foreach($credit_sy as $sy)
            <?php $credit_pr = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
            @foreach ($credit_pr as $pr)
<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>
            <?php $credit_school = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->orderBy('school_name', 'asc')->get(['school_name']); ?>
            @foreach ($credit_school as $sr)
            <?php $credit = \App\CollegeCredit::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->where('school_name', $sr->school_name)->get(); ?><h4>@if($sr->school_name != ""){{$sr->school_name}} : @endif{{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}</h4>
            <table class="table table-striped table-condensed" width="100%">
                <thead>
                    <tr>
                        <th width='7%'>Course Code</th>
                        <th width='40%'>Course Name</th>
                        <th width='10%'>Final Grade</th>
                        <th width='10%'>Completion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($credit as $grade)
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
        if ($grade->completion == "PASSED") {
        $is_x = 0;
        } else {
            if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
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
            $grade2 = $grade->completion;
            if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                $grade->completion = "4.00";
                $is_x = 1;
            }

            $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
            $count = $count + $grade->lec + $grade->lab;
            $grade->completion = $grade2;
        }
    } else {
            $grade2 = $grade->finals;
        if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
            $grade->finals = "4.00";
                $is_x = 1;
        }
        $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
        $count = $count + $grade->lec + $grade->lab;
            $grade->finals = $grade2;
    }
}
?>
                    <tr>
                        <td>{{$grade->credit_code}}</td>
                        <td>{{$grade->credit_name}}</td>
                        <td>{{$grade->finals}}</td>
                        <td>{{$grade->completion}}</td>
                        <!--<td><a href="{{url('registrar_college', array('edit','credit_grades', $grade->id))}}">Edit</td>-->
                    </tr>
                    @endforeach
                    @if($count > 0)
                    <tr><td colspan='2'>GPA</td><td align='center'><b>{{number_format($gpa/$count,4)}}</b></td><td></td></tr>
                    @else
                    <tr><td colspan='2'>GPA</td><td align='center'><b>{{number_format(0,4)}}</b></td><td></td></tr>
                    @endif
                </tbody>
            </table>
            @endforeach
            @endforeach
            @endforeach
            @endif
            
            <?php $pinnacle_sy = \App\CollegeGrades2018::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($pinnacle_sy)>0)
            @foreach ($pinnacle_sy as $pin_sy)
            <?php $pinnacle_period = \App\CollegeGrades2018::distinct()->where('idno', $idno)->where('school_year', $pin_sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
            @foreach($pinnacle_period as $pin_pr)
<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>
            <?php $pinnacle_grades = \App\CollegeGrades2018::where('idno', $idno)->where('school_year', $pin_sy->school_year)->where('period', $pin_pr->period)->get(); ?>
            <h4>{{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}, {{$pin_pr->period}}</h4>
            <table class="table table-striped table-condensed" width="100%">
                <thead>
                    <tr>
                        <th width='7%'>Course Code</th>
                        <th width='40%'>Course Name</th>
                        <th width='10%'>Final Grade</th>
                        <th width='10%'>Completion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinnacle_grades as $pin_grades)
                    @if (stripos($pin_grades->course_code, "+") !== FALSE)
<?php
$gpa = 0;
$count = 1;
?>
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
        if ($pin_grades->completion == "PASSED") {
        $is_x = 0;
        } else {
            if ($pin_grades->completion == "" || $pin_grades->completion == "AUDIT" || $pin_grades->completion == "NA" || $pin_grades->completion == "NG" || $pin_grades->completion == "W" || $pin_grades->completion == "FA" || $pin_grades->completion == "UD" || $pin_grades->completion == "FAILED" || $pin_grades->completion == "4.00") {
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
            $grade = $pin_grades->completion;
            if ($pin_grades->completion == "FA" || $pin_grades->completion == "UD" || $pin_grades->completion == "FAILED" || $pin_grades->completion == "4.00") {
                $pin_grades->completion = "4.00";
                $is_x = 1;
            }

            $gpa = $gpa + ($pin_grades->completion * ($pin_grades->lec + $pin_grades->lab));
            $count = $count + $pin_grades->lec + $pin_grades->lab;
            $pin_grades->completion = $grade;
        }
    } else {
        $grade = $pin_grades->finals;
        if ($pin_grades->finals == "FA" || $pin_grades->finals == "UD" || $pin_grades->finals == "FAILED" || $pin_grades->finals == "4.00") {
            $pin_grades->finals = "4.00";
                $is_x = 1;
        }
        $gpa = $gpa + ($pin_grades->finals * ($pin_grades->lec + $pin_grades->lab));
        $count = $count + $pin_grades->lec + $pin_grades->lab;
        $pin_grades->finals = $grade;
    }
}
?>
        @endif
                    <tr>
                        <td>{{$pin_grades->course_code}}</td>
                        <td>{{$pin_grades->course_name}}</td>
                        <td>{{$pin_grades->finals}}</td>
                        <td>{{$pin_grades->completion}}</td>
                        <!--<td><a href="{{url('registrar_college', array('edit','college_grades', $pin_grades->id))}}">Edit</td>-->
                    </tr>
                    @endforeach
                    @if($count > 0)
                    <tr><td colspan='2'>GPA</td><td align='center'><b>{{number_format($gpa/$count,4)}}</b></td><td></td></tr>
                    @else
                    <tr><td colspan='2'>GPA</td><td align='center'><b>{{number_format(0,4)}}</b></td><td></td></tr>
                    @endif
                </tbody>
            </table>
            @endforeach
            @endforeach
            <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($grades_sy)>0)
            @foreach($grades_sy as $sy)
            <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
            @foreach ($grades_pr as $pr)
<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>
            <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?><h4>{{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}</h4>
            <table class="table table-striped table-condensed" width="100%">
                <thead>
                    <tr>
                        <th width='7%'>Course Code</th>
                        <th width='40%'>Course Name</th>
                        <th width='10%'>Midterm Grade</th>
                        <th width='10%'>Final Grade</th>
                        <th width='10%'>Completion</th>
                    </tr>
                </thead>
                <tbody>
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
        if ($grade->completion == "PASSED") {
        $is_x = 0;
        } else {
            if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
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
        $grade2 = $grade->completion;
            if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                $grade->completion = "4.00";
                $is_x = 1;
            }

            $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
            $count = $count + $grade->lec + $grade->lab;
        $grade->completion = $grade2;
        }
    } else {
        $grade2 = $grade->finals;
        if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
            $grade->finals = "4.00";
                $is_x = 1;
        }
        $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
        $count = $count + $grade->lec + $grade->lab;
        $grade->finals = $grade2;
    }
}
?>
                    <tr>
                        <td>{{$grade->course_code}}</td>
                        <td>{{$grade->course_name}}</td>
                        <td>@if($grade->midterm_status == 3){{$grade->midterm}}@endif</td>
                        <td>@if($grade->finals_status == 3){{$grade->finals}}@endif</td>
                        <td>@if($grade->finals_status == 3){{$grade->completion}}@endif</td></td>
                        <!--<td><a href="{{url('registrar_college', array('edit','grades', $grade->id))}}">Edit</td>-->
                    </tr>
                    @endforeach
                    @if($count > 0)
                    <tr><td colspan='3'>GPA</td><td align='center'><b>{{number_format($gpa/$count,4)}}</b></td><td></td></tr>
                    @else
                    <tr><td colspan='3'>GPA</td><td align='center'><b>{{number_format(0,4)}}</b></td><td></td></tr>
                    @endif
                </tbody>
            </table>
            @endforeach
            @endforeach
            @endif
            @else

            <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($grades_sy)>0)
            @foreach($grades_sy as $sy)
            <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
            @foreach ($grades_pr as $pr)
<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>
            <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?><h4>{{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}</h4>
            <table class="table table-striped table-condensed" width="100%">
                <thead>
                    <tr>
                        <th width='7%'>Course Code</th>
                        <th width='40%'>Course Name</th>
                        <th width='10%'>Midterm Grade</th>
                        <th width='10%'>Final Grade</th>
                        <th width='10%'>Completion</th>
                    </tr>
                </thead>
                <tbody>
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
        if ($grade->completion == "PASSED") {
        $is_x = 0;
        } else {
            if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
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
        $grade2 = $grade->completion;
            if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                $grade->completion = "4.00";
                $is_x = 1;
            }

            $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
            $count = $count + $grade->lec + $grade->lab;
        $grade->completion = $grade2;
        }
    } else {
        $grade2 = $grade->finals;
        if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
            $grade->finals = "4.00";
                $is_x = 1;
        }
        $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
        $count = $count + $grade->lec + $grade->lab;
        $grade->finals = $grade2;
    }
}
?>
                    <tr>
                        <td>{{$grade->course_code}}</td>
                        <td>{{$grade->course_name}}</td>
                        <td>@if($grade->midterm_status == 3){{$grade->midterm}}@endif</td>
                        <td>@if($grade->finals_status == 3){{$grade->finals}}@endif</td>
                        <td>@if($grade->finals_status == 3){{$grade->completion}}@endif</td>
                        <!--<td><a href="{{url('registrar_college', array('edit','grades', $grade->id))}}">Edit</td>-->
                    </tr>
                    @endforeach
                    @if($count > 0)
                    <tr><td colspan='3'>GPA</td><td align='center'><b>{{number_format($gpa/$count,4)}}</b></td><td></td></tr>
                    @else
                    <tr><td colspan='3'>GPA</td><td align='center'><b>{{number_format(0,4)}}</b></td><td></td></tr>
                    @endif
                </tbody>
            </table>
            @endforeach
            @endforeach
            @endif
            @endif
           
        </div>     
    </div>
</section>

@endsection
@section('footerscript')
<script>
    function change_finals(grade, grade_id, idno, stat) {
    array = {};
    array['grade'] = grade;
    array['grade_id'] = grade_id;
    array['idno'] = idno;
    array['stat'] = stat;
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/grades/change_finals/" + idno,
            data: array,
            success: function () {
            }
    });
    }
    function change_completion(grade, grade_id, idno, stat) {
    array = {};
    array['grade'] = grade;
    array['grade_id'] = grade_id;
    array['idno'] = idno;
    array['stat'] = stat;
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/grades/change_completion/" + idno,
            data: array,
            success: function () {
            }
    });
    }
</script>
@endsection
