@extends('layouts.admin')

@section('content')
    <style>
        #ui-datepicker-div{
            zoom:123%;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            <h4>Edit Contract || Project : {{ $data->project->name }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ url('/contract') }}">Contract</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Edit Project Contract</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="x_panel">
        <div class="x_title">
            <h5 style="font-weight:550;"> PROJECT INITIALS </h5>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Name </label>
                    <input type="text" class="form-control" value="{{ $data->project->name }}" readonly />
                    <p class="error-project" id="error-name"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Type</label>
                    <div>
                        <input type="text" class="form-control" value="{{ $data->project->project_type }}" readonly />
                        <p class="error-data" id="error-name"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Category</label>

                    <input type="text" class="form-control" value="{{ $data->project->category->name ?? '' }}" readonly>
                    <p class="error" id="error-hpc_date"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Number </label>
                    <input type="text" class="form-control" value="{{ $data->project->number }}" readonly />
                    <p class="error" id="error-number"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">District </label>

                    <input type="text" class="form-control" value="{{ $data->project->district->name }}" readonly />
                    <p class="error" id="error-hpc_number"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Estimate Value</label>
                    <input type="text" class="form-control est_val" value="{{ $data->project->estimate_budget }}" readonly />
                    <p class="error" id="error-approval_date"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Assign To </label>
                    <input type="text" class="form-control" value="{{ $data->project->department->department ?? '' }}" readonly />
                    <p class="error" id="error-method_of_procurement"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <h5 style="font-weight:550;">APPROVAL DETAILS</h5>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label">DEC Approval Letter Number</label>
                    <div>
                        <input type="text" class="form-control" value="{{ $data->project->dec_approval_letter_number }}" readonly>
                        <p class="error" id="error-dec_letter_number"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="control-label">DEC Approval Date</label>
                    <div>
                        <input type="text" class="form-control airpicker" value="{{ date('d-m-Y', strtotime($data->project->dec_approval_date)) }}" readonly>
                        <p class="error" id="error-dec_approval_number"></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">HPC Approval Letter Number </label>
                        <div class="">
                            <input type="text" class="form-control" value="{{ $data->project->hpc_approval_letter_number }}" readonly>
                            <p class="error" id="error-approval_number"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">HPC Approval Date</label>
                        <div class="">
                            <input type="text" class="form-control airpicker" value="{{ date('d-m-Y', strtotime($data->project->hpc_approval_date)) }}" readonly>
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>
            </div>


            {{--
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label ">Project Approval Number </label>
                        <div class="">
                            <input type="text" class="form-control" value="{{ $data->project->approval_number }}" readonly >
                            <p class="error" id="error-approval_number"></p>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label ">Project Approved By </label>
                        <div class="">
                            <input type="text" class="form-control" value="{{ $data->project->approved_by }}"  readonly >
                            <p class="error" id="error-approved_by"></p>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label "> Approval Date </label>
                        <div class="">
                            <input type="text" class="form-control airpicker" value="{{ date('d-m-Y',strtotime($data->project->approval_date)) }}" readonly >
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>
            </div>
            --}}
        </div>
    </div>

    <div class="x_panel">
        <div class="x_title">
            <h5 style="font-weight:550;">PROCUREMENT</h5>

            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Method of procurement</label>
                        <div class="">
                            <input type="text" value="{{ $data->project->defineProject->method_of_procurement ?? '' }}" class="form-control" disabled />
                        </div>
                    </div>
                </div>

                {{--
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label">Assign To(PMU/PIU)</label>
                        <div class=" ">
                            <input type="text" value="{{ $data->project->procureThree->name ?? '' }}" class="form-control" disabled />
                        </div>
                    </div>
                </div>
                --}}

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Tender Fee</label>
                        <div class="">
                            <input type="text" class="form-control ten_fee" value="{{ $data->project->defineProject->bid_fee ?? '' }}" disabled autocomplete="off">
                            <p class="error" id="error-bid_fee"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Earnest Money Deposit  </label>
                        <div class="">
                            <input type="text" class="form-control en_mon" value="{{ $data->project->defineProject->earnest_money_deposit ?? '' }}" disabled autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Bid Validity (In Days)</label>
                        <div class=" ">
                            <input type="number" class="form-control"  value="{{$data->project->defineProject->bid_validity ?? ''}}" disabled >
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Validity of EMD (In Days)</label>
                        <div class=" ">
                            <input type="number" class="form-control" value="{{$data->project->defineProject->bid_completion_days ??  ''}}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.procurement.procurement-show')

    <form autocomplete="off" data-method="POST" data-action="{{ url('contract/update/'.$data->id) }}" id="ajax-form" class="form-horizontal form-label-left">
        @csrf

        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">CONTRACT DETAILS</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Contract Number</label>
                        <input type="text" class="form-control" name="contract_number" value="{{ $data->contract_number }}" placeholder="Contract Numbe...">
                        <p class="error" id="error-contract_number"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Contract Value <span id="con-val">(₹0.00)</span></label>
                        <input type="number" class="form-control" name="procurement_contract" value="{{ $data->procurement_contract }}" min="1" placeholder="Procurement Contract ..">
                        <p class="error" id="error-procurement_contract"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Security (<span id="bid-price" class="text-dark">₹0.00</span>)</label>
                        <input type="text" class="form-control" name="bid_Fee" value="{{ $data->bid_Fee }}" placeholder="Contract Bid Fee ..">
                        <p class="error" id="error-bid_Fee"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Signing date</label>
                        <input type="date" class="form-control" name="contract_signing_date" value="{{ date('Y-m-d', strtotime($data->contract_signing_date)) }}" placeholder="Contract Siging date..">
                        <p class="error" id="error-contract_signing_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Commencement Date</label>
                        <input type="date" class="form-control" name="commencement_date" value="{{ $data->commencement_date ? date('Y-m-d', strtotime($data->commencement_date)) : '' }}" placeholder="Contract Commencement date...">
                        <p class="error" id="error-commencement_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Initial Completion Date</label>
                        <input type="date" class="form-control" name="initial_completion_date" value="{{ $data->initial_completion_date ? date('Y-m-d', strtotime($data->initial_completion_date)) : '' }}" placeholder="Initial Completion date...">
                        <p class="error" id="error-initial_completion_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Revised Completion Date</label>
                        <input type="date" class="form-control" name="actual_completion_date" value="{{ date('Y-m-d', strtotime($data->revised_completion_date ?: ($data->actual_completion_date ?? $data->initial_completion_date))) }}" placeholder="Revised Completion date...">
                        <p class="error" id="error-actual_completion_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Actual Completion Date</label>
                        <input type="date" class="form-control" name="actual_completion_date" value="{{ date('Y-m-d', strtotime($data->actual_completion_date)) }}" placeholder="Actual Completion date...">
                        <p class="error" id="error-actual_completion_date"></p>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label ">Update Contract (Note: - Only pdf allowed)</label>
                        <br>
                        <a target="_blank" class="btn btn-success btn-md" href="{{ $data->contract_doc }}"> <i class="fa fa-file" ></i> &nbsp; View Contract</a>
                        <a class="btn btn-danger btn-md" href="{{ $data->contract_doc }}" download>  <i class="fa fa-file" ></i> &nbsp; Download Contract </a>
                        <input type="file" class="form-control mt-2" name="contract" >
                        <p class="error" id="error-contract"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">CONTRACTOR DETAILS</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Contractor Company Name</label>
                            <input type="text" class="form-control" name="company_name" value="{{ $data->company_name }}">
                            <p class="error" id="error-company_name"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Authorized Personnel Name</label>
                            <input type="text" class="form-control" name="authorized_personel" value="{{ $data->authorized_personel }}" >
                            <p class="error" id="error-authorized_personel"></p>
                        </div>
                    </div>

                    {{--
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Company Type of Registration  </label>
                            <input type="text" class="form-control" name="registration_type" value="{{ $data->registration_type  }}" >
                            <p class="error" id="error-registration_type"></p>
                        </div>
                    </div>
                    --}}

                    {{--
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label"> Company Registration Number </label>
                            <input type="text" class="form-control" name="company_resgistered_no" value="{{ $data->company_resgistered_no }}" >
                            <p class="error" id="error-company_resgistered_no"></p>
                        </div>
                    </div>
                    --}}

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Phone No.</label>
                            <input type="text" class="form-control" name="contact" value="{{ $data->contact }}" >
                            <p class="error" id="error-contact"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $data->email }}" >
                            <p class="error" id="error-email"></p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">GST No. of Contractor</label>
                            <input type="text" class="form-control" name="gst_no" value="{{ $data->gst_no }}" >
                            <p class="error" id="error-gst_no"></p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Contractor address (Contractor / Vendor / Firms)</label>
                            <textarea class="form-control" rows="5" name="contractor_address">{{ $data->contractor_address  }}</textarea>
                            <p class="error" id="error-contractor_address"></p>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">
                                <button id="submit-btn" type="submit" class="btn btn-success">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Update Contract
                                </button>
                                <a type="reset" class="ml-4 btn  btn-danger pull-right" href="{{ url('contract/edit/'.$id) }}">
                                    <i class="fa fa-refresh" ></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <br>
            </div>
        </div>
    </form>

    @if(false)
        <div class="x_panel">
            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <a class="btn btn-primary btn-sm float-right" href="#" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"> Add Security</a>
                            <h5 style="font-weight:550;">CONTRACT SECURITIES</h5>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <table class="table table-striped projects">
                                <thead>
                                    <tr>
                                        <th style="width: 1%">#</th>
                                        <th style="width: 15%"> Name</th>
                                        <th style="width: 10%"> Form of Security</th>
                                        <th style="width: 15%"> Exmaple of Security</th>
                                        <th style="width: 10%;">Security No.</th>
                                        <th>Start Date - End Date</th>
                                        <th>Issuing Authority</th>
                                        <th>Amount </th>
                                        <!-- <th>Status</th> -->
                                        <th style="width: 15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($contract) > 0)
                                    @foreach($contract as $key => $d)
                                    <tr>
                                        <td>{{ $contract->firstItem() + $key }}</td>

                                        <td>{{$d->name}}</td>
                                        <td>{{$d->form_of_security}}</td>
                                        <td>{{$d->example_of_security ?? 'NULL'}}</td>

                                        <td>{{$d->security_number }}</td>
                                        <td> {{$d->start_date}} - {{$d->end_security_date}}</td>
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
        </div>
    @endif
