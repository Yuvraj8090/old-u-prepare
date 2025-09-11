<div class="x_panel">
    <div class="x_title">
        <button id="addData" class="btn btn-success btn-md pull-right">+ Add</button>

        <h2 style="font-weight:700;">Procurement Work Program</h2>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <br>
        <div class="row">
            <div class="col-md-6">
                <label>
                    <b>Start Date:</b>
                </label>
                <input type="date" class="form-control" id="start_date" name="start_date"  />
                <br>
                <br>
            </div>
        </div>

        <form autocomplete="off" data-method="POST" data-action="{{ route('procurement-params.store') }}" class="ajax-form form-horizontal form-label-left">
            @csrf

            <input type="hidden" name="id" value="{{ $id }}" />

            <table class="table table-striped projects wpro">
                <thead>
                    <tr>
                        <th># S.No.</th>
                        <th>Work Program</th>
                        <th class="w-150">Weightage</th>
                        <th class="w-150">Days</th>
                        <th class="w-200">Planned Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="tableData">
                    @foreach($programs as $key => $program)
                        @php ++$key @endphp
                        <tr>
                            <td class="count">{{ $key }}.</td>
                            <td>
                                <textarea class="form-control" name="data[{{ $key }}][name]">{{ $program->name }}</textarea>
                                <input type="hidden" name="data[{{ $key }}][project_id]" value="{{ $data->project_id }}"/>
                            </td>
                            <td>
                                <input type="text" class="form-control weight-input" name="data[{{ $key }}][weight]" value="" />
                            </td>
                            <td>
                                <input type="number" class="form-control days-input" name="data[{{ $key }}][days]" value="" />
                            </td>
                            <td>
                                <input type="date" class="form-control planned-date" name="data[{{ $key }}][planned_date]" value="" />
                            </td>
                            <td>
                                <span type="button" class="btn btn-md btn-danger wp-remove">Remove</span>
                            </td>
                        </tr>

                        <span id="addData"></span>
                    @endforeach
                    <span id="addInputs"></span>
                </tbody>
            </table>

            <div class="ln_solid"></div>

            <span id="end" style="display:none;">{{ count($params) }}</span>

            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <button id="submit-btn" type="submit" class="btn btn-success">
                        <span class="loader" id="loader" style="display: none;"></span>
                        Create Procurement
                    </button>
                    {{--<a type="reset" class="ml-4 btn  btn-primary pull-right" href="{{ route('procurement.create',$id) }}">Reset</a>--}}
                </div>
            </div>
        </form>
        <br>
    </div>
</div>

<div class="d-none">
    <input type="hidden" id="Number" name="number" value="1" />
</div>

