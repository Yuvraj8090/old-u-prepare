  <!-- Environment -->
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;" > {{ $headline }} </h5>
                <div class="clearfix"></div>
            </div>

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
            <th>Action</th>
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
                                                        <div class="progress-bar bg-green" role="progressbar" style="width:{{ $d['percentage']  ?? 0 }}%;" data-transitiongoal="{{ $d['percentage']  ?? 0 }}"></div>
                                            </div><small>{{ $d['percentage']  }}% Complete</small>
                    </td>

                      <td>
                          Start Date :{{$d['start']}} <br>
                          End Date : {{$d['end']}}<br>
                      </td>
                      <td>
                          <a class="btn btn-primary btn-md" href="{{ url('all/tests/'.$module.'/'.$d['type'].'/'.$d['id']) }}">View</a>
                      </td>
                     
                  </tr>
              @endforeach
          
          @endif
        
        </tbody>
      </table>
  </div>
  

</div>
            
            </div>
        </div>
    </div>
