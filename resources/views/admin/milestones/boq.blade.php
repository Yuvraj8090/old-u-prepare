@extends('layouts.admin')

@section('content')
    <style>
        table.table-boq-sheet .num {
            text-align: right;
        }

        table.table-boq-sheet tbody td {
            vertical-align: middle;
        }
    </style>

    {{-- @php $is_msts = $project->contract && !is_null($project->contract->ms_type) @endphp --}}

    <div>
        <div style="width:100% !important;" class="page-title">
            <div  style="width:100% !important;" class="title_left">
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                <h3>{{ isset($view) ? 'View' : 'Update' }} BOQ Sheet || Project: {{ $project->name ?? "N/A" }}</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('dashboard') }}">Home</a>
                </li>
                @isset($view)
                    <li class="breadcrumb-item">
                        <a href="{{ route('mis.projects') }}">Project Monitoring</a>
                    </li>
                @endisset
                <li class="breadcrumb-item">
                    <a href="{{ isset($view) ? route('mis.project.detail', $project->id) : route('mis.project.tracking.progress.update', 'physical') }}">Project{{ isset($view) ? ' Details' : 's' }}</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="{{ isset($view) ? route('mis.project.detail.milestones', $project->id) : url('update/project/create/' . $project->id) }}">{{ isset($view) ? 'View' : 'Update' }} Milestones</a>
                </li>
                <li class="breadcrumb-item active">{{ isset($view) ? 'View' : 'Update' }} BOQ Sheet</li>
            </ol>
        </nav>

        @include('admin.error')

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="x_panel msxp">
                    <div class="x_title d-flex align-items-center justify-content-between">
                        <h2>{{ isset($view) ? '' : 'Update' }} BOQ Sheet</h2>
                        @if(!isset($edit_sheet) && $project->contract->ms_type)
                            @php $month = date('n'); @endphp
                            <input class="form-control w-auto" type="date" name="entry-date" value="{{ date('Y-m-d') }}" autocomplete="off" >
                        @elseif(isset($edit_sheet))
                            <a href="#" class="btn btn-anr btn-info">Add New Row</a>
                        @endif
                    </div>

                    @if(isset($edit_sheet))
                        <form class="anr ajax-form" data-method="POST" data-action="{{ route('contract.boqsheet.record.add') }}" style="display: none;">
                            @csrf
                            <input type="hidden" name="contract_id" value="{{ $project->contract->id }}">
                            <div class="card mb-4">
                                <div class="card-body p-5">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="control-label">S. No.</label>
                                            <input class="form-control" name="s_no" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="control-label">Unit</label>
                                            <input class="form-control" name="unit" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="control-label">Quantity</label>
                                            <input class="form-control" name="qty" required>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="control-label">Rate</label>
                                            <input class="form-control" name="rate" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="control-label">Item Name/Detail</label>
                                            <textarea class="form-control" name="item" required></textarea>
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <a href="#" class="btn btn-primary m-0 text-white mr-3 btn-can">Close</a>
                                            <button class="btn btn-success m-0 btn-snrd">Add Entry to Boq Sheet</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif

                    <div class="x_content"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        let $view = {{ isset($view) ? 1 : 0 }};
        @if($project->contract->ms_type)
        let $ien = "{{ isset($edit_sheet) ? 'sheet' : 'daily' }}";
        fetchBOQData(Number({{ $project->contract->id }}), $ien, '', $view);

        let month = {{ date('n') }};

        $('input[name="entry-date"]').on('change', function(e) {
            fetchBOQData({{ $project->contract->id }}, $ien, $(this).val(), $view);
        })

        $('.btn-anr').on('click', function(e) {
            e.preventDefault();

            $('form.anr').slideDown('normal', function() {

            });
        });

        $('form.anr .btn-can').on('click', function(e) {
            e.preventDefault();

            $(this).closest('form').slideUp('normal', function() {

            });
        })
        @endif
    </script>
@endsection
