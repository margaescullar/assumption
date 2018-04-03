<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
    .bottomline {
        border-bottom: 1px solid black;
    }
    .bottomline-right {
        text-align: right;
    }
    #registration {
        border-collapse: collapse;
    }
    #registration, #reg {
        border: 1px solid black;
        text-align: center;
    }
    #pt {
        font-size: 9pt;
    }
</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{url('/images','assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>REGISTRATION FORM</b>
        <br><small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </div>
</div>
<br>
<table width="100%" style='margin-top: 145px; font-size: 9pt'>
    <tr>
        <td width="13%">Student No:</td>
        <td width="60%" class="bottomline"><b>{{strtoupper($user->idno)}}</b></td>
        <td width="7%">
            <div align='left'>Date:</div>
        </td>
        <td width="20%" class="bottomline">{{$status->date_registered}}</td>
    </tr>
    <tr>
        <td>Name:</td>
        <td class="bottomline">{{mb_strtoupper($user->firstname, 'UTF-8')}} {{mb_strtoupper($user->middlename, 'UTF-8')}} {{mb_strtoupper($user->lastname, 'UTF-8')}} {{mb_strtoupper($user->extensionname, 'UTF-8')}}</td>
        <td>
            <div align='left'>Nationality:</div>
        </td>
        <td class="bottomline">{{$student_info->nationality}}</td>
    </tr>
    <tr>
        <td>Course/Year:</td>
        <td class="bottomline" colspan="3">{{$status->program_name}} - {{$status->level}}</td>
    </tr>
    <tr>
        <td>Home Address:</td>
        <td class="bottomline" colspan="3">{{$student_info->street}} {{$student_info->barangay}} {{$student_info->municipality}} {{$student_info->province}} {{$student_info->zip}}</td>
    </tr>
</table>
<table id='registration' width="100%" style='margin-top: 5px; font-size: 9pt'>
    <tr>
        <th id='reg'>Course</th>
        <th id='reg'>Schedule/Room</th>
        <th id='reg'>Instructor</th>
        <th id='reg'>Lec</th>
        <th id='reg'>Lab</th>
    </tr>
    <?php
    $totallec = 0;
    $totallab = 0;
    ?>
    @foreach ($grades as $grade)
    <tr>
        <td id='reg'><small>@if($status->academic_type!='Senior High School'){{$grade->course_code}}@endif {{$grade->course_name}} @if($grade->is_drop == 1) [DROPPED] @endif </small></td>

        @if ($status->academic_type!='Senior High School')
        <?php
        $offering_ids = \App\CourseOffering::find($grade->course_offering_id);
        ?>
        <td id='reg'>
            <?php
            $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
            ?>
            @foreach ($schedule2s as $schedule2)
            <?php
            $days = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
            ?>
            @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}} [{{$schedule2->room}}]<br>
            <!--{{$schedule2->day}} {{$schedule2->time_start}} - {{$schedule2->time_end}}<br>-->
            @endforeach
        </td>
        <td id='reg'>
            <?php
            $offering_id = \App\CourseOffering::find($grade->course_offering_id);
            $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

            foreach ($schedule_instructor as $get) {
                if ($get->instructor_id != NULL) {
                    $instructor = \App\User::where('idno', $get->instructor_id)->first();
                    echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                } else {
                    echo "";
                }
            }
            ?>
        </td>
        @else
        <td id='reg'></td>
        <td id='reg'></td>
        @endif
        <td id='reg'>
            <?php
            if ($grade->is_drop == 1) {
                $totallec = $totallec;
            } else {
                $totallec = $totallec + $grade->lec;
                if ($grade->lec == 0) {
                    echo "0";
                } else {
                    echo "$grade->lec";
                }
            }
            ?>
        </td>
        <td id='reg'>
            <?php
            if ($grade->is_drop == 1) {
                $totallab = $totallab;
            } else {
                $totallab = $totallab + $grade->lab;
                if ($grade->lab == 0) {
                    echo "0";
                } else {
                    echo "$grade->lab";
                }
            }
            ?>
        </td>
    </tr>
    @endforeach
    <tr>
        <th id='reg' colspan="3"></th>
        <th id='reg'>{{$totallec}}</th>
        <th id='reg'>{{$totallab}}</th>
    </tr>
</table>

<?php
$tfee = 0;
$ofee = 0;
$defee = 0;
$mfee = 0;
$dfee = 0;
$srffee = 0;
$esc = 0;
$oaccounts = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 7)->get();
$tfs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 5)->get();
foreach ($tfs as $tf) {
    $tfee = $tfee + $tf->amount;
}
$ofs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 2)->get();
foreach ($ofs as $of) {
    $ofee = $ofee + $of->amount;
}
$defs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 3)->get();
foreach ($defs as $def) {
    $defee = $defee + $def->amount;
}
$mfs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 1)->get();
foreach ($mfs as $mf) {
    $mfee = $mfee + $mf->amount;
}
$srfs = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 4)->get();
foreach ($srfs as $srf) {
    $srffee = $srffee + $srf->amount;
}
$subjects = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->where('category_switch', 4)->get();

