
<div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h5 style="font-weight:550;">PROJECT MILESTONE</h5>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                        <div class="row">
                <div class="col-md-6">
                        <label ><b>Start Date:</b></label>
                        <input type="text" class="form-control" id="start_date" name="start_date" value="{{ $defineProject->start_date ?? '' }}" readonly  />
                     <br>
                </div>
            <br>
            <table id="datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width:8%;" ># S.No.</th>
                        <th style="width: 25%">Work Program</th>
                        <th>Total (%)</th>
                        <th>Days</th>
                        <th>Planned Date</th>
                        <th>Actual Date</th> 
                        <th>Documents</th>
                    </tr>
                </thead>
                <tbody style="text-align:center;" id="tableData">
                        @if(count($milestones) > 0 )
                            <input type="hidden" name="id" value="{{ $data->id ?? '' }}" />
                            @php
                                $actualdateHas = "readonly"; 
                            @endphp
                            
                            @foreach($milestones as $key => $param)
                            
                             @php
                              $plannedDate = $param->planned_date ? date('d-m-Y',strtotime($param->planned_date)) : '';
                              $actualDate =  $param->actual_date ? date('d-m-Y',strtotime($param->actual_date)) : '';
                              if($actualDate){
                                 $actualdateHas = "readonly";
                              }
                             @endphp
                            
                            <tr id="tr{{$key}}" >
                                <th>M{{ $key + 1 }}. </th>
                                <th> {{ $param->name ?? '' }} </th>
                                <th> {{ $param->weight ?? '' }}  </th>
                                <th> {{ $param->days ?? '' }}</th>
                                <td> {{ $plannedDate ?? '' }}</td>
                                <td> {{ $actualDate ?? '' }} </td>
                                <td style="width:19%;" >
                                    <a href="{{ url('milestone/view/documents/'.$param->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-file"></i> Documents </a>
                                    <a  href="{{ url('milestone/view/photos/'.$param->id) }}"  class="btn btn-danger btn-sm"><i class="fa fa-file"></i> Photos </a>
                                </td> 
                            </tr>
                            @endforeach
                            @else
                            <tr class="tr-remove" >
                                <td colspan="9">
                                    <center> NO DATA FOUND </center>
                                </td>
                            </tr>
                        @endif
                        
                </tbody>
                <footer  >
                    <tr style="text-align:center;">
                      
                        <th colspan="2"> Total Weightage (%)</th>
                        <th colspan="1"> {{ $milestones->sum('weight') ?? 0 }}% </th>
                        <th colspan="1" >  {{ $milestones->sum('days') ??  0 }}</th>
                         <th colspan="3" >  </th>
                    </tr>
                </footer>
            </table>
            
            <br>
    
            <div style="display:none;">
                <input type="hidden" id="Number" name="number" value="1" />
            </div>
    </div>
                </div>
            </div>
        </div>