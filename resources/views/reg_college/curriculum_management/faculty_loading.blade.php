<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
$faculties = \App\User::where('accesslevel', 1)->where('status',1)->orderBy('lastname', 'ASC')->get();
?>
<?php
if(Auth::user()->accesslevel == env('DEAN')){
$layout = "layouts.appdean_college";
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
        Faculty Loading
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','faculty_loading'))}}"> Faculty Loading</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">List of Faculties</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class='table-responsive'>
                    <table class='table table-hover' id="faculty_list">
                        <thead>
                            <tr>
                                <th>Faculty Code</th>
                                <th>Name</th>
                                <th>Modify</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faculties as $faculty)
                            <tr>
                                <td>{{$faculty->idno}}</td>
                                <td>{{$faculty->lastname}} {{$faculty->extensionname}}, {{$faculty->firstname}}</td>
                                <td><a href='{{ url ('registrar_college', array('curriculum_management', 'edit_faculty_loading',$faculty->idno))}}'><button class='btn btn-info'><span class='fa fa-pencil'></span></button></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $("#level-form").hide();
    $("#section-form").hide();
    $("#courses_offered").hide();

    $("#program-form").change(function () {
        $("#level-form").fadeIn();
    });
    $("#level-form").change(function () {
        $("#section-form").fadeIn();
    });
    $("#section-form").change(function () {
        $("#courses_offered").fadeIn();
    });
</script>
<script>

    function courses_offered(program_code) {
        array = {};
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
        array['section'] = $("#level").val();
        array['level'] = $("#section").val();
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/curriculum_management/course_to_schedule/" + program_code,
            data: array,
            success: function (data) {
                $('#courses_offered').fadeIn().html(data);
            }

        });
    }
</script>

<!-- DataTables -->
<script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- page script -->
<script>
  $(function () {
    $('#faculty_list').DataTable()
  })
</script>

@endsection