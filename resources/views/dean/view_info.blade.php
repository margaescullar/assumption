<?php $regions = \App\CtrRegion::all(); ?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php
if(Auth::user()->accesslevel == env('SCHOLARSHIP_HED')){
$layout = "layouts.appscholarship_college";
}else if(Auth::user()->accesslevel == env('AA')){
$layout = "layouts.appaa";
} else {
$layout = "layouts.appdean_college";
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
        Student Information
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="{{url('academic', array('view_info', $idno))}}">View Information</a></li>
    </ol>
</section>
@endsection
@section('maincontent')


<div class="form-horizontal">
    {{ csrf_field() }}
    <div class="col-sm-12">
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif  
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow"><div class="col-md-3 pull-right"">
            <div class="form form-group">
                <label>User Status</label>
                <select class="form form-control" name="user_status" id="user_status" onchange="update_status(this.value)">
                    <option value="0" @if ($user->status == 0) selected=''@endif>0 - Not Active</option>
                    <option value="1" @if ($user->status == 1) selected=''@endif>1 - Active</option>
                    <option value="2" @if ($user->status == 2) selected=''@endif>2 - See Registrar</option>
                    <option value="3" @if ($user->status == 3) selected=''@endif>3 - See Guidance Office</option>
                    <option value="4" @if ($user->status == 4) selected=''@endif>4 - See Scholarship Office</option>
                    <option value="5" @if ($user->status == 5) selected=''@endif>5 - See OSA</option>
                    <option value="6" @if ($user->status == 6) selected=''@endif>6 - See Admissions Office</option>
                    <option value="7" @if ($user->status == 7) selected=''@endif>7 - See Pyschology Dept</option>
                    <option value="8" @if ($user->status == 8) selected=''@endif>8 - See Communication Dept</option>
                    <option value="9" @if ($user->status == 9) selected=''@endif>9 - See GenEd Dept</option>
                    <option value="10" @if ($user->status == 10) selected=''@endif>10 - See Educ Dept</option>
                    <option value="11" @if ($user->status == 11) selected=''@endif>11 - See MSBMW Dept</option>
                    <option value="12" @if ($user->status == 12) selected=''@endif>12 - See Associate Dean</option>
                </select>
            </div>
        </div>
                <div class="widget-user-image">
                    @if($file_exist==1)
                        <img src="/images/PICTURES/{{$user->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                    @else
                    <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                    @endif
                </div>
                <h3 class="widget-user-username">{{$user->firstname}} {{$user->middlename}} {{$user->lastname}}</h3>
                <h5 class="widget-user-desc">{{$user->idno}}</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>ID Number</label>
                                <input type="text" name="idno" class="form-control" value="{{old('idno',$user->idno)}}" readonly="">
                            </div>
                            <div class="col-sm-3">
                                <label>Lastname</label>
                                <input type="text" name="lastname" class="form-control" value="{{old('lastname',$user->lastname)}}">
                            </div>
                            <div class="col-sm-3">
                                <label>Firstname</label>
                                <input type="text" name="firstname" class="form-control" value="{{old('firstname',$user->firstname)}}">
                            </div>
                            <div class="col-sm-2">
                                <label>Middlename</label>
                                <input type="text" name="middlename" class="form-control" value="{{old('middlename',$user->middlename)}}">
                            </div>
                            <div class="col-sm-2">
                                <label>Extension Name</label>
                                <input type="text" name="extensionname" class="form-control" value="{{old('extensionname',$user->extensionname)}}">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Personal Information</a></li>
                <li><a href="#tab_2" data-toggle="tab">Family Background</a></li>
                <li><a href="#tab_3" data-toggle="tab">Educational Background</a></li>
                <!--<li><a href="#tab_4" data-toggle="tab">Admission Credentials</a></li>-->
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class='form-group'>
    <div class="col-sm-4">
            <label class="text-navy">Street Address</label>
            <input type="text" class="form-control upper" id="street" placeholder="Street" name="street" value="{{old('street',$info->street)}}">
    </div>
    <div class='col-sm-4'>
            <label class="text-navy">Region</label>
            <select class='form-control select2' id='region' name="region" onchange='getProvince(this.value)'>
                <option value="{{old('region',$info->region)}}">{{$info->region}}</option>
            </select>
    </div>
    <div class='col-sm-4'>
            <label class="text-navy">Province</label>
            <select class='form-control select2' name="province" id='province' onchange="getMunicipality(region.value)">
                <option value="{{old('province',$info->province)}}">{{$info->province}}</option>
            </select>
    </div>
</div>
<div class="form-group">
    <div class='col-sm-4'>
            <label class="text-navy">City/Municipality</label>
            <select class='form-control select2' name="municipality" id="municipality" onchange="getBarangay(this.value)">
                <option value="{{old('municipality',$info->municipality)}}">{{$info->municipality}}</option>
            </select>
    </div>
    <div class='col-sm-4'>
            <label class="text-navy">Barangay</label>
            <select class='form-control select2' name="barangay" id='barangay'>
                <option value="{{old('barangay',$info->barangay)}}">{{$info->barangay}}</option>
            </select>
    </div>
    <div class="col-sm-4">
            <label class="text-navy">Zip Code</label>
            <input class="form form-control" name='zip' placeholder='ZIP Code' value="{{old('zip',$info->zip)}}" type="text">
    </div>
</div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Contact Numbers</label>
                            <input class="form form-control" name='tel_no' placeholder='Telephone Number' value="{{old('tel_no',$info->tel_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='cell_no' placeholder='Cellphone Number' value="{{old('cel_no',$info->cell_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input class="form form-control" name='email' placeholder='Email Address*' readonly="" value="{{old('email',$user->email)}}" type="email">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label>Birthday</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-birthday-cake"></i>
                                </div>
                                <input class="form form-control" name='birthdate' value="{{old('birthdate',$info->birthdate)}}" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Birth Place</label>
                            <input class="form form-control" name='place_of_birth' value="{{old('place_of_birth',$info->place_of_birth)}}" placeholder='Place of Birth' type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Gender</label>
                            <select class="form form-control" name='gender' type="text">
                                <option value=''>Select Gender*</option>
                                <option value='Male' @if ($info->gender == 'Male') selected='' @endif>Male</option>
                                <option value='Female' @if ($info->gender == 'Female') selected='' @endif>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label>Civil Status</label>
                            <select class="form form-control" name='civil_status' placeholder='Telephone Number' type="text">
                                <option value="">Select Civil Status</option>
                                <option value="Single" @if ($info->civil_status == 'Single') selected='' @endif>Single</option>
                                <option value="Married" @if ($info->civil_status == 'Married') selected='' @endif>Married</option>
                                <option value="Divorced" @if ($info->civil_status == 'Divorced') selected='' @endif>Divorced</option>
                                <option value="Widowed" @if ($info->civil_status == 'Widowed') selected='' @endif>Widowed</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Nationality</label>
                            <input class="form form-control" name='nationality' placeholder='Nationality' value="{{old('nationality',$info->nationality)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Religion</label>
                            <input class="form form-control" name='religion' placeholder='Religion' value="{{old('religion',$info->religion)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Local/Foreigner</label>
                            <select class="form form-control" name='is_foreign' value="{{old('is_alien')}}" type="text">
                                <option value="">Select Local/Foreign</option>
                                <option value="0" @if ($user->is_foreign == 0) selected='' @endif>Local</option>
                                <option value="1" @if ($user->is_foreign == 1) selected='' @endif >Foreign</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <label>For Non-Filipinos and Filipinos Born Abroad</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Immig. Status/Visa Classification</label>
                            <input class="form form-control" name='immig_status' value="{{old('immig_status',$info->immig_status)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Authorized Stay</label>
                            <input class="form form-control" name='auth_stay' value="{{old('auth_stay',$info->auth_stay)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Passport No.</label>
                            <input class="form form-control" name='passport' value="{{old('passport',$info->passport)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Expiration Date</label>
                            <input class="form form-control" name='passport_exp_date' value="{{old('passport_exp_date',$info->passport_exp_date)}}" type="date">
                        </div>
                        <div class="col-sm-4">
                            <label>Place Issued</label>
                            <input class="form form-control" name='passport_place_issued' value="{{old('passport_place_issued',$info->passport_place_issued)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>ACR No.</label>
                            <input class="form form-control" name='acr_no' value="{{old('acr_no',$info->acr_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Date Issued</label>
                            <input class="form form-control" name='acr_date_issued' value="{{old('acr_date_issued',$info->acr_date_issued)}}" type="date">
                        </div>
                        <div class="col-sm-4">
                            <label>Place Issued</label>
                            <input class="form form-control" name='acr_place_issued' value="{{old('acr_place_issued',$info->acr_place_issued)}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <label>Father</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Father's Name</label>
                            <input class="form form-control" name='father' value="{{old('father',$info->father)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='f_is_living' value="1" type="radio" @if($info->f_is_living == 1) checked="" @elseif(is_null($info->f_is_living)) unchecked="" @endif>Living</label>
                                <label><input name='f_is_living' value="0" type="radio" @if($info->f_is_living == 0 && !is_null($info->f_is_living)) checked="" @else unchecked="" @endif>Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-4">
                            <label>Occupation</label>
                            <input class="form form-control" name='f_occupation' value="{{old('f_occupation',$info->f_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='f_phone' value="{{old('f_phone',$info->f_phone)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Business Address</label>
                            <input class="form form-control" name='f_address' value="{{old('f_address',$info->f_address)}}" type="text">
                        </div>
                    </div>
                    <label>Mother</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Mother's Name</label>
                            <input class="form form-control" name='mother' value="{{old('mother',$info->mother)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='m_is_living' value="1" type="radio" @if($info->m_is_living == 1) checked="" @elseif(is_null($info->m_is_living)) unchecked="" @endif>Living</label>
                                <label><input name='m_is_living' value="0" type="radio" @if($info->m_is_living == 0 && !is_null($info->m_is_living)) checked="" @else unchecked="" @endif>Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-4">
                            <label>Occupation</label>
                            <input class="form form-control" name='m_occupation' value="{{old('m_occupation',$info->m_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='m_phone' value="{{old('m_phone',$info->m_phone)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Business Address</label>
                            <input class="form form-control" name='m_address' value="{{old('m_address',$info->m_address)}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label>For Married:</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Spouse's Name</label>
                            <input class="form form-control" name='spouse' value="{{old('spouse',$info->spouse)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='s_is_living' value="1" type="radio" @if($info->s_is_living == 1) checked="" @elseif(is_null($info->s_is_living)) unchecked="" @endif>Living</label>
                                <label><input name='s_is_living' value="0" type="radio" @if($info->s_is_living == 0 && !is_null($info->s_is_living)) checked="" @else unchecked="" @endif>Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-4">
                            <label>Occupation</label>
                            <input class="form form-control" name='s_occupation' value="{{old('s_occupation',$info->s_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='s_phone' value="{{old('s_phone',$info->s_phone)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Business Address</label>
                            <input class="form form-control" name='s_address' value="{{old('s_address',$info->s_address)}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <label>Last School Attended</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='last_school_attended' value="{{old('last_school_attended',$info->last_school_attended)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='last_school_address' value="{{old('last_school_address',$info->last_school_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='last_school_year' value="{{old('last_school_year',$info->last_school_year)}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label>Primary School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='primary' value="{{old('primary',$info->primary)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='primary_address' value="{{old('primary_address',$info->primary_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='primary_year' value="{{old('primary_year',$info->primary_year)}}" type="text">
                        </div>
                    </div>
                    <label>Grade School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='gradeschool' value="{{old('gradeschool',$info->gradeschool)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='gradeschool_address' value="{{old('gradeschool_address',$info->gradeschool_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='gradeschool_year' value="{{old('gradeschool_year',$info->gradeschool_year)}}" type="text">
                        </div>
                    </div>
                    <label>High School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='highschool' value="{{old('highschool',$info->highschool)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='highschool_address' value="{{old('highschool_address',$info->highschool_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='highschool_year' value="{{old('highschool_year',$info->highschool_year)}}" type="text">
                        </div>
                    </div>
                    <label>Senior High School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='senior_highschool' value="{{old('senior_highschool',$info->senior_highschool)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='senior_highschool_address' value="{{old('senior_highschool_address',$info->senior_highschool_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='senior_highschool_year' value="{{old('senior_highschool_year',$info->senior_highschool_year)}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_4">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                    sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                    like Aldus PageMaker including versions of Lorem Ipsum.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter New Password : </h4>
              </div>
                <form method="post" action="{{url('/registrar_college', array('resetpassword'))}}">
                     {{csrf_field()}} 
                     <input type="hidden" name="idno" value="{{$user->idno}}">
              <div class="modal-body">
                  <input type="text" name="password" class="form form-control">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Reset Password">
              </div>
                </form>     
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
@endsection
@section('footerscript')
<script>
function update_status(status) {
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['status'] = status;
    $.ajax({
    type: "GET",
            url: "/ajax/academic/update_status",
            data: array,
            success: function (data) {
                alert("Status Updated!");
            }

    });
}</script>
@endsection
