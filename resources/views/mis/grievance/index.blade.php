@extends('layouts.admin')

@section('header_styles')
    <style>
        .btn-custom{
            padding:10px 3% !important;
        }
        .outerbox{
            margin:10px;
            padding:20px 35px;
            border-radius:10px;
            max-width:23% !important;
        }
        table th,
        table td {
            vertical-align: middle !important;
        }
        tbody tr td.gr-no {
            width: 100px;
        }
        tbody tr td.action {
            width: 200px;
        }
    </style>
@endsection

@section('content')
    <section class="breadcrumbs">
        <div class="row">
            <div class="col-md-12">
                <h4>Grievances</h4>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">Grievances</a></li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="stats pt-2 pb-4">
        <div class="row">
            <div class="outerbox col-md-4 bg-primary">
                <a href="#">
                    <h5 class="text-white">Total Grievance</h5>
                    <h1 class="text-white">
                        <i class="fa fa-file"></i>
                        <span class="stat total">{{ $grieves }}</span>
                    </h1>
                </a>
            </div>

            <div class="outerbox col-md-4 bg-warning">
                <a href="#">
                    <h5 class="text-white">Pending Grievances</h5>
                    <h1 class="text-white">
                        <i class="fa fa-file"></i>
                        <span class="stat pending">{{ $pending }}</span>
                    </h1>
                </a>
            </div>

            <div class="outerbox col-md-4 bg-success">
                <a href="#">
                    <h5 class="text-white">Resolved Grievances</h5>
                    <h1 class="text-white">
                        <i class="fa fa-file"></i>
                        <span class="stat resolved">{{ $resolved }}</span>
                    </h1>
                </a>
            </div>

            <div class="outerbox col-md-4 bg-danger">
                <a href="#">
                    <h5 class="text-white">Rejected Grievances</h5>
                    <h1 class="text-white">
                        <i class="fa fa-file"></i>
                        <span class="stat rejected">{{ $rejected }}</span>
                    </h1>
                </a>
            </div>
        </div>
    </section>

    <section class="filters">
        <form action="" method="GET" autocomplete="off">
            <div class="row">
                <div class="col-lg-10 col-md-8">
                    <div class="col-md-3 mb-3">
                        <input type="text" placeholder="Search by name..." name="name" value="{{ request()->name ?? '' }}" class="form-control" />
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="district" class="form-control">
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option  value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="typology" class="form-control">
                            <option value="">Related to</option>
                            @foreach($typology as $typo)
                                <option  value="{{ $typo->slug }}" @selected(request('typology') == $typo->slug)>{{ $typo->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="pending" @selected(request('status') == 'pending')>Pending</option>
                            <option value="resolved" @selected(request('status') == 'resolved')>Resolved</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <select name="year" class="form-control">
                            <option value="">Select Year</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->year }}" @selected(request('year') == $year->year)>{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-1 mb-3">
                        <select name="month" class="form-control">
                            <option value="">Select Month</option>
                            @foreach ($months as $key => $month)
                                <option value="{{ $key + 1 }}" @selected(request('month') == ($key + 1))>{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mb-3">
                    <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning pull-right">
                        <i class="fa fa-search" ></i>
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </section>

    <section class="data x_panel">
        <div class="x_content">
            <table id="datatable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Grievance No.</th>
                        <th>Related To</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Resolved At</th>
                        {{--
                        <th>Action</th>
                        --}}
                    </tr>
                </thead>
                <tbody>
                    @php $shown = 0; @endphp
@php
    if (!function_exists('formatDate')) {
        function formatDate($date, $format = 'd M Y') {
            if (empty($date)) return 'N/A';

            try {
                return \Carbon\Carbon::parse($date)->format($format);
            } catch (\Exception $e) {
                return 'N/A';
            }
        }
    }
@endphp

                    @if($grievances->count())
                        @foreach($grievances as $key => $grievance)
                            @php
                                $days_lapsed = (int) $grievance->created_at->diffInDays();
                                $g_typo      = App\Helpers\DummyData::typology($grievance->typology);
                                $show        = 0;
                                $days        = 1;

                                // Create a Log Entry for Grievance if Days Lapsed Criteria is Matched
                                if($days_lapsed > 7 && $grievance->user_id)
                                {
                                    App\Helpers\Assistant::grievanceLogEntry($grievance, $days_lapsed);
                                }

                                // If User is Admin Show the Grievance
                                if(auth()->user()->hasRole('Admin') || auth()->user()->hasPermission('grm_nodal') || (auth()->user()->hasPermission('grm_nodal') && $days_lapsed > 14))
                                {
                                    $show = 1;
                                }
                                // elseif(auth()->user()->r_dept == $grievance->department->name)
                                elseif(auth()->id() == $grievance->user_id || ($grievance->project && (auth()->id() == $grievance->project->es_level_four) ) )
                                {
                                    $show = 1;
                                }
                            @endphp
                            @if($show)
                                <tr _id="{{ $grievance->id }}" _grid={{ $grievance->ref_id }} @class(['text-dark'=> $days_lapsed < 7, 'text-warning'=> $days_lapsed > 7, 'text-danger'=> $days_lapsed > 14])>
                                    <th>{{ $key + 1 }}.</th>
                                    <td class="text-center gr-no">
                                        <a class="_gdl" href="{{ route('mis.grievance.detail', $grievance->ref_id) }}">{{ $grievance->ref_id ?? '—' }}</a>
                                    </td>
                                    <td>{{ $g_typo }} @if($g_typo == 'Other')<br><small>{{ $grievance->typo_other }}</small>@endif</td>
                                    <td>{{ $grievance->department ? $grievance->department->name : '—' }}</td>
                                    <td class="text-center">{{ ucwords($grievance->status) }}</td>
                                    <td class="text-center">{{ $grievance->created_at->format('d M, Y') }}</td>
                                    <td class="text-center">{{ formatDate($grievance->resolved_at) }}</td>
                                    {{--
                                    <td class="text-center action">
                                        <a href="#" class="btn btn-success">Complaint Log</a>
                                    </td>
                                    --}}
                                </tr>
                                @php ++$shown; @endphp
                            @endif
                        @endforeach
                    @endif
                    @if(!$shown)
                        <tr>
                            <td colspan="10">
                                <center>
                                    <b> No Grievance Found </b>
                                </center>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{-- $data->links() --}}
        </div>
    </section>
@stop

<!-- code changes added on 9 feb  -->
@section('script')
    {{-- <script>
        (function(){
            let wiw = window.innerWidth;
            let wih = window.innerHeight;
            let csw = document.body.clientWidth;

            let zra = parseInt(csw / wiw);
            let whr = parseInt(wiw / wih);

            let haz = csw / whr;

            $('.right_col').attr('style', `min-height:${haz}px`)
        })()
    </script> --}}
    @if(!$shown)
    <script>
        $('.outerbox span.stat').text('0');
    </script>
    @endif
@stop
