@extends('layouts.admin')

@section('content')
<style>
    .col-md-3,.col-md-4,.col-md-2,.col-md-10{
        margin-top:10px;
    }
</style>
<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
          <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>Work Progress</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="#">Work Progress</a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">
    
    <div class="x_content">
        
            <form action="" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" placeholder="Search..." name="name" value="{{ request()->name ?? '' }}" class="form-control"  >
                    </div>
                    
                     
                    
                      @if(isset($category))
                     <div class="col-md-2">
                        <select name="category" id="category_id" class="form-control" >
                            <option value=""> Category </option>
                            @if(count($category) > 0)
                                @foreach($category as $cat)
                                <option  value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                   
                    <div class="col-md-2">
                        <select name="subcategory" id="subcategory" class="form-control" >
                            <option value=""> Sub-Category </option>
                        </select>
                    </div>
                    
                     @endif
                    
                    <div class="col-md-2">
                        <select  name="status" class="form-control">
                        <option value="">Status</option>
                        <option value="0" @if(request('status') == '0') selected @endif>Yet to Initiated</option>
                        <option value="1" @if(request('status') == '1') selected @endif>Pending for contract</option>
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
                   
                     <div class="col-md-10">
 
                        <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white pull-right">
                            <i class="fa fa-refresh" ></i>
                            Reset
                        </a>
                        
                           <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning pull-right">
                            <i class="fa fa-search" ></i>
                            Filter
                        </button>
        

                    </div>
                    
                </div>
                </div>
             </form>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>All Projects</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <table class="table table-striped projects table-bordered">
                        <thead>
                            <tr>
                                <th >S.No</th>
                               <th style="width: 20%">Project Name</th>
                                <th style="width: 20%" > Details</th>
                                <th style="width: 20%" > Location</th>
                                <th style="width:180px;">Contract Value</th>
                                <th style="width:120px;">Status</th>
                                <th>Physical % </th>
                                <th>Financial % </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($data) > 0)
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
                                        <a  data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="{{ route('project.details.three',$d->id) }}" >{{$truncated_text}} </a><br>
                                          <span class="badge text-white bg-success">Project Id: {{ $d->number}}</span>
                                          <small style="display:block;"><b>Created On : </b> {{ date('d M, Y',strtotime($d->created_at)) }}</small>
                                    </td>
                                    <td style="font-size:17px !important;"> 
                                        <b>Category : </b> {{$d->category->name}}  <br>
                                        <b>Sub-category : </b> {{$d->subcategory ?? 'N/A'}}  <br>
                                        <b>Department :</b> <span class="badge bg-danger text-white" >{{ $d->department->department ?? 'N/A' }}</span> <br>
                                        <b>Approval Date :</b> {{ date("d-m-Y",strtotime($d->approval_date))  }}<br>
                                    </td>
                                    
                                     <td style="font-size:17px !important;"> 
                                        <b>Vidhan Sabha Constituency : </b> {{ $d->assembly ?? "N/A" }}  <br>
                                        <b>Lok Sabha Constituency : </b> {{$d->constituencie ?? "N/A"  }}  <br>
                                        <b>District : </b> {{$d->district_name ?? "N/A"  }}  <br>
                                        <b>Block : </b> {{$d->block ?? 'N/A'  }}  <br>
                                    </td>
                                   
                                  
                                     <th style="text-align:center;" >{{   $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}</th>
                                    <td style="text-align:center;" >
                                        <span class="badge bg-danger text-white"> {{ $d->projectStatus }} </span>
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
    
    
@section('script')
<script>

$("#category_id").on("change", function (event) {
    event.preventDefault();
    
    let id = $(this).val();

    $.ajax({
        url: "{{ url('getSubCategory') }}/"+id,
        type: "GET",
        success: function (response) {
            
            if (response) {
                populateSelect('#subcategory', response);
            }
        
        },
        error: function (err) {
            toastr.info("Error! Please Contact Admin.");
        },
    });
});

function populateSelect(selector, data) {
    $(selector).removeAttr('readonly');
    $(selector).removeAttr('disabled');
    
    $(selector).empty(); // Clear existing options
    
    $(selector).append($('<option>', {
        value: '',
        text: 'Select'
    }));
    
    $.each(data, function(index, item) {
        $(selector).append($('<option>', {
            value: item.name,
            text: item.name
        }));
    });
}
</script>
@stop