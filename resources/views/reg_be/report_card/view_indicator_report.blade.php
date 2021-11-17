<?php

function getAttendances($month, $school_year, $idno, $type) {
    if ($type == 'absences') {
        $getAttendances = \App\Absent::SelectRaw('sum(is_absent)as total')->where('school_year', $school_year)->where('idno', $idno)->where('date', 'like', "%-$month-%")->first();
    } else {
        $getAttendances = \App\Absent::SelectRaw('sum(is_tardy)as total')->where('school_year', $school_year)->where('idno', $idno)->where('date', 'like', "%-$month-%")->first();
    }
    if ($getAttendances->total > 0) {
        return $getAttendances->total;
    } else {
        return 0;
    }
}

function getPromotion($level) {
    switch ($level) {
        case "Pre-Kinder":
            return "Kinder";
            break;
        case "Kinder":
            return "Grade 1";
            break;
        case "Grade 1":
            return "Grade 2";
            break;
        case "Grade 2":
            return "Grade 3";
            break;
        case "Grade 3":
            return "Grade 4";
            break;
        case "Grade 4":
            return "Grade 5";
            break;
        case "Grade 5":
            return "Grade 6";
            break;
        case "Grade 6":
            return "Grade 7";
            break;
        case "Grade 7":
            return "Grade 8";
            break;
        case "Grade 8":
            return "Grade 9";
            break;
        case "Grade 9":
            return "Grade 10";
            break;
        case "Grade 10":
            return "Grade 11";
            break;
        case "Grade 11":
            return "Grade 12";
            break;
        case "Grade 7":
            return "College";
            break;
    }
}
?>
<style type="text/css">
    @font-face {
        font-family: Cambria;
        src: "{{public_path('/font/Cambria_Bold.ttf')}}"
    }
    img {
        display: block;
        max-width:1.5cm;
        max-height:1.5cm;
        width: auto;
        height: auto;
        margin-top: 0.25px;
        margin-left: -.5cm;
    } 
    body{
        margin-left: 5px;
        margin-right: 5px;
    }



    #report_card{
        float: right;
        margin-right: 6px;
        font-family: cambria;
    }
    #school_year{
        font-size: 10pt;
        float: right;
        font-family: cambria;
        margin-top:-15px;
        margin-left:-5px;
    }
    #schoolheader{
        margin-top:-60px;
        margin-left: 1.3cm;
        font-family: cambria;
    }
    #schoolname{
        margin-top: -10px;
    }
    #address{
        font-size: 10pt;
    }
    #nameheader, .tr, .th{
        width:100%;
        font-family: Sans-serif;
        font-size: 8pt;
    }


    #legend, .tr, .th, .td{
        font-family: Sans-serif;
        margin-top: 5px;
        margin-left: 100px;
        width:60%;
        font-size: 8pt;
        text-align: center;
        font-weight:bold;
        border: black solid 1px;
    }
    #attendance, .tr, .td{
        font-family: sans-serif;
        margin-top: 5px;
        width: 100%;
        font-size: 7pt;
        text-align: center;
    }
    #column {
        float: left;
        width: 55%;
    }
    #column2 {
        float: left;
        width: 50%;
        padding-right: 15px;
    }





    #table1{
        border-bottom: black solid 1px;
    }
    #table1, .tr, #td1 {
        border-collapse: collapse;
        font-family: sans-serif;
        font-size: 9pt;
        border-left: black solid 1px;
        border-right: black solid 1px;
    }
    #td1_header{
        border-top: black solid 1px;
        border-bottom: black solid 1px;
        background: darkblue; color: white;
    }


    #transfer{
        text-align: justify;
        font-family: sans-serif;
        font-size: 7pt;
    }
    #cancel{
        text-align: justify;
        font-family: sans-serif;
        font-size: 7pt;
    }
</style> 
<table border="0" width="100%">
    <tr>
        <td>
            <img src="{{public_path('/images/assumption-logo.png')}}">
            <div id="schoolheader">
                <span id="schoolname">Assumption College</span><br>
                <span id="address">San Lorenzo Village, Makati City 1223</span>
            </div>
        </td>
        <td style='font-family: Cambria;' >
            <span id="report_card">REPORT CARD</span>
            <br><br>
            <span id="school_year">School Year {{$school_year}} - {{$school_year + 1}}</span>
        </td>
    </tr>
