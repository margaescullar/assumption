<?php
$other=0;
$miscellaneous=0;
$depository=0;
$optional=0;
$srf=0;
$tuition=0;

if($other_fee_total->balance>0)
    $other = $other_fee_total->balance;

if($optional_fee_total->balance>0)
    $optional = $optional_fee_total->balance;

if($miscellaneous_fee_total->balance>0)
    $miscellaneous=$miscellaneous_fee_total->balance;

if($depository_fee_total->balance>0)
    $depository=$depository_fee_total->balance;

if($srf_total->balance>0)
    $srf=$srf_total->balance;

if($tuition_fee_total->balance>0)
    $tuition=$tuition_fee_total->balance;

$total_max = $other+$miscellaneous+$depository+$srf+$tuition+$optional;

$previousarray = array();
if(!$previous_total->isEmpty()){
    foreach($previous_total as $previous){
        $previousarray[$previous->school_year] = $previous->balance;
    }
}
$previousarray = json_encode($previousarray);
?>
<?php $sy = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first()->school_year; ?>
@extends('layouts.appcashier')
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
        Payment
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url("/cashier",array('viewledger',$sy,$user->idno))}}"> Student Ledger</a></li>
        <li class="active">Main Payment</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
  <form id="paymentform" class="form-horizontal" method="POST" action="{{url('/cashier','main_payment')}}">
    <div class="col-md-12">
        <div class="col-md-6">
            <table class="table table-responsive">
                <!--<tr><td>Date : </td><td>{{date("M d, Y")}}</td></tr>-->
                <tr><td>Date(YYYY-MM-DD) : </td><td><input type="text" name="date" id="date" value="{{date('Y-m-d')}}"></td></tr>
                    <tr><td>Student ID : </td><td>{{$user->idno}}</td></tr>
                    <tr><td>Student Name : </td><td>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</td></tr>
                    </table>
        </div>  
        <div class="col-md-6"><div class="nav navbar pull-right"> Receipt No: <span style="font-size:20pt;font-weight:bold;color:red">{{$receipt_number}}</span></div></div>
    </div>    
   <hr />  
  
      {{csrf_field()}}
           <input type="hidden" name="idno" value="{{$user->idno}}">
           <input type="hidden" name="receipt_no" value="{{$receipt_number}}">
           <input type="hidden" name="level" value="{{$status->level}}">
    <div class="col-md-6">
        <div id="detailed_form">   
            <div class="form form-group">    
                <div class="crcform">

                    @if(!$previous_total->isEmpty())
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Previous Fees :</span>
                    </div> 
                    <div class="col-md-6">
                        <input type="text" class="form form-control number" name="previous_balance" id="previous_balance" value="{{$previous_total->sum("balance")}}" >
                    </div> 
                    </div>
                    
                    <div class="col-md-12">
                        
                        <table class="table table-bordered fees">
                            <tr><td width="33%" align="right">School Year</td><td width="33%" align="right">Balance</td><td align="right">Amount</td></tr>
                            @foreach($previous_total as $previous_fee)
                            <tr><td align="right">
                                    @if($previous_fee->school_year == "2020-2021 - 2nd Semester")
                                    {{$previous_fee->school_year}}
                                    @else
                                    {{$previous_fee->school_year}} - {{$previous_fee->school_year+1}}
                                    @endif
                                </td>
                                <td align="right">{{number_format($previous_fee->balance,2)}}</td><td><input onkeypress="do_previous(event,{{$previous_fee->balance}},this.value,this)" type="text" name="previous_sy[{{$previous_fee->school_year}}]" id="previous{{$previous_fee->school_year}}" class="form form-control number previous"></tr>
                            @endforeach
                        </table>        
                    </div>
                    @else
                    <input type="hidden" id="previous_balance" name="previous_balance" value="0.00">
                    @endif
                    
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Main Fees :</span>
                    </div> 
                    <div class="col-md-6">
                        <input type="text" class="form form-control number" name="main_due" id="main_due" value="{{$due_total}}" >
                    </div> 
                    </div>
                    
                    <div class="col-md-12">
                        
                        <table class="table table-bordered fees">
                            <tr><td width="33%" align="right">Fees</td><td width="33%" align="right">Balance</td><td align="right">Amount</td></tr>
                            <tr><td align="right">Miscellaneous Fee</td><td align="right">{{number_format($miscellaneous_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$miscellaneous_fee_total->balance}},this.value,this)" type="text" name="miscellaneous" id="miscellaneous" class="form form-control number"></tr>
                            <tr><td align="right">Other Fee</td><td align="right">{{number_format($other_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$other_fee_total->balance}},this.value,this)" type="text" name="other_fee" id="other_fee" class="form form-control number"></tr>
                            <tr><td align="right">Depository Fee</td><td align="right">{{number_format($depository_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$depository_fee_total->balance}},this.value,this)" type="text" name="depository" id="depository" class="form form-control number"></tr>
                            <tr><td align="right">Additional Fee<br>(SRF, Family Council, Foreign Fee, Tutorial Fee)</td><td align="right">{{number_format($srf_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$srf_total->balance}},this.value,this)" type="text" name="srf" id="srf" class="form form-control number"></tr>
                            <tr><td align="right">Optional Fee<br>(Books/Materials/Uniforms)</td><td align="right">{{number_format($optional_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$optional_fee_total->balance}},this.value,this)" type="text" name="optional" id="optional" class="form form-control number"></tr>
                            <tr><td align="right">Tuition Fee</td><td align="right">{{number_format($tuition_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$tuition_fee_total->balance}},this.value,this)" type="text" name="tuition" id="tuition" class="form form-control number"></tr>
                        </table>        
                    </div>  
                    
                    @if(count($other_misc)>0)
                    
                    <div class="form form-group">
                    <div class="col-md-12">
                        <span class="label_collected">Other Fees :</span>
                    </div> 
                    </div>    
                   <div class="col-md-12">    
                        <table class="table table-bordered fees"><tr><td align="right">Particular</td><td></td><td align="right">Amount</td></tr>
                           @foreach($other_misc as $om)
                           <tr><td  width="33%" align="right">{{$om->receipt_details}}</td><td width="33%"></td><td><input class="form form-control number other_misc" type="text"  id="other_misc[]" name="other_misc[{{$om->id}}]" onkeypress="do_other(event,{{$om->amount-$om->discount-$om->debit_memo-$om->payment}},this.value,this)" value="{{$om->amount-$om->discount-$om->debit_memo-$om->payment}}"></td></tr>
                             @endforeach
                        </table>     
                    </div> 
  
                     
                   
                   
                    @endif
                    <?php /*
                    @if(count($reservation)>0)
                    @if($reservation->amount>0)
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Reservation :</span>
                    </div> 
                     <div class="col-md-6">
                         <input type="hidden"  name="reservation" value="{{$reservation->amount}}">
                         <div class="form form-control number">{{number_format($reservation->amount,2)}}</div>
                     </div>
                     </div>   
                    @else
                        <input type="hidden"  name="reservation" value="0.00">
                    @endif
                    @else
                        <input type="hidden"  name="reservation" value="0.00">
                    @endif
                    
                    @if(count($deposit)>0)
                    @if($deposit->amount>0)
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Student Deposit :</span>
                    </div> 
                     <div class="col-md-6">
                         <input type="hidden"  name="deposit" value="{{$deposit->amount}}">
                         <div class="form form-control number">{{number_format($deposit->amount,2)}}</div>
                     </div>
                     </div>   
                    @else
                    <input type="hidden"  name="deposit" value="0.00">
                    @endif
                    @else
                        <input type="hidden"  name="deposit" value="0.00">
                    @endif*/?>
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Amount To Be Paid :</span>
                    </div> 
                    <div class="col-md-6">
                        <input type="text" class="form form-control number" id="collected_amount" name="collected_amount" disabled="disabled">
                    </div> 
                    </div> 
                    <div id="donereg">
                    <div class="form form-group">
                        <div class="col-md-6">
                            <span class="label_collected">Explanation :</span>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form form-control" name="remark" id="explanation">
                        </div>
                    
                    </div>
                    </div>        
                </div>
            </div>  
        </div>    
   </div>
   <div class="col-md-6">
      <div id="payment_pad"> 
        <div class="cash-payment">
        <div class="form form-group">
            <div class="col-md-6"> 
            <label>Cash Receive</label>
                <input type="text" name="cash_receive" id="cash_receive" class="form form-control number" />
            </div>
            <div class="col-md-6"> 
            <label>Change</label>
            <input type="text" name="change" id="change" class="form form-control change" readonly="readonly" />
            </div>
        </div> 
   </div>
    
    
   <div class="check_payment">
       <label>Check Payment</label>
        <div class="form form-group">
            <div class="col-md-6">
                <label>Bank</label>
                <input type="text" name="bank" id="bank" class="form form-control" />
            </div>
            
            <div class="col-md-6">
                <label>Check Number</label>
                <input type="text" name="check_number" id="check_number" class="form form-control" />
            </div>
            
            <div class="col-md-12">
                <label>Check Amount</label>
                <input type="text" name="check_amount" id="check_amount" class="form form-control number" />
            </div>
        </div>    
   </div>
    
    <div class="credit_card">
        <label>Credit Card Payment</label> 
        <div class="form form-group">
            <div class="col-md-2">
               <label>Bank</label>
               <input type="text" name="credit_card_bank" id="credit_card_bank" class="form form-control" />
            </div>
            <div class="col-md-3">
                <label>Type</label>
               <select class="form-control" name="credit_card_type" id="credit_card_type">
                   <option>Visa</option>
                   <option>Mastercard</option>
                   <option>Debit</option>
               </select>    
            </div>    
            <div class="col-md-4">    
            <label>Card Number</label>
            <input type="text" name="card_number" id="card_number" class="form form-control" />
            </div>
            <div class="col-md-3">
            <label>Approval No</label>
            <input type="text" name="approval_number" id="approval_number" class="form form-control" />
            </div>
            <div class="col-md-12">
            <label>Credit Card Amount</label>
            <input type="text" name="credit_card_amount" id="credit_card_amount" class="form form-control number" />
            </div>
            </div>
     </div> 
    <div class="bank_deposit">
        <label>Bank Deposit</label>
        <div class="form form-group">
            <div class="col-md-6">
               <label>Deposit Reference No</label>
               <input type="text" name="deposit_reference" id="deposit_reference" class="form form-control" />
            </div>
            <div class="col-md-6">
            <label>Deposit Amount</label>
            <input type="text" name="deposit_amount" id="deposit_amount" class="form form-control number" />
            </div>
        </div>    
    </div> 
    <div class="over_payment">
        <div class="form form-group">
        
        <div class="col-md-12">
            <label>Over Payment</label>
            <input type="text" name="over_payment" id="over_payment" class="form form-control number" value="0" readonly="readonly">
        </div>
        </div>
    </div>    
     <div class="submit_button">
        <div class="form form-group">
            <div class="col-md-12"> 
                <input type="submit" name="submit" id="submit_button" value="Process Payment" class="btn btn-warning form form-control">
            </div>
        </div>
     </div>    
   </div>   
   </div>
