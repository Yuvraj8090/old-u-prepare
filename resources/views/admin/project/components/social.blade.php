  <!-- Environment -->
    <div class="col-md-12 col-sm-12  ">
        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;" > SOCIAL SAFEGUARD DETAILS  </h5>
                <div class="clearfix"></div>
            </div>
            
            <div class="x_content"  >
               <h6 style="font-weight:550;" >Social Safeguards Scope : </h6>
               <span style="color:black;font-size:20px;">{!! $data->SocialDefineProject->define_project ?? "" !!} </span> 
            </div>
            
                   @if(false)
            <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Social Screening Report:</label><br>
                                
                                @if(!empty($data->SocialDefineProject->social_screening_report ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $data->SocialDefineProject->social_screening_report }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $data->SocialDefineProject->social_screening_report }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                @endif
                                
                               
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Resettlement Action Plan:</label><br>
                                
                                  @if(!empty($data->SocialDefineProject->social_resettlement_action_plan ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $data->SocialDefineProject->social_resettlement_action_plan }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $data->SocialDefineProject->social_resettlement_action_plan }}" class="btn btn-danger btn-md" >Download Document</a>
                                @endif
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Social Management Plan:</label><br>
                                
                                @if(!empty($data->SocialDefineProject->social_management_plan ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $data->SocialDefineProject->social_management_plan }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $data->SocialDefineProject->social_management_plan }}" class="btn btn-danger btn-md" >Download Document</a>
                                @endif
                                
                              
                            </div>
                        </div>

            <div class="x_content">
                <br>
                 <h5 style="font-weight:550;" >SOCIAL MILESTONES :</h5>
                 @if(count(($data->socialMilestonesSocial ?? [])) > 0)
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
                        @foreach($data->socialMilestonesSocial as $key => $e)
                            <tr>
                                <th>{{++$key}}.</th>
                                <th style="width:40%">{{ $e->name }}</th>
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
                                <th>{{ $data->socialMilestonesSocial->sum('weight') }}%</th>
                                <th>{{ $data->socialMilestonesSocial->sum('days') }}</th>
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