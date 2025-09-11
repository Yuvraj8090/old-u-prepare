@extends('layouts.admin')

@section('content')

<div class="x_panel">
    <div class="x_title">
        <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h2 style="font-weight:700;">Update MileStones Progress: </h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <br>
        <form autocomplete="off" data-method="POST" data-action="{{ route('mile.stone.update',$data->id) }}" class="form-horizontal form-label-left ajax-form">
            @csrf

            <input type="hidden" name="project_id" value="{{$data->project_id}}" />
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">MileStones Name </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" placeholder="Name..">
                    <p class="error" id="error-name"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Budget </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" name="budget" value="{{ $data->budget }}" placeholder="Budget..">
                    <p class="error" id="error-budget"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Percent of Work </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" name="percent_of_work"  value="{{ $data->percent_of_work }}" placeholder="Percentage of Work..">
                    <p class="error" id="error-percent_of_work"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Start Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="start_date"  value="{{ date('d-m-Y',strtotime($data->start_date)) }}"  placeholder="Start Date..">
                    <p class="error" id="error-start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">End Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="end_date"  value="{{ date('d-m-Y',strtotime($data->end_date)) }}"  placeholder="End Date..">
                    <p class="error" id="error-start_date"></p>
                </div>
            </div>

            <!-- <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Amended Start Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="amended_start_date" placeholder="Amended End Date..">
                    <p class="error" id="error-amended_start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Amended End Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="amended_end_date" placeholder="Amended End Date..">
                    <p class="error" id="error-amended_end_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Financial Progress </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" name="financial_progress" placeholder="Financial Progress..">
                    <p class="error" id="error-financial_progress"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Physical Progress </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" name="physical_progress" placeholder="Physical Progress..">
                    <p class="error" id="error-physical_progress"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Accumulative</label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" name="accumulative" placeholder="Accumulative..">
                    <p class="error" id="error-accumulative"></p>
                </div>
            </div> -->

            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-9 col-sm-9  offset-md-3">
                    
                    <button id="submit-btn"  type="submit" class="btn btn-success">
                        <span class="loader" id="loader" style="display: none;"></span> 
                        Submit
                    </button>

                </div>
            </div>

        </form>
    </div>
</div>
@stop