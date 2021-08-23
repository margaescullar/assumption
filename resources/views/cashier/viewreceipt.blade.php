<?php
$layout="";
if(Auth::user()->accesslevel == env("CASHIER")){
    $layout = "layouts.appcashier";
} else if (Auth::user()->accesslevel == env("ACCTNG_STAFF")){
    $layout="layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")){
    $layout="layouts.appaccountinghead";
}
?>
<?php $sy = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first()->school_year; ?>
@extends($layout)
@section('messagemenu')
 <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"></span>
            </a>
</li>
<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"></span>
            </a>
</li>
          
<li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger"></span>
            </a>
</li>
@endsection
@section('header')
<style>
   
</style>

<section class="content-header">
      <h1>
        View Receipt
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url("/cashier",array('viewledger',$sy,$payment->idno))}}"> Student Ledger</a></li>
        <li class="active">View Receipt</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif  
        @if (Session::has('danger'))
            <div class="alert alert-danger">{{ Session::get('danger') }}</div>
        @endif
        <div class="col-md-6">
            <a href='{{url('/view_previous_next_receipt',array("previous",$payment->reference_id))}}'><button class='btn btn-success'>Previous</button></a>
            <a href='{{url('/view_previous_next_receipt',array("next",$payment->reference_id))}}'><button class='btn btn-success pull-right'>Next</button></a>
        </div>
        <hr>
    <div class="col-md-6 official_receipt">
        
        <div class="col-md-2 image img-responsive"> <img width="86"src="{{url('/images','assumption-logo.png')}}" ></div>
        <div class="col-md-10"><div class="logo">Assumption College</div>
        San Lorenzo Drive, San Lorenzo Village<br> Makati City<br> NON VAT REG. TIN 000-662-720-000</div>
        <div class="col-md-4 pull-right">
            <div class="orno">OR No. : {{$payment->receipt_no}}</div>
        </div>
        <div class="col-md-12 orheader">OFFICIAL RECEIPT</div>
        <div class="col-md-12">
        <table class="table">
        <tr><th>Name</th><td><b> {{$payment->paid_by}}</b></td><td align="right">{{date('M d, Y',strtotime($payment->transaction_date))}}</td><tr>
        @if(count($status)>0)
        @if($status->status==3)
            @if($status->academic_type=="College")
            <tr><th>Course / Level</th><td>{{$status->program_code}} / {{$status->level}}</td><td></td></tr>
            @else
            <tr><th>Level / Section</th><td>{{$status->level}}</td><td></td></tr>
            @endif
            <tr><th>Plan</th><td>{{$status->type_of_plan}}</td><td></td></tr>
        @endif 
        @endif
        <tr><th></th><td align="right"></td></tr>
        </table>
       
        
        <?php $totalreceipt=0;?>
        <table class="table table-striped table-hover"><thead><tr><th>Particular</th><th>Amount</th></tr></thead>
        @if(count($receipt_details)>0)
            @foreach($receipt_details as $receipt_detail)
            <?php $totalreceipt=$totalreceipt+$receipt_detail->credit;?>
            <tr><td>Student Fees</td>
                <td align="right">{{number_format($receipt_detail->credit,2)}}</td></tr>
            @endforeach
        @endif
        @if(count($receipt_less)>0)
        <tr><td colspan="2">Less:</td></tr>
            @foreach($receipt_less as $less)
            <?php $totalreceipt=$totalreceipt-$less->debit;?>
            <tr><td>{{$less->receipt_details}}</td>
                <td>({{number_format($less->debit,2)}})</td></tr>
            @endforeach
        @endif
        <tr><td>Total</td><td align="right"><span class="totalreceipt">{{number_format($totalreceipt,2)}}</span></td><tr>
        </table>
        
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            <b>Explanation:<span class='pull-right'><button data-toggle="modal" data-target="#show_explanation">Edit Explanation</button></span></b><br>{{$payment->remarks}}
        </p>
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        <b>Payment Rendered:</b><br>  
        @if($payment->cash_amount>0) 
        Cash Received : {{number_format($payment->amount_received,2)}}<br>
        Change : {{number_format($payment->amount_received-$payment->cash_amount,2)}}<br>
        @endif
        @if($payment->check_amount>0)
        Bank : {{$payment->bank_name}}<br>
        Check No : {{$payment->check_number}}<br>
        Check Amount : {{number_format($payment->check_amount,2)}}<br>
        @endif
        @if($payment->credit_card_amount>0)
        Credit Card : {{$payment->credit_card_bank}} {{$payment->credit_card_type}}<br>
        Credit Card No : {{substr_replace($payment->credit_card_number,"***********",0,12)}}<br>
        Approval No : {{$payment->approval_number}}<br>
        Amount : {{number_format($payment->credit_card_amount,2)}}<br>
        @endif
        @if($payment->deposit_amount>0)
        Deposit Ref : {{$payment->deposit_reference}}<br>
        Deposit Amount : {{number_format($payment->deposit_amount,2)}}<br>
        @endif
        </p>
        <p class="" style="margin-top: 10px;">
            Posted by: <b>{{\App\User::where('idno',$payment->posted_by)->first()->firstname}} {{\App\User::where('idno',$payment->posted_by)->first()->lastname}}</b>
        </p>
        <div class="col-md-12">
            <div class="form form-group">
            <a class="btn btn-danger pull-right" target="_blank" href="{{url("/cashier",array("printreceipt",$payment->reference_id))}}">Print Receipt</a>
            @if(Auth::user()->accesslevel == env('ACCTNG_HEAD'))
            <a class="btn btn-primary" id="cancelrestore" href="{{url("/cashier",array("reverserestore",$payment->reference_id))}}">
            @if($payment->is_reverse=="0")    
                Cancel
            @else
                Restore
            @endif
            </a>
            @endif
            
            @if($payment->idno != 999999)
            <a class="btn btn-primary"  href="{{url("/cashier",array("viewledger",$sy,$payment->idno))}}">Back To Ledger</a>
            @endif
            </div>
            
        </div>
        <div class="col-sm-12">
            <a href="{{url('cashier',array('view_receipt','update_receipt',$payment->reference_id))}}"><button class="btn btn-warning col-sm-12">EDIT RECEIPT</button></a>
        </div>
        @if($payment->reason_reverse!="")
    <div class="alert alert-info col-md-12">Reason of Reverse/Cancellation:<button class="pull-right" data-toggle="modal" data-target="#show_reason">Edit Reason</button></span></b><br><b>{{$payment->reason_reverse}}</b></div> 
    @endif
    </div>
        
    </div>    
    <div class="col-md-6">
        <div class="box box-primary box-solid">
            <div class="box-header">
                <h5 class="box-title">Receipt Details</h5>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:70%">Particular</th>
                                <th style="text-align: right;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$accountings->isEmpty())
                            @foreach($accountings->where("credit",">",0) as $accounting)
                            <tr>
                                <td>{{$accounting->subsidiary}}</td>
                                <td style="text-align: right;">
                                    @if($accounting->credit > 0)
                                    {{number_format($accounting->credit,2)}}
                                    @else
                                    0
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            
                            <?php $totalless = 0;?>
                            @if(count($receipt_less)>0)
                                <tr>
                                    <td><b>Subtotal:</b></td>
                                    <td style="text-align: right; color:red;">{{number_format($accountings->where("credit",">",0)->sum("credit"),2)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>Less:<b></td>
                                </tr>
                                @foreach($receipt_less as $less)
                                <tr>
                                    <td>{{$less->receipt_details}}</td>
                                    <td style="text-align: right;">({{number_format($less->debit,2)}})</td>
                                </tr>
                                <?php $totalless += $less->debit; ?>
                                @endforeach
                            @endif
                            <?php $total = $accountings->where("credit",">",0)->sum("credit")-$totalless; ?>
                            <tr>
                                <td><b>Total:</b></td>
                                <td style="text-align: right; color:red;">{{number_format($total,2)}}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>   
</div>

<div class="modal fade" id="show_explanation">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Update Explanation</h4>
        </div>
        <form method="post" action="{{url('/update_explanation')}}">
        <div class="modal-body">
            <div class="form-group">
                {{csrf_field()}}
                <input type='hidden' value="{{$payment->reference_id}}" name='reference_id'>
                <input class='form form-control' type='text' name='explanation' value='{{$payment->remarks}}'>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
            <input type="submit" class="btn btn-primary" value="Update Explanation"></input>
        </div>
    </form>
    </div>
</div>
</div>

<div class="modal fade" id="show_reason">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Update Reason</h4>
        </div>
        <form method="post" action="{{url('/update_reason')}}">
        <div class="modal-body">
            <div class="form-group">
                {{csrf_field()}}
                <input type='hidden' value="{{$payment->reference_id}}" name='reference_id'>
                <input class='form form-control' type='text' name='reason_reverse' value='{{$payment->reason_reverse}}'>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
            <input type="submit" class="btn btn-primary" value="Update Reason"></input>
        </div>
    </form>
    </div>
</div>
</div>


@endsection
@section('footerscript')
<style>
 .table{border-color: #000;}
 .orno{text-align: right;
        color:#f00;
        font-size: 15pt;
 }
 .orheader{
     text-align: center;
     font-size: 18pt;
     font-weight: bold;
     text-decoration: underline;
 }
 .totalreceipt{
     color:darkblue;
     font-weight: bold;
     font-size: 12pt;
 }
 .official_receipt{
     padding: 10px;
     background-color: #fff;
     
 }
 .logo{
     font-size: 20pt;
     font-weight: bold;
     color: darkblue;
 }
</style>
<script>
  $(document).ready(function(){
     $("#cancelrestore").on('click',function(e){
         if(value = prompt("Please state your reason.")){
            
            array = {};
            array['payment_reference_id'] = "{{$payment->reference_id}}";
            array['reason'] = value;
            $.ajax({
            type: "GET",
            url: "/ajax/cashier/reason_reverserestore/",
            data: array,
            success: function (data) {
            }
            });
            if(confirm("Are you sure?")){
                return true;
            }else{
                return false;
                e.preventDefault();
            }
         }else{
             return false;
             e.preventDefault();
         }
     }) 
  }); 
</script>    
@endsection


