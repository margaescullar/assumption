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
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
    }

</style>
<style>
    @page {
                margin: 1cm 1cm;
            }
    body{
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
                margin-top: 6.2cm;
                margin-left: 0cm;
                margin-right: 0cm;
                margin-bottom: 2.5cm;
    }
    
    header {
        position: fixed; 
        top: 0cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 

        margin: 0cm 0cm cm 0cm;

    }
    footer {
        position: fixed; 
        bottom: 3cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 

        margin: 0cm 0cm cm 0cm;

    }
</style>
<body>
    
    <script type="text/php">
        if ( isset($pdf) ) {
            $x = 700;
            $y = 110;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $font = $fontMetrics->get_font("helvetica");
            $size = 7;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
    <header>
    <div style='float: left; margin-left: 270px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <!--<div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>CHED ENROLLMENT REPORTS</b><br></div>-->    
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br>OFFICE OF THE REGISTRAR<br><b>CHED ENROLLMENT LIST REPORT</b><br><b>{{$request->period}}, {{$request->school_year}} - {{$request->school_year + 1}}</b></div>

    <table  style='margin-top: 135px;' width="100%">
        <thead>
<!--            <tr>
                <td width="15%"><b>Institutional Identifier:</b></td><td width="50%"><b>13022</b></td>
                <td><b>Semester/Trimester:</b></td><td><b>{{$request->period}}</td>
            </tr>-->
<!--            <tr>
                <td><b>Name of Institution:</b></td><td><b>Assumption College</b></td>
                <td><b>Year Level:</b></td><td><b>{{$request->level}}</b></td>
            </tr>-->
<!--            <tr>
                <td><b>Address:</b></td><td><b>San Lorenzo Village, Makati City</b></td><td></td><td></td>
            </tr>-->
            <tr>
                <td><b>Program:</b></td><td><b><?php $program_name = \App\CtrAcademicProgram::where('program_code', $request->program_code)->first()->program_name; ?>{{$program_name}}</b></td><td></td><td></td>
            </tr>
            <tr>
                <td><b>Year Level:</b></td><td><b>{{$request->level}}</b></td>
            </tr>
<!--            <tr>
                <td><b>Tel. No.:</b></td><td><b>817-0757 loc. 2030</b></td><td></td><td></td>
            </tr>-->
        </thead>
    </table>
    </header>
    
    <footer>
    <table width="100%">
        <tbody>
            <tr>
                <td><b>Certified Correct:<br><br><br><br></b>
                    <b>{{strtoupper(env("HED_REGISTRAR"))}}</b><br>
                    Registrar<br>

                    <div align="right">Date Printed: {{ date('Y-m-d H:i:s') }}</div></td>
            </tr>
        </tbody>
    </table>
    </footer>
    
    <table class='table' width="100%" cellspacing='0' cellpadding='0' border="0" style='margin-top:0px;'>
        <thead>
            <tr>
                <th style="border-top: 1pt dotted black;border-bottom: 1pt dotted black"><?php $counter = 1; ?></th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Student#</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">First Name</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Middle Name</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Surname</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Gender</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Code</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Description</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Units</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
            <?php
            $units = 0;
            $totalunits = 0;
            $grades = \App\GradeCollege::where('idno', $student->idno)->where('school_year', $student->school_year)->where('period', $student->period)->get();
            ?>
            <tr>
                <td valign="top" style="text-align: left">{{$counter}}.<?php $counter = $counter + 1; ?></td>
                <td valign="top">{{$student->idno}}</td>
                <td valign="top">{{strtoupper($user->firstname)}}</td>
                <td valign="top">{{strtoupper($user->middlename)}}</td>
                <td valign="top">{{strtoupper($user->lastname)}}</td>
                <td valign="top" align="center">F</td>
                    @foreach ($grades as $grade)
                    @if ($loop->first)
                        <td>{{$grade->course_code}}</td>
                        <td>{{$grade->course_name}}</td>
                        <td>{{$units = $grade->lec + $grade->lab}}</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="6"></td>
                        <td>{{$grade->course_code}}</td>
                        <td>{{$grade->course_name}}</td>
                        <td>{{$units = $grade->lec + $grade->lab}}</td>
                    </tr>
                    @endif
                    <?php $totalunits = $totalunits + $units; ?>
                    @if ($loop->last)
                    <tr>
                        <td colspan="6"></td>
                        <td style="border-top: 1pt double dotted black" colspan="2" align="right">TOTAL UNITS:</td>
                        <td style="border-top: 1pt double dotted black"><b>{{$totalunits}}</b></td>
                    </tr>
                    @endif   
                    @endforeach
            </tr>    
            @endforeach
        </tbody>
    </table>           
    <table width="100%">
        <tr>
            <td><b>Total Number of Students: {{count($students)}}</b></td>
        </tr>
    </table>
</body>