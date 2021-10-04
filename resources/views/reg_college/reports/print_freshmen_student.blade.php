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
    .table, .th, .td {     
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }
   

</style>
<div>    
    <div style='float: left; margin-left: 250px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>LIST OF FRESHMEN STUDENT</b><br><b>A.Y. {{$school_year}} - {{$school_year + 1}}</b><br><b></b><br></div>
</div>
<div>
@if(count($lists)>0)
<table style='margin-top:150px; width:100%' class="table table-bordered table-striped"  border="1">
    <thead>
        <tr>    
            <th><strong>#</strong></th>
            <th><strong>ID Number</strong></th>
            <th><strong>Name</strong></th>
            <th><strong>Program</strong></th>
            <th><strong>Period</strong></th>
            <th><strong>Last School Attended</strong></th>
            <th><strong>Email</strong></th>
            <th><strong>Contact Number</strong></th>
            <th><strong>Parent Mobile Number</strong></th>
            <th><strong>Parent Email</strong></th>
        </tr>
    </thead>
    <tbody>
        <?php $count = 0; ?>
        @foreach($lists->list as $list)
        <?php $user = \App\User::where('idno', $list->idno)->first(); ?>
        <?php $student_info = \App\StudentInfo::where('idno', $list->idno)->first(); ?>
        <?php $level = \App\CollegeLevel::where('idno', $list->idno)->first(); ?>
        <?php $count = $count + 1?>
        <tr>
            <td>{{$count}}</td>
            <td>{{$list->idno}}</td>
            <td>{{$user->getFullNameAttribute()}}</td>
            <td>{{$list->program_code}}</td>
            <td>{{$level->period}}</td>
            <td>{{$student_info->last_school_attended}}</td>
            <td>{{$user->email}}</td>
            <td>{{$student_info->cell_no}}</td>
            <td>F:{{optional($student_info->studentInfoParentInfo)->f_personal_phone}}<br>
                M:{{optional($student_info->studentInfoParentInfo)->m_personal_phone}}
            </td>
            <td>F:{{optional($student_info->studentInfoParentInfo)->f_email}}<br>
                M:{{optional($student_info->studentInfoParentInfo)->m_email}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<h1>Record Not Found!!!</h1>
@endif

    @if(Auth::user()->accesslevel == env("REG_COLLEGE"))
    <table width="100%">
        <thead>
            <tr>
                <td>Prepared By:<br><br><br><br></td>
                <td>Approved By:<br><br><br><br></td>
                <td><div align="right">Date Printed: {{ date('Y-m-d H:i:s') }}</div></td>
            </tr>
        </thead>
        <tbody>
            <tr>               
                <td><b>{{strtoupper(Auth::user()->lastname)}}, {{strtoupper(Auth::user()->firstname)}} {{strtoupper(Auth::user()->middlename)}}</b></td>
                <td><b>{{strtoupper(env("HED_REGISTRAR"))}}<br></b></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Registrar</td>
                <td></td>
            </tr>
        </tbody>
    </table>
@endif    
</div>