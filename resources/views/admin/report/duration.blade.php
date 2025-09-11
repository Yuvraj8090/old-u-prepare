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
            <h4>  <b> U-PREPARE | Project Finance Report :- Duration Wise </b>
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            </h4>


            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#"> Project finance report :- Duration wise </a></li>
                </ol>
                <p style="font-size:15px;" class="pull-right"><b>(* Click on the project name for a detailed report)</b></p>
            </nav>
        </div>
    </div>


    <div class="x_panel">

        <form action="" method="GET">
            <div class="row">

                <div class="col-md-2">
                    <select name="department"  class="form-control" >
                        <option value="">DEPARTMENT   </option>
                        @if(count($department) > 0)
                            @foreach($department as $a)
                            <option  value="{{ $a->id }}"  @if(request('department') == $a->id) selected @endif >{{ $a->department }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                    <div class="col-md-2">
                    <select name="district"  class="form-control"  >
                        <option value=""> DISTRICT  </option>
                        @if(count($districts) > 0)
                            @foreach($districts as $d)
                            <option  value="{{ $d->name }}"  @if(request('district') == $d->name) selected @endif  >{{ $d->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="category" id="category_id" class="form-control" >
                        <option value="">CATEGORY   </option>
                        @if(count($category) > 0)
                            @foreach($category as $cat)
                            <option  value="{{ $cat->id }}"  @if(request('category') == $cat->id) selected @endif >{{ $cat->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="subcategory" id="subcategory" class="form-control"  >
                        <option value=""> SELECT CATEGORY FIRST... </option>
                    </select>
                </div>

                    <div class="col-md-4">
                    <button type="submit" style="border-radius:5px;margin-left:20px;" class="btn btn-warning">
                        <i class="fa fa-search" ></i>
                        Filter
                    </button>


                    <a href="javascript:void(0)" onClick="refreshPageWithoutQueryParams()" style="border-radius:5px;margin-left:10px;" class="btn btn-danger text-white">
                        <i class="fa fa-refresh" ></i>
                        Reset
                    </a>


                    <button type="button" onclick="exportToPDF()" style="border-radius:5px;margin-left:10px;" class="btn btn-md btn-primary pull-right" >
                        <i class="fa fa-file" ></i> &nbsp; Export in PDF
                    </button>

                </div>

                <div class="col-md-3">
                    <lable style="font-weight:bolder;" class="label">Start Date: (Milestone)</lable>
                    <input type="date" class="form-control" name="startDate" placholder="start Date.."  value="{{ request()->startDate ?  date('Y-m-d',strtotime(request()->startDate)) : '' }}"  />
                </div>

                <div class="col-md-3">
                            <lable style="font-weight:bolder;" class="label">End Date: (Milestone)</lable>
                    <input type="date" class="form-control" name="endDate" placholder="End Date.."value="{{ request()->endDate ?  date('Y-m-d',strtotime(request()->endDate)) : '' }}"  />
                </div>

            </div>
            </div>
        </form>

        <div id="tableExportData" class="x_content">
            <h3 class="pdf-heading text-center" style="text-decoration:underline;"> U-PREPARE | Project Finance Report - Duration Wise</h3>

            <p class="text-center" style="font-size:20px;font-weight:600;" >Department : {{ $filters['department'] }} |  District : {{ $filters['district'] }} | Category : {{ $filters['category'] }} | Sub-Category : {{ $filters['subcategory'] }}</p>
            <br>

            <table id="datatable" class="table table-striped table-bordered" style="width:100%" >
                <thead>
                    <tr>
                        <th rowspan="1">#</th>
                        <th class="text-center" colspan="3">Details</th>
                        <th class="text-center" colspan="3">Financial Details</th>
                    </tr>
                    <tr style="font-weight:700;">
                        <td>S.No</td>
                        <td>Project Details</td>
                        <td>Approval Details</td>
                        <td>Project Location</td>
                        <td>Contract Value</td>
                        <td>Financial Progress (%)</td>
                        <td>Cumulative Expense</td>
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
                                <b>Assembly : </b> {{ $d->assembly ?? "N/A" }}  <br>
                                <b>Constituencie : </b> {{$d->constituencie ?? "N/A"  }}  <br>
                                <b>District : </b> {{$d->district_name ?? "N/A"  }}  <br>
                                <b>Block : </b> {{$d->block ?? 'N/A'  }}  <br>
                            </td>

                            <th class="text-center" style="font-size:17px !important;">
                                {{  isset($d->contract) ? number_format($d->contract->procurement_contract) : 'N/A' }}
                            </th>

                            <td class="project_progress">
                                    <div class="progress progress_sm">
                                                <div class="progress-bar bg-green ui-progressbar ui-corner-all ui-widget ui-widget-content" role="progressbar" style="width:{{$d->ProjectTotalfinancialProgress}}%;" data-transitiongoal="100" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="ui-progressbar-value ui-corner-left ui-widget-header" style="display: none; width: 5.96296px;"></div></div>
                                    </div><small>{{$d->ProjectTotalfinancialProgress}}% Complete</small>
                            </td>

                            <th style="text-align:center;" >
                                {{ number_format($d->ProjectTotalfinancialAccumulativeCost ?? 0) }}
                            </th>
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
