@extends('layouts.admin')

@section('content')
<style>
    .custom-form-control {
        height: 40px;
        border-radius: 5px;
        margin-right: 5px;
        width: 410px;
        padding: 10px;
    }
</style>

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
          <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>Upload Templates</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#"> Upload Templates </a></li>
            </ol>
        </nav>
    </div>
</div>


<div class="x_panel">
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                       <a class="btn btn-md btn-danger pull-right" href="#" data-toggle="modal" data-target="#exampleModalCenter">+Add New </a> 
                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 1%">S.No </th>
                                <th style="width: 18%">Name</th>
                                <th style="width: 18%" > Excel File</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @if(count($data) > 0)
                            @foreach($data as $key => $d)
                            <tr class="text-center">
                                <th>{{ ++$key }}</th>
                                <th style="width:40%;">{{ $d->name }}</th>
                                <td><a class="btn btn-md btn-primary" download href="{{ $d->excel }}" >Excel file</a></td>
                                <td>{{ $d->created_at }}</td>
                                <th>
                                    <button class="btn btn-primary btn-md openModal" data-info='@json($d)' >Update</button>
                                    <a href="{{ url('template/delete/'.$d->id) }}" onClick="return confirm('Are you sure?')"  class="btn btn-danger btn-md" >Delete</a>
                                </th>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8">
                                    <center> NO DATA FOUND </center>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                        {{ $data->links() }}
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
 <div class="modal bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Template: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" data-method="POST" data-action="{{ url('template/store') }}" id="ajax-form"  >
                <div class="modal-body">
                    @csrf

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Template Name </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control"  name="name" />
                            <p class="error-project" id="error-name"></p>
                        </div>
                    </div>
                    
                     <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Template Excel File </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="file" class="form-control"  name="excelfile" />
                            <p class="error-project" id="error-excelfile"></p>
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

<div class="modal bd-example-modal-lg" id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Template: </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form autocomplete="off" data-method="POST" data-action="{{ url('template/update') }}" class="ajax-form"  >
                <div class="modal-body">
                    @csrf
                    
                    <input type="hidden" name="id" id="EditId" value="" />

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Template Name </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" id="Editname"  name="name" />
                            <p class="error-project" id="error-name"></p>
                        </div>
                    </div>
                    
                     <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Template Excel File </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="file" class="form-control"  name="excelfile" />
                            <p class="error-project" id="error-excelfile"></p>
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
    $(document).on('click', '.openModal', function(event) {
        
        var data = $(this).data('info');
        
        $('#EditId').val(data.id);
        $('#Editname').val(data.name);

        $('#openModal').modal('show');
        
    });
</script>
@stop