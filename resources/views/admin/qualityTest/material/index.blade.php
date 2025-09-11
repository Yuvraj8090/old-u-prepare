@extends('layouts.admin')

@section('content')
    <style>
        table.table td,
        table.table th {
            vertical-align: middle;
        }
    </style>
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            @include('admin.include.backButton')

            <h4>Test Details | Project : {{ $projectData->name }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    @if(request()->segment('3') == "1")
                        <li class="breadcrumb-item active">
                            <a href="#">Material Test</a>
                        </li>
                    @else
                        <li class="breadcrumb-item active">
                            <a href="#">{{  $projectData->subcategory }} Test</a>
                        </li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>

    <div class="row x_panel">
        @if(request()->segment('1') == "quality" || isset($view))
            <div class="col-md-12">
                @isset($view)
                    <a @class(['btn', 'btn-md', 'btn-danger'=> $type == 1, 'btn-primary'=> $type !== 1]) href="{{ route('mis.project.detail.quality', [$projectData->id, 'material']) }}">Material Test</a>
                    <a @class(['btn', 'btn-md', 'btn-danger'=> $type == '', 'btn-primary'=> $type !== '']) href="{{ route('mis.project.detail.quality', [$projectData->id, 'environement']) }}">Environment Test</a>
                    <a @class(['btn', 'btn-md', 'btn-danger'=> $type == 2, 'btn-primary'=> $type !== 2]) href="{{ route('mis.project.detail.quality', [$projectData->id, 'bridge']) }}">{{ $projectData->subcategory }} Test</a>
                @else
                    <a class="btn btn-md @if(request()->segment('2') == 'update' && request()->segment('3') == '1') btn-danger @else btn-primary @endif " href="{{ url('quality/update/1/' . $id) }}">Material Test</a>
                    <a class="btn btn-md btn-primary " href="{{ url('/quality/environement/index/'.$id) }}">Environment Test</a>
                    <a class="btn btn-md @if(request()->segment('2') == 'update' && request()->segment('3') == '2') btn-danger @else btn-primary @endif " href="{{ url('quality/update/2/'.$id) }}">{{ $projectData->subcategory }} Test</a>
                @endif
            </div>

            <br>
        @endif

        <div class="col-md-12">
            <div class="col-md-12">
            @if(request()->segment('1') == "quality")
                <a class="btn btn-md btn-danger pull-right" href="#" data-toggle="modal" data-target="#exampleModalCenter">+Add Test Report</a>
            @endif
        </div>
            <div class="x_content">
                <table class="table table-striped projects table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 1%">#</th>
                            <th>Test Name</th>
                            <th>No. of Test</th>
                            <th>Passed</th>
                            <th>Failed</th>
                            <th>Status (% Passed)</th>
                            <th>Duration</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($data) > 0)
                            @foreach($data as $key => $d)
                                <tr>
                                    <td>{{ ++$key }}.</td>
                                    <th class="text-center">{{ $d->name }}</th>
                                    <td class="text-center">{{ $d->total_reports }}</td>
                                    <td class="text-center">{{ $d->passed_reports }}</td>
                                    <td class="text-center">{{ $d->failed_reports }}</td>
                                    <th class="text-center">{{ $d->status_percentage }}%</th>
                                    <th class="text-center">
                                        {{ $d->duration ?? 'X' }} Days
                                        <br>
                                        Start Date: {{ $d->start_date ? date("d-m-y", strtotime($d->start_date)) : 'N/A' }}
                                        <br>
                                        End Date: {{ $d->end_date ? date("d-m-y", strtotime($d->end_date)) : 'N/A' }}
                                        <br>
                                    </th>
                                    <td class="text-center">
                                        @if(request()->segment('1') != "quality")
                                            <a href="{{ url('/project/reports/index/' . $type . '/' . $d->id . '/' . $id) }}" class="btn btn-sm btn-primary">View Reports</a>
                                        @else
                                            <a href="{{ url('/quality/report/index/' . $type . '/' . $d->id . '/' . $id) }}" class="btn btn-sm btn-primary">Update</a>
                                        @endif

                                        @if($d->project_id  != 0 && !isset($view))
                                            <a href="{{ route('quality.delete', $d->id) }}" onClick="return confirm('Are you sure ? This action is not reversible.');" class="btn btn-sm btn-danger">Delete</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop


@section('modal')
    <!-- Modal -->
    <div class="modal bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Test:</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form autocomplete="off" data-method="POST" data-action="{{ route('quality.add') }}" id="ajax-form">
                    <div class="modal-body">
                        @csrf

                        <input type="hidden" name="project_id"  value="{{ $id }}" />
                        <input type="hidden" name="type" value="{{ $type }}" />
                        <input type="hidden" name="category" value="{{ $category ?? '0' }}" />

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Test Name</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" placeholder="Enter Name" name="name" />
                                <p class="error-project" id="error-name"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!--
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        -->
                        <button id="submit-btn" type="submit" class="btn btn-success btn-sm">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop




