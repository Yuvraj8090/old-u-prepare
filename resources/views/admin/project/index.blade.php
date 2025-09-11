@extends('layouts.admin')

@section('content')
    <style>
        table thead th {
            text-align: center !important;
        }
        table tbody td,
        table tbody th {
            vertical-align: middle !important;
        }
        .custom-form-control{
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

    @if(request()->segment('1') == "project")
        <div>
            <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
                <h4>
                    All Projects
                    <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </button>
                </h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#"> Project Monitoring</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    @elseif(request()->segment('2') == "project")
        <div>
            <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
                <h4>
                    All Projects
                    <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </button>
                </h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Manage Project</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">Edit Projects</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    @elseif(request()->segment('1') == "define")
        <div>
            <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
                <h4>
                    All Projects
                    <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    </button>
                </h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">  Assign Project  </a></li>
                    </ol>
                </nav>
            </div>
        </div>
    @endif

    <div class="x_panel">

        <div class="row">
            <div class="col-md-12">
                {{--<div class="x_panel">--}}

                    @include('admin.project.filters')

                    <div class="x_content">

                        <table id="datatable" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th>Project Name</th>
                                    <th>Details</th>
                                    {{-- <th>Location</th> --}}
                                    <th>Contract Value</th>
                                    <th>Status</th>
                                    @if(request()->segment(1) == "project")
                                        <th style="width:200px;">Physical %</th>
                                        <th style="width:200px;">Financial %</th>
                                    @endif

                                    @if(auth()->user()->role_id == 1 || request()->segment(1) != "project")
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $key => $d)
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
                                        <tr>
                                            {{--<th>{{ $data->firstItem() + $key }}.</th>--}}
                                            <th>{{ $key + 1 }}.</th>
                                            <td>
                                                <a data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="{{ route('project.details', $d->id) }}">{{$truncated_text}}</a>
                                                <br>
                                                <span class="badge text-white bg-success">Package Number: {{ $d->number}}</span>
                                                <small style="display:block;"><b>Signed On : </b> 
                                                {{ $d->contract ? date('d M, Y',strtotime($d->contract->contract_signing_date)) : 'N/A' }}</small>
                                            </td>
                                            <td style="font-size:17px !important;">
                                                <b>Category : </b> {{ $d->category->name }}
                                                <br>
                                                <b>Sub-category : </b> {{ $d->subcategory ?? 'N/A' }}
                                                <br>
                                                <b>Department :</b> <span class="badge bg-info text-white" >{{ $d->department->department ?? 'N/A' }}</span>
                                                <br>
                                                <b>HPC Approval Date: </b> {{ date('d-m-Y', strtotime($d->hpc_approval_date)) }}
                                                <br>
                                                <b>District:</b> <span class="badge bg-info text-white">{{ $d->district_name ?? 'N/A' }}</span>
                                            </td>
                                            {{--
                                            <td style="font-size:17px !important;">
                                                <b>Vidhan Sabha Constituency : </b> {{ $d->assembly ?? "N/A" }}
                                                <br>
                                                <b>Lok Sabha Constituency : </b> {{$d->constituencie ?? "N/A"  }}
                                                <br>
                                                <b>District : </b> {{$d->district_name ?? "N/A"  }}
                                                <br>
                                                <b>Block : </b> {{$d->block ?? 'N/A'  }}
                                                <br>
                                            </td>
                                            --}}
                                            <td style="text-align:center;" >₹ {{ $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}</td>
                                            <td style="text-align:center;width:50px;">
                                                <span @class(['badge', 'text-white'=> $d->stage == 5, 'bg-danger'=> $d->stage == 5, 'bg-warning'=> $d->stage != 5])> {{ $d->projectStatus }}</span>
                                                {{-- @if(auth()->user()->role->id === 1)
                                                <br />
                                                <a href="">Change Status</a>
                                                @endif --}}
                                            </td>
                                            @if(request()->segment(1) == "project" || (request()->segment(0) == 'mis' && request()->segment(1) == 'projects'))
                                                <td class="project_progress">
                                                    <div class="progress progress_sm">
                                                        <div class="progress-bar bg-green" role="progressbar" style="width:{{ $d->ProjectTotalphysicalProgress ?? 0 }}%;" data-transitiongoal="{{ $d->ProjectTotalphysicalProgress ?? 0 }}"></div>
                                                    </div>
                                                    <small>{{ $d->ProjectTotalphysicalProgress  }}% Complete</small>
                                                    <hr>
                                                    Value (INR): <span class="fcur">{{ $d->project_total_physical_accumulative_cost ?? 0 }}</span>
                                                </td>
                                                <td class="project_progress">
                                                    <div class="progress progress_sm">
                                                        <div class="progress-bar bg-green" role="progressbar"  style="width:{{ $d->ProjectTotalfinancialProgress ?? 0 }}%;" data-transitiongoal="{{ $d->ProjectTotalfinancialProgress ?? 0 }}"></div>
                                                    </div>
                                                    <small>{{ $d->ProjectTotalfinancialProgress }}% Complete</small>
                                                    <hr>
                                                    Value (INR) : {{ number_format($d->ProjectTotalfinancialAccumulativeCost ?? 0) }}
                                                </td>
                                            @endif

                                            @if(request()->segment(1) != 'mis' && (request()->segment(2) != "work" && request()->segment(1) != "project"))
                                                <td style="text-align:center;" >
                                                    @if(auth()->user()->role->level === 'TWO' && auth()->user()->role->department != 'PROCUREMENT')
                                                        <a href="{{ route('project.defineProjectView',$d->id) }}" class="btn btn-primary btn-md">
                                                            <i class="fa fa-pencil"></i> Create Milestones
                                                        </a>
                                                        <br>
                                                        <a href="{{ route('project.test.report',$d->id) }}" class="btn btn-danger btn-md">
                                                            <i class="fa fa-file"></i> View Test Reports
                                                        </a>
                                                    @else
                                                        @if(request()->segment(1) == "manage")
                                                            <a href="{{ route('project.edit',$d->id) }}" class="btn btn-primary btn-sm">
                                                                <i class="fa fa-pencil"></i> Edit
                                                            </a>
                                                            <br>
                                                        @endif
                                                    @endif
                                                </td>
                                            @elseif(auth()->user()->role->id == 1)
                                                <td class="text-center" _lid="{{ $d->id }}">
                                                    @if($d->stage !== 5)
                                                    <a class="canp btn btn-info" href="{{ route('admin.project.cancel') }}">
                                                        Cancel
                                                    </a>
                                                    @endif
                                                    <a class="delp btn btn-danger" href="{{ route('admin.project.delete') }}">
                                                        Delete
                                                        {{-- <i class="fa fa-trash"></i> --}}
                                                    </a>
                                                </td>
                                            @else
                                                <td class="text-center">
                                                    <a href="{{ route('project.details',$d->id) }}" class="btn btn-primary">
                                                        <i class="fa fa-folder"></i> View Details
                                                    </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10">
                                            <center>
                                                <b> NO PROJECTS FOUND </b>
                                            </center>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        {{-- $data->links() --}}
                    </div>
                {{--</div>--}}
            </div>
        </div>
    </div>
@stop


@section('script')
<link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.bootstrap4.css">
<script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>

<script>
    let $body = $('body');


    @if($data->count())
    let $table = new DataTable('#datatable', {
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

    $(document).ready(function() {
        $('.btn.delp').on('click', function(e) {
            let $btn = $(this);
            e.preventDefault();

            // On Click Callback
            let occb = ()=> {
                let fd = newFormData();
                    fd.append('project_id', Number($btn.closest('td').attr('_lid')));

                let pm = {
                    url: '{{ route("admin.project.delete") }}',
                    data: fd
                }

                let bs = () => busy(1);

                let cb = (resp)=> {
                    $btn.closest('tr').slideUp('normal', function() {
                        $btn.remove();
                    });
                }

                ajaxify(pm, bs, cb);
            }

            alertBox('err', {
                text: 'Are you sure to delete this project?',
                heading: 'Danger!'
            }, occb);
        });

        $('.btn.canp').on('click', function(e) {
            let $btn = $(this);

            e.preventDefault();

            // On Click Callback
            let occb = ()=> {
                let fd = newFormData();
                    fd.append('project_id', Number($btn.closest('td').attr('_lid')));

                let pm = {
                    url: '{{ route("admin.project.cancel") }}',
                    data: fd
                }

                let bs = () => busy(1);

                let cb = (resp)=> {
                    // $btn.closest('tr').slideUp('normal', function() {
                    //     $btn.closest('tr').remove();
                    // });
                    window.location.reload();
                }

                ajaxify(pm, bs, cb);
            }

            alertBox('warn', {
                text: 'Are you sure to cancel this project?',
                heading: 'Danger!'
            }, occb);
        });

        $('.dropdown.districts').on('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            let $dd = $(this);

            $dd.toggleClass('show');
            $dd.find('ul').toggleClass('show');
        });

        $('.dropdown.districts ul').on('click', function(e) {
            e.stopImmediatePropagation();
        });


        $('body').on('click', function() {
            let $dd = $('.dropdown.districts');
                $dd.removeClass('show');
                $dd.find('ul').removeClass('show');
        })
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


    $('.fcur').each(function(i, el) {
        $(el).text(currencyFormatter.format($(el).text()))
    });
</script>
@stop
