@extends('layouts.admin')

@section('content')
    <div>
        <div class=" col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>Create Procurement | Project : {{ $data->name }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ url('/procurement') }}">Procurement</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Create Project Procurement</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="x_panel">
        <div class="x_title">
            <h5 style="font-weight:550;">PROJECT INITIALS</h5>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label">Project Name</label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $data->name }}" readonly />
                            <p class="error-data" id="error-name"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Project Type</label>
                        <div>
                            <input type="text" class="form-control" value="{{ $data->project_type }}" readonly />
                            <p class="error-data" id="error-name"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label">Project Category</label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $data->category->name }}" readonly />
                            <p class="error-project" id="error-name"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label ">Project Number </label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $data->number }}" readonly />
                            <p class="error" id="error-number"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label">Estimated Budget</label>
                        <div class=" ">
                            <input type="text" class="form-control est_val" value="{{ $data->estimate_budget }}" readonly autocomplete="off">
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">District</label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $data->district->name }}" readonly>
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group ">
                        <label class="control-label ">Assigned To</label>
                        <div class=" ">
                            <input type="text" class="form-control" value="{{ $data->department->department }}" readonly>
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
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
                            <p class="error" id="error-approval_number"></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">HPC Approval Date</label>
                        <div class="">
                            <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data->hpc_approval_date)) }}" readonly>
                            <p class="error" id="error-approval_date"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>

    <form id="ajax-form" autocomplete="off" data-method="POST" data-action="{{ url('procurement/store/' . $id) }}" class="form-horizontal form-label-left">
        @csrf

        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">PROCUREMENT</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Method of procurement</label>

                            <div class="">
                                <select class="form-control" name="method_of_procurement">
                                    <option value="">SELECT</option>
                                    @if(count($data->category->methods_of_procurement) > 0)
                                        @foreach($data->category->methods_of_procurement as $pre)
                                            <option value="{{$pre}}">{{ $pre  }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <p class="error" id="error-method_of_procurement"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Type of procurement</label>

                            <div class="">
                                <select class="form-control" name="type_of_procurement">
                                    <option value="">SELECT</option>
                                    @if($data->category_id == 1)
                                        @foreach(App\Helpers\Assistant::procureTypes() as $key => $ptype)
                                            <option value="{{ $key }}">{{ $ptype }}</option>
                                        @endforeach
                                    @else
                                        <option value="2">Others</option>
                                    @endif
                                </select>

                                <p class="error" id="error-type_of_procurement"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tender Fee (<span id="tf-price" class="text-dark">₹0.00</span>)</label>
                            <div class=" ">
                                <input type="number" class="form-control" min="0" name="bid_fee" >

                                <p class="error" id="error-bid_fee"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Earnest Money Deposit (<span id="em-price" class="text-dark">₹0.00</span>)</label>
                            <div class=" ">
                                <input type="number" class="form-control" min="0" name="earnest_money_deposit" >
                                <p class="error" id="error-earnest_money_deposit"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Bid Validity (In Days)</label>
                            <div class=" ">
                                <input type="number" class="form-control" min="0" name="bid_validity" >
                                <p class="error" id="error-bid_validity"></p>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label ">Bid Completion Period (In Days)</label>
                            <div class=" ">
                                <input type="number" class="form-control" min="0" name="bid_completion_days">
                                <p class="error" id="error-bid_completion_days"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-12 col-sm-12">
                                <button id="submit-btn" type="submit" class="btn btn-success">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Create Procurement
                                </button>
                                <a type="reset" class="ml-4 btn btn-primary pull-right" href="{{ url('/procurement/create/'.$id) }}">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('script')
    <script>
        $est_bud = $('.est_val');

        $est_bud.val(currencyFormatter.format($est_bud.val()));

        $tFeein = $('input[name="bid_fee"]');
        $emdin  = $('input[name="earnest_money_deposit"]');

        $tFeein.on('keyup', function() {
            $('#tf-price').text(currencyFormatter.format($(this).val()));
        });
        $emdin.on('keyup', function() {
            $('#em-price').text(currencyFormatter.format($(this).val()));
        });
    </script>
@endsection
