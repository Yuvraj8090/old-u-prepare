@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
              
    @include('admin.include.backButton')

    <h4>Test Details | Project : {{ $projectData->name }}</h4>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">Environment Test</a></li>
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
            <th> Phase  </th>
            <th> Progress</th>
            <th> Duration</th>
            <th> Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
              <th>Pre-Construction Phase </th>
              <th> {{ $data1 ?? 0 }}%</th>
              <td>
                  Start Date : N/A <br>
                  End Date : N/A <br>
              </td>
              
              <td>
                @if(request()->segment('1') == 'project')  
                   <a class="btn btn-primary btn-md" href="{{ url('/project/environement/tests/1/'.$id) }}">Update Activities</a>
                @else
                  <a class="btn btn-success btn-md" href="{{ url('/tests/four/1/'.$id) }}">+ADD</a>
                @endif
                
              </td>
          </tr>
          
          <tr>
              <th>Construction Phase </th>
              <th> {{ $data2 ?? 0 }}%</th>

              <td> 
                  Start Date : N/A <br>
                  End Date : N/A <br>
              </td>
              <td>
                @if(request()->segment('1') == 'project')  
                   <a class="btn btn-primary btn-md" href="{{ url('/project/tests/2/'.$id) }}">Update Activities</a>
                @else
                     <a class="btn btn-success btn-md" href="{{ url('/tests/four/2/'.$id) }}">+ADD</a>
                @endif
              </td>
          </tr>
          
          <tr>
              <th>Post-Construction Phase </th>
              <th> {{ $data3 ?? 0 }}%</th>

              <td> 
                  Start Date : N/A <br>
                  End Date : N/A <br>
              </td>
              <td>
                @if(request()->segment('1') == 'project')  
                   <a class="btn btn-primary btn-md" href="{{ url('/project/environement/tests/3/'.$id) }}">Update Activities</a>
                @else
                    <a class="btn btn-success btn-md" href="{{ url('/tests/four/3/'.$id) }}">+ADD</a>
                @endif
              </td>
          </tr>
        </tbody>
      </table>
  </div>
  

</div>
</div>
@stop






