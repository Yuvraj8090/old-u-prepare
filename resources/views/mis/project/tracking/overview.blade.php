@extends('layouts.admin')

@section('content')
    <style>
        table.table tbody th,
        table.table tbody td {
            font-weight: bold;
            vertical-align: middle;
        }

        table.table .btn {
            margin: 0;
        }

        table.table .progress.progress_sm {
            margin-bottom: 10px;
        }

        table.table .progress.progress_sm + small {
            font-weight: bolder;
        }
    </style>
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            @include('admin.include.backButton')

            <h4>{{ strtoupper($type) . ' SAFEGUARD ACTIVITIES' }} | Project: {{ $project->name }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Environment Compliances</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <br>

    <div class="col-md-12">
        <div class="x_content">
            <table class="table text-center table-striped projects table-bordered">
                <thead>
                    <tr>
                        <th>Phase</th>
                        <th>No. of activities</th>
                        <th>Activities Completed</th>
                        <th>Progress(%)</th>
                        <th>Duration</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($progress as $phase)
                        @if($phase->id < 4)
                        <tr>
                            <th>{{ $phase->name }}</th>
                            <td>{{ $phase->safeguard_rules->count() }}</td>
                            <td>{{ $phase->entries_count }}</td>
                            <td class="compliances_progress">
                                <div class="progress progress_sm">
                                    @php $centage = number_format( $phase->safeguard_rules->count() ? ( ($phase->entries_count / $phase->safeguard_rules->count()) * 100 ) : 0, 2) @endphp
                                    <div class="progress-bar bg-green" role="progressbar" style="width:{{ $centage }}%;" data-transitiongoal="{{ $centage }}"></div>
                                </div>
                                <small>{{ $centage }}%</small>
                            </td>
                            <td></td>
                            <td>
                                <a href="{{ route('mis.project.tracking.safeguard.entry.sheet', [$type, $project->id, 'phase='.$phase->slug]) }}" class="btn btn-md btn-info">Update Compliances</a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
