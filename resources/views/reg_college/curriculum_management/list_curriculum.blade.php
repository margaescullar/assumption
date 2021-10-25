<?php
$program = \App\Curriculum::distinct()->where('program_code', $program_code)->get(['program_name'])->first();
$levels = \App\Curriculum::distinct()->where('program_code', $program_code)->where('curriculum_year', $curriculum_year)->orderBy('level', 'asc')->orderBy('period', 'asc')->get(['level', 'period']);
?>
<?php
if(Auth::user()->accesslevel == env('DEAN')){
$layout = "layouts.appdean_college";
}else if(Auth::user()->accesslevel == env('AA')){
$layout = "layouts.appaa";
} else {
$layout = "layouts.appreg_college";
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
        View Curriculum
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','curriculum'))}}"> Curriculum</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{$program->program_name}} - {{$curriculum_year}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php $totalUnits = 0; ?>
                    <div class='table-responsive'>
                    @foreach ($levels as $level)
                    <table class="table table-condensed">
                        <thead>
                        <th>{{$level->period}}</th>
                        <th>{{$level->level}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <th class='col-sm-2'>Course Code</th>
                                <th class='col-sm-5'>Course Description</th>
                                <th class='col-sm-1'>LEC</th>
                                <th class='col-sm-1'>LAB</th>
                                <th class='col-sm-1'>UNITS</th>
                                <th class='col-sm-1'>SRF</th>
                                <th class='col-sm-2'>LAB</th>
                            </tr>
                            <?php
                            $curriculums = \App\Curriculum::where('program_code', $program_code)->where('curriculum_year', $curriculum_year)->where('level', $level->level)->where('period', $level->period)->get();
                            ?>
                            <?php
                            $totalLec = 0;
                            $totalLab = 0;
                            ?>
                            @foreach ($curriculums as $curriculum)
                            <tr>
                                <td>{{$curriculum->course_code}}</td>
                                <td>{{$curriculum->course_name}}</td>
                                <td>@if ($curriculum->lec==0) @else {{$curriculum->lec}} @endif <?php $totalLec = $curriculum->lec + $totalLec; ?></td>
                                <td>@if ($curriculum->lab==0) @else {{$curriculum->lab}} @endif <?php $totalLab = $curriculum->lab + $totalLab; ?></td>
                                <td>{!!$curriculum->lec + $curriculum->lab!!}</td>
                                <td>{{number_format($curriculum->srf,2)}}</td>
                                <td>{{number_format($curriculum->lab_fee,2)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <th><div align='right'>Total</div> </th>
                                <th><?php echo $totalLec; ?></th>
                                <th><?php echo $totalLab; ?></th>
                                <th><?php $totalUnits = $totalUnits + $totalLec + $totalLab; ?> {!! $totalLec + $totalLab !!}</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                    </div>
                    <table class="table table-condensed">
                        <tr>
                            <th>Total Units: {!! $totalUnits !!}</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footerscript')

@endsection