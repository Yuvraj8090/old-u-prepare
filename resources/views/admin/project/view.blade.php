@php
    $totalProgress = 0;
@endphp
@extends('layouts.admin')

@section('content')
    <div>
        <div style="width:100%;" class="page-title">
            <div style="width:100%;" class="title_left">
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                <h4>Project Name : {{ $data->name }} | Project Id- {{ $data->number }} </h4>
            </div>
        </div>

        <div class="clearfix"></div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ url('dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('project.index') }}">Projects</a>
                </li>
                <li class="breadcrumb-item active">
                    <a href="#">Projects Details</a>
                </li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <!-- code 9 feb change for design change -->
                <div class="row">
                    <div class="col-md-4">
                        @include('admin.project.components.new.project-view-details')
                        <br/>
                        @include('admin.project.components.new.approve-dec-hpc-details')
                        <br/>
                        @if(!empty($contracts))
                  



                            <div class="col-md-12">
                                @include('admin.project.components.contractor')
                            </div>
                        @else
                            <div class="col-md-12">
                                @include('admin.project.components.empty',['headline' => 'PROJECT CONTRACT & CONTRACTOR DETAILS' , 'content' => 'Project Contract & Contractor step in process...'])
                            </div>
                        @endif
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            @if(!empty($defineProject))
                                @include('admin.project.components.procurement')
                            @else
                                @include('admin.project.components.empty', ['headline' => 'PROJECT PROCUREMENT' , 'content' => 'Project Procurement step in process...'])
                            @endif

                            @if(!empty($contracts))
                                <div class="col-md-12">
                                    @include('admin.project.components.contract')
                                </div>
                            @else
                                <div class="col-md-12">
                                    @include('admin.project.components.empty', ['headline' => 'PROJECT CONTRACT & CONTRACTOR DETAILS' , 'content' => 'Project Contract & Contractor step in process...'])
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- @if(auth()->user()->role->department != "PROCUREMENT") --}}
                        <!-- code added 9 feb -->
                        <div class="col-md-12">
                            @if(!empty($defineProject->scope_of_work))
                                @include('admin.project.components.define-project')
                            @else
                                @include('admin.project.components.empty',['headline' => 'DEFINE PROJECT' , 'content' => ' Define Project step in process...'])
                            @endif
                        </div>

                        
                        <div class="col-md-12 col-lg-12">
                         
                            @if(!empty($milestones) && count($milestones) > 0)
    @include('admin.project.components.milestone', [
        'milestones' => $milestones,
        'milestoneFinancial'=> $milestoneFinancial,
        'physicalProgress' => $physicalProgress
    ])
@else
<div class="x_panel">
    <div class="x_title d-flex align-items-center justify-content-between">
        <h5 style="font-weight:550;">PROJECT MILESTONES (Based on BOQ)</h5>
        <div class="clearfix"></div>
       
        <a class="btn btn-sm btn-primary" href="/mis/project/details/{{ $data->id}}/milestones/boq-sheet">
            <i class="fa fa-eye mr-1"></i> View Details
        </a>
   
    </div>

    <div class="x_content">
        

        @php
            $dummyMilestones = [
                ['label' => 'Milestone 1', 'percent_of_work' => 20],
                ['label' => 'Milestone 2', 'percent_of_work' => 30],
                ['label' => 'Milestone 3', 'percent_of_work' => 50],
            ];

            $totalBoq = $contracts->total_boq_amount ?? 0;
            $usedBoq = $contracts->used_boq_amount ?? 0;
            $totalWorkDone = $contracts->boq_used_percentage ?? 0;
            $remaining = $totalWorkDone ;
        @endphp

        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Milestone</th>
                        <th>Budget</th>
                        <th>Work %</th>
                        <th>Physical Progress</th>
                        <th>Financial Progress</th>
                    </tr>
                </thead>
                <tbody>
                   
          @php
    $gstRate = 0.18;
    $totalBoq = $contracts->total_boq_amount ?? 0;
    $totalWorkDone = $contracts->boq_used_percentage ?? 0;
    $contractValueWithGST = $totalBoq + ($totalBoq * $gstRate);

    $dummyMilestones = [
        ['label' => 'Milestone 1', 'percent_of_work' => 20],
        ['label' => 'Milestone 2', 'percent_of_work' => 30],
        ['label' => 'Milestone 3', 'percent_of_work' => 50],
    ];

    // Amount actually spent
    $amountSpentTotal = $milestoneFinancial['total_amount_spent'] ?? 0;
    $runningUsedAmount = $amountSpentTotal;
    $remainingWork = $totalWorkDone;

    $total_physical = 0;
    $total_financial = 0;
@endphp

@foreach($dummyMilestones as $i => $milestone)
    @php
        $budgetShare = $contractValueWithGST * ($milestone['percent_of_work'] / 100);

        // Divide actual spent value milestone-wise (waterfall logic)
        $amountSpent = min($runningUsedAmount, $budgetShare);
        $runningUsedAmount -= $amountSpent;

        $financial_progress = $budgetShare > 0
            ? ($amountSpent / $budgetShare) * 100
            : 0;

        $physical_progress = $remainingWork > 0
            ? min(100, ($remainingWork / $milestone['percent_of_work']) * 100)
            : 0;
        $remainingWork -= $milestone['percent_of_work'];

        $total_physical += ($milestone['percent_of_work'] * $physical_progress) / 100;
        $total_financial += ($milestone['percent_of_work'] * $financial_progress) / 100;

        $physicalColor = $physical_progress >= 90 ? 'success' :
                         ($physical_progress >= 70 ? 'info' :
                         ($physical_progress >= 50 ? 'warning' : 'danger'));

        $financialColor = $financial_progress >= 90 ? 'success' :
                          ($financial_progress >= 70 ? 'info' :
                          ($financial_progress >= 50 ? 'warning' : 'danger'));
    @endphp

    <tr>
        <td>M{{ $i + 1 }}</td>
        <td class="font-weight-bold">{{ $milestone['label'] }}</td>
        <td class="text-right">₹{{ number_format($budgetShare, 2) }}</td>
        <td>{{ $milestone['percent_of_work'] }}%</td>
        <td>
            <div class="progress progress_sm">
                <div class="progress-bar bg-{{ $physicalColor }}"
                     role="progressbar"
                     style="width: {{ round($physical_progress, 1) }}%;"></div>
            </div>
            <small>{{ round($physical_progress, 1) }}% Complete</small>
        </td>
        <td>
            
         
            <span class="font-weight-bold">₹{{ number_format($amountSpent, 2) }}</span>
        </td>
    </tr>
@endforeach

@php
    $total_budget = $contracts->procurement_contract ?? 0;
    $total_milestone_spending_percentage = $total_budget > 0
        ? ($amountSpentTotal / $total_budget) * 100
        : 0;
@endphp

<tfoot class="bg-light font-weight-bold">
    <tr>
        <td colspan="2" class="text-right">TOTAL</td>
        <td class="text-right">₹{{ number_format($contractValueWithGST, 2) }}</td>
        <td class="text-center">100%</td>
        <td class="text-center">{{ round($total_physical, 1) }}%</td>
        <td class="text-center">
            ₹{{ number_format($amountSpentTotal, 2) }}<br>
            ({{ round($total_milestone_spending_percentage, 1) }}%)
        </td>
    </tr>
    <tr>
       
    </tr>
</tfoot>

                    
                </tbody>
            </table>
        </div>

        
    </div>
