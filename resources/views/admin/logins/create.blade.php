@extends('layouts.admin')

@section('content')

<div>
    <div class=" col-md-12" style="display: inline-block;padding-left:12px;">
        <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
        <h4>Create New Login</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#">  Manage Logins </a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#">  Create New Login </a>
                </li>
            </ol>
        </nav>
    </div>
</div>


<div class="x_panel">
    <div class="x_content">
        <form autocomplete="off" data-method="POST" data-action="{{ route('manage-logins.store') }}" enctype="multipart/form-data" id="ajax-form" class="form-horizontal form-label-left">
            @csrf

            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label">Profile Image </label>
                    <div class=" ">
                        <input type="file" class="form-control" name="image"  />
                        <p class="error" id="error-profile_image"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">User Name </label>
                    <div class=" ">
                        <input type="text" class="form-control" name="username" placeholder="username">
                        <p class="error" id="error-username"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label  "> Department </label>
                    <div class=" ">
                        <select class="form-control" name="department">
                            <option value="">SELECT DEPARTMENT</option>
                                @if(isset($department))
                                    @foreach($department as $dis)
                                    <option value="{{ $dis->id }}">{{ $dis->name }} </option>
                                    @endforeach
                                @endif
                        </select>
                        <p class="error" id="error-department"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label  "> Designation </label>
                    <div class=" ">
                        <select class="form-control" name="designation">
                            <option value="">SELECT DESIGNATION</option>
                                @if(isset($designation))
                                    @foreach($designation as $dis)
                                    <option value="{{ $dis->name }}">{{ $dis->name }} </option>
                                    @endforeach
                                @endif
                        </select>
                        {{-- <input type="text" class="form-control" name="designation" > --}}
                        <p class="error" id="error-designation"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label">Name</label>
                    <div class=" ">
                        <input type="text" class="form-control" name="name" >
                        <p class="error" id="error-name"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label  ">Email </label>
                    <div class=" ">
                        <input type="text" class="form-control" name="email">
                        <p class="error" id="error-email"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label  ">Phone No. </label>
                    <div class=" ">
                        <input type="number" class="form-control" name="phone_no" >
                        <p class="error" id="error-phone_no"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label  ">Password </label>
                    <div class=" ">
                        <input type="text" class="form-control" name="password" >
                        <p class="error" id="error-password"></p>
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
                            Create New Login
                        </button>

                        <a type="reset" class="btn btn-primary pull-right" href="{{ route('manage-logins.create') }}">
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
