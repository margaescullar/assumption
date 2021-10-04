<?php $regions = \App\CtrRegion::all(); ?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php
if(Auth::user()->accesslevel == env('ADMISSION_HED')){
$layout = "layouts.appadmission-hed";
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
        Student Information
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="{{url('registrar_college', array('view_info', $idno))}}">View Information</a></li>
    </ol>
</section>
@endsection
@section('maincontent')


<form action="{{url('registrar_college', array('save_info', $idno))}}" method="post" class="form-horizontal">
    {{ csrf_field() }}
    
@if(Auth::user()->accesslevel == env('REG_COLLEGE') or Auth::user()->accesslevel == env('ADMISSION_HED'))
    <div class="col-md-12">
        <div class="box">
        <div class="box-body">
        <?php $status = \App\Status::where('idno', $idno)->first(); ?>
            
@if(Auth::user()->accesslevel == env('REG_COLLEGE'))
        <div class="col-md-2 pull-left">
            <div class="form form-group">
                @if($status->status == 3)
                <label><input type="text" class="form form-control" id='date_today' placeholder="YYYY-MM-DD" value="{{date('Y-m-d')}}" name="date_today"></label>
                <!--<a onclick='withdraw(date_today.value)' href='{{url('/bedregistrar',array('withdraw_enrolled_student','w',$user->idno))}}'>-->
                <a onclick='withdraw(date_today.value, "w","{{$user->idno}}")'>
                    <button type="button" class="btn btn-danger">Tag as Withdrawn</button>
                </a>
                @elseif($status->status == 4)
                <label><br><br></label>
                <!--<a href='{{url('/bedregistrar',array('withdraw_enrolled_student','e',$user->idno))}}'>-->
                <a onclick='withdraw("NULL", "e","{{$user->idno}}")'>
                    <button type="button" class="btn btn-success">Tag as Enrolled</button>
                </a>
                @endif
            </div>
        </div>

        <div class="col-md-3 pull-right">
            <div class="form form-group">
                <label>User Status</label>
                <select class="form form-control" name="user_status" id="user_status">
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
@endif
        <div class="col-md-2 pull-right">
            <div class="form form-group">
                <label><br><br></label>
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">
                    Reset Password
                </button>
            </div>
        </div>
        </div>
        </div>
    </div>
@endif
    <div class="col-sm-12">
        @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif  
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->

            <div class="widget-user-header bg-yellow">
                @if(Auth::user()->accesslevel == env('REG_COLLEGE'))
                <div class="col-sm-2 pull-right">
                    <a href="{{url('/upload_user_image', $user->idno)}}"><button type="button" class="btn btn-primary pull-right">Upload User Image</button></a>
                </div>
                <div class="col-sm-2 pull-right">
                    <a href="{{url('/print_envelope', $user->idno)}}"><button type="button" class="btn btn-success pull-right"><span class="fa fa-envelope"></span> Print Envelope</button></a>
                </div>
                @endif
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
                <li><a href="#tab_4" data-toggle="tab">Activities</a></li>
                <li><a href="#tab_5" data-toggle="tab">College Application</a></li>
                <li><a href="#tab_6" data-toggle="tab">Emergency Information</a></li>
                <!--<li><a href="#tab_7" data-toggle="tab">Admission Credentials</a></li>-->
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <label style="background-color: gray">PERSONAL INFORMATION</label>
                    <div class='form-group'>
    <div class="col-sm-4">
            <label class="text-navy">Street Address</label>
            <input type="text" class="form-control upper" id="street" placeholder="Street" name="street" value="{{old('street',$info->street)}}">
    </div>
    <div class='col-sm-4'>
            <label class="text-navy">Region</label>
            <select class='form-control select2' id='region' name="region" onchange='getProvince(this.value)'>
                <option value="{{old('region',$info->region)}}">{{$info->region}}</option>
                @foreach($regions as $region)
                <option value='{{$region->region}}'>{{$region->region}}</option>
                @endforeach
            </select>
    </div>
    <div class='col-sm-4'>
            <label class="text-navy">Province</label>
            <select class='form-control select2' name="province" id='province' onchange="getMunicipality(region.value)">
                <option value="{{old('province',$info->province)}}">{{$info->province}}</option>
                <option value=''>Please Select..</option>
            </select>
    </div>
</div>
<div class="form-group">
    <div class='col-sm-4'>
            <label class="text-navy">City/Municipality</label>
            <select class='form-control select2' name="municipality" id="municipality" onchange="getBarangay(this.value)">
                <option value="{{old('municipality',$info->municipality)}}">{{$info->municipality}}</option>
                <option value=''>Please Select..</option>
            </select>
    </div>
    <div class='col-sm-4'>
            <label class="text-navy">Barangay</label>
            <select class='form-control select2' name="barangay" id='barangay'>
                <option value="{{old('barangay',$info->barangay)}}">{{$info->barangay}}</option>
                <option value=''>Please Select..</option>
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
                            <input class="form form-control" name='tel_no' id="tel_no" placeholder='Telephone: (02)____-____' value="{{old('tel_no',$info->tel_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='cell_no' id="cell_no" placeholder='Cellphone: (0917)___-____' value="{{old('cell_no',$info->cell_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input class="form form-control" name='email' placeholder='Email Address*' value="{{old('email',$user->email)}}" type="email">
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
                                <option value="">Select Filipino/Foreign</option>
                                <option value="0" @if ($user->is_foreign == 0) selected='' @endif>Filipino</option>
                                <option value="1" @if ($user->is_foreign == 1) selected='' @endif >Foreign</option>
                                <option value="2" @if ($user->is_foreign == 2) selected='' @endif >Dual Citizen</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <label style="background-color: gray">For Non-Filipinos and Filipinos Born Abroad</label>
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
                    <label style="background-color: gray">PARENT INFORMATION</label>
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
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='f_personal_address' value="{{old('f_personal_address',$info->f_personal_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Email</label>
                            <input class="form form-control" name='f_email' value="{{old('f_email',$info->f_email)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Tel. No./Mobile No.</label>
                            <input class="form form-control" name='f_personal_phone' value="{{old('f_personal_phone',$info->f_personal_phone)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Citizenship</label>
                            <input class="form form-control" name='f_citizenship' value="{{old('f_citizenship',$info->f_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Highest Education Attainment</label>
                            <input class="form form-control" name='f_attainment' value="{{old('f_attainment',$info->f_attainment)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='f_company_name' value="{{old('f_company_name',$info->f_company_name)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                <label>Occupation</label>
                            <input class="form form-control" name='f_occupation' value="{{old('f_occupation',$info->f_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='f_phone' value="{{old('f_phone',$info->f_phone)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Business Address</label>
                            <input class="form form-control" name='f_address' value="{{old('f_address',$info->f_address)}}" type="text">
                        </div>
                    </div>
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
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='m_personal_address' value="{{old('m_personal_address',$info->m_personal_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Email</label>
                            <input class="form form-control" name='m_email' value="{{old('m_email',$info->m_email)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Tel. No./Mobile No.</label>
                            <input class="form form-control" name='m_personal_phone' value="{{old('m_personal_phone',$info->m_personal_phone)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Citizenship</label>
                            <input class="form form-control" name='m_citizenship' value="{{old('m_citizenship',$info->m_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Highest Education Attainment</label>
                            <input class="form form-control" name='m_attainment' value="{{old('m_attainment',$info->m_attainment)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='m_company_name' value="{{old('m_company_name',$info->m_company_name)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                <label>Occupation</label>
                            <input class="form form-control" name='m_occupation' value="{{old('m_occupation',$info->m_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='m_phone' value="{{old('m_phone',$info->m_phone)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Business Address</label>
                            <input class="form form-control" name='m_address' value="{{old('m_address',$info->m_address)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Guardian's Name</label>
                            <input class="form form-control" name='guardian' value="{{old('guardian',$info->guardian)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='g_is_living' value="1" type="radio" @if($info->g_is_living == 1) checked="" @elseif(is_null($info->g_is_living)) unchecked="" @endif>Living</label>
                                <label><input name='g_is_living' value="0" type="radio" @if($info->g_is_living == 0 && !is_null($info->g_is_living)) checked="" @else unchecked="" @endif>Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='g_personal_address' value="{{old('g_personal_address',$info->g_personal_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Email</label>
                            <input class="form form-control" name='g_email' value="{{old('g_email',$info->g_email)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Tel. No./Mobile No.</label>
                            <input class="form form-control" name='g_personal_phone' value="{{old('g_personal_phone',$info->g_personal_phone)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Citizenship</label>
                            <input class="form form-control" name='g_citizenship' value="{{old('g_citizenship',$info->g_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Highest Education Attainment</label>
                            <input class="form form-control" name='g_attainment' value="{{old('g_attainment',$info->g_attainment)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='g_company_name' value="{{old('g_company_name',$info->g_company_name)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                <label>Occupation</label>
                            <input class="form form-control" name='g_occupation' value="{{old('g_occupation',$info->g_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='g_phone' value="{{old('g_phone',$info->g_phone)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Business Address</label>
                            <input class="form form-control" name='g_address' value="{{old('g_address',$info->g_address)}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label style="background-color: gray">FOR MARRIED APPLICANTS:</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Spouse's Name</label>
                            <input class="form form-control" name='spouse' value="{{old('spouse',$info->spouse)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Birthday</label>
                            <input class="form form-control" name='s_dob' value="{{old('s_dob',$info->s_dob)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='s_is_living' value="1" type="radio" @if($info->s_is_living == 1) checked="" @elseif(is_null($info->s_is_living)) unchecked="" @endif>Living</label>
                                <label><input name='s_is_living' value="0" type="radio" @if($info->s_is_living == 0 && !is_null($info->s_is_living)) checked="" @else unchecked="" @endif>Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='s_personal_address' value="{{old('s_personal_address',$info->s_personal_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Email</label>
                            <input class="form form-control" name='s_email' value="{{old('s_email',$info->s_email)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Tel. No./Mobile No.</label>
                            <input class="form form-control" name='s_personal_phone' value="{{old('s_personal_phone',$info->s_personal_phone)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Citizenship</label>
                            <input class="form form-control" name='s_citizenship' value="{{old('s_citizenship',$info->s_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Highest Education Attainment</label>
                            <input class="form form-control" name='s_attainment' value="{{old('s_attainment',$info->s_attainment)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='s_company_name' value="{{old('s_company_name',$info->s_company_name)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                <label>Occupation</label>
                            <input class="form form-control" name='s_occupation' value="{{old('s_occupation',$info->s_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='s_phone' value="{{old('s_phone',$info->s_phone)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Business Address</label>
                            <input class="form form-control" name='s_address' value="{{old('s_address',$info->s_address)}}" type="text">
                        </div>
                    </div>


                    <label>Do you have relatives who graduated or study in Assumption?</label>
                    <?php $a = 0; ?>
                    <div  id="dynamic_field_alumni">
                        <!--div class="top-row"-->
                        <?php $alumni = \App\StudentInfoAlmuni::where('idno', $user->idno)->get(); ?>
                        @if(count($alumni)>0)
                        <div class="form form-group">
                            <div class="col-md-2">
                                <label>Name</label>
                            </div>
                            <div class="col-md-2">
                                <label>Relationship</label>
                            </div>
                            <div class="col-md-2">
                                <label>Year Graduated/Study</label>
                            </div>
                            <div class="col-md-3">
                                <label>Department(GS,HS,College)</label>
                            </div>
                            <div class="col-md-2">
                                <label>Location</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($alumni as $alum)
                        <div id='row_alumni{{$a}}' class="form form-group">
                            <div class="col-md-2">
                                <input class="form form-control alumni" type="text" name="alumni_name[{{$a}}]" id='alumni_name{{$a}}' value='{{$alum->name}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control alumni" type="text" name="alumni_relationship[{{$a}}]" id='almuni_relationship{{$a}}' value='{{$alum->relationship}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control alumni" type="text" name="alumni_year_graduated[{{$a}}]" id='alumni_year_graduated{{$a}}' value='{{$alum->year_graduated}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control alumni" type="text" name="alumni_department[{{$a}}]" id='almuni_department{{$a}}' value='{{$alum->department}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control alumni" type="text" name="alumni_location[{{$a}}]" id='alumni_location{{$a}}' value='{{$alum->location}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($a == 0)
                                <button type="button" name="add_alumni" id="add_alumni" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_alumni" id="{{$a}}" class="btn btn-danger btn_remove btn_remove_alumni">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $a = $a + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-2">
                                <label>Name</label>
                                <input class="form form-control alumni" type="text" name="sibling[]" id='sibling1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Relationship</label>
                                <input class="form form-control alumni_relationship" type="text" name="alumni_relationship[]" id='alumni_relationship1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Year Graduated/Study</label>
                                <input class="form form-control alumni_year_graduated" type="text" name="alumni_year_graduated[]" id='alumni_year_graduated1'/>
                            </div>
                            <div class="col-md-3">
                                <label>Department(GS,HS,College)</label>
                                <input class="form form-control alumni_department" type="text" name="alumni_year_graduated[]" id='alumni_year_graduated1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Location</label>
                                <input class="form form-control alumni_location" type="text" name="alumni_location[]" id='alumni_location1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="alumni" id="add_alumni" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>


                    <label>Siblings(Brothers and Sisters)</label>
                    <?php $b = 0; ?>
                    <div  id="dynamic_field_siblings">
                        <!--div class="top-row"-->
                        <?php $siblings = \App\StudentInfoSibling::where('idno', $user->idno)->get(); ?>
                        @if(count($siblings)>0)
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Name</label>
                            </div>
                            <div class="col-md-2">
                                <label>Age</label>
                            </div>
                            <div class="col-md-2">
                                <label>Level/Position</label>
                            </div>
                            <div class="col-md-3">
                                <label>School/Office</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($siblings as $sibling)
                        <div id='row_siblings{{$b}}' class="form form-group">
                            <div class="col-md-4">
                                <input class="form form-control siblings" type="text" name="siblings_name[{{$b}}]" id='siblings_name{{$b}}' value='{{$sibling->name}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control siblings" type="text" name="siblings_age[{{$b}}]" id='siblings_age{{$b}}' value='{{$sibling->age}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control siblings" type="text" name="siblings_level[{{$b}}]" id='siblings_level{{$b}}' value='{{$sibling->level}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control siblings" type="text" name="siblings_school[{{$b}}]" id='siblings_school{{$b}}' value='{{$sibling->school}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($b == 0)
                                <button type="button" name="add_siblings" id="add_siblings" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_siblings" id="{{$b}}" class="btn btn-danger btn_remove btn_remove_siblings">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $b = $b + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Name</label>
                                <input class="form form-control siblings" type="text" name="siblings_name[]" id='siblings_name1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Age</label>
                                <input class="form form-control siblings_age" type="text" name="siblings_age[]" id='siblings_age1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Level/Position</label>
                                <input class="form form-control siblings_level" type="text" name="siblings_level[]" id='siblings_level1'/>
                            </div>
                            <div class="col-md-3">
                                <label>School/Office</label>
                                <input class="form form-control siblings_school" type="text" name="siblings_school[]" id='siblings_school1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="siblings" id="add_siblings" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <?php $pregnant = \App\StudentInfoPregnant::where('idno', $idno)->first() ?>
                    <div class='form-group'>
                        <div class="col-sm-6">
                            <label>Have you ever been pregnant?</label>
                            <select class="form form-control" name='ever_pregnant' value="{{old('ever_pregnant')}}" type="text">
                                <option value="3" @if ($pregnant->ever_pregnant == 3) selected='' @endif></option>
                                <option value="0" @if ($pregnant->ever_pregnant == 0) selected='' @endif>No</option>
                                <option value="1" @if ($pregnant->ever_pregnant == 1) selected='' @endif >Yes</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Are you pregnant now?</label>
                            <select class="form form-control" name='pregnant_now' value="{{old('pregnant_now')}}" type="text">
                                <option value="3" @if ($pregnant->pregnant_now == 3) selected='' @endif></option>
                                <option value="0" @if ($pregnant->pregnant_now == 0) selected='' @endif>No</option>
                                <option value="1" @if ($pregnant->pregnant_now == 1) selected='' @endif >Yes</option>
                            </select>
                        </div>
                    </div>


                    <label>Do you have children?</label>
                    <?php $c = 0; ?>
                    <div  id="dynamic_field_children">
                        <!--div class="top-row"-->
                        <?php $children = \App\StudentInfoChildren::where('idno', $user->idno)->get(); ?>
                        @if(count($children)>0)
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Name</label>
                            </div>
                            <div class="col-md-2">
                                <label>Age</label>
                            </div>
                            <div class="col-md-2">
                                <label>Level</label>
                            </div>
                            <div class="col-md-3">
                                <label>School</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($children as $child)
                        <div id='row_children{{$c}}' class="form form-group">
                            <div class="col-md-4">
                                <input class="form form-control children" type="text" name="children_name[{{$c}}]" id='children_name{{$c}}' value='{{$child->name}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control children" type="text" name="children_age[{{$c}}]" id='children_age{{$c}}' value='{{$child->age}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control children" type="text" name="children_level[{{$c}}]" id='children_level{{$c}}' value='{{$child->level}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control children" type="text" name="children_school[{{$c}}]" id='children_school{{$c}}' value='{{$child->school}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($c == 0)
                                <button type="button" name="add_children" id="add_children" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_children" id="{{$c}}" class="btn btn-danger btn_remove btn_remove_children">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $c = $c + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Name</label>
                                <input class="form form-control children" type="text" name="children_name[]" id='children_name1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Age</label>
                                <input class="form form-control children_age" type="text" name="children_age[]" id='children_age1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Level</label>
                                <input class="form form-control children_level" type="text" name="children_level[]" id='children_level1'/>
                            </div>
                            <div class="col-md-3">
                                <label>School</label>
                                <input class="form form-control children_school" type="text" name="children_school[]" id='children_school1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="children" id="add_children" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <label style="background-color: gray">EDUCATIONAL BACKGROUND</label><br>
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
                    <div class='form-group'>
                        <div class="col-sm-5">
                            <label>Principal/College Dean</label>
                            <input class="form form-control" name='dean' value="{{old('dean',$info->dean)}}" type="text">
                        </div>
                        <div class="col-sm-5">
                            <label>Guidance Counselor</label>
                            <input class="form form-control" name='guidance_counselor' value="{{old('guidance_counselor',$info->guidance_counselor)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>School Tel. Number</label>
                            <input class="form form-control" name='last_school_number' value="{{old('last_school_number',$info->last_school_number)}}" type="text">
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
                    <label>Junior High School</label>
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
                    <hr>
                    <label style="background-color: gray">FOR TRANSFEREES:</label> &nbsp;If you were at any one time enrolled in a certificate or degree course, please fill up this segment.<br>
                    <label>Have you ever applied at the Assumption College in the past?</label>
                    <div class='form-group'>
                        <div class="col-sm-6">
                            <label>If yes, Year and Course applied</label>
                            <input class="form form-control" name='applied_year_course' value="{{old('applied_year_course',$info->applied_year_course)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Reason for leaving</label>
                            <input class="form form-control" name='applied_leaving' value="{{old('senior_highschool_address',$info->applied_leaving)}}" type="text">
                        </div>
                    </div>

                    <label>Colleges/Universities attended</label>
                    <?php $d = 0; ?>
                    <div  id="dynamic_field_attendeds">
                        <!--div class="top-row"-->
                        <?php $attendeds = \App\StudentInfoAttendedCollege::where('idno', $user->idno)->get(); ?>
                        @if(count($attendeds)>0)
                        <div class="form form-group">
                            <div class="col-md-3">
                                <label>Name of School</label>
                            </div>
                            <div class="col-md-3">
                                <label>Address</label>
                            </div>
                            <div class="col-md-3">
                                <label>Course</label>
                            </div>
                            <div class="col-md-2">
                                <label>Year Attended</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($attendeds as $attended)
                        <div id='row_attendeds{{$d}}' class="form form-group">
                            <div class="col-md-3">
                                <input class="form form-control attendeds" type="text" name="attendeds_college[{{$d}}]" id='attendeds_college{{$d}}' value='{{$attended->college}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control attendeds" type="text" name="attendeds_address[{{$d}}]" id='attendeds_address{{$d}}' value='{{$attended->address}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control attendeds" type="text" name="attendeds_course[{{$d}}]" id='attendeds_course{{$d}}' value='{{$attended->course}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control attendeds" type="text" name="attendeds_school_year[{{$d}}]" id='attendeds_school_year{{$d}}' value='{{$attended->school_year}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($d == 0)
                                <button type="button" name="add_attendeds" id="add_attendeds" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_attendeds" id="{{$d}}" class="btn btn-danger btn_remove btn_remove_attendeds">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $d = $d + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-3">
                                <label>Name of School</label>
                                <input class="form form-control attendeds_college" type="text" name="attendeds_college[]" id='attendeds_college1'/>
                            </div>
                            <div class="col-md-3">
                                <label>Address</label>
                                <input class="form form-control attendeds_address" type="text" name="attendeds_address[]" id='attendeds_address1'/>
                            </div>
                            <div class="col-md-3">
                                <label>Event</label>
                                <input class="form form-control attendeds_course" type="text" name="attendeds_course[]" id='attendeds_course1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Year Attended</label>
                                <input class="form form-control attendeds_school_year" type="text" name="attendeds_school_year[]" id='attendeds_school_year1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="attendeds" id="add_attendeds" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr>
                    <label style="background-color: gray">ACADEMIC HONORS AND DISTINCTIONS</label><br>
                    <label>Please list all academic honors, distinctions, awards earned.</label>
                    <?php $e = 0; ?>
                    <div  id="dynamic_field_honors">
                        <!--div class="top-row"-->
                        <?php $honors = \App\StudentInfoHonor::where('idno', $user->idno)->get(); ?>
                        @if(count($honors)>0)
                        <div class="form form-group">
                            <div class="col-md-5">
                                <label>Honor/Award</label>
                            </div>
                            <div class="col-md-2">
                                <label>Year Level</label>
                            </div>
                            <div class="col-md-4">
                                <label>Event</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($honors as $honor)
                        <div id='row_honors{{$e}}' class="form form-group">
                            <div class="col-md-5">
                                <input class="form form-control honors" type="text" name="honors_honor[{{$e}}]" id='honors_honor{{$e}}' value='{{$honor->honor}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control honors" type="text" name="honors_level[{{$e}}]" id='honors_level{{$e}}' value='{{$honor->level}}'/>
                            </div>
                            <div class="col-md-4">
                                <input class="form form-control honors" type="text" name="honors_event[{{$e}}]" id='honors_event{{$e}}' value='{{$honor->event}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($e == 0)
                                <button type="button" name="add_honors" id="add_honors" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_honors" id="{{$e}}" class="btn btn-danger btn_remove btn_remove_honors">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $e = $e + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-5">
                                <label>Honor/Award</label>
                                <input class="form form-control honors_honor" type="text" name="honors_honor[]" id='honors_honor1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Year Level</label>
                                <input class="form form-control honors_level" type="text" name="honors_level[]" id='honors_level1'/>
                            </div>
                            <div class="col-md-4">
                                <label>Event</label>
                                <input class="form form-control honors_event" type="text" name="honors_event[]" id='honors_event1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="honors" id="add_honors" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-12">
                            <label>Are you a candidate for Class Valedictorian, Salutatorian, or Honorable Mentions? If Yes, please specify.</label>
                            <input class="form form-control" name='are_you_candidate' value="{{old('are_you_candidate',$info->are_you_candidate)}}" type="text">
                        </div>
                    </div>

                    <hr>
                    <label style="background-color: gray">DISCONTINUANCE OF STUDY</label><br>
                    <label>Did you ever have to stop studying?</label>
                    <?php $f = 0; ?>
                    <div  id="dynamic_field_discontinuances">
                        <!--div class="top-row"-->
                        <?php $discontinuances = \App\StudentInfoDiscontinuance::where('idno', $user->idno)->get(); ?>
                        @if(count($discontinuances)>0)
                        <div class="form form-group">
                            <div class="col-md-2">
                                <label>School Year</label>
                            </div>
                            <div class="col-md-9">
                                <label>Reason</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($discontinuances as $discontinuance)
                        <div id='row_discontinuances{{$f}}' class="form form-group">
                            <div class="col-md-2">
                                <input class="form form-control discontinuances" type="text" name="discontinuances_school_year[{{$f}}]" id='discontinuances_school_year{{$f}}' value='{{$discontinuance->school_year}}'/>
                            </div>
                            <div class="col-md-9">
                                <input class="form form-control discontinuances" type="text" name="discontinuances_reason[{{$f}}]" id='discontinuances_reason{{$f}}' value='{{$discontinuance->reason}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($f == 0)
                                <button type="button" name="add_discontinuances" id="add_discontinuances" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_discontinuances" id="{{$f}}" class="btn btn-danger btn_remove btn_remove_discontinuances">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $f = $f + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-2">
                                <label>School Year</label>
                                <input class="form form-control discontinuances_school_year" type="text" name="discontinuances_school_year[]" id='discontinuances_school_year1'/>
                            </div>
                            <div class="col-md-9">
                                <label>Reason</label>
                                <input class="form form-control discontinuances_reason" type="text" name="discontinuances_reason[]" id='discontinuances_reason1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="discontinuances" id="add_discontinuances" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr>
                    <label style="background-color: gray">ACADEMIC PROBLEMS</label><br>
                    <label>Did you fail in any subject(s) in hight school/college?</label>
                    <?php $g = 0; ?>
                    <div  id="dynamic_field_fails">
                        <!--div class="top-row"-->
                        <?php $fails = \App\StudentInfoFailSubject::where('idno', $user->idno)->get(); ?>
                        @if(count($fails)>0)
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Subject</label>
                            </div>
                            <div class="col-md-2">
                                <label>Grading Period</label>
                            </div>
                            <div class="col-md-2">
                                <label>Level</label>
                            </div>
                            <div class="col-md-3">
                                <label>Reason</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($fails as $fail)
                        <div id='row_fails{{$g}}' class="form form-group">
                            <div class="col-md-4">
                                <input class="form form-control fails" type="text" name="fails_subject[{{$g}}]" id='fails_subject{{$g}}' value='{{$fail->subject}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control fails" type="text" name="fails_period[{{$g}}]" id='fails_period{{$g}}' value='{{$fail->period}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control fails" type="text" name="fails_level[{{$g}}]" id='fails_level{{$g}}' value='{{$fail->level}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control fails" type="text" name="fails_reason[{{$g}}]" id='fails_reason{{$g}}' value='{{$fail->reason}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($g == 0)
                                <button type="button" name="add_fails" id="add_fails" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_fails" id="{{$g}}" class="btn btn-danger btn_remove btn_remove_fails">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $g = $g + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Subject</label>
                                <input class="form form-control fails_school_year" type="text" name="fails_subject[]" id='fails_subject1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Grading Period</label>
                                <input class="form form-control fails_period" type="text" name="fails_period[]" id='fails_period1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Level</label>
                                <input class="form form-control fails_level" type="text" name="fails_level[]" id='fails_level1'/>
                            </div>
                            <div class="col-md-3">
                                <label>Reason</label>
                                <input class="form form-control fails_reason" type="text" name="fails_reason[]" id='fails_reason1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="fails" id="add_fails" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>


                    <label>Did you ever have to repeat a year in high school?</label>
                    <?php $h = 0; ?>
                    <div  id="dynamic_field_repeats">
                        <!--div class="top-row"-->
                        <?php $repeats = \App\StudentInfoRepeat::where('idno', $user->idno)->get(); ?>
                        @if(count($repeats)>0)
                        <div class="form form-group">
                            <div class="col-md-2">
                                <label>Level</label>
                            </div>
                            <div class="col-md-4">
                                <label>Subject</label>
                            </div>
                            <div class="col-md-5">
                                <label>Reason</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($repeats as $repeat)
                        <div id='row_repeats{{$h}}' class="form form-group">
                            <div class="col-md-2">
                                <input class="form form-control repeats" type="text" name="repeats_level[{{$h}}]" id='repeats_level{{$h}}' value='{{$repeat->level}}'/>
                            </div>
                            <div class="col-md-4">
                                <input class="form form-control repeats" type="text" name="repeats_subject[{{$h}}]" id='repeats_subject{{$h}}' value='{{$repeat->subject}}'/>
                            </div>
                            <div class="col-md-5">
                                <input class="form form-control repeats" type="text" name="repeats_reason[{{$h}}]" id='repeats_reason{{$h}}' value='{{$repeat->reason}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($h == 0)
                                <button type="button" name="add_repeats" id="add_repeats" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_repeats" id="{{$h}}" class="btn btn-danger btn_remove btn_remove_repeats">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $h = $h + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-2">
                                <label>Level</label>
                                <input class="form form-control repeats_reason" type="text" name="repeats_level[]" id='repeats_level1'/>
                            </div>
                            <div class="col-md-4">
                                <label>Subject</label>
                                <input class="form form-control repeats_school_year" type="text" name="repeats_subject[]" id='repeats_subject1'/>
                            </div>
                            <div class="col-md-5">
                                <label>Reason</label>
                                <input class="form form-control repeats_reason" type="text" name="repeats_reason[]" id='repeats_reason1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="repeats" id="add_repeats" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <hr>
                    <label style="background-color: gray">DISCIPLINARY RECORD</label><br>
                    <label>Were you ever placed on probation, suspension, or expelled from school?</label> &nbsp; (Transferees must include undergraduate courses.)
                    <?php $i = 0; ?>
                    <div  id="dynamic_field_suspensions">
                        <!--div class="top-row"-->
                        <?php $suspensions = \App\StudentInfoSuspension::where('idno', $user->idno)->get(); ?>
                        @if(count($suspensions)>0)
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Offense</label>
                            </div>
                            <div class="col-md-4">
                                <label>Penalty</label>
                            </div>
                            <div class="col-md-3">
                                <label>Period Covered</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($suspensions as $suspension)
                        <div id='row_suspensions{{$i}}' class="form form-group">
                            <div class="col-md-4">
                                <input class="form form-control suspensions" type="text" name="suspensions_offense[{{$i}}]" id='suspensions_offense{{$i}}' value='{{$suspension->offense}}'/>
                            </div>
                            <div class="col-md-4">
                                <input class="form form-control suspensions" type="text" name="suspensions_penalty[{{$i}}]" id='suspensions_penalty{{$i}}' value='{{$suspension->penalty}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control suspensions" type="text" name="suspensions_period[{{$i}}]" id='suspensions_period{{$i}}' value='{{$suspension->period}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($i == 0)
                                <button type="button" name="add_suspensions" id="add_suspensions" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_suspensions" id="{{$i}}" class="btn btn-danger btn_remove btn_remove_suspensions">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $i = $i + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>Offense</label>
                                <input class="form form-control suspensions_offense" type="text" name="suspensions_offense[]" id='suspensions_offense1'/>
                            </div>
                            <div class="col-md-4">
                                <label>Penalty</label>
                                <input class="form form-control suspensions_penalty" type="text" name="suspensions_penalty[]" id='suspensions_penalty1'/>
                            </div>
                            <div class="col-md-3">
                                <label>Period Covered</label>
                                <input class="form form-control suspensions_period" type="text" name="suspensions_period[]" id='suspensions_period1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="suspensions" id="add_suspensions" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <label>Were you ever expelled or refused enrollment from your high school, college or university?</label>
                    <div class='form-group'>
                        <div class="col-sm-12">
                            <label>If yes, please specify.</label>
                            <input class="form form-control" name='is_expelled_reason' value="{{old('is_expelled_reason',$info->is_expelled_reason)}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_4">
                    <label style="background-color: gray">ACTIVITIES</label><br>
                    <label>List all activities, jobs, and interest outside of class. Transferees must include college activities. Please include position held an other special responsibilities.</label>
                    <?php $j = 0; ?>
                    <div  id="dynamic_field_activities">
                        <!--div class="top-row"-->
                        <?php $activities = \App\StudentInfoActivity::where('idno', $user->idno)->get(); ?>
                        @if(count($activities)>0)
                        <div class="form form-group">
                            <div class="col-md-5">
                                <label>Activity or Organization</label>
                            </div>
                            <div class="col-md-2">
                                <label>Year Level</label>
                            </div>
                            <div class="col-md-4">
                                <label>Number of hours involved per day/week/month</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($activities as $activity)
                        <div id='row_activities{{$j}}' class="form form-group">
                            <div class="col-md-5">
                                <input class="form form-control activities" type="text" name="activities_activity[{{$j}}]" id='activities_activity{{$j}}' value='{{$activity->activity}}'/>
                            </div>
                            <div class="col-md-2">
                                <input class="form form-control activities" type="text" name="activities_level[{{$j}}]" id='activities_level{{$j}}' value='{{$activity->level}}'/>
                            </div>
                            <div class="col-md-4">
                                <input class="form form-control activities" type="text" name="activities_hours[{{$j}}]" id='activities_hourse{{$j}}' value='{{$activity->hours}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($j == 0)
                                <button type="button" name="add_activities" id="add_activities" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_activities" id="{{$j}}" class="btn btn-danger btn_remove btn_remove_activities">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $j = $j + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-5">
                                <label>Activity or Organization</label>
                                <input class="form form-control activities_offense" type="text" name="activities_activity[]" id='activities_activity1'/>
                            </div>
                            <div class="col-md-2">
                                <label>Year Level</label>
                                <input class="form form-control activities_penalty" type="text" name="activities_level[]" id='activities_level1'/>
                            </div>
                            <div class="col-md-4">
                                <label>Number of hours involved per day/week/month</label>
                                <input class="form form-control activities_period" type="text" name="activities_hours[]" id='activities_hours1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="activities" id="add_activities" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <label>Have you ever been elected/appointed as class officer?</label>
                    <div class='form-group'>
                        <div class="col-sm-12">
                            <label>If yes, please specify.</label>
                            <input class="form form-control" name='is_officer' value="{{old('is_expelled_reason',$info->is_officer)}}" type="text">
                        </div>
                    </div>
                    <label>Do you currently(or in the past) have a modelling contract?</label>
                    <div class='form-group'>
                        <div class="col-sm-12">
                            <label>If yes, please specify.</label>
                            <input class="form form-control" name='is_modelling' value="{{old('is_expelled_reason',$info->is_modelling)}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_5">
                    <label style="background-color:  gray">COLLEGE APPLICATION</label><br>
                    <label>In order of preference, please list colleges or universities you have applied or intend to apply to.</label>
                    <?php $k = 0; ?>
                    <div  id="dynamic_field_intends">
                        <!--div class="top-row"-->
                        <?php $intends = \App\StudentInfoIntend::where('idno', $user->idno)->get(); ?>
                        @if(count($intends)>0)
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>College/University</label>
                            </div>
                            <div class="col-md-4">
                                <label>Course</label>
                            </div>
                            <div class="col-md-3">
                                <label>Have you taken entrance test?</label>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                            </div>
                        </div>
                        @foreach($intends as $intend)
                        <div id='row_intends{{$k}}' class="form form-group">
                            <div class="col-md-4">
                                <input class="form form-control intends" type="text" name="intends_college[{{$k}}]" id='intends_college{{$k}}' value='{{$intend->college}}'/>
                            </div>
                            <div class="col-md-4">
                                <input class="form form-control intends" type="text" name="intends_course[{{$k}}]" id='intends_course{{$k}}' value='{{$intend->course}}'/>
                            </div>
                            <div class="col-md-3">
                                <input class="form form-control intends" type="text" name="intends_is_taken[{{$k}}]" id='intends_is_taken{{$k}}' value='{{$intend->is_taken}}'/>
                            </div>
                            <div class="col-md-1">
                                @if($k == 0)
                                <button type="button" name="add_intends" id="add_intends" class="btn btn-success"> + </button>
                                @else
                                <button type='button' name="remove_intends" id="{{$k}}" class="btn btn-danger btn_remove btn_remove_intends">X</button>
                                @endif
                            </div>
                        </div>

                        <?php $k = $k + 1; ?>
                        @endforeach
                        @else
                        <div class="form form-group">
                            <div class="col-md-4">
                                <label>College/University</label>
                                <input class="form form-control intends_offense" type="text" name="intends_college[]" id='intends_college1'/>
                            </div>
                            <div class="col-md-4">
                                <label>Course</label>
                                <input class="form form-control intends_penalty" type="text" name="intends_course[]" id='intends_course1'/>
                            </div>
                            <div class="col-md-3">
                                <label>Have you taken entrance test?</label>
                                <input class="form form-control intends_period" type="text" name="intends_is_taken[]" id='intends_is_taken1'/>
                            </div>
                            <div class="col-md-1">
                                <label>Add</label>
                                <button type="button" name="intends" id="add_intends" class="btn btn-success"> + </button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <label>Please rank(1 being the highest) top 5 factors which helped in choosing Assumption College.</label>
                    <div class='form-group'>
                        <?php $rank = \App\StudentInfoSchoolRank::where('idno', $user->idno)->first(); ?>
                        <div class="col-sm-12">
                            <div class='form form-group'>
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='academic_excellence' value='{{old('academic_excellence',$rank->academic_excellence)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Academic Excellence</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='womens_college' value='{{old('womens_college',$rank->womens_college)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Women's College</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='values_formation' value='{{old('values_formation',$rank->values_formation)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Character/Values formation</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='flyer' value='{{old('flyer',$rank->flyer)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Flyer/Brochure/Poster</label>
                                </div>
                            </div>

                            <div class='form form-group'>
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='family' value='{{old('family',$rank->family)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Family</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='security' value='{{old('security',$rank->security)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Security</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='college_fair' value='{{old('college_fair',$rank->college_fair)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>College Fair</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='hs_counselor' value='{{old('hs_counselor',$rank->hs_counselor)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>High School Counselor</label>
                                </div>
                            </div>

                            <div class='form form-group'>
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='location' value='{{old('location',$rank->location)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Location</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='ac_graduate' value='{{old('ac_graduate',$rank->ac_graduate)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>AC Graduate</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='friend' value='{{old('friend',$rank->friend)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Friend</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='assumption_career' value='{{old('assumption_career',$rank->assumption_career)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Assumption Career talk/visit</label>
                                </div>
                            </div>

                            <div class='form form-group'>
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='parents_choice' value='{{old('parents_choice',$rank->parents_choice)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Parent's Choice</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='courses' value='{{old('courses',$rank->courses)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Courses</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='prestige' value='{{old('prestige',$rank->prestige)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Prestige</label>
                                </div>

                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='ac_student' value='{{old('ac_student',$rank->ac_student)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>AC Student</label>
                                </div>
                            </div>

                            <div class='form form-group'>
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='newspaper' value='{{old('newspaper',$rank->newspaper)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Newspaper ad</label>
                                </div>
                                
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='open_house' value='{{old('open_house',$rank->open_house)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Assumption Open House</label>
                                </div>
                                
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='social_media' value='{{old('social_media',$rank->social_media)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Social Media(FB, Twitter, Instagram, Youtube)</label>
                                </div>
                                
                                <div class="col-sm-1">
                                    <input type=number max="5" min="0" class="form-control" name='others' value='{{old('others',$rank->others)}}'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Others</label><input type=text class="form-control" name='others_field' value='{{old('others_field',$rank->others_field)}}'>
                                </div>
                            </div>
                        <?php $course_rank = \App\StudentInfoCoursesRank::where('idno', $user->idno)->first(); ?>
                            <label>Please rank in numerical order the top 3 course preferences offered by Assumption College</label>
                            <input type=text class="form-control" name='rank_1' value='{{old('rank_1',$course_rank->rank_1)}}' placeholder="Rank 1">
                            <input type=text class="form-control" name='rank_2' value='{{old('rank_2',$course_rank->rank_2)}}' placeholder="Rank 2">
                            <input type=text class="form-control" name='rank_3' value='{{old('rank_3',$course_rank->rank_3)}}' placeholder="Rank 3">
                            <label>Why did you select your most preferred course?</label>
                            <input type=text class="form-control" name='why_most_preferred' value='{{old('why_most_preferred',$course_rank->why_most_preferred)}}'>
                            <label>Who decided on your course/study in Assumption?</label>
                            <input type=text class="form-control" name='who_decided' value='{{old('who_decided',$course_rank->who_decided)}}'>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_6">
                    <label style="background-color:  gray">EMERGENCY INFORMATION</label><br>
                    <label>Please provide the name of a contact person other than your parents whom the Admissions Office can call.</label>
                    <div class='form-group'>
                        <?php $emergency = \App\StudentInfoEmergency::where('idno', $user->idno)->first(); ?>
                        <div class="col-sm-3">
                            <label>Person to notify</label>
                            <input class="form form-control" name='emer_lastname' value="{{old('emer_lastname',$emergency->lastname)}}" type="text" placeholder="Lastname">
                        </div>
                        <div class="col-sm-3">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='emer_firstname' value="{{old('emer_firstname',$emergency->firstname)}}" type="text" placeholder="Firstname">
                        </div>
                        <div class="col-sm-3">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='emer_middlename' value="{{old('emer_middlename',$emergency->middlename)}}" type="text" placeholder="Middlename">
                        </div>
                        <div class="col-sm-3">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='emer_extensionname' value="{{old('emer_extensionname',$emergency->extensionname)}}" type="text" placeholder="Extensionname">
                        </div>
                        <div class="col-sm-12">
                            <label>Relationship</label>
                            <input class="form form-control" name='emer_relation' value="{{old('emer_relation',$emergency->relation)}}" type="text" placeholder="Example: Aunt/Uncle">
                        </div>
                        <div class="col-sm-12">
                            <label>Address</label>
                            <input class="form form-control" name='emer_address' value="{{old('emer_address',$emergency->address)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Home Phone</label>
                            <input class="form form-control" name='emer_phone' value="{{old('emer_phone',$emergency->phone)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Business Telephone</label>
                            <input class="form form-control" name='emer_business_phone' value="{{old('emer_business_phone',$emergency->business_phone)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Mobile Phone</label>
                            <input class="form form-control" name='emer_mobile' value="{{old('emer_mobile',$emergency->mobile)}}" type="text">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="submit" value='Save' class='form-control btn btn-success'><br><br>
        <a target="_blank" href="{{url('/registrar_college',array('print_info', $user->idno))}}"><button type="button" class="btn btn-primary">Print Student Infromation</button></a>
    </div>
</form>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" crossorigin="anonymous"></script>
    <script>
$('#tel_no').mask('(00)0000-0000');
$('#cell_no').mask('(0000)000-0000');
    function withdraw(date_today, value, idno) {
    array = {};
    array['date_today'] = date_today;
    array['value'] = value;
    array['idno'] = idno;
    window.location.replace('/registrar_college/withdraw_enrolled_student/' + array['value'] + "/" + array['date_today'] + "/" + array['idno']);
    }

    var a = "{{$a-1}}";
    var b = "{{$b-1}}";
    var c = "{{$c-1}}";
    var d = "{{$d-1}}";
    var e = "{{$e-1}}";
    var f = "{{$f-1}}";
    var g = "{{$g-1}}";
    var h = "{{$h-1}}";
    var i = "{{$i-1}}";
    var j = "{{$j-1}}";
    var k = "{{$k-1}}";
    $(document).ready(function () {
    $('#add_alumni').click(function(){

    a++;
    $('#dynamic_field_alumni').append('<div id="row_alumni' + a + '" class="form form-group">\n\
           <div class="col-md-2"><input class="form form-control alumni"       type="text" name="alumni_name[]"       id="alumni_name' + a + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control alumni"       type="text" name="alumni_relationship[]"       id="alumni_relationship' + a + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control alumni"       type="text" name="alumni_year_graduated[]"       id="alumni_graduated' + a + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control alumni"       type="text" name="alumni_department[]"       id="alumni_department' + a + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control alumni"       type="text" name="alumni_location[]"       id="alumni_location' + a + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_alumni"  id="' + a + '" class="btn btn-danger btn_remove btn_remove_alumni">X</a></div></div>');
    });
    $('#dynamic_field_alumni').on('click', '.btn_remove_alumni', function(){
    var button_id = $(this).attr("id");
    $("#row_alumni" + button_id + "").remove();
    a--;
    });
    $('#add_siblings').click(function(){

    b++;
    $('#dynamic_field_siblings').append('<div id="row_siblings' + b + '" class="form form-group">\n\
           <div class="col-md-4"><input class="form form-control siblings"       type="text" name="siblings_name[]"       id="siblings_name' + b + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control siblings"       type="text" name="siblings_age[]"       id="siblings_age' + b + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control siblings"       type="text" name="siblings_level[]"       id="siblings_level' + b + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control siblings"       type="text" name="siblings_school[]"       id="siblings_school' + b + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_siblings"  id="' + b + '" class="btn btn-danger btn_remove btn_remove_siblings">X</a></div></div>');
    });
    $('#dynamic_field_siblings').on('click', '.btn_remove_siblings', function(){
    var button_id = $(this).attr("id");
    $("#row_siblings" + button_id + "").remove();
    b--;
    });
    $('#add_children').click(function(){

    c++;
    $('#dynamic_field_children').append('<div id="row_children' + c + '" class="form form-group">\n\
           <div class="col-md-4"><input class="form form-control children"       type="text" name="children_name[]"       id="children_name' + c + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control children"       type="text" name="children_age[]"       id="children_age' + c + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control children"       type="text" name="children_level[]"       id="children_level' + c + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control children"       type="text" name="children_school[]"       id="children_school' + c + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_children"  id="' + c + '" class="btn btn-danger btn_remove btn_remove_children">X</a></div></div>');
    });
    $('#dynamic_field_children').on('click', '.btn_remove_children', function(){
    var button_id = $(this).attr("id");
    $("#row_children" + button_id + "").remove();
    c--;
    });
    $('#add_attendeds').click(function(){

    d++;
    $('#dynamic_field_attendeds').append('<div id="row_attendeds' + d + '" class="form form-group">\n\
           <div class="col-md-3"><input class="form form-control attendeds"       type="text" name="attendeds_college[]"       id="attendeds_college' + d + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control attendeds"       type="text" name="attendeds_address[]"       id="attendeds_address' + d + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control attendeds"       type="text" name="attendeds_course[]"       id="attendeds_course' + d + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control attendeds"       type="text" name="attendeds_school_year[]"       id="attendeds_school_year' + d + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_attendeds"  id="' + d + '" class="btn btn-danger btn_remove btn_remove_attendeds">X</a></div></div>');
    });
    $('#dynamic_field_attendeds').on('click', '.btn_remove_attendeds', function(){
    var button_id = $(this).attr("id");
    $("#row_attendeds" + button_id + "").remove();
    d--;
    });
    $('#add_honors').click(function(){

    e++;
    $('#dynamic_field_honors').append('<div id="row_honors' + e + '" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control honors"       type="text" name="honors_honor[]"       id="honors_honor' + e + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control honors"       type="text" name="honors_level[]"       id="honors_level' + e + '"/></div>\n\
           <div class="col-md-4"><input class="form form-control honors"       type="text" name="honors_event[]"       id="honors_event' + e + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_honors"  id="' + e + '" class="btn btn-danger btn_remove btn_remove_honors">X</a></div></div>');
    });
    $('#dynamic_field_honors').on('click', '.btn_remove_honors', function(){
    var button_id = $(this).attr("id");
    $("#row_honors" + button_id + "").remove();
    e--;
    });
    $('#add_discontinuances').click(function(){

    f++;
    $('#dynamic_field_discontinuances').append('<div id="row_discontinuances' + f + '" class="form form-group">\n\
           <div class="col-md-2"><input class="form form-control discontinuances"       type="text" name="discontinuances_school_year[]"       id="discontinuances_school_year' + f + '"/></div>\n\
           <div class="col-md-9"><input class="form form-control discontinuances"       type="text" name="discontinuances_reason[]"       id="discontinuances_reason' + f + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_discontinuances"  id="' + f + '" class="btn btn-danger btn_remove btn_remove_discontinuances">X</a></div></div>');
    });
    $('#dynamic_field_discontinuances').on('click', '.btn_remove_discontinuances', function(){
    var button_id = $(this).attr("id");
    $("#row_discontinuances" + button_id + "").remove();
    f--;
    });
    $('#add_fails').click(function(){

    g++;
    $('#dynamic_field_fails').append('<div id="row_fails' + g + '" class="form form-group">\n\
           <div class="col-md-4"><input class="form form-control fails"       type="text" name="fails_subject[]"       id="fails_subject' + g + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control fails"       type="text" name="fails_period[]"       id="fails_period' + g + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control fails"       type="text" name="fails_level[]"       id="fails_level' + g + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control fails"       type="text" name="fails_reason[]"       id="fails_reason' + g + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_fails"  id="' + g + '" class="btn btn-danger btn_remove btn_remove_fails">X</a></div></div>');
    });
    $('#dynamic_field_fails').on('click', '.btn_remove_fails', function(){
    var button_id = $(this).attr("id");
    $("#row_fails" + button_id + "").remove();
    g--;
    });
    $('#add_repeats').click(function(){

    h++;
    $('#dynamic_field_repeats').append('<div id="row_repeats' + h + '" class="form form-group">\n\
           <div class="col-md-2"><input class="form form-control repeats"       type="text" name="repeats_level[]"       id="repeats_level' + h + '"/></div>\n\
           <div class="col-md-4"><input class="form form-control repeats"       type="text" name="repeats_subject[]"       id="repeats_subject' + h + '"/></div>\n\
           <div class="col-md-5"><input class="form form-control repeats"       type="text" name="repeats_reason[]"       id="repeats_reason' + h + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_repeats"  id="' + h + '" class="btn btn-danger btn_remove btn_remove_repeats">X</a></div></div>');
    });
    $('#dynamic_field_repeats').on('click', '.btn_remove_repeats', function(){
    var button_id = $(this).attr("id");
    $("#row_repeats" + button_id + "").remove();
    h--;
    });
    $('#add_suspensions').click(function(){

    i++;
    $('#dynamic_field_suspensions').append('<div id="row_suspensions' + i + '" class="form form-group">\n\
           <div class="col-md-4"><input class="form form-control suspensions"       type="text" name="suspensions_offense[]"       id="suspensions_offense' + i + '"/></div>\n\
           <div class="col-md-4"><input class="form form-control suspensions"       type="text" name="suspensions_penalty[]"       id="suspensions_penalty' + i + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control suspensions"       type="text" name="suspensions_period[]"       id="suspensions_period' + i + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_suspensions"  id="' + i + '" class="btn btn-danger btn_remove btn_remove_suspensions">X</a></div></div>');
    });
    $('#dynamic_field_suspensions').on('click', '.btn_remove_suspensions', function(){
    var button_id = $(this).attr("id");
    $("#row_suspensions" + button_id + "").remove();
    i--;
    });
    $('#add_activities').click(function(){

    j++;
    $('#dynamic_field_activities').append('<div id="row_activities' + j + '" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control activities"       type="text" name="activities_activity[]"       id="activities_activity' + j + '"/></div>\n\
           <div class="col-md-2"><input class="form form-control activities"       type="text" name="activities_level[]"       id="activities_level' + j + '"/></div>\n\
           <div class="col-md-4"><input class="form form-control activities"       type="text" name="activities_hours[]"       id="activities_hours' + j + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_activities"  id="' + j + '" class="btn btn-danger btn_remove btn_remove_activities">X</a></div></div>');
    });
    $('#dynamic_field_activities').on('click', '.btn_remove_activities', function(){
    var button_id = $(this).attr("id");
    $("#row_activities" + button_id + "").remove();
    j--;
    });
    $('#add_intends').click(function(){

    k++;
    $('#dynamic_field_intends').append('<div id="row_intends' + k + '" class="form form-group">\n\
           <div class="col-md-4"><input class="form form-control intends"       type="text" name="intends_college[]"       id="intends_college' + k + '"/></div>\n\
           <div class="col-md-4"><input class="form form-control intends"       type="text" name="intends_course[]"       id="intends_course' + k + '"/></div>\n\
           <div class="col-md-3"><input class="form form-control intends"       type="text" name="intends_is_taken[]"       id="intends_is_taken' + k + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_intends"  id="' + k + '" class="btn btn-danger btn_remove btn_remove_intends">X</a></div></div>');
    });
    $('#dynamic_field_intends').on('click', '.btn_remove_intends', function(){
    var button_id = $(this).attr("id");
    $("#row_intends" + button_id + "").remove();
    k--;
    });
    })
    
    

 function getProvince(region){
     var array = {};
     array['region'] = region;
     $.ajax({
         type: "GET",
         url: "/ajax/get_province",
         data: array,
         success: function(data){
             $('#province').html(data).fadeIn();
         }
     })
     getMunicipality(region);
 }
 
 function getMunicipality(region){
    province = $('#province').val();
    var array = {};
    array['region'] = region;
    array['province'] = province;
    $.ajax({
        type: "GET",
        url: "/ajax/get_municipality",
        data: array,
        success: function(data){
            $('#municipality').html(data).fadeIn();
        }
    })
}

function getBarangay(municipality){
    var array = {};
    array['municipality'] = municipality;
    $.ajax({
        type: "GET",
        url: "/ajax/get_brgy",
        data: array,
        success: function(data){
            $('#barangay').html(data).fadeIn();
        }
    })
}
</script>
@endsection
