@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>Project Procurement</h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

                <li class="breadcrumb-item active"><a href="#">Project Procurement</a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">
 


  
    

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Projects</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 1%">#S.NO</th>
                                <th style="width: 25%">Project Name</th>
                                <th>Type</th>
                                <th style="width: 10%;">Assign To</th>
                                <th>Est. Cost</th>
                                 <th>Weight Status </th> 
                                <th>Status</th>
                                <th style="width: 15%">Action</th>
                            </tr>
                        </thead>
                      
                            <tbody>
                            @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{ $data->firstItem() + $key }}</td>
                                    <td>
                                        <b>{{ ucfirst($d->name) }}</b><br>
                                        <span class="badge text-white bg-success">Project Id: {{ $d->number}}</span> 
                                      
                                    </td>
                                    <td>{{$d->category->name}} </td>
                                    <td>{{$d->department->department ?? '-' }}</td>
                                    <td>{{ $d->estimate_budget }}</td>
                                    
                                     <td class="project_progress">
                                        <div class="progress progress_sm">
                                                <div class="progress-bar bg-green" role="progressbar"  style="width: {{ $d->weightCompleted ?? 0 }}%;" data-transitiongoal="{{ $d->weightCompleted ?? 0 }}"></div>
                                        </div><small>{{ $d->weightCompleted ?? 0 }}% Complete</small>
                                    </td>

                                    <td>
                                        <span class="">
                                            {{ $d->projectStatus }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('procure.level.three.edit',$d->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i>&nbsp;&nbsp;  Update Work Program </a>
                                    </td>
                                    
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9"><center> NO DATA FOUND </center> </td>
                                </tr>
                            @endif
                        </tbody>
                       
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop