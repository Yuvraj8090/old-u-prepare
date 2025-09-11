@extends('layouts.admin')

@section('header_styles')
    <style>
        nav #nav-tab.nav-tabs {
            border: none;
        }

        nav #nav-tab button {
            padding: 8px 30px;
            font-size: 24px;
            min-width: 96px;
            margin-right: 0;
            border-radius: 0;
            margin-bottom: 0;
            background-color: transparent;
        }

        nav #nav-tab button:focus {
            outline: none;
        }

        nav #nav-tab button:hover,
        nav #nav-tab button.active {
            border-color: green;
            border-radius: 5px 5px 0 0;
            background-color: white;
            border-bottom-color: white;
        }

        .tab-content .tab-pane {
            background-color: white;
        }

        .tab-content .tab-pane.active {
            padding: 30px 0;
            margin-top: -1px;
            border-top: 1px solid green;
            border-radius: 0 0 5px 5px;
        }

        table tbody tr th:first-child {
            font-size: 24px !important;
        }

        table.table.stats tbody td {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h1 class="text-center my-3">Dashboard {{ $userDepartmentName }}</h1>
        </div>
    </div>
   



    @php
        use App\Models\MIS\Department;

        $departments = Department::where('is_active', 1)->get()->keyBy('id');
        $userDeptId = auth()->user()->department_id;
        $isAdmin = in_array(auth()->user()->username, ['admin', 'PM-PWD']); // adjust for your app
    @endphp

    {{-- <div class="row" id="projectCards">
        @foreach (['epc' => 'EPC Projects', 'item' => 'Item Rate Projects'] as $type => $label)
            @foreach ($tableData->all->$type->projects as $project)
                @php
                    $deptId = $project->dept_id;
                    $dept = $departments->get($deptId);
                    $deptName = $dept->name ?? 'Unknown';
                    $boqCount = $project->boq_count ?? 0;

                    // Skip project if department is not active
                    if (!$dept || $dept->is_active == 0) {
                        continue;
                    }

                    // Skip if not admin and not same department
                    if (!$isAdmin && $userDeptId != $deptId) {
                        continue;
                    }
                @endphp

                <div class="col-md-6 col-xl-4 mb-4 project-card dept-{{ $deptId }}">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary text-truncate" title="{{ $project->name }}">
                                {{ \Illuminate\Support\Str::limit($project->name, 40) }}
                            </h5>
                            <p class="mb-1 small"><strong>Project ID:</strong> {{ $project->unique_id }}</p>
                            <p class="mb-1 small"><strong>Created On:</strong>
                                {{ \Carbon\Carbon::parse($project->created_at)->format('d M, Y') }}</p>
                            <p class="mb-1 small"><strong>Type:</strong> {{ strtoupper($project->project_type) }}</p>
                            <p class="mb-1 small"><strong>Category:</strong> {{ $project->category->name ?? 'N/A' }}</p>
                            <p class="mb-1 small"><strong>Sub-category:</strong> {{ $project->subcategory ?? 'N/A' }}</p>
                            <p class="mb-1 small"><strong>District:</strong> {{ $project->district_name ?? 'N/A' }}</p>
                            <p class="mb-1 small"><strong>Contract Value:</strong>
                                ₹{{ number_format($project->estimate_budget) }}</p>
                            <p class="mb-1 small"><strong>HPC Date:</strong>
                                {{ \Carbon\Carbon::parse($project->hpc_approval_date)->format('d-m-Y') }}</p>
                            <p class="mb-0 small"><strong>Department:</strong> {{ $deptName }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div> --}}


   @php
    $userDeptId = auth()->user()->department_id;
    $isAdmin = in_array(auth()->user()->username, ['admin', 'PM-PWD']);

    $mergedProjects = collect($tableData->all->epc->projects)
        ->merge($tableData->all->item->projects)
        ->filter(function ($project) use ($departments, $userDeptId, $isAdmin) {
            $dept = $departments->get($project->dept_id);
            if (!$dept || $dept->is_active == 0) return false;
            if (!$isAdmin && $userDeptId != $project->dept_id) return false;
            return ($project->estimate_budget ?? 0) > 0;
        });
@endphp

@if ($userDeptId === 0 || $userDeptId === 1)

    
<div class="row mt-4">
        <!-- Department Budget Chart + Table -->

        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">Component Budget Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="componentPieChart" style="width:100%; height:350px;"></div>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Component</th>
                                    <th class="text-right">Amount (₹ Cr)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($comps as $comp)
                                    <tr>
                                        <td>{{ $comp->name }}</td>
                                        <td class="text-right">{{ number_format($comp->amt_inr / 10000000, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td class="text-right">{{ number_format($comps->sum('amt_inr') / 10000000, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- PIU Budget Chart + Table -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">PIU Budget Distribution</h5>
                </div>
                <div class="card-body">
                    <div id="piuPieChart" style="width:100%; height:350px;"></div>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>PIU</th>
                                    <th class="text-right">Amount (₹ Cr)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pius as $piu)
                                    <tr>
                                        <td>{{ $piu->name }}</td>
                                        <td class="text-right">{{ number_format($piu->amt_inr / 10000000, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td class="text-right">{{ number_format($pius->sum('amt_inr') / 10000000, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Component Budget Chart + Table -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">PIU wise Contracts Signed</h5>
                </div>
                <div class="card-body">
                    <div id="departmentPieChart" style="width:100%; height:350px;"></div>
                    <div class="table-responsive mt-3">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Department</th>
                                    <th class="text-right">Total Projects</th>
                                    <th class="text-right">Contract Signed</th>
                                    <th class="text-right">Budget (₹ Cr)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departmentBudgets as $dept)
    @if (!in_array($dept['name'], ['PIU', 'PMU']))
        {{-- Optional: Apply department-specific logic based on name or ID --}}
        @php
            $departmentLinks = [
                'PWD' => 8,
                'RWD' => 9,
                'FOREST' => 13,
                'USDMA' => 14,
            ];
            $linkDeptId = $departmentLinks[$dept['name']] ?? $dept['id'];
        @endphp

        <tr>
            <td>
                <a href="/mis/projects?department={{ $linkDeptId }}" target='_blank'>{{ $dept['name'] }}</a>
            </td>
            <td class="text-right"><a href="/mis/projects?department={{ $linkDeptId }}" target='_blank'>{{ $dept['project_count'] }}</a></td>
            <td class="text-right"><a href="/mis/projects?department={{ $linkDeptId }}&category=&subcategory=&status=3" target='_blank'>{{ $dept['contracts_signed_count'] }}</a></td>
            <td class="text-right">{{ number_format($dept['contract_as'] / 10000000, 2) }}</td>
        </tr>
    @endif
@endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Budget Utilization Chart + Table -->
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title">PIU wise Budget Expenditure</h5>
                </div>
                <div class="card-body">
                    <div id="utilizationPieChart" style="width:100%; height:350px;"></div>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Department</th>

                                <th class="text-right">Budget (₹ Cr)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departmentBudgets as $dept)
                                @if (!in_array($dept['name'], ['PIU', 'PMU']))
                                    <tr>
                                        <td>{{ $dept['name'] }}</td>

                                        <td class="text-right">{{ number_format($dept['budget_used'] / 10000000, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   @elseif ($mergedProjects->count())
        <hr class="my-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold">Project Distribution by Department</h5>
            <div>
                <button class="btn btn-sm btn-outline-primary me-2" onclick="changeChart('PieChart')">Pie Chart</button>
                <button class="btn btn-sm btn-outline-secondary" onclick="changeChart('BarChart')">Bar Chart</button>
            </div>
        </div>

        <div id="departmentChart" style="width: 100%; height: 400px;"></div>
      <div class="col-md-4 mb-4">
    <div class="card shadow h-100 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="fas fa-chart-pie mr-2"></i> {{ $userDepartmentName }} - Projects & Contracts
            </h5>
        </div>
        <div class="card-body">
            <!-- Pie Chart -->
            <div id="userDepartmentPieChart" style="width: 100%; height: 350px;"></div>

            <!-- Table -->
            <div class="table-responsive mt-4">
                <table class="table table-sm table-bordered table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Department</th>
                            <th class="text-right">Total Projects</th>
                            <th class="text-right">Contracts Signed</th>
                            <th class="text-right">Budget (₹ Cr)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departmentBudgets as $dept)
                            @if (!in_array($dept['name'], ['PIU', 'PMU']) && $dept['name'] == $userDepartmentName)
                                @php
                                    $departmentLinks = [
                                        'PWD' => 8,
                                        'RWD' => 9,
                                        'FOREST' => 13,
                                        'USDMA' => 14,
                                    ];
                                    $linkDeptId = $departmentLinks[$dept['name']] ?? $dept['id'];
                                @endphp
                                <tr>
                                    <td>
                                        <a href="/mis/projects?department={{ $linkDeptId }}" target="_blank">
                                            {{ $dept['name'] }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="/mis/projects?department={{ $linkDeptId }}" target="_blank">
                                            {{ $dept['project_count'] }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        <a href="/mis/projects?department={{ $linkDeptId }}&status=3" target="_blank">
                                            {{ $dept['contracts_signed_count'] }}
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($dept['contract_as'] / 10000000, 2) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Google Pie Chart Script -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawUserDepartmentPieChart);

    function drawUserDepartmentPieChart() {
        const data = google.visualization.arrayToDataTable([
            ['Metric', 'Count'],
            @foreach ($departmentBudgets as $dept)
                @if (!in_array($dept['name'], ['PIU', 'PMU']) && $dept['name'] == $userDepartmentName)
                    ['Pending Projects', {{ $dept['project_count'] }} - {{ $dept['contracts_signed_count'] }}],
                    ['Contracts Signed', {{ $dept['contracts_signed_count'] }}],
                @endif
            @endforeach
        ]);

        const options = {
            title: '',
            pieHole: 0.4,
            legend: { position: 'bottom' },
            chartArea: { width: '90%', height: '80%' },
        };

        const chart = new google.visualization.PieChart(document.getElementById('userDepartmentPieChart'));
        chart.draw(data, options);
    }
</script>

    @else
        

  
    @endif



   

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        google.charts.load('current', {
            'packages': ['corechart']
        });
        google.charts.setOnLoadCallback(() => drawChart('PieChart'));

        function drawChart(type = 'PieChart') {
            const data = google.visualization.arrayToDataTable([
                ['Project Name', 'Contract Value'],
                @php
                    $mergedProjects = collect($tableData->all->epc->projects)->merge($tableData->all->item->projects);
                    $chartRows = [];

                    foreach ($mergedProjects as $project) {
                        $deptId = $project->dept_id;
                        $dept = $departments->get($deptId);

                        $isDeptActive = $dept && $dept->is_active;
                        $userDeptId = auth()->user()->department_id;
                        $isAdmin = in_array(auth()->user()->username, ['admin', 'PM-PWD']);

                        if (!$isDeptActive) {
                            continue;
                        }
                        if (!$isAdmin && $userDeptId != $deptId) {
                            continue;
                        }

                        $name = addslashes($project->name);
                        $value = $project->estimate_budget ?? 0;
                        if ($value > 0) {
                            $chartRows[] = '["' . $name . '", ' . $value . ']';
                        }
                    }

                    echo implode(',', $chartRows);
                @endphp
            ]);

            const options = {
                title: 'Project-wise Contract Value',
                pieHole: type === 'PieChart' ? 0.4 : 0,
                legend: {
                    position: 'bottom'
                },
                chartArea: {
                    width: '90%',
                    height: '75%'
                },
                tooltip: {
                    isHtml: true
                }
            };

            const chart = new google.visualization[type](document.getElementById('departmentChart'));
            chart.draw(data, options);
        }

        function changeChart(type) {
            drawChart(type);
        }
    </script>




    <script>
        document.getElementById('departmentSelect').addEventListener('change', function() {
            const selected = this.value;
            const cards = document.querySelectorAll('.project-card');

            cards.forEach(card => {
                if (selected === 'all' || card.classList.contains(selected)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>

   
  <div class="row">
        <div class="x_panel py-4 px-2">
            <div class="x_content mt-0">
                <div class="col-12">
                    <nav>
                        <div id="nav-tab" class="nav nav-tabs" role="tablist">
                            <button id="nav-all-tab" class="nav-link active" data-toggle="tab" data-target="#nav-all"
                                type="button" role="tab" aria-controls="nav-all" aria-selected="true">All</button>
                            <button id="nav-bridges-tab" class="nav-link" data-toggle="tab" data-target="#nav-bridges"
                                type="button" role="tab" aria-controls="nav-bridges"
                                aria-selected="false">Bridges</button>
                            <button id="nav-slopes-tab" class="nav-link" data-toggle="tab" data-target="#nav-slopes"
                                type="button" role="tab" aria-controls="nav-slopes" aria-selected="false">Slopes
                                Protection</button>
                            <button id="nav-consult-tab" class="nav-link" data-toggle="tab" data-target="#nav-consult"
                                type="button" role="tab" aria-controls="nav-consult"
                                aria-selected="false">Consultancy</button>
                        </div>
                    </nav>
                    <div id="nav-tabContent" class="tab-content">
                        <div id="nav-all" class="tab-pane fade show active" role="tabpanel">
                            <div class="pane-content">
                                <div class="table-responsive">
                                    <table class="table stats table-bordered">
                                        <thead>
                                            <th>Contract Type</th>
                                            {{-- <th>Under Readiness</th> --}}
                                            <th>LOA Issued</th>
                                            <th>Contract Signed</th>
                                            <th>Start Date Given</th>
                                            <th>LOA to be issued</th>
                                            <th>Contract Signing Pending</th>
                                            <th>To be Re-bidded</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>EPC</th>
                                                {{-- <td></td> --}}
                                                       @php
    $totalEpcContracts = $departmentBudgets->sum('contracts_signed_epc');
    
    $totalitem = $departmentBudgets->sum('contracts_signed_item_rate');
@endphp

<td>{{ $totalEpcContracts }}</td>

                                               <td>{{ $totalEpcContracts }}</td>
                                                <td>{{ $tableData->all->epc->stats->startd }}</td>
                                                <td>{{ $tableData->all->epc->stats->loatbi }}</td>
                                                <td>{{ $tableData->all->epc->stats->signpn }}</td>
                                                <td>{{ $tableData->all->epc->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th>Item Rate</th>
                                                {{-- <td></td> --}}
                                                <td>{{ $totalitem }}</td>
                                                <td>{{ $totalitem }}</td>
                                                <td>{{ $tableData->all->item->stats->startd }}</td>
                                                <td>{{ $tableData->all->item->stats->loatbi }}</td>
                                                <td>{{ $tableData->all->item->stats->signpn }}</td>
                                                <td>{{ $tableData->all->item->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Total</th>
                                                <td>{{ $totalitem + $totalEpcContracts }}
                                                </td>
                                                <td>{{ $totalitem + $totalEpcContracts }}
                                                </td>
                                                <td>{{ $tableData->all->epc->stats->startd + $tableData->all->item->stats->startd }}
                                                </td>
                                                <td>{{ $tableData->all->epc->stats->loatbi + $tableData->all->item->stats->loatbi }}
                                                </td>
                                                <td>{{ $tableData->all->epc->stats->signpn + $tableData->all->item->stats->signpn }}
                                                </td>
                                                <td>{{ $tableData->all->epc->stats->tbereb + $tableData->all->item->stats->tbereb }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Cost (Cr.)</th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p>
                                        <em>*Each sub-project consists of a single work either a bridge or a slope
                                            protection work.</em>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="nav-bridges" class="tab-pane fade" role="tabpanel">
                            <div class="pane-content">
                                <div class="table-responsive">
                                    <table class="table stats table-bordered">
                                        <thead>
                                            <th>Contract Type</th>
                                            {{-- <th>Under Readiness</th> --}}
                                            <th>LOA Issued</th>
                                            <th>Contract Signed</th>
                                            <th>Start Date Given</th>
                                            <th>LOA to be issued</th>
                                            <th>Contract Signing Pending</th>
                                            <th>To be Re-bidded</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>EPC</th>
                                                {{-- <td></td> --}}
                                                <td>{{ $tableData->bridges->epc->stats->loai }}</td>
                                                <td>{{ $tableData->bridges->epc->stats->signed }}</td>
                                                <td>{{ $tableData->bridges->epc->stats->startd }}</td>
                                                <td>{{ $tableData->bridges->epc->stats->loatbi }}</td>
                                                <td>{{ $tableData->bridges->epc->stats->signpn }}</td>
                                                <td>{{ $tableData->bridges->epc->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th>Item</th>
                                                {{-- <td></td> --}}
                                                <td>{{ $tableData->bridges->item->stats->loai }}</td>
                                                <td>{{ $tableData->bridges->item->stats->signed }}</td>
                                                <td>{{ $tableData->bridges->item->stats->startd }}</td>
                                                <td>{{ $tableData->bridges->item->stats->loatbi }}</td>
                                                <td>{{ $tableData->bridges->item->stats->signpn }}</td>
                                                <td>{{ $tableData->bridges->item->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Total</th>
                                                <td>{{ $tableData->bridges->epc->stats->loai + $tableData->bridges->item->stats->loai }}
                                                </td>
                                                <td>{{ $tableData->bridges->epc->stats->signed + $tableData->bridges->item->stats->signed }}
                                                </td>
                                                <td>{{ $tableData->bridges->epc->stats->startd + $tableData->bridges->item->stats->startd }}
                                                </td>
                                                <td>{{ $tableData->bridges->epc->stats->loatbi + $tableData->bridges->item->stats->loatbi }}
                                                </td>
                                                <td>{{ $tableData->bridges->epc->stats->signpn + $tableData->bridges->item->stats->signpn }}
                                                </td>
                                                <td>{{ $tableData->bridges->epc->stats->tbereb + $tableData->bridges->item->stats->tbereb }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Cost (Cr.)</th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p>
                                        <em>*Each sub-project consists of a single work either a bridge or a slope
                                            protection work.</em>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="nav-slopes" class="tab-pane fade" role="tabpanel">
                            <div class="pane-content">
                                <div class="table-responsive">
                                    <table class="table stats table-bordered">
                                        <thead>
                                            <th>Contract Type</th>
                                            {{-- <th>Under Readiness</th> --}}
                                            <th>LOA Issued</th>
                                            <th>Contract Signed</th>
                                            <th>Start Date Given</th>
                                            <th>LOA to be issued</th>
                                            <th>Contract Signing Pending</th>
                                            <th>To be Re-bidded</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>EPC</th>
                                                {{-- <td></td> --}}
                                                <td>{{ $tableData->slopes->epc->stats->loai }}</td>
                                                <td>{{ $tableData->slopes->epc->stats->signed }}</td>
                                                <td>{{ $tableData->slopes->epc->stats->startd }}</td>
                                                <td>{{ $tableData->slopes->epc->stats->loatbi }}</td>
                                                <td>{{ $tableData->slopes->epc->stats->signpn }}</td>
                                                <td>{{ $tableData->slopes->epc->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th>Item</th>
                                                {{-- <td></td> --}}
                                                <td>{{ $tableData->slopes->item->stats->loai }}</td>
                                                <td>{{ $tableData->slopes->item->stats->signed }}</td>
                                                <td>{{ $tableData->slopes->item->stats->startd }}</td>
                                                <td>{{ $tableData->slopes->item->stats->loatbi }}</td>
                                                <td>{{ $tableData->slopes->item->stats->signpn }}</td>
                                                <td>{{ $tableData->slopes->item->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Total</th>
                                                <td>{{ $tableData->slopes->epc->stats->loai + $tableData->slopes->item->stats->loai }}
                                                </td>
                                                <td>{{ $tableData->slopes->epc->stats->signed + $tableData->slopes->item->stats->signed }}
                                                </td>
                                                <td>{{ $tableData->slopes->epc->stats->startd + $tableData->slopes->item->stats->startd }}
                                                </td>
                                                <td>{{ $tableData->slopes->epc->stats->loatbi + $tableData->slopes->item->stats->loatbi }}
                                                </td>
                                                <td>{{ $tableData->slopes->epc->stats->signpn + $tableData->slopes->item->stats->signpn }}
                                                </td>
                                                <td>{{ $tableData->slopes->epc->stats->tbereb + $tableData->slopes->item->stats->tbereb }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Cost (Cr.)</th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p>
                                        <em>*Each sub-project consists of a single work either a bridge or a slope
                                            protection work.</em>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="nav-consult" class="tab-pane fade" role="tabpanel">
                            <div class="pane-content">
                                <div class="table-responsive">
                                    <table class="table stats table-bordered">
                                        <thead>
                                            <th>Contract Type</th>
                                            {{-- <th>Under Readiness</th> --}}
                                            <th>LOA Issued</th>
                                            <th>Contract Signed</th>
                                            <th>Start Date Given</th>
                                            <th>LOA to be issued</th>
                                            <th>Contract Signing Pending</th>
                                            <th>To be Re-bidded</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>EPC</th>
                                                {{-- <td></td> --}}
                                                <td>{{ $tableData->consultancy->epc->stats->loai }}</td>
                                                <td>{{ $tableData->consultancy->epc->stats->signed }}</td>
                                                <td>{{ $tableData->consultancy->epc->stats->startd }}</td>
                                                <td>{{ $tableData->consultancy->epc->stats->loatbi }}</td>
                                                <td>{{ $tableData->consultancy->epc->stats->signpn }}</td>
                                                <td>{{ $tableData->consultancy->epc->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th>Item</th>
                                                {{-- <td></td> --}}
                                                <td>{{ $tableData->consultancy->item->stats->loai }}</td>
                                                <td>{{ $tableData->consultancy->item->stats->signed }}</td>
                                                <td>{{ $tableData->consultancy->item->stats->startd }}</td>
                                                <td>{{ $tableData->consultancy->item->stats->loatbi }}</td>
                                                <td>{{ $tableData->consultancy->item->stats->signpn }}</td>
                                                <td>{{ $tableData->consultancy->item->stats->tbereb }}</td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Total</th>
                                                <td>{{ $tableData->consultancy->epc->stats->loai + $tableData->consultancy->item->stats->loai }}
                                                </td>
                                                <td>{{ $tableData->consultancy->epc->stats->signed + $tableData->consultancy->item->stats->signed }}
                                                </td>
                                                <td>{{ $tableData->consultancy->epc->stats->startd + $tableData->consultancy->item->stats->startd }}
                                                </td>
                                                <td>{{ $tableData->consultancy->epc->stats->loatbi + $tableData->consultancy->item->stats->loatbi }}
                                                </td>
                                                <td>{{ $tableData->consultancy->epc->stats->signpn + $tableData->consultancy->item->stats->signpn }}
                                                </td>
                                                <td>{{ $tableData->consultancy->epc->stats->tbereb + $tableData->consultancy->item->stats->tbereb }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="color: #034ea2;">Cost (Cr.)</th>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <p>
                                        <em>*Each sub-project consists of a single work either a bridge or a slope
                                            protection work.</em>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
<script src="https://cdn.jsdelivr.net/npm/echarts@5.3.2/dist/echarts.min.js"></script>



@section('script')


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['corechart', 'table']
        });
        google.charts.setOnLoadCallback(drawAllCharts);

        function drawAllCharts() {
            drawDepartmentChart();
            drawPIUChart();
            drawComponentChart();
            drawUtilizationChart();
        }

        function drawDepartmentChart() {
            const data = google.visualization.arrayToDataTable([
                ['Department', 'Budget (₹ Cr)'],
                @foreach ($departmentBudgets as $dept)
                    @if (!in_array($dept['name'], ['PIU', 'PMU']))
                        ['{{ $dept['name'] }} ({{ $dept['project_count'] }} projects)',
                            {{ $dept['contract_as'] / 10000000 }}
                        ],
                    @endif
                @endforeach
            ]);
            const options = {
                title: '',
                pieHole: 0.4,
                pieSliceText: 'value',
                legend: {
                    position: 'right'
                },
                chartArea: {
                    width: '80%',
                    height: '80%'
                }
            };
            new google.visualization.PieChart(document.getElementById('departmentPieChart')).draw(data, options);
        }
         function drawDepartmentChartDe() {
            const data = google.visualization.arrayToDataTable([
                ['Department', 'Budget (₹ Cr)'],
                @foreach ($departmentBudgets as $dept)
                        @if (!in_array($dept['name'], ['PIU', 'PMU']) && $dept['name'] == $userDepartmentName)
                        ['{{ $dept['name'] }} ({{ $dept['project_count'] }} projects)',
                            {{ $dept['contract_as'] / 10000000 }}
                        ],
                    @endif
                @endforeach
            ]);
            const options = {
                title: '',
                pieHole: 0.4,
                pieSliceText: 'value',
                legend: {
                    position: 'right'
                },
                chartArea: {
                    width: '80%',
                    height: '80%'
                }
            };
            new google.visualization.PieChart(document.getElementById('departmentPieChartDe')).draw(data, options);
        }

        function drawPIUChart() {
            const data = google.visualization.arrayToDataTable([
                ['PIU', 'Budget (₹ Cr)'],
                @foreach ($pius as $piu)
                    ['{{ $piu->name }}', {{ $piu->amt_inr / 10000000 }}],
                @endforeach
            ]);
            const options = {
                title: '',
                pieHole: 0.4,
                pieSliceText: 'value',
                legend: {
                    position: 'right'
                },
                chartArea: {
                    width: '80%',
                    height: '80%'
                }
            };
            new google.visualization.PieChart(document.getElementById('piuPieChart')).draw(data, options);
        }

        function drawComponentChart() {
            const data = google.visualization.arrayToDataTable([
                ['Component', 'Budget (₹ Cr)'],
                @foreach ($comps as $comp)
                    ['{{ $comp->name }}', {{ $comp->amt_inr / 10000000 }}],
                @endforeach
            ]);
            const options = {
                title: '',
                pieHole: 0.4,
                pieSliceText: 'value',
                legend: {
                    position: 'right'
                },
                chartArea: {
                    width: '80%',
                    height: '80%'
                }
            };
            new google.visualization.PieChart(document.getElementById('componentPieChart')).draw(data, options);
        }

        function drawUtilizationChart() {
            const data = new google.visualization.DataTable();
            data.addColumn('string', 'Department');
            data.addColumn('number', 'Budget Used (Cr)');
            @foreach ($departmentBudgets as $dept)
                data.addRow(["{{ $dept['name'] }}", {{ $dept['budget_used'] / 10000000 }}]);
            @endforeach

            const pieOptions = {
                title: '',
                pieHole: 0.4,
                pieSliceText: 'value',
                legend: {
                    position: 'right'
                },
                chartArea: {
                    width: '90%',
                    height: '80%'
                }
            };

            const tableOptions = {
                showRowNumber: true,
                width: '100%',
                height: '100%'
            };

            new google.visualization.PieChart(document.getElementById('utilizationPieChart')).draw(data, pieOptions);
            new google.visualization.Table(document.getElementById('utilizationTable')).draw(data, tableOptions);
        }

        // Responsive redraw
        $(window).resize(function() {
            drawAllCharts();
        });
    </script>


    @if (false)
        @if (in_array(auth()->user()->role->department, [
                'PROCUREMENT',
                'USDMA-PROCUREMENT',
                'FOREST-PROCUREMENT',
                'RWD-PROCUREMENT',
                'PWD-PROCUREMENT',
                'PMU-PROCUREMENT',
            ]))
            <script type="text/javascript">
                google.charts.load('current', {
                    packages: ['corechart', 'bar']
                });
                google.charts.setOnLoadCallback(procurementChart);

                function procurementChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Project Category', 'Total Projects', 'Ongoing Projects', 'Completed Projecs'],
                        ['Work', {{ $procurement['workTotal'] }}, {{ $procurement['workTotalP'] }},
                            {{ $procurement['workTotalC'] }}
                        ],
                        ['Goods', {{ $procurement['goodsTotal'] }}, {{ $procurement['goodsTotalP'] }},
                            {{ $procurement['goodsTotalC'] }}
                        ],
                        ['Consultancy', {{ $procurement['consultancyTotal'] }}, {{ $procurement['consultancyTotalP'] }},
                            {{ $procurement['consultancyTotalC'] }}
                        ],
                        ['Others', {{ $procurement['othersTotal'] }}, {{ $procurement['othersTotalP'] }},
                            {{ $procurement['othersTotalC'] }}
                        ],
                    ]);

                    var options = {
                        vAxis: {
                            textPosition: 'none',
                            gridlines: {
                                color: 'transparent'
                            },
                            textStyle: {
                                color: 'white'
                            }
                        },
                        hAxis: {
                            gridlines: {
                                color: 'transparent'
                            },
                            textStyle: {
                                color: 'black'
                            }
                        },
                        annotations: {
                            alwaysOutside: true,
                            textStyle: {
                                fontSize: 25,
                                color: 'green'
                            }
                        },
                        chart: {
                            title: '',
                            subtitle: ''
                        },
                        xAxis: {
                            textPosition: ''
                        }
                    };

                    var chart = new google.charts.Bar(document.getElementById('procuremnt_chart'));
                    chart.draw(data, google.charts.Bar.convertOptions(options));
                }

                google.charts.setOnLoadCallback(procurementContract);

                function procurementContract() {
                    var data = new google.visualization.arrayToDataTable([
                        ['Name', 'Values'],
                        ["Works", {{ $contract['workTotal'] }}],
                        ["Goods", {{ $contract['goodsTotal'] }}],
                        ["Consultancy", {{ $contract['consultancyTotal'] }}],
                        ["Others", {{ $contract['othersTotal'] }}],
                    ]);

                    var decimalFormatter = new google.visualization.NumberFormat({
                        pattern: '##0.00'
                    });

                    decimalFormatter.format(data, 1);

                    var options = {
                        title: '',
                        hAxis: {
                            title: 'Project Category',
                        },
                        vAxis: {
                            title: '',
                            gridlines: {
                                count: 0
                            }
                        },
                        width: '80%',
                        legend: {
                            position: 'none'
                        },
                        chart: {
                            title: '',
                            subtitle: ''
                        },
                        bars: 'vertical',
                        axes: {
                            x: {
                                0: {
                                    side: 'left',
                                    label: 'Project Category'
                                }
                            }
                        },
                        bar: {
                            groupWidth: "80%"
                        },
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('contract_chart'));
                    chart.draw(data, options);
                }
            </script>
        @elseif(in_array(CheckESLevelThree(), [1, 2, 3]))
            <script type="text/javascript">
                google.charts.load("current", {
                    packages: ['corechart']
                });
                google.charts.setOnLoadCallback(PMU);

                function PMU() {
                    var data = new google.visualization.arrayToDataTable([
                        ['Project Category', '', {
                            role: "style"
                        }],
                        ["Total Projects", {{ $projects['workTotal'] }}, "#007bff"],
                        ["Ongoing Projects", {{ $projects['workTotalP'] }}, "#dc3545"],
                        ["Completed Projecs", {{ $projects['workTotalC'] }}, "#f4b400"],
                    ]);

                    var options = {
                        vAxis: {
                            gridlines: {
                                color: 'transparent'
                            },
                        },
                        hAxis: {
                            gridlines: {
                                color: 'transparent'
                            },
                        },
                        title: "",
                        width: 800,
                        height: 550,
                        bar: {
                            groupWidth: "70%"
                        },
                        legend: {
                            position: "none"
                        },
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById("PMU_LEVEL_2"));
                    chart.draw(data, options);
                }
            </script>
        @elseif(in_array(auth()->user()->role->level, ['TWO', 'THREE']))
            <script type="text/javascript">
                google.charts.load('current', {
                    packages: ['corechart', 'bar']
                });
                google.charts.setOnLoadCallback(PMU);

                function PMU() {
                    var data = google.visualization.arrayToDataTable([
                        ['Project Category', 'Total Projects', 'Ongoing Projects', 'Completed Projecs'],
                        ['Work', {{ $projects['workTotal'] }}, {{ $projects['workTotalP'] }},
                            {{ $projects['workTotalC'] }}
                        ],
                        ['Goods', {{ $projects['goodsTotal'] }}, {{ $projects['goodsTotalP'] }},
                            {{ $projects['goodsTotalC'] }}
                        ],
                        ['Consultancy', {{ $projects['consultancyTotal'] }}, {{ $projects['consultancyTotalP'] }},
                            {{ $projects['consultancyTotalC'] }}
                        ],
                        ['Others', {{ $projects['othersTotal'] }}, {{ $projects['otherTotalP'] }},
                            {{ $projects['otherTotalC'] }}
                        ],
                    ]);

                    var options = {
                        vAxis: {
                            textPosition: 'none',
                            gridlines: {
                                color: 'transparent'
                            },
                            textStyle: {
                                color: 'white'
                            }
                        },
                        hAxis: {
                            gridlines: {
                                color: 'transparent'
                            },
                            textStyle: {
                                color: 'black'
                            }
                        },
                        annotations: {
                            alwaysOutside: true,
                            textStyle: {
                                color: 'green'
                                fontSize: 25,
                            }
                        },
                        chart: {
                            title: '',
                            subtitle: ''
                        },
                        xAxis: {
                            textPosition: ''
                        }
                    };

                    var chart = new google.charts.Bar(document.getElementById('PMU_LEVEL_2'));
                    chart.draw(data, google.charts.Bar.convertOptions(options));
                }

                google.charts.load("current", {
                    packages: ["corechart"]
                });
                google.charts.setOnLoadCallback(financialChart);

                function financialChart() {
                    var data = new google.visualization.arrayToDataTable([
                        ['Name', 'Values'],
                        ["Works", {{ $financialData['Works'] }}],
                        ["Goods", {{ $financialData['Goods'] }}],
                        ["Consultancy", {{ $financialData['Consultancy'] }}],
                        ["Others", {{ $financialData['others'] }}],
                        @if (auth()->user()->role->level != 'THREE')
                            ["Office Expense", {{ $financialData['officeExense'] }}],
                        @endif
                    ]);

                    var decimalFormatter = new google.visualization.NumberFormat({
                        pattern: '##0.00'
                    });

                    decimalFormatter.format(data, 1);

                    var options = {
                        title: '',
                        hAxis: {
                            title: 'Project Category',
                        },
                        vAxis: {
                            title: '',
                            gridlines: {
                                count: 0
                            }
                        },
                        width: '80%',
                        legend: {
                            position: 'none'
                        },
                        chart: {
                            title: '',
                            subtitle: ''
                        },
                        bars: 'vertical',
                        axes: {
                            x: {
                                0: {
                                    side: 'left',
                                    label: 'Project Category'
                                }
                            }
                        },
                        bar: {
                            groupWidth: "80%"
                        },
                    };

                    var chart = new google.visualization.ColumnChart(document.getElementById('financialChart'));
                    chart.draw(data, options);
                }
            </script>
        @else
            <script type="text/javascript">
                google.charts.load('current', {
                    packages: ['corechart', 'bar']
                });
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Project Category', 'Total Projects', 'Ongoing Projects', 'Completed Projecs'],
                        ['Work', {{ $projects['workTotal'] }}, {{ $projects['workTotalP'] }},
                            {{ $projects['workTotalC'] }}
                        ],
                        ['Goods', {{ $projects['goodsTotal'] }}, {{ $projects['goodsTotalP'] }},
                            {{ $projects['goodsTotalC'] }}
                        ],
                        ['Consultancy', {{ $projects['consultancyTotal'] }}, {{ $projects['consultancyTotalP'] }},
                            {{ $projects['consultancyTotalC'] }}
                        ],
                        ['Others', {{ $projects['othersTotal'] }}, {{ $projects['otherTotalP'] }},
                            {{ $projects['otherTotalC'] }}
                        ],
                    ]);


                    var options = {
                        vAxis: {
                            textPosition: 'none',
                            gridlines: {
                                color: 'transparent'
                            },
                            textStyle: {
                                color: 'white'
                            }
                        },
                        hAxis: {
                            gridlines: {
                                color: 'transparent'
                            },
                            textStyle: {
                                color: 'black'
                            }
                        },
                        annotations: {
                            alwaysOutside: true,
                            textStyle: {
                                fontSize: 25,
                                color: 'green'
                            }
                        },
                        chart: {
                            title: '',
                            subtitle: ''
                        },
                        xAxis: {
                            textPosition: ''
                        }
                    };
                    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                    chart.draw(data, google.charts.Bar.convertOptions(options));
                }

                google.charts.setOnLoadCallback(drawChart1);

                function drawChart1() {
                    var data = google.visualization.arrayToDataTable([
                        ['Project Category', 'Total Cost'],
                        ["Works", {{ $financialData['Works'] ?? 0 }}],
                        ["Goods", {{ $financialData['Goods'] ?? 0 }}],
                        ["Consultancy", {{ $financialData['Consultancy'] ?? 0 }}],
                        ["Others", {{ $financialData['others'] ?? 0 }}],
                        ["Office Expense", {{ $financialData['officeExense'] ?? 0 }}],
                    ]);

                    var decimalFormatter = new google.visualization.NumberFormat({
                        pattern: '##0.00'
                    });

                    decimalFormatter.format(data, 1);

                    var options = {
                        title: '',
                        hAxis: {
                            title: 'Project Category',
                        },
                        vAxis: {
                            title: '',
                            gridlines: {
                                count: 0
                            }
                        },
                        width: '80%',
                        legend: {
                            position: 'none'
                        },
                        chart: {
                            title: '',
                            subtitle: ''
                        },
                        bars: 'vertical',
                        axes: {
                            x: {
                                0: {
                                    side: 'left',
                                    label: 'Project Category'
                                }
                            }
                        },
                        bar: {
                            groupWidth: "80%"
                        },
                    };

                    var chart1 = new google.visualization.ColumnChart(document.getElementById('columnchart_material1'));
                    chart1.draw(data, options);
                }
            </script>
        @endif
    @endif

    <script>
        $(document).ready(function() {
            $('.buttonParam').on('click', function() {
                var paramName = $(this).data('name');
                var paramValue = $(this).data('value');

                addOrReplaceParameter(paramName, paramValue);
            });

            function addOrReplaceParameter(param, value) {
                const url = new URL(window.location.href);
                url.searchParams.set(param, value);
                window.location.href = url.href;
            }
        });
    </script>
@stop
