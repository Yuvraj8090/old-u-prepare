@extends('layouts.admin')

@section('content')

<div class="x_panel">
    <div class="x_title">
        <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
        <h5 style="font-weight:550;">Edit MileStones: </h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
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
                    <input type="number" class="form-control" name="percent_of_work" value="{{ $data->percent_of_work }}" placeholder="Percentage of Work..">
                    <p class="error" id="error-percent_of_work"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">Start Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="start_date" value="{{ date('d-m-Y',strtotime($data->start_date)) }}" disabled >
                    <p class="error" id="error-start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3 ">End Date </label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="text" class="form-control" name="end_date" value="{{ date('d-m-Y',strtotime($data->end_date)) }}" disabled >
                    <p class="error" id="error-start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3">Amended Start Date</label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="date" class="form-control airpicker" name="amended_start_date" placeholder="Amended End Date..">
                    <p class="error" id="error-amended_start_date"></p>
                </div>
            </div>

            <div class="form-group row">
                <label class="control-label col-md-3 col-sm-3">Amended End Date</label>
                <div class="col-md-9 col-sm-9 ">
                    <input type="date" class="form-control airpicker" name="amended_end_date" placeholder="Amended End Date..">
                    <p class="error" id="error-amended_end_date"></p>
                </div>
            </div>

            <div class="ln_solid"></div>
            <div class="form-group">
                <div class="col-md-9 col-sm-9  offset-md-3">
                    <button id="submit-btn" type="submit" class="btn btn-success">
                        <span class="loader" id="loader" style="display: none;"></span>
                        Edit Milestone
                    </button>

                    {{-- <a type="reset" class="btn btn-primary" href="{{ route('project.create') }}">Reset</a> --}}
                </div>
            </div>

        </form>
    </div>
</div>

<div class="x_panel">
    <div class="x_title">
        <button id="AddButton" class="btn btn-md btn-success pull-right">Add Document</button>

        <h5 style="font-weight:550;">MileStones Documents: </h5>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @if( count($data->document) > 0)
            @foreach($data->document as $key => $doc)

                <div class="form-group row" id="removeInputq">

                        <label class="control-label col-md-3 col-sm-3 ">Document Name </label>
                        <input type="hidden" name="documents[{{$key}}]['id']" value="{{ $doc->id }}" />
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="documents{{$key}}" value="{{ $doc->name }}" />
                        </div>
                        <div class="col-md-2">
                            <button type="button" data-id="{{$key}}" data-docid="{{$doc->id}}" class="btn btn-danger btn-sm removeInputAdd">Edit</button>
                        </div>

                </div>

            @endforeach
            @endif

            <form style="display:none;" id="form1" autocomplete="off" data-method="POST" data-action="{{ route('mile.stone.documents',$data->id) }}" class="form-horizontal form-label-left ajax-form">
                @csrf
                    <div id="AddInput"></div>

                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-9 col-sm-9  offset-md-3">
                            <button id="submit-btn" type="submit" class="btn btn-success">
                                <span class="loader" id="loader" style="display: none;"></span>
                                Submit
                            </button>
                        </div>
                    </div>
            </form>
    </div>
</div>



<input type="hidden" id="input" value="1"/>
@stop



@section('script')

<script>

$('#AddButton').click(function() {

    var i = $('#input').val();

    $('#form1').show();

    var html = '<div class="form-group row" id="removeInput'+i+'">';
        html += '<label class="control-label col-md-3 col-sm-3 ">Document Name </label>';
        html += '<div class="col-md-7">';
        html += '<input type="text" class="form-control" name="documents[]" placeholder="Document Name.." required>';
        html += '<p class="error" id="error-start_date"></p>';
        html += '</div>';
        html += '<div class="col-md-2">';
        html += '<button type="button" data-id="'+i+'" class="btn btn-danger btn-sm removeInput">Remove</button>';
        html += '</div>';
        html += '</div>';

        i = parseInt(i, 10) + 1;
        $('#input').val(i);
        $('#AddInput').append(html);
});


$(document).on('click','.removeInput',function() {
    var id = $(this).data('id');
    $('#removeInput'+id).remove();
    console.log(id);
});


$(document).on('click','.removeInputAdd',function(e){
    e.preventDefault();

    var i = $(this).data('id');
    var idd = $(this).data('docid');
    var name = $('#documents'+i).val();

    $.ajax({
            url: "{{ route('mile.stone.updated') }}",
            type: 'POST',
            data : {
                name:name,
                id:idd,
                _token : "{{csrf_token()}}",
            },
            dataType: 'json',
            success: function(response) {
                if(response.success){
                    toastr.success('Milestone Document updated successfully.');
                }else{
                    toastr.warning('Error! Please try again after sometime.');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error! Please try again after sometime.');
            }
    });
});

</script>

@stop
