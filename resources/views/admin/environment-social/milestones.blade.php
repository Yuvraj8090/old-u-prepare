@extends('layouts.admin')

@section('content')
<style>
.ck-editor__editable {
    height: 300px; /* Your desired height */
}
#editor2 {
    height: 300px; /* Your desired height */
}
</style>

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
          <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>Define Project || Project Name : {{ $data->name ?? '' }} </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('/procurement') }}">Define Project </a></li>
            </ol>
        </nav>
    </div>
</div>


    <div class="clearfix"></div>
    <div class="row">

    <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <a class="pull-right btn btn-sm btn-success" type="button" data-toggle="modal" data-target="#exampleModalLong" href="" > Create MileStones</a>
                    <h5 style="font-weight:550;">PROJECT MILESTONE</h5>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">

                                <div id="datatable-buttons_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap no-footer">
                                    <table class="table table-striped table-bordered dataTable no-footer dtr-inline" style="width: 100%;" role="grid" aria-describedby="datatable-buttons_info">
                                        <thead>
                                            <tr role="row">
                                                <th   style="width: 7px;" >S.No #</th>
                                                <th   style="width: 150px;" >Milestone</th>
                                                <th   style="width: 65px;"> Total work ( In % )</th>
                                                <th   style="width: 70px;">Start date </th>
                                                <th   style="width: 70px;">End date </th>
                                                <th   style="width: 72px;">Start date  ( amended)</th>
                                                <th   style="width: 71px;">End date  ( amended )</th>
                                                <th   style="width: 64px;"> Progress</th>
                                                <th   style="width: 60px;">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @if(count($milestone) > 0 )
                                            @foreach($milestone as $key => $datum)
                                            <tr role="row" class="odd">
                                                <th tabindex="0" class="sorting_1">MileStone {{ ++$key }}</th>
                                                <th>{{ $datum->name }}</th>
                                                <td>{{ $datum->percent_of_work}}%</td>
                                                <td>{{ date('d-m-Y',strtotime($datum->start_date))}}</td>
                                                <td>{{ date('d-m-Y',strtotime($datum->end_date)) }}</td>
                                                <td>{{ $datum->amended_start_date ? date('d-m-Y',strtotime($datum->amended_start_date)) : ''}}</td>
                                                <td>{{ $datum->amended_end_date ? date('d-m-Y',strtotime($datum->amended_end_date)) : ''}}</td>
                                             
                                                <td class="project_progress">
                                                <div class="progress progress_sm">
                                                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ $datum->physical_progress }}"></div>
                                                    </div><small>{{ $datum->physical_progress }}% Complete</small>
                                                </td>
                                                <td>
                                                    <!-- changes added on16 feb 2024 -->
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-info edit-button"  
                                                     data-url="{{ url('/environment-social/milestones/edit/'.$datum->id) }}"
                                                     data-edit="{{ url('/environment-social/milestones/update/'.$datum->id) }}" 
                                                     >
                                                        <i class="fa fa-pencil" ></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@stop


@section('modal')
<!-- CREATE MILESTONE -->
<div class="modal" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create MileStone : </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form autocomplete="off" data-method="POST" data-action="{{ url('project/milestones/social/environment/store/'.$id) }}" class="form-horizontal form-label-left ajax-form">
            @csrf

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">MileStones Name </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="name" >
                    <p class="error" id="error-name"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Percent of Work <br>(In %)</label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" name="percent_of_work" min="1"  max="100" placeholder="in %">
                    <p class="error" id="error-percent_of_work"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Start Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="start_date" >
                    <p class="error" id="error-start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">End Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="end_date" >
                    <p class="error" id="error-start_date"></p>
                </div>
            </div>

            <!-- <div class="form-group row">
                <div class="col-md-12">
                    <button type="button" class="btn btn-info btn-sm pull-right" id="AddButton" > + Add Document</button>
                </div>
            </div>

            <div id="AddInput"></div> -->

            <div class="ln_solid"></div>
        
      </div>
      <div class="modal-footer">
        <button id="submit-btn"  type="submit" class="btn btn-success">
            <span class="loader" id="loader" style="display: none;"></span> 
                Create
        </button>

        </form>
        <input type="hidden" id="input" value="1" />
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- UPDATE MILESTONE -->
<div class="editmodal modal" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit MileStone : </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form autocomplete="off" id="editform" data-method="POST"  data-action="" class="form-horizontal form-label-left ajax-form-edit">
            @csrf

            <input type="hidden" name="project_id" value="{{$id}}" />
            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">MileStones Name </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="name" data-key="name" placeholder="Name..">
                    <p class="error" id="editerror-name"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Percent of Work </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="number" class="form-control" name="percent_of_work"  min="1"  max="100"  data-key="percent_of_work"  placeholder="Percentage of Work..">
                    <p class="error" id="editerror-percent_of_work"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Start Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker"  data-key="start_date" placeholder="Start Date.." readonly>
                    <p class="error" id="editerror-start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">End Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" data-key="end_date" placeholder="End Date.." readonly>
                    <p class="error" id="editerror-end_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Amended Start Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="amended_start_date" data-key="amended_start_date" placeholder="Amended Start Date..">
                    <p class="error" id="editerror-start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Amended Start Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control datepicker" name="amended_end_date" data-key="amended_end_date" placeholder="Amended End Date..">
                    <p class="error" id="editerror-end_date"></p>
                </div>
            </div>

            <div class="ln_solid"></div>        
      </div>
      <div class="modal-footer">
        <button id="submit-btn"  type="submit" class="btn btn-success">
            <span class="loader" id="loader" style="display: none;"></span> 
                Update 
        </button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('script')



@stop