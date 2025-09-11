@extends('layouts.admin')

@section('content')

<!-- code added 7 Feb 2024 -->
<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
         <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>Project Milestone Photos </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#"> Photos of Project Milestone </a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addSiteImages">
             + Add
        </button>
        <h5 style="font-weight:550;">{{ $milestone->name }} photos</h5>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">


        <table id="datatable" class="table table-striped table-bordered" style="width:100%;text-align:center;">

            <thead>
                <tr>
                    <th style="width: 8%">S. No #</th>
                    <th style="width: 20%">Image</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center">Updated At</th>
                    @if(auth()->user()->role->level == "THREE")
                    <th style="width:20%;">Action</th>
                    @endif
                </tr>
            </thead>
            @if(count($data) > 0)
            @foreach($data as $k => $d)
            <tr>
                <th>{{ ++$k }}.</th>
                <th>
                    <img src="{{ asset('images/milestone/photos/'.$d->name) }}" style="width:200px" />
                </th>
                <td class="text-center">{{ date('d M, Y H:i A',strtotime($d->created_at)) }}</td>
                <td class="text-center">{{ date('d M, Y H:i A',strtotime($d->updated_at)) }}</td>
                @if(auth()->user()->role->level == "THREE")
                    <td>
                        <a class="btn btn-md btn-info media_id" data-id="{{ $d->id }}" data-toggle="modal" data-target="#UpdateSiteImage" href="javascript:void(0)">
                            <i class="fa fa-pencil"></i> Update Image</a>
                        <a class="btn btn-md btn-danger" onClick="return confirm('Are you sure? This action not reversiable.')" href="{{ url('es/milestone/images/destroy/'.$d->id) }}">  <i class="fa fa-trash"></i>  Delete</a>
                    </td>
                @endif
            </tr>
            @endforeach
            @endif
            <tbody>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</div>


<!-- New code 8 feb 2024 -->
<form autocomplete="off" data-method="POST" data-action="{{ url('es/milestone/images/store') }}" class="form-horizontal form-label-left ajax-form">
    @csrf

    <!-- Modal -->
    <div class="modal" id="addSiteImages" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalLongTitle">Add Milestone Photo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="project_id" value="{{ $projectId }}" />
                    <input type="hidden" name="id" value="{{ $id }}" />

                    <div class="form-group">
                        <label class="control-label">
                             Images (Note :- Only JPG,JPEG,PNG file allowed.)
                        </label>
                        <div class="">
                            <input type="file" class="form-control" name="images[]" required multiple />
                            <p class="error" id="error-images"></p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Upload Image </button>
                </div>
            </div>
        </div>
    </div>

</form>

<form autocomplete="off" data-method="POST" data-action="{{ url('es/milestone/images/update') }}" class="form-horizontal form-label-left ajax-form">
    @csrf

    <!-- Modal -->
    <div class="modal" id="UpdateSiteImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><b>Update Image</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" name="id" id="media_id" value="" />
                    <input type="hidden" name="milestone_id" value="{{ $id }}" />

                    <div class="form-group">
                        <label class="control-label">
                             Images (Note :- Only JPG,JPEG,PNG file allowed.)
                        </label>
                        <div class="">
                            <input type="file" class="form-control" name="image" required />
                            <p class="error" id="error-image"></p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Update Image</button>
                </div>
            </div>
        </div>
    </div>
</form>

@stop

<!-- New code 8 feb 2024 -->
@section('script')
<script>
    $(document).on('click', '.media_id', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        console.log(id);
      
        $('#media_id').val(id);
    });
</script>
@stop