<?php
if (Auth::user()->accesslevel == env('ADMISSION_BED')) {
    $layout = "layouts.appadmission-bed";
} else {
    $layout = "layouts.appadmission-shs";
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
        Admission School Year Settings
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pre Registration Settings</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-7">  
    <div class="box">
        <div class="box-header">
            <div class="box-title">Admission School Year</div>
        </div>
        <div class="box-body">
            Note: This is auto-saving. As soon as you change the value it will automatically changes.

            <br>Note: After updating the school year, you must reset the control number below to 0.
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>School Year</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $group)
                    @if($group->applying_for != "College")
                    <tr>
                        <td>{{$group->applying_for}}</td>
                        <td><input name="school_year" value="{{$group->school_year}}" onchange="update_school_year(this.value, '{{$group->applying_for}}')"></td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
    <div class="box">
        <div class="box-header">
            <div class="box-title">Application Control Number</div>
        </div>
        <div class="box-body">
            <?php
            $school_year = \App\CtrAdmissionSchoolYear::where('applying_for', 'Basic Education')->first()->school_year;
            $current_control_no = \App\CtrPreRegNumber::where('academic_type', 'BED')->first();

            $idNumber = $current_control_no->prereg;
            for ($i = strlen($idNumber); $i <= 2; $i++) {
                $idNumber = "0" . $idNumber;
            }
            $current_number = substr($school_year, 2, 2) . substr($school_year + 1, 2, 2) . "BEDAPL" . $idNumber;
            ?>
            <form action="{{url('bedadmission/update_control_number')}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="academic_type" value="BED">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Current Number</th>
                            <th>Current Control No.</th>
                            <th>Update Control No.</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$current_number}}</td>
                            <td>{{$current_control_no->prereg}}</td>
                            <td><input type="text" name="control_number" value="{{$current_control_no->prereg}}"></td>
                            <td><input type="submit" value="Update"></td>
                        </tr>
                        </form>
                    </tbody>
                </table>
            </form>
        </div>
    </div> 
</div>
@endsection

@section('footerscript')
<script>

    function update_school_year(school_year, applying_for) {
    array = {};
    array['school_year'] = school_year;
    array['applying_for'] = applying_for;
    $.ajax({
    type: "GET",
            url: "/ajax/bedadmission/settings/update_admission_sy",
            data: array,
            success: function (data) {
            $("#show_adding_dropping").html(data);
            }

    });
    }
</script>
@endsection
