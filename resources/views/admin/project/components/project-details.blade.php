<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">PROJECT INITIALS</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Type:</label>
                    <div class="">
                        <input type="text" class="form-control" value="{{ $data->project_type }}" readonly />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Category:</label>
                    <div class="">
                        <input type="text" class="form-control" value="{{ $data->category->name }}" readonly />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Sub-Category:</label>
                    <div class="">
                        <input type="text" class="form-control" value="{{ $data->subcategory }}" readonly />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Project Name:</label>
                    <div class="">
                        <input type="text" class="form-control" value="{{ $data->name }}" readonly />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label ">Project Number:</label>
                    <div class=" ">
                        <input type="text" class="form-control" value="{{ $data->number }}" readonly />
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Estimate Budget:</label>
                    <div class="">
                        <input type="text" class="form-control" value="{{ $data->estimate_budget }}" readonly>
                        <p class="error" id="error-approval_date"></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label ">District:</label>
                    <div class=" ">
                        <input type="text" class="form-control" value="{{ $data->district ? $data->district->name : '' }}" readonly />
                    </div>
                </div>
            </div>

             <div class="col-md-6">
                <div class="form-group ">
                    <label class="control-label ">Department:</label>
                    <div class=" ">
                        <input type="text" class="form-control" value="{{ $data->department->department }}" readonly />
                    </div>
                </div>
            </div>
        </div>

        <!--<div class="ln_solid"></div>-->
    </div>
</div>
