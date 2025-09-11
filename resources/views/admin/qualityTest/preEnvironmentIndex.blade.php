@extends('layouts.admin')

@section('content')


<div>
  <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
              
    @include('admin.include.backButton')

    <h4>    
    @if($moduleType == 1)
        ENVIRONMENT SAFEGUARD ACTIVITIES
    @elseif($moduleType == 2)
        SOCIAL SAFEGUARD ACTIVITIES
    @else
        TEST DETAILS
    @endif
            | Project : {{ $projectData->name }}
    </h4>
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
            <th> No. of activities</th>
            <th>Activities Completed</th>
            <th>Progress(%)</th>
            <th> Duration</th>
            <th> Action</th>
          </tr>
        </thead>
        <tbody>
        @if(count($data) > 0)
            @foreach($data as $d)
                <tr>
                      <th>{{ $d['name'] }}</th>
                      <th>{{ $d['total_activities'] }}</th>
                      <th>{{ $d['total_completed'] }}</th>
                       <td class="project_progress">
                                            <div class="progress progress_sm">
                                                        <div class="progress-bar bg-green" role="progressbar" style="width:{{ $d['percentage'] ?? 0 }}%;" data-transitiongoal="{{ $d['percentage'] ?? 0 }}"></div>
                                            </div><small>{{ $d['percentage']  }}% </small>
                        </td>

                      <td>
                          Start Date :{{$d['start']}} <br>
                          End Date : {{$d['end']}}<br>
                      </td>
                      <td>
                        @if($yes)
                          <a class="btn btn-success btn-md" href="{{ url('/tests/'.$d['type'].'/'.$id) }}">+ADD</a>
                        @else
                            <a class="btn btn-primary btn-md" href="{{ url('/tests/four/'.$d['type'].'/'.$id) }}">Update Activities</a>
                        @endif
                      </td>
                  </tr>
              @endforeach
          
          @endif
        
        </tbody>
      </table>
  </div>
  

</div>
</div>
@stop






