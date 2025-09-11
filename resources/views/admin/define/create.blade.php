@extends('layouts.admin')

@section('content')
    <style>
        .ck-editor__editable {
            height: 300px; /* Your desired height */
        }

        #editor2 {
            height: 300px; /* Your desired height */
        }

        .x_panel.msxp table th,
        .x_panel.msxp table td {
            vertical-align: middle;
        }

        .x_panel.msxp table tfoot th {
            font-size: 24px;
            text-align: center;
            font-weight: 700;
        }

        .cms_pub .boq .alert-warning {
            zoom: 120%;
            color: #856404;
            border-color: #ffeeba;
            background-color: #fff3cd;
        }

        .pointer {
            cursor: pointer;
        }

        .nav-tabs .nav-link {
            outline: none;
            margin-bottom: -1px !important;
            background-color: transparent;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            @include('admin.include.backButton')

            <h4>Define Project || Project Name: {{ $data->name ?? '' }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ url('/procurement') }}">Define Project </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css" /> --}}
    {{-- <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script> --}}

    @include('admin.project.components.project-details')

    @include('admin.project.components.approve-details')

    @include('admin.project.components.procurement')

    @include('admin.procurement.procurement-show')

    @include('admin.project.components.contract')

    @include('admin.project.components.contractor')

    <form id="ajax-form" autocomplete="off" data-method="POST" data-action="{{ route('project.defineProjectByLvlTwo', $data->defineProject->id ?? $data->id ) }}" class="form-horizontal form-label-left">
        @csrf

        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">ASSIGNEE/INCHARGE DETAILS:</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Assignee/Incharge:</label>
                            <div class="">
                                <select id="selectDepartment" class="form-control" name="department" >
                                    <option value="">SELECT</option>
                                    @if(count($department) > 0)
                                        @foreach($department as $dep)
                                            @if(!$assignDepartmentDetails || $assignDepartmentDetails->id != $dep->id)
                                                <option value="{{ $dep->id }}" {{ ($data->assign_level_2  == $dep->id) ? 'selected' : '' }}>{{ $dep->name }} ({{ $dep->username }})</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <p class="error" id="error-department"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        @if($assignDepartmentDetails)
                            <div class="row" id="departmentShow">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Name:</label>
                                        <div class=" ">
                                            <input type="text" id="departName" value="{{ $assignDepartmentDetails->name }} ({{ $assignDepartmentDetails->username }})" class="form-control" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Designation:</label>
                                        <div class=" ">
                                            <input type="text" id="departDesignation"  value="{{ $assignDepartmentDetails->designation }}"  class="form-control" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact:</label>
                                        <div class=" ">
                                            <input type="text"  id="departContact" class="form-control" value="{{ $assignDepartmentDetails->phone_no }}" readonly  />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Email:</label>
                                        <div class=" ">
                                            <input type="text" id="departEmail" class="form-control" value="{{ $assignDepartmentDetails->email }}" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">DEFINE PROJECT</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <input type="hidden" name="project_id" value="{{ $data->id }}" />

                <div class="row">
                    <div class="col-md-12"></div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Scope of Work:</label>
                            <div class="">
                                  {{-- <input id="x" type="hidden" value="{{ $data->defineProject->scope_of_work ?? ''}}" name="scope_of_work"> --}}
                                  {{-- <trix-editor input="x"></trix-editor> --}}
                                <textarea name="scope_of_work" rows="4" class="form-control">{{ $data->defineProject->scope_of_work }}</textarea>
                                <p class="error" id="error-scope_of_work"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Objective:</label>
                            <div class=" ">
                                {{-- <input id="y" type="hidden" value="{{ $data->defineProject->objective ?? ''}}" name="objective"> --}}
                                {{-- <trix-editor input="y"></trix-editor> --}}
                                <textarea name="objective" rows="4" class="form-control">{{ $data->defineProject->objective ?? '' }}</textarea>

                                <p class="error" id="error-objective"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <button id="submit-btn" type="submit" class="btn btn-md btn-primary">
                            <span class="loader" id="loader" style="display: none;"></span>
                            {{ isset($data->defineProject->objective) ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="clearfix"></div>

    @php $is_msts = $data->contract && !is_null($data->contract->ms_type) @endphp
    @php $is_boqs = $is_msts && $data->contract->ms_type && $data->contract->pwd_boqs->count(); @endphp
    @php $nmsi    = 1; @endphp
    @php $nmsn    = ''; @endphp
    @php $nmssd   = ''; @endphp
    @php $nmsed   = ''; @endphp

    <div class="row">
        <div id="milestonesButton" class="col-md-12 col-sm-12">
            <div class="x_panel msxp">
                <div class="x_title d-flex align-items-center justify-content-between">
                    <h5 style="font-weight:550;">PROJECT MILESTONE</h5>

                    {{-- @if( ($data->contract && !$is_msts) || ($is_msts && ( !$data->contract->ms_type && $avcfAllocation) ) ) --}}
                    <span>
                    @if( !$is_msts || ( $is_msts && ( $avcfAllocation  || ($data->contract->ms_type && !$is_boqs) ) ) )
                        <a class="pull-right btn btn-sm btn-cmspup btn-success" type="button" data-toggle="modal" data-target="#createModal" href="{{ route('mile.stone.create', $data->id) }}">Create Milestones</a>
                    @endif
                    @if($is_msts && $data->contract->ms_type && auth()->user()->role->department == 'PWD' && auth()->user()->role->level == "TWO")
                        <a href="#" class="btn btn-sm btn-success btn-vboqs">View BOQ Sheet</a>
                        <a href="{{ route('project.contract.boqsheet.update', $data->id) }}" class="btn btn-sm btn-info">Edit BOQ Sheet</a>
                    @endif
                    </span>
                    {{-- <div class="clearfix"></div> --}}
                </div>

                <div class="x_content">
                    @if($milestone->count())
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">

                                <div id="datatable-buttons_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap no-footer px-0">
                                    <table id="datatable-buttons" class="table table-striped table-bordered dataTable no-footer dtr-inline" style="width: 100%;" role="grid" aria-describedby="datatable-buttons_info">
                                        <thead>
                                            <tr role="row">
                                                <th style="width: 20px;">S.No</th>
                                                <th style="width: 150px;">Milestone</th>
                                                <th style="width: 66px;">Budget</th>
                                                <th style="width: 59px;">% of total work</th>
                                                <th style="width: 150px;">Date</th>
                                                <th style="width: 150px;">Amended date</th>
                                                <th style="width: 64px;">Physical Progress</th>
                                                <th style="width: 64px;">Financial Progress</th>
                                                <th style="width: 74px;">Cumulative cost</th>
                                                <th style="width: 80px;">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody class="text-center">
                                            {{-- @if($milestone->count()) --}}
                                                @foreach($milestone as $key => $datum)
                                                    <tr role="row" class="odd">
                                                        <th tabindex="0" class="sorting_1">M{{ ++$key }}.</th>
                                                        <th>{{ $datum->name }}</th>
                                                        <th class="currency">{{ $datum->budget }}</th>
                                                        <th>{{ $datum->percent_of_work }}%</th>
                                                        <td> Start : {{ date('d-m-Y', strtotime($datum->start_date))}} <br > End : {{ date('d-m-Y', strtotime($datum->end_date)) }}</td>
                                                        <td>
                                                            Start : {{ $datum->amended_start_date ? date('d-m-Y', strtotime($datum->amended_start_date)) : 'N/A'}}
                                                            <br>
                                                            End : {{ $datum->amended_end_date ? date('d-m-Y', strtotime($datum->amended_end_date)) : 'N/A'}}
                                                        </td>

                                                        <td class="project_progress">
                                                            <div class="progress progress_sm">
                                                                <div class="progress-bar bg-green" role="progressbar" style="width:{{ $datum->physicalProgress }}%" data-transitiongoal="{{ $datum->physicalProgress }}"></div>
                                                            </div>
                                                            <small>{{ $datum->physicalProgress }}% Complete</small>
                                                        </td>

                                                        <td class="project_progress">
                                                            <div class="progress progress_sm">
                                                                <div class="progress-bar bg-green" role="progressbar" style="width:{{ $datum->financialProgress }}%" data-transitiongoal="{{ $datum->financialProgress }}"></div>
                                                            </div>
                                                            <small>{{ $datum->financialProgress }}% Complete</small>
                                                        </td>

                                                        <td>{{ $datum->financialAccumulativeAmount ?? 0 }}</td>

                                                        <td>
                                                            {{--
                                                            <a class="btn btn-sm btn-info" data-url="{{ route('mile.stone.edit', $datum->id) }}" href="{{ route('mile.stone.edit', $datum->id) }}">
                                                                <i class="fa fa-pencil" ></i> Edit
                                                            </a>
                                                            --}}
                                                            <a class="btn btn-sm btn-primary btn-ems pointer text-white"  data-toggle="modal" data-target="#editModal" data-info='@json($datum)'>
                                                                <i class="fa fa-pencil" ></i> Edit
                                                            </a>
                                                            @if($datum->media->count())
                                                            <a href="{{ route('project.define.milestone.site.images', $datum->id) }}" class="btn btn-sm btn-info">View Images</a>
                                                            @endif
                                                            <!--
                                                                <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This action is not reversable.');" href="{{ route('mile.stone.destroy',$datum->id) }}">Delete</a>
                                                            -->
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            {{-- @endif --}}
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th class="fs-24 currency">{{ $amAmount }}</th>
                                                <th>{{ $mCentage }}%</th>
                                                <th colspan="6"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

<style>
    .ms_pub table.table tr > th,
    .ms_pub table.table tr > td {
        border: 1px solid #008000;
        padding: 0.25rem;
        font-size: 18px !important;
    }

    .ms_pub table.table tr > td {
        text-align: center;
        border-color: black;
    }

    .mx-n15x {
        margin-left: -15px !important;
        margin-right: -15px !important;
    }

    #AddInput .form-group {
        align-items: center;
        margin-bottom: 0;
    }

    #AddInput .form-group p,
    #AddInput .form-group label {
        margin: 0;
        padding: 0;
    }

    #AddInput .form-group button {
        margin-bottom: 0;
    }

    #AddInput .form-group:not(:nth-child(2)) {
        margin-top: 15px;
    }
