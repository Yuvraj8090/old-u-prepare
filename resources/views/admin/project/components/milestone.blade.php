<div class="x_panel">
    <div class="x_title d-flex align-items-center justify-content-between">
        <h5 style="font-weight:550;">PROJECT MILESTONE CHART</h5>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box chart-container" style="background: #fff; border-radius: 5px; padding: 15px;">
                    <div id="dualAxisLineChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="x_panel">
    <div class="x_title d-flex align-items-center justify-content-between">
        <h5 style="font-weight:550;">PROJECT MILESTONE DETAILS</h5>
        <a class="btn btn-sm btn-primary" href="{{ route('mis.project.detail.milestones', $data->id) }}">
            <i class="fa fa-eye mr-1"></i> View Details
        </a>
    </div>
    <div class="x_panel">
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered text-center"
                            style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th class="text-center">Milestone</th>
                                    <th class="text-center">Budget</th>
                                    <th class="text-center">Work %</th>
                                    <th class="text-center">Planned Dates</th>
                                    <th class="text-center">Amended Dates</th>
                                    <th style="width: 120px;">Physical Progress</th>
                                    <th style="width: 150px;">Financial Progress</th> <!-- Increased width -->
                                </tr>
                            </thead>
                            @if (count($milestones) > 0)
                                @php
                                    $total_budget = $contracts->procurement_contract;
                                    $total_centag = 0;
                                    $total_pcompc = 0;
                                    $total_fcompc = 0;
                                    $total_used_amount = 0;
                                    $totalPhysicalProgress = $contracts->physical_progress->sum('progress') ?? 0;
                                    $remainingProgress = $totalPhysicalProgress;
                                @endphp
                                <tbody>
                                   @php
    $totalBudget = $contracts->procurement_contract;
    $remainingAmountSpent = $milestoneFinancial['total_amount_spent']; // total spent
    $remainingProgress = $contracts->physical_progress->sum('progress') ?? 0;

    $total_pcompc = 0;
    $total_fcompc = 0;
@endphp

@foreach($milestones as $key => $datum)
    @php
        // ----- FINANCIAL PROGRESS -----
        if ($remainingAmountSpent >= $datum->budget) {
            $milestoneSpent = $datum->budget;
            $remainingAmountSpent -= $datum->budget;
        } else {
            $milestoneSpent = $remainingAmountSpent;
            $remainingAmountSpent = 0;
        }

        $milestone_financial_progress = $datum->budget > 0
            ? ($milestoneSpent / $datum->budget) * 100
            : 0;

        $financialColor = $milestone_financial_progress >= 90 ? 'green' :
                         ($milestone_financial_progress >= 70 ? 'blue' :
                         ($milestone_financial_progress >= 50 ? 'yellow' : 'red'));

        $total_fcompc += ($datum->percent_of_work * $milestone_financial_progress) / 100;

        // ----- PHYSICAL PROGRESS -----
        if ($remainingProgress > 0) {
            $milestone_physical_progress = min(100, ($remainingProgress / $datum->percent_of_work) * 100);
            $remainingProgress -= $datum->percent_of_work;
        } else {
            $milestone_physical_progress = 0;
        }

        $physicalColor = $milestone_physical_progress >= 90 ? 'green' :
                        ($milestone_physical_progress >= 70 ? 'blue' :
                        ($milestone_physical_progress >= 50 ? 'yellow' : 'red'));

        $total_pcompc += ($datum->percent_of_work * $milestone_physical_progress) / 100;
    @endphp

    <tr>
        <td class="text-center">M{{ ++$key }}</td>
        <td class="font-weight-bold">{{ $datum->name }}</td>
        <td class="text-right">₹{{ number_format($datum->budget) }}</td>
        <td class="text-center">{{ $datum->percent_of_work }}%</td>

        <!-- Planned Dates -->
        <td>
            <div class="d-flex flex-column">
                <span><strong>Start:</strong> {{ date('d M Y', strtotime($datum->start_date)) }}</span>
                <span><strong>End:</strong> {{ date('d M Y', strtotime($datum->end_date)) }}</span>
            </div>
        </td>

        <!-- Amended Dates -->
        <td>
            <div class="d-flex flex-column">
                <span><strong>Start:</strong> {{ $datum->amended_start_date ? date('d M Y', strtotime($datum->amended_start_date)) : 'N/A' }}</span>
                <span><strong>End:</strong> {{ $datum->amended_end_date ? date('d M Y', strtotime($datum->amended_end_date)) : 'N/A' }}</span>
            </div>
        </td>

        <!-- Physical Progress -->
        <td class="project_progress">
            <div class="progress progress_sm">
                <div class="progress-bar bg-{{ $physicalColor }}"
                     style="width:{{ $milestone_physical_progress }}%;">
                </div>
            </div>
            <small>{{ round($milestone_physical_progress, 1) }}% Complete</small>
        </td>

        <!-- Financial Progress -->
        <td class="project_progress">
            <div class="d-flex flex-column">
                <div class="progress progress_sm mb-1">
                    <div class="progress-bar bg-{{ $financialColor }}"
                         style="width:{{ $milestone_financial_progress }}%;">
                    </div>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="font-weight-bold">
                        ₹{{ number_format($milestoneSpent, 2) }}
                    </span>
                </div>
            </div>
        </td>
    </tr>
@endforeach

                                </tbody>
                                @php
                                    $total_milestone_spending_percentage =
                                        $total_budget > 0
                                            ? ($milestoneFinancial['total_amount_spent'] / $total_budget) * 100
                                            : 0;
                                @endphp
                                <tfoot class="bg-light">
                                    <tr>
                                        <td colspan="2" class="text-right font-weight-bold">TOTAL</td>
                                        <td class="text-right font-weight-bold">₹{{ number_format($total_budget) }}
                                        </td>
                                        <td class="text-center font-weight-bold">{{ $total_centag }}%</td>
                                        <td colspan="2"></td>
                                        <td class="text-center font-weight-bold">
                                            ({{ round($total_pcompc, 1) }}%)
                                        </td>


                                        <td class="text-center font-weight-bold">
                                            ₹{{ number_format($milestoneFinancial['total_amount_spent'], 2) }}<br>
                                            <small>({{ number_format($total_milestone_spending_percentage, 2) }}%
                                                )</small>
                                        </td>
                                    </tr>
                                </tfoot>
                            @else
                                <tbody>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No milestones found</td>
                                    </tr>
                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
