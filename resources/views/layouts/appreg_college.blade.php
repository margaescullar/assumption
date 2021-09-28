<?php
$file_exist = 0;
if (file_exists(public_path("images/" . Auth::user()->idno . ".jpg"))) {
    $file_exist = 1;
}
?>

<!DOCTYPE html>
<html>
    <head class="no-print">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College - Registrar</title>
        <link rel="shortcut icon" type="image/jpg" href="{{url('/images','assumption-logo.png')}}">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset ('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/Ionicons/css/ionicons.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('dist/css/AdminLTE.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('dist/css/skins/skin-blue.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('plugins/pace/pace.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header no-print">
                <a href="{{url('/')}}" class="logo">
                    <span class="logo-mini"><b>A</b>CS</span>
                    <span class="logo-lg"><b>Registrar</b>COLLEGE</span>
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            @yield('messagemenu')

                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    @if($file_exist==1)
                                    <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="user-image" alt="User Image">
                                    @else
                                    <img class="user-image" width="25" height="25" alt="User Image" src="/images/default.png">
                                    @endif
                                    <span class="hidden-xs">{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        @if($file_exist==1)
                                        <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                                        @else
                                        <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                                        @endif

                                        <p>
                                            {{Auth::user()->lastname}}, {{Auth::user()->firstname}}
                                            <small>Registrar - College</small>
                                        </p>
                                    </li>

                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">

                                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                                               onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                                <span><i class="fa fa-sign-out"></i> Logout</span>
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar no-print">

                <section class="sidebar">

                    <div class="user-panel">
                        <div class="pull-left image">
                            @if($file_exist==1)
                            <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                            @else
                            <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                            @endif
                        </div>
                        <div class="pull-left info">
                            <p>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MENU</li>
                        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> <span>Home</span></a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-id-card-o"></i> <span>Register</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('admission','new_student'))}}"><i class="fa fa-circle-o"></i> <span>Add Old Student</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-money"></i> <span>Subject Related Fee</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('dean', array('srf'))}}"><i class="fa fa-circle-o" aria-hidden="true"></i> <span>Set SRF</span></a></li>
                                <li><a href="{{url('dean', array('srf', 'print_srf'))}}"><i class="fa fa-circle-o" aria-hidden="true"></i> <span>Print SRF</span></a></li>
                                <li><a href="{{url('dean', array('srf', 'student_srf'))}}"><i class="fa fa-circle-o" aria-hidden="true"></i> <span>Student List</span></a></li>
                                <li><a href="{{url('dean', array('srf', 'srf_balances'))}}"><i class="fa fa-circle-o" aria-hidden="true"></i> <span>SRF Balance Report</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-gavel"></i> <span>Advising</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('advising','set_up'))}}"><i class="fa fa-circle-o"></i> <span>Set Up</span></a></li>
                                <!--<li><a href="{{url('/registrar_college', array('advising','advising_statistics'))}}"><i class="fa fa-circle-o"></i> <span>Sectioning</span></a></li>-->
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-list-alt"></i> <span>Curriculum Management</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('curriculum_management','curriculum'))}}"><i class="fa fa-circle-o"></i> <span>Curriculum</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','add_electives'))}}"><i class="fa fa-circle-o"></i> <span>Add Electives</span></a></li>
                                <!--<li><a href="{{url('/registrar_college', array('curriculum_management','upload_curriculum'))}}"><i class="fa fa-circle-o"></i> <span>*Upload Curriculum</span></a></li>-->
                        <li class="treeview">
                            <a href="#"><i class="fa fa-circle-o"></i> View Course Offering<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('curriculum_management','view_course_offering_general'))}}"><i class="fa fa-circle-o"></i> <span>General Schedule</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','view_course_offering'))}}"><i class="fa fa-circle-o"></i> <span>Per Section</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','view_course_offering_room'))}}"><i class="fa fa-circle-o"></i> <span>Per Room</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','view_course_offering_course'))}}"><i class="fa fa-circle-o"></i> <span>Per Course</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','view_course_offering_per_day'))}}"><i class="fa fa-circle-o"></i> <span>Per Day</span></a></li>
                            </ul>
                        </li>    
                                <li><a href="{{url('/registrar_college', array('curriculum_management','course_offering'))}}"><i class="fa fa-circle-o"></i> <span>Course Offering</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','course_schedule'))}}"><i class="fa fa-circle-o"></i> <span>Course Schedule</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','faculty_loading'))}}"><i class="fa fa-circle-o"></i> <span>Faculty Loading</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','view_room_schedule'))}}"><i class="fa fa-circle-o"></i> <span>View Room Schedules</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','update_rooms'))}}"><i class="fa fa-circle-o"></i> <span>Update List of Rooms</span></a></li>
                                <li><a href="{{url('/registrar_college', array('curriculum_management','section_limit'))}}"><i class="fa fa-circle-o"></i> <span>Update Section Limit</span></a></li>
