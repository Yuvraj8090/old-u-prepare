@extends('layouts.admin')

@section('content')
    <style>
        #ui-datepicker-div{
            zoom:123%;
        }
        .error {
        	color: #ea0202;
        }
    </style>

    <div>
        <div class=" col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>Project Contract | Project : {{ $data->name ?? '' }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="{{ url('/contract') }}">Contract</a></li>
                    <li class="breadcrumb-item active"><a href="#">Create Project Contract</a></li>
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
                    <input type="text" class="form-control" value="{{ $data->name }}" readonly />
                    <p class="error-project" id="error-name"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Type</label>
                    <div>
                        <input type="text" class="form-control" value="{{ $data->project_type }}" readonly />
                        <p class="error-data" id="error-project_type"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Category</label>

                    <input type="text" class="form-control" value="{{ $data->category->name ?? '' }}" readonly>
                    <p class="error" id="error-project_category"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Number </label>
                    <input type="text" class="form-control" value="{{ $data->number }}" readonly />
                    <p class="error" id="error-number"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">District </label>

                    <input type="text" class="form-control" value="{{ $data->district->name }}"  readonly>
                    <p class="error" id="error-hpc_number"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Estimate Value </label>
                    <input type="text" class="form-control est_val" value="{{ $data->estimate_budget }}" readonly autocomplete="off">
                    <p class="error" id="error-approval_date"></p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Assign To </label>
                    <input type="text" class="form-control" value="{{ $data->department->department ?? '' }}" placeholder="Department Assigned.." readonly>
                    <p class="error" id="error-assign_to"></p>
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
                        <input type="text" class="form-control" value="{{ $data->dec_approval_letter_number }}" readonly>
                        <p class="error" id="error-dec_letter_number"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="control-label">DEC Approval Date</label>
                    <div>
                        <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data->dec_approval_date)) }}" readonly>
                        <p class="error" id="error-dec_approval_number"></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">HPC Approval Letter Number </label>
                        <div class="">
                            <input type="text" class="form-control" value="{{ $data->hpc_approval_letter_number }}" readonly>
                            <p class="error" id="error-hpc_approval_number"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">HPC Approval Date</label>
                        <div class="">
                            <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data->hpc_approval_date)) }}" readonly>
                            <p class="error" id="error-hpc_approval_date"></p>
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
                            <input type="text" class="form-control" value="{{ $data->approval_number }}" readonly="">
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label ">Project Approved By </label>
                        <div class="">
                            <input type="text" class="form-control" value="{{ $data->approved_by }}" readonly="">
                            <p class="error" id="error-approved_by"></p>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label ">Govt. Approval Date </label>
                        <div class="">
                            <input type="text" class="form-control airpicker hasDatepicker" value="{{ $data->approval_date }}" readonly="">
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
                        <label class="control-label">Method of procurement </label>
                        <div class="">
                            <input type="text" value="{{ $data->defineProject->method_of_procurement }}" class="form-control" disabled />
                        </div>
                    </div>
                </div>

                {{--
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label "> Assign To(PMU/PIU) </label>
                        <div class=" ">
                            <input type="text" value="{{ $data->procureThree->name ?? '' }}" class="form-control" disabled />
                        </div>
                    </div>
                </div>
                --}}

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Tender Fee </label>
                        <div class="">
                            <input type="text" class="form-control ten_fee" value="{{ $data->defineProject->bid_fee }}" disabled autocomplete="off" >
                            <p class="error" id="error-bid_fee"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Earnest Money Deposit  </label>
                        <div class="">
                            <input type="text" class="form-control en_mon" value="{{ $data->defineProject->earnest_money_deposit }}" disabled autocomplete="off" >
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Bid Validity (In Days)</label>
                        <div class=" ">
                            <input type="number" class="form-control"  value="{{$data->defineProject->bid_validity}}" disabled >
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Bid Completion Period  (In Days)</label>
                        <div class=" ">
                            <input type="number" class="form-control" value="{{$data->defineProject->bid_completion_days}}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('admin.procurement.procurement-show')

    <form autocomplete="off" data-method="POST" data-action="{{ url('contract/store/'.$id) }}" id="ajax-form" class="form-horizontal form-label-left">
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
                        <input type="text" class="form-control" name="contract_number" >
                        <p class="error" id="error-contract_number"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Contract Value</label>
                        <input type="number" class="form-control" name="procurement_contract" min="1" >
                        <p class="error" id="error-procurement_contract"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Security (<span id="bid-price" class="text-dark">₹0.00</span>)</label>
                        <input type="text" class="form-control " name="bid_Fee" >
                        <p class="error" id="error-bid_Fee"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Signing date</label>
                        <input type="date" class="form-control" name="contract_signing_date" value="" placeholder="Contract Siging date..">
                        <p class="error" id="error-contract_signing_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Commencement Date</label>
                        <input type="date" class="form-control" name="commencement_date" value="" placeholder="Contract Commencement date...">
                        <p class="error" id="error-commencement_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Initial Completion Date</label>
                        <input type="date" class="form-control" name="initial_completion_date" value="" placeholder="Initial Completion date...">
                        <p class="error" id="error-initial_completion_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Revised Completion Date</label>
                        <input type="date" class="form-control" name="actual_completion_date" value="" placeholder="Revised Completion date...">
                        <p class="error" id="error-actual_completion_date"></p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Actual Completion Date</label>
                        <input type="date" class="form-control" name="actual_completion_date" value="" placeholder="Actual Completion date...">
                        <p class="error" id="error-actual_completion_date"></p>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Upload Contract Document (Note: - Only pdf allowed)</label>
                        <input type="file" class="form-control" name="contract" >
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

                {{--
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Contract Agency </label>
                            <input type="text" class="form-control" name="contract_agency" placeholder="Contract Agency...">
                            <p class="error" id="error-contract_agency"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Contractor Company Name </label>
                            <input type="text" class="form-control" name="company_name" placeholder="Contractor Company Name...">
                            <p class="error" id="error-company_name"></p>
                        </div>
                    </div>
                </div>
                --}}

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Contractor Company Name </label>
                            <input type="text" class="form-control" name="company_name">
                            <p class="error" id="error-company_name"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Authorized Personnel Name</label>
                            <input type="text" class="form-control" name="authorized_personel" >
                            <p class="error" id="error-authorized_personel"></p>
                        </div>
                    </div>

                    {{--
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Company Type of Registration </label>
                            <input type="text" class="form-control" name="registration_type" >
                            <p class="error" id="error-registration_type"></p>
                        </div>
                    </div>
                    --}}

                    {{--
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Company Registration Number</label>
                            <input type="text" class="form-control" name="company_resgistered_no" >
                            <p class="error" id="error-company_resgistered_no"></p>
                        </div>
                    </div>
                    --}}

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Phone No. </label>
                            <input type="text" class="form-control" name="contact" >
                            <p class="error" id="error-contact"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Email </label>
                            <input type="email" class="form-control" name="email" >
                            <p class="error" id="error-email"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">GST No. of Contractor</label>
                        <input type="text" class="form-control" name="gst_no" value="{{ $data->gst_no }}" >
                        <p class="error" id="error-gst_no"></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Contractor address (Contractor / Vendor / Firms) </label>
                            <textarea class="form-control" rows="5" name="contractor_address"></textarea>
                            <p class="error" id="error-contractor_address"></p>
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <button id="submit-btn" type="submit" class="btn btn-lg btn-success">
                                <span class="loader" id="loader" style="display: none;"></span>
                                Create Contract
                            </button>
                            <a type="reset" class="ml-4 btn  btn-primary btn-lg pull-right" href="{{ url('contract/create/'.$id) }}">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('script')

    <script>
        $enMon  = $('.en_mon');
        $estVal = $('.est_val');
        $tenFee = $('.ten_fee');
        $estBin = $('input[name="bid_Fee"]');

        $estBin.on('keyup', function() {
            $('#bid-price').text(currencyFormatter.format($(this).val()));
        });

        $estBin.trigger('keyup');
        $enMon.val(currencyFormatter.format($enMon.val()));
        $estVal.val(currencyFormatter.format($estVal.val()));
        $tenFee.val(currencyFormatter.format($tenFee.val()));
    </script>
@endsection
