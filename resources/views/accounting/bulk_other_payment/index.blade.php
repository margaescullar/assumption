<?php
$levels = DB::Select("SELECT distinct level, sort_by from ctr_academic_programs where academic_type='BED' order by sort_by");
$strands = DB::Select("Select distinct strand from ctr_academic_programs where academic_type='BED'");
$school_years = DB::Select("Select distinct school_year from bed_levels");
?>
<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env('CLUB_MODERATOR')){
    $layout = "layouts.appclubmoderator";
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
        Bulk Other Payments
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Bulk Other Payments</li>
    </ol>
</section>
@endsection
@section('maincontent')

@if (Session::has('message'))
<div class="alert alert-success">{{ Session::get('message') }}</div>
@endif 
<div class="col-md-6">
    <div class="col-md-3">
        <div class="form form-group">
            <label>Level</label>
            <select class="form-control select2" id="level" data-placeholder="Select Level">
                <option>Select Level</option>      
                @foreach($levels as $level)
                <option>{{$level->level}}</option>
                @endforeach
            </select>
        </div>      
    </div>  
    <div class="col-md-3">
        <div class="form form-group">
            <label>Strand</label>
            <div class="strandDisplay">
                <select class="form-control select2" id="strand" data-placeholder="Select Strand">       
                    <option>Select Strand</option> 
                    @foreach($strands as $level)
                    <option>{{$level->strand}}</option>
                    @endforeach
                </select>
            </div>    
        </div>      
    </div> 
    <div class="col-md-3">
        <div class="form form-group">
            <label>Section</label>
            <div id="sectionDisplay">
            </div>    
        </div>      
    </div>  

    <div class="col-md-3">
        <div class="form form-group">
            <br>
            <button class="btn btn-primary form-control" id="view_list">View List</button>
        </div>      
    </div> 

</div>
<div class="col-md-6">
    <form method="post" action="{{url('/accounting',array('bulk_other_payment','process'))}}">
        {{csrf_field()}}
        <div class="col-md-5">
            <div class="form form-group">
                <label>Other Payments</label>
                <select class="form-control select2" name="id" required="">
                    <option>Select Other Payments</option>      
                    @foreach($otherPayments as $otherPayment)
                    <option value="{{$otherPayment->id}}">{{$otherPayment->subsidiary}}</option>
                    @endforeach
                </select>
            </div>      
        </div> 
        <div class="col-md-4">
            <div class="form form-group">
                <label>Amount</label>
                <input class="form form-control" name="amount" required="">
            </div>      
        </div>  

        <div class="col-md-3">
            <div class="form form-group">
                <br>
                <button class="btn btn-success form-control" id="process">Process</button>
            </div>      
        </div>
    </form>

</div>
<div class="col-md-6">
    <div class="box">
        <div class="box-header">
            <div class="box-title">List of Students to Process</div>
        </div>
        <div class="box-body">
            <div id="displaystudent">
            </div> 
        </div>
    </div>        
</div>
<div class="col-md-6">
    <div class="box">
        <div class="box-header">
            <div class="box-title">List of Students to Process</div>
        </div>
        <div class="box-body" id="studenttoprocess">
            <table class="table table-striped">
                <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Level-Section</th>
                    <th>Action</th>
                </tr>
                @if(count($currentDatas)>0)
                @foreach($currentDatas as $currentData)
                <tr>
                    <td>{{$currentData->idno}}</td>
                    <td>{{$currentData->getFullNameAttribute()}}</td>
                    <td>{{$currentData->getLevelSection()}}</td>
                    <td>Remove</td>
                </tr>
                @endforeach
                @endif
            </table>
        </div>

    </div>        
</div>
@endsection
@section('footerscript')

<script src="{{url('/dist',array('js','demo.js'))}}"></script>
<script src="{{url('/',array('bower_components','select2','dist','js','select2.full.min.js'))}}"></script>
<script>

$(document).ready(function () {
    $(".strandDisplay").fadeOut(300);
    $('.select2').select2()
    
    $('#process').on('click', function(e){
       var r = confirm("Do want to continue?");
       if (r == false){
           return false;
       }
    });

    $("#level").on('change', function (e) {
        if ($("#level").val() == "Grade 11" || $("#level").val() == "Grade 12") {
            $(".strandDisplay").fadeIn(300);
            $("#sectionDisplay").html("");
        } else {
            $(".strandDisplay").fadeOut(300);
            var array = {};
            array['level'] = $("#level").val();
            $.ajax({
                type: "GET",
                url: "/bedregistrar/ajax/getsection",
                data: array,
                success: function (data) {
                    $("#sectionDisplay").html(data)
                    $('.select2').select2()
                }
            })
        }
    });

    $("#strand").on('change', function (e) {

        var array = {};
        array['level'] = $("#level").val();
        array['strand'] = $("#strand").val()
        $.ajax({
            type: "GET",
            url: "/bedregistrar/ajax/getsection",
            data: array,
            success: function (data) {
                $("#sectionDisplay").html(data)
                $('.select2').select2()
            }
        })

    });

    $("#view_list").on('click', function (e) {

        var array = {};
        array['level'] = $("#level").val();
        array['section'] = $("#section").val();
        array['strand'] = $("#strand").val();
        $.ajax({
            type: "GET",
            url: "/accounting/bulk_other_payment/get_list",
            data: array,
            success: function (data) {
                $("#displaystudent").html(data)

            }
        })

    });
});

function addStudent(idno) {

    var array = {};
    array['idno'] = idno;
    $.ajax({
        type: "GET",
        url: "/accounting/bulk_other_payment/add_student",
        data: array,
        success: function (data) {
            $("#studenttoprocess").html(data)
        }
    })

}

function removeStudent(idno) {

    var array = {};
    array['idno'] = idno;
    $.ajax({
        type: "GET",
        url: "/accounting/bulk_other_payment/remove_student",
        data: array,
        success: function (data) {
            $("#studenttoprocess").html(data)
        }
    })

}
</script>    
@endsection
