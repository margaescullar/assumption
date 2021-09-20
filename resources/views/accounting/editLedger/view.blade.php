<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
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
        Add To Account
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Edit Ledger</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    @if (Session::has('warning'))
    <div class="alert alert-warning">{{ Session::get('warning') }}</div>
    @endif
    @if (Session::has('message'))
    <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">  
            <div class="col-sm-8">
                <form class="form-horizontal" method="post" action="{{url('accounting', array('edit_ledger_now'))}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$ledger->id}}">
                    <input type="hidden" name="idno" value="{{$status->idno}}">
                    <input type="hidden" name="academic_type" value="{{$status->academic_type}}">

                    <div class="form-group">
                        <label>{{$ledger->subsidiary}}</label>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Discount</th>
                                    <th>Debit Memo</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$ledger->subsidiary}}</td>
                                    <td><input class="form form-control" type="text" name="amount" value="{{$ledger->amount}}" placeholder="Amount"></td>
                                    <td><input class="form form-control" type="text" name="discount" value="{{$ledger->discount}}" placeholder="Discount"></td>
                                    <td><input class="form form-control" type="text" name="debit_memo" value="{{$ledger->debit_memo}}" placeholder="Debit Memo"></td>
                                    <td><input class="form form-control" type="text" name="payment" value="{{$ledger->payment}}" placeholder="Payment"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
<!--                    <div class="form-group col-sm-6 col-sm-offset-6">
                        <label>Enter Passcode:</label>
                        <input class="form form-control" type="text" name="passcode" placeholder="Requested passcode from Accounting Head">
                    </div>-->
                    <div class="form-group col-sm-12">
                        <input type="submit" value="Update Ledger" name="submit" class="col-sm-4 btn btn-success">
                    </div>
                    @if($ledger->discount > 0 || $ledger->debit_memo > 0 || $ledger->payment > 0)
                    <div class="form-group col-sm-12">
                        <input type="submit" value="Remove Ledger" name="submit" class="col-sm-4 btn btn-danger" disabled="">
                    </div>
                    @else
                    <div class="form-group col-sm-12">
                        <input type="submit" value="Remove Ledger" name="submit" class="col-sm-4 btn btn-danger">
                    </div>
                    @endif


                </form>
            </div>
        </div>
    </div>        
</div>
@endsection
@section('footerscript')
<style>
    .payment{
        color:#f00;
        font-weight: bold;
    }
</style>
@endsection


