<style>
    body {
        font-size: 9pt;
    }
    footer {
        font-size: 8pt;
    }
    #schoolname{
        font-size: 20pt; 
        font-weight: bolder;
    }
</style>
<style>
@page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 1cm;
                margin-top: 4.3cm;

            }
            header {
                position: fixed; 
                top: .5cm; 
                left: 0px; 
                right: 0px;
                height: 0px; 
                
                margin: 0cm 1cm 0cm 1cm;
            }          
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
        </style>           
<body>       
<!--    
    <div style='float: left; margin-left:630px; margin-top:-110px;'></div>-->
      
    <header>
        <table class="table table-condensed" width="100%" border="0">
            <tbody>        
                <div>    
                    <div style='float: left; margin-left: 275px;'><img src="../images/assumption-logo.png"></div>
                    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br>San Lorenzo Drive, San Lorenzo Village<br> Makati City<br><br><b>GENERAL SCHEDULE</b><br><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}}</b></div>
                </div>
            </tbody>
        </table>
    </header> 
    @if (count($courses)>0)
        <table class="table table-striped" width="100%">
            <thead>
                <tr>
                    <th style="border-bottom:1px solid black" width="10%">Course Code</th>
                    <th style="border-bottom:1px solid black">Section</th>                    
                    <th style="border-bottom:1px solid black" width="40%">Course Name</th>
                    <th style="border-bottom:1px solid black" align="center">Unit</th>
                    <th style="border-bottom:1px solid black" align="center">Enrolled</th>
                    <th style="border-bottom:1px solid black" align="center">Assessed</th>
                    <th style="border-bottom:1px solid black" width="18%">Schedule</th>
                    <th style="border-bottom:1px solid black">Room</th>
                    <th style="border-bottom:1px solid black" width="15%">Instructor</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalunits = 0;?>
                @foreach($courses as $course)
                
                <?php $get_student=0; ?>
                <?php $get_number = \App\GradeCollege::where('course_offering_id', $course->id)->get(); ?>
                <?php $get_student = $get_student + count($get_number); ?>
                
                <tr>
                    <td>{{$course->course_code}}</td>
                    <td>
                        {{$course->section_name}}
                    </td>                    
                    <td>
                        <?php
                        $schedules = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->get();
                        ?>
                        {{$course->course_name}}

                    </td>
                    <td align="center">
                        {{$units = $course->lab + $course->lec}}
                    </td>    
                    <td align="center">
                        <?php $no = \App\CourseOffering::where('schedule_id', $course->schedule_id)->leftJoin('grade_colleges', 'grade_colleges.course_offering_id','=','course_offerings.id')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $request->school_year)->where('college_levels.period',$request->period)->where('schedule_id','!=',null)->get(['grade_colleges.id']); ?>
                        {{count($no)}}
                    </td>    
                    <td>{{$get_student}}</td>
                        <?php $totalunits = $totalunits + $units?>
                    <td>
                        <?php
                        $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $course->schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?>
                        @foreach ($schedule2s as $schedule2)
                        <?php
                        $days = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                        ?>
                        <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                        @foreach ($days as $day){{$day->day}}@endforeach 
                        <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->first()->is_tba; ?>
                        @if ($is_tba == 0)
                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                        @else

                        @endif
                        @endforeach
                    </td>
                    <td>
                        <?php
                        $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $course->schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?>
                        @foreach ($schedule3s as $schedule3)
                        {{$schedule3->room}}<br>
                        @endforeach
                    </td>
                    <td>
                        <?php
                        $offering_id = \App\CourseOffering::find($course->id);
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
                </tr>
                @endforeach
        <tr>
            <td><b></b></td>
            <td><b></b></td>
            <td align="right">TOTAL UNITS:</td>
            <td align="center"><b>{{$totalunits}}</b></td>
            <td><b></b></td>
            <td><b></b></td>
            <td><b></b></td>
        </tr>                 
            </tbody>
        </table>
    @else
    <div class="alert alert-info alert-dismissible">
        <h4><i class="icon fa fa-info"></i> Alert!</h4>
        No Courses Offered for this room!!!
    </div>
    @endif
    
    @if(Auth::user()->accesslevel == env("REG_COLLEGE"))
    <br>        
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
</body>