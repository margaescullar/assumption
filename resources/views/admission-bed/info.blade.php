<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php $is_lock = 0; ?>
<?php $regions = \App\CtrRegion::all(); ?>

<style>
.nav-tabs-custom > .nav-tabs > li.active > a, .nav-tabs-custom > .nav-tabs > li.active:hover > a {
    background-color: white !important;
    color: #333 !important;
}
.nav-tabs-custom > .nav-tabs > li > a:hover {
    color: white !important;
}
.nav-tabs-custom > .nav-tabs > li > a {
    color: whitesmoke !important;
}
</style>
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
        Student Information
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student Information</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="form-horizontal">
    <form action="{{url('/bedregistrar', array('updateinfo', $user->idno))}}" method="post" class="form-horizontal">
    {{ csrf_field() }}
    @if($status->status == env("FOR_APPROVAL"))
    <input type="hidden" name="user_status" value="1">
    <div class="col-md-12">
        
         <div class="col-md-3 pull-right">
             <div class="form form-group">
                 <label>Admission Status: </label><br><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     
                 @if(Auth::user()->idno == "acruz")
                     FOR TESTING/INTERVIEW
                     @else
                     FOR APPROVAL
                 @endif</b>
             </div>
          </div>
        
        <?php //$interview_schedules = \App\InterviewSchedule::where('is_remove',0)->where('datetime','>', DATE(NOW()))->orderBy('datetime', 'asc')->get(); ?>
        <?php $interview_schedules = \App\InterviewSchedule::where('is_remove',0)->orderBy('datetime', 'dsc')->get(); ?>
        <?php $interview = \App\InterviewStudent::where('idno',$user->idno)->first(); ?>
        @if($interview->schedule_id == NULL)
        <?php $is_lock = 1; ?>
        @endif
        <div class="col-md-3 pull-left">
             <div class="form form-group">
                 <label>Parent Interview Schedule:</label>
                 <select class="form form-control" onchange='update_interview(this.value)'>
                     <option>Select Schedule</option>
                     @foreach($interview_schedules as $inter)
                     <option value='{{$inter->id}}' @if($interview->schedule_id == $inter->id)selected @endif>{{date("F j, Y - g:i A",strtotime($inter->datetime))}}</option>
                     @endforeach
                 </select>
             </div>
        </div>
        <?php //$testing_schedules = \App\TestingSchedule::where('is_remove',0)->where('datetime','>', DATE(NOW()))->orderBy('datetime', 'asc')->get(); ?>
        <?php $testing_schedules = \App\TestingSchedule::where('is_remove',0)->orderBy('datetime', 'dsc')->get(); ?>
        <?php $testing = \App\TestingStudent::where('idno',$user->idno)->first(); ?>
        @if($testing->schedule_id == NULL)
        <?php $is_lock = 1; ?>
        @endif
        <div class="col-md-3 pull-left">
             <div class="form form-group">
                 <label>Testing Schedule:</label>
                 <select class="form form-control" onchange='update_testing(this.value)'>
                     <option>Select Schedule</option>
                     @foreach($testing_schedules as $sched)
                     <option value='{{$sched->id}}' @if($testing->schedule_id == $sched->id)selected @endif>{{date("F j, Y - g:i A",strtotime($sched->datetime))}}</option>
                     @endforeach
                 </select>
             </div>
        </div>
        
        
        @if($info->applied_for == "Pre-Kinder" || $info->applied_for == "Kinder" || $info->applied_for == "Grade 1")
        <?php //$group_schedules = \App\GroupSchedule::where('is_remove',0)->where('datetime','>', DATE(NOW()))->orderBy('datetime', 'asc')->get(); ?>
        <?php $group_schedules = \App\GroupSchedule::where('is_remove',0)->orderBy('datetime', 'dsc')->get(); ?>
        <?php $group = \App\GroupStudent::where('idno',$user->idno)->first(); ?>
        @if($group->schedule_id == NULL)
        <?php $is_lock = 1; ?>
        @endif
        <div class="col-md-3 pull-left">
             <div class="form form-group">
                 <label>Group Interview Schedule:</label>
                 <select class="form form-control" onchange='update_group(this.value)'>
                     <option>Select Schedule</option>
                     @foreach($group_schedules as $gro)
                     <option value='{{$gro->id}}' @if($group->schedule_id == $gro->id)selected @endif>{{date("F j, Y - g:i A",strtotime($gro->datetime))}}</option>
                     @endforeach
                 </select>
             </div>
        </div>
        @else
        <?php //$group_schedules = \App\GroupSchedule::where('is_remove',0)->where('datetime','>', DATE(NOW()))->orderBy('datetime', 'asc')->get(); ?>
        <?php $individual_schedules = \App\IndividualSchedules::where('is_remove',0)->orderBy('datetime', 'dsc')->get(); ?>
        <?php $individual = \App\IndividualStudents::where('idno',$user->idno)->first(); ?>
        @if($individual->schedule_id == NULL)
        <?php $is_lock = 1; ?>
        @endif
        <div class="col-md-3 pull-left">
             <div class="form form-group">
                 <label>Applicant Interview Schedule:</label>
                 <select class="form form-control" onchange='update_individual(this.value)'>
                     <option>Select Schedule</option>
                     @foreach($individual_schedules as $ind)
                     <option value='{{$ind->id}}' @if($individual->schedule_id == $ind->id)selected @endif>{{date("F j, Y - g:i A",strtotime($ind->datetime))}}</option>
                     @endforeach
                 </select>
             </div>
        </div>
        @endif
        
    </div>
    @elseif($status->status == env("PRE_REGISTERED"))
    {{ csrf_field() }}
    <div class="col-md-12">
    
         <div class="col-md-2 pull-left">
             <div class="form form-group">
                 <label><br><br></label>
                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">
                Reset Password
              </button>
             </div>
          </div>
        
         <div class="col-md-3 pull-right">
             <div class="form form-group">
                 <label>Admission Status: </label><button class="form form-control btn btn-success" style="color: white">PRE-REGISTERED</button>
             </div>
          </div>
    </div>
    
    @endif
    <div class="col-sm-12">
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif  
        @if (Session::has('danger'))
            <div class="alert alert-danger">{{ Session::get('danger') }}</div>
        @endif
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
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
            <div class="box-footer">
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
    @if($status->status == env("FOR_APPROVAL"))
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-3">
                <label>Level Applied For</label>
                <select onchange="change_applied_for(this.value,strand.value,'{{$user->idno}}','level')" id="level">
                    <option @if($info->applied_for == "Pre-Kinder") selected="" @endif>Pre-Kinder</option>
                    <option @if($info->applied_for == "Kinder") selected="" @endif>Kinder</option>
                    <option @if($info->applied_for == "Grade 1") selected="" @endif>Grade 1</option>
                    <option @if($info->applied_for == "Grade 2") selected="" @endif>Grade 2</option>
                    <option @if($info->applied_for == "Grade 3") selected="" @endif>Grade 3</option>
                    <option @if($info->applied_for == "Grade 4") selected="" @endif>Grade 4</option>
                    <option @if($info->applied_for == "Grade 5") selected="" @endif>Grade 5</option>
                    <option @if($info->applied_for == "Grade 6") selected="" @endif>Grade 6</option>
                    <option @if($info->applied_for == "Grade 7") selected="" @endif>Grade 7</option>
                    <option @if($info->applied_for == "Grade 8") selected="" @endif>Grade 8</option>
                    <option @if($info->applied_for == "Grade 9") selected="" @endif>Grade 9</option>
                    <option @if($info->applied_for == "Grade 10") selected="" @endif>Grade 10</option>
                    <option @if($info->applied_for == "Grade 11") selected="" @endif>Grade 11</option>
                    <!--<option @if($info->applied_for == "Grade 12") selected="" @endif>Grade 12</option>-->
                </select>
                <label>Strand Applied For</label>
                <select onchange="change_applied_for(level.value,this.value,'{{$user->idno}}','strand')" id="strand">
                    <option @if($info->applied_for_strand == NULL) selected="" @endif></option>
                    <option @if($info->applied_for_strand == "STEM") selected="" @endif>STEM</option>
                    <option @if($info->applied_for_strand == "HUMSS") selected="" @endif>HUMSS</option>
                    <option @if($info->applied_for_strand == "ABM") selected="" @endif>ABM</option>
                    <option @if($info->applied_for_strand == "AD") selected="" @endif>AD</option>
                </select>
            </div>
        </div>
        <br>
    </div>
    @endif
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active nav" style="border-top-color: #3c8dbc;background-color:#3c8dbc"><a href="#tab_1" data-toggle="tab"><strong>PERSONAL INFORMATION</strong></a></li>
                <li style="border-top-color: #3c8dbc;background-color:#3c8dbc"><a href="#tab_2" data-toggle="tab"><strong>FAMILY BACKGROUND</strong></a></li>
                <li style="border-top-color: #3c8dbc;background-color:#3c8dbc"><a href="#tab_3" data-toggle="tab"><strong>ACADEMIC BACKGROUND</strong></a></li>
                <li style="border-top-color: #3c8dbc;background-color:#3c8dbc"><a href="#tab_4" data-toggle="tab"><strong>MEDICAL HISTORY/PHYSICAL FITNESS</strong></a></li>
                <li style="border-top-color: #3c8dbc;background-color:#3c8dbc"><a href="#tab_5" data-toggle="tab"><strong>OTHER REQUIREMENTS</strong></a></li>
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
                            <input class="form form-control" name='tel_no' id='tel_no' placeholder='Telephone: (02)____-____' value="{{old('tel_no',$info->tel_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='cell_no' id="cell_no" placeholder='Cellphone: (0917)___-____' value="{{old('cel_no',$info->cell_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input class="form form-control" name='email' placeholder='Email Address*' readonly="" value="{{old('email',$user->email)}}" type="email">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label>Date of Birth</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-birthday-cake"></i>
                                </div>
                                <input class="form form-control" name='date_of_birth' value="{{old('birthdate',$info->date_of_birth)}}" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Place of Birth</label>
                            <input class="form form-control" name='place_of_birth' value="{{old('place_of_birth',$info->place_of_birth)}}" placeholder='Place of Birth' type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Gender</label>
                            <select class="form form-control" name='gender' type="text">
                                <option value='Female' @if ($info->gender == 'Female') selected='' @endif>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Nationality</label>
                            <input class="form form-control" name='nationality' placeholder='Nationality' value="{{old('nationality',$info->nationality)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Religion</label>
                            <input class="form form-control" name='religion' placeholder='Religion' value="{{old('religion',$info->religion)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Citizenship</label>
                            <select class="form form-control" name='is_foreign' value="{{old('is_alien')}}" type="text">
                                <option value="">Select Local/Foreign</option>
                                <option value="0" @if ($user->is_foreign == 0) selected='' @endif>Filipino</option>
                                <option value="1" @if ($user->is_foreign == 1) selected='' @endif >Foreign</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <label>For Non-Filipinos and Filipinos Born Abroad</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Immigration Status/Visa Classification</label>
                            <input class="form form-control" name='immig_status' value="{{old('immig_status',$info->immig_status)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Authorized Stay</label>
                            <input class="form form-control" name='auth_stay' value="{{old('auth_stay',$info->auth_stay)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Passport Number</label>
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
                            <label>ACR I-Card No.</label>
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
<!--                    <label>Father</label>-->
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Father's Name</label>
                            <input class="form form-control" name='father' value="{{old('father',$info->father)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Nationality</label>
                            <input class="form form-control" name='f_citizenship' value="{{old('f_citizenship',$info->f_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='f_is_living' @if($info->f_is_living==1)checked @endif value="1" type="radio">Living</label>
                                <label><input name='f_is_living' @if($info->f_is_living==0)checked @endif value="0" type="radio">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label>Religion</label>
                            <input class="form form-control" name='f_religion' value="{{old('f_religion',$info->f_religion)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Highest Educational Attainment</label>
                            <input class="form form-control" name='f_education' value="{{old('f_education',$info->f_education)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>School</label>
                            <input class="form form-control" name='f_school' value="{{old('f_school',$info->f_school)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Occupation/Profession</label>
                            <input class="form form-control" name='f_occupation' value="{{old('f_occupation',$info->f_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='f_company_name' value="{{old('f_company_name',$info->f_company_name)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Company Address</label>
                            <input class="form form-control" name='f_company_address' value="{{old('f_company_address',$info->f_company_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Office Tel. No.</label>
                            <input class="form form-control" name='f_company_number' value="{{old('f_company_number',$info->f_company_number)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Home Telephone Number</label>
                            <input class="form form-control" name='f_phone' value="{{old('f_phone',$info->f_phone)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Cellphone Number</label>
                            <input class="form form-control" name='f_cell_no' value="{{old('f_cell_no',$info->f_cell_no)}}" type="text">
                        </div>
<!--                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='f_address' value="{{old('f_address',$info->f_address)}}" type="text">
                        </div>-->
                        <div class="col-sm-3">
                            <label>Email Address</label>
                            <input class="form form-control" name='f_email' value="{{old('f_email',$info->f_email)}}" type="email">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Membership in Any Organization</label>
                            <div class='radio'>
                                <label><input name='f_any_org' @if($info->f_any_org==1)checked @endif value="1" type="radio">Yes</label>
                                <label><input name='f_any_org' @if($info->f_any_org==0)checked @endif value="0" type="radio">No</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>Type of Organization</label>
                            <select class="form form-control" name='f_type_of_org' value="{{old('f_type_of_org',$info->f_type_of_org)}}" type="text">
                                <option value="" @if($info->f_type_of_org != "Religious" or $info->f_type_of_org != "Civic") selected="" @endif>Select Type of Organization</option>
                                <option @if($info->f_type_of_org == "Religious") selected="" @endif>Religious</option>
                                <option @if($info->f_type_of_org == "Civic") selected="" @endif>Civic</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Area of expertise you can share with the school</label>
                            <input class="form form-control" name='f_expertise' value="{{old('f_expertise',$info->f_expertise)}}" type="text">
                        </div>
                    </div>
                    <hr>
<!--                    <label>Mother</label>-->
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Mother's Maiden Name</label>
                            <input class="form form-control" name='mother' value="{{old('mother',$info->mother)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Nationality</label>
                            <input class="form form-control" name='m_citizenship' value="{{old('m_citizenship',$info->m_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='m_is_living' @if($info->m_is_living==1)checked @endif value="1" type="radio">Living</label>
                                <label><input name='m_is_living' @if($info->m_is_living==0)checked @endif value="0" type="radio">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label>Religion</label>
                            <input class="form form-control" name='m_religion' value="{{old('m_religion',$info->m_religion)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Highest Educational Attainment</label>
                            <input class="form form-control" name='m_education' value="{{old('m_education',$info->m_education)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>School</label>
                            <input class="form form-control" name='m_school' value="{{old('m_school',$info->m_school)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Occupation/Profession</label>
                            <input class="form form-control" name='m_occupation' value="{{old('m_occupation',$info->m_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='m_company_name' value="{{old('m_company_name',$info->m_company_name)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Company Address</label>
                            <input class="form form-control" name='m_company_address' value="{{old('m_company_address',$info->m_company_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Office Tel. No.</label>
                            <input class="form form-control" name='m_company_number' value="{{old('m_company_number',$info->m_company_number)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Home Telephone Number</label>
                            <input class="form form-control" name='m_phone' value="{{old('m_phone',$info->m_phone)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Cellphone Number</label>
                            <input class="form form-control" name='m_cell_no' value="{{old('m_cell_no',$info->m_cell_no)}}" type="text">
                        </div>
<!--                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='m_address' value="{{old('m_address',$info->m_address)}}" type="text">
                        </div>-->
                        <div class="col-sm-3">
                            <label>Email Address</label>
                            <input class="form form-control" name='m_email' value="{{old('m_email',$info->m_email)}}" type="email">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Membership in Any Organization</label>
                            <div class='radio'>
                                <label><input name='m_any_org' @if($info->m_any_org==1)checked @endif value="1" type="radio">Yes</label>
                                <label><input name='m_any_org' @if($info->m_any_org==0)checked @endif value="0" type="radio">No</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>Type of Organization</label>
                            <select class="form form-control" name='m_type_of_org' value="{{old('m_type_of_org',$info->m_type_of_org)}}" type="text">
                                <option value="" @if($info->m_type_of_org != "Religious" or $info->f_type_of_org != "Civic") selected="" @endif>Select Type of Organization</option>
                                <option @if($info->m_type_of_org == "Religious") selected="" @endif>Religious</option>
                                <option @if($info->m_type_of_org == "Civic") selected="" @endif>Civic</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label>Area of expertise you can share with the school</label>
                            <input class="form form-control" name='m_expertise' value="{{old('m_expertise',$info->m_expertise)}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <div  class="form form-group">
                        <div class="col-sm-3">
                            <label>Parent's Civil Status at Present</label>
                            <select class="form form-control" name='parents_civil_status' type="text">
                                <option value=''>Select Civil Status</option>
                                <option @if ($info->parents_civil_status == 'Married') selected='' @endif>Married</option>
                                <option @if ($info->parents_civil_status == 'Separated') selected='' @endif>Separated</option>
                                <option @if ($info->parents_civil_status == 'Single') selected='' @endif>Single</option>
                                <option @if ($info->parents_civil_status == 'Widowed') selected='' @endif>Widowed</option>
                                <option @if ($info->parents_civil_status == 'Living-In') selected='' @endif>Living-In</option>
                                <option @if ($info->parents_civil_status == 'Anulled') selected='' @endif>Anulled</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <label>If not living with parents:</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Guardian's Name</label>
                            <input class="form form-control" name='guardian' value="{{old('guardian',$info->guardian)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Relation</label>
                            <input class="form form-control" name='g_relation' value="{{old('g_relation',$info->g_relation)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-8">
                            <label>Address</label>
                            <input class="form form-control" name='g_address' value="{{old('g_address',$info->g_address)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Contact Number</label>
                            <input class="form form-control" name='g_contact_no' value="{{old('g_contact_no',$info->g_contact_no)}}" type="text">
                        </div>
                    </div>
                    
                    <hr>
                    
                    <label>Siblings (brothers and sisters)</label>
                    <?php $i=0; ?>
                        <div  id="dynamic_field_sibling">
                            <!--div class="top-row"-->
                                <?php $siblings = \App\BedSiblings::where('idno', $user->idno)->get(); ?>
                                @if(count($siblings)>0)
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Name</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Age</label>
                                </div>
                                <div class="col-md-2">
                                    <label>Level/Occupation</label>
                                </div>
                                <div class="col-md-3">
                                    <label>School/Work</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($siblings as $sibling)
                            <div id='row_sibling{{$i}}' class="form form-group">
                                <div class="col-md-5">
                                    <input class="form form-control limitation" type="text" name="sibling[{{$i}}]" id='sibling{{$i}}' value='{{$sibling->sibling}}'/>
                                </div>
                                <div class="col-md-1">
                                    <input class="form form-control limitation" type="text" name="sibling_age[{{$i}}]" id='sibling_age{{$i}}' value='{{$sibling->age}}'/>
                                </div>
                                <div class="col-md-2">
                                    <input class="form form-control limitation" type="text" name="sibling_level[{{$i}}]" id='sibling_level{{$i}}' value='{{$sibling->level}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control limitation" type="text" name="sibling_school[{{$i}}]" id='sibling_school{{$i}}' value='{{$sibling->school}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($i == 0)
                                    <button type="button" name="add_sibling" id="add_sibling" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_sibling" id="{{$i}}" class="btn btn-danger btn_remove btn_remove_sibling">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $i = $i+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Name</label>
                                    <input class="form form-control sibling" type="text" name="sibling[]" id='sibling1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Age</label>
                                    <input class="form form-control sibling_age" type="text" name="sibling_age[]" id='sibling_age1'/>
                                </div>
                                <div class="col-md-2">
                                    <label>Level/Occupation</label>
                                    <input class="form form-control sibling_level" type="text" name="sibling_level[]" id='sibling_level1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>School/Work</label>
                                    <input class="form form-control sibling_school" type="text" name="sibling_school[]" id='sibling_school1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="sibling" id="add_sibling" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    <hr>
                    <label>Is your mother an Alumna of Assumption College? If yes, year graduated:</label>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Grade School</label>
                            <input class="form form-control" name='m_alumna_gradeschool_year' value="{{old('m_alumna_gradeschool_year',$info->m_alumna_gradeschool_year)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>High School</label>
                            <input class="form form-control" name='m_alumna_highschool_year' value="{{old('m_alumna_highschool_year',$info->m_alumna_highschool_year)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>College</label>
                            <input class="form form-control" name='m_alumna_college_year' value="{{old('m_alumna_college_year',$info->m_alumna_college_year)}}" type="text">
                        </div>
                    </div>
                    <label>Aside from mother, are there other members of your family who are alumnae of Assumption? If yes, please fill out the spaces below</label>
                    <?php $j=0; ?>
                        <div  id="dynamic_field_alumni">
                            <!--div class="top-row"-->
                                <?php $alumnis = \App\BedOtherAlumni::where('idno', $user->idno)->get(); ?>
                                @if(count($alumnis)>0)
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Name</label>
                                </div>
                                <div class="col-md-5">
                                    <label>Relationship</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($alumnis as $alumni)
                            <div id='row_alumni{{$j}}' class="form form-group">
                                <div class="col-md-5">
                                    <input class="form form-control alumni" type="text" name="alumni[{{$j}}]" id='alumni{{$j}}' value='{{$alumni->alumni}}'/>
                                </div>
                                <div class="col-md-5">
                                    <input class="form form-control alumni_relationship" type="text" name="alumni_relationship[{{$j}}]" id='alumni_relationship{{$j}}' value='{{$alumni->relationship}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($j == 0)
                                    <button type="button" name="add_alumni" id="add_alumni" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_alumni" id="{{$j}}" class="btn btn-danger btn_remove btn_remove_alumni">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $j = $j+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Name</label>
                                    <input class="form form-control alumni" type="text" name="alumni[]" id='alumni1'/>
                                </div>
                                <div class="col-md-5">
                                    <label>Relationship</label>
                                    <input class="form form-control alumni_relationship" type="text" name="alumni_relationship[]" id='alumni_relationship1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="alumni" id="add_alumni" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                </div>
                <div class="tab-pane" id="tab_3">
<!--                    <label>Present School N</label>-->
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>Present School Name</label>
                            <input class="form form-control" name='present_school' value="{{old('present_school',$info->present_school)}}" type="text">
                        </div>
                        <div class="col-sm-5">
                            <label>Address</label>
                            <input class="form form-control" name='present_school_address' value="{{old('present_school_address',$info->present_school_address)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-5">
                            <label>Principal</label>
                            <input class="form form-control" name='present_principal' value="{{old('last_principal',$info->present_principal)}}" type="text">
                        </div>
                        <div class="col-sm-5">
                            <label>Guidance Counselor</label>
                            <input class="form form-control" name='present_guidance' value="{{old('present_guidance',$info->present_guidance)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Telephone Number</label>
                            <input class="form form-control" name='present_tel_no' value="{{old('present_tel_no',$info->present_tel_no)}}" type="text">
                        </div>
                    </div>
                    <hr>
<!--                    <label>Primary School</label>-->
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>Preschool</label>
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
<!--                    <label>Grade School</label>-->
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>Elementary</label>
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
<!--                    <label>High School</label>-->
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>High School</label>
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
                    
                    
                    
                    
                    
                    <label>List any honors that the applicant received</label>
                    <?php $a=0; ?>
                        <div  id="dynamic_field_achievement">
                            <!--div class="top-row"-->
                                <?php $awards = \App\BedReceivedHonor::where('idno', $user->idno)->get(); ?>
                                @if(count($awards)>0)
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Achievement Awards</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Grade/Level</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Name of Event</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($awards as $award)
                            <div id='row_achievement{{$a}}' class="form form-group">
                                <div class="col-md-5">
                                    <input class="form form-control achievement" type="text" name="achievement[{{$a}}]" id='achievement1{{$a}}' value='{{$award->achievement}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control achievement_level" type="text" name="achievement_level[{{$a}}]" id='achievement_level{{$a}}' value='{{$award->level}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control number achievement_event" type="text"  name="achievement_event[{{$a}}]" id="achievement_event{{$a}}" value='{{$award->event}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($a == 0)
                                    <button type="button" name="add_achievement" id="add_achievement" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_achievement" id="{{$a}}" class="btn btn-danger btn_remove btn_remove_achievement">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $a = $a+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Achievement Awards</label>
                                        <input class="form form-control achievement" type="text" name="achievement[]" id='achievement1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Grade/Level</label>
                                    <input class="form form-control achievement_level" type="text" name="achievement_level[]" id='achievement_level1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Name of Event</label>
                                    <input class="form form-control number achievement_event" type="text"  name="achievement_event[]" id="achievement_event1"/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_achievement" id="add_achievement" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                    <hr>
                    
                    <label>Did the applicant fail in any subject/s in school? If <u>yes</u>, specify the grade level:</label>
                    <?php $b=0; ?>
                        <div  id="dynamic_field_fail">
                            <!--div class="top-row"-->
                                <?php $fails = \App\BedApplicantFail::where('idno', $user->idno)->get(); ?>
                                @if(count($fails)>0)
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Subject</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Grade/Level</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($fails as $fail)
                            <div id='row_fail{{$b}}' class="form form-group">
                                <div class="col-md-5">
                                    <input class="form form-control fail" type="text" name="fail[{{$b}}]" id='fail1{{$b}}' value='{{$fail->subject}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control fail_level" type="text" name="fail_level[{{$b}}]" id='fail_level{{$b}}' value='{{$fail->level}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($b == 0)
                                    <button type="button" name="add_fail" id="add_fail" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_fail" id="{{$b}}" class="btn btn-danger btn_remove btn_remove_fail">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $b = $b+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Subject</label>
                                        <input class="form form-control fail" type="text" name="fail[]" id='fail1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Grade/Level</label>
                                    <input class="form form-control fail_level" type="text" name="fail_level[]" id='fail_level1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_fail" id="add_fail" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                    <hr>
                    
                    <label>Did the applicant repeat grade/level? If <u>yes</u>, please provide details below:</label>
                    <?php $c=0; ?>
                        <div  id="dynamic_field_repeat">
                            <!--div class="top-row"-->
                                <?php $repeats = \App\BedRepeat::where('idno', $user->idno)->get(); ?>
                                @if(count($repeats)>0)
                            <div class="form form-group">
                                <div class="col-md-3">
                                    <label>Grade/Level</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($repeats as $repeat)
                            <div id='row_repeat{{$c}}' class="form form-group">
                                <div class="col-md-3">
                                    <input class="form form-control repeat_level" type="text" name="repeat_level[{{$c}}]" id='repeat_level{{$c}}' value='{{$repeat->level}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($c == 0)
                                    <button type="button" name="add_repeat" id="add_repeat" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_repeat" id="{{$c}}" class="btn btn-danger btn_remove btn_remove_repeat">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $c = $c+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-3">
                                    <label>Grade/Level</label>
                                    <input class="form form-control repeat_level" type="text" name="repeat_level[]" id='repeat_level1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_repeat" id="add_repeat" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                        <hr>
                        
                        <label>Was the applicant ever placed on probation, suspended, dismissed by any school? If <u>yes</u>, specify offense/s, dates and penalties:</label>
                        <?php $d=0; ?>
                        <div  id="dynamic_field_probation">
                            <!--div class="top-row"-->
                                <?php $probations = \App\BedProbation::where('idno', $user->idno)->get(); ?>
                                @if(count($probations)>0)
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Specify Offense/s</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Date</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Penalty</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($probations as $probation)
                            <div id='row_probation{{$d}}' class="form form-group">
                                <div class="col-md-5">
                                    <input class="form form-control probation" type="text" name="probation[{{$d}}]" id='probation1{{$d}}' value='{{$probation->offense}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control probation_date" type="text" name="probation_date[{{$d}}]" id='probation_date{{$d}}' value='{{$probation->date}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control number probation_penalty" type="text"  name="probation_penalty[{{$d}}]" id="probation_penalty{{$d}}" value='{{$probation->penalty}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($d == 0)
                                    <button type="button" name="add_probation" id="add_probation" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_probation" id="{{$d}}" class="btn btn-danger btn_remove btn_remove_probation">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $d = $d+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Offense</label>
                                        <input class="form form-control probation" type="text" name="probation[]" id='probation1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Date</label>
                                    <input class="form form-control probation_date" type="text" name="probation_date[]" id='probation_date1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Penalty</label>
                                    <input class="form form-control number probation_penalty" type="text"  name="probation_penalty[]" id="probation_penalty1"/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_probation" id="add_probation" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                    <hr>
                    
                    <label>List applicant's extra-curricular activites, including club/organization and specify grade level(e.g. class president, glee club, etc.)</label>
                    <?php $e=0; ?>
                        <div  id="dynamic_field_club">
                            <!--div class="top-row"-->
                                <?php $clubs = \App\BedExtraActivity::where('idno', $user->idno)->get(); ?>
                                @if(count($clubs)>0)
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Club/Organization</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Grade Level</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($clubs as $club)
                            <div id='row_club{{$e}}' class="form form-group">
                                <div class="col-md-5">
                                    <input class="form form-control club" type="text" name="club[{{$e}}]" id='club{{$e}}' value='{{$club->club}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control club_level" type="text" name="club_level[{{$e}}]" id='club_level{{$e}}' value='{{$club->level}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($e == 0)
                                    <button type="button" name="add_club" id="add_club" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_club" id="{{$e}}" class="btn btn-danger btn_remove btn_remove_club">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $e = $e+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Club/Organization</label>
                                    <input class="form form-control club" type="text" name="club[]" id='club1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Grade/Level</label>
                                    <input class="form form-control club_level" type="text" name="club_level[]" id='club_level1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_club" id="add_club" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                        <hr>
                    
                    <label>List applicant's community or Church involvement: (if any)</label>
                    <?php $f=0; ?>
                        <div  id="dynamic_field_involvement">
                            <!--div class="top-row"-->
                                <?php $involvements = \App\BedChurchInvolvement::where('idno', $user->idno)->get(); ?>
                                @if(count($involvements)>0)
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Community/Church Involvement</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Year</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($involvements as $involvement)
                            <div id='row_involvement{{$f}}' class="form form-group">
                                <div class="col-md-5">
                                    <input class="form form-control involvement" type="text" name="involvement[{{$f}}]" id='involvement{{$f}}' value='{{$involvement->involvement}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control involvement_year" type="text" name="involvement_year[{{$f}}]" id='involvement{{$f}}' value='{{$involvement->year}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($f == 0)
                                    <button type="button" name="add_involvement" id="add_involvement" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_involvement" id="{{$f}}" class="btn btn-danger btn_remove btn_remove_involvement">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $f = $f+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-5">
                                    <label>Community/Church Involvement</label>
                                    <input class="form form-control involvement" type="text" name="involvement[]" id='involvement1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Year</label>
                                    <input class="form form-control involvement_year" type="text" name="involvement_year[]" id='involvement_year1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_involvement" id="add_involvement" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                    
                    
                    
                    
                </div>
                <div class="tab-pane" id="tab_4">
                    
                    <label>Has the applicant undergone any form of therapy? If <u>yes</u>, provide details below and specify the kind of therapy received:</label>
                    <?php $g=0; ?>
                        <div  id="dynamic_field_therapy">
                            <!--div class="top-row"-->
                                <?php $therapys = \App\BedUndergoneTherapy::where('idno', $user->idno)->get(); ?>
                                @if(count($therapys)>0)
                            <div class="form form-group">
                                <div class="col-md-8">
                                    <label>Kind of Therapy</label>
                                </div>
                                <div class="col-md-3">
                                    <label>Period of Treatment</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($therapys as $therapy)
                            <div id='row_therapy{{$g}}' class="form form-group">
                                <div class="col-md-8">
                                    <input class="form form-control therapy" type="text" name="therapy[{{$g}}]" id='therapy{{$g}}' value='{{$therapy->therapy}}'/>
                                </div>
                                <div class="col-md-3">
                                    <input class="form form-control therapy_period" type="text" name="therapy_period[{{$g}}]" id='therapy_period{{$g}}' value='{{$therapy->treatment}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($g == 0)
                                    <button type="button" name="add_therapy" id="add_therapy" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_therapy" id="{{$g}}" class="btn btn-danger btn_remove btn_remove_therapy">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $g = $g+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-8">
                                    <label>Kind of Therapy</label>
                                    <input class="form form-control therapy" type="text" name="therapy[]" id='therapy1'/>
                                </div>
                                <div class="col-md-3">
                                    <label>Period of Treatment</label>
                                    <input class="form form-control therapy_period" type="text" name="therapy_period[]" id='therapy_period1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_therapy" id="add_therapy" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                    <hr>
                    
                    <label>List any health/physical limitations which should be taken into consideration in carrying out school activities:</label>
                    <?php $h=0; ?>
                        <div  id="dynamic_field_limitation">
                            <!--div class="top-row"-->
                                <?php $limitations = \App\BedLimitations::where('idno', $user->idno)->get(); ?>
                                @if(count($limitations)>0)
                            <div class="form form-group">
                                <div class="col-md-11">
                                    <label>&nbsp;</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                </div>
                            </div>
                                @foreach($limitations as $limitation)
                            <div id='row_limitation{{$h}}' class="form form-group">
                                <div class="col-md-11">
                                    <input class="form form-control limitation" type="text" name="limitation[{{$h}}]" id='limitation{{$h}}' value='{{$limitation->limitations}}'/>
                                </div>
                                <div class="col-md-1">
                                    @if($h == 0)
                                    <button type="button" name="add_limitation" id="add_limitation" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_limitation" id="{{$h}}" class="btn btn-danger btn_remove btn_remove_limitation">X</button>
                                    @endif
                                </div>
                            </div>
                                
                                <?php $h = $h+1; ?>
                                @endforeach
                                @else
                            <div class="form form-group">
                                <div class="col-md-11">
                                    <label>&nbsp;</label>
                                    <input class="form form-control limitation" type="text" name="limitation[]" id='limitation1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Add</label>
                                    <button type="button" name="add_limitation" id="add_limitation" class="btn btn-success"> + </button>
                                </div>
                            </div>
                                @endif
                        </div>
                    
                </div>
                <div class="tab-pane" id="tab_5">
                    <?php $ctrrequirements = \App\CtrBedRequirement::where('level', $info->applied_for)->first(); ?>
                    <?php $bedrequirements = \App\BedRequirement::where('idno', $user->idno)->first(); ?>
                    <div class="form-group">
                        @if($ctrrequirements->psa >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='psa' @if($bedrequirements->psa == 1)checked=''@endif><label>&nbsp;Original copy and two (2) clear photocopies of Philippine Statistics Authority (PSA) Birth Certificate</label>
                        </div>
                        @endif
                        @if($ctrrequirements->recommendation_form >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='recommendation_form' @if($bedrequirements->recommendation_form == 1)checked=''@endif><label>&nbsp;Recommendation Forms (duly accomplished by Guidance/ Class Adviser and Principal)</label>
                        </div>
                        @endif
                        @if($ctrrequirements->baptismal_certificate >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='baptismal_certificate' @if($bedrequirements->baptismal_certificate == 1)checked=''@endif><label>&nbsp;One (1) clear photocopy of Baptismal Certificate</label>
                        </div>
                        @endif
                        @if($ctrrequirements->passport_size_photo >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='passport_size_photo' @if($bedrequirements->passport_size_photo == 1)checked=''@endif><label>&nbsp;Four (4) passport size recent colored photos (computer printed & cut-outs are not accepted)</label>
                        </div>
                        @endif
<!--                        @if($ctrrequirements->progress_report_card >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='progress_report_card' @if($bedrequirements->progress_report_card == 1)checked=''@endif><label>&nbsp;Progress Report Cards</label>
                        </div>
                        @endif-->
                        @if($ctrrequirements->currentprevious_report_card >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='currentprevious_report_card' @if($bedrequirements->currentprevious_report_card == 1)checked=''@endif><label>&nbsp;One (1) clear photocopies of PREVIOUS and CURRENT report cards</label>
                        </div>
                        @endif
                        @if($ctrrequirements->narrative_assessment_report >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='narrative_assessment_report' @if($bedrequirements->narrative_assessment_report == 1)checked=''@endif><label>&nbsp;One (1) clear photocopies of either Certificate of Attendance or Narrative Assessment Report.</label>
                        </div>
                        @endif
                        @if($ctrrequirements->essay >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='essay' @if($bedrequirements->essay == 1)checked=''@endif><label>&nbsp;Essay for Parents</label>
                        </div>
                        @endif
                        @if($ctrrequirements->question_parent >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='question_parent' @if($bedrequirements->question_parent == 1)checked=''@endif><label>&nbsp;Questionnaire for Parents</label>
                        </div>
                        @endif
                        @if($ctrrequirements->question_student >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='question_student' @if($bedrequirements->question_student == 1)checked=''@endif><label>&nbsp;Questionnaire for Student Applicant</label>
                        </div>
                        @endif
                        @if($ctrrequirements->dpa >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='dpa' @if($bedrequirements->dpa == 1)checked=''@endif><label>&nbsp;AC Student Privacy Notice and Consent Form</label>
                        </div>
                        @endif
                        @if($ctrrequirements->adviser_guidance_reco >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='adviser_guidance_reco' @if($bedrequirements->adviser_guidance_reco == 1)checked=''@endif><label>&nbsp;Class Adviser/Guidance Counselor's Recommendation</label>
                        </div>
                        @endif
                        @if($ctrrequirements->principal_reco >= 1)
                        <div class="col-sm-12">
                            <input type='checkbox'  name='principal_reco' @if($bedrequirements->principal_reco == 1)checked=''@endif><label>&nbsp;Principal's Recommendation Form</label>
                        </div>
                        @endif
                        <div class="col-sm-12"><hr><strong>For Foreign Students</strong><br>
                            <input type='checkbox'  name='acr' @if($bedrequirements->acr == 1)checked=''@endif><label>&nbsp;Alien Certificate of Registration (ACR)</label>
                        </div>
                        <div class="col-sm-12">
                            <input type='checkbox'  name='photocopy_passport' @if($bedrequirements->passport == 1)checked=''@endif><label>&nbsp;Photocopy of Passport</label>
                        </div>
                        <div class="col-sm-12">
                            <input type='checkbox'  name='visa_parent' @if($bedrequirements->visa_parent == 1)checked=''@endif><label>&nbsp;Visa/ Working Permit of Parents</label>
                        </div>
                        <div class="col-sm-12">
                            <input type='checkbox'  name='photocopy_of_dual' @if($bedrequirements->photocopy_of_dual == 1)checked=''@endif><label>&nbsp;Photocopy of dual citizenship passports (for dual citizenship)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($status->status == env("FOR_APPROVAL") || $status->status <= env("ENROLLED"))
        @if($status->status <= env("ENROLLED"))
        <div class="col-sm-3">
        <input type="submit" value='Save' class='form-control btn btn-primary'>
        </div>
        @elseif($status->status == env("FOR_APPROVAL"))
            @if($is_lock == 0)
            <div class="col-sm-3">
            <input type="submit" value='Save' class='form-control btn btn-primary'>
            </div>
            <div class="col-sm-3"><a href="{{url('admissionbeds', array('disapprove_application', $user->idno))}}"><button onclick="if (confirm('Do you really want to REGRET Applicant?'))
                    return true;
                else
                    return false;" type='button' class='btn btn-danger col-sm-12'>Regret Application</button></a></div>
            <div class="col-sm-6"><a href="{{url('admissionbed', array('approve_application', $user->idno))}}"><button onclick="if (confirm('Do you really want to APPROVED Applicant?'))
                    return true;
                else
                    return false;"  type='button' class='btn btn-success col-sm-12'>Approve Application</button></a></div>
            @else
            <div class="col-sm-3">
            <input type="submit" value='Save' class='form-control btn btn-primary'>
            </div>
            <div class="col-sm-3"><a href="{{url('admissionbeds', array('disapprove_application', $user->idno))}}"><button onclick="if (confirm('Do you really want to REGRET Applicant?'))
                    return true;
                else
                    return false;" type='button' class='btn btn-danger col-sm-12'>Regret Application</button></a></div>
            <div class="col-sm-6 alert alert-danger">Assign Schedule to Approve Applicant</div>
            @endif
        @endif
        @endif
        <br/>
        <br/>
        @if($status->status == env("FOR_APPROVAL"))
        <div class="col-md-6">
            <a href="{{url('admissionbed', array('not_yet_approval', $user->idno))}}">
                <button type='button' class='btn btn-info col-sm-12'>Not Yet for Approval</button></a>
        </div>
        <div class="col-md-6">
            <a href="{{url('admissionbed', array('print_info', $user->idno))}}">
                <button type='button' class='btn btn-warning col-sm-12'>Print Student Information</button></a>
        </div>
        
        @else
        @if($status->status == env("REGRET_FINAL"))
            @if(Auth::user()->idno != "acruz")
            <div class="col-md-6">
                <a href="{{url('admissionbed', array('change_status_application', $user->idno))}}">
                    <button type='button' class='btn btn-danger col-sm-12' onclick="if (confirm('Do you really want to change the status of Applicant?')) return true; else return false;">Change Application Status</button>
                </a>
            </div>
            @endif
        <div class="col-md-6">
            <a href="{{url('admissionbed', array('print_info', $user->idno))}}">
                <button type='button' class='btn btn-warning col-sm-12'>Print Student Information</button></a>
        </div>
        @elseif($status->status == env("PRE_REGISTERED"))
        <div class="col-md-6 col-md-offset-6">
            <a href="{{url('admissionbed', array('print_info', $user->idno))}}">
                <button type='button' class='btn btn-warning col-sm-12'>Print Student Information</button></a>
        </div>
        <br>
        <br>
        <div class="col-md-6 col-md-offset-6">
            <a href="{{url('admissionbed', array('resend_access', $user->idno))}}">
                <button type='button' class='btn btn-danger col-sm-12'>Re-send Email Access Confirmation</button></a>
        </div>
        @else
        <div class="col-md-6 col-md-offset-6">
            <a href="{{url('admissionbed', array('print_info', $user->idno))}}">
                <button type='button' class='btn btn-warning col-sm-12'>Print Student Information</button></a>
        </div>
        @endif
        @endif
        
    </div>
    </form>
</div>
<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter New Password : </h4>
              </div>
                <form method="post" action="{{url('/bedregistrar', array('resetpassword'))}}">
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
function update_testing(id){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['testing_id'] = id;
    $.ajax({
        type: "GET",
        url: "/ajax/admissionbed/update_schedule",
        data: array,
        success: function (data) {
        }

    });
}


function update_interview(id){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['interview_id'] = id;
    $.ajax({
        type: "GET",
        url: "/ajax/admissionbed/update_interview",
        data: array,
        success: function (data) {
        }

    });
}


function update_group(id){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['interview_id'] = id;
    $.ajax({
        type: "GET",
        url: "/ajax/admissionbed/update_group",
        data: array,
        success: function (data) {
        }

    });
}


function update_individual(id){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['interview_id'] = id;
    $.ajax({
        type: "GET",
        url: "/ajax/admissionbed/update_individual",
        data: array,
        success: function (data) {
        }

    });
}
function change_applied_for(level,strand,idno,type){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['level'] = level;
    array['strand'] = strand;
    array['type'] = type;
    $.ajax({
        type: "GET",
        url: "/ajax/admissionbed/change_applied_for",
        data: array,
        success: function (data) {
        }

    });
}
</script>
<script>
    var a="{{$a-1}}";
    var b="{{$b-1}}";
    var c="{{$c-1}}";
    var d="{{$d-1}}";
    var e="{{$e-1}}";
    var f="{{$f-1}}";
    var g="{{$g-1}}";
    var h="{{$h-1}}";
    var i="{{$i-1}}";
    var j="{{$j-1}}";
    $(document).ready(function () {
        $('#add_achievement').click(function(){

           a++;
           $('#dynamic_field_achievement').append('<div id="row_achievement'+a+'" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control achievement"       type="text" name="achievement[]"       id="achievement'+a+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control achievement_level" type="text" name="achievement_level[]" id="achievement_level'+a+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control achievement_event" type="text" name="achievement_event[]" id="achievement_event'+a+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_achievement"  id="'+a+'" class="btn btn-danger btn_remove btn_remove_achievement">X</a></div></div>');


           });
        $('#dynamic_field_achievement').on('click','.btn_remove_achievement', function(){
            var button_id = $(this).attr("id");
            $("#row_achievement"+button_id+"").remove();
            a--;
        });
        
        $('#add_fail').click(function(){

           b++;
           $('#dynamic_field_fail').append('<div id="row_fail'+b+'" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control fail"       type="text" name="fail[]"       id="fail'+b+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control fail_level" type="text" name="fail_level[]" id="fail_level'+b+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_fail"  id="'+b+'" class="btn btn-danger btn_remove btn_remove_fail">X</a></div></div>');


           });
        $('#dynamic_field_fail').on('click','.btn_remove_fail', function(){
            var button_id = $(this).attr("id");
            $("#row_fail"+button_id+"").remove();
            b--;
        });
        
        $('#add_repeat').click(function(){

           c++;
           $('#dynamic_field_repeat').append('<div id="row_repeat'+c+'" class="form form-group">\n\
           <div class="col-md-3"><input class="form form-control repeat_level" type="text" name="repeat_level[]" id="fail_level'+c+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_repeat"  id="'+c+'" class="btn btn-danger btn_remove btn_remove_repeat">X</a></div></div>');


           });
        $('#dynamic_field_repeat').on('click','.btn_remove_repeat', function(){
            var button_id = $(this).attr("id");
            $("#row_repeat"+button_id+"").remove();
            c--;
        });
        
        $('#add_probation').click(function(){

           d++;
           $('#dynamic_field_probation').append('<div id="row_probation'+d+'" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control probation"       type="text" name="probation[]"       id="probation'+d+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control probation_date" type="text" name="probation_date[]" id="probation_date'+d+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control probation_penalty" type="text" name="probation_penalty[]" id="probation_penalty'+d+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_probation"  id="'+d+'" class="btn btn-danger btn_remove btn_remove_probation">X</a></div></div>');


           });
        $('#dynamic_field_probation').on('click','.btn_remove_probation', function(){
            var button_id = $(this).attr("id");
            $("#row_probation"+button_id+"").remove();
            d--;
        });
        
        $('#add_club').click(function(){

           e++;
           $('#dynamic_field_club').append('<div id="row_club'+e+'" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control club"       type="text" name="club[]"       id="club'+e+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control club_level" type="text" name="club_level[]" id="club_level'+e+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_club"  id="'+e+'" class="btn btn-danger btn_remove btn_remove_club">X</a></div></div>');


           });
        $('#dynamic_field_club').on('click','.btn_remove_club', function(){
            var button_id = $(this).attr("id");
            $("#row_club"+button_id+"").remove();
            e--;
        });
        
        $('#add_involvement').click(function(){

           f++;
           $('#dynamic_field_involvement').append('<div id="row_involvement'+f+'" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control involvement"       type="text" name="involvement[]"       id="involvement'+f+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control involvement_year" type="text" name="involvement_year[]" id="involvement_year'+f+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_involvement"  id="'+f+'" class="btn btn-danger btn_remove btn_remove_involvement">X</a></div></div>');


           });
        $('#dynamic_field_involvement').on('click','.btn_remove_involvement', function(){
            var button_id = $(this).attr("id");
            $("#row_involvement"+button_id+"").remove();
            f--;
        });
        
        $('#add_therapy').click(function(){

           g++;
           $('#dynamic_field_therapy').append('<div id="row_therapy'+g+'" class="form form-group">\n\
           <div class="col-md-8"><input class="form form-control therapy"       type="text" name="therapy[]"       id="therapy'+g+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control therapy_period" type="text" name="therapy_period[]" id="therapy_period'+g+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_therapy"  id="'+g+'" class="btn btn-danger btn_remove btn_remove_therapy">X</a></div></div>');


           });
        $('#dynamic_field_therapy').on('click','.btn_remove_therapy', function(){
            var button_id = $(this).attr("id");
            $("#row_therapy"+button_id+"").remove();
            g--;
        });
        
        $('#add_limitation').click(function(){

           h++;
           $('#dynamic_field_limitation').append('<div id="row_limitation'+h+'" class="form form-group">\n\
           <div class="col-md-11"><input class="form form-control limitation"       type="text" name="limitation[]"       id="limitation'+h+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_limitation"  id="'+h+'" class="btn btn-danger btn_remove btn_remove_limitation">X</a></div></div>');


           });
        $('#dynamic_field_limitation').on('click','.btn_remove_limitation', function(){
            var button_id = $(this).attr("id");
            $("#row_limitation"+button_id+"").remove();
            h--;
        });
        
        $('#add_sibling').click(function(){

           i++;
           $('#dynamic_field_sibling').append('<div id="row_sibling'+i+'" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control sibling"       type="text" name="sibling[]"       id="sibling'+i+'"/></div>\n\
           <div class="col-md-1"><input class="form form-control sibling_age"       type="text" name="sibling_age[]"       id="sibling_age'+i+'"/></div>\n\
           <div class="col-md-2"><input class="form form-control sibling_level"       type="text" name="sibling_level[]"       id="sibling_level'+i+'"/></div>\n\
           <div class="col-md-3"><input class="form form-control sibling_school"       type="text" name="sibling_school[]"       id="sibling_school'+i+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_sibling"  id="'+i+'" class="btn btn-danger btn_remove btn_remove_sibling">X</a></div></div>');


           });
        $('#dynamic_field_sibling').on('click','.btn_remove_sibling', function(){
            var button_id = $(this).attr("id");
            $("#row_sibling"+button_id+"").remove();
            i--;
        });
        
        $('#add_alumni').click(function(){

           j++;
           $('#dynamic_field_alumni').append('<div id="row_alumni'+j+'" class="form form-group">\n\
           <div class="col-md-5"><input class="form form-control alumni"       type="text" name="alumni[]"       id="alumni'+j+'"/></div>\n\
           <div class="col-md-5"><input class="form form-control alumni_relationship"       type="text" name="alumni_relationship[]"       id="alumni_relationship'+j+'"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_alumni"  id="'+j+'" class="btn btn-danger btn_remove btn_remove_alumni">X</a></div></div>');


           });
        $('#dynamic_field_alumni').on('click','.btn_remove_alumni', function(){
            var button_id = $(this).attr("id");
            $("#row_alumni"+button_id+"").remove();
            j--;
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
