@extends('layouts.admin')

@section('content')

<div>
    <div class=" col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" > 
            <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>Change Password</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">  Settings </a></li>
                <li class="breadcrumb-item active"><a href="#"> Change Password </a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">

    <div class="x_content">
        <form autocomplete="off" data-method="POST" data-action="{{ url('post/change-password') }}" id="ajax-form" class="form-horizontal form-label-left">
            @csrf
            
            <div class="col-md-12">  <div class="form-group ">
                <label class="control-label "> User Name </label>
                <div class="">
                    <input type="text" class="form-control" value="{{ auth()->user()->username }}" disabled >
                </div>
            </div>

          </div>

          <div class="col-md-12">  <div class="form-group ">
                <label class="control-label "> Password </label>
                <div class="">
                    <input type="text" class="form-control" name="password"  >
                    <p class="error" id="error-password"></p>
                </div>
            </div>

          </div>

          <div class="col-md-12">  <div class="form-group ">
                <label class="control-label ">New Password </label>
                <div class="">
                    <input type="text" class="form-control" name="new_password"  >
                    <p class="error" id="error-new_password"></p>
                </div>
            </div>
          </div>

          <div class="col-md-12">  <div class="form-group ">
                <label class="control-label ">Confirm New Password</label>
                <div class="">
                    <input type="text" class="form-control" name="confirm_new_password" >
                    <p class="error" id="error-confirm_new_password"></p>
                </div>
            </div>

          </div>

          <div class="col-md-12">
            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="">
             
                    <button id="submit-btn"  type="submit" class="btn btn-success">
                        <i class="fa fa-file" ></i>
                        <span class="loader" id="loader" style="display: none;"></span> 
                        Update Password
                    </button>
                    
                    <a type="reset" class="btn btn-primary pull-right previousButton" href="">
                        <i class="fa fa-refresh" ></i>
                        Reset
                    </a>
                </div>
            </div>
          </div>

        </form>
    </div>
</div>
@stop