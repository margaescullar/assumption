<?php
//$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
$student_info = \App\StudentInfo::where('idno', $idno)->first();
$grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
$discounts = \App\CtrDiscount::all();
        $scholar = \App\CollegeScholarship::where('idno', $idno)->first();
?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}


$check_balances = \App\OldSystemBalance::where('idno',$user->idno)->get();
$check_reservations = \App\Reservation::where('idno', $user->idno)->where('reservation_type',1)->where('is_consumed', 0)->where('is_reverse', 0)->get();
$check_student_deposits = \App\Reservation::where('idno', $user->idno)->where('reservation_type',2)->where('is_consumed', 0)->where('is_reverse', 0)->get();

$previous = \App\Ledger::groupBy(array('category', 'category_switch'))->where('idno', $user->idno)
                ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();
$due_previous = 0;
if (count($previous) > 0) {
    foreach ($previous as $prev) {
        $due_previous = $due_previous + $prev->amount - $prev->discount - $prev->debit_memo - $prev->payment;
    }
}

?>
@extends('layouts.appreg_college')
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
        Assessment
        <small>A.Y. {{$school_year}} - {{$school_year+1}} {{$period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Assessment</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('assessment',$idno))}}"> {{$idno}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<form method="POST" action="{{url('/registrar_college',array('assessment','save_assessment'))}}">    
