@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>{{ $title ?? '' }} </h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('manage-logins') }}">Manage Logins</a></li>
                <li class="breadcrumb-item active"><a href="#">{{ $title ?? '' }}</a></li>
            </ol>
        </nav>
    </div>
</div>

@include('admin.error')

<div class="x_panel">

@if(false)
    <div class="x_title">
        <h2>Filter</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">


    <form action="" method="GET">
        <div class="btn-group">
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
                    <option  value="{{ $dp->id }}" @if(request('department') == $dp->id) selected @endif >{{ $dp->name }}</option>
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

            <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                <i class="fa fa-search" ></i>
                Filter
            </button>

            <a href="{{ route('project.index') }}" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
            <i class="fa fa-refresh" ></i>
                Reset
            </a>

    </form>


        </div>
    </div>

    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Projects</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 1%">#</th>
                                <th style="width: 20%">Project Name</th>
                                <th>Type</th>
                                <th style="width: 10%;">Assign Dept.</th>
                                <th>Est. Cost</th>
                                <th>Physical </th>
                                <th>Financial </th>
                                <th>Status</th>

                                @if(request()->segment(2) != "view")
                                    <th style="width: 15%">Action</th>
                                @endif
                              
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
                                    <td>{{$d->department->department}}</td>
                                    <td>{{ $d->estimate_budget }}</td>
                                    
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

                                    <td style="text-align:center;" >
                                        <span class="">
                                            {{ $d->projectStatus }}
                                        </span>
                                    </td>

                                    @if(request()->segment(2) != "view")
                                    <td>
                                        <a 
                                        href="{{ url('assign-project/user/'.$d->id.'/'.$id) }}" onClick="return confirm('Are you sure you want to assign this project because it will remove this project from previous department.');" class="btn btn-sm btn-primary"> 
                                        Assign Project  </a>
                                    </td>
                                    @endif
                                  
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9"><center> NO DATA FOUND </center> </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $data->links() }}

                </div>
            </div>
        </div>
    </div>



    @stop