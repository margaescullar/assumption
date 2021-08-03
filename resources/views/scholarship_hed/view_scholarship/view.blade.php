<?php
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
$student_info = \App\StudentInfo::where('idno', $idno)->first();
$list_of_scholars = \App\CtrDiscount::where('academic_type', 'College')->where('is_display', 1)->get();
?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php
if(Auth::user()->accesslevel == env('ACCTNG_HEAD')){
$layout = "layouts.appaccountinghead";
} else if(Auth::user()->accesslevel == env('ACCTNG_HEAD')){
$layout = "layouts.appaccountingstaff";
}else{
$layout = "layouts.appscholarship_college";
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
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> View Scholarship</a></li>
        <li class="active"><a href="{{ url ('/scholarship_college', array('view_scholar',$idno))}}"> {{$idno}}</a></li>
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
                        <img src="/images/{{$user->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                        @else
                        <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                        @endif
                    </div>
                    <h3 class="widget-user-username">{{$user->firstname}} {{$user->lastname}}</h3>
                    <h5 class="widget-user-desc">{{$user->idno}}</h5>
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        @if(count($status)>0)
                            @if($status->is_new == "0")
                            <li><a href="#">Previous Status <span class="pull-right">Old Student</span></a></li>
                            <li><a href="#">Previous Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                            <li><a href="#">Previous Level <span class="pull-right">{{$status->level}}</span></a></li>
                            <!--<li><a href="#">Previous Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                            @else
                            <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                            <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                            <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                            <!--<li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                            @endif
                        @else 
                        <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                        <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                        <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                        <!--<li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                        @endif
                    </ul>
                </div>
            </div>
        </div>
            @if (Auth::user()->accesslevel==env("SCHOLARSHIP_HED"))
                @if ($status->status == env('ASSESSED'))
                    <div class="col-sm-12"><a role="button" class="col-md-12 btn btn-danger" href="{{url('/accounting',array('manual_marking',$user->idno))}}" onclick="return confirm('This process cannot be undone. Do you wish to continue?')"><b>Mark student as Enrolled</b></a></div>
                @endif
            @endif
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    @if (count($scholar)>0)
                    <form method="post" action="{{url('scholarship_college','update_scholar')}}" onsubmit="return confirm('Do you really want to udpate the scholarship?');">
                        {{ csrf_field() }}
                        <input type='hidden' value='{{$idno}}' name='idno'>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label>Scholarship</label>
                                <select class="form form-control" name="discount_code">
                                    <option value="">None Scholar</option>
                                @foreach($list_of_scholars as $list)
                                    <option value="{{$list->discount_code}}" {{($scholar->discount_code=="$list->discount_code"?"selected":"")}}>{{$list->discount_description}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Tuition</label>
                                <input class="form form-control" name='tf' value="{{$scholar->tuition_fee}}" type="text">
                            </div>
                            <div class="col-sm-2">
                                <label>Miscellaneous</label>
                                <input class="form form-control" name='of' value="{{$scholar->other_fee}}" type="text">
                            </div>
                            <div class="col-sm-2">
                                <label>Other</label>
                                <input class="form form-control" name='mf' value="{{$scholar->other_fee}}" type="text">
                            </div>
                            <div class="col-sm-2">
                                <label>Depository</label>
                                <input class="form form-control" name='df' value="{{$scholar->depository_fee}}" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label>SRF</label>
                                <input class="form form-control" type='text' name='srf' value="{{$scholar->srf}}" type="text">
                            </div>
                            <div class="col-sm-2">
                                <label>Dorm</label>
                                <input class="form form-control" type='text' name='dorm' value="{{$scholar->dorm}}" type="text">
                            </div>
                            <div class="col-sm-2">
                                <label>Meal</label>
                                <input class="form form-control" type='text' name='meal' value="{{$scholar->meal}}" type="text">
                            </div>
                        </div>
                        
                        <div class='form-group row'>
                            <div class="col-sm-2">
                                <label>Non-Discounted</label>
                                <input class="form form-control" type='text' name='non_discounted' value="{{$scholar->non_discounted}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>List of Non Discounted to be discount</label>
                                <ol><strong>Old Student</strong>
                                <?php $nondiscountotherfees = \App\CtrCollegeNonDiscountedOtherFee::distinct()->where('period',$status->period)->get(['subsidiary']); ?>
                                @foreach($nondiscountotherfees as $list)
                                <li>{{$list->subsidiary}}</li>
                                @endforeach
                                </ol>
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <?php $nondiscountotherfees_new = \App\CtrCollegeNewNonDiscountOtherFee::distinct()->where('period',$status->period)->get(['subsidiary']); ?>
                                <ol><strong>New Student</strong>
                                @foreach($nondiscountotherfees_new as $list)
                                <li>{{$list->subsidiary}}</li>
                                @endforeach
                                </ol>
                            </div>
                        </div>
                        <div class='form-group row'>
                            <div class="col-sm-12">
                                <label>Remarks</label>
                                <input class="form form-control" type='text' name='remarks' value="{{$scholar->remarks}}" type="text">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label class="col-sm-12"><br></label>
                                <input type="submit" value="Update Scholarship" class="col-sm-12 btn btn-primary">
                            </div>
                            <div class="col-sm-8">
                                <label class="col-sm-12"><br></label>
                                <a href="{{url('/scholarship_college', array('print_scholarship', $idno))}}"><button class="col-sm-12 btn btn-success" type="button">Print Scholarship Certificate</button></a>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection