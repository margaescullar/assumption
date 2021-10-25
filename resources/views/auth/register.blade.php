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
        Register User
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Register User</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading">
            Register
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('idno') ? ' has-error' : '' }}">
                    <label for="idno" class="col-md-4 control-label">ID Number</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="idno" value="{{ old('idno') }}" required autofocus>

                        @if ($errors->has('idno'))
                        <span class="help-block">
                            <strong>{{ $errors->first('idno') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                    <label for="idno" class="col-md-4 control-label">First Name</label>
                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

                        @if ($errors->has('firstname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('firstname') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('middlename') ? ' has-error' : '' }}">
                    <label for="middlename" class="col-md-4 control-label">Middle Name</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="middlename" value="{{ old('middlename') }}" autofocus>

                        @if ($errors->has('middlename'))
                        <span class="help-block">
                            <strong>{{ $errors->first('middlename') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                    <label for="lastname" class="col-md-4 control-label">Last Name</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required autofocus>

                        @if ($errors->has('lastname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('lastname') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('extensionname') ? ' has-error' : '' }}">
                    <label for="firstname" class="col-md-4 control-label">Extension Name</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control" name="extensionname" value="{{ old('extensionname') }}" autofocus>

                        @if ($errors->has('extensionname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('extensionname') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>
                <div class="form-group{{ $errors->has('accesslevel') ? ' has-error' : '' }}">
                    <label for="accesslevel" class="col-md-4 control-label">Access Level</label>

                    <div class="col-md-6">
                        <select id="accesslevel" class="form-control" name="accesslevel">
                            <option>Select Access level</option>
                            <option value="1">Instructor</option>
                            <option value="10">Dean</option>
                            <option value="11">MESIL</option>
                            <option value="12">MSBMW</option>
                            <option value="20">Registrar College</option>
                            <option value="21">Registrar Basic Education</option>
                            <option value="22">OSA</option>
                            <option value="23">EduTech</option>
                            <option value="24">BED Academic Director</option>
                            <option value="25">BED Class Lead</option>
                            <option value="26">OAA</option>
                            <option value="27">Club Moderator</option>
                            <option value="30">Accounting Head</option>
                            <option value="31">Accounting Staff</option>
                            <option value="40">Cashier</option>
                            <option value="50">Bookstore</option>
                            <option value="60">Admission-HED</option>
                            <option value="61">Admission-BED</option>
                            <option value="62">Admission-SHS</option>
                            <option value="70">Guidance-HED</option>
                            <option value="71">Guidance-BED</option>
                            <option value="80">Scholarship-HED</option>
                            <option value="81">Scholarship-BED</option>
                            <option value="90">BED Academic</option>
                        </select>    

                        @if ($errors->has('accesslevel'))
                        <span class="help-block">
                            <strong>{{ $errors->first('accesslevel') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required>

                        @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
