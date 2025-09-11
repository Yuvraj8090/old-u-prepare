@extends('layouts.admin')

@section('content')
    <section class="breadcrumbs">
        <div class="row">
            <div class="col-md-12">
                <h4>Role & Permission</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Role & Permission</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Edit Role & Permission</a>
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
                <form autocomplete="off" data-method="POST" data-action="{{ route('departments.update') }}" enctype="multipart/form-data" class="ajax-form" class="form-horizontal form-label-left">
                    @csrf
                    <input type="hidden" name="id" value="{{ $department->id }}">
                    <div class="form-group">
                        <label class="control-label">Role Name</label>
                        <div class=" ">
                            <input type="text" class="form-control" name="name" value="{{old('name',$department->name)}}" required>
                            <p class="error" id="error-name"></p>
                        </div>
                    </div>

                    <div class="row">
                        @if(isset($roles))
                            @foreach($roles as $perm)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="role[]" value="{{ $perm->id }}" class="custom-control-input" id="role_{{ $perm->id }}"
                                                @if($department->roles->contains('id', $perm->id)) checked @endif>
                                            <label class="custom-control-label" for="role_{{ $perm->id }}">{{ $perm->name }}</label>
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
                                    Update Role
                                </button>

                                <a type="reset" class="btn btn-primary pull-right" href="{{ url('department/' . $department->id . '/edit') }}">
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
