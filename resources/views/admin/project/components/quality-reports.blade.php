
      
  
                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="qualityReportsTable">
                            <thead >
                                <tr>
                                    <th style="width:30%;">Test Type</th>
                                    <th class="text-center">Total Tests</th>
                                    <th class="text-center">Passed</th>
                                    <th class="text-center">Failed</th>
                                    <th class="text-center">Pass Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Material Test Row -->
                                <tr>
                                    <td class="font-weight-bold">
                                        <a href="{{ url('project/tests/1/'.$data->id) }}" class="text-primary">
                                            <i class="fa fa-cubes mr-2"></i> Material Test
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($materialData->sum('total_reports') ?? 0) }}</td>
                                    <td class="text-right text-success">{{ number_format($materialData->sum('passed_reports') ?? 0) }}</td>
                                    <td class="text-right text-danger">{{ number_format($materialData->sum('failed_reports') ?? 0) }}</td>
                                    <td>
                                        @php
                                            $materialPercentage = $materialData['status_percentage'] ?? 0;
                                            $materialColor = $materialPercentage >= 90 ? 'success' : ($materialPercentage >= 70 ? 'info' : 'warning');
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 20px;">
                                                <div class="progress-bar progress-bar-striped bg-primary" 
                                                     role="progressbar" 
                                                     style="width: {{ $materialPercentage }}%" 
                                                     aria-valuenow="{{ $materialPercentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="ml-2 font-weight-bold">{{ number_format($materialPercentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Environment Test Row -->
                                <tr>
                                    <td class="font-weight-bold">
                                        <a href="{{ url('/project/environement/index/'.$data->id) }}" class="text-primary">
                                            <i class="fa fa-leaf mr-2"></i> Environment Test
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($evTotal ?? 0) }}</td>
                                    <td class="text-right text-success">{{ number_format($evPass ?? 0) }}</td>
                                    <td class="text-right text-danger">{{ number_format($evFailed ?? 0) }}</td>
                                    <td>
                                        @php
                                            $envColor = $evStatusPercentage >= 90 ? 'success' : ($evStatusPercentage >= 70 ? 'info' : 'warning');
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 20px;">
                                                <div class="progress-bar progress-bar-striped bg-{{ $envColor }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $evStatusPercentage ?? 0 }}%" 
                                                     aria-valuenow="{{ $evStatusPercentage ?? 0 }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="ml-2 font-weight-bold">{{ number_format($evStatusPercentage ?? 0, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Structure Test Row -->
                                <tr>
                                    <td class="font-weight-bold">
                                        <a href="{{ url('project/tests/2/'.$data->id) }}" class="text-primary">
                                            <i class="fa fa-building mr-2"></i> Structure Test
                                        </a>
                                    </td>
                                    <td class="text-right">{{ number_format($categoryData->sum('total_reports') ?? 0) }}</td>
                                    <td class="text-right text-success">{{ number_format($categoryData->sum('passed_reports') ?? 0) }}</td>
                                    <td class="text-right text-danger">{{ number_format($categoryData->sum('failed_reports') ?? 0) }}</td>
                                    <td>
                                        @php
                                            $structurePercentage = $categoryData['status_percentage'] ?? 0;
                                            $structureColor = $structurePercentage >= 90 ? 'success' : ($structurePercentage >= 70 ? 'info' : 'warning');
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 20px;">
                                                <div class="progress-bar progress-bar-striped bg-{{ $structureColor }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $structurePercentage }}%" 
                                                     aria-valuenow="{{ $structurePercentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="ml-2 font-weight-bold">{{ number_format($structurePercentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
          
 

<style>
    #qualityReportsTable {
        font-size: 14px;
        border-collapse: separate;
        border-spacing: 0;
    }
    #qualityReportsTable thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
        vertical-align: middle;
    }
    #qualityReportsTable tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    .progress {
        background-color: #e9ecef;
    }
    .progress-bar {
        transition: none;
    }
    a.text-primary:hover {
        text-decoration: underline;
    }
</style>