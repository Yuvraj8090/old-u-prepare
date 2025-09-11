  <!-- Environment -->
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;" > ENVIRONMENT SAFEGUARD DETAILS  </h5>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content" >
               <h6 style="font-weight:550;" >  Environmental Safeguards Scope : </h6>
               <span style="color:black;font-size:20px;">{!! $data->EnvironmentDefineProject->define_project ?? "" !!} </span> 
            </div>
            
            
            @if(false)
            <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Environment Screening Report:</label><br>
                                
                                @if(!empty($data->EnvironmentDefineProject->environment_screening_report ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $data->EnvironmentDefineProject->environment_screening_report }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $data->EnvironmentDefineProject->environment_screening_report }}" class="btn btn-danger btn-md" >Download Document</a>
                                @endif
                                
                               
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Environment Management Plan:</label><br>
                                
                                @if(!empty($data->EnvironmentDefineProject->environment_screening_report ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $data->EnvironmentDefineProject->environment_management_plan }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $data->EnvironmentDefineProject->environment_management_plan }}" class="btn btn-danger btn-md" >Download Document</a>
                                @endif
                               
                            </div>
                        </div>

            <div class="x_content">
                <br>
                 <h5 style="font-weight:550;" > MILESTONES :</h5>
                 @if(count(($data->environmentMilestones ?? [])) > 0)
                    <table class="table table-bordered table-stripped" >
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Milestone</th>
                            <th>Weightage (%)</th>
                            <th>Days</th>
                            <th>Planned Date</th>
                            <th>Actual Date</th>
                            <th>Documents</th>
                        </tr>   
                    </thead>
                        <tbody class="text-center" >
                        @foreach($data->environmentMilestones as $key => $e)
                            <tr>
                                <th>{{++$key}}.</th>
                                <th style="width:40%" >{{ $e->name }}</th>
                                <th>{{ $e->weight }}</th>
                                <td>{{ $e->days }}</td>
                                <td>{{ date("d-m-Y",strtotime($e->planned_date)) }}</td>
                                <td>{{ $e->actual_date ?  date("d-m-Y",strtotime($e->planned_date)) : 'N/A' }}</td>
                                <td>
                                     <a href="{{ url('environment/social/documents/'.$e->id) }}" class="btn btn-sm btn-primary" ><i class="fa fa-file"></i> Documents</a>
                                     <a href="{{ url('environment/social/photos/'.$e->id) }}" class="btn btn-sm btn-danger" ><i class="fa fa-image"></i> Photos</a>
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <th colspan="2" style="text-align:right;" >Total</th>
                                <th>{{ $data->environmentMilestones->sum('weight') }}%</th>
                                <th>{{ $data->environmentMilestones->sum('days') }}</th>
                                <th colspan="2" ></th>
                            </tr>
                        </tbody>
                    </table>
                 @endif
            </div>
            @endif
        </div>
    </div>
    <!-- Environment -->