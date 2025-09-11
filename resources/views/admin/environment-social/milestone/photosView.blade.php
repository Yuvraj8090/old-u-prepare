@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4> Project Milestone Photos | Project : {{ $data->project->name ?? '' }}</h4>
    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('projects/social/environment/details/'.$data->project->id) }}">Project Details</a></li>
                <li class="breadcrumb-item active"><a href="#">Project Milestone Photos</a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">

    <div class="x_title">
        <p style="font-weight:700;word-wrap: break-word;font-size:25px;">Project Milestones :- {{ $data->name }}</p>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table id="datatable" class="table table-striped table-bordered" style="width:100%;text-align:center;">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 45%;">Images</th>
                                <th style="width: 17%" > Create Date</th>
                                <th style="width: 17%" > Updated Date</th>
                                <th style="width:200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data->photos) > 0)
                                @foreach($data->photos as $key => $d)
                                    <tr>
                                        <th>{{ ++$key }}.</th>
                                        <td>
                                           <img src="{{ url('images/milestone/photos/'.$d->name) }}" width="300px" >
                                        </td>
                                        <td> {{ date('d M, Y h:i A',strtotime($d->created_at)) }}</td>
                                        <td> {{ date('d M, Y h:i A',strtotime($d->updated_at)) }}</td>
                                        <td>
                                            <a onClick="return confirm('Are you sure?')" href="{{ url('milestone/delete/photo/'.$d->id) }}" class="btn  btn-md btn-danger text-white" >
                                                <i class="fa fa-trash"></i> &nbsp;  Delete
                                            </a> 
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                <td colspan="8">
                                    <center> NO DATA FOUND </center>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
    </div>
</div>


@stop