<!--                                <li><a href="{{url('/registrar_college', array('curriculum_management','edit_schedule'))}}"><i class="fa fa-circle-o"></i> <span>*Edit Schedule</span></a></li>-->
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-male"></i> <span>Instructor</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('instructor','view_instructor'))}}"><i class="fa fa-circle-o"></i> <span>View Instructors</span></a></li>
                                <li><a href="{{url('/registrar_college', array('instructor','add_instructor'))}}"><i class="fa fa-circle-o"></i> <span>Add Instructor</span></a></li>
                            </ul>
                        </li>
                                <?php 
                                $school_year = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->school_year;
                                $period = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->period;
                                ?>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-pencil"></i> <span>Grade Management</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('grade_management','grading_sy'))}}"><i class="fa fa-circle-o"></i> <span>Changed Grading Period</span></a></li>
                                <li><a href="{{url('/registrar_college', array('grade_management','open_close'))}}"><i class="fa fa-circle-o"></i> <span>Open/Close Grade Module</span></a></li>
                                <li><a href="{{url('/registrar_college', array('grade_management','view_grades', $school_year, $period))}}"><i class="fa fa-circle-o"></i> <span>View Grades</span></a></li>
                                <li><a href="{{url('/registrar_college', array('grade_management','inc_ng', $school_year, $period,'finals'))}}"><i class="fa fa-circle-o"></i> <span>INC/NG Grades</span></a></li>
                                <li><a href="{{url('/registrar_college', array('grade_management','statistics_of_grades', $school_year, $period))}}"><i class="fa fa-circle-o"></i> <span>Statistics of Grades</span></a></li>
                                <li><a href="{{url('/registrar_college', array('grade_management','report_card'))}}"><i class="fa fa-circle-o"></i> <span>Report Cards</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-graduation-cap"></i> <span>Graduates/Alumni</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('graduates','tagging', $school_year))}}"><i class="fa fa-circle-o"></i> <span>Tagging of Graduates</span></a></li>
                                <li><a href="{{url('/registrar_college', array('graduates','batch_ranking'))}}"><i class="fa fa-circle-o"></i> <span>Batch Ranking</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-bar-chart"></i> <span>Reports</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li class="treeview">
                                    <a href="#"><i class="fa fa-circle-o"></i> Student List<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                                    <ul class="treeview-menu">
                                        <li><a href="{{url('registrar_college', array('reports','student_list', 'search'))}}"><i class="fa fa-circle-o"></i> <span>Search</span></a></li>
                                        <li><a href="{{url('registrar_college', array('reports','student_list', 'per_course'))}}"><i class="fa fa-circle-o"></i> <span>Per Course</span></a></li>
                                        <li><a href="{{url('registrar_college', array('reports','student_list', 'per_instructor'))}}"><i class="fa fa-circle-o"></i> <span>Per Instructor</span></a></li>
<!--                                        <li><a href="{{url('#')}}"><i class="fa fa-circle-o"></i> <span>*Section List</span></a></li>-->
                                    </ul>
                                </li>
                                <?php $date_today = date('Y-m-d'); ?>
                                <li><a href="{{url('/registrar_college', array('reports', 'enrollment_statistics', $school_year, $period))}}"><i class="fa fa-circle-o"></i> <span>Enrollment Statistics</span></a></li>
                                <li><a href="{{url('/registrar_college', array('reports', 'total_daily_enrollment_statistics', $date_today, $date_today))}}"><i class="fa fa-circle-o"></i> <span>Daily Enrollment Statistics</span></a></li>
                                <li><a href="{{url('/registrar_college', array('reports', 'ched_enrollment_reports'))}}"><i class="fa fa-circle-o"></i> <span>CHED Enrollment Report</span></a></li>
                                <li><a href="{{url('/registrar_college', array('reports', 'list_transfer_student'))}}"><i class="fa fa-circle-o"></i> <span>List of Transfer Student</span></a></li>
                                <li><a href="{{url('/registrar_college', array('reports', 'list_unofficially_enrolled'))}}"><i class="fa fa-circle-o"></i> <span>List of Unofficially Enrolled</span></a></li>
                                <li><a href="{{url('/registrar_college', array('reports', 'list_freshmen_student'))}}"><i class="fa fa-circle-o"></i> <span>List of Freshmen Student</span></a></li>
                                <li><a href="{{url('/registrar_college', array('reports', 'list_foreign_student'))}}"><i class="fa fa-circle-o"></i> <span>List of Foreign Student</span></a></li>
                                <li><a href="{{url('/registrar_college', array('reports', 'list_audit_student'))}}"><i class="fa fa-circle-o"></i> <span>List of Audit Student</span></a></li>
                                <li class="treeview">
                                    <a href="#"><i class="fa fa-circle-o"></i> NSTP Reports<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                                    <ul class="treeview-menu">
                                        <li><a href="{{url('/registrar_college', array('reports', 'nstp_reports'))}}"><i class="fa fa-circle-o"></i> <span>NSTP Student List</span></a></li>
                                        <li><a href="{{url('/registrar_college', array('reports', 'nstp_graduates'))}}"><i class="fa fa-circle-o"></i> <span>NSTP Graduates</span></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="{{url('/registrar_college',array('portal_notifications'))}}"><i class="fa fa-bell"></i> <span>Notifications</span></a></li>
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper">
                @yield('header')
                <section class="content container-fluid">
                    @yield('maincontent')
                </section>
            </div>
            <footer class="main-footer no-print">
                <div class="pull-right hidden-xs">
                    In partnership with <a href="http://nephilaweb.com.ph">Nephila Web Technology, Inc.</a>
                </div>
                <strong>Copyright &copy; 2018 <a href="http://assumption.edu.ph">Assumption College - San Lorenzo</a>.</strong> All rights reserved.
            </footer>

            <aside class="control-sidebar control-sidebar-dark no-print">
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <div class="tab-content no-print">
                </div>
            </aside>
            <div class="control-sidebar-bg no-print"></div>
        </div>
        <script src="{{ asset ('bower_components/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{ asset ('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset ('dist/js/adminlte.min.js')}}"></script>
        <script src="{{ asset ('bower_components/PACE/pace.min.js')}}"></script>
        <script>
           $(document).ajaxStart(function () {
               Pace.restart()
           })
        </script>
        <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
        <script>
           $(function () {
               $('.select2').select2();
           });
        </script>
        <script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
        @yield('footerscript')
    </body>
</html>