</div>
@endif


                        </div>
                    {{-- @endif --}}
                </div>
            </div>

            {{-- @if(auth()->user()->role->name == "PMU-LEVEL-ONE" || auth()->user()->hasRole('Admin')) --}}
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title d-flex aic justify-content-between">
                                    <h5 style="font-weight:550;">QUALITY REPORTS</h5>
                                    <a class="btn btn-sm btn-info" href="{{ route('mis.project.detail.quality', [$data->id, 'material']) }}">View Details</a>
                                </div>
                                @include('admin.project.components.quality-reports')
                                
                            </div>
                        </div>
                    </div>
                </div>
            {{-- @endif --}}

            {{-- @if($data->category_id == '1' && ( auth()->user()->role->affilaited == "0" && auth()->user()->role->name == "PMU-LEVEL-ONE" ) || auth()->user()->role->id == 1) --}}
                @if(isset($data->EnvironmentDefineProject->define_project))
                    @include('admin.project.components.environment')
                @else
                    @include('admin.project.components.empty',['headline' => 'PROJECT ENVIRONMENT DETAILS' , 'content' => 'Project Environment Details & Milestones step in process...'])
                @endif

                @include(
                    'admin.project.components.enviornmentSocialTestReport',
                    [
                        'data'    => $environmentCalculation,
                        // 'module'  => '1',
                        'module'  => 'environment',
                        'project' => $data,
                        'env_soc' => $envSafeguards,
                        'headline'=> 'ENVIRONMENT SAFEGUARD ACTIVITIES',
                    ]
                );

                @if(isset($data->SocialDefineProject->define_project))
                    @include('admin.project.components.social')
                @else
                    @include('admin.project.components.empty',['headline' => 'PROJECT SOCIAL DETAILS' , 'content' => 'Project SOCIAL Details & Milestones step in process...'])
                @endif

                @include(
                    'admin.project.components.enviornmentSocialTestReport',
                    [
                        'data'    => $socialCalculation,
                        // 'module'  => '2',
                        'module'  => 'social',
                        'project' => $data,
                        'env_soc' => $socSafeguards,
                        'headline'=> 'SOCIAL SAFEGUARD ACTIVITIES',
                    ]
                );
            {{-- @endif --}}
        </div>
    </div>
@stop


@section('script')
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });

    function drawChart() {
        const data = new google.visualization.DataTable();

        data.addColumn('string', 'Month/Year');
        data.addColumn('number', 'Physical Progress');
        data.addColumn('number', 'Financial Progress');
        data.addRows(@json($mileStonechartData));

        const options = {
            hAxis: {
                title: 'Month/Year',
                textStyle: { fontSize: 10 },
                slantedText: true,
                slantedTextAngle: 45,
            },
            vAxis: {
                title: 'Percentage Completed',
                minValue: 0,
                maxValue: 100,
                format: '#\'%\'',
                textStyle: { fontSize: 10 },
            },
            curveType: 'function', // <-- Smooth the line for S-curve effect
            series: {
                0: {
                    pointSize: 5,
                    pointShape: 'circle',
                    lineWidth: 2,
                    color: '#4285F4' // Blue for Physical Progress
                },
                1: {
                    pointSize: 5,
                    pointShape: 'circle',
                    lineWidth: 2,
                    color: '#34A853' // Green for Financial Progress
                },
            },
            chartArea: {
                left: '10%',
                top: '15%',
                width: '85%',
                height: '70%'
            },
            legend: {
                position: 'top',
                textStyle: { fontSize: 11 }
            },
            width: '100%',
            height: 350,
            backgroundColor: 'transparent',
            animation: {
                duration: 1000,
                easing: 'out',
                startup: true
            }
        };

        const chart = new google.visualization.LineChart(document.getElementById('dualAxisLineChart'));

        function resizeChart() {
            chart.draw(data, options);
        }

        window.addEventListener('resize', resizeChart);
        chart.draw(data, options);
    }

    google.charts.setOnLoadCallback(drawChart);

    // Format currency values (if applicable)
    document.querySelectorAll('.amtbf').forEach((el, i) => {
        el.innerText = currencyFormatter.format(el.innerText);
    });
</script>
@stop
