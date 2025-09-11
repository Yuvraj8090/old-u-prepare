@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
        <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4>  Contract Amendment | Project : {{ ucfirst($contract->project->name) }} | contract end date : {{  dateFormat($contract->end_date) }}</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('contract') }}">Contracts</a></li>
                <li class="breadcrumb-item active"><a href="#">Contract Amend</a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">
                    <div class="x_title">
                        <button type="button" class="btn btn-info btn-md pull-right" data-toggle="modal" data-target="#exampleModalCenter">+ Add </button>

                        <h2>   Contract Amendment </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">

                        <table class="table table-striped projects table-bordered text-center">
                            <thead>
                                <tr>
                                    <th style="width: 15%"> Amendment No.</th>
                                    <th> Amended Contract Date</th>
                                    <th> Amended Contract Value</th>
                                    <th style="width: 20%">Document</th>
                                </tr>
                            </thead>
                            <tbody >
                                @if(count($data) > 0)
                                @foreach($data as $key => $d)
                                <tr>
                                    <td>{{ ++$key }}.</td>
                                    <td>{{ dateFormat($d->amend_date) }}</td>
                                    <td>{{$d->cost ?? ''}} </td>
                                    <td>
                                        <a target="_blank" class="btn btn-md btn-primary" onClick="openPDF('{{ asset('images/contract/amend/'.$d->document) }}')" href="javascript:void(0)"> View</a>
                                        <a download class="btn btn-md btn-danger" href="{{ asset('images/contract/amend'.$d->document) }}">Download</a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="9">
                                        <center> NO DATA FOUND </center>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

@stop

@section('modal')
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> Amend Contract </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form autocomplete="off" data-method="POST" data-action="{{ url('contract-amend/store') }}" id="ajax-form" class="form-horizontal form-label-left" >
                <div class="modal-body">
                    @csrf

                    <input type="hidden" name="contract_id" value="{{ $contract->id ?? '' }}" />

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Project Name</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control"  value="{{ $contract->project->name }}"  disabled />
                        </div>
                    </div>
                    <br>


                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Last Amendment Date</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control"  value="{{ dateFormat($contract->end_date) }}"  disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Contract Amend Date</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control datepicker"  name="contrat_date" />
                            <p class="error-project" id="error-contrat_date"></p>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Last Contract Value</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control"  value="{{ $contract->procurement_contract }}"  disabled />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Amend Contract Value</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control"  name="contrat_value" min="1" />
                            <p class="error-project" id="error-contrat_value"></p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Upload Contract Amend Docuemnts</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="file" class="form-control" name="contract_amend_document"  />
                            <p class="error-doxument" id="error-contract_amend_document"></p>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="submit-btn" type="submit" class="btn btn-success btn-md">
                        <span class="loader" id="loader" style="display: none;"></span>
                        Amend Contract
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@stop