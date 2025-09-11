@extends('layouts.admin')

@section('content')
<style>
    .custom-form-control{
        height: 40px;
        border-radius: 5px;
        margin-right: 5px;
        width: 410px;
        padding: 10px;
    }
</style>

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>Procurement Projects</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#"> Procurement Projects  </a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">

    <div class="x_content">
        
        @if(false)
            <form action="" method="GET">

                <div class="btn-group">

                <input type="text" placeholder="Search by project name..." name="name" value="{{ request()->name ?? '' }}" class="custom-form-control"  >

                    <select name="category"  class="select mr-1">
                        <option value="">Type of Project</option>
                        @if(count($category) > 0)
                            @foreach($category as $cat)
                            <option  value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                            @endforeach
                        @endif
                    </select>

                    <select name="department"   class="select  mr-1 ">
                        <option value="">  Departments</option>
                        @if(count($department) > 0)
                            @foreach($department as $dp)
                            <option  value="{{ $dp->id }}" @if(request('department') == $dp->id) selected @endif >{{ $dp->department }}</option>
                            @endforeach
                        @endif
                    </select>

                    <select  name="status" class="select mr-1 ">
                        <option value="">Status</option>
                        <option value="0" @if(request('status') == '0') selected @endif>Yet to Initiated</option>
                        <option value="1" @if(request('status') == '1') selected @endif>Pending</option>
                        <option value="2" @if(request('status') == '2') selected @endif>Ongoing</option>
                        <option value="3" @if(request('status') == '3') selected @endif>Completed</option>
                    </select>

                    <select  name="year" class="select mr-1 ">
                        <option value="">Year</option>
                        @if(count($years) > 0)
                            @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}"  @if(request('year') == $ye) selected @endif>{{ $ye }}</option>
                            @endforeach
                        @endif
                    </select>

                    
                    <select  name="year" class="select mr-1 ">
                        <option value="">Completed Year</option>
                        @if(count($years) > 0)
                            @foreach($years as $ye)
                            <option style="background-color:white;color:black;" value="{{ $ye }}"  @if(request('year') == $ye) selected @endif>{{ $ye }}</option>
                            @endforeach
                        @endif
                    </select>

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
            
            </form>
        @endif 

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">

                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 20%">Project Name</th>
                                <th>Type</th>
                                <th>Est. Cost</th>
                                <th>Status</th>
                                <th>Financial % </th>
                                <th>Physical % </th>
                                <th style="width: 15%">Action</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{ $data->firstItem() + $key }}</td>
                                    <td>
                                        {{ $d->name }}<br>
                                        <span class="badge text-white bg-success">Project Id: {{ $d->number}}</span>
                                    </td>
                                    <td>{{$d->category->name}} </td>
                                    <td>{{ $d->estimate_budget }}</td>
                                    <td>
                                        <span class="">
                                            @if($d->status == 0)
                                                Yet to Initiated
                                            @elseif($d->status == 1)
                                                Pending on <br>procuremnet & contract
                                            @elseif($d->status == 2)
                                                ONGOING
                                            @elseif($d->status == 3)
                                                COMPLETED
                                            @else
                                                --
                                            @endif
                                        </span>
                                    </td>
                                    <td class="project_progress">
                                                    <div class="progress progress_sm">
                                                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ $datum->physicalProgress ?? 0 }}"></div>
                                                    </div><small>{{ $datum->physicalProgress ?? 0 }}% Complete</small>
                                    </td>
                                    <td class="project_progress">
                                            <div class="progress progress_sm">
                                                    <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ $datum->financialProgress ?? 0 }}"></div>
                                            </div>
                                            <small>{{ $datum->financialProgress ?? 0 }}% Complete</small>
                                    </td>
                                    <td> 
                                         <a href="{{ url('procurement-project/edit/'.$d->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> Update Milestones </a> 
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8"><center> NO DATA FOUND </center> </td>
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