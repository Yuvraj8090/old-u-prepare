@extends('layouts.admin')

@section('content')
    <div>
        <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <h4>
                <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
                @isset($boqf)
                    Financial Progress of Project BOQ Sheet
                @else
                    Financial Progress Update – Project: {{ $project->name }}
                @endisset
            </h4>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <a
                            href="#">{{ isset($boqf) ? 'Financial Progress of Project BOQ' : 'Financial Progress of Project Milestone' }}</a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>


    <div class="x_panel">
        <div class="x_title">
            @if (auth()->user()->role->level == 'THREE' && $project->id)
    <button type="button" class="btn btn-primary pull-right" id="btnNextMilestone"
        data-toggle="modal"
        data-target="#exampleModal"
        data-id="{{ $project->id }}"
        data-name="{{ $project->id }}"
        data-budget="{{ $project->contract->procurement_contract ?? 0 }}"
        title="Update Progress">
        Update Progress
    </button>
@endif

            <h2></h2>
            <div class="clearfix"></div>
        </div>
{{-- <div class="alert alert-info">
    <strong>Project Financial Progress:</strong><br>
    Total Budget: ₹{{ number_format($totalProjectBudget, 2) }}<br>
    Total Used: ₹{{ number_format($totalSubmitted, 2) }}<br>
    Overall Progress: <strong>{{ $overallProgress }}%</strong>
</div>

<div class="progress">
    <div class="progress-bar bg-success" role="progressbar"
         style="width: {{ $overallProgress }}%;"
         aria-valuenow="{{ $overallProgress }}"
         aria-valuemin="0" aria-valuemax="100">
        {{ $overallProgress }}%
    </div>
</div> --}}

        <div class="x_content">
            <table class="table table-striped projects  table-bordered text-center">
                <thead>
                    <tr>
                        <th style="width: 8%">S. No #</th>
                        <th style="width: 20%">Amount</th>
                        <th class="text-center">Progress (In %)</th>
                        <th>No. of Bills</th>
                        <th>Bill Serial No.</th>
                        <th>Submit Date </th>
                        <th style="width: 20%">Payment Slip</th>
                    </tr>
                </thead>

                <tbody>
                   
                        <tbody>
    @forelse ($milestoneValues as $k => $d)
        <tr _ac="{{ $d->percentage }}" _aa="{{ $d->amount }}" _sn="{{ $d->stage_name }}"
            _an="{{ $d->activity_name }}" _ad="{{ $d->date }}">
            <td>{{ $k + 1 }}</td>
            <td>{{ number_format($d->amount, 2) }}</td>
            <td>{{ $d->percentage }}</td>
            <td>{{ $d->no_of_bills }}</td>
            <td>{{ $d->bill_serial_no }}</td>
            <td>{{ $d->date }}</td>
            <td>
                @php
                    $hasDoc = $d->financeDoc && $d->financeDoc->file;
                    $fileUrl = $hasDoc ? asset('images/finance_payment_slips/' . $d->financeDoc->file) : '#';
                @endphp

                @if (!isset($view))
                    <a class="btn btn-info text-white btn-edfp"
                        data-toggle="modal"
                        data-target="#editModal"
                        title="Edit Slip"
                        data-info='@json($d)'>Edit</a>
                @endif

                <a class="btn btn-md btn-success"
                    target="{{ $hasDoc ? '_blank' : '_self' }}"
                    href="{{ $fileUrl }}"
                    title="{{ $hasDoc ? 'View Payment Slip' : 'No document present' }}"
                    onclick="{{ !$hasDoc ? 'event.preventDefault(); alert(\'No file present!\');' : '' }}">
                    View
                </a>

                <a class="btn btn-md btn-danger"
                    {{ $hasDoc ? 'download' : '' }}
                    target="{{ $hasDoc ? '_blank' : '_self' }}"
                    href="{{ $fileUrl }}"
                    title="{{ $hasDoc ? 'Download Payment Slip' : 'No document present' }}"
                    onclick="{{ !$hasDoc ? 'event.preventDefault(); alert(\'No file present!\');' : '' }}">
                    Download
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center text-muted">No financial milestone data found.</td>
        </tr>
    @endforelse
