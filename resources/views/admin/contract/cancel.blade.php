@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>Cancel Project Contract</h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/contract') }}">Contract</a></li>
                <li class="breadcrumb-item active"><a href="#">Cancel Project Contract</a></li>
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
        <form autocomplete="off" data-method="POST" data-action="{{ url('contract/close/post/'.$data->id) }}" id="ajax-form" class="form-horizontal form-label-left">
            @csrf

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Project Name </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->project->name }}" readonly  />
                    <p class="error-project" id="error-name"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Project Number </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" value="{{ $data->project->number }}" readonly />
                    <p class="error" id="error-number"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Cancel Type </label>
                <div class="col-md-9 col-sm-9 ">
                    <select name="type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="CANCEL">Cancel Contract</option>
                        <option value="FOR-CLOSE">Forclose Contract</option>
                    </select>
                    <p class="error" id="error-type"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Reason </label>
                <div class="col-md-9 col-sm-9 ">
                    <textarea  class="form-control" rows="5" name="reason"></textarea>
                    <p class="error" id="error-reason"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Upload Documents</label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="file" class="form-control" name="documents[]" multiple />
                    <p class="error" id="error-documents"></p>
                </div>
            </div>

        <div class="ln_solid"></div>
           <div class="form-group">
                <div class="col-md-12 col-sm-12  offset-md-3">
                    <button id="submit-btn"  type="submit" class="btn btn-success">
                        <span class="loader" id="loader" style="display: none;"></span> 
                        Submit
                    </button>
                </div>
            </div> 
        </form>
        <br>

    </div>
  
</div>


@stop



