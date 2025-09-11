@extends('layouts.admin')

@section('content')
    <style>
        .custom-form-control {
            width: 350px;
            height: 40px;
            padding: 10px;
            margin-right: 5px;
            border-radius: 5px;
        }
        .col-md-2,
        .col-md-4 {
            margin-bottom: 10px;
        }
        #datatable_wrapper .dt-layout-row:first-child {
            zoom: 125%;
        }
    </style>

    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>
                <b> U-PREPARE | Project Implementation Report :-  Contract</b>
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#"> Project Implementation Report :-  Contract </a></li>
                </ol>
                <p style="font-size:15px;" class="pull-right"><b>(* Click on the project name for a detailed report)</b></p>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        @include('admin.report.all-filters')

        <div id="tableExportData" class="x_content">
                <h3 class="pdf-heading text-center" style="text-decoration:underline;"> U-PREPARE | Project Implementation Report - Contract</h3>

                <p class="text-center" style="font-size:20px;font-weight:600;" >Department : {{ $filters['department'] }} |  District : {{ $filters['district'] }} | Category : {{ $filters['category'] }} | Sub-Category : {{ $filters['subcategory'] }}</p>
            <br>

            <table id="datatable" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="text-center" colspan="3"> Details</th>
                        <th class="text-center" colspan="2">Contract Details</th>
                    </tr>
                    <tr style="font-weight:700;" >
                        <td>S.No</td>
                        <td>Project Details</td>
                        <td>Approval Details</td>
                        <td>Project Location</td>
                        <td style="width:20%;">Contract Details</td>
                        <td style="width:20%;">Contractor Details</td>
                    </tr>
                </thead>
                <tbody>
                    @if(count($data) > 0)
                        @foreach($data as $key => $d)
                            <?php
                                $max_length = 50;
                                if (strlen($d->name) > $max_length) {
                                    $truncated_text = substr($d->name, 0, $max_length) . "...";
                                } else {
                                    $truncated_text = $d->name;
                                }
                            ?>

                            <tr>
                                <th>{{ $data->firstItem() + $key }}.</th>
                                <td style="width:17%;" >
                                    <a  data-toggle="tooltip" data-placement="top" title="{{ $d->name }} / {{$d->category->name}}"  style="color:blue;" href="{{ route('project.view',$d->id) }}">{{$truncated_text}}/{{$d->category->name}} </a><br>
                                    <span class="badge text-white bg-success">Project Id: {{ $d->number}}</span>
                                    <small style="display:block;"><b>Created On : </b> {{ date('d M, Y',strtotime($d->created_at)) }}</small>
                                    Contract  Value : {{  isset($d->contract) ? number_format($d->contract->procurement_contract) : 'N/A' }}
                                </td>
                                <td style="width:17%;font-size:17px !important;">
                                    <b>Category : </b> {{$d->category->name}}  <br>
                                    <b>Sub-category : </b> {{$d->subcategory ?? 'N/A'}}  <br>
                                    <b>Department :</b> <span class="badge bg-danger text-white" >{{ $d->department->department ?? 'N/A' }}</span> <br>
                                    <b>Approval Date :</b> {{ date("d-m-Y",strtotime($d->approval_date))  }}<br>
                                </td>
                                <td style="width:17%;font-size:17px !important;">
                                    <b>Vidhan Sabha Constituency : </b> {{ $d->assembly ?? "N/A" }}  <br>
                                    <b>Lok Sabha Constituency : </b> {{$d->constituencie ?? "N/A"  }}  <br>
                                    <b>District : </b> {{$d->district_name ?? "N/A"  }}  <br>
                                    <b>Block : </b> {{$d->block ?? 'N/A'  }}  <br>
                                </td>
                                <td style="font-size:17px !important;">
                                    <b> Number :</b> {{ $d->contract->contract_number ?? 'N/A'  }}  <br>
                                    <b> Value:</b> {{ $d->contract->procurement_contract ?? 'N/A' }} <br>
                                    <b>Performance Gurantee:</b> {{ $d->contract->bid_Fee ?? 'N/A' }}  <br>
                                    <b> Signing Date:</b>  {{ $d->contract ? dateFormat($d->contract->contract_signing_date) : 'N/A';  }} <br>
                                    <b> End Date:</b>  {{ $d->contract ? dateFormat($d->contract->end_date) : 'N/A';  }} <br>
                                </td>
                                <td style="font-size:17px !important;">
                                    <b>Company Name :</b> {{ $d->contract->company_name ?? 'N/A'  }}  <br>
                                    <b>Authorized Personnel Name:</b> {{ $d->contract->authorized_personel ?? 'N/A' }} <br>
                                    <b>Type of Registration:</b> {{ $d->contract->registration_type ?? 'N/A' }}  <br>
                                    <b>Phone No.:</b>  {{ $d->contract->contact ?? 'N/A' }} <br>
                                    <b>Email:</b>  {{ $d->contract->email ?? 'N/A' }} <br>
                                    <b>Address:</b>  {{ $d->contract->contractor_address ?? 'N/A' }} <br>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10"><center> <b> NO PROJECTS FOUND </b></center> </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            {{ $data->links() }}
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
