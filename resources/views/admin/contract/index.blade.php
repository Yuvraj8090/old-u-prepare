@extends('layouts.admin')

@section('content')
    <style>
        .custom-form-control{
            width: 350px;
            height: 40px;
            padding: 10px;
            margin-right: 5px;
            border-radius: 5px;
        }
        #datatable_wrapper .dt-layout-row:first-child {
            zoom: 125%;
        }
        .col-md-2,
        .col-md-4 {
            margin-bottom: 10px;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>Project Contracts</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Project Contract</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <div class="x_content">
            @include('admin.contract.filters')
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Projects for Contracts</h2>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <table id="datatable" class="table table-striped projects table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th style="width: 17%">Project Name</th>
                                    <th style="width: 17%" > Details</th>
                                    <th style="width: 17%" > Location</th>
                                    <th  style="width: 10%">Contract Value</th>
                                    <th> Sign Date</th>
                                    <th> End Date</th>
                                    <th>Current Status</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($data) > 0)
                                    @foreach($data as $key => $d)
                                        @php
                                            $max_length = 50;
                                            if (strlen($d->name) > $max_length) {
                                                $truncated_text = substr($d->name, 0, $max_length) . "...";
                                            } else {
                                                $truncated_text = $d->name;
                                            }
                                        @endphp

                                        <tr>
                                            <th>{{ $data->firstItem() + $key }}.</th>

                                            <td class="text-left">
                                                <a data-toggle="tooltip" data-placement="top" title="{{ $d->name }}"  style="color:blue;" href="javascript:void(0)">{{ $truncated_text }}</a>
                                                <br />
                                                <span class="badge text-white bg-success">Project Id: {{ $d->number }}</span>
                                                <small style="display:block;"><b>Created On : </b> {{ date('d M, Y',strtotime($d->created_at)) }}</small>
                                            </td>

                                            <td style="font-size:17px !important;">
                                                <b>Category : </b> {{ $d->category->name }}
                                                <br />
                                                <b>Sub-category : </b> {{ $d->subcategory ?? 'N/A' }}
                                                <br />
                                                <b>Department :</b> <span class="badge bg-danger text-white" >{{ $d->department->department ?? 'N/A' }}</span>
                                                <br />
                                                <b>Approval Date :</b> {{ date("d-m-Y", strtotime($d->hpc_approval_date))  }}
                                                <br />
                                            </td>

                                            <td style="font-size:17px !important;">
                                                <b>Vidhan Sabha Constituency  : </b> {{ $d->assembly ?? "N/A" }}
                                                <br />
                                                <b>Lok Sabha Constituency  : </b> {{$d->constituencie ?? "N/A"  }}
                                                <br />
                                                <b>District : </b> {{$d->district_name ?? "N/A"  }}
                                                <br />
                                                <b>Block : </b> {{$d->block ?? 'N/A'  }}
                                                <br />
                                            </td>

                                            <th class="text-center" >
                                                {{ $d->contract ? number_format($d->contract->procurement_contract) : 'N/A' }}
                                            </th>

                                            <td class="text-center">
                                                @if(isset($d->contract->contract_signing_date))
                                                    {{ date('d-m-Y',strtotime($d->contract->contract_signing_date)) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if(isset($d->contract->end_date))
                                                    {{ date('d-m-Y',strtotime($d->contract->end_date)) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>

                                            <td class='text-center'>
                                                @if($d->stage == "0")
                                                    Yet to initaite
                                                @elseif($d->stage == "1")
                                                    Pending for contract
                                                @elseif($d->stage == "2")
                                                    Completed
                                                @else
                                                    Completed
                                                @endif
                                            </td>

                                            <td>
                                                @if(!$d->contract)
                                                    <a href="{{ url('contract/create/'.$d->id) }}" class="btn btn-primary btn-sm"> Create Contract </a>
                                                @else
                                                    <a href="{{ url('contract/edit/'.$d->contract->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fa fa-pencil"></i>&nbsp;&nbsp;  Edit Contract
                                                    </a>

                                                    <a href="{{ url('contract-security/index/'.$d->contract->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fa fa-pencil"></i>&nbsp;&nbsp;Add Securities
                                                    </a>

                                                    <!--<a href="{{ url('contract/view/'.$d->contract->id) }}" class="btn btn-primary btn-sm">-->
                                                    <!--    <i class="fa fa-eye"></i>&nbsp;&nbsp;  View Contract -->
                                                    <!--</a> -->

                                                    <a href="{{ url('contract-amend/index/'.$d->contract->id) }}" class="btn btn-success btn-sm">
                                                        <i class="fa fa-pencil"></i>&nbsp;&nbsp; Amend Contract
                                                    </a>

                                                    @if($d->status == 1)
                                                        <a href="{{ url('contract/close/'.$d->contract->id) }}" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-close"></i>&nbsp;&nbsp;  Cancel Contract
                                                        </a>
                                                    @endif

                                                    @if(false)
                                                        <a href="{{ url('contract/close/'.$d->contract->id) }}" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-close"></i>&nbsp;&nbsp;  Cancel Contract
                                                        </a>
                                                    @endif

                                                    @if(auth()->user()->role->name === 'PROCUREMENT-LEVEL-TWO')

                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9"><center> NO DATA FOUND </center> </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('modal')
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cancel Contract: </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off" data-method="POST" data-action="" id="ajax-form" class="form-horizontal form-label-left" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Security Name </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" placeholder="Enter Security Name " name="name" />
                                <p class="error-project" id="error-name"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Forms of Security</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" placeholder="Enter Security Name " name="name" />
                                <p class="error-project" id="error-name"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Issue Start Date </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control datepicker" placeholder="Enter Starting Date" name="start_date" />
                                <p class="error-project" id="error-start_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Issue End Date </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control datepicker" placeholder="Enter End Date" name="end_date" />
                                <p class="error-project" id="error-end_date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Security Number </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" placeholder="Enter Number" name="security_number" />
                                <p class="error-project" id="error-security_number"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Issuing Authority </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="text" class="form-control" placeholder="Enter Issuing Authority" name="issuing_authority" />
                                <p class="error-project" id="error-issuing_authority"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Amount </label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="number" class="form-control" placeholder="Enter Security Amount" name="amount" />
                                <p class="error-project" id="error-amount"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3 col-sm-3 ">Upload Security Docuemnts</label>
                            <div class="col-md-9 col-sm-9 ">
                                <input type="file" class="form-control" name="files[]"  multiple  />
                                <p class="error-doxument" id="error-files"></p>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button id="submit-btn" type="submit" class="btn btn-success btn-sm">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Add Security
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('script')
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.bootstrap4.css">
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>

    <script>
        @if($data->count())
        let table = new DataTable('#datatable', {
            pageLength: 5,
            lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
        });
        @endif

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

            $.each(data, function(index, item) {
                $(selector).append($('<option>', {
                    value: item.name,
                    text: item.name
                }));
            });
        }
    </script>
@stop
