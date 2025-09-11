@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
   @include('admin.include.backButton')

    <h4>
    @if($moduleType == 1)
        ENVIRONMENT SAFEGUARD 
    @elseif($moduleType == 2)
        SOCIAL SAFEGUARD 
    @else
        TEST DETAILS
    @endif
        |  
    @if($type  == 1 )
        Pre-Construction
    @elseif($type  == 2)
        Construction
    @elseif($type  == 3)
        Post-Construction
    @else
        N/A
    @endif 
         Activities 
    @if(isset($test->name))
           | {{$test->name}}
    @endif
    </h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">
               @if($type  == 1 )
                Pre
                @elseif($type  == 2)
                Construction
                @elseif($type  == 3)
                Post
                @else
                N/A
                @endif Construction Activities </a></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row x_panel">
    

        <div class="col-md-12">            
        </div>

    <div class="col-md-12">
        <div class="x_content">
            <h3>
                @if(isset($test->name))
                    {{$test->name}}
                @else
                    @if($type  == 1 )
                        Pre
                        @elseif($type  == 2)
                        Construction
                        @elseif($type  == 3)
                        Post
                        @else
                        N/A
                        @endif Construction Activities
                @endif 
            </h3>
                
                @if(isset($parent->excel))
                    <a href="{{ url('excel/'.$parent->excel) }}" class="btn btn-primary btn-md pull-right" download>Download Excel Report Document</a>
                @endif
                
                <table class="table text-center table-striped projects table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 1%">#</th>
                            <th style="width:25%;" >Activity Name </th>
                            @if($not)
                                <th>No. of sub-activites</th>
                            @endif
                            <th style="width:7%;"> Complied (Yes/No/Not Applicable) </th>
                            <th style="width:10%;"> Planned Date  </th>
                            <th style="width:10%;"> Actual date  </th>
                            <th style="width:20%;" >Remarks</th>
                            <th style="width:7%;">Documents</th>
                            <th style="width:7%;">Photos</th>
                            <th style="width:10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data) > 0)
                            @foreach($data as $key => $d)
                                <tr>
                                    <td>{{ ++$key }}.</td>
                                    <th class="text-center">
                                        @if($d->have_child == 1)
                                            <a style="color:blue;" href="{{ url('tests/four/others/'.$d->type.'/'.$id.'/'.$d->id) }}">{{ $d->name ?? 'N/A' }}</a>   
                                        @else
                                            {{ $d->name ?? 'N/A' }}
                                        @endif
                                    </th>
                                    @if($not)
                                        <th>
                                            @if($d->have_child == 1)
                                                {{ $d->subtests_count }}
                                            @else
                                                1
                                            @endif
                                        </th>
                                    @endif
                                    <th class="text-center">
                                        @if($d->have_child == 1)
                                            -
                                        @elseif($d->reports)
                                            @if($d->reports->status == 1)
                                                Yes
                                            @elseif($d->reports->status == 2)
                                                No
                                            @elseif($d->reports->status == 3)
                                                Not Applicable
                                            @else
                                                N/A
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </th>
                                    <td>{{ $d->reports ? $d->reports->planned_date : 'N/A' }}</td>
                                    <td>{{ $d->reports ? $d->reports->actual_date : 'N/A' }}</td>
                                    <td>
                                        @if($d->have_child == 1)
                                            -
                                        @else
                                            {{ $d->reports->remark ?? "N/A" }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(isset($d->reports->document))
                                            <a download href="{{ $d->reports->document }}" class="btn btn-sm btn-primary">Document</a>    
                                        @elseif($d->have_child == 1)
                                            -
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($d->reports)
                                            <a href="{{ url('test/images/' . $id . '/' . $d->id) }}" class="btn btn-sm btn-danger" > Photos</a>   
                                        @elseif($d->have_child == 1)
                                            -
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($d->have_child == 0)
                                            @if($d->reports)
                                                <button href="#" class="btn btn-primary btn-sm openModal" data-id="{{ $d->id }}" data-name="{{ $d->name }}" >Update Report</button>
                                            @else
                                                <button href="#" class="btn btn-primary btn-sm openModal" data-id="{{ $d->id }}" data-name="{{ $d->name }}" >Add Report</button>
                                            @endif
                                        @else
                                            -
                                        @endif
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

<div class="modal bd-example-modal-lg" id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> <b> Submit Test Report :- </b> <b id="test" ></b> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" data-method="POST" data-action="{{ url('tests/storeRReport') }}" class="ajax-form"  >
                <div class="modal-body">
                    @csrf
                    
                    <input type="hidden" name="test_id" id="EditId" value="" />
                    <input type="hidden" name="type"  value="{{ $type }}" />
                    <input type="hidden" name="project_id"  value="{{ $id }}" />
                    
                     <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Complied Status </label>
                        <div class="col-md-9 col-sm-9 ">
                        <select name="status" class="form-control">
                            <option value="" >SELECT</option>
                            <option value="1" >Yes</option>
                            <option value="2" >No</option>
                            <option value="3" >Not Applicable</option>
                        </select>
                            <p class="error-project" id="error-status"></p>
                        </div>
                    </div>

                    {{--
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3">Planned Date</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control datepicker" name="planned_date" />
                            <p class="error-project" id="error-planned_date"></p>
                        </div>
                    </div>
                    --}}
                    
                     <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3">Actual Date </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control datepicker" name="actual_date" />
                            <p class="error-project" id="error-actual_date"></p>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3">Report Document (Only Excel & PDF file allowed.) </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="file" class="form-control" name="document" />
                            <p class="error-project" id="error-document"></p>
                        </div>
                    </div>
                    
                     <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Remark(Optional) </label>
                        <div class="col-md-9 col-sm-9 ">
                            <textarea class="form-control" name="remark" rows="5"></textarea>
                            <p class="error-project" id="error-remark"></p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
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

@section('script')
<script>
    $(document).on('click', '.openModal', function(event)
    {
        var id = $(this).data('id');
        var name = $(this).data('name');

        $('#EditId').val(id);
        $('#test').text(name);

        $('#openModal').modal('show');
    });
</script>
@stop
