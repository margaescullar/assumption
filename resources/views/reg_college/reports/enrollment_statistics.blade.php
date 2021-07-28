    <?php
    if(Auth::user()->accesslevel == env('REG_COLLEGE')){
    $layout = "layouts.appreg_college";
    } else if(Auth::user()->accesslevel == env('ADMISSION_HED')){
    $layout = "layouts.appadmission-hed";    
    } else if (Auth::user()->accesslevel == env('DEAN')){
    $layout = "layouts.appdean_college";
    } else if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    } else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
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
            <div class='form-horizontal'>
        <div class='form-group'>
            <div class='col-sm-2'>
                <label>School Year</label>
                <select class="form form-control select2" name="school_year" id='school_year'>
                    <option value="">Select School Year</option>
                    <option value="2017" @if ($school_year == 2017) selected = "" @endif>2017-2018</option>
                <option value="2018" @if ($school_year == 2018) selected = "" @endif>2018-2019</option>
                <option value="2019" @if ($school_year == 2019) selected = "" @endif>2019-2020</option>
                <option value="2020" @if ($school_year == 2020) selected = "" @endif>2020-2021</option>
                <option value="2021" @if ($school_year == 2021) selected = "" @endif>2021-2022</option>
                </select>
            </div>
            <div class='col-sm-2'>
                <label>Period</label>
                <select class="form form-control select2" name="period" id='period'>
                    <option value="">Select Period</option>
                    <option value='1st Semester' @if ($period == "1st Semester") selected = "" @endif>1st Semester</option>
                    <option value='2nd Semester' @if ($period == "2nd Semester") selected = "" @endif>2nd Semester</option>
                    <option value='Summer' @if ($period == "Summer") selected = "" @endif>Summer</option>
                </select>    
            </div>   
            <div class='col-sm-4'>
                <label>&nbsp;</label>
                <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Change School Year/Period</span></button>
            </div>
        </div>    
    </div>
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
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
                            $totaladvised1 = 0;
                            $totaladvised2 = 0;
                            $totaladvised3 = 0;
                            $totaladvised4 = 0;
                            ?>
                            @foreach ($academic_programs as $academic_program)
                            <tr>
                                <td><a href="{{url('registrar_college',array('reports','update_enrollment_statistics',$academic_program->program_code,$school_year,$period))}}"> @if($academic_program->is_display==1)<span class="fa fa-eye"></span> @else <span class="fa fa-eye-slash"></span> @endif </a></td>
                                <td>{{$academic_program->program_name}}</td>
                                <td><?php $count1 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count1)}}</td>
                                <td><?php $count2 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count2)}}</td>
                                <td><?php $count3 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count3)}}</td>
                                <td><?php $count4 = \App\CollegeLevel::where('program_code', $academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count4)}}</td>
                                <td><?php $totalcount = count($count1) + count($count2) + count($count3) + count($count4); ?>{{$totalcount}}</td>
                            </tr>
                            <?php
                            $totalcount1 = $totalcount1 + count($count1);
                            $totalcount2 = $totalcount2 + count($count2);
                            $totalcount3 = $totalcount3 + count($count3);
                            $totalcount4 = $totalcount4 + count($count4);
                            ?>
                            <?php $unofficial1 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            <?php $unofficial2 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            <?php $unofficial3 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            <?php $unofficial4 = \App\Status::where('program_code', $academic_program->program_code)->where('status', 2)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            
                            <?php
                            $totalunofficial1 = $totalunofficial1 + count($unofficial1);
                            $totalunofficial2 = $totalunofficial2 + count($unofficial2);
                            $totalunofficial3 = $totalunofficial3 + count($unofficial3);
                            $totalunofficial4 = $totalunofficial4 + count($unofficial4);
                            ?>
                            <?php $advised1 = \App\Status::where('program_code', $academic_program->program_code)->where('is_advised', 1)->where('status',0)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            <?php $advised2 = \App\Status::where('program_code', $academic_program->program_code)->where('is_advised', 1)->where('status',0)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            <?php $advised3 = \App\Status::where('program_code', $academic_program->program_code)->where('is_advised', 1)->where('status',0)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            <?php $advised4 = \App\Status::where('program_code', $academic_program->program_code)->where('is_advised', 1)->where('status',0)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>
                            
                            <?php
                            $totaladvised1 = $totaladvised1 + count($advised1);
                            $totaladvised2 = $totaladvised2 + count($advised2);
                            $totaladvised3 = $totaladvised3 + count($advised3);
                            $totaladvised4 = $totaladvised4 + count($advised4);
                            ?>
                            @endforeach
                            @foreach ($hide_academic_programs as $hide_academic_program)
                            <tr>
                                <td><a href="{{url('registrar_college',array('reports','update_enrollment_statistics',$hide_academic_program->program_code,$school_year,$period))}}"> @if($hide_academic_program->is_display==1)<span class="fa fa-eye"></span> @else <span class="fa fa-eye-slash"></span> @endif </a></td>
                                <td>{{$hide_academic_program->program_name}}</td>
                                <td><?php $count1 = \App\CollegeLevel::where('program_code', $hide_academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count1)}}</td>
                                <td><?php $count2 = \App\CollegeLevel::where('program_code', $hide_academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count2)}}</td>
                                <td><?php $count3 = \App\CollegeLevel::where('program_code', $hide_academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count3)}}</td>
                                <td><?php $count4 = \App\CollegeLevel::where('program_code', $hide_academic_program->program_code)->whereRaw('(is_audit = 0 or is_audit > 1)')->where('status', 3)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($count4)}}</td>
                                <td><?php $totalcount = count($count1) + count($count2) + count($count3) + count($count4); ?>{{$totalcount}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td><div align="right">TOTAL AUDIT(Credited)</div></td>
                                <td><?php $auds1 = \App\CollegeLevel::where('is_audit',">",1)->where('status', 3)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($auds1)}}</td>
                                <td><?php $auds2 = \App\CollegeLevel::where('is_audit',">",1)->where('status', 3)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($auds2)}}</td>
                                <td><?php $auds3 = \App\CollegeLevel::where('is_audit',">",1)->where('status', 3)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($auds3)}}</td>
                                <td><?php $auds4 = \App\CollegeLevel::where('is_audit',">",1)->where('status', 3)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($auds4)}}</td>
                                <td><?php $totalauds = count($auds1) + count($auds2) + count($auds3) + count($auds4); ?>{{$totalauds}}</td>
                            </tr>
