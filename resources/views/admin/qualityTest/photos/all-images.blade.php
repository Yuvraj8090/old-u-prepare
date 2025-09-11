@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
              
    @include('admin.include.backButton')

    <h4>@if($moduleType == 1)
        ALL ENVIRONMENT SAFEGUARD ACTIVITIES PHOTOS
    @elseif($moduleType == 2)
        ALL SOCIAL SAFEGUARD ACTIVITIES PHOTOS
    @else
        TEST DETAILS
    @endif</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">Test Photos</a></li>
      </ol>
    </nav>
  </div>
</div>

<div class="row x_panel">
    
<div class="col-md-12">
    <div class="x_content">
        
      <h3 style="padding:10px;" class="bg-success text-white">All Photos</h3>

      <table class="table text-center table-striped projects table-bordered">
        <thead>
          <tr>
            <th>S.No</th>
            <th style="width:40%;">Image</th>
            <th> Created  At</th>
            <th> Updated At</th>
        
       
          </tr>
        </thead>
        <tbody>
            @if(count($data) > 0)
            @foreach($data as $k=>$d)
                <tr >
                    <th>{{ $data->firstItem() + $k }}.</th>
                    <td>
                        <img src="{{$d->name}}" heighht="300" width="100%"  />
                    </td>
                    <td>
                        {{ $d->created_at }}
                    </td>
                    <td>
                        {{ $d->updated_at }}
                    </td>

                    
                </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">No data Found</td>
            </tr>
            @endif
        </tbody>
        {{ $data->links() }}
      </table>
  </div>
  

</div>
</div>
@stop




