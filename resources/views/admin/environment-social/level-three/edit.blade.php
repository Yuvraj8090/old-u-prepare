@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
         <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
          @if(CheckESLevelThree() == 1)
                <h5 style="font-weight:550;">Environmental Safeguard Milestones |  Project : {{ $data->name ?? '' }}</h5>
            @else
              <h5 style="font-weight:550;">Social Safeguard Milestones | Project : {{ $data->name ?? '' }}</h5>
            @endif

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                 <li class="breadcrumb-item active"><a href="{{ url('environment/projects/update') }}">Projects</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('dashboard') }}">MileStone Update</a></li>
            </ol>
        </nav>
    </div>
</div>

    
<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">Project Milestone </h5>
            <div class="clearfix"></div>
    </div>

    <div class="x_content">
        
        <div class="row">

            <br>
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width:8%;" ># S.No.</th>
                        <th style="width: 25%">Work Program</th>
                         <th>Weightage</th>
                        <th>Days</th>
                        <th>Planned Date</th>
                        <th>Actual Date</th> 
                        <th style="width:15%;" >Action</th>
                    </tr>
                </thead>
                <tbody id="tableData">
                        @if(count($params) > 0 )
                            <input type="hidden" name="id" value="{{ $data->id ?? '' }}" />
                            @php
                                $actualdateHas = "readonly"; 
                            @endphp
                            
                            @foreach($params as $key => $param)
                            
                             @php
                              $plannedDate = $param->planned_date ? date('d-m-Y',strtotime($param->planned_date)) : '';
                              $actualDate =  $param->actual_date ? date('d-m-Y',strtotime($param->actual_date)) : '';
                              if($actualDate){
                                 $actualdateHas = "readonly";
                              }
                             @endphp
                            
                            <tr id="tr{{$key}}" >
                                <td>{{ $key + 1 }} </td>
                                
                                <th>
                                    <input type="hidden" name="project_id" value="{{ $data->id }}"  /> 
                                    <input type="hidden" name="id" value="{{ $param->id }}"  />
                                    <input type="text"  minlength="1"  maxlength="200" name="name"  class="form-control input-readonly" value="{{ $param->name ?? '' }}" disabled  /> 
                                </th>
                                <th> <input type="text" name="weight"  value="{{ $param->weight ?? '' }}" class="number-input form-control input-readonly" disabled    />   </th>
                                <td> <input type="number" min="1" name="days" data-key="{{ $key + 1 }}" id="days{{ $key + 1 }}" class="form-control days input-readonly"  value="{{ $param->days ?? '' }}" disabled  /> </td>
                                <td>  <input type="text" name="planned_date"  value="{{ $plannedDate ?? '' }}" class="form-control input-readonly"  disabled /> </td>
                                <td>  <input type="text" name="actual_date"  value="{{ $actualDate ?? '' }}" class="form-control input-readonly datepicker" required  /> </td>
                                <td> 
                                    <button class="btn btn-sm btn-primary submit-btn" href=""> <i class="fa fa-pencil" ></i> &nbsp;  Update </button> 
                                    @if(!empty($actualDate))
                                    <a class="btn btn-sm btn-danger" href="{{ url('es/milestone/images/'.$param->id) }}"> <i class="fa fa-image" ></i>  &nbsp;   Photos </a> 
                                    <a class="btn btn-sm btn-secondary" href="{{ url('es/milestone/documents/'.$param->id) }}"> <i class="fa fa-file" ></i>  &nbsp;   Document </a> 
                                    @endif
                                </td>
                            @endforeach
                             <span id="addInputs" > </span>
                            <tfooter>
                          
                            </tfooter>
                            @else
                            <tr class="tr-remove" >
                                <td colspan="9">
                                    <center> NO DATA FOUND </center>
                                </td>
                            </tr>
                        @endif
                    
                </tbody>
            </table>
            
            <br>
    
            <div style="display:none;">
                <input type="hidden" id="Number" name="number" value="1" />
            </div>
    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>
<div style="display:none;">
<input type="hidden" id="Number" name="number" value="1" />
</div>

@stop

@section('script')

<script>
        $(document).ready(function() {
            $('#tableData').on('click', '.submit-btn', function(event) {
                
                event.preventDefault(); 
              
                var isConfirmed = window.confirm('Are you sure you want to update this because this action not reversible.');
                
                if(!isConfirmed){
                    return false;
                }
              
                var formFields = $(this).closest('tr').find('input, select, textarea');
            
                $.ajax({
                    url: "{{ url('progress/update/milestones') }}",
                    type: 'POST',
                    data: formFields.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        
                        if (response.errors) {
                            var msg = Object.keys(response.errors)[0];
                            msg = response.errors[msg];
                            toastr.error(msg);
                        } else if (response.success) {
                            toastr.success("Success! "+ response.message);
                            if(response.last){
                                $('#bidDocument').show();
                            }
                             if(response.url){
                                setTimeout(function() {
                                    window.location = response.url;
                                }, 500);  
                            }
                        }
                    
                    },
                    error: function (err) {
                        toastr.info("Error! Please Contact Admin.");
                    }
                });
        
            });
        });
    </script>
    
@stop




