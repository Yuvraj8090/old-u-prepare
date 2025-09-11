@extends('layouts.admin')

@section('content')
    <style>
        .btn-custom{
            padding:10px 3% !important;
        }
        .outerbox{
            margin:10px;
            padding:20px 35px;
            border-radius:10px;
            max-width:23% !important;
        }
    </style>

    <div class="row">
        <div class="outerbox col-md-3 bg-primary">
            <a href="#">
                <h5 class="text-white">Total Grievance</h5>
                <h1 class="text-white">
                    <i class="fa fa-file"></i>
                    {{ $grievances }}
                </h1>
            </a>
        </div>

        <div class="outerbox col-md-3 bg-warning">
            <a href="#">
                <h5 class="text-white">Pending Grievances</h5>
                <h1 class="text-white">
                    <i class="fa fa-file"></i>
                    {{ $pending }}
                </h1>
            </a>
        </div>

        <div class="outerbox col-md-3 bg-success">
            <a href="#">
                <h5 class="text-white">Resolved Grievances</h5>
                <h1 class="text-white">
                    <i class="fa fa-file"></i>
                    {{ $resolved }}
                </h1>
            </a>
        </div>

        <div class="outerbox col-md-3 bg-danger">
            <a href="#">
                <h5 class="text-white">Rejected Grievances</h5>
                <h1 class="text-white">
                    <i class="fa fa-file"></i>
                    {{ '0' }}
                </h1>
            </a>
        </div>
    </div>
    <!-- Report Modal -->
@stop

<!-- code changes added on 9 feb  -->
@section('script')
    
@stop

