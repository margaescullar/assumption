<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php $student_info = \App\StudentInfo::where('idno', $user->idno)->first(); ?>
@extends('layouts.appdean_college')
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
        Student Record
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
        <div class="col-sm-12">
            <h3>Curriculum Record</h3>
            <?php $levels = \App\Curriculum::distinct()->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->orderBy('level')->get(['level']); ?>
            @foreach ($levels as $level)
            <?php $periods = \App\Curriculum::distinct()->where('level', $level->level)->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->orderBy('period')->get(['period']); ?>
            @foreach ($periods as $period)
            <?php $curricula = \App\Curriculum::where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->where('level', $level->level)->where('period', $period->period)->get(); ?>
            <table class="table table-striped" width="100%">
                <br><b>{{$level->level}} - {{$period->period}}</b>
                <thead>
                    <tr>
                        <th width="10%">Code</th>
                        <th width="50%">Description</th>
                        <th width="5%">Lec</th>
                        <th width="5%">Lab</th>
                        <th width="8%">Grade</th>
                        <th width="8%">Completion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($curricula as $curriculum)
                    <?php //$grades = \App\GradeCollege::where('idno', $idno)->where('course_code', $curriculum->course_code)->first(); ?>
<?php $old_grades = \App\CollegeGrades2018::where('idno', $idno)->where('course_code', $curriculum->course_code)->orderBy('id', 'desc')->first(); ?>
<?php $grades = \App\GradeCollege::where('idno', $idno)->where('course_code', $curriculum->course_code)->where('finals_status', 3)->orderBy('id', 'desc')->first(); ?>
                    <?php
                    $style="";
                    if(count($grades)>0){
                            if($grades->finals=="Failed" || $grades->finals=="4.00"){
                                $style="style='color:red; font-weight:bold'";
                            }else if ($grades->finals == "FA"){
                                $style="style='color:orange; font-weight:bold'";
                            }
                    }else{
                        if(count($old_grades)>0){
                        if($old_grades->finals=="Failed" ||$old_grades->finals=="4.00"){
                            $style="style='color:red; font-weight:bold'";
                        }else if($old_grades->finals=="FA"){
                            $style="style='color:orange; font-weight:bold'";
                        }
                        }else{
                            $checkcredit = \App\CollegeCredit::where('credit_code', $curriculum->course_code)->where('idno', $user->idno)->get();
                            if(count ($checkcredit) == 0){
                            $style="style='color:green; font-weight:bold'";
                            }else{
                            $style="style='color:brown; font-weight:bold'";
                            }
                        }
                    }
                    
                    ?>
                    
                    <tr>
                        <td {!!$style!!}>{{$curriculum->course_code}}</td>
                        <td {!!$style!!}>{{$curriculum->course_name}}</td>
                        <td {!!$style!!}>{{$curriculum->lec}}</td>
                        <td {!!$style!!}>{{$curriculum->lab}}</td>
                        <td {!!$style!!}>@if(count($grades)>0)
                                {{$grades->finals}}
                            @else
                                @if(count($old_grades)>0)
                                {{$old_grades->finals}}
                                @else
                                    <?php $checkcredit = \App\CollegeCredit::where('credit_code', $curriculum->course_code)->where('idno', $user->idno)->get(); ?>
                                    @if(count($checkcredit)==0)
                                    Not Yet Taken
                                    @else
                                    Credited
                                    @endif
                                @endif
                            @endif
                        </td>
                        <td {!!$style!!}>@if(count($old_grades)>0)
                                {{$old_grades->completion}}
                            @else
                                @if(count($grades)>0)
                                {{$grades->completion}}
                                @else
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            @if(Auth::user()->accesslevel == "DEAN")<a target='_blank' href='{{url('registrar_college', array('print_curriculum_record',$idno))}}'><button class='col-sm-12 btn btn-warning'>Print Curriculum Record</button></a>@endif
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection
