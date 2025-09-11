@extends('layouts.admin')

@section('content')

<!-- Code added 8 Feb 2024 -->
<div>
    <div style="width:100%;" class="page-title">
        <div style="width:100%;" class="title_left">
            <h4>Project Name : {{ $data->name }} | Project Id- {{ $data->number }}</h4>
        </div>
    </div>


    <div class="clearfix"></div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
            <li class="breadcrumb-item active"><a href="#">Projects Details</a></li>
        </ol>
    </nav>


    <div class="row">
        <div class="col-md-12">

            <!-- code 9 feb change for design change -->
            <div class="row">
                <div class="col-md-6">
                    @include('admin.project.components.project-details')
                </div>
                <div class="col-md-6">
                    @include('admin.project.components.approve-details')
                </div>
                <div class="col-md-12">
                    @if(!empty($defineProject))
                    @include('admin.project.components.procurement')
                    @else
                    @include('admin.project.components.empty',['headline' => 'PROJECT PROCUREMENT' , 'content' => 'Project Procurement step in process...'])
                    @endif
                </div>

                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h5 style="font-weight:550;">WORK PROGRAM UPDATE</h5>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <h1></h1>
                            <br>
                            <table class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width:7%;" > S.No.</th>
                                    <th style="width: 25%">Work Program</th>
                                    <th>Days</th>
                                    <th>Weightage</th>
                                    <th>Planned Date</th>
                                    <th>Actual Date</th> 
                                    <th style="width:8%;" >Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableData">
                                    @if(count($params) > 0 )
                                        <input type="hidden" name="id" value="{{ $data->id }}" />
                                        @foreach($params as $key => $param)
                                          @php
                                            $plannedDate = $param->planned_date ? date('Y-m-d',strtotime($param->planned_date)) : '';
                                            $actualDate =   $param->actual_date ? date('Y-m-d',strtotime($param->actual_date)) : '';
                                          @endphp
                                          
                                        <tr id="tr{{$key}}" >
                                            <td>{{ $key + 1 }} </td>
                                            
                                            <th>
                                                <input type="hidden" name="project_id" value="{{ $data->id }}" />
                                                <input type="hidden" name="id" value="{{ $param->id }}" />
                                                <input type="text"  minlength="1"  maxlength="200" name="name"  class="form-control input-readonly" value="{{ $param->name ?? '' }}" disabled /> 
                                            </th>
                                            <td> <input type="number" min="1" name="days" data-key="{{ $key + 1 }}" id="days{{ $key + 1 }}" class="form-control days input-readonly" value="{{ $param->days ?? '' }}" disabled  /> </td>
                                            <th> <input type="text" name="weight"  value="{{ $param->weight ?? '' }}" class="number-input form-control input-readonly" disabled required />   </th>
                                            <td> <input type="date" name="planned_date"  value="{{ $plannedDate ?? '' }}" class="form-control input-readonly" disabled required /> </td>
                                            <td> <input type="date" name="actual_date"  value="{{ $actualDate ?? '' }}" class="form-control input-readonly"  required /> </td>
                                            <td><button class='btn btn-primary btn-sm submit-btn' type='submit'>Update</button>  </td>
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
                </div>
                
                <div id="bidDocument" @if($document) style="display:none;"  @endif class="col-md-12">
                      <div class="x_panel">
                        <div class="x_title">
                            <h5 style="font-weight:550;">PROCUREMENT BID DOCUMENT  (Note:- Important document for contract mendatory for upload)</h5>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <form autocomplete="off" data-method="POST" data-action="{{ route('procurement.update.bid.document') }}" id="ajax-form" class="form-horizontal form-label-left">
                               @csrf
                               
                                <input type="hidden" name="project_id" value="{{ $data->id }}" />
                                <input type="hidden" name="id" value="{{ $data->defineProject->id ?? '' }}" />
                                                
                                <div class="form-group ">
                                    <label class="control-label ">Bid Document (Note:- Only PDF file allowed.)</label>
                                    <div class="">
                                        @if(isset($data->defineProject->media) && !empty($data->defineProject->media))
                                            <a target="_blank"   href="{{ url('images/bid_document/'.$data->defineProject->media->name) }}" class="btn btn-md btn-success" >View Document </a>  
                                            <a download href="{{ url('images/bid_document/'.$data->defineProject->media->name) }}" class="btn btn-md btn-danger" >Download Document</a>
                                        <br><br>
                                        @endif
                                        <input type="file" name="bid_document" class="form-control" required />
                                    </div>
                                </div>
                                
                                    <div class="ln_solid"></div>
                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-12">
                                            <button id="submit-btn" type="submit" class="btn btn-success">
                                                <span class="loader" id="loader" style="display: none;"></span> 
                                                Upload Bid Document
                                            </button>
                                           
                                        </div>
                                    </div>
                            </form>
                        <div>
                    </div>
                </div>

        </div>
    </div>

</div>
</div>



@stop


@section('script')
<script>
       $('#tableData').on('click', '.submit-btn', function(event) {
          event.preventDefault(); // Prevent the default form submission
          
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
</script>
@stop

