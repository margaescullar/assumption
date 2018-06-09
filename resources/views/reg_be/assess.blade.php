<?php
$current_level="";
$levels = \App\CtrAcademicProgram::selectRaw("distinct level, sort_by")->where('academic_type',"BED")->orderBy('sort_by')->get();
$strands =\App\CtrAcademicProgram::selectRaw("distinct strand")->where('academic_code','SHS')->get();
switch ($status->level){
case "Pre-Kinder":
    $current_level = "Kinder";
    break;
case "Kinder":
    $current_level = "Grade 1";
    break;
case "Grade 1":
    $current_level = "Grade 2";
    break;    
case "Grade 2":
    $current_level = "Grade 3";
    break;
case "Grade 3":
    $current_level = "Grade 4";
    break;
case "Grade 4":
    $current_level = "Grade 5";
    break;
case "Grade 5":
    $current_level = "Grade 6";
    break;
case "Grade 6":
    $current_level = "Grade 7";
    break;
case "Grade 7":
    $current_level = "Grade 8";
    break;
case "Grade 8":
    $current_level = "Grade 9";
    break;
case "Grade 9":
    $current_level = "Grade 10";
    break;
case "Grade 10":
    $current_level = "Grade 11";
    break;
case "Grade 11":
    $current_level = "Grade 12";
    break;
}

$plans = \App\CtrDueDateBed::selectRaw('distinct plan')->where('academic_type',$user->academic_type)->get();
$discounts = \App\CtrDiscount::get();
$optional_books = \App\CtrOptionalFee::where('level',$current_level)->where('category','Books')->where('amount','>','0')->get();
$optional_materials = \App\CtrOptionalFee::where('level',$current_level)->where('category','Materials')->get();
$optional_other_materials = \App\CtrOptionalFee::where('level',$current_level)->where('category','Other Materials')->get();
$optional_pe_uniforms = \App\CtrOptionalFee::where('level',$current_level)->where('category','PE Uniforms/Others')->get();;
$uniforms=  \App\CtrUniformSize::where('particular','AC P.E. T-Shirt')->get();
$joggings = \App\CtrUniformSize::where('particular','AC P.E. Jogging Pants')->get();
$socks = \App\CtrUniformSize::where('particular','AC School Socks')->get();
$dengues=  \App\CtrUniformSize::where('particular','AC Dengue Attire')->get();
//$pre_discount = DB::Select("Select * from partial_student_discount where idno = '$user->idno'")->first();
$pre_discount=  \App\PartialStudentDiscount::where('idno',$user->idno)->first();
$materials =  \App\CtrMaterial::where('level',$current_level)->where('category','Materials')->get();
$other_materials = \App\CtrMaterial::where('level',$current_level)->where('category','Other Materials')->get();
$discount = \App\DiscountCollection::where('id',$user->idno)->get();
$other_collection = \App\OtherCollection::get();

$check_balances = \App\OldSystemBalance::where('idno',$user->idno)->get();
$check_reservations = \App\Reservation::where('idno', $user->idno)->where('reservation_type',1)->where('is_consumed', 0)->get();
?>
@extends('layouts.appbedregistrar')
@section('messagemenu')
 <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="#">
                      <div class="pull-left">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Assessment
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assessment</li>
    </ol>
