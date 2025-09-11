@extends('layouts.admin')

@section('content')
<style>
    .custom-form-control {
        height: 40px;
        border-radius: 5px;
        margin-right: 5px;
        width: 410px;
        padding: 10px;
    }
</style>

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
          <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4> Templates</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">  Templates </a></li>
            </ol>
        </nav>
    </div>
</div>


<div class="x_panel">
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 1%">S.No </th>
                                <th style="width: 18%">Name</th>
                                <th style="width: 18%" > Template</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @if(count($data) > 0)
                            @foreach($data as $key => $d)
                            <tr class="text-center">
                                <th>{{ ++$key }}</th>
                                <th style="width:40%;">{{ $d->name }}</th>
                                <td><a class="btn btn-md btn-primary" download href="{{ $d->excel }}" >Download Template</a></td>

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
                        {{ $data->links() }}
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@stop