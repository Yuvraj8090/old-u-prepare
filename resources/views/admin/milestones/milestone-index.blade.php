@php
    $totalProgress = $totalPhysicalProgress;
@endphp
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
                        <div class="d-flex gap-2">

                            {{-- BOQ Sheet/Physical Progress --}}
                              @if ($is_msts && !$project->contract->ms_type && !$project->contract->pwd_boqs->count() && !isset($view))
                                <a class="btn btn-sm btn-success"
                                    href="{{ route('projectLevel.physical.view', $project->id) }}">
                                    <i class="fa fa-pencil mr-1"></i> Update Physical Progress
                                </a>
                            @elseif($is_msts && $project->contract->pwd_boqs->count())
                                @isset($view)
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('mis.project.detail.milestones.boq', $project->id) }}">
                                        <i class="fa fa-eye mr-1"></i> View BOQ Sheet
                                    </a>
                                @else
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('projectLevel.update.boqsheet', $project->id) }}">
                                        <i class="fa fa-pencil mr-1"></i> Update BOQ Sheet
                                    </a>
                                @endisset
                            @endif

                            {{-- Site Images --}}
                            @if ($is_msts && $project->contract->ms_type)
                                @if (auth()->user()->role->level === 'THREE')
                                    <a class="btn btn-md btn-info"
                                        href="{{ route('site.milestone.images', $project->id) }}">
                                        View Site Images
                                    </a>
                                @elseif(auth()->user()->role->level === 'ONE')
                                    <a class="btn btn-md btn-info"
                                        href="{{ url('admin/milestone/site/images/' . $project->id) }}">
                                        View Site Images
                                    </a>
                                @endif
                            @endif

                            {{-- Financial Progress --}}
                            @if (isset($view))
                                <a class="btn btn-sm btn-primary"
                                    href="{{ route('mis.project.detail.milestone.financial', [$project->id, $project->id]) }}">
                                    <i class="fa fa-eye mr-1"></i> View Financial Progress
                                </a>
                            @else
                                <a class="btn btn-sm btn-primary"
                                    href="{{ route('projectLevel.financial.view', $project->id) }}">
                                    <i class="fa fa-pencil mr-1"></i> Financial Progress
                                </a>
                            @endif
                        </div>
                    </div>
                   

                    <div class="x_content">
                        {{-- Financial Summary Block --}}
                         <div class="financial-summary">
                            <div><strong>Financial Progress:</strong> {{ $project->progress_percentage }}%</div>
                            <div><strong>Physical Progress:</strong> {{ $totalProgress }}%</div>
                           
                            <div>ðŸ“„ <strong>Estimated Budget:</strong> â‚¹{{ number_format($project->estimate_budget, 2) }}
                            </div>
                        </div>

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
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    {{-- Milestone Edit Modal --}}
    <div class="editmodal modal" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form autocomplete="off" id="editform" data-method="POST" data-action=""
                    class="form-horizontal form-label-left ajax-form-edit">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update MileStone Progress</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="project_id" value="{{ $id }}" />

                        <div class="form-group row">
                            <label class="control-label col-md-3">Milestone Name</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" data-key="name" readonly>
                                <p class="error" id="editerror-name"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">Physical Progress</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="physical_progress"
                                    data-key="physical_progress" placeholder="Physical progress..">
                                <p class="error" id="editerror-physical_progress"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">Financial Progress</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="financial_progress"
                                    data-key="financial_progress" placeholder="Financial progress..">
                                <p class="error" id="editerror-financial_progress"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="submit-btn" type="submit" class="btn btn-success">
                            <span class="loader" id="loader" style="display: none;"></span> Submit
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
