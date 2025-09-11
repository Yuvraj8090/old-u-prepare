@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>Project Contract</h4>

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
        <h2 style="font-weight:700;"> Project Contract
        </h2>

        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <br>
        <form autocomplete="off" data-method="POST" data-action="{{ url('contract/store/'.$id) }}" id="ajax-form" class="form-horizontal form-label-left">
            @csrf

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Project Name </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->name }}" readonly  />
                    <p class="error-project" id="error-name"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Project Number </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->number }}" readonly />
                    <p class="error" id="error-number"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">HPC Approval Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->defineProject->hpc_date }}" placeholder="HPC approval date.." readonly>
                    <p class="error" id="error-hpc_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">HPC Approval Number </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->defineProject->hpc_number }}" placeholder="HPC approval number.." readonly>
                    <p class="error" id="error-hpc_number"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Method Of procurement </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->defineProject->method_of_procurement }}" placeholder="Method of procurement.." readonly>
                    <p class="error" id="error-method_of_procurement"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Contract Value </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->estimate_budget }}"  readonly>
                    <p class="error" id="error-approval_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Contract Siging date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="date" class="form-control" name="contract_signing_date" placeholder="Contract Siging date.." >
                    <p class="error" id="error-contract_signing_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Contract End date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="date" class="form-control" name="end_date" placeholder="Contract End date ..">
                    <p class="error" id="error-end_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Contract Bid Fee </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control " name="bid_Fee" placeholder="Contract Bid Fee ..">
                    <p class="error" id="error-bid_Fee"></p>
                </div>
            </div>


            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Contract Agency </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="contract_agency" placeholder="Earnest Contract Agency...">
                    <p class="error" id="error-contract_agency"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Authorized Personnel </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="authorized_personel" placeholder="Earnest Authorized Personnel..">
                    <p class="error" id="error-authorized_personel"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Contact</label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="contact" placeholder="Enter Contact...">
                    <p class="error" id="error-contact"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Email</label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="email" class="form-control" name="email" placeholder="Enter Email...">
                    <p class="error" id="error-email"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Contractor address</label>
                <div class="col-md-9 col-sm-9 ">
                    <textarea  class="form-control" rows="5" name="contractor_address"></textarea>
                    <p class="error" id="error-contractor_address"></p>
                </div>
            </div>


            <div class="ln_solid"></div>
           <div class="form-group">
                <div class="col-md-12 col-sm-12  offset-md-3">
                    <button id="submit-btn"  type="submit" class="btn btn-success">
                        <span class="loader" id="loader" style="display: none;"></span>
                        Submit
                    </button>
                    <a type="reset" class="ml-4 btn  btn-primary" href="{{ url('contract/create/'.$id) }}">Reset</a>
                </div>
            </div>
        </form>
        <br>

    </div>

</div>


@stop
