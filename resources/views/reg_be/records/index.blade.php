@extends("layouts.appbedregistrar")
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
        Student Records
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student Records</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    <div class="box">
        <div class="box-body row">
            <div class="col-sm-3"><a href="{{url('/view_elementary_record',$idno)}}"><button class="btn btn-success">View Elementary Permanent Record</button></a></div>
            <div class="col-sm-3"><a href="{{url('/view_secondary_record',$idno)}}"><button class="btn btn-success">View Secondary Permanent Record</button></a></div>
        </div>
        <div class="box-body row">
            <div class="col-sm-3"><a href="{{url('/transcript_of_records',array($idno,'add'))}}"><button class="btn btn-info">Add Record</button></a></div>
            <!--<div class="col-sm-3 pull-right"><a class="pull-right" href="{{url('/transcript_of_records',array($idno,'fetch'))}}"><button class="btn btn-warning">Fetch/Update Record from Grading System</button></a></div>-->
        </div>
    </div> 
</div>    

@yield('displaygrades')

@endsection
@section('footerscript')    
@endsection