</style>

@section('modal')
    {{-- @if(!$is_msts || ($is_msts && !$data->contract->ms_type)) --}}
    @if(!$is_msts || ( $is_msts && ( $avcfAllocation  || ($data->contract->ms_type && !$is_boqs) ) ))
    <!-- CREATE MILESTONE -->
    <div class="modal ms_pub cms_pub" id="createModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create Milestone: {{ $data->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form class="form-horizontal form-label-left ajax-form mb-0" autocomplete="off" data-method="POST" data-action="{{ url('/project/milestones/store/' . $data->id) }}" enctype="multipart/form-data" _ccv="{{ $contracts->procurement_contract }}" _amp="{{ $avcfAllocation }}" _ama="{{ $avafAllocation }}" autocomplete="off">
                    <div class="modal-body">
                        @csrf
                        @if($is_msts || $milestone->count())
                            <input type="hidden" name="ms_type" value="{{ $data->contract->ms_type }}" />
                        @endif

                        <div class="form-group row">
                            {{--
                            <div class="col-md-12">
                                <label for="ms_type" class="control-label">Select Milestones Type</label>
                                <select id="ms_type" name="ms_type" class="form-control" {{ $is_msts || $milestone->count() ? 'disabled' : '' }}>
                                <!--select id="ms_type" name="ms_type" class="form-control"-->
                                    <option value="">SELECT</option>
                                    <option value="0" @selected($avcfAllocation)>EPC</option>
                                    @if(!$is_msts || !$data->contract->ms_type)
                                    <option value="1" @disabled($is_msts && $data->contract->ms_type)>Item Wise</option>
                                    @else
                                    <option value="" disabled>BOQ Already Uploaded</option>
                                    @endif
                                </select>
                            </div>
                            --}}
                            <div class="col-md-12">
                                <label for="ms_type" class="control-label">Select Milestones Type</label>
                                <select id="ms_type" name="ms_type" class="form-control" {{ $is_msts || $milestone->count() ? 'disabled' : '' }}>
                                    <option value="">SELECT</option>
                                    <option value="0" @selected($is_msts && !$data->contract->ms_type)>EPC</option>
                                    <option value="1" @selected($is_msts && $data->contract->ms_type)>Item Wise</option>
                                </select>
                            </div>
                        </div>

                        <div class="ln_solid mx-n15x"></div>

                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                @if(!$is_msts || ($is_msts && $data->contract->ms_type))
                                <button id="nav-boq-tab" @class(['nav-link', 'active'=> $is_msts, 'd-none'=> !$is_msts]) data-toggle="tab" data-target="#nav-boq" type="button" role="tab">Upload BOQ</button>
                                @endif
                                <button id="nav-ms-tab" @class(['nav-link', 'active'=> (!$is_msts || ($is_msts && !$data->contract->ms_type))]) data-toggle="tab" data-target="#nav-ms" type="button" role="tab">Create Milestones</button>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                            @if(!$is_msts || ($is_msts && $data->contract->ms_type))
                            <div @class(['tab-pane', 'fade', 'show'=> $is_msts, 'active'=> $is_msts]) id="nav-boq" role="tabpanel">
                                <div class="boq">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            @if($is_msts && $is_boqs)
                                            <div class="alert alert-primary my-0 h6">
                                                <strong>Info!</strong> BOQ sheet is already uploaded for this contract.
                                            </div>
                                            @else
                                            <div class="alert alert-warning my-0">
                                                <strong>Warning!</strong> Please upload the BOQ sheet in the format approved by the
                                                MIS Administrator. Failure to do so may result in application errors and data loss.
                                            </div>
                                            <label for="boq_doc" class="control-label mb-0">Upload BOQ Sheet</label>
                                            <input type="file" class="form-control" name="boq_sheet">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div @class(['tab-pane', 'fade', 'show'=> (!$is_msts || ($is_msts && !$data->contract->ms_type)), 'active'=> (!$is_msts || ($is_msts && !$data->contract->ms_type))]) id="nav-ms" role="tabpanel">
                                @if(!$is_msts || ($is_msts && $avcfAllocation))
                                <div class="i_w">
                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-3">Milestone Name</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" class="form-control" name="name" value="Milestone {{ $milestone->count() + 1 }}" />
                                            <p class="error" id="error-name"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12">
                                            <h5>Total Contract Value: <span class="currency">{{ $contracts->procurement_contract }}</span></h5>
                                        </div>
                                        <div class="col-12">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Milestone</th>
                                                        <th>% of Work</th>
                                                        <th>Alloted Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if($milestone->count())
                                                        @foreach($milestone as $key => $ms)
                                                            <tr>
                                                                <td>M{{ $key + 1 }}</td>
                                                                <td>{{ $ms->percent_of_work }}%</td>
                                                                <td class="currency">{{ $ms->budget }}</td>
                                                            </tr>
                                                            @php
                                                                $nmsi = $key + 1;
                                                            @endphp
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-3">Percent of Work <br>(In %)</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="number" class="form-control" name="percent_of_work" min="1"  max="100" placeholder="in % (only numeric)">
                                            <p class="error" id="error-percent_of_work"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-3">Budget</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="text" class="form-control disabled" name="budget" placeholder="only numeric" />
                                            <p class="error" id="error-budget"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-3 ">Start Date</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="date" class="form-control airpicker" name="start_date" />
                                            <p class="error" id="error-start_date"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label col-md-3 col-sm-3">End Date</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="date" class="form-control airpicker" name="end_date" />
                                            <p class="error" id="error-start_date"></p>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-info btn-sm pull-right" id="AddButton" > + Add Required Document</button>
                                        </div>
                                    </div>

                                    <div id="AddInput">
                                        <div class="ln_solid mx-n15x"></div>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-primary my-0 h6">
                                    <strong>Info!</strong> Milestones are Complete (100%).
                                </div>
                                @endif

                            </div>
                        </div>
                        {{-- @endif --}}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn mb-0 btn-secondary" data-dismiss="modal">Close</button>
                        <button id="submit-btn" class="btn mb-0 btn-success"  type="submit">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Create
                        </button>
                    </div>

                    <input type="hidden" id="input" value="1" />
                </form>
            </div>
        </div>
    </div>
    @endif


    <!-- UPDATE MILESTONE -->
    <div class="modal ms_pub ums_pub" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit MileStone: {{ $data->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form autocomplete="off" id="editform" data-method="POST" data-action="{{ url('project/milestones/update') }}" class="form-horizontal form-label-left ajax-form-edit">
                        @csrf

                        <input type="hidden" name="project_id" value="{{ $data->id }}" />
                        <input type="hidden" name="milestone_id" value="">

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Milestones Name</label>
                            <div class="col-md-9 col-sm-9">
                                <input type="text" class="form-control" name="name" data-key="name" placeholder="Name..">
                                <p class="error" id="editerror-name"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <h5>Total Contract Value: <span class="currency">{{ $contracts->procurement_contract }}</span></h5>
                            </div>
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Milestone</th>
                                            <th>% of Work</th>
                                            <th>Alloted Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($milestone->count())
                                            @foreach($milestone as $key => $ms)
                                                <tr class="ms-{{ $ms->id }}">
                                                    <td>M{{ $key + 1 }}</td>
                                                    <td>{{ $ms->percent_of_work }}%</td>
                                                    <td class="currency">{{ $ms->budget }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Percent of Work</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="number" class="form-control" name="percent_of_work"  min="1"  max="100"  data-key="percent_of_work"  placeholder="Percentage of Work..">
                                <p class="error" id="editerror-percent_of_work"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Budget</label>
                            <div class="col-md-9 col-sm-9">
                                <input type="text" class="form-control" name="budget"  data-key="budget" placeholder="Budget..">
                                <p class="error" id="editerror-budget"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Start Date </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" name="start_date" data-key="start_date" placeholder="Start Date.." readonly>
                                <p class="error" id="editerror-start_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">End Date </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" name="end_date" data-key="end_date" placeholder="End Date.." readonly>
                                <p class="error" id="editerror-end_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Amended Start Date</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="date" class="form-control airpicker" name="amended_start_date" data-key="amended_start_date" placeholder="Amended Start Date..">
                                <p class="error" id="editerror-start_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Amended End Date</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="date" class="form-control airpicker" name="amended_end_date" data-key="amended_end_date" placeholder="Amended End Date..">
                                <p class="error" id="editerror-end_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-info btn-sm btn-ard pull-right">+ Add Required Document</button>
                            </div>
                        </div>

                        <div class="ard-msu">
                            <div class="ln_solid mx-n15x"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button id="submit-btn"  type="submit" class="btn btn-success">
                                <span class="loader" id="loader" style="display: none;"></span>
                                Update
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        $('#AddButton').click(function() {
            var i = $('#input').val();

            var html = '<div class="form-group row" id="removeInput'+i+'">';
                html += '<label class="control-label col-md-3 col-sm-3 ">Document Name </label>';
                html += '<div class="col-md-7">';
                html += '<input type="text" class="form-control" name="documents[]" placeholder="Document Name..">';
                html += '<p class="error" id="error-start_date"></p>';
                html += '</div>';
                html += '<div class="col-md-2">';
                html += '<button type="button" data-id="'+i+'" class="btn btn-danger btn-sm removeInput">Remove</button>';
                html += '</div>';
                html += '</div>';

                i = parseInt(i, 10) + 1;

                $('#input').val(i);
                $('#AddInput').append(html);
        });

        $(document).on('click', '.removeInput', function() {
            var id = $(this).data('id');

            $('#removeInput'+id).remove();
            console.log(id);
        });

        $('#selectDepartment').change(function(e) {
            e.preventDefault();

            $('#departmentShow').hide();
            var id = $(this).val();

            $.ajax({
                url: "{{ url('show/department/details/') }}/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#departName').val(response.name);
                    $('#departDesignation').val(response.designation);
                    $('#departContact').val(response.phone_no);
                    $('#departEmail').val(response.email);

                    $('#departmentShow').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        // Milestone Creation Popup Functionality
        let $mpuf = $('.cms_pub form');

        let $mt = $mpuf.find('select[name="ms_type"]');
        let $cv = parseFloat({{ $contracts->procurement_contract }});   // Contract Value
        let $ac = parseInt({{ $avcfAllocation }});                      // Available Percentage for Allocation
        let $aa = parseInt({{ $avafAllocation }});                      // Available Amount for Allocation
        let $ap = 100 - $ac;                                            // Allocated Percentage
        let $ab = $cv - $aa;                                            // Allocated Budget

        $('input[name="budget"]').on('keyup input paste', function() {
            let $tv  = parseFloat($(this).val()) || 0;
            let $mab = parseFloat($(this).attr('_csv')) || 0;

            if($tv > ($aa + $mab)) {
                if(confirm(`The Budget cannot be more than ${currencyFormatter.format($aa + $mab)} as ${currencyFormatter.format($ab - $mab)} of total contract value (${currencyFormatter.format($cv)}) is already allocated!\n\nClick Ok to fill max available amount.`)) {
                    $(this).val(($aa + $mab));
                }
            }
        });

        $('input[name="percent_of_work"]').on('keyup input paste', function() {
            let $ti  = $(this);
            let $tv  = parseFloat($ti.val()) || 0;                              // Input Percentage Value
            let $bvi = $(this).closest('form').find('input[name="budget"]');    // Budget Value Input
            let $mab = parseFloat($bvi.attr('_csv')) || 0;                      // 
            let $ctc = $ac;                                                     // Percentage to check
            let $aac = $ti.attr('_csv');                                        // Already Allocated Percentage for Update Popup
                $aac = $aac ? Number($aac) : null;

            if($aac && $aac >= 0) {
                $ctc = $ac + $aac;
            }

            if($ti.val() > $ctc) {
                let $amsg = `The percentage cannot be more than ${$ctc} as ${$ap}% of total (100%) is already allocated!`;

                if($aac) {
                    $amsg = `The percentage cannot be more than ${$ctc} as ${$aac}% is already alloted to this Milestone and only ${$ac}% is available for allocation to reach total 100%;`;
                }

                return alert($amsg);
            }

            if($cv) {
                console.log('Parsed Values: ', $cv, $tv);
                $ccva = ($cv * $tv) / 100;
                // $ccva = $cv > $ccva ? $cv - $ccva : alert('Invalid Percentage Value');

                $ccva = $tv && $ccva ? $ccva : 0;
                $diff = Math.abs($ccva - ($aa + $mab));

                if($ccva > ($aa + $mab) && $diff > 1) {
                    return alert(`The Budget cannot be more than ${currencyFormatter.format($aa + $mab)} as ${currencyFormatter.format($ab - $mab)} of total contract value (${currencyFormatter.format($cv)}) is already allocated!`);
                }

                $bvi.val($ccva);
            }
        });

        $mt.on('change', function(e) {
            let $msNav  = $('#nav-ms-tab');
            let $boqNav = $('#nav-boq-tab');

            if($(this).val().length && Number($(this).val())) {
                // if(Number($(this).val())) {
                    // $mpuf.find('.i_w').addClass('d-none');
                    // $mpuf.find('.boq').removeClass('d-none');

                    $boqNav.removeClass('d-none');
                    $boqNav.trigger('click');
                // } else {
                    // $mpuf.find('.i_w').removeClass('d-none');
                    // $mpuf.find('.boq').addClass('d-none');
                    // }
            } else {
                $msNav.trigger('click');
                $boqNav.addClass('d-none');
                // $mpuf.find('.i_w, .boq').addClass('d-none');
            }
        });

        $mt.trigger('change');

        $('.currency').each(function(i, el) {
            let $el = $(el);

            if(Number($el.text())) {
                $el.text(currencyFormatter.format($el.text()))
            }
        });

        $('.btn-ems').on('click', function(e) {
            let data = $(this).data('info');

            let ppb = $('.modal.ums_pub');
            let tel = $('.modal.ums_pub .ard-msu');

            if(ppb.length) {
                ppb.find('input[name="milestone_id"]').val(data.id);
                ppb.find('input[name="name"]').val(data.name);
                ppb.find('input[name="budget"]').val(data.budget);
                ppb.find('input[name="budget"]').attr('_csv', data.budget);
                ppb.find('input[name="percent_of_work"]').val(data.percent_of_work);
                ppb.find('input[name="percent_of_work"]').attr('_csv', data.percent_of_work);
                ppb.find('input[name="start_date"]').val(data.start_date);
                ppb.find('input[name="end_date"]').val(data.end_date);
                ppb.find('input[name="amended_start_date"]').val(data.amended_start_date);
                ppb.find('input[name="amended_end_date"]').val(data.amended_end_date);
            }

            if(data.document && data.document.length) {
                data.document.forEach(function(item, indx) {
                    addReqDoc(tel, item.id, item.name);
                })
            }

            console.log(data);
        });


        $('.modal.ums_pub .btn-ard').on('click', function(e) {
            e.preventDefault();

            let tel = $('.modal.ums_pub .ard-msu');

            addReqDoc(tel);
        })

        $('.modal.ums_pub .ard-msu').on('click', '.btn-rrd', function(e) {
            $(this).closest('.form-group').remove();
        });

        $('.btn-cmspup').on('click', function() {
            let iiw = Number({{ $data->contract->ms_type }})

            if(iiw && $('.modal.cms_pub .tab-content .alert-primary').length) {
                $('#nav-ms-tab').trigger('click');
            }
        });

        function addReqDoc(el, id, val) {
            id  = typeof id !== 'undefined' ? id : 0;
            val = typeof val !== 'undefined' ? val : 0;

            let html  = '<div class="form-group row">';
                html += '<label class="control-label col-md-3 col-sm-3 ">Document Name</label>';
                html += '<div class="col-md-7">';
                html += '<input type="text" class="form-control"' + (val ? (' value="' + val + '"') : '') + ` name="documents[${id}][]" placeholder="Document Name..">`;
                html += '<p class="error" id="error-documents"></p>';
                html += '</div>';
                html += '<div class="col-md-2">';
                if(!id && !val) {
                    html += '<button type="button" class="btn btn-danger btn-sm btn-rrd">Remove</button>';
                }
                html += '</div>';
                html += '</div>';

            el.append(html);
        }

        @if($is_msts && $data->contract->ms_type)
            $('.btn-vboqs').on('click', function(e) {
                e.preventDefault();

                fetchBOQData(Number({{ $data->contract->id }}));
                $(this).off('click');
                $(this).remove();
            });
        @endif
    </script>
@stop
