@extends('layouts.admin')

@section('content')

<style>
    .btn-custom{
        padding-right:30px !important;
        padding-left:30px  !important;
    }
    .outerbox{
        margin:10px;
        padding:20px 35px;
        border-radius:10px;
        max-width:31% !important;
    }
</style>

<div class="row">
@if(auth()->user()->role->department == "PMU" && auth()->user()->role->level == "ONE")
    <div class="outerbox col-md-4 bg-primary">
        <a href="{{ url('project') }}">

            <h5 class="text-white">TOTAL PROJECTS</h5>
            <h1  class="text-white"><i class="fa fa-file" ></i> {{ $data['totalProjects'] ?? 0 }}</h1>
        </a>
    </div>
   
     <div class="outerbox col-md-4 bg-danger">
        <a href="{{ url('project') }}?status=2">
            <h5 class="text-white">ONGOING PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['Ongoing'] ?? 0 }}</h1>
        </a>
    </div>
    
     <div style="background-color:rgb(244, 180, 0);" class="outerbox col-md-4">
         <a href="{{ url('project') }}?status=3">
            <h5 class="text-white">COMPLETED PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['completed'] ?? 0 }}</h1>
        </a>
    </div>
    
@elseif(in_array(auth()->user()->role->department,['PROCUREMENT']))

    <div class="outerbox col-md-3 bg-primary">
        <a href="{{ url('procurement/index') }}">
            <h5 class="text-white">TOTAL PROJECTS</h5>
            <h1  class="text-white"><i class="fa fa-file" ></i> {{ $data['totalProjects'] ?? 0 }}</h1>
        </a>
    </div>
   
     <div class="outerbox col-md-3 bg-danger">
        <a href="{{ url('procurement/index') }}">
            <h5 class="text-white">ONGOING PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['Ongoing'] ?? 0 }}</h1>
        </a>
    </div>
    
     <div style="background-color:rgb(244, 180, 0);" class="outerbox col-md-3">
        <a href="{{ url('procurement/index') }}">
            <h5 class="text-white">COMPLETED PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['completed'] ?? 0 }}</h1>
        </a>
    </div>
@elseif(in_array(auth()->user()->role->department,['USDMA-PROCUREMENT','FOREST-PROCUREMENT','RWD-PROCUREMENT','PWD-PROCUREMENT','PMU-PROCUREMENT']))

    <div class="outerbox col-md-3 bg-primary">
        <a href="{{ url('procurement/level/three/projects') }}">
            <h5 class="text-white">TOTAL PROJECTS</h5>
            <h1  class="text-white"><i class="fa fa-file" ></i> {{ $data['totalProjects'] ?? 0 }}</h1>
        </a>
    </div>
   
     <div class="outerbox col-md-3 bg-danger">
        <a href="{{ url('procurement/level/three/projects') }}">
            <h5 class="text-white">ONGOING PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['Ongoing'] ?? 0 }}</h1>
        </a>
    </div>
    
     <div style="background-color:rgb(244, 180, 0);" class="outerbox col-md-3">
        <a href="{{ url('procurement/level/three/projects') }}">
            <h5 class="text-white">COMPLETED PROJECTS</h5>
            
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['completed'] ?? 0 }}</h1>
        </a>
        
    </div>
    
@elseif(in_array(auth()->user()->role->name,['PMU-LEVEL-TWO','PIU-LEVEL-TWO-PWD','PIU-LEVEL-TWO-RWD','PIU-LEVEL-TWO-FOREST','PIU-LEVEL-TWO-USDMA']))
     <div class="outerbox col-md-3 bg-primary">
        <a href="#">
            <h5 class="text-white">TOTAL PROJECTS</h5>
            <h1  class="text-white"><i class="fa fa-file" ></i> {{ $data['totalProjects'] ?? 0 }}</h1>
        </a>
    </div>
   
     <div class="outerbox col-md-3 bg-danger">
        <a href="#">
            <h5 class="text-white">ONGOING PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['Ongoing'] ?? 0 }}</h1>
        </a>
    </div>
    
     <div style="background-color:rgb(244, 180, 0);" class="outerbox col-md-3">
        <a href="#">
            <h5 class="text-white">COMPLETED PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['completed'] ?? 0 }}</h1>
        </a>
    </div>
