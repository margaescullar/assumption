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
        ID Request
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>

    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Payment Pending</a></li>
            <li><a href="#tab_2" data-toggle="tab">Paid</a></li>
            <li><a href="#tab_3" data-toggle="tab">For Claiming</a></li>
            <li><a href="#tab_4" data-toggle="tab">Claimed</a></li>
            <!--<li class="pull-right"><a href="{{url('/bedregistrar',array('request_form','reports'))}}">Reports</a></li>-->
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
                @if(count($forms_request_pending)>0)
                <div class="box box-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-responsive">
                            <tr>
                                <th>Reference No</th>
                                <th>Date Requested</th>
                                <th>ID No</th>
                                <th>Requested By</th>
                                <th>Requests</th>
                                <th>Uploaded Image</th>
                                <th>Amount</th>
                                <th>Date Claimed</th>
                            </tr>
                            @foreach($forms_request_pending as $form_requested)
                            <tr>
                                <td>{{$form_requested->reference_id}}</td>
                                <td>{{$form_requested->created_at}}</td>
                                <td>{{$form_requested->idno}}</td>
                                <td>{{$form_requested->getFullNameAttribute()}}</td>
                                <td>Student ID x{{$form_requested->amount_pay/300}}</td>
                                <td><a target="_blank" href="{{url("https://portal.assumption.edu.ph/images/id_uploads/$form_requested->idno.jpg")}}"> {{$form_requested->idno}}.jpg</a></td>
                                <td>{{$form_requested->amount_pay}}</td>
                                <td>@if($form_requested->status==0) Payment Pending @elseif($form_requested->status==1) Paid @elseif($form_requested->status==2) For Claiming  on {{$form_requested->claiming_date}} @elseif($form_requested->status==3) Claimed @endif</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endif
            </div>    
            <div class="tab-pane" id="tab_2">
                @if(count($forms_request_paid)>0)
                <div class="box box-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-responsive">
                            <tr>
                                <th>Reference No</th>
                                <th>Date Requested</th>
                                <th>ID No</th>
                                <th>Requested By</th>
                                <th>Requests</th>
                                <th>Uploaded Image</th>
                                <th>Amount</th>
                                <th>OR Number</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @foreach($forms_request_paid as $form_requested)
                            <tr>
                                <td>{{$form_requested->reference_id}}</td>
                                <td>{{$form_requested->created_at}}</td>
                                <td>{{$form_requested->idno}}</td>
                                <td>{{$form_requested->getFullNameAttribute()}}</td>
                                <td>Student ID x{{$form_requested->amount_pay/300}}</td>
                                <td><a target="_blank" href="{{url("https://portal.assumption.edu.ph/images/id_uploads/$form_requested->idno.jpg")}}"> {{$form_requested->idno}}.jpg</a></td>
                                <td>{{$form_requested->amount_pay}}</td>
                                <td>{{$form_requested->or_number}}</td>
                                <td>@if($form_requested->status==0) Payment Pending @elseif($form_requested->status==1) Paid @elseif($form_requested->status==2) For Claiming  on {{$form_requested->claiming_date}} @elseif($form_requested->status==3) Claimed @endif</td>
                                <td>
                                    @if($form_requested->status==0)
                                    <!--<a href="javascript:void(0)" data-toggle="modal" data-target="#modal-view_form" onclick="getForm('{{$form_requested->reference_id}}')">Paid</a>-->
                                    @elseif($form_requested->status==1)
                                    <form method="post" action="{{url('tag_as_for_claiming_id')}}">
                                        {{csrf_field()}}
                                        <input type="date" name="date_for_claiming" required="">
                                        <input type="hidden" name="reference_id" value="{{$form_requested->reference_id}}">
                                        <button type="submit">For Claiming</button>
                                    </form>
                                    @elseif($form_requested->status==2)
                                    <a href="{{url('tag_as_claimed_id',$form_requested->reference_id)}}">Claim</a>
                                    @else
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endif
            </div>
            <div class="tab-pane" id="tab_3">
                @if(count($forms_request_for_claiming)>0)
                <div class="box box-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-responsive">
                            <tr>
                                <th>Reference No</th>
                                <th>Date Requested</th>
                                <th>ID No</th>
                                <th>Requested By</th>
                                <th>Requests</th>
                                <th>Uploaded Image</th>
                                <th>Amount</th>
                                <th>OR Number</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @foreach($forms_request_for_claiming as $form_requested)
                            <tr>
                                <td>{{$form_requested->reference_id}}</td>
                                <td>{{$form_requested->created_at}}</td>
                                <td>{{$form_requested->idno}}</td>
                                <td>{{$form_requested->getFullNameAttribute()}}</td>
                                <td>Student ID x{{$form_requested->amount_pay/300}}</td>
                                <td><a target="_blank" href="{{url("https://portal.assumption.edu.ph/images/id_uploads/$form_requested->idno.jpg")}}"> {{$form_requested->idno}}.jpg</a></td>
                                <td>{{$form_requested->amount_pay}}</td>
                                <td>{{$form_requested->or_number}}</td>
                                <td>@if($form_requested->status==0) Payment Pending @elseif($form_requested->status==1) Paid @elseif($form_requested->status==2) For Claiming  on {{$form_requested->claiming_date}} @elseif($form_requested->status==3) Claimed @endif</td>
                                <td>
                                    @if($form_requested->status==0)
                                    <!--<a href="javascript:void(0)" data-toggle="modal" data-target="#modal-view_form" onclick="getForm('{{$form_requested->reference_id}}')">Paid</a>-->
                                    @elseif($form_requested->status==1)
                                    <form method="post" action="{{url('tag_as_for_claiming_id')}}">
                                        {{csrf_field()}}
                                        <input type="date" name="date_for_claiming" required="">
                                        <input type="hidden" name="reference_id" value="{{$form_requested->reference_id}}">
                                        <button type="submit">For Claiming</button>
                                    </form>
                                    @elseif($form_requested->status==2)
                                    <a href="{{url('tag_as_claimed_id',$form_requested->reference_id)}}">Claim</a>
                                    @else
                                    @endif

                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endif
            </div>
            <div class="tab-pane" id="tab_4">
                @if(count($forms_request_claimed)>0)
                <div class="box box-body">
                    <div class="table-responsive">
                        <table class="table table-condensed table-responsive">
                            <tr>
                                <th>Reference No</th>
                                <th>Date Requested</th>
                                <th>ID No</th>
                                <th>Requested By</th>
                                <th>Requests</th>
                                <th>Uploaded Image</th>
                                <th>Amount</th>
                                <th>OR Number</th>
                                <th>Date Claimed</th>
                                <th>Status</th>
                            </tr>
                            @foreach($forms_request_claimed as $form_requested)
                            <tr>
                                <td>{{$form_requested->reference_id}}</td>
                                <td>{{$form_requested->created_at}}</td>
                                <td>{{$form_requested->idno}}</td>
                                <td>{{$form_requested->getFullNameAttribute()}}</td>
                                <td>Student ID x{{$form_requested->amount_pay/300}}</td>
                                <td><a target="_blank" href="{{url("https://portal.assumption.edu.ph/images/id_uploads/$form_requested->idno.jpg")}}"> {{$form_requested->idno}}.jpg</a></td>
                                <td>{{$form_requested->amount_pay}}</td>
                                <td>{{$form_requested->or_number}}</td>
                                <td>{{$form_requested->claim_date}}</td>
                                <td>@if($form_requested->status==0) Payment Pending @elseif($form_requested->status==1) Paid @elseif($form_requested->status==2) For Claiming  on {{$form_requested->claiming_date}} @elseif($form_requested->status==3) Claimed @endif</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>    
    </div>    
</div>

<!--View Form-->
<div class="modal fade" id="modal-view_form">
</div>


@endsection
@section('footerscript') 

<script>
    function getForm(reference_id) {
        var array = {};
        array['reference_id'] = reference_id;
        $.ajax({
            type: "get",
            url: "/get_requestforms",
            data: array,
            success: function (data) {
                $("#modal-view_form").html(data);
            }
        })
    }
</script>

@endsection