@stop


@section('modal')
    @if(false)
        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Security : </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form autocomplete="off" data-method="POST" data-action="{{ url('contract-security/store/'.$id) }}" class="form-horizontal form-label-left ajax-form" enctype="multipart/form-data">

                        <div class="modal-body">
                            @csrf

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Security Name </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <!-- <select class="form-control" name="name" required>
                                        <option value="">SELECT</option>
                                        <option value="Bank Securities">Bank Securities</option>
                                    </select> -->
                                    <input type="text" class="form-control"  name="name" />
                                    <p class="error-project" id="error-name"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Form of Security</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control" name="form_of_security" />
                                    <p class="error-project" id="error-form_of_security"></p>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Example of Security (Optional)</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control"  name="example_of_security" />
                                    <p class="error-project" id="error-example_of_security"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Issue Start Date </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control airpicker"  name="start_date" />
                                    <p class="error-project" id="error-start_date"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Issue End Date </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control airpicker"  name="end_date" />
                                    <p class="error-project" id="error-end_date"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Security Number </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control" name="security_number" />
                                    <p class="error-project" id="error-security_number"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Issuing Authority </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control"  name="issuing_authority" />
                                    <p class="error-project" id="error-issuing_authority"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Amount </label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="number" class="form-control" name="amount" />
                                    <p class="error-project" id="error-amount"></p>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="control-label col-md-3 col-sm-3 ">Upload Security Documents</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="file" class="form-control" name="files[]"  multiple  />
                                    <p class="error-doxument" id="error-files"></p>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                            <button id="submit-btn" type="submit" class="btn btn-success btn-sm">
                                <span class="loader" id="loader" style="display: none;"></span>
                                Add Security
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop

@section('script')
    <script>

        $enMon  = $('.en_mon');
        $tenFee = $('.ten_fee');
        $estBin = $('input[name="bid_Fee"]');
        $conVal = $('input[name="procurement_contract"]');

        $('.est_val').val(currencyFormatter.format($('.est_val').val()));

        $conVal.on('keyup', function() {
            $('#con-val').text(currencyFormatter.format($conVal.val()));
        });

        $estBin.on('keyup', function() {
            $('#bid-price').text(currencyFormatter.format($(this).val()));
        });

        $estBin.trigger('keyup');
        $conVal.trigger('keyup');

        $enMon.val(currencyFormatter.format($enMon.val()));
        $tenFee.val(currencyFormatter.format($tenFee.val()));
    </script>
@endsection