</tbody>

                        {{-- Modal Title --}}


                <tfoot></tfoot>
            </table>

        </div>
    </div>



    <form id="editform" autocomplete="off" data-method="POST"
        data-action="{{ route('projectLevel.financial', $project->id) }}"
        class="form-horizontal form-label-left ajax-form-edit">
        @csrf
        @isset($boqf)
            <input type="hidden" name="boq-sheet" value="1">
        @endisset

        <input type="hidden" name="milestone_id" id="milestone_id">
        
        <input type="hidden" name="budget"  id="budget" value="{{ $project->contract->procurement_contract }}">

        <div class="modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 style="font-weight:600;" class="modal-title" id="exampleModalLabel">
                            Update Financial Progress 
                        
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="control-label col-md-3">Financial Progress (%)</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="financial_progress" id="financial_progress"
                                    data-key="physical_progress" min="1" max="100" readonly>
                                <p class="error" id="editerror-financial_progress"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">
                                Finance Amount <br>
                                <small>Budget: ₹{{ number_format($project->contract->procurement_contract, 2) }}</small>
                            </label>
                            <div class="col-md-9">
                                <input type="number" class="form-control amount" name="amount" data-key="amount"
                                    min="1" />
                                <p class="error" id="editerror-amount"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">No. of Bills</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="no_of_bills"
                                    placeholder="No. of bills">
                                <p class="error" id="editerror-no_of_bills"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">
                                Bill Serial No. <br>
                                <small>(Comma separated)</small>
                            </label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="bill_serial_no" placeholder="Bill Serial No..."></textarea>
                                <p class="error" id="editerror-bill_serial_no"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">Submit Date</label>
                            <div class="col-md-9">
                                <input type="date" class="form-control" name="date"
                                    placeholder="Enter Submit Date">
                                <p class="error" id="editerror-date"></p>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-3">
                                Upload Payment Slip <br>
                                <small class="text-danger">(Upload single PDF serial-wise)</small>
                            </label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="payment_slip" />
                                <p class="error" id="editerror-payment_slip"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a type="reset" class="btn btn-primary pull-left" href="">Reset</a>
                        <button id="submit-btn" type="submit" class="btn btn-success">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Update Progress
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </form>


@include('partials._financial_progress_modal', [
    'modalId' => 'editModal',
    'formId' => 'editFP',
    'actionRoute' => route('projectLevel.financial', $project->id),
    'isEdit' => true,
    'budget' => $project->contract->procurement_contract ?? 0,
    'boqf' => $boqf ?? null
])

@section('script')
 <script>
    $(function() {
        // Format all currency values initially
        $('.fcur').each(function() {
            $(this).text(currencyFormatterFraction.format($(this).text()));
        });

        // Remove modal-opening logic
        // $('#btnNextMilestone').on('click', function() {
        //     const id = $(this).data('id');
        //     const name = $(this).data('name');
        //     const budget = parseFloat($(this).data('budget')) || 0;

        //     $('#milestone_id').val(id);
        //     $('#budget').val(budget);
        //     $('#modalMilestoneName').text(name);
        //     $('#budgetDisplay').text(currencyFormatterFraction.format(budget));

        //     // Clear inputs
        //     $('#editform').trigger("reset");
        //     $('#financial_progress').val('');
        // });

        // Auto-update financial progress on amount input
        $(".form-control.amount").on("input", function() {
    const amount = parseFloat($(this).val()) || 0;
    const budget = parseFloat($('#budget').val()) || 0;

    console.log("Budget value:", $('#budget').val());
    console.log("Parsed budget:", budget);
    console.log("Entered amount:", amount);

    const progressInput = $('#financial_progress');

    if (amount > budget) {
        alert(`Entered amount exceeds budget: ${currencyFormatterFraction.format(budget)}`);
        $(this).val('');
        progressInput.val('');
        return;
    }

    if (budget > 0) {
        const percentage = (amount / budget) * 100;
        progressInput.val(percentage.toFixed(0));
    }
});


        // Optional: Format currency values on focus out
        $(".form-control.amount").on("blur", function() {
            const val = parseFloat($(this).val()) || 0;
            $(this).val(val.toFixed(2));
        });
    });
</script>




@endsection




@stop
