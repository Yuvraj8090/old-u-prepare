@extends('layouts.admin')

@section('content')
    <div>
        <div class=" col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>Update Login</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ url('manage-logins') }}">Manage Logins</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Update Login</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_content">
            <form autocomplete="off" data-method="POST" data-action="{{ route('pmu.login.update', $data->id) }}" id="ajax-form" class="form-horizontal form-label-left">
                @csrf

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label ">Profile Image </label>
                        <div class="">
                            <img src="{{ $data->profile_image }}" style="width:300px;" />
                            <input type="file" class="form-control" name="image"  />
                            <p class="error" id="error-image"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">User Name</label>
                        <div class="">
                            <input type="text" class="form-control" name="username" value="{{ $data->username }}">
                            <p class="error" id="error-username"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Department</label>
                        <div class="">
                            <select class="form-control" name="department">
                                <option value="">SELECT DEPARTMENT</option>
                                    @if($departments->count())
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" @selected($department->id == $data->department_id)>{{ $department->name }}</option>
                                        @endforeach
                                    @endif
                            </select>
                            <p class="error" id="error-department"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Designation</label>
                        <div class="">
                            {{-- <input type="text" class="form-control" name="designation" value="{{ $data->designation }}" > --}}
                            <select class="form-control" name="designation">
                                <option value="">SELECT DESIGNATION</option>
                                @if(count($designation) > 0)
                                    @foreach($designation as $dis)
                                        <option value="{{ $dis->name }}" @if($dis->name == $data->designation) selected @endif>{{ $dis->name }}</option>
                                    @endforeach
                                @endif                                {{--
                                @foreach ($departments as $department)
                                    @if($department->designations->count())
                                        <optgroup label="{{ $department->name }}">
                                            @foreach($department->designations as $designation)
                                                <option value="{{ $designation->id }}" @selected($designation->id == $data->designation_id)>{{ $designation->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach                                --}}
                            </select>
                            <p class="error" id="error-designation"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <div class="">
                            <input type="text" class="form-control" name="name" value="{{ $data->name }}" >
                            <p class="error" id="error-name"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <div class="">
                            <input type="text" class="form-control" name="email" value="{{ $data->email }}" >
                            <p class="error" id="error-email"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Phone No.</label>
                        <div class="">
                            <input type="number" class="form-control" name="phone_no" value="{{ $data->phone_no }}" >
                            <p class="error" id="error-phone_no"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <div class="">
                            <input type="text" class="form-control" name="user_password" >
                            <p class="error" id="error-user_password"></p>
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
                                Update Login
                            </button>

                            <a type="reset" class="btn btn-primary pull-right" href="{{ route('manage-logins.edit', $data->id) }}">
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
