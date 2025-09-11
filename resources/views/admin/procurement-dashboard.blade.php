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
            <span class="count_top"><i class="fa fa-user"></i> ongoing </span>
            <div class="count green"> {{ $data['Ongoing'] ?? 0 }}</div>
            <!-- <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span> -->
        </div>

    </div>
</div>
<!-- /top tiles -->


@stop