$discounts = \App\Ledger::where('idno', $status->idno)->where('school_year', $y->school_year)->where('period', $y->period)->get();
foreach ($discounts as $discount) {
    $dfee = $dfee + ($discount->discount);
    $esc = $esc + $discount->esc;
}
?>
<div>
    <table width='40%' style="float:left;" style='margin-top: 5px;' id='pt'>
        <tr>
            <td><b>SAS ASSESSMENT</b></td>
        </tr>
        <tr>
            <td width='40%'>Tuition Fee</td>
            <td>:</td>
            <td class='bottomline-right'>{{number_format($tfee,2)}}</td>
        </tr>
        <tr>
            <td>Miscellaneous Fees</td>
            <td>:</td>
            <td class='bottomline-right'>{{number_format($mfee,2)}}</td>
        </tr>
        <tr>
            <td>Depository Fees</td>
            <td>:</td>
            <td class='bottomline-right'>{{number_format($defee,2)}}</td>
        </tr>
        <tr>
            <td>Other Fees</td>
            <td>:</td>
            <td class='bottomline-right'>{{number_format($ofee,2)}}</td>
        </tr>
        @if(count($subjects) > 0)
        @foreach ($subjects as $subjectfee )
        <tr>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subjectfee->subsidiary}}</td>
            <td>:</td>
            <td class='bottomline-right'>{{number_format($subjectfee->amount,2)}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        </tr>
        @endforeach
        @endif
        <tr>
            <td>Subject Related Fee</td>
            <td>:</td>
            <td class='bottomline-right'>{{number_format($srffee,2)}}</td>
        </tr>
        <tr>
            <td>Discounts</td>
            <td>:</td>
            <td class='bottomline-right'>({{number_format($dfee,2)}})</td>
        </tr>
        @if($status->academic_type=="Senior High School")
        <tr>
            <td>Voucher</td>
            <td>:</td>
            <td class='bottomline-right'>({{number_format($esc,2)}})</td>
        </tr>
        @endif
        <tr>
            <td><strong>Total Tuition Fee</strong></td>
            <td><strong>:</strong></td>
            <td class='bottomline-right'><strong>Php {{number_format((($srffee+$tfee+$ofee+$defee+$mfee)-$dfee)-$esc,2)}}</strong></td>
        </tr>
    </table>
    
    
    @if (count($ledger_due_dates)>0)
    <table width='60%' style="margin-left: 30px; margin-right: 30px; float:left; border: 0px" id='pt'>
        <tr>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>Due Date</th>
            <th>Description</th>
            <th class='bottomline-right'>Amount</th>
        </tr>
        <tr>
            <td>{{ date ('D, M d, Y', strtotime($downpayment->due_date))}}</td>
            <td>Downpayment</td>
            <td class='bottomline-right'>{{number_format($downpayment->amount,2)}}</td>
        </tr>
        @foreach ($ledger_due_dates as $ledger_due_date)
        <tr>
            <td>{{ date ('D, M d, Y', strtotime($ledger_due_date->due_date))}}</td>
            <td></td>
            <td class='bottomline-right'>{{number_format($ledger_due_date->amount,2)}}</td>
        </tr>
        @endforeach
    </table>
    @else
    <table width="60%"  style="margin-left: 30px; margin-right: 30px; float:left; border: 0px" id='pt'>
        <tr>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>Date</th>
            <th>Description</th>
            <th class='bottomline-right'>Amount</th>
        </tr>
        <tr>
            <td>{{ date ('D, M d, Y', strtotime($downpayment->due_date))}}</td>
            <td>Upon Enrollment</td>
            <td class='bottomline-right'>{{number_format($downpayment->amount,2)}}</td>
        </tr>
    </table>
    @endif
</div>
<span style="clear:both;"><hr></span>
<table width="100%" style="clear:both; font-size: 9pt">
    <tr>
        <td rowspan="2" width="50%">
            <center>I shall abide by all the rules and regulations now enforced or may be promulgated by Assumption College from time to time, Likewise. I agree to the cancellation of the credits I have earned in courses I have enrolled under false pretenses.</center><br>
            <center>____________________________<br><strong>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}} {{$user->extensionname}}</strong></center>
        </td>
        <td width="50%">
            <center>Processed by:<br><br>____________________________<br><strong>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</strong></center></td>
    </tr>
    <tr>
        <td>
            <center><br>Approved by:<br><br>____________________________<br><strong></strong><br><small>Registration Releasing Officer</small></center>
        </td>
    </tr>
</table>

<div style="text-align: justify; font-size: 7pt">
    <div align="left"><strong>NOTE:</strong></div>
    <ol>
        <li>Students are advised to accomplish within 1 day</li>
        <li>In the event this form was not accomplished within 1 day, students are advised to verify with Registering Officer for availability of the schedules listed above.</li>
        <li>This form remains valid as long as ALL schedule of classess listed above are still OPEN.</li>
        <li>15 units and below FULL PAYMENTS.</li>
    </ol>
</div>
