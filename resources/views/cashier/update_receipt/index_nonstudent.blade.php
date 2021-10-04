<?php
function get_value($id,$reference_id){
    $get_ledger = \App\Accounting::where('reference_id',$reference_id)->where('accounting_type',1)->where('reference_number',$id)->first();
    if(count($get_ledger)>0){
        return "$get_ledger->credit";
    }else{
        return 0.00;
    }
}
?>
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
<!--<h1>Module not yet done...</h1>-->
<section class="content-header">
      <h1>
        Update OR Number: {{$payment->receipt_no}}
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Update Receipt</li>
      </ol>
</section>
@endsection
@section('maincontent')
<form action="{{url('/update_receipt_nonstudent')}}" method="POST">
{{csrf_field()}}
<input type='hidden' value='{{$payment->reference_id}}' name='reference_id'>
<input type='hidden' value='{{$payment->posted_by}}' name='posted_by'>
<input type='hidden' value='{{$payment->transaction_date}}' name='transaction_date'>

<!--start of form-->
<!--Payment Details-->
    <div class="box">
        <div class="box-header">
            <div class="box-title">Payment Details</div>
        </div>
        <div class="box-body">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Receipt No:</label>
                    <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="{{$payment->receipt_no}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Cash Amount:</label>
                    <input type="text" class="form-control" id="amount_received" name="amount_received" value="{{$payment->amount_received}}">
                </div>
                <div class="col-sm-3">
                    <label>Change:</label>
                    <input type="text" class="form-control" id="change" name="change" value="{{$payment->amount_received-$payment->cash_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-2">
                    <label>Credit Card Bank:</label>
                    <input type="text" class="form-control" id="credit_card_bank" name="credit_card_bank" value="{{$payment->credit_card_bank}}">
                </div>
                <div class="col-sm-2">
                    <label>Type:</label>
                    <input type="text" class="form-control" id="credit_card_type" name="credit_card_type" value="{{$payment->credit_card_type}}">
                </div>
                <div class="col-sm-3">
                    <label>Approval Number:</label>
                    <input type="text" class="form-control" id="approval_number" name="credit_card_type" value="{{$payment->approval_number}}">
                </div>
                <div class="col-sm-3">
                    <label>Credit Card Number:</label>
                    <input type="text" class="form-control" id="credit_card_number" name="credit_card_number" value="{{$payment->credit_card_number}}">
                </div>
                <div class="col-sm-2">
                    <label>Amount:</label>
                    <input type="text" class="form-control" id="credit_card_amount" name="credit_card_amount" value="{{$payment->credit_card_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Check Bank:</label>
                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{$payment->bank_name}}">
                </div>
                <div class="col-sm-3">
                    <label>Check Number:</label>
                    <input type="text" class="form-control" id="check_number" name="check_number" value="{{$payment->check_number}}">
                </div>
                <div class="col-sm-3">
                    <label>Amount:</label>
                    <input type="text" class="form-control" id="check_amount" name="check_amount" value="{{$payment->check_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-3">
                    <label>Deposit Reference:</label>
                    <input type="text" class="form-control" id="deposit_reference" name="deposit_reference" value="{{$payment->deposit_reference}}">
                </div>
                <div class="col-sm-3">
                    <label>Amount:</label>
                    <input type="text" class="form-control" id="deposit_amount" name="deposit_amount" value="{{$payment->deposit_amount}}">
                </div>
            </div>
            
            <div class="form-group row">
                <div class="col-sm-12">
                    <label>Remarks:</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" value="{{$payment->remarks}}">
                </div>
            </div>
        </div>
    </div>

<!--Payment Particulars-->
    <div class="box">
        <div class="box-header">
            <div class="box-title">Payment Particulars</div>
        </div>
        <div class="box-body">
            <div class="form-group">
                    <table class='table-condensed table' width='100%'>
                        <tr>
                            <td>Control ID</td>
                            <td>Subsidiary</td>
                            <td>Accounting Code</td>
                            <td>Accounting Name</td>
                            <td>Amount</td>
                        </tr>
                    @foreach($accounting_particulars as $accounting_particular)
                    <tr>
                        <td>{{$accounting_particular->id}}</td>
                        <td>{{$accounting_particular->subsidiary}}</td>
                        <td>{{$accounting_particular->accounting_code}}</td>
                        <td>{{$accounting_particular->accounting_name}}</td>
                        <td>{{$accounting_particular->credit}}</td>
                    </tr>
                    @endforeach
                    </table><br>
            </div>
            <div class="form-group row">
                <div class="form form-group">

                    <div class="col-md-5">   
                        Particular
                    </div>

                    <div class="col-md-5">
                        Amount
                    </div>
                    <div class="col-md-2">

                    </div>
                </div>
                <div  id="dynamic_field">
                    <div class="form form-group">
                        <div class="col-md-5">
                            <select name="particular[]" id="particular1" class="form form-control select2" onkeypress="gotoother_amount(1,event)">
                            <option>Select Particular</option>
                            @if(count($particulars)>0)
                                @foreach($particulars as $particular)
                                    <option value="{{$particular->subsidiary}}">{{$particular->subsidiary}}</option>
                                @endforeach
                            @endif
                            </select>

                        </div>
                        <div class="col-md-5">
                            <input class="form form-control number" type="text" onkeypress="totalOther(event)" onkeyup = "toNumeric(this)" name="other_amount[]" id="other_amount1"/>
                        </div>
                        <div class="col-md-2">
                        <button type="button" name="add" id="add" class="btn btn-success"> + </button></td>
                        </div>
                    </div>    
                </div>
            </div>
            <div class="form-group">
                    
                <input type="submit" class="btn btn-success col-sm-12" value="UPDATE RECEIPT">
            </div>
        </div>
    </div>



</form>
@endsection
@section('footerscript')
<script>
    $(document).ready(function(){
        
         var i = 1;
         $('#add').click(function(){  
        i++;
        $('#dynamic_field').append('<div id="row'+i+'" class="form form-group">\n\
        <div class="col-md-5">\n\
        <select class="form form-control select2" type="text" onkeypress = "gotoother_amount('+i+',event)" name="particular[]" id="particular'+i+'">'
         @foreach($particulars as $particular) + '<option>{{$particular->subsidiary}}</option>'  @endforeach 
         + '</select></div>\n\
        <div class="col-md-5"><input class="form form-control number" type="text" onkeypress="totalOther(event)" onkeyup = "toNumeric(this)" onkeypress = "totalOther(event)" name="other_amount[]" id="other_amount'+i+'"/></div>\n\
        <div class="col-md-2"><a href="javascript:void()" name="remove"  id="'+i+'" class="btn btn-danger btn_remove">X</a></div></div>');
        $("#particular"+i).focus();
        updatefunction();
        });
        
        $('#dynamic_field').on('click','.btn_remove', function(){
                //alert($(this).attr("id"))
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
                i--;
                totalamount =0;
                other_amount = document.getElementsByName('other_amount[]');
                for(var i = 0; i < other_amount.length; i++){
                if(other_amount[i].value != ""){    
                totalamount = totalamount+parseFloat(other_amount[i].value)
                }
                }
                $("#other_total").val(totalamount.toFixed(2))
                $("#donereg").fadeIn(300);
        updatefunction();
            }); 
    });
    
    
    
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
</script>
@endsection
