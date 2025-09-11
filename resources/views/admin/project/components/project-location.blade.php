<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">PROJECT LOCATION</h5>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">

        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Vidhan Sabha Constituency</label>
                    <div class="">
                            <input type="text" class="form-control" id="constituencie" name="constituencie" value="{{ $data->assembly ?? 'N/A' }}" readonly  />

                        <p class="error" id="error-assembly"></p>
                    </div>
                </div>
            </div>
            
                <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Lok Sabha Constituency</label>
                    <div class="">
                        <input type="text" class="form-control" id="constituencie" name="constituencie" value="{{ $data->constituencie ?? 'N/A' }}" readonly  />
                        <p class="error" id="error-constituencie"></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">District</label>
                    <div class="">
                        <input type="text" class="form-control" id="district" name="district" value="{{ $data->district_name }}" readonly  />
                        <p class="error" id="error-district"></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Block</label>
                    <div class="">
                        <input type="text" class="form-control" value="{{ $data->block ?? 'N/A' }}" readonly  />
                        
                    </div>
                </div>
            </div>
            
            
        </div>
        
    </div>
</div>