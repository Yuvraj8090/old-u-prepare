@extends('layouts.admin')

@section('content')
    <style>
        .custom-form-control {
            width: 350px;
            height: 40px;
            padding: 10px;
            margin-right: 5px;
            border-radius: 5px;
        }

        #datatable_wrapper .dt-layout-row:first-child {
            zoom: 125%;
        }
        .col-md-2,
        .col-md-4 {
            margin-bottom: 10px;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>Project Procurement</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Project Procurement</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_content">
            @include('admin.procurement.filters')
        </div>

        <div class="row">
            <div class="col-md-12">
                {{-- <div class="x_panel"> --}}
                    <div class="x_title">
                        <h2>Projects</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        @php $status = FALSE;
                            if(
                                auth()->user()->role->level === 'THREE' &&
                                in_array(
                                    auth()->user()->role->department,
                                    ['USDMA-PROCUREMENT', 'FOREST-PROCUREMENT', 'RWD-PROCUREMENT', 'PWD-PROCUREMENT', 'PMU-PROCUREMENT' ]
                                )
                            ) {
                                $status = TRUE;
                            }
                        @endphp

                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                @if($status)
                                    <tr>
                                        <th style="width: 1%">#</th>
                                        <th style="width: 17%">Project Name</th>
                                        <th style="width: 17%"> Details</th>
                                        <th style="width: 17%"> Location</th>
                                        <th  style="width: 10%">Contract Value</th>
                                        <th style="width: 10%">Procurement Progress(%) </th>
                                        <th style="width: 10%"> Status</th>
                                        @if($status)
                                        <th style="width: 13%">Action </th>
                                        @endif
                                    </tr>
                                @else
                                    <tr>
                                        <th style="width: 1%">#</th>
                                        <th style="width: 20%">Project Name</th>
                                        <th style="width: 17%">Details</th>
                                        <th style="width: 17%">Location</th>
                                        <th style="width: 10%">Contract Value</th>
                                        <th style="width: 15%">Procurement Progress(%) </th>
                                        <th style="width: 10%">Status</th>
                                        @if($status)
                                        <th style="width: 20%">Action</th>
                                        @endif
                                    </tr>
                                @endif

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

                                            <td class="text-left">
                                                {{-- <a data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="{{ (!$status) ? url('project/details/' . $d->id) : 'javascript:void(0)' }}">{{ $truncated_text }}</a> --}}
                                                <a data-toggle="tooltip" data-placement="top" title="{{ $d->name }}" style="color:blue;" href="{{ url('project/details/' . $d->id) }}">{{ $truncated_text }}</a>
                                                <br />
                                                <span class="badge text-white bg-success">Project Id: {{ $d->number}}</span>
                                                <small style="display:block;"><b>Created On : </b> {{ date('d M, Y',strtotime($d->created_at)) }}</small>
                                            </td>

                                            <td style="font-size:17px !important;">
                                                <b>Category : </b> {{$d->category->name}}  <br>
                                                <b>Sub-category : </b> {{$d->subcategory ?? 'N/A'}}  <br>
                                                <b>Department :</b> <span class="badge bg-danger text-white" >{{ $d->department->department ?? 'N/A' }}</span> <br>
                                                <b>Approval Date :</b> {{ date("d-m-Y",strtotime($d->hpc_approval_date))  }}<br>
                                            </td>

                                            <td style="font-size:17px !important;">
                                                <b>Vidhan Sabha Constituency  : </b> {{ $d->assembly ?? "N/A" }}  <br>
                                                <b>Lok Sabha Constituency  : </b> {{$d->constituencie ?? "N/A"  }}  <br>
                                                <b>District : </b> {{$d->district_name ?? "N/A"  }}  <br>
                                                <b>Block : </b> {{$d->block ?? 'N/A'  }}  <br>
                                            </td>

                                            <th class="text-center" >
                                                {{ $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}
                                            </th>

                                            <td class="project_progress text-center">
                                                <div class="progress progress_sm">
                                                    <div class="progress-bar bg-green" role="progressbar"  style="width: {{ $d->weightCompleted ?? 0 }}%;" data-transitiongoal="{{ $d->weightCompleted ?? 0 }}"></div>
                                                    {{--
                                                    <div class="progress-bar bg-green" role="progressbar" style="width: {{ 0 }}%;" data-transitiongoal="{{ 0 }}"></div>
                                                    --}}
                                                </div>
                                                {{--
                                                <small>{{ 0 }}% Complete</small>
                                                --}}
                                                <small>{{ $d->weightCompleted ?? 0 }}% Complete</small>
                                            </td>

                                            <td class='text-center'>{{ Assistant::getProjectStatus($d->stage) }}</td>

                                            @if($status)
                                                <td class='text-center'>
                                                    @if(!$d->defineProject)
                                                        <a href="{{ url('procurement/create/' . $d->id) }}" class="btn btn-primary btn-sm">Create Procurement</a>
                                                    @else
                                                        <a href="{{ route('procurement.edit', $d->defineProject->id) }}" class="btn btn-success btn-sm">
                                                            <i class="fa fa-pencil"></i>&nbsp;&nbsp;  Edit Procurement
                                                        </a>
                                                        <a href="{{ url('procurement/program/update/'.$d->defineProject->id) }}" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-pencil"></i>&nbsp;&nbsp;  Update Work Program
                                                        </a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            <center>NO DATA FOUND</center>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        {{-- $data->links() --}}
                    </div>
                {{-- </div> --}}
            </div>
        </div>
    </div>
@stop

@section('script')
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.bootstrap4.css">
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>

    <script>
        @if($data->count())
        let table = new DataTable('#datatable', {
            pageLength: 5,
            lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
        });
        @endif

        $("#category_id").on("change", function (event) {
            event.preventDefault();

            let id = $(this).val();

            $.ajax({
                url: "{{ url('getSubCategory') }}/"+id,
                type: "GET",
                success: function (response) {

                    if (response) {
                        populateSelect('#subcategory', response);
                    }

                },
                error: function (err) {
                    toastr.info("Error! Please Contact Admin.");
                },
            });
        });

        function populateSelect(selector, data) {
            $(selector).removeAttr('readonly');
            $(selector).removeAttr('disabled');

            $(selector).empty(); // Clear existing options

            $(selector).append($('<option>', {
                value: '',
                text: 'Select'
            }));

            $.each(data, function(index, item) {
                $(selector).append($('<option>', {
                    value: item.name,
                    text: item.name
                }));
            });
        }
    </script>
@stop
