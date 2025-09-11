@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>Work Program | Project: {{ $data->project->name }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ url('/procurement') }}">Procurement</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Create Project Procurement</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <style>
        .btn {
            cursor: pointer;
        }
        table.wpro td {
            vertical-align: middle;
        }
        table.wpro thead th:first-child {
            width: 88px;
        }
        table.wpro thead th:last-child {
            width: 66px;
        }
        table.wpro th.w-150 {
            width: 150px;
        }
        table.wpro th.w-200 {
            width: 150px;
        }
    </style>

    @if($params->count())
        @include('admin.procurement.params')
    @else
        @include('admin.procurement.params-create')
    @endif
@stop