</form>

</div>

@endsection
@section('footerscript')
<link rel="stylesheet" href="{{url('/',array('bower_components','select2','dist','css','select2.min.css'))}}">
<style>
    .fees td input{
        background-color: #ccc;
    }
    .label_collected{
        font-size:15pt;
        font-weight: bold;
    }
    .submit_button{
        padding-top:10px;
    }
    .check_payment{
        background-color:#d3d3d3;
        padding: 10px;
        
    }
    .credit_card{
        background-color:#b1dae7;
        padding: 10px;
    }
    .top-payment{
         background-color: #E9C062;
        padding: 10px; 
    }
    .cash-payment{
        background-color: #b1dae7;
        padding: 10px
    }
    .bank_deposit{
        background-color: #d3d3d3;
        padding: 10px
    }
    .number{
        text-align: right;
    }
    .change{
        text-align:right;
        color:#f00;
        font-weight: bold;
    }
    #collected_amount{
        color:#f00;
        font-weight: bold;
        font-size: 12pt;
    }
    .over_payment{
        background-color: #B995A9;
        padding: 10px
    }
</style>

<script src="{{url('/',array('bower_components','select2','dist','js','select2.full.min.js'))}}"></script>

<script>
    var i = 1;
    var main_total_max={{$total_max}};
    var other_total_max={{$other}};
    var misc_total_max={{$miscellaneous}};
    var depository_total_max={{$depository}};
    var optional_total_max={{$optional}};
    var srf_total_max={{$srf}};
    var tuition_total_max={{$tuition}};
    
    
    $(document).ready(function(){ 
        jQuery('input[type=submit]').click(function(){
            if(jQuery.data(this, 'clicked')){
                return false;
            } else{
                jQuery.data(this, 'clicked', true);
                return true;
            }
        });
        $("#submit_button").fadeOut(300);
        $("#donereg").fadeOut(300);
        $("#payment_pad").fadeOut(300);
        if($("#previous_balance").val()>0){
            $("#previous_balance").focus();
        }else{
            $("#main_due").focus();
        }
        computeSubaccount();
        
        $("#previous_balance").on('keypress',function(e){
            if(e.keyCode==13){
                var desiredpreviousfee = parseFloat($(this).val());
                
                if($("#previous_balance").val()==""){
                    $("#previous_balance").val("0.00");
                }
                
                var previousarray = "{{$previousarray}}";
                previousarray = JSON.parse(previousarray.replace(/&quot;/g,'"'));

                for (var key in previousarray) {
                    if (previousarray.hasOwnProperty(key)) {
                        
                        if(desiredpreviousfee >= previousarray[key]){
                            $("#previous"+key).val(parseFloat(previousarray[key],2));
                            desiredpreviousfee = desiredpreviousfee - previousarray[key];
                        }else{
                            $("#previous"+key).val(parseFloat(desiredpreviousfee,2));
                            desiredpreviousfee = 0;
                        }

                    }
                }
                $("#main_due").focus();
                e.preventDefault();
            }
        });
        
        $("#date").on('keypress',function(e){
            if(e.keyCode==13){
                $("#main_due").focus();
                e.preventDefault();
            }
        });
        
        $("#main_due").on('keypress',function(e){
            if(e.keyCode==13){
                if($("#main_due").val()==""){
                    $("#main_due").val("0.00");
                }
                if($("#main_due").val() > main_total_max){
                    alert("Amount Should Not Be Greater Than " + "{{number_format($total_max,2)}}" )
                } else {
                    computeSubaccount();
                    computeToBePaid();
                }
                
                e.preventDefault();
            }
        });
        $("#explanation").on('keypress',function(e){
            if(e.keyCode==13){
                if($("#explanation").val() == ""){
                    alert("Please Fillup Details");
                }else{
                    var total = parseFloat($("#miscellaneous").val()) + parseFloat($("#other_fee").val())+ parseFloat($("#depository").val()) + parseFloat($("#optional").val()) + parseFloat($("#srf").val())+ parseFloat($("#tuition").val())
                    var main_due = parseFloat($("#main_due").val())
                    if( main_due.toFixed(2) == total.toFixed(2)){
                    computeToBePaid()
                    $("#previous_balance").attr('readonly', true);
                    $("#main_due").attr('readonly', true);
                    $("#miscellaneous").attr('readonly', true);
                    $("#other_fee").attr('readonly', true);
                    $("#depository").attr('readonly', true);
                    $("#srf").attr('readonly', true);
                    $("#optional").attr('readonly', true);
                    $("#tuition").attr('readonly', true);
//                    $("#other_misc[]").attr('readonly', true);
                    $("#payment_pad").fadeIn();
                    $("#cash_receive").focus();
                    }else{
                        alert("Main fee is not equal to the distributed fees and must be equal to " + $("#main_due").val())
//                        alert(parseFloat($("#main_due").val()) + "=" + parseFloat($("#srf").val()));
                        
                e.preventDefault();
                    }
                }
                e.preventDefault();
            }
        })
        
        $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })
        $("#reservation").on("keypress",function(e){
            if(e.keyCode==13){
               if($("#reservation").val()=="" && $("#deposit").val()==""){
                    alert("Please put amount to Reservation or Student Deposit")
                    $("#reservation").focus();
                }else{
                computechange();    
                $("#cash_receive").focus();
                }
                e.preventDefault();
            }
        })
        
        $("#deposit").on("keypress",function(e){
            if(e.keyCode==13){
                if($("#reservation").val()=="" && $("#deposit").val()==""){
                    alert("Please put amount to Reservation or Student Deposit")
                    $("#deposit").focus();
                }else{
                $("#cash_receive").focus();
                }
                e.preventDefault();
            }
        })
        
       $("#cash_receive").on("keypress",function(e){
           if(e.keyCode==13){
               if(computechange() < 0){
                    $("#bank").focus();
               }
               e.preventDefault();
            }
           
       }) 
       $("#bank").on("keypress",function(e){
           if(e.keyCode==13){
               $("#check_number").focus()
               e.preventDefault();
           }
       })
       
       $("#check_number").on("keypress",function(e){
           if(e.keyCode==13){
               $("#check_amount").focus()
               e.preventDefault();
           }
       })
       $("#check_amount").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#check_amount").val()==""){
                   alert("Invalid amount")
               }else{
                   if(computechange()<0){
                     $("#credit_card_bank").focus();  
                   }
               }
               e.preventDefault();
           }
       })
       
       $("#credit_card_bank").on('keypress',function(e){
           if(e.keyCode==13){
           $("#credit_card_type").focus();
            e.preventDefault();
           }
       })
       $("#credit_card_type").on('keypress',function(e){
           if(e.keyCode==13){
           $("#card_number").focus();
            e.preventDefault();
           }
       })
       $("#card_number").on('keypress',function(e){
           if(e.keyCode==13){
           $("#approval_number").focus();
            e.preventDefault();
           }
       })
       $("#approval_number").on('keypress',function(e){
           if(e.keyCode==13){
           $("#credit_card_amount").focus();
            e.preventDefault();
           }
       })
       
       $("#credit_card_amount").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#credit_card_amount").val()==""){
                   alert("Invalid Amount")
               }else{
                   if(computechange()<0){
                     $("#deposit_reference").focus();  
                   }
               }
               e.preventDefault();
           }
       })
       
       $("#deposit_reference").on('keypress',function(e){
           if(e.keyCode==13){
           $("#deposit_amount").focus();
            e.preventDefault();
           }
       })
       $("#deposit_amount").on("keypress",function(e){
           if(e.keyCode==13){
              if(computechange()<0){
                   alert("Invalid Amount");  
                   }
               e.preventDefault();
           }
       })
       
    
    })
    function computeSubaccount(){
        var total = parseFloat($("#main_due").val());
        
        if(total >= misc_total_max){
        $("#miscellaneous").val(misc_total_max)
        total = total - misc_total_max
        } else {
         $("#miscellaneous").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= other_total_max){
        $("#other_fee").val(other_total_max)
        total = total - other_total_max
        } else {
         $("#other_fee").val(total.toFixed(2))
         total=0;
        }
        
        
        
        if(total >= depository_total_max){
        $("#depository").val(depository_total_max)
        total = total - depository_total_max
        } else {
         $("#depository").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= srf_total_max){
        $("#srf").val(srf_total_max)
        total = total - srf_total_max
        } else {
         $("#srf").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= optional_total_max){
        $("#optional").val(optional_total_max)
        total = total - optional_total_max
        } else {
         $("#optional").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= tuition_total_max){
        $("#tuition").val(tuition_total_max)
        total = total - tuition_total_max
        } else {
         $("#tuition").val(total.toFixed(2))
         total=0;
        }
        
        
        
        
    }
    function updatefunction(){
    $('.select2').select2();
    $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }});
    }
    
    
    function computechange(){
         totalamount = 0;
         amountreceive= 0;
         noncash=0;
         check_amount=0;
         deposit_amount=0;
         if($("#collected_amount").val()!=""){
            totalamount = totalamount + eval($("#collected_amount").val())
        }
         
         if($("#check_amount").val()!=""){
            check_amount= eval($("#check_amount").val());
            noncash = noncash + eval($("#check_amount").val())
        }
         if($("#credit_card_amount").val()!=""){
            noncash = noncash + eval($("#credit_card_amount").val())
        }
        if($("#deposit_amount").val()!=""){
            deposit_amount= eval($("#deposit_amount").val());
            noncash = noncash + eval($("#deposit_amount").val())
        }
         
        if(noncash > totalamount){
            if(noncash-(check_amount+deposit_amount)>totalamount){
             alert("Invalid Amount !!!!!")
            } else {
             $("#over_payment").val((check_amount+deposit_amount)-totalamount)
             if($("#cash_receive").val()!=""){
                   amountreceive = eval($("#cash_receive").val());
               } 
              if(amountreceive+noncash-totalamount >= 0){
                  $("#submit_button").fadeIn(300)
                  $("#submit_button").focus();
              }else{
                  $("#submit_button").fadeOut(300)
              }
              $('#cash_receive').val(0);
              $('#credit_card_amount').val(0);
              $("#change").val(0)
              return "0.00";
            }
            } else {
                $("#over_payment").val(0)
             if($("#cash_receive").val()!=""){
                   amountreceive = eval($("#cash_receive").val());
               } 
              if(amountreceive+noncash-totalamount >= 0){
                  $("#submit_button").fadeIn(300)
                  $("#submit_button").focus();
              }else{
                  $("#submit_button").fadeOut(300)
              }
              totalchange = amountreceive+noncash-totalamount
               $("#change").val(totalchange.toFixed(2));
               return totalchange.toFixed(2);
         }
    }
    
    function computeToBePaid(){
        total_payment=0;
        if($('input.other_misc')){
        $('input.other_misc').each(function(){
            total_payment=parseFloat(total_payment) + parseFloat(this.value)
        })
        total_payment = parseFloat(total_payment) + parseFloat($("#main_due").val()) + parseFloat($("#previous_balance").val())
        $("#collected_amount").val(total_payment.toFixed(2));
        $("#donereg").fadeIn(300)
        $("#explanation").focus()
        }
       
    }
    function gotoother_amount(i,evt){
        if(evt.keyCode==13){
            $("#other_amount" + i).focus()
            evt.preventDefault()
            return false;
        }
    }
    
   function totalOther(e){
           if(e.keyCode == 13){
                        totalamount =0;
                        other_amount = document.getElementsByName('other_amount[]');
                        for(var i = 0; i < other_amount.length; i++){
                        totalamount = totalamount+parseFloat(other_amount[i].value)
                        }
                        $("#other_total").val(totalamount.toFixed(2))
                        //$("#add").focus();
                        $("#donereg").fadeIn(300)
                        $("#remark").focus()
                         e.preventDefault();
                         return false;
                 }
        }
        
   function do_other(event,amount,value,obj){
       if(event.keyCode==13 || event.keyCode==9){
           if(parseFloat(value)>parseFloat(amount)){
               alert("Invalid amount")
               obj.value=amount;
           }else{
               computeToBePaid();
           }
           event.preventDefault();
       }
   }   
   function do_main(event,amount,value,obj){
       if(event.keyCode==13 || event.keyCode==9){
           if(parseFloat(value)>parseFloat(amount)){
               alert("Invalid amount")
               obj.value=amount;
           }else{
               totalmain = parseFloat($("#other_fee").val()) + parseFloat($("#miscellaneous").val())+ parseFloat($("#optional").val())
               + parseFloat($("#depository").val()) + parseFloat($("#srf").val()) + parseFloat($("#tuition").val());
               $("#main_due").val(totalmain)
               computeToBePaid();
           }
           event.preventDefault();
       }     
   }
   
   function do_previous(event,amount,value,obj){
       if(event.keyCode==13 || event.keyCode==9){
           if(parseFloat(value)>parseFloat(amount)){
               alert("Invalid amount")
               obj.value=amount;
           }else{
                totalprevious = 0.00;
                $('input[type="text"].previous').each(function () {
                    totalprevious += parseFloat($(this).val(),2);
                });
               
               $("#previous_balance").val(parseFloat(totalprevious,2))
               computeToBePaid();
           }
           
           
           event.preventDefault();
       }
   }
</script>    
@endsection
