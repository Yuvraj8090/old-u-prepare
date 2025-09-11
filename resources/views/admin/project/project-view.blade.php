@extends('layouts.admin')

@section('content')
    <style>
        .bg-success1{
            background-color:#008000 !important;
        }
    </style>

    <div>
        <div style="width:100%;" class="page-title">
            <div style="width:100%;" class="title_left">
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
                <h4>Project Name : {{ $data->name }} <br> Project Id- {{ $data->number }} </h4>
            </div>
        </div>

        <div class="clearfix"></div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
                <li class="breadcrumb-item active"><a href="#">Projects Details</a></li>
            </ol>
        </nav>

        <div style="font-size:20px;" >
            <button type="button" onclick="exportToPDF()" style="border-radius:5px;margin-left:10px;" class="btn btn-md btn-primary pull-right" >
                <i class="fa fa-file" ></i> &nbsp; Export in PDF
            </button>

            <div  class="col-md-12">
                <div class="row">

                    <div id="tableExportData" class="col-md-12">
                        <div  class="x_panel">
                            <div class="x_content">

                                <h3 style="font-weight:700;" class="text-center bg-light"> Project Summary Report </h3>
                                <br>

                                <table class="table table-bordered" >
                                    <tr class="bg-success1 text-white text-center" >
                                        <th colspan="6" >Project Details</th>
                                    </tr>
                                    <tr>
                                        <th>Project Name</th>
                                        <td colspan="5" style="width:80%;" >{{ $data->name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Project Number</th>
                                        <td colspan="2" class="text-center">{{ $data->number  }} </td>
                                        <th> Category</th>
                                        <td colspan="2" class="text-center">{{ $data->category->name }} </td>
                                    </tr>
                                    <tr>
                                        <th> Sub-category</th>
                                        <td colspan="2" class="text-center">{{ $data->subcategory }} </td>
                                        <th>Approval Date</th>
                                        <td colspan="2" class="text-center"> {{ date("d-m-Y",strtotime($data->approval_date))  }}</td>
                                    </tr>
                                    <tr>
                                        <th>Department</th>
                                        <td colspan="2" class="text-center">{{ $data->department->department }} </td>
                                        <th>Lok Sabha Constituency </th>
                                        <td colspan="2" class="text-center">{{ $data->assembly   }} </td>
                                    </tr>
                                    <tr>
                                        <th>Vidhan Sabha Constituency </th>
                                        <td colspan="2" class="text-center">{{ $data->constituencie  }} </td>
                                        <th>District </th>
                                        <td colspan="2" class="text-center">{{ $data->district_name  }} </td>
                                    </tr>
                                    <tr>
                                        <th>Block </th>
                                        <td colspan="4" class="text-center">{{ $data->block  }} </td>
                                    </tr>
                                </table>
                                <br>

                                <table class="table table-bordered text-center" >
                                    <tr class="bg-success1 text-white " >
                                        <th colspan="6" >Procurement</th>
                                    </tr>
                                    <tr>
                                        <th style="width:20%;">Method of procurement </th>
                                        <td colspan="2" class="text-center">{{ $defineProject->method_of_procurement ?? 'N/A' }} </td>
                                        <th style="width:20%;">Tender Fee </th>
                                        <td colspan="2" class="text-center"> {{ $defineProject->bid_fee ?? 'N/A'}} </td>
                                    </tr>
                                    <tr>
                                        <th style="width:20%;" >Earnest Money Deposit</th>
                                        <td class="text-center">{{ $defineProject->earnest_money_deposit ?? 'N/A'}} </td>
                                        <th  style="width:20%;"> Bid Validity (In Days)</th>
                                        <td class="text-center">{{ $defineProject->bid_validity ?? 'N/A' }} </td>
                                        <th style="width:25%;"> Bid Completion Period (In Days)</th>
                                        <td class="text-center">{{ $defineProject->bid_completion_days ?? 'N/A' }} </td>
                                    </tr>
                                </table>

                                <table class="table table-bordered text-center" >
                                    <tr class="bg-success1 text-white text-left" >
                                        <th colspan="6" >Procurement Program</th>
                                    </tr>

                                    <tr class="bg-success1 text-white text-center" >
                                        <th style="width:4%;" ># S.No.</th>
                                        <th style="width:25%;"  >Work Program</th>
                                        <th style="width:10%;">Days</th>
                                        <th style="width:10%;">Weightage in(%)</th>
                                        <th style="width:15%;" >Planned Date</th>
                                        <th style="width:25%;" >Actual Date</th>
                                    </tr>

                                    @if(!empty($params) && count($params) > 0)
                                        @foreach($params as $key => $param)
                                            @php
                                                $plannedDate = $param->planned_date ? date('d-m-Y',strtotime($param->planned_date)) : '';
                                                $actualDate  = $param->actual_date ? date('d-m-Y',strtotime($param->actual_date)) : 'N/A';
                                            @endphp

                                            <tr class="text-center" >
                                                <td >{{ $key + 1 }}.</td>
                                                <th >{{ $param->name }} </th>
                                                <td > {{ $param->days }} </td>
                                                <th > {{ $param->weight }}  </th>
                                                <td >{{ $plannedDate }}  </td>
                                                <td >{{ $actualDate ?? 'N/A' }} </td>
                                            </tr>

                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center" > Procuremnt program not defined in progress.... </td>
                                        </tr>
                                    @endif
                                </table>

                                <br>
                                <table class="table table-bordered" >
                                    <tr class="bg-success1 text-white text-center" >
                                        <th colspan="6" >Contract</th>
                                    </tr>
                                    <tr class="bg-success1 text-white text-left" >
                                        <th colspan="6"  >Contract Details</th>
                                    </tr>

                                    <tr>
                                        <th>Contract Number</th>
                                        <td class="text-center">{{ $contracts->contract_number ?? 'N/A'  }} </td>
                                        <th> Contract Value</th>
                                        <td class="text-center">{{ $contracts->procurement_contract ?? 'N/A' }} </td>
                                        <th>Performance Guarantee </th>
                                        <td class="text-center">{{ $contracts->bid_Fee ?? 'N/A' }} </td>
                                    </tr>

                                    <tr>
                                        <th>Contract Signing date </th>
                                        <td colspan="2" class="text-center">{{ isset($contracts->contract_signing_date) ? date('d-m-Y', strtotime($contracts->contract_signing_date))  : 'N/A' }} </td>
                                        <th>Contract End date </th>
                                        <td colspan="2" class="text-center">{{ isset($contracts->end_date) ? date('d-m-Y', strtotime($contracts->end_date))  : 'N/A' }} </td>
                                    </tr>


                                    <tr class="bg-success1 text-white text-left" >
                                        <th colspan="6"  >Contractor Details</th>
                                    </tr>


                                    <tr>
                                        <th>Company Name</th>
                                        <td colspan="2" class="text-center">{{ $contracts->company_name ?? 'N/A' }} </td>
                                        <th> Authorized Personnel Name </th>
                                        <td colspan="2" class="text-center">{{ $contracts->authorized_personel ?? 'N/A' }} </td>
                                    </tr>

                                    <tr>
                                        <th> Company Type of Registration </th>
                                        <td colspan="2" class="text-center">{{ $contracts->registration_type  ?? 'N/A' }} </td>
                                        <th> Company Registered Number </th>
                                        <td colspan="2" class="text-center">{{ $contracts->company_resgistered_no   ?? 'N/A' }}</td>
                                    </tr>

                                    <tr>
                                        <th>Phone No.</th>
                                        <td colspan="2" class="text-center">{{ $contracts->contact  ??  'N/A' }} </td>
                                        <th>Email </th>
                                        <td colspan="2" class="text-center">{{ $contracts->email  ?? 'N/A' }} </td>
                                    </tr>

                                    <tr>
                                        <th colspan="2" >Contractor address (Contractor / Vendor / Firms) </th>
                                        <td colspan="4" class="text-center">{{ $contracts->contractor_address  ??  'N/A' }} </td>
                                    </tr>
                                </table>
                                <br>

                                <table class="table table-bordered" >
                                    <tr class="bg-success1 text-white text-center" >
                                        <th colspan="6" >Project Implementation</th>
                                    </tr>

                                    <tr class="bg-success1 text-white text-center" >
                                        <th  >Scope of Work</th>
                                    </tr>
                                    <tr class=" text-center" >
                                        <td colspan="3" style="width:50%;" >{!! ($defineProject->scope_of_work ?? '') !!}</td>
                                    </tr>
                                    <tr class="bg-success1 text-white text-center" >
                                        <th  >Objective</th>
                                    </tr>
                                    <tr class=" text-center" >
                                            <td colspan="3"  style="width:50%;">{!! ($defineProject->objective ?? '') !!}</td>
                                    </tr>
                                </table>


                                <table class="table table-bordered" >
                                    <tr class="bg-success1 text-white text-center" >
                                        <th colspan="9" >Project Milestones</th>
                                    </tr>

                                    <tr class="bg-success1 text-white text-center" >
                                        <th>S.No </th>
                                        <th >Milestone</th>
                                        <th> Budget</th>
                                        <th>Total work (%)</th>
                                        <th> Date </th>
                                        <th>Amended Date</th>
                                        <th>Physical Progress</th>
                                        <th>Financial Progress</th>
                                    </tr>

                                    @if(!empty($milestones) && (count($milestones) > 0))
                                        @foreach($milestones as $key => $datum)
                                            <tr>
                                                <th>M{{ ++$key }}.</th>
                                                <th style="width:20%;">{{ $datum->name }}</th>
                                                <td>{{$datum->budget}}</td>
                                                <th style="text-align:center;">{{ $datum->percent_of_work}}%</th>
                                                <td>
                                                    Start Date : {{ date('d-m-Y',strtotime($datum->start_date))}}
                                                    <br>
                                                    End Date :  {{ date('d-m-Y',strtotime($datum->end_date)) }}
                                                </td>
                                                <td>
                                                    Start Date : {{ $datum->amended_start_date ? date('d-m-Y',strtotime($datum->amended_start_date)) : 'N/A'}}
                                                    <br>
                                                    End Date : {{ $datum->amended_end_date ? date('d-m-Y',strtotime($datum->amended_end_date)) : 'N/A'}}
                                                </td>
                                                <td class="project_progress">
                                                    <div class="progress progress_sm">
                                                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ $datum->physicalProgress }}" style="width:{{ $datum->physicalProgress ?? 0 }}%;"></div>
                                                    </div>
                                                    <small>{{ $datum->physicalProgress }}% Complete</small>
                                                </td>

                                                <td class="project_progress">
                                                    <div class="progress progress_sm">
                                                        <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="{{ $datum->financialProgress }}%"  style="width:{{ $datum->financialProgress ?? 0 }}%;"  ></div>
                                                    </div>
                                                    <small>{{ $datum->financialProgress }}% Complete</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th>Project Milestone is not defined in progress.</th>
                                        </tr>
                                    @endif
                                </table>
                                <br>

                                @if($data->category_id == 1)
                                    <table class="table table-bordered" >
                                        <tr class="bg-success1 text-white text-center">
                                            <th>Environmental Safeguards Scope</th>
                                        </tr>
                                        <tr>
                                            <td class="text-left" >
                                                {!! $data->EnvironmentDefineProject->define_project ?? "In progress..." !!}
                                            </td>
                                        </tr>

                                        <tr class="bg-success1 text-white text-center" >
                                            <th> Milestones</th>
                                        </tr>

                                        <table class="table table-bordered table-stripped" >
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Milestone</th>
                                                    <th>Weightage (%)</th>
                                                    <th>Days</th>
                                                    <th>Planned Date</th>
                                                    <th>Actual Date</th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-center" >
                                                @if(count(($data->environmentMilestones ?? [])) > 0)
                                                    @foreach($data->environmentMilestones as $key => $e)
                                                        <tr>
                                                            <th>{{++$key}}.</th>
                                                            <th style="width:40%" >{{ $e->name }}</th>
                                                            <th>{{ $e->weight }}</th>
                                                            <td>{{ $e->days }}</td>
                                                            <td>{{ date("d-m-Y",strtotime($e->planned_date)) }}</td>
                                                            <td>{{ $e->actual_date ?  date("d-m-Y",strtotime($e->planned_date)) : 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <th colspan="2" style="text-align:right;" >Total</th>
                                                        <th>{{ $data->environmentMilestones->sum('weight') }}%</th>
                                                        <th>{{ $data->environmentMilestones->sum('days') }}</th>
                                                        <th colspan="2" ></th>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="6" >Milestone not defined in progress...</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </table>

                                    <table class="table table-bordered" >
                                        <tr class="bg-success1 text-white text-center">
                                            <th>Social Safeguards Scope</th>
                                        </tr>
                                        <tr>
                                            <td class="text-left" >
                                                {!! $data->SocialDefineProject->define_project ?? "In progress..." !!}
                                            </td>
                                        </tr>
                                        <tr class="bg-success1 text-white text-center" >
                                            <th> Milestones</th>
                                        </tr>

                                        <table class="table table-bordered table-stripped" >
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Milestone</th>
                                                    <th>Weightage (%)</th>
                                                    <th>Days</th>
                                                    <th>Planned Date</th>
                                                    <th>Actual Date</th>
                                                </tr>
                                            </thead>

                                            <tbody class="text-center" >
                                                @if(count(($data->socialMilestonesSocial ?? [])) > 0)
                                                    @foreach($data->socialMilestonesSocial as $key => $e)
                                                        <tr>
                                                            <th>{{++$key}}.</th>
                                                            <th style="width:40%">{{ $e->name }}</th>
                                                            <th>{{ $e->weight }}</th>
                                                            <td>{{ $e->days }}</td>
                                                            <td>{{ date("d-m-Y",strtotime($e->planned_date)) }}</td>
                                                            <td>{{ $e->actual_date ?  date("d-m-Y",strtotime($e->planned_date)) : 'N/A' }}</td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <th colspan="2" style="text-align:right;" >Total</th>
                                                        <th>{{ $data->socialMilestonesSocial->sum('weight') }}%</th>
                                                        <th>{{ $data->socialMilestonesSocial->sum('days') }}</th>
                                                        <th colspan="2" ></th>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="6" >Milestone not defined in progress...</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
