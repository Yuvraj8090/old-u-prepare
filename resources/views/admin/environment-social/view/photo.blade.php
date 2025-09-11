@extends('layouts.admin')

@section('content')

<!-- code added 7 Feb 2024 -->
<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
         <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>Project Milestone Photos </h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#"> Photos of Project Milestone </a></li>
            </ol>
        </nav>
    </div>
</div>




<div class="x_panel">
    <div class="x_title">

        <h5 style="font-weight:550;">{{ $milestone->name }} photos</h5>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">


        <table id="datatable" class="table table-striped table-bordered" style="width:100%;text-align:center;">

            <thead>
                <tr>
                    <th style="width: 8%">S. No #</th>
                    <th style="width: 20%">Image</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center">Updated At</th>
                    
                </tr>
            </thead>
            @if(count($data) > 0)
            @foreach($data as $k => $d)
            <tr>
                <th>{{ ++$k }}.</th>
                <th>
                    <img src="{{ asset('images/milestone/photos/'.$d->name) }}" style="width:200px" />
                </th>
                <td class="text-center">{{ date('d M, Y H:i A',strtotime($d->created_at)) }}</td>
                <td class="text-center">{{ date('d M, Y H:i A',strtotime($d->updated_at)) }}</td>
            </tr>
            @endforeach
            @endif
            <tbody>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>
</div>

@stop