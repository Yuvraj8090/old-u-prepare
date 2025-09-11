@extends('layouts.admin')

@section('content')
    <style>
        .img-prv {
            width: 150px;
            height: 150px;
            position: relative;
            margin-right: 15px;
        }
        .img-prv-blk {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            position: absolute;
        }
        .img-prv .img-prv-blk img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .img-prv+.img-inp label:not(.control-label) {
            width: 100%;
            color: #878c9f;
            z-index: 10;
            /* font-size: 12px; */
            text-align: left;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .img-prv+.img-inp {
            width: calc(100% - 200px);
        }

        form.row {
            font-size: 16px;
        }
    </style>
    <div>
        <div class=" col-md-12" style="display: inline-block;padding-left:12px;">
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                <h4>My Profile</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">  Settings </a></li>
                        <li class="breadcrumb-item active"><a href="#"> My Profile </a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_content">

                <div class="col-12 d-flex align-items-end mb-3">
                    <div class="img-prv">
                        <div class="img-prv-blk">
                            <img src="{{ asset($user->profile_pic ?? 'assets/img/ph-med.webp') }}" _defs="{{ asset('assets/img/ph-med.webp') }}" />
                        </div>
                    </div>
                </div>

                <form class="row" method="POST" action="{{ route('my.profile.save') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-6">
                        <div class="img-inp">
                            <label class="form-label">Profile Image <small>(Max. 512x512 pixels)</small></label>
                            <input class="form-control @error('image'){{ __('is-invalid') }}@enderror" type="file" _n="image" name="image" />
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label ">Username</label>
                            <div class="">
                                <input type="text" class="form-control" name="username"  value="{{ $user->username }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Department</label>
                            <div class="">
                                <input type="text" class="form-control" name="department"  value="{{ $user->role->department ?? 'N/A' }}" readonly>
                                <p class="error" id="error-department"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Designation</label>
                            <div class="">
                                <input type="text" class="form-control" name="designation" value="{{ $user->designation }}" readonly >
                                <p class="error" id="error-designation"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <div class="">
                                <input type="text" class="form-control @error('name'){{ __('is-invalid') }}@enderror" name="name" value="{{ $user->name }}" >
                                <p class="error" id="error-name"></p>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Gender</label>
                            <div class="">
                                <select class="form-control @error('gender'){{ __('is-invalid') }}@enderror" name="gender">
                                    <option value="">Please Select...</option>
                                    <option value="male" @selected($user->gender == 'male')>Male</option>
                                    <option value="female" @selected($user->gender == 'female')>Female</option>
                                    <option value="other" @selected($user->gender == 'other')>Other</option>
                                </select>
                                <p class="error" id="error-gender"></p>
                                @error('gender')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Email </label>
                            <div class="">
                                <input type="text" class="form-control @error('email'){{ __('is-invalid') }}@enderror" name="email" value="{{ $user->email }}" >
                                <p class="error" id="error-email"></p>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label ">Phone No. </label>
                            <div class="">
                                <input type="text" class="form-control @error('phone'){{ __('is-invalid') }}@enderror" name="phone" value="{{ $user->phone_no }}" >
                                <p class="error" id="error-phone_no"></p>
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <button class="btn btn-success" type="submit">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
