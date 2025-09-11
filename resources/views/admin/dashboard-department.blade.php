@extends('layouts.admin')

@section('content')
<!-- top tiles -->
<div class="row" style="display: inline-block;">
    <div class="tile_count">
        <div class="col-md-4 col-sm-4  tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Project Summary</span>
            <div class="count">
                <h3> U-PREPARE </h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-4  tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Projects</span>
            <div class="count"> {{ $data['totalProjects'] ?? 0 }}</div>
            <!-- <span class="count_bottom"><i class="green">4% </i> From last Week</span> -->
        </div>
        <div class="col-md-3 col-sm-4  tile_stats_count">
            <span class="count_top"><i class="fa fa-clock-o"></i> Projects Completed </span>
            <div class="count">{{ $data['completed'] ?? 0 }}</div>
            <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>3% </i> From last Week</span> -->
        </div>
        <div class="col-md-2 col-sm-4  tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Ongoing </span>
            <div class="count green"> {{ $data['Ongoing'] ?? 0 }}</div>
            <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> -->
        </div>

    </div>
</div>
<!-- /top tiles -->

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
                                        <h2><i class="fa fa-bars"></i> Physical Progress</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                            @if($id == "PWD")
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="pmu-tab" data-toggle="tab" href="#pmu" role="tab" aria-controls="pmu" aria-selected="true">PWD</a>
                                                </li>
                                            @elseif($id == "USDMA")
                                                <li class="nav-item">
                                                    <a class="nav-link" id="pwd-tab" data-toggle="tab" href="#pwd" role="tab" aria-controls="pwd" aria-selected="false">USDMA</a>
                                                </li>
                                            @elseif($id == "RWD")
                                                <li class="nav-item">
                                                    <a class="nav-link" id="rwd-tab" data-toggle="tab" href="#rwd" role="tab" aria-controls="rwd" aria-selected="false">RWD</a>
                                                </li>
                                            @elseif($id == "Forest-Department")
                                                <li class="nav-item">
                                                    <a class="nav-link" id="usdma-tab" data-toggle="tab" href="#usdma" role="tab" aria-controls="usdma" aria-selected="false">Forest Department</a>
                                                </li>
                                            @elseif($id == "Procurement-&-contracts")
                                                <li class="nav-item">
                                                    <a class="nav-link" id="forest-tab" data-toggle="tab" href="#forest" role="tab" aria-controls="forest" aria-selected="false">Procurement & Contracts</a>
                                                </li>
                                            @else
                                            @endif
                                        </ul>
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="pmu" role="tabpanel" aria-labelledby="pmu-tab">
                                                <div id="columnchart_material" style="width: 600px; height: 300px;"></div>
                                            </div>
                                            <div class="tab-pane fade" id="pwd" role="tabpanel" aria-labelledby="pwd-tab">
                                                <div id="columnchart_material" style="width: 600px; height: 300px;"></div>
                                            </div>
                                            <div class="tab-pane fade" id="rwd" role="tabpanel" aria-labelledby="rwd-tab">
                                                <div id="columnchart_material" style="width: 600px; height: 300px;"></div>
                                            </div>
                                            <div class="tab-pane fade" id="usdmsa" role="tabpanel" aria-labelledby="usdmsa-tab">
                                                <div id="columnchart_material" style="width: 600px; height: 300px;"></div>
                                            </div>
                                            <div class="tab-pane fade" id="forest" role="tabpanel" aria-labelledby="forest-tab">
                                                <div id="columnchart_material" style="width: 600px; height: 300px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="x_panel">
                                    <div class="x_title">
                                        <h2><i class="fa fa-bars"></i> Financial Progress</h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">
                                        @if($id == "PWD")
                                            <li class="nav-item">
                                                <a class="nav-link active" id="pmu-financial" data-toggle="tab" href="#pmu-financial" role="tab" aria-controls="pmu-financial" aria-selected="true">PMU</a>
                                            </li>
                                        @elseif($id == "USDMA")
                                            <li class="nav-item">
                                                <a class="nav-link" id="usdma-financial" data-toggle="tab" href="#usdma-financial" role="tab" 
                                                aria-controls="usdma-financial" aria-selected="false">PWD</a>
                                            </li>
                                        @elseif($id == "RWD")  
                                            <li class="nav-item">
                                                <a class="nav-link" id="rwd-financial" data-toggle="tab" href="#rwd-financial" role="tab" aria-controls="rwd-financial" aria-selected="false">RWD</a>
                                            </li>
                                        @elseif($id == "Forest-Department")  
                                            <li class="nav-item">
                                                <a class="nav-link" id="forest-financial" data-toggle="tab" href="#forest-financial" role="tab" aria-controls="forest-financial" aria-selected="false">Forest Department</a>
                                            </li>
                                        @elseif($id == "Procurement-&-contracts")  
                                            <li class="nav-item">
                                                <a class="nav-link" id="procurement-financial" data-toggle="tab" href="#procurement-financial" role="tab" aria-controls="procurement-financial" aria-selected="false">Procurement & Contracts</a>
                                            </li>
                                        @else
                                        @endif
                                        </ul>
                                        <div class="tab-content" id="myTabContent">

                                            <div class="tab-pane fade show active" id="pmu-financial" role="tabpanel" aria-labelledby="pmu-financial">
                                                <div>
                                                    <center style="height:300px;">
                                                        <canvas id="myChartPie" width="400" height="400"></canvas>
                                                    </center>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="usdma-financial" role="tabpanel" aria-labelledby="usdma-financial">
                                                <div>
                                                    <center style="height:300px;">
                                                        <canvas id="myChartPie" width="400" height="400"></canvas>
                                                    </center>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="rwd-financial" role="tabpanel" aria-labelledby="rwd-financial">
                                                <div>
                                                    <center style="height:300px;">
                                                        <canvas id="myChartPie" width="400" height="400"></canvas>
                                                    </center>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="forest-financial" role="tabpanel" aria-labelledby="forest-financial">
                                                <div>
                                                    <center style="height:300px;">
                                                        <canvas id="myChartPie" width="400" height="400"></canvas>
                                                    </center>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="procurement-financial" role="tabpanel" aria-labelledby="procurement-financial">
                                                <div>
                                                    <center style="height:300px;">
                                                        <canvas id="myChartPie" width="400" height="400"></canvas>
                                                    </center>
                                                </div>
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
@stop