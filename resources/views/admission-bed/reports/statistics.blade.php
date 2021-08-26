<?php
if(Auth::user()->accesslevel == env('ADMISSION_BED')){
$layout = "layouts.appadmission-bed";
} else {
$layout = "layouts.appadmission-shs";
}
?>

@extends($layout)
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                <li><!-- start message -->
                    <a href="#">
                        <div class="pull-left">
                            <!-- User Image -->

                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
                    </a>
                </li>
                <!-- end message -->
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Statistics Report
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Statistics Report</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
</div> 
<div class="col-md-12">  
    <div class="box">
        <div class="box-header">
            <div class="box-title">Result</div>
        </div>
        <div class="box-body">
            <table class='table'>
                <tr>
                    <td align='center'>SY</td>
                    <!--<td align='center'>Applicants</td>-->
                    <td align='center'>Pre-Registered</td>
                    <td align='center'>For Approval</td>
                    <td align='center'>Regret</td>
                    <td align='center'>Approved</td>
                </tr>
                @foreach($stats->groupBy('admission_sy') as $sy=>$stat)
                <?php
                $total_applicants = $stat->count('idno');
                $non_paid = $stat->where('is_complete',0)->count('is_complete');
                $paid = $stat->where('is_complete',1)->count('is_complete');
                $waived = $stat->where('is_complete',2)->count('is_complete');
                $for_approval = count(\App\Status::where('statuses.status', env('FOR_APPROVAL'))->where('statuses.academic_type', "!=","College")->where('users.admission_sy',$sy)->join('users', 'users.idno','=','statuses.idno')->get());
                $approved     = count(\App\Status::where('statuses.status', "<=",env('ENROLLED'))->where('statuses.academic_type', "!=","College")->where('users.admission_sy',$sy)->join('users', 'users.idno','=','statuses.idno')->get());
                $regret_final     = count(\App\Status::where('statuses.status', env('REGRET_FINAL'))->where('statuses.academic_type', "!=","College")->where('users.admission_sy',$sy)->join('users', 'users.idno','=','statuses.idno')->get());
                $regret_retreive  = count(\App\Status::where('statuses.status', env('REGRET_RETREIVE'))->where('statuses.academic_type', "!=","College")->where('users.admission_sy',$sy)->join('users', 'users.idno','=','statuses.idno')->get());
                ?>
                <tr>
                    <td align='center'>{{$sy}}</td>
                    <td align='center'>{{$total_applicants}}</td>
                    <!--<td align='center'>{{$non_paid+$paid+$waived}}</td>-->
                    <td align='center'>{{$for_approval}}</td>
                    <td align='center'>{{$regret_final}}</td>
                    <td align='center'><strong>{{$approved}}</strong></td>
                <tr>
                @endforeach
            </table>
        </div>
    </div> 
</div>
@endsection
@section('footerscript') 
@endsection
