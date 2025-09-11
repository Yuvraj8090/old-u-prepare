@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
              
    @include('admin.include.backButton')
    <h4>Test Reports  | Test : {{ $test->name }}</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">Test Reports </a></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row x_panel">
    
<div class="col-md-12">

    <a class="btn btn-md btn-danger pull-right" href="#" data-toggle="modal" data-target="#exampleModalCenter">+Add Test Report</a>
</div>
<br>
  <div class="col-md-12">
        <div class="x_content">
      <table class="table table-striped projects table-bordered">
        <thead>
          <tr>
            <th style="width: 1%">#</th>
            <th>Report Name </th>
            <th> Status Document  </th>
            <th> Status  </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if(count($data) > 0)
            @foreach($data as $key => $d)
              <tr>
                <td>{{ ++$key }}.</td>
                <th class="text-center">{{ $d->name }}</th>
                
                <td class="text-center">
                    <a onClick="openPDF('{{ $d->document }}')" href="javascript:void(0)" class="btn btn-sm btn-primary">View Document</a>
                    <a href="{{ $d->document }}" class="btn btn-sm btn-danger" download>Download Document</a>
                </td>
                
                <th class="text-center">
                    @if($d->status == 1)
                      Passed
                    @else
                      Failed
                    @endif
                </th>
                
                <td class="text-center">
                    <a href="" class="btn btn-sm btn-primary"  >Update Report </a>
                    <a href="{{ route('quality.ReportDelete',$d->id) }}" onClick="return confirm('Are you sure ? This action is not reversible.');" class="btn btn-sm btn-danger"  >Delete </a>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Add Test Report: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" data-method="POST" data-action="{{ route('quality.storeReport') }}" id="ajax-form"  >
                <div class="modal-body">
                    @csrf

                    <input type="hidden" name="test_id"  value="{{ $id }}" />
                    <input type="hidden" name="type"  value="{{ $type }}" />
                    <input type="hidden" name="project_id"  value="{{ $projectId }}" />

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Test Name </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control"  name="name" />
                            <p class="error-project" id="error-name"></p>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Status </label>
                        <div class="col-md-9 col-sm-9 ">
                            <select  class="form-control"  name="status"  >
                                <option value="" >SELECT</option>
                                <option value="1" >Passed</option>
                                <option value="0" >Failed</option>
                            </select>
                            <p class="error-project" id="error-status"></p>
                        </div>
                    </div>
                    
                    <!--<div class="form-group row">-->
                    <!--    <label class="control-label col-md-3 col-sm-3 "> Start Date </label>-->
                    <!--    <div class="col-md-9 col-sm-9 ">-->
                    <!--        <input type="text" class="form-control datepicker" placeholder="" name="start_date" />-->
                    <!--        <p class="error-project" id="error-start_date"></p>-->
                    <!--    </div>-->
                    <!--</div>-->
                    
                    
                    <!-- <div class="form-group row">-->
                    <!--    <label class="control-label col-md-3 col-sm-3 "> End Date </label>-->
                    <!--    <div class="col-md-9 col-sm-9 ">-->
                    <!--        <input type="text" class="form-control datepicker" placeholder="" name="end_date" />-->
                    <!--        <p class="error-project" id="error-end_date"></p>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Document </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="file" class="form-control"  name="document" />
                            <p class="error-project" id="error-document"></p>
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 "> Report Date </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control datepicker" placeholder="" name="date" />
                            <p class="error-project" id="error-date"></p>
                        </div>
                    </div>
                    
                    
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 "> Remark (Optional) </label>
                        <div class="col-md-9 col-sm-9 ">
                            <textarea name="remark" class="form-control" row="5" ></textarea>
                            <p class="error-project" id="error-remark"></p>
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
@stop



