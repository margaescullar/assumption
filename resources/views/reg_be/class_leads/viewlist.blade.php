@extends("layouts.appbedregistrar")
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                <li><!-- start message -->
                    <a href="#">
                        <div class="pull-left">
                            <!-- User Image -->

                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
                    </a>
                </li>
                <!-- end message -->
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Cluster Leads
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cluster Leads</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    <div class="box">
        <div class="box-body">
            @if (count($errors) > 0)
            <div class = "alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class = "alert alert-info">
                <ul>
                    <li>Default Password will be the User ID</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="form form-group">
                    <input type="text" name="idno" id="idno" class="form-control" placeholder="User ID">
                    <input type="hidden" name="id" id="id">
                </div>    
            </div>  
            <div class="col-md-12">   
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="First Name">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name="middlename" id="middlename" class="form-control" placeholder="Middle Name">
                    </div>
                </div> 

                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Last Name">
                    </div>
                </div> 
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                    </div>
                </div> 
                <div class="col-md-2">
                    <div class="form-group">
                        <input type="button" name="button" id="button" value="Add" class="form-control btn btn-success">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div id="user_list">
                    @include('reg_be.class_leads.ajax.getpersonellist')
                </div>    
            </div>    
            <div class="modal fade" id="modal-default">
            </div>
        </div>    
    </div> 
</div>    

@endsection
@section('footerscript')
<script>
    $(document).ready(function () {
        $("#cancel").fadeOut();

        $("#user_list").on('click', '.modify', function () {

            id = $(this).attr('reference');
            array = {};
            array['id'] = id;
            $.ajax({
                type: "GET",
                url: "/modify_class_leads_level",
                data: array,
                success: function (data) {
                    $("#button").val("Modify");
                    $("#id").val(id);
                    $("#idno").val(data.idno);
                    $("#firstname").val(data.firstname);
                    $("#middlename").val(data.middlename);
                    $("#lastname").val(data.lastname);
                    $("#email").val(data.email);
                    $("#cancel").fadeIn();
                }
            })

        })

        $("#cancel").on("click", function () {
            $("#button").val("Add");
            $("#id").val("");
            $("#idno").val("");
            $("#firstname").val("");
            $("#middlename").val("");
            $("#lastname").val("");
            $("#email").val("");
            $("#cancel").fadeOut();

        })

        $("#button").on("click", function () {
            array = {};
            array['id'] = $("#id").val();
            array['idno'] = $("#idno").val();
            array['firstname'] = $("#firstname").val();
            array['middlename'] = $("#middlename").val();
            array['lastname'] = $("#lastname").val();
            array['email'] = $("#email").val();
            array['button'] = $("#button").val();
            $.ajax({
                type: "GET",
                url: "/update_class_leads",
                data: array,
                success: function (data) {
                    $("#user_list").html(data);
                    $("#button").val("Add");
                    $("#id").val("");
                    $("#idno").val("");
                    $("#firstname").val("");
                    $("#middlename").val("");
                    $("#lastname").val("");
                    $("#email").val("");
                    $("#cancel").fadeOut();
                }
            })
        })

        $("#user_list").on('click', '.reset_password', function () {
            if (confirm("Are You Sure?")) {
                array = {}
                array['id'] = $(this).attr('reference');
                $.ajax({
                    type: 'GET',
                    url: '/resetpassword',
                    data: array,
                    success: function (data) {
                        alert("Successfully Reset")
                    }
                })
            }
        })

        $("#user_list").on("click", '.remove', function () {
            if (confirm("Are you sure to remove this user?")) {
                array = {}
                array['id'] = $(this).attr('reference');
                $.ajax({
                    type: 'GET',
                    url: '/remove_user',
                    data: array,
                    success: function (data) {
                        $("#user_list").html(data)
                        alert('Successfully removed')

                    }
                })
            }
        })

        $("#user_list").on('click', '.assign', function () {
            idno = $(this).attr('reference');
            array = {};
            array['idno'] = idno;
            $.ajax({
                type: "GET",
                url: "/modify_class_leads_level",
                data: array,
                success: function (data) {
                    $("#modal-default").html(data)
                }
            })

        })
    })
</script>    
@endsection
