<?php
if(Auth::user()->accesslevel == env('DEAN')){
$layout = "layouts.appdean_college";
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
        List of Audit Student
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Reports</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('reports','list_audit_student'))}}"></i> List Audit Student</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
        @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <form class="form form-horizontal" method="post" action='{{url('/registrar_college/reports/print_audit_student')}}'>
            {{ csrf_field() }}        
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">  
                    <h3 class="box-title">Search Audit Student</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class='form-horizontal'>
                        <div class='form-group'>
                           
                            <div class='col-sm-2'>
                                <label>School Year</label>
                                <select class="form form-control select2" name="school_year" id='school_year'>
                                    <option value="">Select School Year</option>
                                    <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
                                </select>    
                            </div>  
                            <div class='col-sm-2'>
                                <label>Period</label>
                                <select class="form form-control select2" name="period" id='period'>
                                    <option value="">Select Period</option>
                                    <option value="1st Semester">1st Semester</option>
                                    <option value="2nd Semester">2nd Semester</option>
                                    <option value="Summer">Summer</option>
                                </select>    
                            </div>                                                         
                       
                       
                            <div class='col-sm-7'>
                                <label>&nbsp;</label>
                                <button formtarget='_blank' type='submit' class='col-sm-12 btn btn-success'><span>PRINT REPORT</span></button>
                            </div>
                         </div>                        
<!--                        <div class='form-group'>
                            <div class="col-sm-5" id='getstudent'>
                                
                            </div>    
                        </div>-->
                    </div>    
                </div>    
            </div>
        </div>  
            </form>
        </div>    
<section>
       
</section>    
@endsection
@section('footerscript')


@endsection
