@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>

            <h4>Create Project</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#"> Create Project </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <form autocomplete="off" data-method="POST" data-action="{{ route('project.store') }}" class="form-horizontal form-label-left ajax-form">
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
                                    <option value="">SELECT</option>
                                    <option value="Project Readiness">Project Readiness</option>
                                    <option value="Regular Loan" selected>Regular Loan</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label ">Project Category</label>

                            <div class="">
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">SELECT</option>

                                    @if(count($category) > 0)
                                        @foreach($category as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

                                <p class="error" id="error-category_id"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Project Sub-Category</label>

                            <div class="">
                                <select class="form-control" id="subcategory" name="subcategory" disabled>
                                    <option value="">SELECT</option>
                                </select>

                                <p class="error" id="error-subcategory"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Project Name</label>
                            <div class="">
                                <input type="text" class="form-control" name="name">

                                <p class="error" id="error-name"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Project Number</label>

                            <div class="">
                                <input type="text" class="form-control" name="number">

                                <p class="error" id="error-number"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Estimated Budget (<span id="eb-price" class="text-dark">₹0.00</span>)</label>
                            <div class=" ">
                                <input type="number" class="form-control" name="estimate_budget" value="0">

                                <p class="error" id="error-estimate_budget"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group ">
                            <label class="control-label">Select Department</label>

                            <input type="hidden" name="assign_to" value="" />

                            <div class="">
                                <select name="assign_to_" id="dept" class="form-control">
                                    <option value="">SELECT</option>
                                    @if(count($department) > 0)
                                        @foreach($department as $dep)
                                            <option value="{{ $dep->id }}">{{ $dep->department }}</option>
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
                <h5 style="font-weight:550;">PROJECT LOCATION</h5>
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
                                    @if(count($assembly) > 0)
                                        @foreach($assembly as $dis)
                                            <option value="{{ $dis->name }}">{{ $dis->name }}</option>
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
                                <select class="form-control" name="constituencie" data-live-search="true" data-style="btn-light">
                                    <option value="">SELECT</option>
                                    @if(count($constituencies) > 0)
                                        @foreach($constituencies as $const)
                                            <option value="{{ $const->name }}">{{ $const->name }}</option>
                                        @endforeach
                                    @endif
                                </select>

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
                                        @foreach($districts as $dis)
                                            <option value="{{ $dis->name }}">{{ $dis->name }}</option>
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
                                    <input type="date" class="form-control datepicker" name="dec_approval_date">
                                    <p class="error" id="error-dec_approval_number"></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="control-label">DEC Approval Letter Number</label>
                                <div>
                                    <input type="text" class="form-control" name="dec_approval_letter_number" />
                                    <p class="error" id="error-dec_letter_number"></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label ">
                                        DEC Approval Document <span style="font-size: 14px">(Minutes of Meeting)</span>
                                        <br />
                                        <small>(Note:- Only PDF file of max. 10MB size is allowed)</small></label>
                                    <div class="">
                                        <input type="file" class="form-control" name="dec_approval_document">
                                        <p class="error" id="error-dec_approval_document"></p>
                                    </div>
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
                                        <input type="date" class="form-control datepicker" name="hpc_approval_date">
                                        <p class="error" id="error-approval_date"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">HPC Approval Letter Number </label>
                                    <div class="">
                                        <input type="text" class="form-control" name="hpc_approval_letter_number">
                                        <p class="error" id="error-approval_number"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">
                                        HPC Approval Document <span style="font-size: 14px">(Minutes of Meeting)</span>
                                        <br />
                                        <small>(Note:- Only PDF file of max. 10MB size is allowed)</small>
                                    </label>
                                    <div class="">
                                        <input type="file" class="form-control" name="hpc_approval_document">
                                        <p class="error" id="error-approval_document"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="">
                                <button id="submit-btn" type="submit" class="btn btn-success">
                                    <span class="loader" id="loader" style="display: none;"></span>
                                    Create Project
                                </button>

                                <a type="reset" class="btn btn-primary pull-right" href="{{ route('project.create') }}">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
        $deptInp = $('input[name="assign_to"');
        $dept    = $('select#dept');
        $subCat  = $('#subcategory');

        $subCat.on('change', function() {
            $val = $(this).val();

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
        });

        $estBin.trigger('keyup');

        $("#assembly").on("change", function (event) {
            event.preventDefault();

            let id = $(this).val();

            $.ajax({
                url: "{{ url('get-tehsil-and-blocks') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    assembly: id
                },
                success: function (response) {
                    $('#district').val(response.district.name);
                    $('#constituencie').val(response.const.name);
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
                url: "{{ url('get-project-subcategories') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    category_id: id
                },
                success: function (response) {
                    if(response) {
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

            $.each(data, function(index, item) {
                $(selector).append($('<option>', {
                    value: item.name,
                    text: item.name
                }));
            });
        }
    </script>
@stop
