
@extends('layouts.admin')

@section('content')
    <style>
        table td,
        table th {
            vertical-align: middle !important;
        }

        table.table-boq-sheet .num {
            text-align: right;
        }

        .financial-summary {
            background: #f1f1f1;
            border-radius: 6px;
            padding: 15px 25px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: 500;
        }
    </style>

    @php $is_msts = $project->contract && !is_null($project->contract->ms_type) @endphp

    <div>
        <div class="page-title">
            <div class="title_left w-100">
                <button class="btn btn-md btn-primary pull-right previousButton" style="padding:5px 20px;">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                <h3>{{ isset($view) ? 'View ' : '' }}Milestones || Project: {{ $project->name ?? 'N/A' }}</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                @isset($view)
                    <li class="breadcrumb-item"><a href="{{ route('mis.projects') }}">Project Monitoring</a></li>
                @endisset
                <li class="breadcrumb-item">
                    <a
                        href="{{ isset($view) ? route('mis.project.detail', $id) : route('mis.project.tracking.progress.update', 'physical') }}">
                        Project{{ isset($view) ? ' Details' : 's' }}
                    </a>
                </li>
                <li class="breadcrumb-item active">Milestones</li>
            </ol>
        </nav>

        @include('admin.error')

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel msxp">
                    <div class="x_title d-flex justify-content-between align-items-center">
                        <h2>Project Milestones</h2>
                        {{-- Update Physical Progress (Conditionally Shown) --}}
@if ($is_msts && !$project->contract->ms_type && !$project->contract->pwd_boqs->count() && !isset($view))
    <a class="btn btn-sm btn-success" href="{{ route('projectLevel.physical.view', $project->id) }}">
        <i class="fa fa-pencil mr-1"></i> Update Physical Progress
    </a>
@endif

{{-- Update BOQ Sheet (Conditionally Shown) --}}
@if ($is_msts && $project->contract->pwd_boqs->count() && !isset($view))
    <a class="btn btn-sm btn-info" href="{{ route('projectLevel.update.boqsheet', $project->id) }}">
        <i class="fa fa-pencil mr-1"></i> Update BOQ Sheet
    </a>
@endif

{{-- View BOQ Sheet (Always Shown if $view is set) --}}
@isset($view)
   
@endisset

{{-- Site Images (View only — always shown if ms_type is true) --}}
@if ($project->contract->ms_type)
    @if (auth()->user()->role->level === 'THREE')
        
    @elseif(auth()->user()->role->level === 'ONE')
        <a class="btn btn-md btn-info" href="{{ url('admin/milestone/site/images/' . $project->id) }}">
            View Site Images
        </a>
    @endif
@endif

{{-- Financial Progress --}}
@isset($view)
    {{-- View button: Always show --}}
    
@else
    {{-- Update button: Conditionally shown --}}
    <a class="btn btn-sm btn-primary" href="{{ route('projectLevel.financial.view', $project->id) }}">
        <i class="fa fa-pencil mr-1"></i> Financial Progress
    </a>
@endisset
<a class="btn btn-sm btn-primary"
        href="{{ route('mis.project.detail.milestone.financial', [$project->id, $project->id]) }}">
        <i class="fa fa-eye mr-1"></i> View Financial Progress
    </a>
    <a class="btn btn-md btn-info" href="{{ url('admin/milestone/site/images/' . $project->id) }}">
            View Site Images
        </a>
         <a class="btn btn-sm btn-info" href="{{ route('projectLevel.physical.view.name', $project->id) }}">
        <i class="fa fa-eye mr-1"></i> View Physical Progress </a>
                    </div>
                   

                    <div class="x_content">
                        {{-- Financial Summary Block --}}
                     

                        <div class="card-box table-responsive">
                            <table class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">S.No</th>
                                        <th style="width: 20%;">Milestone</th>
                                        <th style="width: 15%;">Budget</th>
                                        <th style="width: 15%;">Total Work (%)</th>
                                        <th style="width: 20%;">Dates</th>
                                        <th style="width: 20%;">Amended Dates</th>
                                    </tr>
                                </thead>
                                
                                <tbody class="text-center">
                                    @forelse ($data as $key => $datum)
                                        <tr>
                                            <td>M{{ $key + 1 }}</td>
                                            <td>{{ $datum->name }}</td>
                                           <td class="fcurr">₹{{ number_format($datum->budget) }}</td>
                                            <td>{{ $datum->percent_of_work }}%</td>
                                            <td>
                                                Start:
                                                {{ $datum->start_date ? date('d-m-Y', strtotime($datum->start_date)) : 'N/A' }}<br>
                                                End:
                                                {{ $datum->end_date ? date('d-m-Y', strtotime($datum->end_date)) : 'N/A' }}
                                            </td>
                                            <td>
                                                Start:
                                                {{ $datum->amended_start_date ? date('d-m-Y', strtotime($datum->amended_start_date)) : 'N/A' }}<br>
                                                End:
                                                {{ $datum->amended_end_date ? date('d-m-Y', strtotime($datum->amended_end_date)) : 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No milestones found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop



@section('script')
    <script>
        // Currency formatter
        const currencyFormatterFraction = new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR',
            minimumFractionDigits: 2
        });

        // Format all currency cells
        $('.fcurr').each(function(i, el) {
            const val = parseFloat($(el).text());
            $(el).text(currencyFormatterFraction.format(val));
        });
    </script>
@endsection
