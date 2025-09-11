@extends('layouts.admin')

@section('header_styles')
    <style>
        table th,
        table td {
            vertical-align: middle !important;
        }

        table .prona {
            display: inline-block;
            overflow: hidden;
            max-width: 320px;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            <h4>Update Project Progress || Project : {{ $data[0]->name ?? 'N/A' }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Update Project Progress</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <form action="" method="GET">
            <div class="row">
                <div class="col-lg-10 col-md-8">
                    <div class="row">

                        <div class="col-md-2">
                            <select name="subcategory" id="subcategory" class="form-control">
                                <option value="">Sub-Category</option>
                                @if(isset($subcategory) && $subcategory->count())
                                    @foreach ($subcategory as $scat)
                                        <option value="{{ $scat->name }}" @selected(request('subcategory') == $scat->name)>{{ $scat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="year" class="form-control mr-1">
                                <option value="">HPC Approval Year</option>
                                @if(count($years) > 0)
                                    @foreach($years as $ye)
                                        <option style="background-color:white;color:black;" value="{{ $ye }}" @if(request('year') == $ye) selected @endif>{{ $ye }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2">
                    <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                        <i class="fa fa-search" ></i>
                        Filter
                    </button>

                    <a href="{{ url('four/projects') }}" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                        <i class="fa fa-refresh" ></i>
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="x_panel">
        <div class="x_content">

            <div class="row">
                <div class="col-md-12">
                    {{-- <div class="x_panel"> --}}
                        <div class="x_title">
                            <h2>Projects</h2>
                            <div class="clearfix"></div>
                        </div>

                        <table id="datatable" class="table table-striped table-bordered w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project Name</th>
                                    <th>Details</th>
                                    <th>Location</th>
                                    <th style="width: 188px;">Action</th>
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
                                            <th>{{ $key + 1 }}.</th>
                                            <td>
                                                <a data-toggle="tooltip" data-placement="top" title="{{ $d->name }}" style="color:blue;" href="javavscript:void(0)">
                                                    <span class="prona">{{ $d->name }}</span>
                                                    {{-- {{ $truncated_text }} --}}
                                                </a>
                                                <br>
                                                <span class="badge text-white bg-success">Project Id: {{ $d->number }}</span>
                                                <small style="display:block;">
                                                    <b>Created On : </b> {{ date('d M, Y', strtotime($d->created_at)) }}
                                                </small>
                                                Contract Value: {{ $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}
                                            </td>
                                            <td style="font-size:17px !important;">
                                                <b>Category:</b> {{$d->category->name}}
                                                <br>
                                                <b>Sub-category:</b> {{$d->subcategory ?? 'N/A'}}
                                                <br>
                                                <b>Department:</b>
                                                <span class="badge bg-danger text-white" >{{ $d->department->department ?? 'N/A' }}</span>
                                                <br>
                                                <b>Approval Date:</b> {{ date("d-m-Y", strtotime($d->approval_date)) }}
                                                <br>
                                            </td>
                                            <td style="font-size:17px !important;">
                                                <b>Vidhan Sabha Constituency:</b> {{ $d->assembly ?? "N/A" }}
                                                <br>
                                                <b>Lok Sabha Constituency:</b> {{$d->constituencie ?? "N/A" }}
                                                <br>
                                                <b>District:</b> {{$d->district_name ?? "N/A" }}
                                                <br>
                                                <b>Block:</b> {{ $d->block ?? 'N/A' }}
                                                <br>
                                            </td>
                                            <td class="text-center">
                                                {{--
                                                <a href="{{ url('quality/four/tests/'.$d->id) }}" class="btn btn-secondary btn-sm">
                                                    <i class="fa fa-pencil"></i> &nbsp;&nbsp; Update Activities
                                                </a>
                                                --}}
                                                @php $section = auth()->user()->dept_sec; @endphp

                                                @if($section && in_array($section, ['ENVIRONMENT', 'SOCIAL']))
                                                <a href="{{ route('mis.project.tracking.safeguard.entry.sheet', [strtolower($section), $d->id]) }}" class="btn btn-secondary btn-sm">
                                                    <i class="fa fa-pencil"></i> &nbsp;&nbsp; Update Compliances
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9"><center> NO DATA FOUND </center> </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                    {{-- $data->links() --}}
                    {{-- </div> --}}
                </div>
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
    </script>
@endsection
