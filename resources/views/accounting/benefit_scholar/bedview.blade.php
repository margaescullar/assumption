<?php
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
$student_info = \App\StudentInfo::where('idno', $idno)->first();
$list_of_scholars = \App\CtrDiscount::where('academic_type', '!=','College')->where('discount_type', 2)->where('is_display', 1)->get();
?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
} else if (Auth::user()->accesslevel == env("SCHOLARSHIP_BED")) {
    $layout = "layouts.appscholarship_bed";
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
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-md-6">
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
                        <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                        <li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>
                        <li><a href="#">Strand <span class="pull-right">{{$status->strand}}</span></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    @if (count($scholar)>0)
                    <form method="post" action="{{url('accounting','bed_update_scholar')}}" onsubmit="return confirm('Do you really want to udpate the scholarship?');">
                        {{ csrf_field() }}
                        <input type='hidden' value='{{$idno}}' name='idno'>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Scholarship</label>
                                <select class="form form-control" name="discount_code">
                                    <option value="">None Scholar</option>
                                @foreach($list_of_scholars as $list)
                                    <option value="{{$list->discount_code}}" {{($scholar->discount_code=="$list->discount_code"?"selected":"")}}>{{$list->discount_description}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>Tuition</label>
                                <input class="form form-control" name='tf' value="{{$scholar->tuition_fee}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>Miscellaneous</label>
                                <input class="form form-control" name='of' value="{{$scholar->other_fee}}" type="text">
                            </div>
<!--                            <div class="col-sm-2">
                                <label>Other</label>-->
                                <input class="form form-control" type='hidden' name='mf' value="{{$scholar->other_fee}}" type="text">
<!--                            </div>
                            <div class="col-sm-2">
                                <label>Depository</label>-->
                                <input class="form form-control" type='hidden' name='df' value="{{$scholar->depository_fee}}" type="text">
                            <!--</div>-->
                            <div class="col-sm-3">
                                <label>Student Development Fee</label>
                                <input class="form form-control" type='text' name='non_discounted' value="{{$scholar->non_discounted}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>SRF</label>
                                <input class="form form-control" type='text' name='srf' value="{{$scholar->srf}}" type="text">
                            </div>
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
<!--                            <div class="col-sm-8">
                                <label class="col-sm-12"><br></label>
                                <a href="{{url('/scholarship_college', array('print_scholarship', $idno))}}"><button class="col-sm-12 btn btn-success" type="button">Print Scholarship Certificate</button></a>
                            </div>-->
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