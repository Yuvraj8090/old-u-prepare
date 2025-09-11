@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>Project Contract Security</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ url('/contract') }}">Contract</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Edit Contract Security</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <h2 style="font-weight:700;">Edit Contract Security</h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <form id="ajax-form" autocomplete="off" data-method="POST" data-action="{{ url('contract-security/update/' . $data->id) }}" class="form-horizontal form-label-left">
                @csrf
                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3">Security Name</label>
                    <div class="col-md-9 col-sm-9">
                        @if($securityTypes->count())
                            <select name="security_type_id" class="form-control">
                                <option value="">SELECT</option>
                                @foreach ($securityTypes as $security)
                                    <option value="{{ $security->id }}" @selected($security->id == $data->security_id)>{{ $security->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control" value="No Type of Security record found. Kindly add some..." disabled>
                        @endif
                        {{-- <input type="text" class="form-control" value="{{ $data->name }}" name="name" /> --}}

                        <!-- <input type="text" class="form-control" placeholder="Enter Security Name" value="{{ $data->name }}" name="name" /> -->
                        <p class="error-project" id="error-name"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3 ">Form of Security</label>
                    <div class="col-md-9 col-sm-9">
                        @if($securityForms->count())
                        <select name="security_form_id" class="form-control">
                            <option value="">SELECT</option>
                            @foreach ($securityForms as $form)
                                <option value="{{ $form->id }}" @selected($form->id == $data->security_type_id)>{{ $form->name }}</option>
                            @endforeach
                        </select>
                        @else
                            <input type="text" class="form-control" value="No Form of Security record found. Kindly add some..." disabled>
                        @endif
                        {{-- <input type="text" class="form-control" value="{{ $data->form_of_security }}" name="form_of_security" /> --}}
                        <p class="error-project" id="error-form_of_security"></p>
                    </div>
                </div>

                {{--
                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3 ">Example of Security (Optional)</label>
                    <div class="col-md-9 col-sm-9">
                        <input type="text" class="form-control" value="{{ $data->example_of_security }}" name="example_of_security" />
                        <p class="error-project" id="error-example_of_security"></p>
                    </div>
                </div>
                --}}

                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3 ">Issue Start Date </label>

                    <div class="col-md-9 col-sm-9">
                        <input type="date" class="form-control airpicker" placeholder="Enter Starting Date" value="{{ $data->start_date }}" name="start_date" />
                        <p class="error-project" id="error-start_date"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3">Issue End Date</label>
                    <div class="col-md-9 col-sm-9">
                        <input type="date" class="form-control" placeholder="Enter End Date" value="{{ $data->end_security_date }}" name="end_date" />
                        <p class="error-project" id="error-end_date"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3">Security Number</label>
                    <div class="col-md-9 col-sm-9 ">
                        <input type="text" class="form-control" placeholder="Enter Number" value="{{ $data->security_number }}" name="security_number" />
                        <p class="error-project" id="error-security_number"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3">Issuing Authority </label>

                    <div class="col-md-9 col-sm-9">
                        <input type="text" class="form-control" placeholder="Enter Issuing Authority" value="{{ $data->issuing_authority }}" name="issuing_authority" />
                        <p class="error-project" id="error-issuing_authority"></p>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="control-label col-md-3 col-sm-3">Amount</label>
                    <div class="col-md-9 col-sm-9">
                        <input type="number" class="form-control" placeholder="Enter Security Amount"  value="{{ $data->amount }}" name="amount" />
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

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-12 col-sm-12 offset-md-3">
                        <button id="submit-btn" type="submit" class="btn btn-success">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Update Contract Security
                        </button>
                        <a type="reset" class="ml-4 btn btn-primary" href="{{ url('contract-security/edit/' . $data->id) }}">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <h2 style="font-weight:700;">Uploaded Security Documents</h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="col-md-12">
                <table class="table" style="width:100%;">
                    <tr>
                        <th>S.NO</th>
                        <th>Name</th>
                        <th>Action</th>
                        <th>Delete</th>
                    </tr>

                    @if(count($medias) > 0)
                        @foreach($medias as $key => $m)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $m->name }}</td>
                                <td>
                                    <a target="_blank" href="{{ asset('images/security/' . $m->name) }}" class="btn btn-sm btn-success">View</a>
                                    <a href="{{ asset('images/security/' . $m->name) }}" class="btn btn-sm btn-info" download >Download Doc</a>
                                </td>
                                <td>
                                    <a onClick="return confirm('Are you sure? This action is not revertable.')" href="{{ route('contract.security.delete',$m->id) }}" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@stop
