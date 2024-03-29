<style>
    .post
    {
        border-bottom:1px solid #d2d6de;
        margin-bottom:15px;
        padding-bottom:15px;
        color:#666
    }
    .post:last-of-type
    {
        border-bottom:0;
        margin-bottom:0;
        padding-bottom:0
    }
    .post .user-block
    {
        margin-bottom:15px
    }
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    } 
</style>
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
        Pre-Registration Email
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Pre-Registration Email</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<!-- search form (Optional) -->
    <form action='{{url('/bedadmission/settings/pre_registration_email/post')}}' method='post'>
        {{csrf_field()}}
        <?php $regular = \App\CtrPreRegMessages::where('type','regular')->first(); ?>
        <?php $waive = \App\CtrPreRegMessages::where('type','waive')->first(); ?>
        
        <div class="col-sm-8">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-warning"></i>

                    <h3 class="box-title">Regular Email</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group" style="margin-top:15px;">

                        <textarea id="editor1" name="message_regular" rows="5" cols="59">
{{$regular->message}}
                        </textarea>
                    </div>

                    <div class="form-group" style="margin-top:15px;">

                        <button class="btn btn-primary btn-flat btn-block" value="regular" name="submit">Update Regular Email</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
    <div class="box box-default">
        <div class="box-header with-border">
            <i class="fa fa-warning"></i>

            <h3 class="box-title">Legends:</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-condensed table-bordered">
                <tr>
                    <th>Legend</th>
                    <th>Description</th>
                </tr>
                <tr>
                    <td>#referenceid#</td>
                    <td>Username/Temporary ID</td>
                </tr>
                <tr>
                    <td>#firstname#</td>
                    <td>Applicant's first name</td>
                </tr>
                <tr>
                    <td>#lastname#</td>
                    <td>Applicant's surname</td>
                </tr>
                <tr>
                    <td>#middlename#</td>
                    <td>Applicant's middle name</td>
                </tr>
                <tr>
                    <td>#extensionname#</td>
                    <td>Applicant's extension name</td>
                </tr>
            </table>
        </div>
    </div>
</div>
        
        
        <div class="col-sm-8">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-warning"></i>

                    <h3 class="box-title">Waive Email</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group" style="margin-top:15px;">

                        <textarea id="editor2" name="message_waive" rows="5" cols="59">
{{$waive->message}}
                        </textarea>
                    </div>

                    <div class="form-group" style="margin-top:15px;">

                        <button class="btn btn-primary btn-flat btn-block" value="waive" name="submit">Update Waive Email</button>
                    </div>
                </div>
            </div>
        </div>
        
    </form>

<!-- /.search form -->

@endsection
@section('footerscript')
<script src="{{asset('/bower_components/ckeditor/ckeditor.js')}}"></script>                 
<script src="{{asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script>
$(function () {
    CKEDITOR.replace('editor1')
    CKEDITOR.replace('editor2')
})
</script>
@endsection