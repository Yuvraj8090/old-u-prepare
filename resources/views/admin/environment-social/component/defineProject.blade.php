<div class="x_panel">
        <div class="x_title">
            <h5 style="font-weight:550;"> PROJECT IMPLEMENTATION SUMMARY</h5>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Scope of Work:</label>
                            <div class=" ">
                                {!! ($defineProject->define_project ?? '') !!}
                            </div>
                        </div>
                    </div>
                    
                @if(false)
                    @if(auth()->user()->role->department == "ENVIRONMENT")
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Environment Screening Report:</label><br>
                                
                                @if(!empty($defineProject->environment_screening_report ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $defineProject->environment_screening_report }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $defineProject->environment_screening_report }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                @endif
                                
                               
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Environment Management Plan:</label><br>
                                
                                @if(!empty($defineProject->environment_screening_report ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $defineProject->environment_management_plan }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $defineProject->environment_management_plan }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                @endif
                               
                            </div>
                        </div>
                    @elseif(auth()->user()->role->department == "SOCIAL")
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Social Screening Report:</label><br>
                                
                                @if(!empty($defineProject->social_screening_report ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $defineProject->social_screening_report }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $defineProject->social_screening_report }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                @endif
                                
                               
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Resettlement Action Plan:</label><br>
                                
                                  @if(!empty($defineProject->social_resettlement_action_plan ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $defineProject->social_resettlement_action_plan }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $defineProject->social_resettlement_action_plan }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                @endif
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Social Management Plan:</label><br>
                                
                                @if(!empty($defineProject->social_management_plan ))
                                <a href="javascript:void(0)" onClick="openPDF('{{ $defineProject->social_management_plan }}')" class="btn btn-primary btn-md" >View Document</a>  
                                <a download href="{{ $defineProject->social_management_plan }}" class="btn btn-danger btn-md" >Download Doucment</a>
                                @endif
                                
                              
                            </div>
                        </div>
                    @endif
                @endif

            </div>

        </div>

    </div>