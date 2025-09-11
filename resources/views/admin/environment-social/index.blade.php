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
        <h4>Define Projects</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#"> Define Projects </a></li>
            </ol>
        </nav>
    </div>
</div>


<div class="x_panel">

    <div class="x_content">
        @include('admin.project.filters')
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">

                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                 <th>Project Name</th>
                                <th> Details</th>
                                {{-- <th style="width: 18%" > Location</th> --}}
                                <th>Contract Value</th>
                                <th>Status</th>
                                <!--<th>Progress(%)</th>-->
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    <tr>
                                        @php
                                            $max_length = 50;

                                            if (strlen($d->name) > $max_length)
                                            {
                                                $truncated_text = substr($d->name, 0, $max_length) . "...";
                                            }
                                            else
                                            {
                                                $truncated_text = $d->name;
                                            }
                                        @endphp

                                        <th>{{ $data->firstItem() + $key }}.</th>
                                        <td>
                                            <a data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="{{ url('/projects/social/environment/details/'.$d->id) }}">
                                                {{$truncated_text}}
                                            </a><br>
                                            <span class="badge text-white bg-success">Project Id: {{ $d->number}}</span>
                                            <small style="display:block;"><b>Created On : </b> {{ date('d M, Y',strtotime($d->created_at)) }}</small>
                                            Contract Value :  {{ $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}
                                        </td>
                                        <td style="font-size:17px !important;">
                                            <b>Category : </b> {{$d->category->name}}
                                            <br>
                                            <b>Sub-category : </b> {{$d->subcategory ?? 'N/A'}}
                                            <br>
                                            <b>Department :</b> <span class="badge bg-info text-white" >{{ $d->department->department ?? 'N/A' }}</span>
                                            <br>
                                            <b>HPC Approval Date: </b> {{ date('d-m-Y', strtotime($d->hpc_approval_date)) }}
                                            <br>
                                            <b>District:</b> <span class="badge bg-info text-white">{{ $d->district_name ?? 'N/A' }}</span>
                                        </td>

                                        {{--
                                        <td style="font-size:17px !important;">
                                            <b>Assembly : </b> {{ $d->assembly ?? "N/A" }}  <br>
                                            <b>Constituencie : </b> {{$d->constituencie ?? "N/A"  }}  <br>
                                            <b>District : </b> {{$d->district_name ?? "N/A"  }}  <br>
                                            <b>Block : </b> {{$d->block ?? 'N/A'  }}  <br>
                                        </td>
                                        --}}

                                        <td style="text-align:center;font-align:center;font-weight:800;">{{ $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}</td>
                                        <td class="text-center" >
                                            <span class="badge bg-danger text-white">
                                            {{ $d->projectStatus }}
                                            </span>
                                        </td>
                                        @if(false)
                                            <td class="project_progress">
                                                <div class="progress progress_sm">
                                                    <div class="progress-bar bg-green" role="progressbar"  style="width:{{ $d->weightCompleted ?? 0 }}%"  data-transitiongoal="{{ $d->weightCompleted ?? 0 }}"></div>
                                                </div>
                                                <small>{{ $d->weightCompleted ?? 0 }}% Complete</small>
                                            </td>
                                        @endif
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
                    </table>

                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@if(false)
<form action="{{ route('AssignProjectlevelThree.update') }}" method="POST">
    @csrf
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Assign Project to:</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">



                    <input type="hidden" id="projectid" name="project_id" value="" />

                    <div class="form-group">
                        <label for="exampleInputEmail1">Select Department:</label>
                        <select id="selectDepartment" class="form-control" name="department">
                            <option value="">SELECT DEPARTEMENT</option>
                            @if(count($AssignDepartment) > 0)
                            @foreach($AssignDepartment as $depart)
                            <option value="{{ $depart->id }}">{{ $depart->name }}</option>
                            @endforeach
                            @endif
                        </select>
                        <p class="error" id="error-department"></p>
                    </div>

                    <div style="display:none;" id="departmentShow">

                        <h5>Department Details:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label"> Name:</label>
                                    <div class=" ">
                                        <input type="text" id="departName" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Designation:</label>
                                    <div class=" ">
                                        <input type="text" id="departDesignation" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Contact:</label>
                                    <div class=" ">
                                        <input type="text" id="departContact" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Email:</label>
                                    <div class=" ">
                                        <input type="text" id="departEmail" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>

                <!-- Modal footer -->
                <div class="modal-footer">

                    <button type="submit" class="btn btn-primary pull-left">Submit</button>

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endif

@stop


<!-- code added on  -->
@section('script')
@if(false)
<script>
    $('#selectDepartment').change(function(e) {
        e.preventDefault();

        $('#departmentShow').hide();
        var id = $(this).val();

        $.ajax({
            url: "{{ url('environment-social/department') }}/" + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {

                $('#departName').val(response.name);
                $('#departDesignation').val(response.designation);
                $('#departContact').val(response.phone_no);
                $('#departEmail').val(response.email);

                $('#departmentShow').show();

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

    });
</script>
@endif
@stop
