@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            @include('admin.include.backButton')

            <h4>Test Details | Test : @if($type == 1 ){{ __('Pre Construction Report') }}@elseif($type == 2){{ ('Construction  Report') }}@elseif($type == 3){{ ('Post Construction Report') }}@else{{ __('N/A') }}@endif</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">@if($type  == 1 ){{ __('Pre Construction Report') }}@elseif($type  == 2){{ __('Construction  Report') }}@elseif($type  == 3){{ __('Post Construction Report') }}@else{{ __('N/A') }}@endif</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row x_panel">
        @if(request()->segment('1') == "quality")
            <div class="col-md-12">
                <a class="btn btn-md btn-danger pull-right" href="#" data-toggle="modal" data-target="#exampleModalCenter">+Add Test</a>
            </div>
        @endif

        <div class="col-md-12">
            <div class="x_content">
                <h3>@if($type  == 1 ){{ __('Pre Construction Report') }}@elseif($type  == 2){{ __('Construction Report ') }}@elseif($type  == 3){{ __('Post Construction Report') }}@else{{ ('N/A') }}@endif</h3>
                <table class="table text-center table-striped projects table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 1%">#</th>
                            <th style="width:30%;">Test Name</th>
                            <td>Date</td>
                            <th>Document</th>
                            <th>Status</th>
                            <th style="width:25%;">Remark</th>
                            @if(request()->segment('1') == "quality")
                                <th style="width:15%;">Action</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @if(count($data) > 0)
                            @foreach($data as $key => $d)
                                <tr>
                                    <td>{{ ++$key }}.</td>
                                    <th class="text-center">{{ $d->name ?? 'N/A' }}</th>
                                    <td>{{ $d->reports ? date('d-m-Y', strtotime($d->reports->date)) : 'N/A' }}</td>
                                    <td class="text-center">
                                        @if(isset($d->reports->document))
                                            <a onClick="openPDF('{{ $d->reports->document }}')" href="javascript:void(0)" class="btn btn-sm btn-primary">View Document</a>
                                            <a href="{{ $d->reports->document }}" class="btn btn-sm btn-danger" download>Download Document</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <th class="text-center">
                                        @if($d->reports)
                                            @if($d->reports->status == 1)
                                                Passed
                                            @elseif($d->reports->status == 0)
                                                Failed
                                            @else
                                                N/A
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </th>
                                    <td>{{ $d->reports->remark ?? "N/A" }}</td>
                                    @if(request()->segment('1') == "quality")
                                        <td class="text-center">
                                            <a href="#" class="btn btn-sm btn-primary openModal" data-id="{{ $d->id }}" data-info='@json($d->reports)'>Update Report</a>
                                            @if($d->project_id  != 0)
                                                <a href="{{ route('quality.environmentDelete', $d->id) }}" onClick="return confirm('Are you sure ? This action is not reversible.');" class="btn btn-sm btn-danger">Delete</a>
                                            @endif
                                        </td>
                                    @endif
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
    <div class="modal bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Test: </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off" data-method="POST" data-action="{{ route('quality.environmentStore') }}" id="ajax-form">
                    <div class="modal-body">
                        @csrf

                        <input type="hidden" name="type" value="{{ $type }}" />
                        <input type="hidden" name="project_id" id="projectId" value="{{ $id }}" />

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Test Name</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" placeholder="Enter Name" name="name" />
                                <p class="error-project" id="error-name"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button id="submit-btn" type="submit" class="btn btn-success btn-sm">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal bd-example-modal-lg" id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Update Report: </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form autocomplete="off" data-method="POST" data-action="{{ route('quality.storeEnvironmentReport') }}" class="ajax-form update-test">
                    <div class="modal-body">
                        @csrf

                        <input type="hidden" name="type" value="{{ $type }}" />
                        <input type="hidden" name="test_id" id="testId" value="" />
                        <input type="hidden" name="project_id" id="projectId" value="{{ $id }}" />

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Report Date</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="date" class="form-control datepicker" placeholder="" name="date" />
                                <p class="error-project" id="error-date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Status</label>
                            <div class="col-md-9 col-sm-9">
                                <select class="form-control" name="status">
                                    <option value="">SELECT</option>
                                    <option value="1">Passed</option>
                                    <option value="0">Failed</option>
                                </select>
                                <p class="error-project" id="error-status"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Document</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="file" class="form-control" placeholder="" name="document" />
                                <p class="error-project" id="error-document"></p>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Remark (Optional)</label>
                            <div class="col-md-9 col-sm-9">
                                <textarea class="form-control" name="remark" rows="5"></textarea>
                                <p class="error-project" id="error-remark"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
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

@section('script')
    <script>
        $(document).on('click', '.openModal', function(event) {
            var data = $(this).data('info');
            var id = $(this).data('id');

            $('#testId').val(id);

            if(data) {
                $('form.update-test select[name="status"]').val(data.status);
                $('form.update-test input[name="date"]').val(data.date);
                $('form.update-test textarea[name="remark"]').text(data.remark);
            }

            $('#openModal').modal('show');
        });
    </script>
@stop
