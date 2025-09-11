@extends('layouts.admin')

@section('content')
    <section class="breadcrumbs">
        <div class="row">
            <div class="col-md-12">
                <h4>Departments</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Departments</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Create Departments</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <div class="col-md-2"></div>

    <div class="col-md-8">
        <div class="x_panel">
            <div class="x_content">
                <form autocomplete="off" data-method="POST" data-action="{{route('department.store')}}" enctype="multipart/form-data" id="ajax-form" class="form-horizontal form-label-left">
                    @csrf
                    <div class="form-group">
                        <label class="control-label">Department Name</label>
                        <div class=" ">
                            <input type="text" class="form-control" name="name" placeholder="Enter Dept. name here..." required>
                            <p class="error" id="error-name"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label mb-3">Select atleast one role from below:</label>
                        </div>

                        @if(isset($roles))
                            @foreach($roles as $role)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="role[]" value="{{ $role->id }}" class="custom-control-input" id="role_{{ $role->id }}">
                                            <label class="custom-control-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                                            <p class="error" id="error-role[]"></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-md-12">
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="">
                                <button id="submit-btn" type="submit" class="btn btn-success">
                                    <i class="fa fa-file"></i>
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Add New Departments
                                </button>

                                <a type="reset" class="btn btn-primary pull-right" href="{{url('/department/create')}}">
                                    <i class="fa fa-refresh"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>
@endsection
