@extends('layouts.admin')

@section('content')
    <style>
        table.table thead th,
        table.table tbody td {
            vertical-align: middle;
        }
    </style>

    <div class="row">
        <div class="col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>U-PREPARE | Project Report - {{ ucfirst($type) }} Safeguards
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Project Reports - {{ ucfirst($type) }} Safeguards Compliances</a>
                    </li>
                </ol>
                {{-- <p style="font-size:15px;" class="pull-right"><b>(* Click on the project name for a detailed report)</b></p> --}}
            </nav>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-md-8"></div>
        <div class="col-md-2 text-right">
            <label class="m-0">Select Phase:</label>
        </div>
        <div class="col-md-2">
            <select name="phase" id="switchPhase" class="form-control" autocomplete="off">
                @foreach ($phases as $fphase)
                    @if( ($type == 'social' && $fphase->id < 3) || ($type == 'environment' && $fphase->id < 4) )
                        <option value="{{ $fphase->slug }}" @selected($phase->slug == $fphase->slug)>{{ $fphase->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="x_panel">
                <div class="x_content">
                    <h3 class="pdf-heading text-center" style="text-decoration:underline;">U-PREPARE | Project Report -{{ ucfirst($type) }} Safeguards Compliances</h3>

                    <div class="table-responsive">
                        <table id="dataTable" class="w-100 table table-striped table-bordered">
                            <thead>
                                <tr rowspan="3">
                                    <th rowspan="3">S. No.</th>
                                    <th rowspan="3">Project / Code</th>
                                    <th rowspan="3">Project Id</th>
                                    <th rowspan="3">Type</th>
                                    <th rowspan="3">District</th>
                                    <th rowspan="3">FPIU</th>
                                    @foreach($safeguard_rules as $rule)
                                        <th colspan="3">{{ $rule->name }}</th>
                                    @endforeach
                                    <th rowspan="3">Total Progress (%)</th>
                                </tr>
                                <tr>
                                    @foreach($safeguard_rules as $key => $rule)
                                        <th colspan="3">{{ $rule->children->count() }}</th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($safeguard_rules as $key => $rule)
                                        <th>Complied</th>
                                        <th>Non Complied</th>
                                        <th>% Compliance</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $key => $project)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->number }}</td>
                                    <td>{{ $project->subcategory }}</td>
                                    <td>{{ $project->district_name }}</td>
                                    <td></td>
                                    @foreach($safeguard_rules as $rule)
                                        @php $compliant  = 0; @endphp
                                        @php $ncompliant = 0; @endphp
                                        @foreach($rule->children as $r_child)
                                            @php
                                                $entry = $r_child->entries()->where('project_id', $project->id)->first();
                                                if($entry && $entry->applicable)
                                                {
                                                    $compliant++;
                                                }
                                                else
                                                {
                                                    $ncompliant++;
                                                }
                                            @endphp
                                        @endforeach
                                        <td class="text-center">{{ $compliant }}</td>
                                        <td class="text-center">{{ $ncompliant }}</td>
                                        <td class="text-center">{{ $compliant ? number_format( ($compliant / $rule->children->count()) * 100) : 0 }}%</td>
                                    @endforeach
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let $table = new DataTable('#dataTable', {
            pageLength: 5,
            lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
        });

        $('#switchPhase').on('change', function() {
            let $pv = '{{ $phase->slug }}';
            let $cv = $(this).val();

            if($pv !== $cv) {
                let $url = `{{ route("mis.report.env-soc", $type) }}?phase=${$cv}`;

                redirect($url);
            }
        });
    </script>
@endsection
