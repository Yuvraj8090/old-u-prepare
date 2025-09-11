@php
    $idPrefix = $isEdit ? 'edit' : '';
@endphp

<form id="{{ $formId }}" autocomplete="off" data-method="POST" data-action="{{ $actionRoute }}"
    class="form-horizontal form-label-left ajax-form-edit" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        <input type="hidden" name="msv_id" value="">
    @endif

    @isset($boqf)
        <input type="hidden" name="boq-sheet" value="1">
    @endisset

    <input type="hidden" name="milestone_id" id="milestone_id">
    <input type="hidden" name="budget" id="{{ $idPrefix }}_budget">

    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">
                        {{ $isEdit ? 'Update Progress of Milestone Value:' : 'Update Financial Progress' }}
                        @if($isEdit)
                            <span id="editMilestoneName"></span>
                        @endif
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    @foreach([
                        ['label' => 'Financial Progress (%)', 'name' => 'financial_progress', 'type' => 'number', 'readonly' => true],
                        ['label' => 'Finance Amount', 'name' => 'amount', 'type' => 'number', 'note' => "Budget: ₹<span class='fcur' id='{$idPrefix}BudgetDisplay'>".number_format($budget,2)."</span>"],
                        ['label' => 'No. of Bills', 'name' => 'no_of_bills', 'type' => 'number'],
                        ['label' => 'Bill Serial No.', 'name' => 'bill_serial_no', 'type' => 'textarea', 'note' => 'Comma separated'],
                        ['label' => 'Submit Date', 'name' => 'date', 'type' => 'date'],
                        ['label' => 'Upload Payment Slip', 'name' => 'payment_slip', 'type' => 'file']
                    ] as $field)
                        <div class="form-group row">
                            <label class="control-label col-md-3">
                                {{ $field['label'] }}
                                @isset($field['note'])<br><small>{!! $field['note'] !!}</small>@endisset
                            </label>
                            <div class="col-md-9">
                                @if($field['type'] === 'textarea')
                                    <textarea class="form-control" name="{{ $field['name'] }}"></textarea>
                                @else
                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                                           class="form-control {{ $field['name'] === 'amount' ? 'amount' : '' }}"
                                           {{ $field['readonly'] ?? false ? 'readonly' : '' }}>
                                @endif
                                <p class="error" id="editerror-{{ $field['name'] }}"></p>
                            </div>
                        </div>
                    @endforeach
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
