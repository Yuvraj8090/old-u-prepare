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
            <h4>  <b> U-PREPARE | Project Finance Report :- Admin Expenses Report</b>
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton" >
                <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="#"> Admin Expenses Report </a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="x_panel">
        <form action="" method="GET">
            <div class="row">

                <div class="col-md-3">
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
                    <select name="year"  class="form-control" >
                        <option value="">YEAR   </option>
                        @if(count($years) > 0)
                            @foreach($years as $y)
                            <option  value="{{ $y }}"  @if(request('year') == $y) selected @endif >{{ $y }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                {{--
                <!--  <div class="col-md-3">-->
                <!--   <select name="quarter"  class="form-control" >-->
                <!--        <option value="">QUARTERS   </option>-->
                <!--        <option value="1" @if(request('quarter') == $y) selected @endif > JAN-MAR </option>-->
                <!--        <option value="2" @if(request('quarter') == $y) selected @endif > APR-JUN </option>-->
                <!--        <option value="3" @if(request('quarter') == $y) selected @endif >JUL-SEP </option>-->
                <!--        <option value="4" @if(request('quarter') == $y) selected @endif >OCT-DEC </option>-->
                <!--    </select>-->
                <!--</div>-->
                --}}

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
            </div>
        </form>
    </div>

    <div id="tableExportData" class="x_content">
        <h3 class="pdf-heading text-center" style="text-decoration:underline;">U-PREPARE | Project Finance Report - Admin Expenses Report</h3>
        <p class="text-center" style="font-size:20px;font-weight:600;">Department : {{ $filters['department'] }} | Year : {{ request()->year ?? 'All'}}</p>
        <br>

        <table id="datatable" class="table table-striped table-bordered text-center w-100">
            <thead>
                <tr style="font-weight:700;">
                    <th>S.No</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Office Equipement Expenses</th>
                    <th>Electricity Expenses</th>
                    <th>Transport Expenses</th>
                    <th>Salaries Expenses</th>
                    <th>Rent Expenses</th>
                    <th>Other Expenses</th>
                    <th>Total Expenses</th>
                </tr>
            </thead>

            <tbody>
                @if(count($dataFinance) > 0)
                    @foreach($dataFinance as $key => $d)
                        <tr>
                            <th>{{ ++$key }}.</th>
                            <th>{{ $d->department->department ?? 'N/A' }}</th>
                            <th>{{ $d->year }}</th>
                            <td>{{ formatIndianNumber($d->office_exp) }}</td>
                            <td>{{ formatIndianNumber($d->electricty_exp) }}</td>
                            <td>{{ formatIndianNumber($d->transport_exp) }}</td>
                            <td>{{ formatIndianNumber($d->salaries_exp) }}</td>
                            <td>{{ formatIndianNumber($d->rent_exp) }}</td>
                            <td>{{ formatIndianNumber($d->miscelleneous_exp) }}</td>
                            <td>{{ formatIndianNumber($d->total_exp) }}</td>
                        </tr>
                    @endforeach

                    <tr style="border:black solid 3px;" >
                        <th colspan="3" > Total Expenses </th>
                        <th> {{ formatIndianNumber($dataFinance->sum('office_exp')) }}</th>
                        <th> {{ formatIndianNumber($dataFinance->sum('electricty_exp')) }}</th>
                        <th> {{formatIndianNumber( $dataFinance->sum('transport_exp')) }}</th>
                        <th> {{ formatIndianNumber($dataFinance->sum('salaries_exp')) }}</th>
                        <th> {{ formatIndianNumber($dataFinance->sum('rent_exp')) }}</th>
                        <th> {{ formatIndianNumber($dataFinance->sum('miscelleneous_exp')) }}</th>
                        <th> {{ formatIndianNumber($dataFinance->sum('total_exp')) }}</th>
                    </tr>
                @else
                    <tr>
                        <td colspan="10"><center> <b> NO PROJECTS FOUND </b></center> </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@stop



@section('script')
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.2/css/dataTables.bootstrap4.css">
    <script src="//cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>

    <script>
        @if($dataFinance->count())
        let table = new DataTable('#datatable', {
            pageLength: 5,
            lengthMenu: [[-1, 5, 10, 25, 50, 100, 200, 500], ['All', 5, 10, 25, 50, 100, 200, 500]]
        });
        @endif
    </script>
@endsection
