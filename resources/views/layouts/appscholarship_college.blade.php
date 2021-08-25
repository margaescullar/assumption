<?php
$file_exist = 0;
if (file_exists(public_path("images/" . Auth::user()->idno . ".jpg"))) {
    $file_exist = 1;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College - Scholarship College</title>
        <link rel="shortcut icon" type="image/jpg" href="{{url('/images','assumption-logo.png')}}">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{url("/bower_components",array("bootstrap","dist","css","bootstrap.min.css"))}}">
        <link rel="stylesheet" href="{{url("/bower_components",array("font-awesome","css","font-awesome.min.css"))}}">
        <link rel="stylesheet" href="{{url("/bower_components",array("Ionicons","css","ionicons.min.css"))}}">
        <link rel="stylesheet" href="{{url("/dist",array("css","AdminLTE.min.css"))}}">
        <link rel="stylesheet" href="{{url("/dist",array("css","skins","skin-blue.min.css"))}}">
        <link rel="stylesheet" href="{{ asset ('plugins/pace/pace.min.css')}}">
        <link rel="stylesheet"href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <a href="{{url('/')}}" class="logo">
                    <span class="logo-mini"><b>A</b>CS</span>
                    <span class="logo-lg"><b>Scholarship</b>COLLEGE</span>
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
                                            <small>Scholarship - College</small>
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
            <aside class="main-sidebar">
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
                            <a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span>   
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/scholarship_college',array('report', 'scholarship_report'))}}"><span>List of Scholars</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper">
                @yield('header')
                <section class="content container-fluid">
                    @yield('maincontent')
                </section>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    In partnership with <a href="http://nephilaweb.com.ph">Nephila Web Technology, Inc.</a>
                </div>
                <strong>Copyright &copy; 2018 <a href="http://assumption.edu.ph">Assumption College - San Lorenzo</a>.</strong> All rights reserved.
            </footer>
            <aside class="control-sidebar control-sidebar-dark">
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="control-sidebar-home-tab">
                        <h3 class="control-sidebar-heading">Recent Activity</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript:;">
                                    <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                        <p>Will be 23 on April 24th</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <h3 class="control-sidebar-heading">Tasks Progress</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript:;">
                                    <h4 class="control-sidebar-subheading">
                                        Custom Template Design
                                        <span class="pull-right-container">
                                            <span class="label label-danger pull-right">70%</span>
                                        </span>
                                    </h4>
                                    <div class="progress progress-xxs">
                                        <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                    <div class="tab-pane" id="control-sidebar-settings-tab">
                        <form method="post">
                            <h3 class="control-sidebar-heading">General Settings</h3>
                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Report panel usage
                                    <input type="checkbox" class="pull-right" checked>
                                </label>
                                <p>
                                    Some information about this general settings option
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
            <div class="control-sidebar-bg"></div>
        </div>
        <script src="{{url("/bower_components",array("jquery","dist","jquery.min.js"))}}"></script>
        <script src="{{url("/bower_components",array("bootstrap","dist","js","bootstrap.min.js"))}}"></script>
        <script src="{{url("/dist",array("js","adminlte.min.js"))}}"></script>
        <script src="{{ asset('bower_components/PACE/pace.min.js')}}"></script>
        <script>
            $(document).ajaxStart(function () {
                Pace.restart()
            });
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