</section>
@endsection
@section('maincontent')
<form class="form form-horizontal" method="post" action="{{url('/bedregistrar','assess')}}">
    {{csrf_field()}}
    <?php $balance = 0; ?>
    @if(count($check_balances)>0)
    @foreach ($check_balances as $check_balance)
    <?php $balance = $balance + $check_balance->balance; ?>
    @endforeach
    <div class="alert alert-danger">Student still have an outstanding balance of <b>Php {{number_format($balance)}}</b>. Please go to Treasurer's Office to settle the account.<br>
    If <b>Official Receipt</b> was presented, kindly disregard this message. Thank you!</div>
    @endif
    <?php $reservation = 0; ?>
    @if(count($check_reservations)>0)
    @foreach ($check_reservations as $check_reservation)
    <?php $reservation = $reservation + $check_reservation->amount; ?>
    @endforeach
    <div class="alert alert-info    ">Student placed a reservation fee with the amount of <b>Php {{number_format($reservation)}}</b>.</div>
    @endif
    <input type="hidden" name="idno" value="{{$user->idno}}">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h1 class="box-title"><b>{{$user->idno}} - {{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b></h1>
                    </div>
                    <div class="box-body">
                        <div class="box-group" id="accordion">
                            <div class="panel box box-primary">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Previous Grade Level</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in">
                                    <div class="box-body">
                                        <div class="col-md-4">
                                            <table class="table table-responsive">
                                                @if(count($status)>0)
                                                @if($status->status==env("ENROLLED"))
                                                <tr><td colspan="2"><b>This student is already ENROLLED!!</b></td></tr>
                                                <tr><th>Level</th><td>{{$status->level}}</td></tr>
                                                @if($status->level == "Grade 11" || $status->level=="Grade 12")
                                                <tr><th>Strand</th><td>{{$status->strand}}</td></tr>
                                                @endif
                                                <tr><th>Section</th><td>{{$status->section}}</td></tr>
                                                @elseif($status->status=="0")
                                                <tr><td colspan="2"><b>Previous Level</b></td></tr>
                                                <tr><th>Level</th><td>{{$status->level}}</td></tr>
                                                @if($status->level == "Grade 11" || $status->level=="Grade 12")
                                                <tr><th>Strand</th><td>{{$status->strand}}</td></tr>
                                                @endif
                                                <tr><th>Section</th><td>{{$status->section}}</td></tr>
                                                @endif
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel box box-danger">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Select Level, Option and Discount</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse in">
                                    <div class="box-body">
                                        @if(count($status)>0)
            @if($status->status == "0")
                <div class="form form-group">
                <div class="col-md-6">
                <label>Grade Level</label>
                <Select name="level" id="level" class="form form-control">
                    <option value="">Select Level</option>
                    @foreach($levels as $level)
                    <option value="{{$level->level}}"
                            @if($level->level==$current_level)
                            selected="selected"
                            @endif
                            >{{$level->level}}</option>
                    @endforeach
                </select>      
                </div>
                <div class="col-md-6" id="strand_control">
                    <label>Strand</label>
                    <Select name="strand" id="strand" class="form form-control">
                    <option value="">Select Strand</option>    
                    @foreach($strands as $strand)
                    <option value="{{$strand->strand}}"
                            @if($strand->strand == $status->strand)
                            selected="selected"
                            @endif
                            >{{$strand->strand}}</option>
                    @endforeach
                </select> 
                </div>    
                </div>  
                <div class="form form-group">
                <div class="col-md-6">
                    <label>Section</label>
                    <select name="section" id="section" class="form form-control">
                        @for($i=1;$i<=7;$i++)
                        <option value="{{$i}}"
                             @if($i == $status->section)
                                selected="selected"
                             @endif
                                >{{$i}}</option>
                        @endfor
                    </select>    
                </div>  
                <div class="col-md-6">
                    <label>Payment Options <span class="warning">Required</span></label>
                    <select class="form form-control" name="plan" id="plan">
                        <option value="">Select Payment Option</option>
                        <option value="Plan A - Annual">Plan A - Annual</option>
                        @foreach($plans as $plan)
                        <option value="{{$plan->plan}}">{{$plan->plan}}</option>
                        @endforeach
                    </select> 
                    @if ($errors->has('plan'))
                                    <span class="invalid-feedback warning">
                                        <strong>Payment Option Required</strong>
                                    </span>
                    @endif
                </div>    
            </div>    
            
            <div class="form form-group">
                <div class="col-md-6">
                    <label>Discount</label>
                    <select name="discount" id="discount" class="form form-control">
                        <option value="none">None</option>
                        @if(count($discounts)>0)
                            @foreach($discounts as $discount)
                                <option value="{{$discount->discount_code}}"
                                        @if(count($pre_discount)>0)
                                        @if($discount->discount_description == $pre_discount->discount_description)
                                        selected="selected"
                                        @endif
                                        @endif
                                        >{{$discount->discount_description}}</option>
                            @endforeach
                        @endif
                    </select>    
                </div>    
                </div>    
            
                @endif
         @endif
         
           
                    </div>
                  </div>
                </div>

         <!--Other Discounts-->                 

                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Other Discounts
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse in">
                      <div class="box-body">
                          
                          
         <div class="form form-group">
             <div class="col-md-5">
                <label>Other Discount</label>
                <select id="other_discount" class="form form-control">
                    @foreach($other_collection as $other)
                    <option>{{$other->subsidiary}}</option>
                    @endforeach
                </select>    
             </div>
             <div class="col-md-5">
                 <label>Discount Amount</label>
                 <input type="text" class="form-control" id="discount_amount">
             </div>
             <div class="col-md-2">
                 <label class="col-sm-12">&nbsp;</label>
                 <a href="javascript:void(0)"><button type="button" class="btn btn-success" onclick="add_discount_collection('{{$user->idno}}', other_discount.value, discount_amount.value)"><span class="fa fa-plus-circle"></span></button></a>
             </div>    
         </div>
                          <?php $get_discount_collections = \App\DiscountCollection::where('idno', $user->idno)->get(); ?>
                          
                          <div id="discount_collections">
                              <table class="table table-condensed">
                                  @if(count($get_discount_collections)>0)
                                  @foreach($get_discount_collections as $discount_collection)
                                  <tr><td>{{$discount_collection->subsidiary}}</td><td>{{$discount_collection->discount_amount}}</td><td><a href="javascript:void(0)" onclick="remove_discount_collection('{{$discount_collection->id}}','{{$user->idno}}')">Remove</a></td></tr>
                                  @endforeach
                                  @else
                                  <tr><td><i>No Other Discounts Added.</i></td></tr>
                                  @endif
                              </table>
                          </div>
                      </div>
                  </div>
                </div>
                      
            </div>
                
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
           
 <!-- search form (Optional) -->  
</div>
            <div class="col-md-6">
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        LIST OF REQUIRED BOOKS AND MATERIALS
                      </a>
                    </h4>
                  </div>
                    
               <div id="collapseThree" class="panel-collapse collapse in">
               <div class="box-body">
                 
                <div class="col-md-12 ">
                <div class="row" id="book_materials">      
                    <table border = "1">
                     
                @if(count($optional_books)>0)
                <tr align="left"><td colspan="4"><strong>Books and Other Materials</strong></td><td>Sub Total</td></tr>
                <?php $i=1; $totalbook=0;$count=1;?>
                @foreach($optional_books as $optional)
                <?php 
                $default_value="checked='checked'";
                $default_amount=number_format($optional->amount * $optional->default_qty,2);
                if($errors->has('plan')){
                    $default_value="";
                    $default_amount=0;
                    $qty_books=old('qty_books');
                   
                    foreach($qty_books as $key=>$value){
                        if($key==$optional->id){  
                        $default_value="checked='checked'";
                        $default_amount=number_format($optional->amount * $optional->default_qty,2);
                    }}
                  
                    }
                ?>
                <tr><td>{{$count++}}</td><td>
                 {{$optional->subsidiary}}
                    </td><td><input name="qty_books[{{$optional->id}}]" value="1" type="number"  oninput="process_sub1({{$optional->id}},this.value,{{$optional->amount}},this)"></td>
                <td align="left"><div class="book_display[]" id="book_display{{$optional->id}}">{{$default_amount}}
                    <?php $totalbook=$totalbook+ ($optional->amount * $optional->default_qty);?></div></td>
                <td></td></tr>
                @endforeach
                <tr><td colspan="4">Sub Total</td><td><div id="total_book">{{number_format($totalbook,2)}}</div></td></tr>
                @endif
                @if(count($optional_materials)>0)
                @foreach($optional_materials as $optional)
                <tr><td><input onclick="return false;" name="qty_books[{{$optional->id}}]" value="1"  type="checkbox" checked="checked" onclick="process_sub1({{$optional->id}},this.checked,{{$optional->amount}},this)"></td>
                <td colspan="3">
                    Required {{$optional->subsidiary}} <span class="warning">(SET)</span>
                   @if(count($materials)>0)
                   <ul>
                   @foreach($materials as $material)
                  <li>{{$material->particular}}
                   @endforeach
                   </ul>
                   @endif
                    </td>
                    
                <td align="left"><div id="book_display{{$optional->id}}">{{number_format($optional->amount * $optional->default_qty,2)}}</div></td>
                </tr>
                @endforeach
                @endif 
                
                @if(count($optional_other_materials)>0)
                @foreach($optional_other_materials as $optional)
                 <?php 
                $default_value="checked='checked'";
                $default_amount=number_format($optional->amount * $optional->default_qty,2);
                if($errors->has('plan')){
                    $default_value="";
                    $default_amount=0.00;
                    $qty_books=old('qty_books');
                    foreach($qty_books as $key=>$value){
                        if($key==$optional->id){
                        $default_value="checked='checked'";
                        $default_amount=number_format($optional->amount * $optional->default_qty,2);
                    }}
                  
                    }
                ?>
                <tr><td><input name="qty_books[{{$optional->id}}]" value="1"  type="checkbox" {{$default_value}} onclick="process_sub1({{$optional->id}},this.checked,{{$optional->amount}},this)"></td>
                <td colspan="3">
                 {{$optional->subsidiary}} <span class="warning">(SET)</span>
                 @if(count($other_materials)>0)
                   <ul>
                   @foreach($other_materials as $material)
                  <li>{{$material->particular}}
                   @endforeach
                   </ul>
                   @endif
                    </td>
                    
                <td align="left"><div id="book_display{{$optional->id}}">{{$default_amount}}</div></td>
                </tr>
                @endforeach
                @endif
                </table>
                </div>
                    <div class="row">       
                <table border="1" class="table">        
                <tr><td colspan="4"><strong>P.E. Uniform/Others</strong></td></tr>
                <tr><td>Particular</td><td>Qty</td><td>Size</td><td>Amount</td></tr>
                <tr><td>AC P.E. T-Shirt </td><td>
                        <input  type="number" value =@if(!is_null(old('tshirt_qty')))"{{old('tshirt_qty')}}" @else "1" @endif class="form form-control number" oninput="getUniformAmount1('1','uniform')" name="tshirt_qty" id="tshirt_qty"></td>
                    <td><select id="tshirt_size" name="tshirt_size" class="form form-control" onchange="getUniformAmount(this.value,'uniform')">
                                    <option value=""></option>
                                    @if(count($uniforms)>0)
                                        @foreach($uniforms as $particular)
                                        <option value="{{$particular->id}}" 
                                                <?php if($particular->id == old('tshirt_size')){ 
                                                echo " selected=\"selected\""; 
                                                }
                                                ?>>{{$particular->size}}</option>
                                        @endforeach
                                    @endif
                            </select></td><td><div id="uniform">0.00</div></td></tr>
                <tr><td>AC P.E. Jogging Pants </td><td><input type="number" value=@if(!is_null(old('jogging_qty')))"{{old('jogging_qty')}}" @else "1" @endif oninput="getUniformAmount1('2','jogging')"  class="form form-control number" name="jogging_qty" id="jogging_qty"></td>
                            <td><select id="jogging_size" name="jogging_size" class="form form-control" onchange="getUniformAmount(this.value,'jogging')">
                                    <option value=""></option>
                                     @if(count($joggings)>0)
                                        @foreach($joggings as $particular)
                                        <option value="{{$particular->id}}"
                                                <?php if($particular->id == old('jogging_size')){ 
                                                echo " selected=\"selected\""; 
                                                }
                                                ?>>{{$particular->size}}</option>
                                        @endforeach
                                    @endif
                            </select></td><td><div id="jogging">0.00</div></td></tr>
                <tr><td>AC School Socks </td><td><input type="number" value=@if(!is_null(old('socks_qty')))"{{old('socks_qty')}}" @else "1" @endif  oninput="getUniformAmount1('3','socks')" class="form form-control number" name="socks_qty" id="socks_qty"></td>
                            <td><select  id="socks_size" name="socks_size" class="form form-control" onchange="getUniformAmount(this.value,'socks')">
                                    <option value=""></option>
                                     @if(count($socks)>0)
                                        @foreach($socks as $particular)
                                        <option value="{{$particular->id}}"
                                                <?php if($particular->id == old('socks_size')){ 
                                                echo " selected=\"selected\""; 
                                                }
                                                ?>>{{$particular->size}}</option>
                                        @endforeach
                                    @endif
                            </select></td><td><div id="socks">0.00</div></td></tr>
                <tr><td>AC Dengue Attire </td><td><input type="number" value=@if(!is_null(old('dengue_qty')))"{{old('dengue_qty')}}" @else "1" @endif oninput="getUniformAmount1('4','dengue')"  class="form form-control number" name="dengue_qty" id="dengue_qty"></td>
                            <td><select id="dengue_size" name="dengue_size" class="form form-control" onchange="getUniformAmount(this.value,'dengue')">
                                    <option value=""></option>
                                     @if(count($dengues)>0)
                                        @foreach($dengues as $particular)
                                        <option value="{{$particular->id}}"
                                                <?php if($particular->id == old('dengue_size')){ 
                                                echo " selected=\"selected\""; 
                                                }
                                                ?>>{{$particular->size}}</option>
                                        @endforeach
                                    @endif
                            </select></td><td><div id="dengue">0.00</div></td></tr>
                </table>
                 </div>       
                </div>  
                
               
                    
                 
                  </div>
                </div>
                      
              </div>
          
              </div>  
  <input type="submit" name="submit" value="Process Assessment" class="btn btn-primary form-control">           
 </div>   
 </div>     
            
    
 </form> 
@endsection
@section('footerscript')
<style>
table td{ 
    padding:2px;
}

input[type=number]{
    width:50px;
}  

.warning{
    color:#f00;
    font-style: italic;
    font-size: 7pt;
}
</style>
<script>
    $(document).ready(function(){
        
       if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
         $("#strand_control").fadeIn(300);  
       }else {  
       $("#strand_control").fadeOut(300);
        }
       $("#level").on('change',function(e){
           if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
               $("#strand_control").fadeIn(300);
           } else {
               $("#strand_control").fadeOut(300);
           }
           popBookMaterials($('#level').val());
           popUniform($("#level").val());
           
       })
       
     $(".number").on('keypress',function(e){
         //alert(e.keyCode)
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        
        if ((key < 48 || key > 57) && !(key == 8 || key == 9  || key == 37 || key == 39 || key == 46) ){ 
        
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        
        }
    })
    getUniformAmount($("#tshirt_size").val(),'uniform');
    getUniformAmount($("#jogging_size").val(),'jogging');
    getUniformAmount($("#socks_size").val(),'socks');
    getUniformAmount($("#dengue_size").val(),'dengue');
    //book_display();
    });
    
    function process_sub(id,qty,amount,event,these){  
        //alert(event.keyCode)
        if(event.keyCode==13){   
        if(qty<0){
            qty=0;
            these.value=0;
        }      
        total = amount*qty;
        $("#book_display"+id).html(total.toFixed(2));
        event.preventDefault();
        } 
    }
    function process_sub1(id,qty,amount,these){
       
         if(qty<0){
            qty=0;
            these.value=0;
        }      
        total = amount*qty;
        $("#book_display"+id).html(total.toFixed(2));
        
        book_display();
    }
    
    function popBookMaterials(level){
         $.ajax({
            type: "GET", 
            url: "/bedregistrar/ajax/book_materials/" +  level, 
            success:function(data){
                $('#book_materials').html(data);  
                }
            });
            
    }
    
    function popUniform(level){
        $.ajax({
            type: "GET", 
            url: "/bedregistrar/ajax/peuniforms/" +  level, 
            success:function(data){
                $('#pe_uniforms').html(data);  
                }
            });
    }
    function book_display(){
        amount=0;
        books = document.getElementsByClassName("book_display[]")
         for(var i = 0; i < books.length; i++){
             amount = amount + eval(books[i].innerHTML);
        }
        $("#total_book").html(amount.toFixed(2));
    }
    
    function getUniformAmount(id,display){
       if(id==""){
           $("#"+display).html("0.00")
       }
        array={};
        switch(display){
            case "uniform":
                array['qty']=$("#tshirt_qty").val();
                break;
            case "jogging":
                array['qty']=$("#jogging_qty").val();
                break;
            case "socks":
                array['qty']=$("#socks_qty").val();
                break;
            case "dengue":
                array['qty']=$("#dengue_qty").val();
                break;
        }
        array['id']=id;
        $.ajax({
            type:"GET",
            data:array,
            url:"/bedregistrar/ajax/getUniformAmount",
            success:function(data){
             $("#"+display).html(data);   
            }
        });
        
        
    }
    function getUniformAmount1(id,display){
        
        array={};
        switch(id){
            case "1":
                if($("#tshirt_qty").val()<0){
                    $("#tshirt_qty").val(0);
                }
                array['id']=$("#tshirt_size").val();
                array['qty']=$("#tshirt_qty").val();
                break;
            case "2":
                if($("#jogging_qty").val()<0){
                    $("#jogging_qty").val(0);
                }
                array['id']=$("#jogging_size").val();
                array['qty']=$("#jogging_qty").val();
                break;
            case "3":
                if($("#socks_qty").val()<0){
                    $("#socks_qty").val(0);
                }
                array['id']=$("#socks_size").val();
                array['qty']=$("#socks_qty").val();
                break;
            case "4":
                if($("#dengue_qty").val()<0){
                    $("#dengue_qty").val(0);
                }
                array['id']=$("#dengue_size").val();
                array['qty']=$("#dengue_qty").val();
                break;
        }
        if(array['id']==""){
            alert("Please Select Size First!!")
            return false;
        } else {
        $.ajax({
            type:"GET",
            data:array,
            url:"/bedregistrar/ajax/getUniformAmount",
            success:function(data){
             $("#"+display).html(data);   
            }
        });
        
        }
    }
    function add_discount_collection(idno,subsidiary, discount_amount){
    array = {};
    array['idno'] = idno;
    array['subsidiary'] = subsidiary;
    array['discount_amount'] = discount_amount;
    $.ajax({
    type: "GET",
            url: "/bedregistrar/ajax/add_discount_collection",
            data: array,
            success: function (data) {
                pop_discount_collection(idno);
            }

    });
    }
    function remove_discount_collection(id,idno){
    array = {};
    array['id'] = id;
    array['idno'] = idno;
    $.ajax({
    type: "GET",
            url: "/bedregistrar/ajax/remove_discount_collection",
            data: array,
            success: function (data) {
                pop_discount_collection(idno);
            }

    });
    }
    function pop_discount_collection(idno){
    $.ajax({
    type: "GET",
            url: "/bedregistrar/ajax/pop_discount_collection",
            data: array,
            success: function (data) {
                $('#discount_collections').html(data);
            }

    });
    }
</script>    

@endsection


