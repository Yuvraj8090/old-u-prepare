@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
              
    @include('admin.include.backButton')

    <h4>Test Details | Project : {{ $projectData->name }}</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">Project Quality Reports</a></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row x_panel">
    
<div class="col-md-12">
    <div class="x_content">
        
      <h3 style="padding:10px;" class="bg-success text-white">Quality Reports</h3>
      <table class="table text-center table-striped projects table-bordered">
        <thead>
          <tr>
            <th style="width:30%;" > Quality Reports  </th>
            <th> No. of tests</th>
            <th> Passed</th>
            <th> Failed</th>
            <th> Status(Pass%) </th>
          </tr>
        </thead>
        <tbody>
          <tr>
              <th>
                  <a href="{{ url('project/tests/1/'.$projectData->id)  }}">Material Test</a>
             </th>
              <th>{{ $materialData->sum('total_reports') ?? 0 }}</th>
              <td>{{ $materialData->sum('passed_reports') ?? 0 }}</td>
              <td>{{ $materialData->sum('failed_reports') ?? 0 }}</td>
              <th>{{ $materialData['status_percentage'] ?? 0 }} %</th>
          </tr>
          
          <tr>
            <th>
                 <a href="{{ url('/project/environement/index/'.$projectData->id) }}">Environment Test</a>
            </th>
            <th>{{ $evTotal ?? 0  }}</th>
            <td>{{ $evPass ??  0 }}</td>
            <td>{{ $evFailed ??  0 }}</td>
            <th>{{ $evStatusPercentage ??  0 }} %</th>
          </tr>
          
          <tr>
              <th>
                   <a href="{{ url('project/tests/2/'.$projectData->id)  }}">Bridge/Slope/Building Test  </a>
                 </th>
             <th>{{ $categoryData->sum('total_reports') ?? 0 }}</th>
              <td>{{ $categoryData->sum('passed_reports') ?? 0 }}</td>
              <td>{{ $categoryData->sum('failed_reports') ?? 0 }}</td>
              <th>{{ $categoryData['status_percentage'] ?? 0 }} %</th>
          </tr>
        </tbody>
      </table>
  </div>
  

</div>
</div>
@stop






