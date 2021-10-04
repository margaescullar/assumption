<?php
    if(Auth::user()->accesslevel == env('OSA')){
    $layout = "layouts.apposa";
    } else {
    $layout = "layouts.appbedregistrar";
    }
?>

@extends($layout)
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">1</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 1 messages</li>
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
                            <small><i class="fa fa-clock-o"></i> 1 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Welcome to Assumption Student Portal!!<br>More functionality and features coming.<br>Enjoy!!</p>
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
        Request Form->Settings
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>

    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-6">
    @if(count($forms)>0)
    <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <tr>
                    <td>Group</td>
                    <td>Name</td>
                    <td>Price</td>
                    <td>Requirements</td>
                    <td></td>
                </tr>
                @foreach($forms as $form)
                <tr>
                    <td>{{$form->document_group}}</td>
                    <td>{{$form->document_name}}</td>
                    <td>{{$form->cost}}</td>
                    <td>{{$form->requirements}}</td>
                    <td><a href="javascript:void()" onclick="getFormDetails('{{$form->id}}')">Edit</a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
</div>
<div class="col-sm-6" id="display_details">
    <div class="box">
        <div class="box-body">
            <form method="post" action="{{url('update_form_details')}}" class="form">
                {{csrf_field()}}
                <div class="form-group">
                    <label>Document Group</label>
                    <input class="form-control" name="document_group">
                </div>
                <div class="form-group">
                    <label>Document Name</label>
                    <input class="form-control" name="document_name" Required="">
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input class="form-control" name="cost" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Requirements</label>
                    <input class="form-control" name="requirements">
                </div>
                <div class="form-group">
                    <input type="submit" name="button" value="Add" class="pull-right btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('footerscript') 

<script>
    function getFormDetails(id) {
    var array = {};
    array['id'] = id;
    $.ajax({
    type: "get",
            url: "/get_form_details",
            data: array,
            success: function (data) {
            $("#display_details").html(data);
            }
    })
    }
</script>

@endsection