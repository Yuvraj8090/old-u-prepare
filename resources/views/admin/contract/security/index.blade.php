@extends('layouts.admin')

@section('header_styles')
    <style>
        table th,
        table td {
            vertical-align: middle !important;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            <h4>Contracts Securities</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Contracts Securities</a></li>
                </ol>
            </nav>
        </div>
    </div>

    @include('admin.error')


    <div class="x_panel">
        <div class="row">
            <div class="col-md-12">
                <div class="x_title d-flex align-items-center justify-content-between">
                    <h2 style="white-space: collapse;">Contract Securities for <i>{{ $contract->project->name }}</i></h2>

                    <div style="width: 558px;">
                        <a class="btn btn-primary btn-sm" href="#" type="button" data-toggle="modal" data-target="#exampleModalCenter">Add Contract Security</a>
                        <a class="btn btn-info btn-sm" href="{{ route('contract.security.types') }}" type="button">Manage Securities</a>
                    </div>

                    {{-- <div class="clearfix"></div> --}}
                </div>

                <div class="x_content">
                    <table class="table table-striped projects table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 15%">Project Name</th>
                                <th style="width: 15%">Security Detail</th>
                                <th style="width: 10%;">Security No.</th>
                                <th>Start Date - End Date</th>
                                <th>Issuing Authority</th>
                                <th>Amount </th>
                                <!-- <th>Status</th> -->
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>

                        <tbody class="text-center">
                            @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{ $data->firstItem() + $key }}</td>
                                    <td>
                                        {{ $d->contract->project->name ?? '' }}
                                        <br>
                                        <span class="badge text-white bg-success">Project Id: {{ $d->contract->project->number  ?? '--' }}</span>
                                    </td>
                                    {{-- <td>{{ $d->name }}</td> --}}
                                    <td>
                                        <small>Security: <i>{{ $d->security_type ? $d->security_type->name : 'N/A' }}</i></small>
                                        <br>
                                        <small>Form of Security: <i>{{ $d->security_form ? $d->security_form->name : 'N/A' }}</i></small>
                                    </td>
                                    <td>{{ $d->security_number }}</td>
                                    <td>{{ $d->start_date }} - {{ $d->end_security_date }}</td>
                                    <td>{{ $d->issuing_authority }}</td>
                                    <td>{{ $d->amount }}</td>

                                    <td>
                                        <a href="{{ url('contract-security/edit/'.$d->id) }}" class="btn btn-info btn-xs">
                                            <i class="fa fa-pencil"></i>&nbsp;&nbsp; Edit Security
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">
                                        <center> NO DATA FOUND </center>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    <!-- Modal -->
    <div class="modal  bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add Security: {{ $contract->project->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form autocomplete="off" data-method="POST" data-action="{{ url('contract-security/store/' . $id) }}" class="form-horizontal form-label-left ajax-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Security Name</label>

                            <div class="col-md-9 col-sm-9 ">
                                <!-- <select class="form-control" name="name" required>
                                    <option value="">SELECT</option>
                                    <option value="Bank Securities">Bank Securities</option>
                                </select> -->
                                @if($securityTypes->count())
                                    <select name="security_type_id" class="form-control">
                                        <option value="">SELECT</option>
                                        @foreach ($securityTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" value="No Type of Security record found. Kindly add some..." disabled>
                                @endif

                                {{-- <input type="text" class="form-control" name="name" /> --}}
                                <p class="error-project" id="error-name"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Form of Security</label>

                            <div class="col-md-9 col-sm-9">
                                @if($securityForms->count())
                                    <select name="security_form_id" class="form-control">
                                        <option value="">SELECT</option>
                                        @foreach ($securityForms as $form)
                                            <option value="{{ $form->id }}">{{ $form->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control" value="No Form of Security record found. Kindly add some..." disabled>
                                @endif
                                {{-- <input type="text" class="form-control" name="form_of_security" /> --}}
                                <p class="error-project" id="error-form_of_security"></p>
                            </div>
                        </div>

                        {{--
                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Example of Security (Optional)</label>
                            <div class="col-md-9 col-sm-9">
                                <input type="text" class="form-control"  name="example_of_security" />
                                <p class="error-project" id="error-example_of_security"></p>
                            </div>
                        </div>
                        --}}

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Issue Start Date</label>

                            <div class="col-md-9 col-sm-9 ">
                                <input type="date" class="form-control" name="start_date" />
                                <p class="error-project" id="error-start_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Issue End Date</label>

                            <div class="col-md-9 col-sm-9">
                                <input type="date" class="form-control airpicker" name="end_date" />
                                <p class="error-project" id="error-end_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Security Number</label>

                            <div class="col-md-9 col-sm-9">
                                <input type="text" class="form-control" name="security_number" />
                                <p class="error-project" id="error-security_number"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Bank Name</label>

                            <div class="col-md-9 col-sm-9">
                                <input type="text" class="form-control" name="issuing_authority" />
                                <p class="error-project" id="error-issuing_authority"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Value</label>

                            <div class="col-md-9 col-sm-9 ">
                                <input type="number" class="form-control" name="amount" />
                                <p class="error-project" id="error-amount"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3">Upload Security Documents</label>

                            <div class="col-md-9 col-sm-9">
                                <input type="file" class="form-control" name="files[]" multiple />
                                <p class="error-doxument" id="error-files"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> --}}
                        <button id="submit-btn" type="submit" class="btn btn-success btn-sm">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Add Security
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

