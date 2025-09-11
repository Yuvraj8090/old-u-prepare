@extends('layouts.admin')

@section('header_styles')
    <style>
        table th,
        table td {
            vertical-align: middle !important;
        }
        table th a{
            text-decoration: none !important;
            color:white !important;
        }
    </style>
@endsection

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            <h4>Update Project Progress || Project : {{ $data[0]->name ?? 'N/A' }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#">Update Project Progress</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_content">
            @if(false)
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

                        <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                            <i class="fa fa-search" ></i>
                            Filter
                        </button>

                        <a href="{{ route('project.index') }}" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                        <i class="fa fa-refresh" ></i>
                            Reset
                        </a>

                    </div>
                </form>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_title">
                    <h2>Projects</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th style="min-width: 20px">#</th>
                                <th>Project Name</th>
                                <th>Details</th>
                                <th>Location</th>
                                <th>
                                 
    <a href="{{ request()->fullUrlWithQuery(['sort' => ($sort === 'asc' ? 'desc' : 'asc')]) }}">
        Financial Progress
        @if($sort === 'asc')
           
            <i class="fa fa-sort-amount-asc"></i>
        @else
        
            <i class="fa fa-sort-amount-desc"></i>
        @endif
    </a>
</th>
                                <th>Status</th>
                                <th style="width: 230px">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php $q_tests = isset($q_tests) ? $q_tests : 0; @endphp

                            @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                    @php
                                        $max_length = 50;
                                        if (strlen($d->name) > $max_length)
                                        {
                                            $truncated_text = substr($d->name, 0, $max_length) . "...";
                                        }
                                        else
                                        {
                                            $truncated_text = $d->name;
                                        }

                                        $skip = 1;

                                        if( !$q_tests || ($q_tests && $d->category_id == 1) )
                                        {
                                            $skip = 0;
                                        }
                                    @endphp

                                    @if(!$skip)
                                        <tr>
                                            <th>{{ $data->firstItem() + $key }}.</th>
                                            <td>
                                                <a data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="{{ route('project.details', $d->id) }}">{{ $truncated_text }}</a>
                                                <br>
                                                <span class="badge text-white bg-success">Project Id: {{ $d->number }}</span>
                                                <br>
                                                <span class="badge text-white bg-secondary">Project Type: {{ $d->contract && $d->contract->ms_type ? 'Itemwise' : 'EPC' }}</span>
                                                <small style="display:block;">
                                                    <b>Created On: </b>
                                                    {{ date('d M, Y',strtotime($d->created_at)) }}
                                                </small>
                                                Contract Value: {{ $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}
                                            </td>
                                            <td style="font-size:17px !important;">
                                                <b>Category: </b> {{ $d->category->name }}
                                                <br>
                                                <b>Sub-category: </b> {{ $d->subcategory ?? 'N/A' }}
                                                <br>
                                                <b>Department: </b>
                                                <span class="badge bg-danger text-white">{{ $d->department->department ?? 'N/A' }}</span>
                                                <br>
                                                <b>Start Date: </b>{{ $d->contract->commencement_date ? date("d-m-Y", strtotime($d->contract->commencement_date)) : 'N/A' }}
                                                <br>
                                            </td>
                                            <td style="font-size:17px !important;">
                                                <b>Vidhan Sabha Constituency: </b> {{ $d->assembly ?? "N/A" }}
                                                <br>
                                                <b>Lok Sabha Constituency: </b> {{ $d->constituencie ?? "N/A" }}
                                                <br>
                                                <b>District: </b> {{ $d->district_name ?? "N/A" }}
                                                <br>
                                                <b>Block: </b> {{ $d->block ?? 'N/A' }}
                                                <br>
                                            </td>
                                            <td class="text-center">
                                                <b style="font-size:24px;">{{ $d->progress_percentage }} %</b>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger text-white">{{ $d->projectStatus }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if(!$q_tests)
                                                <a href="{{ route('projectLevel.create', $d->id) }}" class="btn btn-success btn-sm mb-3 mr-0">
                                                    <i class="fa fa-pencil"></i>
                                                    {{-- Update Milestones --}}
                                                    Update Progress
                                                </a>
                                                {{--
                                                <a href="{{ route('projectLevel.create', $d->id) }}" class="btn btn-info btn-sm mr-0">
                                                    <i class="fa fa-pencil"></i>
                                                    Update Financial Progress
                                                </a>
                                                --}}
                                                @endif

                                                @if($q_tests)
                                                {{-- @if($d->category_id == 1) --}}
                                                    {{-- <br> --}}
                                                    <a href="{{ url('quality/update/1/'.$d->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-pencil"></i>
                                                        Update Quality Tests
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">
                                        <center>NO DATA FOUND</center>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                   <div class="pagination-wrapper">
    {{ $data->links('pagination::bootstrap-4') }}
</div>

                </div>
            </div>
        </div>
    @stop
