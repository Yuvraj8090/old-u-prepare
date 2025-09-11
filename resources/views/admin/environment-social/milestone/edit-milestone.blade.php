@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>Project Milestones | Project : {{ $data->name ?? '' }}</h4>
    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <!--<li class="breadcrumb-item active"><a href="{{ url('/procurement') }}">Procurement</a></li>-->
                <!--<li class="breadcrumb-item active"><a href="#">Create Project Procurement</a></li>-->
            </ol>
        </nav>
    </div>
</div>

    
<div class="x_panel">
    <div class="x_title">
           <!--<button id="addData" class="btn btn-success btn-md pull-right" > + Add More</button>-->
        <h5 style="font-weight:550;">Project Milestone </h5>
            <div class="clearfix"></div>
    </div>

    <div class="x_content">
        
        <div class="row">
                <div class="col-md-6">
                        <label ><b>Start Date:</b></label>
                        <input type="text" class="form-control" id="start_date" name="start_date" value="{{ $data->start_date ?? '' }}" readonly  />
                     <br><br>
                </div>
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
                                    <input type="hidden" name="project_id" value="{{ $data->id }}" /> 
                                    <input type="hidden" name="id" value="{{ $param->id }}" />
                                    <input type="text"  minlength="1"  maxlength="200" name="name"  class="form-control input-readonly" value="{{ $param->name ?? '' }}"  /> 
                                </th>
                                <th> <input type="text" name="weight"  value="{{ $param->weight ?? '' }}" class="number-input form-control input-readonly"   required />   </th>
                                <td> <input type="number" min="1" name="days" data-key="{{ $key + 1 }}" id="days{{ $key + 1 }}" class="form-control days input-readonly"  value="{{ $param->days ?? '' }}"  /> </td>
                                <td>  <input type="text" name="planned_date"  value="{{ $plannedDate ?? '' }}" class="form-control input-readonly" required readonly /> </td>
                                <td>  <input type="text" name="actual_date"  value="{{ $actualDate ?? '' }}" class="form-control input-readonly" required readonly /> </td>
                            </tr>
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
            
            $('#start_date, .days-input').on('input', function() {
                 
                  var dday = 0;
                $('.days-input').each(function() {
                    
                    var days = parseInt($(this).val(),10);
                    var plannedDateInput = $(this).closest('tr').find('.planned-date');
                    
                    if (!isNaN(days) && days !== '') {
                            
                            dday = dday + days;
                            
                            var startDateValue = $(this).val();
                            const originalDate = Date.parse(startDateValue);
                            const newDate = originalDate.addDays(dday);
                            const formattedDate = newDate.toString("yyyy-MM-dd");

                            plannedDateInput.val(formattedDate);
 
                    } else {
                        plannedDateInput.val('');
                    }
                });
            });

            $('#start_date').trigger('input');
            
            $('#start_date').on('change',function(){
                
                var startDateValue = $(this).val();
                const originalDate = Date.parse(startDateValue);
                const newDate = originalDate.addDays(1);
                const formattedDate = newDate.toString("yyyy-MM-dd");
                // console.log(formattedDate);
                        
                if(startDateValue) {
                     $('.days-input').prop('readonly', false);
                }  else{
                     $('.days-input').prop('readonly', true);
                }
            });
            
            $('#tableData').on('click', '.submit-btn', function(event) {
                
                event.preventDefault(); 
              
                var isConfirmed = window.confirm('Are you sure you want to update this because this action not reversible.');
                
                if(!isConfirmed){
                    return false;
                }
              
                var formFields = $(this).closest('tr').find('input, select, textarea');
            
                $.ajax({
                    url: "{{ url('procurement/update/single/three') }}",
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
            
            $('.datepicker').on('change',function(){
                
                var startDateValue = $(this).val();
                var minDate = $(this).data('min');
                
                console.log(startDateValue + " " + minDate);
                
                if(startDateValue < minDate) {
                     $(this).val('');
                     toastr.error('Warniing! Date can not be less than planned date.')
                }
            });
            
        });
    </script>
    
@stop




