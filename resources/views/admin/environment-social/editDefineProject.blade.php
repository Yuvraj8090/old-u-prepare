@extends('layouts.admin')

@section('content')
    <style>
        .ck-editor__editable {
            height: 300px; /* Your desired height */
        }

        #editor2 {
            height: 300px; /* Your desired height */
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            @if(CheckESLevelThree() == 1)
                <h5 style="font-weight:550;">Environmental Safeguard Scope Update | Project : {{ $data->name ?? '' }}</h5>
            @else
                <h5 style="font-weight:550;">Social Safeguard Scope Update | Project : {{ $data->name ?? '' }}</h5>
            @endif
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('/procurement') }}">Define Project </a></li>
                </ol>
            </nav>
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    @include('admin.project.components.project-details')

    @include('admin.project.components.approve-details')

    <form autocomplete="off" data-method="POST" data-action="{{ url('/social/environment/define/update/' . $data->defineProject->id) }}" id="ajax-form" class="form-horizontal form-label-left">
        @csrf

        <div class="x_panel">
            <div class="x_title">
                @if(CheckESLevelThree() == 1)
                    <h5 style="font-weight:550;">Environmental Safeguard Scope</h5>
                @else
                    <h5 style="font-weight:550;">Social Safeguard Scope</h5>
                @endif

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <input type="hidden" name="project_id" value="{{ $data->id }}" />

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="">
                                  <input id="x" type="hidden" value="{{ $data->defineProject->define_project ?? ''}}" name="scope_of_project">
                                  <trix-editor input="x"></trix-editor>
                                <p class="error" id="error-scope_of_project"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Assign To:</label>
                            @if(count($users) == 0)
                                <b>Note:-Please create logins first before assign.</b>
                            @endif
                            <div class="">
                                <select class="form-control" name="assign_to">
                                    <option value="">SELECT</option>
                                    @if(count($users) > 0)
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" @selected($u->id == $data->es_level_four)>{{ $u->name }} ({{ $u->username }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="error" id="error-assign_to"></p>
                            </div>
                        </div>
                    </div>

                    @if(false)
                        @if(CheckESLevelThree() == 1)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Environment Screening Report:</label>
                                    <br>

                                    @if(!empty($data->defineProject->environment_screening_report ))
                                        <a href="javascript:void(0)" onClick="openPDF('{{ $data->defineProject->environment_screening_report }}')" class="btn btn-primary btn-md">View Document</a>
                                        <a download href="{{ $data->defineProject->environment_screening_report }}" class="btn btn-danger btn-md">Download Doucment</a>
                                    @endif

                                    <div class="">
                                        <input  type="file" class="form-control" name="environment_screening_report">
                                        <p class="error" id="error-enviornment-screening-report"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Environment Management Plan:</label>
                                    <br>

                                    @if(!empty($data->defineProject->environment_screening_report ))
                                        <a href="javascript:void(0)" onClick="openPDF('{{ $data->defineProject->environment_management_plan }}')" class="btn btn-primary btn-md">View Document</a>
                                        <a download href="{{ $data->defineProject->environment_management_plan }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                    @endif
                                    <div class="">
                                        <input  type="file" class="form-control" name="environment_management_plan">
                                        <p class="error" id="error-enviornment-management-plan"></p>
                                    </div>
                                </div>
                            </div>
                        @elseif(CheckESLevelThree() == 2)
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Social Screening Report:</label>
                                    <br>

                                    @if(!empty($data->defineProject->social_screening_report ))
                                        <a href="javascript:void(0)" onClick="openPDF('{{ $data->defineProject->social_screening_report }}')" class="btn btn-primary btn-md" >View Document</a>
                                        <a download href="{{ $data->defineProject->social_screening_report }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                    @endif

                                    <div class="">
                                        <input  type="file" class="form-control" name="social_screening_report">
                                        <p class="error" id="error-social-screening-report"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Resettlement Action Plan (If Mandated):</label>
                                    <br>

                                    @if(!empty($data->defineProject->social_resettlement_action_plan ))
                                        <a href="javascript:void(0)" onClick="openPDF('{{ $data->defineProject->social_resettlement_action_plan }}')" class="btn btn-primary btn-md" >View Document</a>
                                        <a download href="{{ $data->defineProject->social_resettlement_action_plan }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                    @endif

                                    <div class="">
                                        <input  type="file" class="form-control" name="social_resettlement_action_plan">
                                        <p class="error" id="error-social-resettlement-action-plan"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Social Management Plan:</label>
                                    <br>

                                    @if(!empty($data->defineProject->social_management_plan ))
                                        <a href="javascript:void(0)" onClick="openPDF('{{ $data->defineProject->social_management_plan }}')" class="btn btn-primary btn-md" >View Document</a>
                                        <a download href="{{ $data->defineProject->social_management_plan }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                    @endif

                                    <div class="">
                                        <input  type="file" class="form-control" name="social_management_plan">
                                        <p class="error" id="error-social-management-plan"></p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <button id="submit-btn" type="submit" class="btn btn-md btn-primary">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Update
                        </button>
                    </div>
                </div>

                <br>
            </div>
        </div>
    </form>
@stop
