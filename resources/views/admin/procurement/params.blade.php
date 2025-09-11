<div class="x_panel">
    <div class="x_title">
        <!--<button id="addData" class="btn btn-success btn-md pull-right" > + Add </button>-->

        <h2 style="font-weight:700;">Procurement Work Program</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <br>

        <button id="addData" class="btn btn-success btn-md pull-right" > + Add </button>
            <div class="row">
                <div class="col-md-6">
                        <label ><b>Start Date:</b></label>
                        <input type="date" class="form-control" min="{{ $data->project->approval_date }}" id="start_date" name="start_date"   />
                     <br><br>
                </div>

            </div>
         <h3>Note :- Once planned date set not be editable.</h3>
        <form autocomplete="off" data-method="POST" data-action="{{ route('procurement-params.store') }}"  class="ajax-form form-horizontal form-label-left">

            @csrf

            <input type="hidden" name="id" value="{{ $id }}" />

            <table class="table table-striped projects wpro">
                <thead>
                    <tr>
                        <th># S.No.</th>
                        <th style="width: 25%">Work Program</th>
                        <th>Weightage</th>
                        <th>Days</th>
                        <th>Planned Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody id="tableData">
                    @if(count($params) ==  0)
                        <span id="addData"></span>
                    @else
                       @foreach($params as $key => $p)
                            @php ++$key; @endphp
                            <tr>
                                <td class="count" >{{ $key}}.  </td>
                                <td>
                                    <textarea class="form-control" name="data[{{$key}}][name]">{{ $p->name }}</textarea>  <input type="hidden" name="data[{{ $key }}][project_id]" value="{{$data->project_id}}"/>
                                </td>
                                <td>
                                    <input type="text" class="form-control weight-input" name="data[{{ $key }}][weight]" value="{{ $p->weight }}" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control days-input" name="data[{{ $key }}][days]" value="{{ $p->days }}" readonly>
                                </td>
                                <td>
                                    <input type="date" class="form-control planned-date" name="data[{{ $key }}][planned_date]" readonly>
                                </td>
                                <td>
                                    <span type="button" class="btn btn-md btn-danger remove" > Remove</span>
                                </td>
                            </tr>
                             <span id="addData"></span>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="ln_solid"></div>
            <span id="end" style="display:none;"  >{{ count($params) }}</span>
            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <button id="submit-btn" type="submit" class="btn btn-success ">
                        <span class="loader" id="loader" style="display: none;"></span>
                        Create Procurement
                    </button>
                </div>
            </div>
        </form>
        <br>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/datejs/1.0/date.min.js"></script>

<div style="display:none;">
    <input type="hidden" id="Number" name="number" value="1" />
</div>

@section('script')
    <script>
        $(document).ready(function() {
            $('#addData').click(function() {
                var html           = "";
                var number         = parseInt($('#Number').val(), 10);
                var startDateValue = $('#start_date').val();

                    html += "<tr id='tr"+number+"'>";
                    html += "<td class='count' >"+number+".</td>";
                    html += "<th><input type='text'  minlength='1'  maxlength='200' name='data["+number+"][name]'  class='form-control'  required  /></th>";

                if(startDateValue) {
                    html += "<td><input type='number' min='1' max='100' name='data["+number+"][weight]' value='' class='form-control number-input'  required /></td>";
                    html += "<td><input  type='number' min='1' name='data["+number+"][days]' class='form-control days-input'  required  /></td>";
                }else {
                    html += "<td><input type='number' min='1' max='100' name='data["+number+"][weight]'    value='' class='form-control number-input' readonly  required /></td>";
                    html += "<td><input  type='number' min='1' name='data["+number+"][days]' class='form-control days-input' readonly required  /></td>";
                }

                html += "<td><input type='date' name='data["+number+"][planned_date]' class='form-control planned-date' readonly  required /></td> ";
                html += "<td><span data-tr='"+number+"'  class='btn btn-md btn-danger remove' >Remove</span></td>";
                html += " <input type='hidden'  name='data["+number+"][project_id]' value='{{ $data->project->id }}' />";
                html += "</tr>";

                $('#Number').val(number + 1);
                $('#tableData').append(html);

                updateSerialNumbers();
            });

            function updateSerialNumbers() {
                $('#tableData tr').each(function(index) {

                  var row = $(this);
                    var newRowNumber = index + 1;

                    row.find('.count').html(newRowNumber);
                    row.attr('id', 'tr' + newRowNumber);
                    row.find('.remove').data('tr', newRowNumber);

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

        $(document).on('click', '.remove', function() {
                // Get the closest parent row and remove it

                var confirmDelete = confirm("Are you sure you want to remove this row?");

                if (confirmDelete) {
                    $(this).closest('tr').remove();
                }

                // Additional logic or function calls can be placed here if needed
                // updatePlannedDates1();
                updateSerialNumbers();
            });

        $(document).on('input','#start_date, .days-input', function() {

                var dday = 0;
                $('.days-input').each(function() {

                    var days = parseInt($(this).val(),10);
                    var plannedDateInput = $(this).closest('tr').find('.planned-date');
                    var startDateValue = $('#start_date').val();
                    const originalDate = Date.parse(startDateValue);


                    if (!isNaN(days) && days !== '') {

                            dday = dday + days;
                            const newDate = originalDate.addDays(dday);
                            const formattedDate = newDate.toString("yyyy-MM-dd");
                            // console.log(dday +',dfdsfdsf'+ originalDate );
                            plannedDateInput.val(formattedDate);


                    } else {
                        // If 'Days' is not a valid number, clear the 'Planned Date'
                        plannedDateInput.val('');
                    }
                });
            });

        $('#start_date').trigger('input');

        $('#start_date').on('change',function(){

                const startDateValue = $(this).val();

                const minDate = $(this).attr('min');

                if(startDateValue < minDate){
                    $(this).val('');
                    alert('strt date cant be less than approval date ' + minDate );
                    return false;
                }


                if(startDateValue) {
                     $('.days-input, .weight-input, .number-input').prop('readonly', false);

                }  else{
                     $('.days-input, .weight-input, .number-input').prop('readonly', true);
                }
        });

        });
    </script>

@stop