@section('script')
    <script>
        $(document).ready(function() {
            $('#start_date').trigger('input');

            Date.prototype.addDays = function (days) {
                var date = new Date(this.valueOf());
                date.setDate(date.getDate() + days);

                return date;
            };

            function updatePlannedDates() {
                var dday = 0;

                $('.days-input').each(function () {
                    var days             = parseInt($(this).val(), 10);
                    var plannedDateInput = $(this).closest('tr').find('.planned-date');
                    var startDateValue   = $('#start_date').val();

                    const originalDate = new Date(startDateValue);

                    if (!isNaN(days) && days !== '') {
                        dday += days;

                        const newDate = originalDate.addDays(dday);
                        const formattedDate = newDate.toISOString().split('T')[0];
                        plannedDateInput.val(formattedDate);
                    }else {
                        plannedDateInput.val('');
                    }
                });
            }

            $(document).on('input', '#start_date, .days-input', updatePlannedDates);

            $('#start_date').on('change', function() {
                var startDateValue = $(this).val();

                if(startDateValue) {
                    $('.days-input').prop('readonly', false);
                    $('.number-input').prop('readonly', false);
                }else {
                    $('.days-input').prop('readonly', true);
                    $('.number-input').prop('readonly', true);
                }
            });
        });

        $('#addData').click(function() {
            var html = "";
            var number = parseInt($('#Number').val(), 10);
            var startDateValue = $('#start_date').val();

            html += "<tr id='tr"+number+"'>";
            html += "<td class='count' >"+number+".</td>";
            html += "<th><textarea type='text' minlength='1' maxlength='200' name='data["+number+"][name]' class='form-control' required ></textarea></th>";

            if(startDateValue) {
                html += "<td><input type='number' min='1' max='100' name='data["+number+"][weight]' value='' class='form-control number-input' required /></td>";
                html += "<td><input type='number' min='1' name='data["+number+"][days]' class='form-control days-input'  required /></td>";
            }else {
                html += "<td><input type='number' min='1' max='100' name='data["+number+"][weight]' value='' class='form-control number-input' required /></td>";
                html += "<td><input type='number' min='1' name='data["+number+"][days]' class='form-control days-input' required /></td>";
            }

            html += "<td><input type='date' name='data["+number+"][planned_date]' class='form-control planned-date' required /></td>";
            html += "<td><span data-tr='"+number+"' class='btn btn-md btn-danger wp-remove'>Remove</span></td>";
            html += "<input type='hidden' name='data["+number+"][project_id]' value='{{ $data->project->id }}' />";
            html += "</tr>";

            $('#Number').val(number + 1);
            $('#tableData').append(html);

            updateSerialNumbers();
        });

        function updatePlannedDates1() {
            var dday = 0;

            $('.days-input').each(function () {
                var days = parseInt($(this).val(), 10);
                var plannedDateInput = $(this).closest('tr').find('.planned-date');
                var startDateValue = $('#start_date').val();
                const originalDate = new Date(startDateValue);

                if (!isNaN(days) && days !== '') {
                    dday += days;
                    const newDate = originalDate.addDays(dday);
                    const formattedDate = newDate.toISOString().split('T')[0];
                    plannedDateInput.val(formattedDate);
                } else {
                    plannedDateInput.val('');
                }
            });
        }

        $(document).on('click', '.wp-remove', function() {
            // var value = $(this).data('tr');
            // var val   = "#tr" + value;
            $(this).closest('tr').remove();
            // $(val).remove();

            // Call the function here
            updatePlannedDates1();
            updateSerialNumbers();
        });

        $('#startDate').on('input', function() {
            var value = $(this).val();
            var plannedDate = $('#startDate').val();

            if(value != "" && plannedDate != ""){
                $('.days').removeAttr("readonly");
                $('.days').val('');
            }
        });

        $('.days').on('input', function() {
            var value = parseInt($(this).val(), 10); // Ensure value is a number
            if (isNaN(value)) {
                console.log("Invalid number of days");
                return; // Early return if value is not a number
            }

            var days = parseInt($(this).data('key'), 10);
            var sameDate = '#date' + days;

            var previousDateSelector = days <= 2 ? '#startDate' : '#date' + (days - 1);

            var previousDateValue = $(previousDateSelector).val();

            if (!previousDateValue) {
                console.log("No previous date found");
                return;
            }

            var end = parseInt($('#end').text(), 10);

            for (var i = days + 1; i <= end; i++) {
                $('#date' + i).val('');
                $('#days' + i).val('');
                $('#weight' + i).val('');
            }

            if (sameDate === "#date" + end) {
                // console.log('triggered');
                weight();
            }

            var currentDate = new Date(previousDateValue).addDays(value);
            var formattedDate = currentDate.getFullYear() + '-' +
                ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' +
                ('0' + currentDate.getDate()).slice(-2);

            $(sameDate).val(formattedDate);
        });

        function weight(){

                var total = 0;
                var start = 1;
                var end = $('#end').text();

                $('.days').each(function() {
                    var value = $(this).val();
                    if (value) {
                        total += parseFloat(value);
                    }
                });

                for (var i = start; i <= end; i++) {
                    var days =  $('#days'+i).val();
                    var percentage = (days/total) * 100;
                    percentage = percentage.toFixed(2)
                    console.log(percentage);
                    $('#weight'+i).val(percentage);
                }
        }

        function updateSerialNumbers() {
            $('#tableData tr').each(function(index) {

                  var row = $(this);
                    var newRowNumber = index + 1;

                    row.find('.count').html(newRowNumber);
                    row.attr('id', 'tr' + newRowNumber);
                    row.find('.wp-remove').data('tr', newRowNumber);

                    // Update name attributes of input fields
                    row.find('input, select, textarea').each(function() {
                        var currentName = $(this).attr('name');

                        var adjustedRowNumber = newRowNumber - 1;  // Adjust the row number as needed
                            if (currentName) {
                                var newName = currentName.replace(/\[\d+\]/, '[' + adjustedRowNumber + ']');
                                $(this).attr('name', newName);
                            }
                        });

            });

            var lastNumber = $('#tableData tr').length;
            $('#Number').val(lastNumber + 1);
        }
</script>
@stop
