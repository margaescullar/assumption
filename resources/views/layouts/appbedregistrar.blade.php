<?php
$file_exist = 0;
if (file_exists(public_path("images/" . Auth::user()->idno . ".jpg"))) {
    $file_exist = 1;
}
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
?>
<?php $date_today = date('Y-m-d'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College - Registrar</title>
        <link rel="shortcut icon" type="image/jpg" href="{{url('/images','assumption-logo.png')}}">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{url('/bower_components',array('bootstrap','dist','css','bootstrap.min.css'))}}">
        <link rel="stylesheet" href="{{url("/bower_components",array("font-awesome","css","font-awesome.min.css"))}}">
        <link rel="stylesheet" href="{{url("/bower_components",array("Ionicons","css","ionicons.min.css"))}}">
        <link rel="stylesheet" href="{{url("dist",array("css","AdminLTE.min.css"))}}">
        <link rel="stylesheet" href="{{url("dist",array("css","skins","skin-blue.min.css"))}}">
        <link rel="stylesheet" href="{{url("/bower_components", array("datatables.net-bs","css","dataTables.bootstrap.min.css"))}}">
        <link rel="stylesheet" href="{{url('/dist',array('css','skins','_all-skins.min.css'))}}">
        <link rel="stylesheet" href="{{url('/dist',array('css','AdminLTE.min.css'))}}">
        <link rel="stylesheet" href="{{url('/bower_components',array('select2','dist','css','select2.min.css'))}}">
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <a href="{{url('/')}}" class="logo">
                    <span class="logo-mini"><b>R</b>EG</span>
                    <span class="logo-lg"><b>BED-R</b>EGISTRAR</span>
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            @yield('messagemenu')



                            <!-- User Account Menu -->
                            <li class="dropdown user user-menu">
                                <!-- Menu Toggle Button -->
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <!-- The user image in the navbar-->
                                    @if($file_exist==1)
                                    <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="user-image" alt="User Image">
                                    @else
                                    <img class="user-image" width="25" height="25" alt="User Image" src="/images/default.png">
                                    @endif
                                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                    <span class="hidden-xs">{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- The user image in the menu -->
                                    <li class="user-header">
                                        @if($file_exist==1)
                                        <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="user-image" alt="User Image">
                                        @else
                                        <img class="user-image" width="25" height="25" alt="User Image" src="/images/default.png">
                                        @endif

                                        <p>
                                            {{Auth::user()->lastname}}, {{Auth::user()->firstname}}
                                            <small>BED Registrar</small>
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
                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="javascript:void(0)" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">

                    <!-- Sidebar user panel (optional) -->
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
                            <!-- Status -->
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>



                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MENU</li>
                        <!-- Optionally, you can add icons to the links -->
                        <li><a href="{{url('/')}}"><i class="fa fa-link"></i> <span>Home</span></a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Enrollment Statistics</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/bedregistrar',array('enrollment_statistics',$school_year->school_year))}}">Enrollment Statistics</a></li>
                                <li><a href="{{url('/bed_registrar', array('reports', 'total_daily_enrollment_statistics', $date_today, $date_today))}}">Daily Enrollment Statistics</a></li>
                            </ul>
                        </li>
                        <!--<li><a href="{{url('/bedregistrar','registration')}}"><i class="fa fa-link"></i> Registration</a></li>-->
                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Student List</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/bedregistrar',array('student_list'))}}">Per Grade Level and Section </a></li>
                                <li><a href="{{url('/bedregistrar',array('withdrawn_students'))}}">Withdrawn Students</a></li>
                                <li><a href="{{url('/bedregistrar',array('assessed_students'))}}">Assessed Students</a></li>
                                <li><a href="{{url('/bedregistrar',array('not_yet_enrolled'))}}">Students Not Yet Enrolled</a></li>
                                <li><a href="{{url('/accounting',array('outstanding_balances'))}}">Outstanding Balances</a></li>
                                <li><a href="{{url('/bedregistrar',array('directory'))}}">Directory</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Grades</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/bedregistrar','report_card')}}"></i> Report Card</a></li>
                                <li><a href="{{url('/bedregistrar','batch_ranking')}}"></i> Batch Ranking</a></li>
                                <li class="treeview">
                                    <a href="#"></i> Grade Summary<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                                    <ul class="treeview-menu">
                                        <li><a href="{{url('/bedregistrar','grade_summary')}}"></i> Quarter Summary</a></li>
                                        <li><a href="{{url('/bedregistrar', array('all_term_summary'))}}"></i> All-Term Summary</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{url('/bedregistrar','sac_grade_summary')}}"></i> SAC Grade Summary</a></li>
                                <li><a href="{{url('/bedregistrar','conduct_grade_summary')}}"></i> Conduct Grade Summary</a></li>
                                <li><a href="{{url('/bedregistrar','shs_honor')}}"></i> SHS Honor</a></li>
                                <li><a href="{{url('/bedregistrar','hold_students')}}"></i> Hold Students</a></li>
                                <li><a href="{{url('/bedregistrar','grade_portal_display_settings')}}"></i> Portal Display Settings</a></li>
                                <li><a href="{{url('/bedregistrar','report_card_sequencing')}}"></i> Report Card Sequencing</a></li>
                            </ul>
                        </li>
                        <li><a href="{{url('/bedregistrar','sectioning')}}"><i class="fa fa-link"></i> Sectioning</a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Promotions</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/bedregistrar',array('batch_promotions'))}}"></i> <span>Batch Promotion</span></a></li>
                                <li><a href="{{url('/bedregistrar',array('individual_promotions'))}}"></i> <span>Individual Promotion</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Benefits/Siblings</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/bedregistrar',array('benefits'))}}"></i> <span>Benefits Scholar</span></a></li>
                                <li><a href="{{url('/bedregistrar',array('siblings'))}}"></i> <span>Siblings Discount</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Cluster Leads</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/bedregistrar',array('class_leads'))}}"></i> <span>Cluster Leaders</span></a></li>
                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#"><i class="fa fa-link"></i> <span>Other Reports</span>   
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/accounting',array('unused_reservations'))}}"><span>Reservations</span></a></li>
                            </ul>
                        </li>
                        <li><a href="{{url('/bedregistrar',array('request_form'))}}"><i class="fa fa-link"></i> <span>Credential Request</span></a></li>
                        <li><a href="{{url('/bedregistrar',array('external_form'))}}"><i class="fa fa-link"></i> <span>External Forms</span></a></li>
                        <li><a href="{{url('/bed_portal_notifications/records')}}"><i class="fa fa-link"></i> <span>Notifications</span></a></li>


                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                @yield('header')

                <!-- Main content -->
                <section class="content container-fluid">

                    <!--------------------------
                    | Your Page Content Here |
                    -------------------------->
                    @yield('maincontent')

                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Main Footer -->
            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                    In partnership with <a href="http://nephilaweb.com.ph">Nephila Web Technology, Inc.</a>
                </div>
                <!-- Default to the left -->
                <strong>Copyright &copy; 2018 <a href="http://assumption.edu.ph">Assumption College - San Lorenzo</a>.</strong> All rights reserved.
            </footer>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Create the tabs -->
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Home tab content -->
                    <div class="tab-pane active" id="control-sidebar-home-tab">
                        <h3 class="control-sidebar-heading">Recent Activity</h3>
                    </div>

                    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                    <!-- /.tab-pane -->
                    <!-- Settings tab content -->
                    <div class="tab-pane" id="control-sidebar-settings-tab">

                    </div>
                    <!-- /.tab-pane -->
                </div>
            </aside>
            <!-- /.control-sidebar -->
            <!-- Add the sidebar's background. This div must be placed
            immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED JS SCRIPTS -->

        <!-- jQuery 3 -->
        <script src="{{url("/bower_components",array("jquery","dist","jquery.min.js"))}}"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="{{url("/bower_components",array("bootstrap","dist","js","bootstrap.min.js"))}}"></script>
        <!-- AdminLTE App -->
        <script src="{{url("/dist",array("js","adminlte.min.js"))}}"></script>
        @yield('footerscript')

    </body>
</html>

