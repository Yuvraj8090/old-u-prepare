<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">PROJECT IMPLEMENTATION SUMMARY</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
            <div class="row">

            <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label">Project Incharge:</label>
                        <div class=" ">
                            <input type="text" class="form-control" name="department" value="{{ $data->PiuLvlThree->name ?? '' }} ({{ $data->piuLvlThree->username }})" readonly />
                            <p class="error" id="error-department"></p>
                        </div>
                    </div>
                </div>

                <!--<div class="col-md-12">-->
                <!--    <div class="form-group">-->
                <!--        <label class="control-label">Incharge Name:</label>-->
                <!--        <div class=" ">-->
                <!--        <input type="text" class="form-control" name="supervisor_name" value="{{ $defineProject->supervisor_name ?? '' }}" disabled>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="col-md-6">-->
                <!--    <div class="form-group">-->
                <!--        <label class="control-label">Designation:</label>-->
                <!--        <div class=" ">-->
                <!--        <input type="text" class="form-control" name="designation" value="{{ $defineProject->supervisor_deisgnation ?? '' }}" disabled >-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="col-md-6">-->
                <!--    <div class="form-group">-->
                <!--        <label class="control-label">Contact:</label>-->
                <!--        <div class=" ">-->
                <!--        <input type="number" class="form-control" name="contact" value="{{ $defineProject->supervisor_contact ?? '' }}"" disabled>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Scope of Work:</label>
                        <div class=" ">
                            {!! ($defineProject->scope_of_work ?? '') !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label"> Objective:</label>
                        <div class=" ">
                            {!! ($defineProject->objective ?? '') !!}
                        </div>
                    </div>
                </div>
            </div>

    </div>

</div>
