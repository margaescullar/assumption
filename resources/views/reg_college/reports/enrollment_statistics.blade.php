@extends('layouts.appreg_college')
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
        Enrollment Statistics
        <small>{{$school_year}}-{{$school_year+1}} - {{$period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Reports</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('reports','enrollment_statistics'))}}"> Enrollment Statistics</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="70%">Program</th>
                                <th>1st</th>
                                <th>2nd</th>
                                <th>3rd</th>
                                <th>4th</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalcount1 = 0;
                            $totalcount2 = 0;
                            $totalcount3 = 0;
                            $totalcount4 = 0;
                            $totalunofficial1 = 0;
                            $totalunofficial2 = 0;
                            $totalunofficial3 = 0;
                            $totalunofficial4 = 0;
                            ?>
                            @foreach ($academic_programs as $academic_program)
                            <tr>
                                <td>{{$academic_program->program_name}}</td>
                                <td><?php $count1 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count1)}}</td>
                                <td><?php $count2 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count2)}}</td>
                                <td><?php $count3 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count3)}}</td>
                                <td><?php $count4 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->where('is_audit', 0)->where('status', 3)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count4)}}</td>
                                <td><?php $totalcount = count($count1) + count($count2) + count($count3) + count($count4); ?>{{$totalcount}}</td>
                            </tr>
                            <?php
                            $totalcount1 = $totalcount1 + count($count1);
                            $totalcount2 = $totalcount2 + count($count2);
                            $totalcount3 = $totalcount3 + count($count3);
                            $totalcount4 = $totalcount4 + count($count4);
                            ?>
                            <?php $unofficial1 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "1st Year")->get(); ?>
                            <?php $unofficial2 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "2nd Year")->get(); ?>
                            <?php $unofficial3 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "3rd Year")->get(); ?>
                            <?php $unofficial4 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "4th Year")->get(); ?>

                            <?php
                            $totalunofficial1 = $totalunofficial1 + count($unofficial1);
                            $totalunofficial2 = $totalunofficial2 + count($unofficial2);
                            $totalunofficial3 = $totalunofficial3 + count($unofficial3);
                            $totalunofficial4 = $totalunofficial4 + count($unofficial4);
                            ?>
                            @endforeach
                            <tr>
                                <td>AUDIT</td>
                                <td><?php $aud1 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud1)}}</td>
                                <td><?php $aud2 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud2)}}</td>
                                <td><?php $aud3 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud3)}}</td>
                                <td><?php $aud4 = \App\CollegeLevel::where('is_audit', 1)->where('status', 3)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud4)}}</td>
                                <td><?php $totalaud = count($aud1) + count($aud2) + count($aud3) + count($aud4); ?>{{$totalaud}}</td>
                            </tr>
                            <tr>
                                <td><div align="right">TOTAL ENROLLED</div></td>
                                <td>{{$totalcount1}}</td>
                                <td>{{$totalcount2}}</td>
                                <td>{{$totalcount3}}</td>
                                <td>{{$totalcount4}}</td>
                                <td><?php $totalenrolled = $totalcount1 + $totalcount2 + $totalcount3 + $totalcount4 + $totalaud; ?>{{$totalenrolled}}</td>
                            </tr>
                            <tr>
                                <td><div align="right">TOTAL UNOFFICIALLY ENROLLED</div></td>
                                <td>{{$totalunofficial1}}</td>
                                <td>{{$totalunofficial2}}</td>
                                <td>{{$totalunofficial3}}</td>
                                <td>{{$totalunofficial4}}</td>
                                <td><?php $totalunofficial = $totalunofficial1 + $totalunofficial2 + $totalunofficial3 + $totalunofficial4; ?>{{$totalunofficial}}</td>
                            </tr>
                            <tr>
                                <td><div align="right">GRAND TOTAL</div></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{$totalenrolled + $totalunofficial}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <a target='_blank' href='{{url('registrar_college', array('reports', 'enrollment_statistics', 'print_enrollment_statistics', $school_year, $period))}}'><button class="btn btn-success col-sm-12">PRINT</button></a>
        </div>
    </div>
</section>
@endsection
@section('footerscript')
@endsection