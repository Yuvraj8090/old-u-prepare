@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
            </button>
            <h4>Work Program | Project : {{ $data->project->name }}</h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="{{ url('/procurement') }}">Procurement</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a href="#">Create Project Procurement</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="x_panel">
        <div class="x_title">
            <h2 style="font-weight:700;">Procurement Work Program</h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <br>
            <style>table.wpro td {
                    vertical-align: middle;
            }</style>
            <form autocomplete="off" data-method="POST" data-action="{{ route('procurement-params.store') }}" class="ajax-form form-horizontal form-label-left">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}" />

                <table class="table table-striped projects wpro">
                    <thead>
                        <tr>
                            <th style="width:10%"># S.No.</th>
                            <th>Work Program</th>
                            <th style="width: 10%">Days</th>
                            <th style="width: 10%">Weightage</th>
                            <th>Planned Date</th>
                            <th>Actual Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableData" >
                        @if(count($params) ==  0)
                            <span id="addInputs"></span>
                        @else
                            @foreach($params as $key => $p)
                                @php
                                    ++$key;
                                    $plannedDate = $p->planned_date ? date('d-m-Y', strtotime($p->planned_date)) : '';
                                    $actualDate  = $p->actual_date ?  date('Y-m-d', strtotime($p->actual_date)) : '';
                                @endphp

                                <tr>
                                    <td>{{ $key }}.</td>
                                    <td>
                                        <input type="hidden" name="id" value="{{ $p->id }}" />
                                        <input type="hidden" name="project_id" value="{{ $p->project_id }}" />
                                        <textarea class="form-control" name="name" disabled>{{ $p->name }}</textarea>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control days-input" name="days" value="{{ $p->days }}" disabled>
                                    </td>
                                    <td>
                                        {{-- <input type="text" class="form-control" name="weight" value="{{ $p->weight }}" {{ !empty($p->actual_date) ? 'disabled' : '' }}> --}}
                                        <input type="text" class="form-control" name="weight" value="{{ $p->weight }}" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control planned-date" name="planned_date" value="{{ $plannedDate }}" disabled>
                                    </td>
                                    <td>
                                        <input type="date" class="form-control actual-date" data-min="{{ $plannedDate }}" name="actual_date" daa value="{{ $actualDate }}" >
                                    </td>
                                    <td>
                                        <button class="btn btn-md btn-primary submit-btn" href="">Update</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </form>

            <br>
        </div>
    </div>


    <div id="bidDocument" @class(['d-none'=> $document, 'col-md-12'])>
        <div class="x_panel">
            <div class="x_title">
                <h5 style="font-weight:550;">PROCUREMENT BID DOCUMENT ( Note:- Document Mandatory )</h5>
                    <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form autocomplete="off" data-method="POST" data-action="{{ route('procurement.update.bid.document') }}" id="ajax-form" class="form-horizontal form-label-left">
                    @csrf

                    <input type="hidden" name="project_id" value="{{ $data->project->id }}" />
                    <input type="hidden" name="id" value="{{ $data->id ?? '' }}" />

                    <div class="form-group">
                        <label class="control-label ">Bid Document  (Only PDF file allowed.)</label>

                        <div class="">
                            @if(isset($data->media) && !empty($data->media))
                                <a href="javascript:void(0)" onClick="openPDF('{{ url('images/bid_document/' . $data->media->name) }}')" class="btn btn-md btn-success">View Document</a>
                                <a download href="{{ url('images/bid_document/' . $data->media->name) }}" class="btn btn-md btn-danger">Download Document</a>
                                <br>
                                <br>
                            @endif

                            <input type="file" name="bid_document" class="form-control" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label">Pre Bid Minutes Document (Only PDF file allowed.)</label>
                        <div class="">
                            @if(isset($data->media2) && !empty($data->media2))
                                <a href="javascript:void(0)" onClick="openPDF('{{ url('images/pre_bid_document/' . $data->media2->name) }}')" class="btn btn-md btn-success">View Document</a>
                                <a download href="{{ url('images/pre_bid_document/' . $data->media2->name) }}" class="btn btn-md btn-danger">Download Document</a>
                                <br>
                                <br>
                            @endif

                            <input type="file" name="pre_bid_document" class="form-control" required />
                        </div>
                    </div>

                    <div class="ln_solid"></div>

                    <div class="form-group">
                        <div class="col-md-12 col-sm-12">
                            <button id="submit-btn" type="submit" class="btn btn-primary">
                                <span class="loader" id="loader" style="display: none;"></span>
                                {{ isset($data->media) && !empty($data->media) ? 'Update' : 'Upload' }} Bid Document
                            </button>
                        </div>
                    </div>
                </form>

                <div></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>

    <div style="display:none;">
        <input type="hidden" id="Number" name="number" value="1" />
    </div>
@stop


@section('script')
    <script>
        $(document).ready(function() {
            // Attach an event listener to the 'Start Date' input
            $('#start_date, .days-input').on('input', function() {

                var dday = 0;
                $('.days-input').each(function() {

                    var days = parseInt($(this).val(),10);
                    var plannedDateInput = $(this).closest('tr').find('.planned-date');

                    if (!isNaN(days) && days !== '') {

                            dday = dday + days;

                            var startDateValue = $(this).val();
                            const originalDate = Date.parse(startDateValue);
                            const newDate = originalDate.addDays(dday);
                            const formattedDate = newDate.toString("yyyy-MM-dd");

                            plannedDateInput.val(formattedDate);

                    } else {
                        plannedDateInput.val('');
                    }
                });
            });

            $('#start_date').trigger('input');

            $('#start_date').on('change',function() {
                var startDateValue  = $(this).val();
                const originalDate  = Date.parse(startDateValue);
                const newDate       = originalDate.addDays(1);
                const formattedDate = newDate.toString("yyyy-MM-dd");
                // console.log(formattedDate);

                if(startDateValue) {
                    $('.days-input').prop('readonly', false);
                }
                else
                {
                    $('.days-input').prop('readonly', true);
                }
            });

            $('#tableData').on('click', '.submit-btn', function(event) {
                event.preventDefault();

                var isConfirmed = window.confirm('Are you sure you want to update this because this action not reversible.');

                if(!isConfirmed){
                    return false;
                }

                var formFields = $(this).closest('tr').find('input, select, textarea');

                $.ajax({
                    url: "{{ url('procurement/update/single/three') }}",
                    type: 'POST',
                    data: formFields.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {

                        if (response.errors) {
                            var msg = Object.keys(response.errors)[0];
                            msg = response.errors[msg];
                            toastr.error(msg);
                        } else if (response.success) {
                            toastr.success("Success! "+ response.message);
                            if(response.last){
                                $('#bidDocument').show();
                            }
                             if(response.url){
                                setTimeout(function() {
                                    window.location = response.url;
                                }, 500);
                            }
                        }

                    },
                    error: function (err) {
                        toastr.info("Error! Please Contact Admin.");
                    }
                });
            });

            $('.datepicker').on('change',function() {
                var startDateValue = $(this).val();
                var minDate        = $(this).data('min');

                console.log(startDateValue + " " + minDate);

                const startDate = new Date(startDateValue.split('-').reverse().join('-'));
                const endDate   = new Date(minDate.split('-').reverse().join('-'));

                if (endDate > startDate) {
                    $(this).val('');
                    toastr.error('Warning! Date cant be less than planned date.')
                }
            });
        });
    </script>
@stop

