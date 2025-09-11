@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4> Project Milestone Documents | Project : {{ $data->project->name ?? '' }}</h4>
    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('projects/social/environment/details/'.$data->project->id) }}">Project Details</a></li>
                <li class="breadcrumb-item active"><a href="#">Project Milestone Documents</a></li>
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
        <table id="datatable" class="table table-striped table-bordered" style="width:100%;text-align:center;">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 45%;">Document</th>
                                <th style="width: 17%" > Create Date</th>
                                <th style="width: 17%" > Updated Date</th>
                            </tr>
                        </thead>
                        <tbody  >
                            @if(count($data->document) > 0)
                                @foreach($data->document as $key => $d)
                                    <tr>
                                        <th>{{ ++$key }}.</th>
                                        <td>
                                            <a href="javascript:void(0)" onclick="openPDF('{{ url('images/milestone/document/'.$d->name) }}')" class="btn btn-primary btn-md">View Document</a>  
                                            <a download href="{{ url('images/milestone/document/'.$d->name) }}" class="btn btn-danger btn-md">Download Doucment</a>
                                        </td>
                                        <td> {{ date('d M, Y h:i A',strtotime($d->created_at)) }}</td>
                                        <td> {{ date('d M, Y h:i A',strtotime($d->updated_at)) }}</td>
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