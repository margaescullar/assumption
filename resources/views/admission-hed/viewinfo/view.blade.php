<?php $regions = \App\CtrRegion::all(); ?>
<?php
if (Auth::user()->accesslevel == env('ADMISSION_HED')) {
    $layout = "layouts.appadmission-hed";
} else {
    $layout = "layouts.appreg_college";
}
if ($adhedinfo->applying_for == "Senior High School") {
    $ctr_academic_program = \App\CtrAcademicProgram::SelectRaw("distinct strand")->where('academic_code', 'SHS')->get();
} elseif ($adhedinfo->applying_for == "College") {
    $ctr_academic_program = \App\CtrAcademicProgram::SelectRaw("distinct program_name")->where('academic_type', 'College')->get();
} elseif ($adhedinfo->applying_for == "Graduate School") {
    $ctr_academic_program = \App\CtrAcademicProgram::SelectRaw("distinct program_name")->where('academic_type', 'Masters Degree')->get();
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

<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
<section class="content-header">
    <h1>
        Personal Information
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="{{url('registrar_college', array('view', $idno))}}">View Information</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <form class="form-horizontal" method='post' action='{{url('/admission_hed/update_info')}}'>
            {{ csrf_field() }}
            <div class="col-sm-12">
                <?php $testing_schedules = \App\HedTestingSchedule::where('is_remove',0)->orderBy('datetime', 'dsc')->get(); ?>
                <?php $testing = \App\HedTestingStudent::where('idno',$idno)->first(); ?>
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
            </div>
            <div class="col-sm-12">
                @if(Session::has('message'))
                <div class="alert alert-success">{{Session::get('message')}}</div>
                @endif
                @if(Session::has('danger'))
                <div class="alert alert-danger">{{Session::get('danger')}}</div>
                @endif

                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif               
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><span class='fa fa-edit'></span>Enrollment Permit</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                    
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Student No.</label>
                                <input class="form form-control" name="idno" placeholder="ID Number" value="{{old('idno',$users->idno)}}" readonly="">
                            </div>              
                            <div class="col-sm-3">
                                <label>Student Status</label>
                                <select class="form form-control" name='student_status' type="text">                             
                                    <option value='2'@if($adhedinfo->student_status  == '2') selected='' @else @endif>Select Status</option>
                                    <option value='0'@if($adhedinfo->student_status  == '0') selected='' @else @endif>Not Approved</option>
                                    <option value='1' @if($adhedinfo->student_status  == '1') selected='' @else @endif>Approved</option>
                                </select>                                
                            </div>                              
                            <div class="col-sm-3">
                                <label>Tagged As</label>
                                <select class="form form-control" name='tagged_as' type="text">                              
                                    <option value='1'@if($adhedinfo->tagged_as == '1') selected='' @else @endif>Freshman</option>
                                    <option value='2' @if($adhedinfo->tagged_as == '2') selected='' @else @endif>Transferee</option>
                                    <option value='3' @if($adhedinfo->tagged_as == '3') selected='' @else @endif>Cross Enrollee</option>
                                </select>                                
                            </div>              
                            <div class='col-sm-3'>
                                <label>School Year</label>
                                <select class="form form-control" name="applying_for_sy">
                                    <option value='2018' @if($adhedinfo->applying_for_sy == '2018') selected='' @else @endif>2018-2019</option>
                                    <option value='2019' @if($adhedinfo->applying_for_sy == '2019') selected='' @else @endif>2019-2020</option>
                                    <option value='2020' @if($adhedinfo->applying_for_sy == '2020') selected='' @else @endif>2020-2021</option>
                                    <option value='2021' @if($adhedinfo->applying_for_sy == '2021') selected='' @else @endif>2021-2022</option>
                                </select>                           
                            </div>                                                                  
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Applied For</label>
                                <input class="form form-control" name='applying_for' value="{{old('applying_for', $adhedinfo->applying_for)}}" type="text" readonly="">
                            </div>   
                            <div class='col-sm-6'id='programForm'>
                                <label>Major</label>
                                <div id='displayProgram'>                                   
                                    @if($adhedinfo->applying_for == "Senior High School")
                                    <select class="form form-control" name="strand" > 
                                        @foreach($ctr_academic_program as $academicprogram)
                                        <option @if($adhedinfo->strand == $academicprogram->strand) selected='' @else @endif>{{$academicprogram->strand}}</option>
                                        @endforeach
                                    </select>
                                    @else  
                                    <select class="form form-control" name="program_name"> 
                                        @foreach($ctr_academic_program as $academicprogram)
                                        <option @if($adhedinfo->program_name == $academicprogram->program_name) selected='' @else @endif>{{$academicprogram->program_name}}</option>
                                        @endforeach
                                    </select>
                                    @endif 
                                </div>
                            </div>             
                            <div class='col-sm-3'> 
                                <label>Reinforcement Summer Class</label>    
                                <select class="form form-control" name="summer_classes">
                                    <option value='0'>Select Class</option>
                                    <option value='1' @if($adhedinfo->summer_classes == '1') selected='' @else @endif>Both</option>
                                    <option value='3' @if($adhedinfo->summer_classes == '3') selected='' @else @endif>English Plus</option>
                                    <option value='2' @if($adhedinfo->summer_classes == '2') selected='' @else @endif>Math Plus</option>
                                    <option value='4' @if($adhedinfo->summer_classes == '4') selected='' @else @endif>None</option>
                                </select>    
                            </div>                            
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Admission Status</label>
                                <select class="form form-control" name='admission_status' id="admission_status" type="text">
                                    <option value='Regular' @if($adhedinfo->admission_status == 'Regular') selected='' @else @endif>Regular</option>
                                    <option value='Deload'@if($adhedinfo->admission_status == 'Deload') selected='' @else @endif>Deload</option>
                                    <option value='Probationary'@if($adhedinfo->admission_status == 'Probationary') selected='' @else @endif>Probationary</option>
                                    <option value='Scholar' @if($adhedinfo->admission_status == 'Scholar') selected='' @else @endif>Scholar</option>
                                    <option value='Audit' @if($adhedinfo->admission_status == 'Audit') selected='' @else @endif>Audit</option>
                                </select>      
                            </div>                      
                            <div class="col-sm-3" id='assumption_scholar'>
                                <label>Assumption Scholar</label>
                                <input readonly='' class="form form-control" name='assumption_scholar' type="text" value='{{$adhedinfo->assumption_scholar}}'>
                            </div>   
                            <div class="col-sm-3" id='partner_scholar'>
                                <label>Partner Scholar</label>
                                <select class="form form-control" name='partner_scholar' type="text">                              
                                    <option value=''>Select Scholarship*</option>
                                    <option value='1' @if($adhedinfo->partner_scholar == '1') selected='' @else @endif>Aboitiz</option>
                                    <option value='2' @if($adhedinfo->partner_scholar == '2') selected='' @else @endif>OPPA</option>
                                </select>      
                            </div>                                  
                            <div class="col-sm-3" id='probationary'>
                                <label>Agreement Contract</label>
                                <select class="form form-control" name='agreement' type="text">                              
                                    <option value=''>Select Agreement*</option>
                                    <option value='Academic' @if($adhedinfo->agreement == 'Academic') selected='' @else @endif>Academic</option>
                                    <option value='AcademicBehave' @if($adhedinfo->agreement == 'AcademicBehave') selected='' @else @endif>Academic/Behavioral</option>
                                    <option value='AcademicMod' @if($adhedinfo->agreement == 'AcademicMod') selected='' @else @endif>Academic/Modelling</option>
                                    <option value='AcaBehaDeload' @if($adhedinfo->agreement == 'AcaBehaDeload') selected='' @else @endif>Academic/Behavioral/Deload</option>
                                    <option value='AcaDeload' @if($adhedinfo->agreement == 'AcaDeload') selected='' @else @endif>Academic/Deload</option>
                                    <option value='AcaTheoDeload' @if($adhedinfo->agreement == 'AcaTheoDeload') selected='' @else @endif>Academic/Deload/Theology</option>
                                    <option value='Audit' @if($adhedinfo->agreement == 'Audit') selected='' @else @endif>Audit</option>
                                    <option value='Behavioral' @if($adhedinfo->agreement == 'Behavioral') selected='' @else @endif>Behavioral</option>
                                    <option value='BehaDeload' @if($adhedinfo->agreement== 'BehaDeload') selected='' @else @endif>Behavioral/Deload</option>
                                    <option value='BehavioralMod' @if($adhedinfo->agreement == 'BehavioralMod') selected='' @else @endif>Behavioral/Modelling</option>                                    
                                    <option value='BehavioralModDeload' @if($adhedinfo->agreement == 'BehavioralModDeload') selected='' @else @endif>Behavioral/Modelling/Deload</option>  
                                    <option value='BehavioralTrans' @if($adhedinfo->agreement== 'BehavioralTrans') selected='' @else @endif>Behavioral/Transferee</option>
                                    <option value='Deload' @if($adhedinfo->agreement == 'Deload') selected='' @else @endif>Deload</option>
                                    <option value='Deload/Trans' @if($adhedinfo->agreement == 'Deload/Trans') selected='' @else @endif>Deload/Transferee</option>
                                    <option value='ModellingTrans' @if($adhedinfo->agreement == 'ModellingTrans') selected='' @else @endif>Modelling/Transferee</option>
                                    <option value='None' @if($adhedinfo->agreement == 'None') selected='' @else @endif>None</option>
                                    <option value='Transferee' @if($adhedinfo->agreement == 'Transferee') selected='' @else @endif>Transferee</option>
                                    <option value='Special_Learning' @if($adhedinfo->agreement== 'Special_Learning') selected='' @else @endif>Special Learning</option>
                                    <option value='Theology' @if($adhedinfo->agreement == 'Theology') selected='' @else @endif>Theology</option>                               
                                </select>      
                            </div>  
                        </div>  
                        <hr>
                        <!--Checklists-->  
                        <div class="form-group">
                            <div class="col-sm-6">
                                <h4 class="box-title"><span class='fa fa-edit'></span>Checklist of Requirements</h4>
                            </div>
                        </div>                     
                        <div class="form-group">
                            <div class="col-sm-4">    
                                <label>Freshman / Transferee</label><br>
                                <input type="checkbox" name='admission_agreement' value='1' @if($admissionreq->admission_agreement == '1') checked='' @else @endif> Admissions Agreement<br>
                                <input type="checkbox" name='course_desc' value='1' @if($admissionreq->course_desc == '1') checked='' @else @endif> Course Description<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>Labtest</label><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name='cbc' value='1' @if($admissionreq->cbc == '1') checked='' @else @endif> Complete Blood Count<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name='bt' value='1' @if($admissionreq->bt == '1') checked='' @else @endif> Blood Typing<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name='x_ray' value='1' @if($admissionreq->x_ray == '1') checked='' @else @endif> Chest X-Ray<br>  
                                <input type="checkbox" name='medical_clearance'value='1' @if($admissionreq->medical_clearance == '1') checked='' @else @endif> Medical Clearance<br>
                            </div>
                            <div class="col-sm-4"><br>
                                <input type="checkbox" name='birth_certificate'value='1' @if($admissionreq->birth_certificate == '1') checked='' @else @endif> Original PSA Birth Certificate<br>
                                <input type="checkbox" name='form138' value='1' @if($admissionreq->form138 == '1') checked='' @else @endif> Original Report Card (Form 138)<br>
                                <input type="checkbox" name='school_rec' value='1' @if($admissionreq->school_rec == '1') checked='' @else @endif> Original School Record Form( 137)<br>
                                <input type="checkbox" name='tor' value='1' @if($admissionreq->tor == '1') checked='' @else @endif> Original Transcript of Records<br>
                                <input type="checkbox" name='parent_partnership' value='1' @if($admissionreq->parent_partnership  == '1') checked='' @else @endif> Parent Partnership<br>
                                <input type="checkbox" name='photocopy_diploma' value='1' @if($admissionreq->photocopy_diploma == '1') checked='' @else @endif> Photocopy of High School Diploma<br>
                                <input type="checkbox" name='honor_dismiss' value='1' @if($admissionreq->honor_dismiss == '1') checked='' @else @endif> Honorable Dismissal/Transfer Credential<br>                      
                                <label>Remarks:</label>    
                                <input class="form form-control" name="remarks" placeholder="Remarks" type="text" value='{{old('remarks', $admissionreq->remarks)}}'>
                            </div>  
                            <div class="col-sm-4">
                                <label>Foreign/ Dual Citizens</label><br>
                                <input type="checkbox" name='passport' value='1' @if($admissionreq->passport == '1') checked='' @else @endif> Copy of passport bio page, latest admission authorized stay<br>
                                <input type="checkbox" name='visa' value='1' @if($admissionreq->visa == '1') checked='' @else @endif> Student Visa/ Study Permit/ Recognition from Bureau of Immigration/ Deportation<br>           
                                <hr>
                                <label>Married</label><br>
                                <input type="checkbox" name='child_birth_cert' value='1' @if($admissionreq->child_birth_cert == '1') checked='' @else @endif> Birth Certificate of a child</input><br>
                                <input type="checkbox" name='marriage_contract' value='1' @if($admissionreq->marriage_contract == '1') checked='' @else @endif> Marriage Contract</input><br>                          
                            </div>
                        </div>                       
                    </div>    
                </div>
            </div>    
            <!--Personal Info-->            
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <h4 class="box-title"><span class='fa fa-edit'></span>Pre-Application Form</h4>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>                     
                        </div>
                        <div class="box-body">
                            <div class="form-group">                            
                                <div class="col-sm-3">
                                    <label>Name</label>
                                    <input class="form form-control" name='firstname' placeholder='First  Name' value="{{old('firstname', $users->firstname)}}" type="text">
                                </div>
                                <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                    <input class="form form-control" name='middlename' placeholder='Middle Name' value="{{old('middlename', $users->middlename)}}" type="text">
                                </div>
                                <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                    <input class="form form-control" name='lastname' placeholder='Last Name*' value="{{old('lastname', $users->lastname)}}" type="text">
                                </div>
                            </div>
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
                                    <input class="form form-control" name='tel_no' id="tel_no" placeholder='Telephone: (02)____-____' value="{{old('tel_no', $studentinfos->tel_no)}}" type="text">
                                </div>
                                <div class="col-sm-4">
                                    <label>&nbsp;</label>
                                    <input class="form form-control" name='cell_no' id="cell_no" placeholder='Cellphone: (0917)___-____' value="{{old('cell_no', $studentinfos->cell_no)}}" type="text">
                                </div>
                                <div class="col-sm-4">
                                    <label>Email</label>
                                    <input class="form form-control" name='email' placeholder='Email Address*' value="{{old('email',$users->email)}}" type="email">                           
                                </div>
                            </div>                        
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <label>Birthday</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-birthday-cake"></i>
                                        </div>
                                        <input class="form form-control" name='birthdate' value="{{old('birthdate',$studentinfos->birthdate)}}" type="date">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label>Birth Place</label>
                                    <input class="form form-control" name='place_of_birth' value="{{old('place_of_birth',$studentinfos->place_of_birth)}}" placeholder='Place of Birth' type="text">
                                </div>         
                            </div>                            
                            <div class="form-group">    
                                <div class="col-sm-3">
                                    <label>Citizenship</label>
                                    <select class="form form-control" name='is_foreign' value="{{old('is_alien')}}" type="text">
                                        <option value=''>Select Local/Foreign</option>
                                        <option value='0' @if($users->is_foreign == '0') selected='' @else @endif >Filipino</option>
                                        <option value='1' @if ($users->is_foreign == '1') selected='' @else @endif >Foreigner</option>
                                        <option value='2' @if ($users->is_foreign == '2') selected='' @else @endif >Dual Citizen</option>
                                    </select>
                                </div>                            
                                <div class="col-sm-3">
                                    <label>&nbsp;</label>
                                    <input class="form form-control" name='specify_citizenship' placeholder='Specified Citizenship' value="{{old('specify_citizenship', $adhedinfo->specify_citizenship)}}" type="text">
                                </div>                                   
                                <div class="col-sm-3">
                                    <label>Civil Status</label>
                                    <select class="form form-control" name='civil_status' placeholder='Civil Status' type="text">
                                        <option value="">Select Civil Status</option>
                                        <option value="Single" @if($studentinfos->civil_status == 'Single') selected='' @else @endif>Single</option>
                                        <option value="Married" @if($studentinfos->civil_status == 'Married') selected='' @else @endif>Married</option>
                                        <option value="Divorced" @if($studentinfos->civil_status == 'Divorced') selected='' @else @endif>Divorced</option>
                                        <option value="Widowed" @if($studentinfos->civil_status == 'Widowed') selected='' @else @endif>Widowed</option>
                                    </select>   
                                </div>
                            </div>  
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Last School Attended</label>
                                    <input class="form form-control" name='last_school_attended' placeholder='Last School Attended' value="{{old('last_school_attended', $studentinfos->last_school_attended)}}" type="text">
                                </div> 
                                <div class="col-sm-6">
                                    <label>School Address</label>
                                    <input class="form form-control" name='last_school_address' placeholder='School Address' value="{{old('last_school_address', $studentinfos->last_school_address)}}" type="text">
                                </div> 
                            </div>
<!--                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label>Guardian Type</label>
                                    <select class="form form-control" name="guardian_type" type="text">
                                        <option value='0'>Select Guardian Type</option>
                                        <option value='Father' @if($adhedinfo->guardian_type == 'Father') selected='' @else @endif>Father</option>
                                        <option value='Mother' @if($adhedinfo->guardian_type == 'Mother') selected='' @else @endif>Mother</option>
                                        <option value='Guardian' @if($adhedinfo->guardian_type == 'Guardian') selected='' @else @endif>Guardian</option>
                                    </select>
                                </div>                                    
                            </div>    
                            <div class="form-group"> 
                                <div class="col-sm-4">
                                    <label>Emergency Contact Person</label>
                                    <input class="form form-control" name='guardian_name' placeholder='Complete Name' value="{{old('guardian_name', $adhedinfo->guardian_name)}}" type="text">
                                </div>
                                <div class="col-sm-4">
                                    <label>&nbsp;</label>
                                    <input class="form form-control" name='guardian_contact' placeholder='Landline / Tel No.' value="{{old('guardian_contact', $adhedinfo->guardian_contact)}}" type="text">
                                </div>
                                <div class="col-sm-4">
                                    <label>&nbsp;</label>
                                    <input class="form form-control" name='guardian_email' placeholder='Guardian Email Address' value="{{old('guardian_email', $adhedinfo->guardian_email)}}" type="email">
                                </div>    
                            </div>-->
                            <hr>
                            <div class="form-group">
                                    <div class="col-sm-6">
                                        <label>Do you have now, or in the past, a condition/s which require or requires you to see a professional?*</label>
                                        <select class="form form-control" id="see_professional" name='see_professional' type="text">
                                            <option></option>
                                            <option value='0' @if($adhedinfo->see_professional == 0) selected='' @endif>None</option>
                                            <option value='10' @if($adhedinfo->see_professional == 10) selected='' @endif>Yes</option>
                                        </select>    
                                    </div>
                                </div>
                            <div class="form-group">            
                                <div class="col-sm-12" id="conditionType" name='conditionType' type='text'>
                                    <label>Condition:</label><br>
                                    <input type='checkbox' name='medical' value='1' @if($adhedinfo->medical == '1') checked='' @else @endif> Medical |
                                           <input type='checkbox' name='psychological' value='1' @if($adhedinfo->psychological == '1') checked='' @else @endif> Psychological |
                                           <input type='checkbox' name='learning_disability' value='1' @if($adhedinfo->learning_disability == '1') checked='' @else @endif> Learning Disability |
                                           <input type='checkbox' name='emotional' value='1' @if($adhedinfo->emotional == '1') checked='' @else @endif> Emotional |
                                           <input type='checkbox' name='social' value='1' @if($adhedinfo->social == '1') checked='' @else @endif> Social |
                                           <input type='checkbox' name='others' value='1' @if($adhedinfo->others == '1') checked='' @else @endif> Others 
                                </div>
                            </div>    
                            <div class="form-group">    
                                <div class="col-sm-6" id="specify_condition">
                                    <label>Please specify condition and type of professional seen</label>
                                    <input class="form form-control" placeholder="Specify*" name='specify_condition' type="text" value='{{old('specify_condition', $adhedinfo->specify_condition)}}'>
                                </div>        
                            </div>
                            <hr>
                            <label style="background-color: gray">WE WANT TO KNOW MORE ABOUT YOU...</label>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Interest/Hobbies</label>
                        <input class="form form-control" name='interest' value="{{old('interest',$about->interest)}}" type="text">
                    </div>
                    <div class="col-sm-6">
                        <label>Goals</label>
                        <input class="form form-control" name='goals' value="{{old('goals',$about->goals)}}" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Challenges and Key Concerns</label>
                        <input class="form form-control" name='challenges' value="{{old('challenges',$about->challenges)}}" type="text">
                    </div>
                    <div class="col-sm-6">
                        <label>Preferred Communication Channel</label>
                        <select class="form form-control" name="com_channel">
                            <option value="">Select Preferred Communication Channel</option>
                            <option value="Email" @if($about->com_channel=="Email")selected='' @endif>Email</option>
                            <option value="Viber" @if($about->com_channel=="Viber")selected='' @endif>Viber</option>
                            <option value="Call" @if($about->com_channel=="Call")selected='' @endif>Call</option>
                            <option value="Text" @if($about->com_channel=="Text")selected='' @endif>Text</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <label>Core Values</label><br>
                        <div class="col-sm-6">
                        <input type="checkbox" name="awareness" value="1" @if($about->awareness=="1") checked @endif>
                        <label for="awareness"> Awareness</label><br>
                        <input type="checkbox" name="commitment" value="1" @if($about->commitment=="1") checked @endif>
                        <label for="commitment"> Commitment</label><br>
                        <input type="checkbox" name="kindness" value="1" @if($about->kindness=="1") checked @endif>
                        <label for="kindness"> Kindness</label><br>
                        <input type="checkbox" name="simplicity" value="1" @if($about->simplicity=="1") checked @endif>
                        <label for="simplicity"> Simplicity</label><br>
                        <input type="checkbox" name="humility" value="1" @if($about->humility=="1") checked @endif>
                        <label for="humility"> Humility</label>
                        </div>
                        <div class="col-sm-6">
                        <input type="checkbox" name="integrity" value="1" @if($about->integrity=="1") checked @endif>
                        <label for="integrity"> Integrity</label><br>
                        <input type="checkbox" name="oneness" value="1" @if($about->oneness=="1") checked @endif>
                        <label for="oneness"> Oneness</label><br>
                        <input type="checkbox" name="nature" value="1" @if($about->nature=="1") checked @endif>
                        <label for="nature"> Nature</label><br>
                        <label for="other_core"> Others:</label>
                        <input type="text" class="form form-control" name="other_core" value="{{old('other_core',$about->others)}}" placeholder="Other Core Values">
                        </div>
                    </div>
                </div>
                            
                            
                            <!--if(empty($email) && $status->status != env("ENROLLED"))-->
                            @if(($adhedinfo->admission_status == 'Regular' || $adhedinfo->admission_status == 'Scholar' || $adhedinfo->admission_status == 'Probationary') && $adhedinfo->student_status ==1) 
                            <div class="form-group">
                                <!--if($adhedinfo->student_status == 1)-->
                                <div class="col-sm-6">
                                    <button class="form form-control btn btn-success" type="submit">UPDATE STUDENT</button>
                                </div>
                                <div class="col-sm-6">
                                    <a role="button" href="{{url('admission',array('send_email',$idno))}}" class="form form-control btn btn-warning">Send email for AC Portal Access of 1st Year Student</a>
                                </div>
<!--                                else
                                <div class="col-sm-12">
                                    <button class="form form-control btn btn-success" type="submit">UPDATE STUDENT</button>
                                </div>
                                endif-->
                            </div>
                            @else
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="form form-control btn btn-success" type="submit">UPDATE STUDENT</button>
                                </div>
                            </div>
                            @endif
<!--                            else
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button class="form form-control btn btn-success" type="submit">UPDATE STUDENT</button>
                                </div>
                            </div>
                            endif-->
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <a role="button" href="{{url('admission',array('print_pre_application_form',$idno))}}" class="form form-control btn btn-primary">PRINT PRE-APPLICATION FORM</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>                  
@endsection
@section('footerscript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js" integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" crossorigin="anonymous"></script>
    <script>
$('#tel_no').mask('(00)0000-0000');
$('#cell_no').mask('(0000)000-0000');
    $(document).ready(function () {
//    $("#conditionType").hide();
        $('#assumption_scholar').hide();
        $('#partner_scholar').hide();
        $('#probationary').hide();

        $('#applying_for').on('change', function (e) {
            // alert("hello")
            var array = {};
            array['applying_for'] = $('#applying_for').val();
            $.ajax({
                type: "get",
                url: "/registrarcollege/ajax/getprogram",
                data: array,
                success: function (data) {
                    $("#displayProgram").html(data);
                }

            });
        });


        $('#admission_status').on('change', function () {
            var value = $('#admission_status').val();
            if (value == "Scholar") {
                $('#assumption_scholar').fadeIn();
                $('#partner_scholar').fadeIn();
            } else {
                $('#assumption_scholar').hide();
                $('#partner_scholar').hide();
            }
        });

        $('#admission_status').on('change', function () {
            var value = $('#admission_status').val();
            if (value == "Probationary" || value == "Deload") {
                $('#probationary').fadeIn();
            } else {
                $('#probationary').hide();
            }
        });

//    $('#conditionType').on('change', function(){
//        var value = $('#conditionType').val();
//        if(value === "Others"){
//            $('#specifyCondition').fadeIn();
//        }
//        else{
//            $('#specifyCondition').hide();  
//        }
//    });

    })
</script>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2();
    });
    
    function update_testing(id){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['testing_id'] = id;
    $.ajax({
        type: "GET",
        url: "/ajax/admissionhed/update_schedule",
        data: array,
        success: function (data) {
        }

    });
}


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