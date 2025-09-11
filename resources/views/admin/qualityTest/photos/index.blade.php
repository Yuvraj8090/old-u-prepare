@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
              
    @include('admin.include.backButton')

    <h4>@if($moduleType == 1)
      ENVIRONMENT SAFEGUARD ACTIVITIES PHOTOS
    @elseif($moduleType == 2)
      SOCIAL SAFEGUARD ACTIVITIES PHOTOS
    @else
        TEST DETAILS
    @endif </h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">@if($moduleType == 1)
      ENVIRONMENT SAFEGUARD ACTIVITIES PHOTOS
    @elseif($moduleType == 2)
      SOCIAL SAFEGUARD ACTIVITIES PHOTOS
    @else
        TEST DETAILS
    @endif</a></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row x_panel">
    
<div class="col-md-12">
    <div class="x_content">
        
      <h3  class=""> Test : {{ $test->name }} photos</h3>
      @if(request()->segment('1') == "test")
            <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#AddOpenModal" >+ Add Photos</button>
      @endif
      <table class="table text-center table-striped projects table-bordered">
        <thead>
          <tr>
            <th>S.No</th>
            <th style="width:40%;">Image</th>
            <th> Created  At</th>
            <th> Updated At</th>
            @if(request()->segment('1') == "test")
                <th> Action </th>
            @endif
       
          </tr>
        </thead>
        <tbody>
            @if(count($data) > 0)
            @foreach($data as $k=>$d)
                <tr >
                    <th>{{ $data->firstItem() + $k }}.</th>
                    <td>
                        <img src="{{$d->name}}" heighht="300" width="100%"  />
                    </td>
                    <td>
                        {{ $d->created_at }}
                    </td>
                    <td>
                        {{ $d->updated_at }}
                    </td>
                                @if(request()->segment('1') == "test")

                    <td>
                        <button class="btn btn-sm btn-primary UpdateModal"  data-info='@json($d)'  >Update</button>
                        <a class="btn btn-sm btn-danger" href="{{ url('test/image/delete/'.$d->id) }}" onClick="return confirm('Are you sure ? This action not reversible.');" >Delete</a>
                    </td>
                    @endif
                </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">No data Found</td>
            </tr>
            @endif
        </tbody>
        {{ $data->links() }}
      </table>
  </div>
  

</div>
</div>
@stop



@if(request()->segment('1') == "test")

@section('modal')

<div class="modal bd-example-modal-lg" id="AddOpenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> <b> Add Photos :- </b> <b id="test" >{{ $test->name }}</b> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" data-method="POST" data-action="{{ url('test/images/upload') }}" class="ajax-form"  >
                <div class="modal-body">
                    @csrf
                    
                    <input type="hidden" name="test_id" value="{{ $test->id }}" />
                    <input type="hidden" name="project_id"  value="{{ $projectId }}" />
                    
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Upload Image</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="file" class="form-control" name="image" />
                            <p class="error-project" id="error-image"></p>
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

<div class="modal bd-example-modal-lg" id="UpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> <b> Update Image :- </b> <b id="test" >{{ $test->name }}</b> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" data-method="POST" data-action="{{ url('test/images/update') }}" class="ajax-form"  >
                <div class="modal-body">
                    @csrf
                                        
                    <input type="hidden" name="id" id="testId" />
                    
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Upload Image</label>
                        <br>
                            <img src="" id="imageId" height="300px" width="100%"  style="margin-bottom:20px;"/>
                       
                        <div class="col-md-12">
                            <input type="file" class="form-control" name="images" />
                            <p class="error-project" id="error-images"></p>
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
    $(document).on('click', '.UpdateModal', function(event) {
        
        var info = $(this).data('info');
    
        $('#testId').val(info.id);
        $('#imageId').attr('src',info.name);
        $('#UpdateModal').modal('show');
        
    });
</script>
@stop

@endif




