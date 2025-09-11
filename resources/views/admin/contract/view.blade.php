@extends('layouts.admin')

@section('content')
<style>
    th,td{
        width:50% !important;
    }
    th{
        font-size:17px !important;
    }
    td{
        font-size:17px !important;
    }
    h4{
        font-size:18px !important;
    }
</style>
<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <h4>View Contract</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

                <li class="breadcrumb-item active"> <a href="#">Contract</a> </li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">
    <div class="x_content">
        <div class="col-md-12 col-sm-12">
            <h4>PROJECT DETAILS</h4>
            <table class="table table-striped projects">
                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>

                    <tr>
                        <th>Project Name </th>
                        <td>{{ $data->project->name }}</td>
                    </tr>

                    <tr>
                        <th>Project Number </th>
                        <td>{{ $data->project->number }}</td>
                    </tr>

                    <tr>
                        <th>HPC Approval Date  </th>
                        <td>{{ $data->project->defineProject->hpc_date }}</td>
                    </tr>

                    <tr>
                        <th>HPC Approval Number  </th>
                        <td>{{ $data->project->defineProject->hpc_number }}</td>
                    </tr>

                    <tr>
                        <th>Method Of procurement   </th>
                        <td>{{ $data->project->defineProject->method_of_procurement ?? "NULL" }}</td>
                    </tr>
                    <tr>
                        <th>Estimate Value </th>
                        <td>{{ $data->project->estimate_budget }}</td>
                    </tr>
            </table>
        </div>

        <div class="col-md-12 col-sm-12">
            <h4>CONTRACT DETAILS</h4>
            <table class="table table-striped">

                    <tr>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>
                  
                    <tr>
                        <th>Procurement Contract </th>
                        <td>{{ $data->procurement_contract }}</td>
                    </tr>
                    <tr>
                        <th>Performance Guarantee  </th>
                        <td>{{ $data->bid_Fee }}</td>
                    </tr>
                    <tr>
                        <th>Contract Siging date </th>
                        <td>{{ date('d-m-Y',strtotime($data->contract_signing_date)) }}</td>
                    </tr>
                    <tr>
                        <th>Contract End date</th>
                        <td>{{ date('d-m-Y',strtotime($data->end_date)) }}</td>
                    </tr>
                 
            </table>
        </div>

        <div class="col-md-12 col-sm-12">
            <h4>CONTRACTOR DETAILS</h4>
            <table class="table table-striped projects">

            <tr>
                        <th>Name</th>
                        <th>Value</th>
                    </tr>

                  
                    <tr>
                        <th>Contract Agency </th>
                        <td>{{ $data->contract_agency }}</td>
                    </tr>
                    
                    <tr>
                        <th>Contractor Company Name </th>
                        <td>{{ $data->company_name }}</td>
                    </tr>
                    <tr>
                        <th> Authorized Personnel </th>
                        <td>{{ $data->authorized_personel }}</td>
                    </tr>

                    <tr>
                        <th> Company Registered Number </th>
                        <td>{{ $data->company_resgistered_no }}</td>
                    </tr>

                    <tr>
                        <th> Aadhaar Number </th>
                        <td>{{ $data->aadhaar_no  }}</td>
                    </tr>

                    <tr>
                        <th> Contact  </th>
                        <td>{{ $data->contact }}</td>
                    </tr>

                    <tr>
                        <th> Email  </th>
                        <td>{{ $data->email }}</td>
                    </tr>

                    <tr>
                        <th>Contractor address </th>
                        <td>{{ $data->contractor_address  }}</td>
                    </tr>          
            </table>
        </div>
    </div>
</div>

@stop