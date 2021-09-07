@extends('layouts.admin')
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
        View Information
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='box'>
    <div class='box-body'>
        
    <div class="col-sm-12">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
        <form class='form form-horizontal' method="post" action="{{url('/admin', array('update_info'))}}">
            {{ csrf_field() }}
            <div class="form form-group">
                <div class="col-sm-3">
                    <label>ID Number</label>
                    <input type='text' name="idno" id="idno" class='form form-control' value='{{$idno}}' readonly="">
                </div>
                <div class="col-md-3 pull-right">
                     <label>User Status</label>
                     <select class="form form-control" name="user_status" id="user_status">
                         <option value="0" @if ($user->status == 0) selected=''@endif>0 - Not Active</option>
                         <option value="1" @if ($user->status == 1) selected=''@endif>1 - Active</option>
                         <option value="2" @if ($user->status == 2) selected=''@endif>2 - See Registrar</option>
                     </select>
                </div>
                <div class="col-sm-3 pull-right">
                    <label><br><br></label>
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">
                        Reset Password
                    </button>
                </div>
            </div>
            <div class="form form-group">
                <div class="col-sm-3">
                    <label>Firstname</label>
                    <input type='text' class='form form-control' name='firstname' id='firstname' value="{{old('firstname',$user->firstname)}}">
                </div>
                <div class="col-sm-3">
                    <label>Middlename</label>
                    <input type='text' class='form form-control' name='middlename' id='middlename' value="{{old('middlename',$user->middlename)}}">
                </div>
                <div class="col-sm-3">
                    <label>Lastname</label>
                    <input type='text' class='form form-control' name='lastname' id='lastname' value="{{old('lastname',$user->lastname)}}">
                </div>
                <div class="col-sm-3">
                    <label>Extensionname</label>
                    <input type='text' class='form form-control' name='extensionname' id='extenstionname' value="{{old('extensionname',$user->extenstionname)}}">
                </div>
            </div>
            <div class="form form-group">
                <div class="col-sm-6">
                    <label>Accesslevel</label>
                    <select name="accesslevel" id="accesslevel" class="form-control" name="accesslevel">
                        <option>Select Access level</option>
                        <option value="100"  @if ($user->accesslevel == 100) selected='' @endif>Admin</option>
                        <option value="1"  @if ($user->accesslevel == 1) selected='' @endif>Instructor</option>
                        <option value="10" @if ($user->accesslevel == 10) selected='' @endif>Dean</option>
                        <option value="11" @if ($user->accesslevel == 11) selected='' @endif>MESIL</option>
                        <option value="12" @if ($user->accesslevel == 12) selected='' @endif>MSBMW</option>
                        <option value="20" @if ($user->accesslevel == 20) selected='' @endif>Registrar College</option>
                        <option value="21" @if ($user->accesslevel == 21) selected='' @endif>Registrar Basic Education</option>
                        <option value="22" @if ($user->accesslevel == 22) selected='' @endif>Office of Student Affairs</option>
                        <option value="23" @if ($user->accesslevel == 23) selected='' @endif>EduTech</option>
                        <option value="24" @if ($user->accesslevel == 24) selected='' @endif>BED Academic Director</option>
                        <option value="25" @if ($user->accesslevel == 25) selected='' @endif>BED Class Lead</option>
                        <option value="26" @if ($user->accesslevel == 26) selected='' @endif>OAA</option>
                        <option value="30" @if ($user->accesslevel == 30) selected='' @endif>Accounting Head</option>
                        <option value="31" @if ($user->accesslevel == 31) selected='' @endif>Accounting Staff</option>
                        <option value="40" @if ($user->accesslevel == 40) selected='' @endif>Cashier</option>
                        <option value="50" @if ($user->accesslevel == 50) selected='' @endif>Bookstore</option>
                        <option value="60" @if ($user->accesslevel == 60) selected='' @endif>Admission - HED</option>
                        <option value="61" @if ($user->accesslevel == 61) selected='' @endif>Admission - BED</option>
                        <option value="62" @if ($user->accesslevel == 62) selected='' @endif>Admission - SHS</option>
                        <option value="70" @if ($user->accesslevel == 70) selected='' @endif>Guidance - HED</option>
                        <option value="71" @if ($user->accesslevel == 71) selected='' @endif>Guidance - BED</option>
                        <option value="80" @if ($user->accesslevel == 80) selected='' @endif>Scholarship - HED</option>
                        <option value="81" @if ($user->accesslevel == 81) selected='' @endif>Scholarship - BED</option>
                        <option value="90" @if ($user->accesslevel == 90) selected='' @endif>BED Academic</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Email</label>
                    <input type='email' class='form form-control' name='email' id='email' value="{{old('email',$user->email)}}">
                </div>
            </div>
            <div class="form form-group">
                <div class="col-sm-12">
                    <input type="submit" value="Save" class="btn btn-success col-sm-12">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter New Password : </h4>
              </div>
                <form method="post" action="{{url('/admin', array('resetpassword'))}}">
                     {{csrf_field()}} 
                     <input type="hidden" name="idno" value="{{$user->idno}}">
              <div class="modal-body">
                  <input type="password" name="password" class="form form-control">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Reset Password">
              </div>
                </form>     
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>

@endsection
@section('footerscript')
@endsection