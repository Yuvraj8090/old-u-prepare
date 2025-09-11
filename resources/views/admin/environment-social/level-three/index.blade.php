@extends('layouts.admin')

@section('content')
    <style>
        table td,
        table th {
            vertical-align: middle !important;
        }

        .custom-form-control{
            height: 40px;
            border-radius: 5px;
            margin-right: 5px;
            width: 400px;
            padding: 10px;
        }
        .col-md-3,.col-md-4{
            margin-top:10px;
        }

        table .btn-uc {
            min-width: 214px;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            <h4>All Projects</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#"> Update Project </a></li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="x_panel">
        <div class="x_content">
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" placeholder="Search..." name="name" value="{{ request()->name ?? '' }}" class="form-control" >
                    </div>

                    <div class="col-md-3">
                        <select  name="year" class="form-control">
                            <option value="">Approval Year</option>
                            @if(count($years) > 0)
                                @foreach($years as $ye)
                                    <option style="background-color:white;color:black;" value="{{ $ye }}" @if(request('year') == $ye) selected @endif>{{ $ye }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="completion_year" class="form-control">
                            <option value="">Completion Year</option>
                            @if(count($years) > 0)
                                @foreach($years as $ye)
                                    <option style="background-color:white;color:black;" value="{{ $ye }}" @if(request('completion_year') == $ye) selected @endif>{{ $ye }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-3">

                        <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                            <i class="fa fa-search" ></i>
                            Filter
                        </button>

                        <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                            <i class="fa fa-refresh" ></i>
                            Reset
                        </a>

                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-md-12">
                    <div class="x_content">

                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 40px">#</th>
                                    <th>Project Name</th>
                                    <th> Details</th>
                                    {{-- <th style="width: 17%" > Location</th> --}}
                                    <th>Project Status</th>
                                    <th>Progress(%)</th>
                                    <th style="width: 246px">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $key => $d)
                                        @php
                                            $max_length = 50;
                                            if (strlen($d->name) > $max_length) {
                                                $truncated_text = substr($d->name, 0, $max_length) . "...";
                                            } else {
                                                $truncated_text = $d->name;
                                            }
                                        @endphp

                                        <tr>
                                            <th>{{ $key + 1 }}.</th>
                                            <td>
                                                <a  data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="javacsript:void(0)">{{ $truncated_text }} </a>
                                                <br>
                                                <span class="badge text-white bg-success">Project Id: {{ $d->number}}</span>
                                                <small style="display:block;">
                                                    <b>Created On: </b> {{ date('d M, Y',strtotime($d->created_at)) }}
                                                </small>
                                            </td>
                                            <td style="font-size:17px !important;">
                                                <b>Category: </b> {{ $d->category->name }}
                                                <br>
                                                <b>Sub-category: </b> {{ $d->subcategory ?? 'N/A' }}
                                                <br>
                                                <b>Department: </b>
                                                <span class="badge bg-info text-white">{{ $d->department->department ?? 'N/A' }}</span>
                                                <br>
                                                <b>HPC Approval Date: </b>
                                                {{ date('d-m-Y', strtotime($d->hpc_approval_date)) }}
                                                <br>
                                                <b>District:</b>
                                                <span class="badge bg-info text-white">{{ $d->district_name ?? 'N/A' }}</span>
                                            </td>
                                            {{--
                                            <td style="font-size:17px !important;">
                                                <b>Assembly : </b> {{ $d->assembly ?? "N/A" }}  <br>
                                                <b>Constituencie : </b> {{$d->constituencie ?? "N/A"  }}  <br>
                                                <b>District : </b> {{$d->district_name ?? "N/A"  }}  <br>
                                                <b>Block : </b> {{$d->block ?? 'N/A'  }}  <br>
                                            </td>
                                            --}}

                                            <td style="text-align:center;font-weight:700;">Pending</td>

                                            <td class="project_progress">
                                                <div class="progress progress_sm">
                                                    <div class="progress-bar bg-green" role="progressbar" style="width:{{ $d->weightCompleted ?? 0 }}%" data-transitiongoal="{{ $datum->weightCompleted ?? 0 }}"></div>
                                                </div>
                                                <small>{{ $d->weightCompleted ?? 0 }}% Complete</small>
                                            </td>

                                            <td class="text-center">
                                                @if(CheckESLevelThree() == '1')
                                                    @if(empty($d->EnvironmentDefineProject) && $d->es_level_four)
                                                        <a href="{{ url('social/environment/define/' . $d->id) }}" class="btn btn-primary btn-md">Assign Project</a>
                                                    @else
                                                        <a href="{{ url('social/environment/define/edit/' . $d->id) }}" class="btn btn-success btn-md">Assign Project</a>

                                                        @if(false)
                                                            @if(count($d->environmentMilestones) > 0 )
                                                                <a class="btn btn-md btn-success" href="{{ url('update/milestones/progress/'.$d->id) }}">
                                                                    <i class="fa fa-edit"></i>
                                                                    Update Progress
                                                                </a>
                                                            @else
                                                                <a href="{{ url('/es/environment/' . $d->id) }}" class="btn btn-secondary btn-md">
                                                                    <i class="fa fa-pencil"></i>
                                                                    Create MileStone
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @elseif(CheckESLevelThree() == '2')
                                                    @if(empty($d->SocialDefineProject) && $d->es_level_four)
                                                        <a href="{{ url('social/environment/define/' . $d->id) }}" class="btn btn-primary btn-md">Assign Project</a>
                                                    @else
                                                        <a href="{{ url('social/environment/define/edit/' . $d->id) }}" class="btn btn-success btn-md">Assign Project</a>

                                                        @if(false)
                                                            @if(count($d->socialMilestonesSocial) > 0)
                                                                <a class="btn btn-md btn-success"  href="{{ url('update/milestones/progress/'.$d->id) }}">
                                                                    <i class="fa fa-edit"></i>
                                                                    Update Progress
                                                                </a>
                                                            @else
                                                                <a href="{{ url('/es/social/'.$d->id) }}" class="btn btn-secondary btn-md">
                                                                    <i class="fa fa-pencil"></i>
                                                                    Create MileStone
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                                <a href="{{ url('all/photos/' . $d->id) }}" class="btn btn-primary btn-md">Photos</a>

                                                {{-- <a href="{{ url('quality/tests/' . $d->id) }}" class="btn btn-primary btn-md">Activites</a> --}}
                                                @php
                                                    $type = explode('-', auth()->user()->role->department);
                                                    $type = strtolower($type[count($type) - 1]);
                                                    $type = in_array($type, ['environment', 'social']) ? strtolower($type) : NULL;
                                                @endphp
                                                @if($type)
                                                    {{-- <a class="btn btn-info btn-md" href="{{ route('mis.project.tracking.safeguard.overview', [$type, $d->id]) }}">Compliances Overview</a> --}}
                                                    <a class="btn btn-info btn-md btn-uc" href="{{ route('mis.project.tracking.safeguard.overview', [$type, $d->id]) }}">Update Compliances</a>
                                                    {{-- <a class="btn btn-info btn-md" href="{{ route('mis.project.tracking.safeguard.entry.sheet', [$type, $d->id]) }}">Update Compliances</a> --}}
                                                @endif
                                            </td>
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

                        {{-- $data->links() --}}
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


@section('script')
    <script>
        @if($data->count())
        $('.curf').each(function(i, el) {
            $(el).text(currencyFormatterFraction.format($(el).text()));
        });
        let $table = new DataTable('#datatable', {
            pageLength: 5,
            lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
        });
        @endif
    </script>

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
