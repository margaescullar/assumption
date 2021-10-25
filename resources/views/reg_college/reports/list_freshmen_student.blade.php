<?php
if(Auth::user()->accesslevel == env('DEAN')){
$layout = "layouts.appdean_college";
} else if(Auth::user()->accesslevel == env('ADMISSION_HED')) {
$layout = "layouts.appadmission-hed";
} else if(Auth::user()->accesslevel == env('AA')) {
$layout = "layouts.appaa";
} else {
$layout = "layouts.appreg_college";
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
<section class="content-header">
    <h1>
        List of Freshmen Student
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Reports</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('reports','list_transfer_student'))}}"></i> List Transfer Student</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
        @endforeach
                </ul>
            </div>
        @endif
        <div class="row">       
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">  
                    <h3 class="box-title">Search Freshmen Students</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class='form-horizontal'>
                        <div class='form-group'>
                            <div class="col-sm-3">
                                <label>School Year</label>
                                <select id='school_year' class='form-control select2'>
                                    <option value="">Select School Year</option>
                                    <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
                                </select> 
                            </div> 
                        </div>        
                        <div id="getfreshmen"> 
                            
                        </div>    
                    </div>    
                </div>    
            </div>
        </div>  
    </div>    
<section>
       
</section>    
@endsection
@section('footerscript')
<script>
    $('#school_year').on('change', function(e){
        var array={};
        array['school_year'] = $('#school_year').val();
        $.ajax({
            type:"get",
            url:"/registrar_college/reports/ajax/getfreshmen", 
            data: array,
            success:function(data){
                $('#getfreshmen').html(data);
                $('#example2').DataTable();
            }
        });
    });
    

</script>

<!-- DataTables -->
<script src="{{url('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script>
  $(function () {
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })
</script>
@endsection
