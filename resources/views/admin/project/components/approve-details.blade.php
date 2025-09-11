<div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">APPROVAL DETAILS</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="control-label">DEC Approval Letter Number</label>
                            <div class="">
                                <input type="text" class="form-control" value="{{ $data->dec_approval_letter_number ?? '' }}" readonly="">
                	            <p class="error" id="error-dec_approval_letter_number"></p>
    		                </div>
        	            </div>
	                </div>

		            <div class="col-12">
                        <div class="form-group">
                            <label class="control-label ">DEC Approval Date</label>
            		        <div class="">
                        	    <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data->dec_approval_date)) }}" readonly="">
                        	    <p class="error" id="error-dec_approval_date"></p>
            		        </div>
                	    </div>
    		        </div>

		            <div class="col-12">
                	    <div class="form-group">
            		        <label class="control-label">DEC Approval Document</label>
                    	    <div class="">
                        	    @if(isset($data->dec_approval_doc))
                    		        <a onClick="openPDF('{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}')" href="#" class="btn btn-md btn-primary text-white">View Document</a>
                                    <a download href="{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}" class="btn text-white btn-md btn-success">Download Document</a>
                        	    @else
                    		        <h5>Document not uploaded yet!</h5>
                        	    @endif
            		        </div>
                	    </div>
    		        </div>
		        </div>
	        </div>

	        <div class="col-md-6">
		        <div class="row">
		            <div class="col-12">
			            <div class="form-group">
		    	            <label class="control-label">HPC Approval Letter Number</label>
	    		            <div class="">
				                <input type="text" class="form-control" value="{{ $data->hpc_approval_letter_number ?? '' }}" readonly>
				                <p class="error" id="error_hpc_approval_date"></p>
	    		            </div>
			            </div>
     		        </div>

            	    <div class="col-12">
                	    <div class="form-group">
                    	    <label class="control-label">HPC Approval Date</label>
                    	    <div class="">
                        	    <input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data->hpc_approval_date)) }}" readonly="" >
                        	    <p class="error" id="error-approval_date"></p>
                    	    </div>
                	    </div>
    		        </div>

        	        <div class="col-12">
                	    <div class="form-group">
                    	    <label class="control-label">HPC Approval Document</label>
                    	    <div class="">
                        	    @if(isset($data->hpc_approval_doc))
                            	    <a onClick="openPDF('{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}')" href="#" class="btn btn-md btn-primary text-white">View Document</a>
                            	    <a download href="{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}" class="btn text-white btn-md btn-success">Download Document</a>
                        	    @else
                            	    <h5>Document not uploaded yet!</h5>
                        	    @endif
            		        </div>
                	    </div>
        	        </div>
		        </div>
	        </div>
        </div>
    </div>
</div>


{{-- <div class="x_panel">
    <div class="x_title">
        <h5 style="font-weight:550;">APPROVAL DETAILS</h5>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <div class="row">
	    <div class="col-md-6">
		<div class="row">
		    <div class="col-12">
			<div class="form-group">
                    	    <label class="control-label">DEC Approval Letter Number</label>
            		    <div class="">
                        	<input type="text" class="form-control" value="{{ $data->dec_approval_letter_number ?? '' }}" readonly="">
                        	<p class="error" id="error-dec_approval_letter_number"></p>
            		    </div>
                	</div>
    		    </div>

		    <div class="col-12">
                	<div class="form-group">
                    	    <label class="control-label ">DEC Approval Date</label>
            		    <div class="">
                        	<input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data->dec_approval_date)) }}" readonly="">
                        	<p class="error" id="error-dec_approval_date"></p>
            		    </div>
                	</div>
    		    </div>

		    <div class="col-12">
                	<div class="form-group">
            		    <label class="control-label">DEC Approval Document</label>
                    	    <div class="">
                        	@if(isset($data->dec_approval_doc))
                    		    <a onClick="openPDF('{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}')" href="#" class="btn btn-md btn-primary text-white">View Document</a>
                                <a download href="{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}" class="btn text-white btn-md btn-success">Download Document</a>
                        	@else
                    		    <h5>Document not uploaded yet!</h5>
                        	@endif
            		    </div>
                	</div>
    		    </div>
		</div>
	    </div>

	    <div class="col-md-6">
		<div class="row">
		    <div class="col-12">
			<div class="form-group">
		    	    <label class="control-label">HPC Approval Letter Number</label>
	    		    <div class="">
				<input type="text" class="form-control" value="{{ $data->hpc_approval_letter_number ?? '' }}" readonly>
				<p class="error" id="error_hpc_approval_date"></p>
	    		    </div>
			</div>
     		    </div>

            	    <div class="col-12">
                	<div class="form-group">
                    	    <label class="control-label">HPC Approval Date</label>
                    	    <div class="">
                        	<input type="text" class="form-control" value="{{ date('d-m-Y', strtotime($data->hpc_approval_date)) }}" readonly="" >
                        	<p class="error" id="error-approval_date"></p>
                    	    </div>
                	</div>
    		    </div>

        	    <div class="col-12">
                	<div class="form-group">
                    	    <label class="control-label">HPC Approval Document</label>
                    	    <div class="">
                        	@if(isset($data->hpc_approval_doc))
                            	    <a onClick="openPDF('{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}')" href="#" class="btn btn-md btn-primary text-white">View Document</a>
                            	    <a download href="{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}" class="btn text-white btn-md btn-success">Download Document</a>
                        	@else
                            	    <h5>Document not uploaded yet!</h5>
                        	@endif
            		    </div>
                	</div>
        	    </div>
		    </div>
	    </div>
        </div>
    </div>
</div> --}}
