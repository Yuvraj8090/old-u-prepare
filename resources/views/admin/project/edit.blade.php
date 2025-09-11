@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            <h4>Update Project | Project : {{ $data->name  }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#"> Update Project </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <form autocomplete="off" data-method="POST" data-action="{{ route('project.update.new',$data->id) }}" id="ajax-form" class="form-horizontal form-label-left">
        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">PROJECT INITIALS</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Project Type</label>
                            <div>
                                <select class="form-control" name="project_type" required>
                                    <option>Kindly Choose...</option>
                                    <option value="Project Readiness" @selected($data->project_type == "Project Readiness")>Project Readiness</option>
                                    <option value="Regular Loan" @selected($data->project_type == "Regular Loan")>Regular Loan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label ">Project Category</label>

                            <div class="">
                                <select id="category_id" class="form-control" name="category_id">
                                    <option value="">SELECT TYPE</option>
                                    @if(count($category) > 0)
                                        @foreach($category as $cat)
                                            <option value="{{ $cat->id }}" @if($cat->id == $data->category_id) selected @endif>{{ $cat->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <p class="error" id="error-category_id"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label ">Project Sub-Category</label>

                            <div class="">
                                <select class="form-control" id="subcategory" name="sub_category" >
                                    <option value="">SELECT </option>
                                    <option value="{{ $data->subcategory }}" selected>{{ $data->subcategory }} </option>
                                </select>

                                <p class="error" id="error-sub_category"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Project Name </label>

                            <div class="">
                                <input type="text" class="form-control" name="name" value="{{ $data->name }}" >
                                <p class="error" id="error-name"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Project Number </label>

                            <div class="">
                                <input type="text" class="form-control" name="number" value="{{ $data->number }}">
                                <p class="error" id="error-number"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Estimated Budget (<span id="eb-price" class="text-dark">₹0.00</span>)</label>

                            <div class="">
                                <input type="number" class="form-control" name="estimate_budget" value="{{ $data->estimate_budget }}">
                                <p class="error" id="error-estimate_budget"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Select Department</label>
                            <input type="hidden" name="assign_to" value="" />

                            <div class="">
                                <select id="dept" class="form-control" name="assign_to_">
                                    <option value="">Select Dept.</option>
                                    @if(count($department) > 0)
                                        @foreach($department as $dep)
                                            <option value="{{ $dep->id }}" @if($dep->id == $data->assign_to) selected @endif>{{ $dep->department }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <p class="error" id="error-assign_to"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;text-transform:uppercase">Project Location</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Vidhan Sabha Constituency</label>

                            <div class="">
                                <select class="form-control" id="assembly" name="assembly" data-live-search="true" data-style="btn-light">
                                    <option value="">SELECT</option>
                                    <option value="All"  @if('All' == $data->assembly) selected @endif >All</option>
                                    @if(count($assembly) > 0)
                                        @foreach($assembly as $dis)
                                            <option value="{{ $dis->name }}" @if($dis->name == $data->assembly) selected @endif>{{ $dis->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <p class="error" id="error-assembly"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lok Sabha Constituency</label>

                            <div class="">
                                <select class="form-control" name="constituencie">
                                    <option value="">SELECT</option>
                                    <option value="All" @if('All' == $data->constituencie) selected @endif >All</option>
                                    @if(count($constituencies) > 0)
                                        @foreach($constituencies as $const)
                                            <option value="{{ $const->name }}" @if($const->name == $data->constituencie) selected @endif >{{ $const->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <!--<input type="text" class="form-control" id="constituencie"  readonly  />-->
                                <p class="error" id="error-constituencie"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">District</label>

                            <div class="">
                                <select class="form-control" name="district" required>
                                    <option value="">SELECT</option>
                                    @if(count($districts) > 0)
                                        @foreach($districts as $diss)
                                            <option value="{{ $diss->name }}" @if($diss->name == $data->district_name) selected @endif >{{ $diss->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <!--<input type="text" class="form-control" id="district" name="district" readonly  />-->
                                <p class="error" id="error-district"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Block</label>

                            <div class="">
                                {{-- <select class="form-control" id="teshsil" name="block"  required >
                                    <option value="" >SELECT</option>
                                    <option value="All" @if('All' == $data->block) selected @endif >All</option>
                                    <option value="{{ $data->block }}" selected >{{ $data->block }}</option>
                                </select> --}}

                                <input type="text" name="block" class="form-control" placeholder="Enter comma seperated blocks name..." />
                                <p class="error" id="error-block"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">APPROVAL DETAILS</h5>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="m-0 p-0">Department Empowered Committee (DEC)</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">DEC Approval Date</label>
                                <div>
                                    <input type="date" class="form-control datepicker" name="dec_approval_date" value="{{ date('Y-m-d', strtotime($data->dec_approval_date)) }}">
                                    <p class="error" id="error-dec_approval_number"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">DEC Approval Letter Number</label>
                                <div>
                                    <input type="text" class="form-control" name="dec_approval_letter_number" value="{{ $data->dec_approval_letter_number }}" />
                                    <p class="error" id="error-dec_letter_number"></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label ">
                                        DEC Approval Document <span style="font-size: 14px">(Minutes of Meeting)</span>
                                        <br />
                                        <small>(Note:- Only PDF file of max. 10MB size is allowed)</small>
                                    </label>
                                    <div class="">
                                        <input type="file" class="form-control" name="dec_approval_document">
                                        <p class="error" id="error-dec_approval_document"></p>
                                    </div>
                                    @if(isset($data->dec_approval_doc))
                                        <a href="javascript:void(0)" onClick="openPDF('{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}')" class="btn btn-md btn-primary text-white">View Document</a>
                                        <a download href="{{ url('images/project/'. ($data->dec_approval_doc->name) ?? '') }}" class="btn text-white btn-md btn-success">Download Document</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="m-0 p-0">High Powered Committee (HPC)</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">HPC Approval Date</label>
                                    <div class="">
                                        <input type="date" class="form-control datepicker" name="hpc_approval_date" value="{{ date('Y-m-d', strtotime($data->hpc_approval_date)) }}">
                                        <p class="error" id="error-approval_date"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">HPC Approval Letter Number</label>
                                    <div class="">
                                        <input type="text" class="form-control" name="hpc_approval_letter_number" value="{{ $data->hpc_approval_letter_number }}">
                                        <p class="error" id="error-approval_number"></p>
                                    </div>
                                </div>
                            </div>

                            <!--<div class="col-md-6">-->
                            <!--    <div class="form-group">-->
                            <!--        <label class="control-label ">Project Approved By </label>-->
                            <!--        <div class="">-->
                            <!--            <input type="text" class="form-control" name="approved_by">-->
                            <!--            <p class="error" id="error-approved_by"></p>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">
                                        HPC Approval Document <span style="font-size: 14px">(Minutes of Meeting)</span>
                                        <br />
                                        <small>(Note:- Only PDF file of max. 10MB size ise allowed)</small>
                                    </label>
                                    <div class="">
                                        <input type="file" class="form-control" name="hpc_approval_document">
                                        <p class="error" id="error-approval_document"></p>
                                    </div>
                                    @if(isset($data->hpc_approval_doc))
                                        <a href="javascript:void(0)" onClick="openPDF('{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}')" class="btn btn-md btn-primary text-white">View Document</a>
                                        <a download href="{{ url('images/project/'. ($data->hpc_approval_doc->name) ?? '') }}" class="btn text-white btn-md btn-success">Download Document</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    {{--
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label ">Project Approval Number </label>
                            <div class="">
                                <input type="text" class="form-control" value="{{ $data->approval_number }}" name="approval_number">
                                <p class="error" id="error-approval_number"></p>
                            </div>
                        </div>
                    </div>
                    --}}

                    {{--
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Project Approved By </label>
                            <div class="">
                                <input type="text" class="form-control" value="{{ $data->approved_by }}"  name="approved_by">
                                <p class="error" id="error-approved_by"></p>
                            </div>
                        </div>
                    </div>
                    --}}

                    {{--
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Approval Date </label>
                            <div class="">
                                <input type="text" class="form-control datepicker" name="approval_date" value="{{ date('d-m-Y',strtotime($data->approval_date)) }}" >
                                <p class="error" id="error-approval_date"></p>
                            </div>
                        </div>
                    </div>
                    --}}

                    {{--
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label ">Approval Document (Note:- Only PDF file allowed.) </label><br>
                            @if(isset($data->media->name))
                                <a href="javascript:void(0)" onClick="openPDF('{{ url('images/project/'. ($data->media->name) ?? '') }}')" class="btn btn-md btn-primary text-white">View Document</a>
                                <a download href="{{ url('images/project/'. ($data->media->name) ?? '') }}" class="btn text-white btn-md btn-success">Download Document</a>
                            @endif
                            <div class="">
                                <input type="file" class="form-control" name="approval_document">
                                <p class="error" id="error-approval_document"></p>
                            </div>
                        </div>
                    </div>
                    --}}


                    <div class="col-md-12">
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="">
                                <button id="submit-btn" type="submit" class="btn btn-success">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Update Project
                                </button>

                                <a type="reset" class="btn btn-primary pull-right" href="{{ route('project.create') }}">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>

@stop

@section('script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css" />
    <style>
        .bootstrap-select > .dropdown-toggle {
            color: #495057 !important;
            border: 1px solid #ced4da;
            background: transparent;
            border-radius: 8px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
        $('#assembly').selectpicker();

        $deptInp = $('input[name="assign_to"');
        $dept    = $('select#dept');
        $subCat  = $('#subcategory');

        $subCat.on('change', function() {
            $val = $(this).val();
            console.log('Yes')
            $dept.attr('disabled', 'yes');
            if($val == 'Slope Protection' || $val == 'Bridge') {
                $dept.val(8)
            } else if($val == 'Building') {
                $dept.val(9);
            } else {
                $dept.val('');
                $dept.removeAttr('disabled');
            }

            $dept.trigger('change');
        })

        $dept.on('change', function() {
            $deptInp.val($(this).val());
        })

        $subCat.trigger('change');

        $estBin = $('input[name="estimate_budget"]');

        $estBin.on('keyup', function() {
            $('#eb-price').text(currencyFormatter.format($(this).val()));
        })

        $estBin.trigger('keyup');

        $("#assembly").on("change", function (event) {
            event.preventDefault();

            let id = $(this).val();

            if(id == "All"){
                return false;
            }

            $.ajax({
                url: "{{ url('teshsilsAndBlocks') }}/"+id,
                type: "GET",
                success: function (response) {

                    $('#constituencie').val(response.const.name);
                    $('#district').val(response.district.name);

                    // if(response.teshils){
                    //     populateSelect('#teshsil', response.teshils);
                    // }

                },
                error: function (err) {
                    toastr.info("Error! Please Contact Admin.");
                },
            });
        });

        $("#category_id").on("change", function (event) {
            event.preventDefault();

            let id = $(this).val();

            $.ajax({
                url: "{{ url('getSubCategory') }}/"+id,
                type: "GET",
                success: function (response) {

                    if (response) {
                        populateSelect('#subcategory', response);
                    }

                },
                error: function (err) {
                    toastr.info("Error! Please Contact Admin.");
                },
            });
        });

        function populateSelect(selector, data) {
            $(selector).removeAttr('readonly');
            $(selector).removeAttr('disabled');

            $(selector).empty(); // Clear existing options

            $(selector).append($('<option>', {
                value: '',
                text: 'Select'
            }));

            $(selector).append($('<option>', {
                value: 'All',
                text: 'All'
            }));

            $.each(data, function(index, item) {
                $(selector).append($('<option>', {
                    value: item.name,
                    text: item.name
                }));
            });
        }
    </script>
@stop