</table>



<table id="nameheader" border="0">
    <thead>
        <tr>
            <th width="60%" align="left">Name: {{$user->getFullNameAttribute()}}</th>
            <th width="40%" align="right">
                <span>Adviser: {{$adviser->getFullNameAttribute()}}</span>
            </th>
        </tr>
        <tr align="left">
            <th width="15%">Level/Section: {{$status->level}} - {{$status->section}} </th>
        </tr>
    </thead>
</table>


<hr>





<table id="table1" width="100%">
    <tr>
        <td id="td1 td1_header" width="75%"><b>Learning Indicators</b></td>
        <td id="td1 td1_header" align="center"><b>1st Eval.</b></td>
        <td id="td1 td1_header" align="center"><b>2nd Eval.</b></td>
        <td id="td1 td1_header" align="center"><b>3rd Eval.</b></td>
    </tr>    
    @foreach($records->groupBy('group_code') as $consRecord=>$groups)
    <tr>
        <td><strong>{{$consRecord}}</strong></td>
        <td id="td1"></td>
        <td id="td1"></td>
        <td id="td1"></td>
    </tr>
    @foreach($groups as $group)
    @if($group->school_year >= '2021')
    <tr>
        
        <td id="td1">{{$group->subject_name}}</td>
        <td id="td1" align='center'>@if($group->status1==3){{$group->qtr1}}@endif</td>
        <td id="td1" align='center'>@if($group->status2==3){{$group->qtr2}}@endif</td>
        <td id="td1" align='center'>@if($group->status3==3){{$group->qtr3}}@endif</td>
        
    </tr>
    @else
    <tr>
        <td id="td1">{{$group->subject_name}}</td>
        <td id="td1" align='center'>@if($group->status2==3){{$group->qtr2}}@endif</td>
        <td id="td1" align='center'>@if($group->status3==3){{$group->qtr3}}@endif</td>
        <td id="td1" align='center'>@if($group->status4==3){{$group->qtr4}}@endif</td>
    </tr>
    @endif
    @endforeach
    @endforeach
</table>



<div id="column">
    <table id="attendance" border = 1 cellpadding = 1 cellspacing =0>
        <tr>
            <th>ATTENDANCE</th>
            <th>Aug</th>
            <th>Sep</th>
            <th>Oct</th>
            <th>Nov</th>
            <th>Dec</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>May</th>
            <?php $school_days = \App\CtrSchoolDay::where('academic_type', 'BED')->where('school_year', $school_year)->value('school_days'); ?>
            <td width="16%" valign="top"><strong>Days of School</strong><br>{{$school_days}}</td>
        </tr>
        <?php $total_absent = 0; ?>
        <?php $totalab = 0; ?>
        </tr>
        <tr>
            <td>Absences</td>
            <td><?php $total_absent += $totalab = getAttendances('08', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('09', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('10', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('11', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('12', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('01', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('02', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('03', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('04', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td><?php $total_absent += $totalab = getAttendances('05', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <!--<td valign="top" rowspan="2"><strong>Days Present</strong><br>{{$school_days-$total_absent}}</td>-->
            <td valign="top" rowspan="2"><strong>Days Present</strong><br>N/A</td>
        </tr>
        <tr>
            <td>Tardiness</td>
            <td><?php $tardy = getAttendances('08', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('09', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('10', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('11', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('12', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('01', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('02', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('03', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('04', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td><?php $tardy = getAttendances('05', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
        </tr>
    </table>
</div>
<div id="column">
    <table id="legend">
        <tr>
            <td><u>LEGEND</u></td>
        </tr>
        <tr>
            <td>O - Observed<br>NO - Not Observed</td>
        </tr>
    </table>
</div>
<br>
<br>
<br>
<br>
<hr>
<div id="column2">
    <div id="transfer">
        <strong>CERTIFICATE OF TRANSFER</strong><br>
        The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
        <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
        She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.
        <br>
        <br>
        <br>
        <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
        Principal<br><br>
        <i>May 28, 2021</i>
        <br>
        Date
    </div>
</div>
<div id="column2">
    <div id="cancel">
        <strong>CANCELLATION OF TRANSFER ELIGIBILITY</strong><br>
        Has been admitted to _____________________________________________.
        <br>
        <br>
        <br>
        <br>
        <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
        Principal<br>
        <br>
        Date
    </div>
</div>