<!--                            <tr>
                                <td><div align="right">TOTAL AUDIT(Not Credited)</div></td>
                                <td><?php $aud1 = \App\CollegeLevel::where('is_audit',"=",1)->where('status', 3)->where('level', "1st Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud1)}}</td>
                                <td><?php $aud2 = \App\CollegeLevel::where('is_audit',"=",1)->where('status', 3)->where('level', "2nd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud2)}}</td>
                                <td><?php $aud3 = \App\CollegeLevel::where('is_audit',"=",1)->where('status', 3)->where('level', "3rd Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud3)}}</td>
                                <td><?php $aud4 = \App\CollegeLevel::where('is_audit',"=",1)->where('status', 3)->where('level', "4th Year")->where('school_year', $school_year)->where('period', $period)->get(); ?>{{count($aud4)}}</td>
                                <td><?php $totalaud = count($aud1) + count($aud2) + count($aud3) + count($aud4); ?>{{$totalaud}}</td>
                            </tr>-->
                            <tr>
                                <td></td>
                                <td><div align="right">TOTAL ENROLLED</div></td>
                                <td>{{$totalcount1}}</td>
                                <td>{{$totalcount2}}</td>
                                <td>{{$totalcount3}}</td>
                                <td>{{$totalcount4}}</td>
                                <td><?php $totalenrolled = $totalcount1 + $totalcount2 + $totalcount3 + $totalcount4; ?>{{$totalenrolled}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><div align="right">TOTAL ASSESSED STUDENTS</div></td>
                                <td>{{$totalunofficial1}}</td>
                                <td>{{$totalunofficial2}}</td>
                                <td>{{$totalunofficial3}}</td>
                                <td>{{$totalunofficial4}}</td>
                                <td><?php $totalunofficial = $totalunofficial1 + $totalunofficial2 + $totalunofficial3 + $totalunofficial4; ?>{{$totalunofficial}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><div align="right">TOTAL ADVISED STUDENTS</div></td>
                                <td>{{$totaladvised1}}</td>
                                <td>{{$totaladvised2}}</td>
                                <td>{{$totaladvised3}}</td>
                                <td>{{$totaladvised4}}</td>
                                <td><?php $totaladvised = $totaladvised1 + $totaladvised2 + $totaladvised3 + $totaladvised4; ?>{{$totaladvised}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><div align="right">GRAND TOTAL</div></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <!--<td>{{$totalenrolled + $totalunofficial + $totalaud + $totaladvised}}</td>-->
                                <td>{{$totalenrolled + $totalunofficial + $totaladvised}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED') || Auth::user()->accesslevel == env('DEAN'))
            
            <a target='_blank' id='print_enroll' href='{{url('registrar_college', array('reports', 'enrollment_statistics', 'print_enrollment_statistics', $school_year, $period))}}'><button class="btn btn-success col-sm-12">PRINT ENROLLMENT REPORT</button></a>
            <!--<a target='_blank' href='{{url('registrar_college', array('reports', 'enrollment_statistics', 'print_enrollment_official', $school_year, $period))}}'><button class="btn btn-success col-sm-12">PRINT OFFICIAL REPORT</button></a>-->
            @endif
        </div>
    </div>
</section>
@endsection
@section('footerscript')
<script>
    $(document).ready(function(){
      $("#view-button").on('click',function(e){
        document.location="{{url('/registrar_college',array('reports'))}}" + "/enrollment_statistics/" + $("#school_year").val() + "/" + $("#period").val();
      });
    });
</script>
@endsection