<section class="content">
    <div class="row">
    <?php $balance = 0; ?>
    @if(count($check_balances)>0 || $due_previous>=1)
    @foreach ($check_balances as $check_balance)
    <?php $balance = $balance + $check_balance->balance; ?>
    @endforeach
    <div class="alert alert-danger">Student still have an outstanding balance of <b>Php {{number_format($balance + $due_previous)}}</b>. Please go to Treasurer's Office to settle the account.<br>
    <!--If <b>Official Receipt</b> was presented, kindly disregard this message. Thank you!-->
    </div>
    @endif
    <?php $reservation = 0; ?>
    @if(count($check_reservations)>0)
    @foreach ($check_reservations as $check_reservation)
    <?php $reservation = $reservation + $check_reservation->amount; ?>
    @endforeach
    <div class="alert alert-info">Student placed a reservation fee with the amount of <b>Php {{number_format($reservation)}}</b>.</div>
    @endif
    
    <?php $student_deposit = 0; ?>
    @if(count($check_student_deposits)>0)
    @foreach ($check_student_deposits as $check_student_deposit)
    <?php $student_deposit = $student_deposit + $check_student_deposit->amount; ?>
    @endforeach
    <div class="alert alert-info    ">Student have a student deposit with the amount of <b>Php {{number_format($student_deposit)}}</b>.</div>
    @endif
        <div class="col-md-4">
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
                        <li><a href="#">Status <span class="pull-right">Old Student</span></a></li>
                        <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                        <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                        <!--<li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                        @else
                        <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                        <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                        <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
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
            <div class='box'>
                <div class='box-body'>
                    <a href='{{url('/registrar_college', array('assessment', 'readvise',$user->idno))}}'><button type="button" class='btn btn-primary col-sm-12'>Re-advise Student</button></a>
                </div>
            </div>
            
            @if(count($grade_colleges)>0)
            <!--For audit-->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Is Audit?</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>
                        {{ csrf_field() }}
                            <input type="radio" name="is_audit" value='1'> Yes - Special Learning Needs<br>
                            <input type="radio" name="is_audit" value='2'> Yes - Special Interest(Credited)<br>
                            <input type="radio" name="is_audit" value='3'> Yes - Exchange Student(Credited)<br>
                            <input type="radio" name="is_audit" value='0' checked> No
                        
                </div>
            </div>
            <!--for tutorial-->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Add Tutorial Fee</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>
                    <form method="POST" action="{{url('/registrar_college',array('assessment','save_assessment'))}}">    
                        {{ csrf_field() }}
                        <input type="text" class="form form-control" name="tutorial_amount" placeholder="Amount">
                        <input type="text" class="form form-control" name="tutorial_units" placeholder="Number of Units">
                        
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Tuition Fee Quotations</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>

                        <div class="form-horizontal">
                            <input type="hidden" name="idno" value="{{$user->idno}}">  
                            <input type="hidden" name="school_year" value="{{$school_year}}">  
                            <input type="hidden" name="period" value="{{$period}}">  
                            <input type="hidden" name="program_code" value="{{$status->program_code}}">
                            <input type="hidden" name="level" value="{{$status->level}}">
                            <input type="hidden" name="type_account" id="type_account" value="Regular">
                            <div class="form-group">
                                <div class='col-sm-12' id='plan-form'>
                                    <?php $plans = \App\CtrDueDate::distinct()->where('academic_type', 'College')->where('level', $status->level)->get(['plan','description']); ?>
                                    <label>Plan</label>
                                    <select id="plan" name="plan" class='form-control'>
                                        <option>Select Plan</option>
                                        <option value='Plan A'>Plan A - Full Payment</option>
                                        @if($period != "Summer")
                                        @foreach ($plans as $plan)
                                        <option value='{{$plan->plan}}'>{{$plan->plan}} - {{$plan->description}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class='col-sm-12' id='discount-form'>
                                    <label>Discount</label>
                                    <input type='text' class='form-control' readonly="" value='{{$scholar->discount_description}}'>
                                    <input type='hidden' class='form-control' name='discount' value='{{$scholar->discount_code}}'>
<!--                                    <select id="discount" name="discount" class='form-control'>
                                        <option value="">Select Discount</option>
                                        <option value="">None</option>
                                        @foreach ($discounts as $discount)
                                        <option value="{{$discount->discount_code}}">{{$discount->discount_code}}</option>
                                        @endforeach
                                    </select>-->
                                </div>
                            </div>
                            <div class="form-group" id="compute-form">
                                <div class="col-sm-12">
                                    <input type="submit" class="form-control btn-primary" value="Process Assessment" name="submit">
                                    <!--<button class="btn btn-primary col-sm-12"  onclick="get_assessed_payment(plan.value, type_account.value, '{{$user->idno}}',discount.value)">Compute</button>-->
                                </div>
                            </div>
                            <div class="col-sm-12 box-body" id="display_result">

                            </div>
                        </div>        
                </div>
            </div>
                            
                            @else
                            @endif
        </div>
        <div class="col-md-8">
                            @if(count($grade_colleges)>0)
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Courses Advised</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>
                    <div class='table-responsive'>
                        <table class='table table-striped'>
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Course Name</th>
                                    <th>Units</th>
                                    <th>Schedule</th>
                                    <th>Instructor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $units = 0; ?>
                                @foreach($grade_colleges as $grade_college)
                                <?php
                                $units = $units + $grade_college->lec + $grade_college->lab;
                                $offering_ids = \App\CourseOffering::find($grade_college->course_offering_id);
                                ?>
                                <tr>
                                    <td>{{$grade_college->course_code}}</td>
                                    <td>{{$grade_college->course_name}}</td>
                                    <td>{{$grade_college->lec+$grade_college->lab}}</td>
                                    @if($grade_college->course_offering_id!=NULL)
                                    <td>
                                        <?php
                                        $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
                                        ?>   
                                        @foreach ($schedule3s as $schedule3)
                                        {{$schedule3->room}}
                                        @endforeach
                                        <?php
                                        $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
                                        ?>
                                        @foreach ($schedule2s as $schedule2)
                                        <?php
                                        $days = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                        ?>
                                        <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                        [@foreach ($days as $day){{$day->day}}@endforeach
                                        <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <?php
                $offering_id = \App\CourseOffering::find($grade_college->course_offering_id);
                    $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

                    foreach($schedule_instructor as $get){
                        if ($get->instructor_id != NULL){
                            $instructor = \App\User::where('idno', $get->instructor_id)->first();
                            echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                        } else {
                        echo "";
                        }
                    }
                ?>
                                    </td>
                                    @else
                                    <td>TBA</td>
                                    <td>TBA</td>
                                    @endif
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2"><strong>Total Units</strong></td>
                                    <td><strong>{{$units}}</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <strong>Remarks:</strong><br>
                    <?php $advised_remarks = \App\AdvisingRemarks::where('idno',$idno)->where('school_year',$school_year)->where('period',$period)->first(); ?>
                    @if(count($advised_remarks)>0)
                    {{$advised_remarks->remarks}} <br>-{{$advised_remarks->remarks_by}}
                    @else
                    No Remarks...
                    @endif
                </div>
            </div>
        </div>
    
    
    @if(count($grade_colleges) > 1)
        <?php
            $is_new = \App\Status::where('idno', $idno)->first()->is_new;
            if($is_new == 0){
            $otherfees = \App\CtrCollegeOtherFee::where('level', $status->level)->where('period', $period)->get();
            }else{
            $otherfees = \App\CtrCollegeNewOtherFee::where('level', $status->level)->where('period', $period)->get();
            }
            
                $is_foreign = \App\User::where('idno', $idno)->first();
                    if (count($is_foreign) > 0) {
                        if ($is_foreign->is_foreign == '1') {
                            $checkforeign = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('subsidiary','Foreign Fee')->get();
                            if(count($checkforeign) == 0){
                                $addfee = \App\CtrCollegeForeignFee::get();
                            }else{
                                $addfee = \App\CtrCollegeForeignFee::where('subsidiary', "!=",'Foreign Fee')->get();
                            }
                        }else{
                            $addfee = \App\CtrCollegeForeignFee::where('id', NULL);
                        }
                    }
            $is_new = \App\Status::where('idno', $idno)->first()->is_new;
            if($is_new == 0){
            $nondiscountotherfees = \App\CtrCollegeNonDiscountedOtherFee::where('level', $status->level)->where('period', $period)->get();
            }else{
            $nondiscountotherfees = \App\CtrCollegeNewNonDiscountOtherFee::where('level', $status->level)->where('period', $period)->get();
            }
        ?>
    @else
        <?php
            $check_practicum = \App\GradeCollege::where('idno', $user->idno)->where('school_year', $school_year)->where('period', $period)
                    ->where(function($q) {
                        $q->where('course_name', 'like', '%practicum%')
                        ->orWhere('course_name', 'like', '%intern%')
                        ->orWhere('course_name', 'like', '%internship%')
                        ->orWhere('course_name', 'like', '%ojt%')
                        ->orWhere('course_name', 'like', '%practice%');
                        
                    })
                    ->get();

            if (count($check_practicum) == 1) {
                    $otherfees = \App\CtrCollegePracticumFee::get();
                    $nondiscountotherfees = \App\CtrCollegeNonDiscountedOtherFee::where('level', $status->level)->where('period', $period)->get();
                    $is_foreign = \App\User::where('idno', $idno)->first();
                    if (count($is_foreign) > 0) {
                        if ($is_foreign->is_foreign == '1') {
                            $addfee = \App\CtrCollegePracticumForeignFee::get();
                        }
                    }
            } else {
                $is_new = \App\Status::where('idno', $idno)->first()->is_new;
                if($is_new == 0){
                $otherfees = \App\CtrCollegeOtherFee::where('level', $status->level)->where('period', $period)->get();
                }else{
                $otherfees = \App\CtrCollegeNewOtherFee::where('level', $status->level)->where('period', $period)->get();
                }
                if($is_new == 0){
                $nondiscountotherfees = \App\CtrCollegeNonDiscountedOtherFee::where('level', $status->level)->where('period', $period)->get();
                }else{
                $nondiscountotherfees = \App\CtrCollegeNewNonDiscountOtherFee::where('level', $status->level)->where('period', $period)->get();
                }
                $is_foreign = \App\User::where('idno', $idno)->first();
                    if (count($is_foreign) > 0) {
                        if ($is_foreign->is_foreign == '1') {
                            $addfee = \App\CtrCollegeForeignFee::get();
                    }
                }
            }
        ?>
    @endif
    <div class="col-md-8">   
        <div class="box">
            <div class="box-header">
                <div class="box-title">Other Fees</div>
            </div>
            <div class="box-body">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Subsidiary</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
        @foreach($otherfees as $other)
                        <tr>
        <td>
        <input type="checkbox" onclick="return false" name="other[{{$other->id}}]" checked=""><td>{{$other->subsidiary}}</td>
        </td>
        <td>{{$other->amount}}</td>
                        </tr>
        @endforeach
        @if(isset($nondiscountotherfees)>0)
        @foreach($nondiscountotherfees as $nodiscountother)
                        <tr>
        <td>
        <input type="checkbox" onclick="return false" name="nodiscountother[{{$nodiscountother->id}}]" checked=""><td>{{$nodiscountother->subsidiary}}</td>
        </td>
        <td>{{$nodiscountother->amount}}</td>
                        </tr>
        @endforeach
        @endif
        @if(isset($addfee)>0)
        @foreach($addfee as $add)
                        <tr>
        <td>
        <input type="checkbox" onclick="return false" name="add[{{$add->id}}]" checked=""><td>{{$add->subsidiary}}</td>
        </td>
        <td>{{$add->amount}}</td>
                        </tr>
        @endforeach
        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        
                            @else
                                No Courses Advised!
                            @endif
    </div>
</section>
</form>

@endsection
@section('footerscript')
<script>
    $("#compute-form").hide();
    $("#discount-form").hide();
    $("#plan-form").change(function () {
        $("#discount-form").fadeIn();
        $("#compute-form").fadeIn();
    });
    $("#discount-form").change(function () {
        $("#compute-form").fadeIn();
    });
</script>
<script>
    function get_assessed_payment(plan, type_account, idno, discount) {
        array = {};
        array['plan'] = plan;
        array['discount'] = discount;
        array['type_of_account'] = type_account;
        array['program_code'] = "{{$status->program_code}}";
        array['level'] = "{{$status->level}}";
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/assessment/get_assessed_payment",
            data: array,
            success: function (data) {
                $('#display_result').html(data);
            }

        });
    }
</script>
@endsection