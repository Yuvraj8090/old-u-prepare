@extends('layouts.admin')

@section('content')
    <section class="breadcrumbs">
        <div class="row">
            <div class="col-md-12">
                <h4>User Permissions</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">User</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Edit Permissions</a>
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
                <form autocomplete="off" data-method="POST" data-action="{{ route('mis.user.permissions.update', $user->username) }}" class="ajax-form" class="form-horizontal form-label-left">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="form-group">
                        <label class="control-label">Editing User Permissions of: {{ $user->name }} [<small>{{ $user->username }}</small>]</label>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="row">
                        @if($permissions->count())
                            @foreach($permissions as $permission)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="permission[]" value="{{ $permission->id }}" class="custom-control-input" id="permission_{{ $permission->id }}" @checked($user->permissions->contains('id', $permission->id))>
                                            <label class="custom-control-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                            <p class="error" id="error-permission[]"></p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="ln_solid"></div>

                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <div class="">
                                <button id="submit-btn" type="submit" class="btn btn-success">
                                    <i class="fa fa-file"></i>
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Update Permissions
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-2"></div>
@endsection
