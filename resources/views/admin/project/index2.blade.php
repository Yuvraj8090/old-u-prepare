@extends('layouts.admin')

@section('content')
<style>
    .custom-form-control{
        height: 40px;
        border-radius: 5px;
        margin-right: 5px;
        width: 350px;
        padding: 10px;
    }a
</style>

@if(request()->segment('1') == "project")
<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>All Projects</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#"> Project Monitoring </a></li>
            </ol>
        </nav>
    </div>
</div>
@elseif(request()->segment('1') == "define")
<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>All Projects</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">  Assign Project  </a></li>
            </ol>
        </nav>
    </div>
</div>
@endif
<style>
    .col-md-4{
        margin-bottom:10px;
    }
    .col-md-2{
         margin-bottom:10px;
    }
</style>
<div class="x_panel">
    <!--<div class="x_content">-->
       
    <!--</div>-->

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" placeholder="Search..." name="name" value="{{ request()->name ?? '' }}" class="form-control"  >
                       
                    </div>
               
                    <div class="col-md-2">
                        <select  name="status" class="form-control">
                        <option value="">Status</option>
                        <option value="0" @if(request('status') == '0') selected @endif>Yet to Initiated</option>
                        <option value="1" @if(request('status') == '1') selected @endif>Pending</option>
                        <option value="2" @if(request('status') == '2') selected @endif>Ongoing</option>
                        <option value="3" @if(request('status') == '3') selected @endif>Completed</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select  name="year" class="form-control">
                        <option value="">Approval Year</option>
                        @if(count($years) > 0)
                            @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}"  @if(request('year') == $ye) selected @endif>{{ $ye }}</option>
                            @endforeach
                        @endif
                    </select>
                    </div>
    
                    <div class="col-md-2">
                        <select name="completion_year" class="form-control">
                        <option value="">Completion Year</option>
                        @if(count($years) > 0)
                            @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}"  @if(request('completion_year') == $ye) selected @endif>{{ $ye }}</option>
                            @endforeach
                        @endif
                    </select>
                    </div>
                   
                     <div class="col-md-2">
                   
                        <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                            <i class="fa fa-search" ></i>
                            Filter
                        </button>
        
                        @if(request()->segment('1') == "define")
                        <a href="{{ url('/define/project') }}" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                            <i class="fa fa-refresh" ></i>
                            Reset
                        </a>
                        @else
                        <a href="{{ route('project.index') }}" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                            <i class="fa fa-refresh" ></i>
                            Reset
                        </a>
                        @endif
                    
                    </div>
                    
                </div>
                </div>
             </form>

                <div class="x_content">

                    <table  id="datatable" class="table table-striped table-bordered" style="width:100%" >
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 20%">Project Name</th>
                                <th style="width:5%;">Category</th>
                            
                                <!--<th>Department</th>-->
                                <!--<th style="width:150px;">Approval Date</th>-->
                                <!--<th style="width:150px;">Est. Cost</th>-->
                                <!--<th>Status</th>-->
                                <!--<th style="width:150px;">Financial % </th>-->
                                <!--<th style="width:150px;">Physical % </th>-->
                                @if(request()->segment(1) != "project")
                                <th style="width: 15%">Action</th>
                                @endif
                               
                            </tr>
                        </thead>
                        <tbody>
                        @if(false)
                            @if(count($data) > 0 && false)
                                @foreach($data as $key => $d)
                                
                                @php
                                $max_length = 50;
                                if (strlen($d->name) > $max_length) {
                                    $truncated_text = substr($d->name, 0, $max_length) . "...";
                                } else {
                                    $truncated_text = $d->name;
                                }
                                @endphp
                                <tr>
                                    <th>{{ $data->firstItem() + $key }}.</th>
                                    <td>
                                        <a  data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="{{ route('project.details',$d->id) }}">{{$truncated_text}} </a><br>
                                        <span class="badge text-white bg-success">Package Number: {{ $d->number}}</span>
                                          <small style="display:block;">Created On : {{ date('d M, Y',strtotime($d->created_at)) }}</small>
                                    </td>
                                    <td>{{$d->category->name}} </td>
                                   
                                    <td> <span class="badge bg-danger text-white" > {{ $d->department->department ?? 'N/A' }} </span> </td>
                                     <td>{{ date("d-m-Y",strtotime($d->approval_date))  }}</td>
                                    <td>{{ formatIndianNumber($d->estimate_budget) }}</td>
                                    <td>
                                        <span class="">
                                            {{ $d->projectStatus }}
                                        </span>
                                    </td>
                                    
                                    <td class="project_progress">
                                            <div class="progress progress_sm">
                                                        <div class="progress-bar bg-green" role="progressbar" style="width:{{ $d->ProjectTotalphysicalProgress ?? 0 }}%;" data-transitiongoal="{{ $d->ProjectTotalphysicalProgress ?? 0 }}"></div>
                                            </div><small>{{ $d->ProjectTotalphysicalProgress  }}% Complete</small>
                                    </td>
                                    <td class="project_progress">
                                            <div class="progress progress_sm">
                                                    <div class="progress-bar bg-green" role="progressbar"  style="width:{{ $d->ProjectTotalfinancialProgress ?? 0 }}%;" data-transitiongoal="{{ $d->ProjectTotalfinancialProgress ?? 0 }}"></div>
                                            </div><small>{{ $d->ProjectTotalfinancialProgress }}% Complete</small>
                                    </td>
                                    @if( request()->segment(2) != "work"  )
                                    <td>

                                            @if(auth()->user()->role->level === 'TWO' && auth()->user()->role->department != 'PROCUREMENT')
                                                <a href="{{ route('project.defineProjectView',$d->id) }}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i>  Define project </a>                                         
                                            @else
                                               
                                                @if(request()->segment(1) == "manage")
                                                    <a href="{{ route('project.edit',$d->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Edit </a><br>
                                                              
                                                @endif
                                     
                                            @endif
                                      
                                    </td>
                                    @else
                                    <td>  <a href="{{ route('project.details',$d->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a></td>
                                    @endif
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10"><center> <b> NO PROJECTS FOUND </b></center> </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $data->links() }}
                    @endif
                    
                </div>
            </div>
        </div>
    </div>

</div>

@stop


@section('script')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            ajax: "{{ asset('project/indexAjax') }}", // URL to fetch data
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'category'  }
                // Add more columns as needed
            ]
        });
    });
</script>
@stop
