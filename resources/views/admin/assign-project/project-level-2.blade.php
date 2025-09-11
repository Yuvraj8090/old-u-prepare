@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>Assign Project</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Assign Lvl 3</a></li>
            </ol>
        </nav>
    </div>
</div>

@include('admin.error')

<div class="x_panel">
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
    </div>

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
                                <th>Assigned Dept Name.</th>
                       
                                <th>Type</th>
                                <th>Est. Cost</th>
                                <th>Status</th>
                                <th style="width: 20%">Action</th>
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
                                    
                                     <td> <span class="badge bg-danger text-white" > Name: {{ $d->PiuLvlThree->name ?? 'N/A' }}  </span> </td>
                                     
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
                                    <td>                       
                                        <a href="javascript:(0);" data-id="{{ $d->id }}"  class="btn getProjectId btn-primary btn-xs" data-toggle="modal" data-target="#myModal" >
                                            <i class="fa fa-folder"></i> &nbsp;&nbsp; Assign Project 
                                        </a>
                                    </td>
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


@section('modal')
<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Update Assign Project to level Three :</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        
        </div>
        <div class="modal-body">

        <form action="{{ route('AssignProjectlevelThree.update') }}" method="POST">
            @csrf
            <input type="hidden" id="projectid" name="project_id" value="" />

            <div class="form-group">
                <label for="exampleInputEmail1">Select:</label>
                <select id="selectDepartment" class="form-control" name="department" >
                    <option value="" >SELECT</option>
                    @if(count($AssignDepartment) > 0)
                        @foreach($AssignDepartment as $depart)
                            <option value="{{ $depart->id }}" >{{ $depart->name }}</option>
                        @endforeach
                    @endif
                </select>
                <p class="error" id="error-department"></p>
            </div>

            <div style="display:none;" id="departmentShow"  >

            <h5>Department Details:</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"> Name:</label>
                        <div class=" ">
                           <input type="text" id="departName" class="form-control"  readonly  />
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Designation:</label>
                        <div class=" ">
                           <input type="text" id="departDesignation" class="form-control"  readonly />
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Contact:</label>
                        <div class=" ">
                           <input type="text"  id="departContact" class="form-control"  readonly  />
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Email:</label>
                        <div class=" ">
                           <input type="text" id="departEmail" class="form-control"  readonly   />
                        </div>
                    </div>
                </div>

              
            </div>

           


            </div>


            <button type="submit" class="btn btn-primary">Submit</button>

        </form>

        

        </div>
      </div>
      
    </div>
  </div>
@stop

@section('script')
<script>
    $('#selectDepartment').change(function(e) {
        e.preventDefault();

        $('#departmentShow').hide();
        var id = $(this).val();
        
        $.ajax({
            url: "{{ url('show/department/details/') }}/" + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {

                $('#departName').val(response.name);
                $('#departDesignation').val(response.designation);
                $('#departContact').val(response.phone_no);
                $('#departEmail').val(response.email);

                $('#departmentShow').show();

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });

    });
</script>
@stop