@elseif(in_array(auth()->user()->role->department,['PMU-LEVEL-THREE','PIU-LEVEL-THREE-PWD','PIU-LEVEL-THREE-RWD','PIU-LEVEL-THREE-FOREST','PIU-LEVEL-THREE-USDMA']))
 <div class="outerbox col-md-3 bg-primary">
        <a href="#">
            <h5 class="text-white">TOTAL PROJECTS</h5>
            <h1  class="text-white"><i class="fa fa-file" ></i> {{ $data['totalProjects'] ?? 0 }}</h1>
        </a>
    </div>
   
     <div class="outerbox col-md-3 bg-danger">
        <a href="#">
            <h5 class="text-white">ONGOING PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['Ongoing'] ?? 0 }}</h1>
        </a>
    </div>
    
     <div style="background-color:rgb(244, 180, 0);" class="outerbox col-md-3">
        <a href="#">
            <h5 class="text-white">COMPLETED PROJECTS</h5>
            <h1  class="text-white"> <i class="fa fa-file" ></i> {{ $data['completed'] ?? 0 }}</h1>
        </a>
    </div>
@endif
</div>

@if(auth()->user()->role->level == "NULL" || auth()->user()->role->level == "ONE")
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="x_content">
                        <!-- bar charts group -->
                        <div class="">
                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Physical Status </h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">

                                        <a class="btn btn-md btn-default @if(!request()->p) btn-primary @else btn-success @endif btn-custom"  href="?p=">ALL</a>
                                        <a class="btn btn-md btn-default @if(request()->p == 'PMU') btn-primary @else btn-success  @endif  btn-custom" href="?p=PMU">PMU</a>
                                        <a class="btn btn-md btn-default @if(request()->p == 'PWD') btn-primary @else btn-success @endif  btn-custom" href="?p=PWD">PWD</a>
                                        <a class="btn btn-md btn-default @if(request()->p == 'RWD') btn-primary @else btn-success  @endif   btn-custom" href="?p=RWD">RWD</a>
                                        <a class="btn btn-md btn-default @if(request()->p == 'USDMA') btn-primary @else btn-success @endif   btn-custom" href="?p=USDMA">USDMA</a>
                                        <a class="btn btn-md btn-default @if(request()->p == 'FOREST') btn-primary @else btn-success @endif   btn-custom" href="?p=FOREST">FOREST</a>
                                        
                                        <style>
                                            #table tbody tr td{
                                                text-align: center;
                                                color:black;
                                            }
                                            #table thead tr td,th{
                                                text-align: center;
                                                color:black;
                                            } 
                                        </style>
                                       
                                        <table id="table" border="2" style="width:100%;margin:10px;margin-bottom:30px;" >
                                            <thead>
                                            <tr>
                                                <th>Category </th>
                                                <th>Total</th>
                                                <th>Ongoing</th>
                                                <th>Completed</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Works</th>
                                                    <td>{{$projects['workTotal']}}</td>
                                                    <td>{{$projects['workTotalP']}}</td>
                                                    <td>{{$projects['workTotalC']}}</td>
                                                </tr>
                                                 <tr>
                                                    <th>Goods</th>
                                                    <td>{{$projects['goodsTotal']}}</td>
                                                    <td>{{$projects['goodsTotalP']}}</td>
                                                    <td>{{$projects['goodsTotalC']}}</td>
                                                </tr>
                                                 <tr>
                                                    <th>Consultancy</th>
                                                    <td>{{$projects['consultancyTotal']}}</td>
                                                    <td>{{$projects['consultancyTotalP']}}</td>
                                                    <td>{{$projects['consultancyTotalC']}}</td>
                                                </tr>
                                                 <tr>
                                                    <th>Others</th>
                                                    <td>{{$projects['othersTotal']}}</td>
                                                    <td>{{$projects['otherTotalP']}}</td>
                                                    <td>{{$projects['otherTotalC']}}</td>
                                                </tr>
                                                <tr>
                                                    <td  colspan="4"  >
                                                        N/A
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    
                                        <div id="columnchart_material" style="height: 400px;"></div>
                                       
                                            

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Financial Status </h2>
                                       
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">

                                        <a class="btn btn-md btn-default @if(!request()->f) btn-primary @else btn-success @endif btn-custom"  href="?f=">ALL</a>
                                        <a class="btn btn-md btn-default @if(request()->f == 'PMU') btn-primary @else btn-success  @endif  btn-custom" href="?f=PMU">PMU</a>
                                        <a class="btn btn-md btn-default @if(request()->f == 'PWD') btn-primary @else btn-success @endif  btn-custom" href="?f=PWD">PWD</a>
                                        <a class="btn btn-md btn-default @if(request()->f == 'RWD') btn-primary @else btn-success  @endif   btn-custom" href="?f=RWD">RWD</a>
                                        <a class="btn btn-md btn-default @if(request()->f == 'USDMA') btn-primary @else btn-success @endif   btn-custom" href="?f=USDMA">USDMA</a>
                                        <a class="btn btn-md btn-default @if(request()->f == 'FOREST') btn-primary @else btn-success @endif   btn-custom" href="?f=FOREST">FOREST</a>
                                        
                                        <table id="table" border="2" style="width:100%;margin:10px;margin-bottom:30px;" >
                                            <thead>
                                            <tr>
                                                <th>Category </th>
                                                <th>Value (in lakh)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                
                                                <tr>
                                                    <th>Works</th>
                                                     <td>{{$financialData['Works']}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Goods</th>
                                                     <td>{{$financialData['Works']}}</td>
                                                </tr>
                                                
                                                 <tr>
                                                    <th>Consultancy</th>
                                                     <td>{{$financialData['Consultancy']}}</td>
                                                </tr>
                                                
                                                 <tr>
                                                    <th>Others</th>
                                                    <td>{{$financialData['others']}}</td>
                                                </tr>
                                                
                                                <tr>
                                                    <th>Office Expense</th>
                                                     <td>{{$financialData['officeExense']}}</td>
                                                </tr>
          
                                                
                                            </tbody>
                                        </table>
                                        
                                        <div id="columnchart_material1" style="height: 400px;"></div>
                                           
                                    </div>
                                </div>
                            </div>
@if(false)
                            <div class="col-md-12 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2>Quality Inspection</h2>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="x_content">

                                        <p>  (RF = RED FLAGS) <br> Red Flags- Project Delayed, Incomplete Environment And Social Compliance, No Site visit, Delay in Updating Status, Procurement Redflags</p>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <a href="javascript:(0)"  class=" text-white btn btn-lg  btn-primary"
                                                data-toggle="tooltip" data-placement="top" title="Total PMU(Red Flags) : 1"  >
                                                    PMU (RF) : 1
                                                </a>

                                                <!-- <a href="javascript:(0)"  class=" text-white btn btn-primary"
                                                data-toggle="tooltip" data-placement="top" title="Total PROCUREMENT(Red Flags) : 1" >
                                                    PROCUREMENT (RF) : 1
                                                </a> -->

                                                <a href="javascript:(0)"  class=" text-white btn  btn-lg  btn-primary"
                                                data-toggle="tooltip" data-placement="top" title="Total PWD(Red Flags) : 0"  >
                                                    PWD (RF) : 0
                                                </a>
                                                <a href="javascript:(0)"  class=" text-white btn  btn-lg btn-primary"
                                                data-toggle="tooltip" data-placement="top" title="Total RWD(Red Flags) : 5"  >
                                                    RWD (RF) : 5
                                                </a>
                                                <a href="javascript:(0)"  class=" text-white btn  btn-lg btn-primary" 
                                                data-toggle="tooltip" data-placement="top" title="Total USDMA(Red Flags) : 3" >
                                                    USDMA (RF) : 3
                                                </a>
                                                <a  href="javascript:(0)"  class=" text-white btn btn-lg  btn-primary" 
                                                data-toggle="tooltip" data-placement="top" title="Total FOREST(Red Flags) : 9" >
                                                    FOREST (RF) : 9
                                                </a>

                                                <!-- <a href="javascript:(0)"  class=" text-white btn btn-primary" 
                                                data-toggle="tooltip" data-placement="top" title="Total ENVIRONMENT(Red Flags) : 1">
                                                    ENVIRONMENT (RF) : 1
                                                </a> -->

                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
@endif
                        </div>
                    </div>
                    <!-- /page content -->
                </div>
            </div>
        </div>
    </div>
</div>
@elseif( (auth()->user()->role->department == "PROCUREMENT") || (auth()->user()->role->department === 'USDMA-PROCUREMENT' ||
                                    auth()->user()->role->department === 'FOREST-PROCUREMENT' ||
                                    auth()->user()->role->department === 'RWD-PROCUREMENT' ||
                                    auth()->user()->role->department === 'PWD-PROCUREMENT' ||
                                    auth()->user()->role->department === 'PMU-PROCUREMENT')   )
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="x_content">
                        <!-- bar charts group -->
                        <div class="">
                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Procurement Status</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                        <div class="x_content">
                                            <div id="procuremnt_chart" style=" height: 500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Contract Awarded</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div id="contract_chart" style="height: 500px;"></div>
                                    </div>
                                </div>
                            </div>

                            @if(false)
                            <div class="col-md-12 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> PMU All Projects</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div id="pmu_chart" style="height: 600px;"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <!-- /page content -->
                </div>
            </div>
        </div>
    </div>
</div>
@elseif(auth()->user()->role->level == "TWO" || auth()->user()->role->level == "THREE")
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="x_content">
                        <!-- bar charts group -->
                        <div class="">
                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Physical Status</h2>
                                        <a target="_blank" href="{{ url('/define/project') }}" class="btn btn-sm btn-success pull-right">View All </a>

                                        <div class="clearfix"></div>
                                    </div>
                                        <div class="tab-content" >
                                                <div id="PMU_LEVEL_2" style="height: 500px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Financial Status</h2>
                                        <a target="_blank" href="{{ url('/finance/index') }}" class="btn btn-sm btn-success pull-right">View All </a>

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div id="financialChart" style="height: 500px;"></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <!-- /page content -->
                </div>
            </div>
        </div>
    </div>
</div>
@elseif(false)
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <div class="x_content">
                        <!-- bar charts group -->
                        <div class="">
                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Physical Status</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    
                                    <div class="x_content">
                                        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pmu-tab" data-toggle="tab" href="#pmu" role="tab" aria-controls="pmu" aria-selected="true">PMU</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="pwd-tab" data-toggle="tab" href="#pwd" role="tab" aria-controls="pwd" aria-selected="false">PWD</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="rwd-tab" data-toggle="tab" href="#rwd" role="tab" aria-controls="rwd" aria-selected="false">RWD</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="usdma-tab" data-toggle="tab" href="#usdma" role="tab" aria-controls="usdma" aria-selected="false">USDMA</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="forest-tab" data-toggle="tab" href="#forest" role="tab" aria-controls="forest" aria-selected="false">FOREST</a>
                                            </li>
                                        </ul>
                                        
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="pmu" role="tabpanel" aria-labelledby="pmu-tab">
                                            <div id="columnchart_material" style="width: 600px; height: 300px;"></div>
                                            </div>
                                            <div class="tab-pane fade" id="pwd" role="tabpanel" aria-labelledby="pwd-tab">
                                                <div> <canvas id="myChart"></canvas> </div>
                                            </div>
                                            <div class="tab-pane fade" id="rwd" role="tabpanel" aria-labelledby="rwd-tab">
                                                <div> <canvas id="myChart"></canvas> </div>
                                            </div>
                                            <div class="tab-pane fade" id="usdmsa" role="tabpanel" aria-labelledby="usdmsa-tab">
                                                <div> <canvas id="myChart"></canvas> </div>
                                            </div>
                                            <div class="tab-pane fade" id="forest" role="tabpanel" aria-labelledby="forest-tab">
                                                <div> <canvas id="myChart"></canvas> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Financial Status</h2>
                                      
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">PMU</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">PWD</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">RWD</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">USDMA</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">FOREST</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                <div>
                                                    <center style="height:300px;">
                                                        <canvas id="myChartPie" width="400" height="400"></canvas>
                                                    </center>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                <div> <canvas id="myChart"></canvas> </div>
                                            </div>
                                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                                <div> <canvas id="myChart"></canvas> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /page content -->
                </div>
            </div>
        </div>
    </div>
</div>
@endif

</div>
</div>
</div>
</div>
@stop

<!-- code changes added on 9 feb  -->
@section('script')

@if(auth()->user()->role->department == "PROCUREMENT" || in_array(auth()->user()->role->department,['USDMA-PROCUREMENT','FOREST-PROCUREMENT','RWD-PROCUREMENT','PWD-PROCUREMENT','PMU-PROCUREMENT']) )
<script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(procurementChart);

      function procurementChart() {

        var data = google.visualization.arrayToDataTable([
          ['Types of projects', 'Total Projects', 'Ongoing Projects', 'Completed Projecs'],
          ['Work', {{$procurement['workTotal']}}, {{$procurement['workTotalC']}}, {{$procurement['workTotalP']}}],
          ['Goods',{{$procurement['goodsTotal']}}, {{$procurement['goodsTotalC']}}, {{$procurement['goodsTotalP']}}],
          ['Consultancy',{{$procurement['consultancyTotal']}}, {{$procurement['consultancyTotalC']}}, {{$procurement['consultancyTotalP']}}],
          ['Others',{{$procurement['othersTotal']}} , {{$procurement['othersTotalP']}}, {{$procurement['othersTotalC']}}],
        ]);
                
        var options = {
          chart: {
            title: 'Value in number',
            subtitle: '',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('procuremnt_chart'));
        chart.draw(data, google.charts.Bar.convertOptions(options));

        }

      google.charts.setOnLoadCallback(procurementContract);
      
      function procurementContract() {
        
        var data = new google.visualization.arrayToDataTable([
            ['Name', 'Values'],
            ["Works", {{ $contract['workTotal'] }} ],
            ["Goods", {{ $contract['consultancyTotal'] }} ],
            ["Consultancy", {{ $contract['goodsTotal'] }} ],
            ["Others",  {{ $contract['othersTotal'] }} ],
        ]);

        // Custom formatter for y-axis and bar values
        var decimalFormatter = new google.visualization.NumberFormat({
            pattern: '#,##0.00'
        });

        decimalFormatter.format(data, 1);
        
        var options = {
            vAxis: {
                textPosition: 'none', 
                textStyle: { color: 'white' },
            },
            title: 'Type of projects',
            width: '100%',
            legend: { position: 'none' },
            chart: { title: 'Value in INR Lakhs', subtitle: '' },
            bars: 'vertical',
            axes: {
                x: {
                    0: { side: 'left', label: 'Type of projects' }
                }
            },
            bar: { 
                groupWidth: "90%"
            },
        };

        var chart = new google.charts.Bar(document.getElementById('contract_chart'));
        chart.draw(data, options);
    }

</script>
@elseif(auth()->user()->role->level == "TWO" || auth()->user()->role->level == "THREE")
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});        
    google.charts.setOnLoadCallback(PMU);
    // google.charts.setOnLoadCallback(financialChart);

    function PMU() {

        var data = google.visualization.arrayToDataTable([
          ['Type of projects', 'Total Projects', 'Ongoing Projects', 'Completed Projecs'],
          ['Work', {{$projects['workTotal']}}, {{$projects['workTotalP']}}, {{$projects['workTotalC']}}],
          ['Goods', {{$projects['goodsTotal']}}, {{$projects['goodsTotalP']}}, {{$projects['goodsTotalC']}}], 
          ['Consultancy',{{$projects['consultancyTotal']}} , {{$projects['consultancyTotalP']}}, {{$projects['consultancyTotalC']}}],
          ['Others',{{$projects['othersTotal']}} , {{$projects['otherTotalP']}}, {{$projects['otherTotalC']}}],
          
        ]);
        
        var options = {
            vAxis: {
            textPosition: 'none', 
            textStyle: { color: 'white' }
          },
          chart: {
            title: 'Value in numbers',
            subtitle: '',
          },
          xAxis: { textPosition: '' } 
        };

        var chart = new google.charts.Bar(document.getElementById('PMU_LEVEL_2'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
        
    }

    google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(financialChart);

    // function financialChart() {
        
    //     var data = new google.visualization.arrayToDataTable([
    //     ['Name', 'Values'],
    //       ["Works", {{$financialData['Works']}}],
    //       ["Goods", {{$financialData['Goods']}}],
    //       ["Consultancy", {{$financialData['Consultancy']}}],
    //       ["Others", {{$financialData['others']}}],
    //       ["Office Expense", {{$financialData['officeExense']}}],
    //     ]);

        
    //     // var options = {
    //     //         vAxis: {
    //     //         textPosition: 'none', 
    //     //         textStyle: { color: 'white' }
    //     //       },
    //     //     title: 'Type of projects',
    //     //     width: '100%',
    //     //     legend: { position: 'none' },
    //     //     chart: { title: 'Value in INR Lakhs', subtitle: '' },
    //     //     bars: 'vertical',
            
    //     //     axes: {
    //     //         x: {
    //     //             0: { side: 'left', label: 'Type of projects' }
    //     //         },
    //     //         // y: {
    //     //         //     0: { side: 'bottom', label: 'Value (in Th)' } // Add this line for y-axis label
    //     //         // }
    //     //     },
    //     //     bar: { groupWidth: "90%" },
     
    //     // };
        
    //     var options = {
    //     vAxis: {
    //         textPosition: 'none', 
    //         textStyle: { color: 'white' },
    //         format: 'decimal' // Display y-axis values in decimal format
    //     },
    //     title: 'Type of projects',
    //     width: '100%',
    //     legend: { position: 'none' },
    //     chart: { title: 'Value in INR Lakhs', subtitle: '' },
    //     bars: 'vertical',
    //     axes: {
    //         x: {
    //             0: { side: 'left', label: 'Type of projects' }
    //         }
    //     },
    //     bar: { 
    //         groupWidth: "90%",
    //         // Display bar values in decimal format
    //         textStyle: { 
    //             fontSize: 12, // Adjust font size as needed
    //             color: 'black' // Adjust font color as needed
    //         },
    //         format: 'decimal'
    //     },
    // };

    //     var chart = new google.charts.Bar(document.getElementById('financialChart'));
    //     chart.draw(data, options);

    // }
    
    function financialChart() {
        
        var data = new google.visualization.arrayToDataTable([
            ['Name', 'Values'],
            ["Works", {{$financialData['Works']}}],
            ["Goods", {{$financialData['Goods']}}],
            ["Consultancy", {{$financialData['Consultancy']}}],
            ["Others", {{$financialData['others']}}],
            ["Office Expense", {{$financialData['officeExense']}}],
        ]);

        // Custom formatter for y-axis and bar values
        var decimalFormatter = new google.visualization.NumberFormat({
            pattern: '#,##0.00'
        });

        decimalFormatter.format(data, 1);
        
        var options = {
            vAxis: {
                textPosition: 'none', 
                textStyle: { color: 'white' },
            },
            title: 'Type of projects',
            width: '100%',
            legend: { position: 'none' },
            chart: { title: 'Value in INR Lakhs', subtitle: '' },
            bars: 'vertical',
            axes: {
                x: {
                    0: { side: 'left', label: 'Type of projects' }
                }
            },
            bar: { 
                groupWidth: "90%"
            },
        };

        var chart = new google.charts.Bar(document.getElementById('financialChart'));
        chart.draw(data, options);
    }

    
</script>
@else
<script type="text/javascript">

    google.charts.load('current', {packages:['bar']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
          
        var data = google.visualization.arrayToDataTable([
          ['Type of projects', 'Total Projects', 'Ongoing Projects', 'Completed Projecs'],
          ['Work', {{$projects['workTotal']}}, {{$projects['workTotalP']}}, {{$projects['workTotalC']}}],
          ['Goods', {{$projects['goodsTotal']}}, {{$projects['goodsTotalP']}}, {{$projects['goodsTotalC']}}], 
          ['Consultancy',{{$projects['consultancyTotal']}} , {{$projects['consultancyTotalP']}}, {{$projects['consultancyTotalC']}}],
          ['Others',{{$projects['othersTotal']}} , {{$projects['otherTotalP']}}, {{$projects['otherTotalC']}}],
        ]);
        
        var options = {
          vAxis: {
            textPosition: 'none',
            gridlines: {
              color: 'transparent' 
            },
            textStyle: { color: 'white' }
          },
          hAxis: {
            gridlines: {
              color: 'transparent'
            },
            textStyle: { color: 'black' }
          },
          annotations: {
            alwaysOutside: true, 
            textStyle: {
              fontSize: 25,
              color: 'green'
            }
          },
          chart: {
            title: 'Value in numbers',
            subtitle: ''
          },
          xAxis: { textPosition: '' }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));
        google.visualization.events.addListener(chart, 'select', selectHandler);
    
        function selectHandler() {
            var selectedItem = chart.getSelection()[0];
            if (selectedItem) {
                // Get data for the selected bar
                var selectedData = data.getValue(selectedItem.row, 0); 
                // var selectedStatus = data.getValue(selectedItem.row, 1);
                var selectedLegend = data.getColumnLabel(selectedItem.column);
                // Assuming the first column contains the project type
                // Redirect to a specific URL with the selected data
                
                alert(selectedLegend);
                // window.location.href = '/your-redirect-url?projectType=' + selectedData;
            }
        }
    
        chart.draw(data, google.charts.Bar.convertOptions(options));
        
      }
    
    // google.charts.load("current", {packages:["corechart"]});
    google.charts.setOnLoadCallback(drawChart1);
    
    function drawChart1() {
        
        var data = google.visualization.arrayToDataTable([
          ['Name', 'Values'],
          ["Works", {{$financialData['Works']}}],
          ["Goods", {{$financialData['Goods']}}],
          ["Consultancy", {{$financialData['Consultancy']}}],
          ["Others", {{$financialData['others']}}],
          ["Office Expense", {{$financialData['officeExense']}}],
        ]);
        
        // var options = {
        //     title: 'Type of projects',
        //     width: '100%',
        //     legend: { position: 'none' },
        //     chart: { title: 'Value in INR Lakhs', subtitle: '' },
        //     bars: 'vertical',
        //     axes: {
        //         x: {
        //             0: { side: 'left', label: 'Type of projects' }
        //         },
        //         y: {
        //             0: { side: 'bottom', label: 'Value (in lakhs)' } 
        //         }
        //     },
        //     bar: { groupWidth: "90%" },
        //     hAxis: {
        //         gridlines: {
        //             color: 'white' 
        //         },
        //         textStyle: {
        //             color: 'white' 
        //         }
        //     },
        //     vAxis: {
        //         gridlines: {
        //             color: 'white'
        //         },
        //         textStyle: {
        //             color: 'white'
        //         }
        //     }
        // };
        
        var options1 = {
          vAxis: {
            textPosition: 'none',
            gridlines: {
              color: 'transparent' 
            },
            textStyle: { color: 'white' }
          },
          hAxis: {
            gridlines: {
              color: 'transparent'
            },
            textStyle: { color: 'black' }
          },
          annotations: {
            alwaysOutside: true, 
            textStyle: {
              fontSize: 25,
              color: 'green'
            }
          },
          chart: {
            title: 'Value in numbers',
            subtitle: ''
          },
          xAxis: { textPosition: '' }
        };
        
       var chart1 = new google.charts.Bar(document.getElementById('columnchart_material1'));
       chart1.draw(data, options);
    }
    
</script>
@endif
@stop

