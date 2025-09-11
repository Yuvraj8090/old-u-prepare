
<div class="x_panel">

    <div class="x_title">
        @if(count($params) == 0) <button id="addData" class="btn btn-success btn-md pull-right" > + Add </button> @endif
        <h2 style="font-weight:700;">Procurement Work Program</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <br>
        <form autocomplete="off" data-method="POST" data-action="{{ route('procurement-params.store') }}"  class="ajax-form form-horizontal form-label-left">

            @csrf   

            <input type="hidden" name="id" value="{{ $id }}" />
 
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th># S.No.</th>
                        <th style="width: 25%">Work Program</th>
                        <th>Days</th>
                        <th>Weightage</th>
                        <th>Planned Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableData" >

                        @if(count($params) > 0 )
                      
                            @php $weight = 0; @endphp
                            @foreach($params as $key => $param)
                                @if($data->paramsValues[$key]['planned_date'] ?? '')
                                    <input type="hidden" name="data[{{$key}}][id]" id="datepicker{{$key}}0" data-match="datepicker{{$key}}1" value="{{$data->paramsValues[$key]['id'] ?? '' }}" class="form-control datepicker" />
                                @endif
                                <input type="hidden" name="data[{{$key}}][procurement_param_id]" value="{{ $param->id }}" />
                                <input type="hidden" name="data[{{$key}}][weight]" value="{{ $param->weight }}" />
                                <input type="hidden" name="data[{{$key}}][project_id]" value="{{ $data->project->id }}" />
                            @php
                                $weight = $param->weight + $weight;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <th> <input type="text"  minlength="1"  maxlength="200" name="data[{{$key}}][name]"  class="form-control" value="{{ $param->name ?? '' }}" readonly /></th>
                                <td>
                                    <input type="number" min="1" name="data[{{$key}}][days]" data-key="{{ $key + 1 }}" id="days{{ $key + 1 }}" class="form-control days " readonly />
                                </td>
                                <td>
                                    <input type="number" name="data[{{$key}}][weight]" id="weight{{ $key + 1 }}" min="0" max="100" maxlength="3" value="" 
                                    class="form-control number-input"  readonly  required />
                                </td>
                                <td>
                                    <input 
                                    type="date" 
                                    name="data[{{$key}}][planned_date]" 
                                      value="{{ $data->paramsValues[$key]['planned_date'] ?? '' }}"
                                      class="form-control"
                                      @if($key == "0") id="startDate" @else id="date{{$key+1}}" @endif
                                      @if($key != "0") readonly  @endif 
                                       
                                      required />
                                </td>
                            </tr>
                            @endforeach
                            <!-- <tfooter>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Total Weight </th>
                                    <th> 
                                        <span  id="total"> {{ number_format($weight,2) }} </span>
                                        <span id="warning-message" style="color: red; display: none;">
                                            Warning: The total is more than 100!
                                        </span>
                                    </th>
                                    <th colspan="2">
                                        
                                    </th>
                                </tr>
                            </tfooter> -->
                            @else
                            <span id="addInputs" > </span>
                        @endif
                    
                </tbody>
            </table>

            <div class="ln_solid"></div>
            <span id="end" style="display:none;"  >{{ count($params ?? []) }}</span>
            <div class="form-group">
                <div class="col-md-12 col-sm-12">
                    <button id="submit-btn" type="submit" class="btn btn-success ">
                        <span class="loader" id="loader" style="display: none;"></span>
                        Create Procurement
                    </button>
                    <a type="reset" class="ml-4 btn  btn-primary pull-right" href="{{ route('procurement.create',$id) }}">Reset</a>
                </div>
            </div>
        </form>
        <br>
    </div>
  
</div>



<div style="display:none;">
<input type="hidden" id="Number" name="number" value="1" />
</div>



@section('script')
<script>

        $('#addData').click(function(){

            var html = "";
            var number = parseInt($('#Number').val(), 10);

            html += "<tr id='tr"+number+"'>";
            html += "<td class='count' >"+ number +"</td>";
            html += "<th><input type='text'  minlength='1'  maxlength='200' name='data["+number+"][name]'  class='form-control'  required  /></th>";
            html += "<td><input  type='number' min='1' name='data["+number+"][days]' class='form-control days' required  /></td>";              
            html += "<td><input type='number'  name='data["+number+"][weight]'    value='' class='form-control number-input'  required /></td>";
            html += "<td><input type='date' name='data["+number+"][planned_date]' class='form-control' required /></td> ";
            html += "<td><span data-tr='"+number+"'  class='btn btn-md btn-danger remove' >Remove</span></td>";
            html += " <input type='hidden'  name='data["+number+"][project_id]' value='{{ $data->project->id }}' />";
            html += "</tr>";


            $('#Number').val(number + 1); 

            $('#tableData').append(html);
             updateSerialNumbers(); 

        });
        
        $(document).on('click', '.remove', function() {
            var value = $(this).data('tr');
            var val = "#tr" + value;
            $(val).remove();
            updateSerialNumbers(); 
        });

        function updateSerialNumbers() {
            $('#tableData tr').each(function(index) {
                // $(this).find('.count').html(index + 1);
                // $(this).attr('id', 'tr' + (index + 1));
                // $(this).find('.remove').data('tr', index + 1);
                
                
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

        $('#startDate').on('input',function(){

            var value = $(this).val();
            var plannedDate = $('#startDate').val();

            if(value != "" && plannedDate != ""){
                $('.days').removeAttr("readonly");
                $('.days').val('');
                // console.log(value);
                // console.log(plannedDate);
            }
            
        });
        
        Date.prototype.addDays = function(days) {
          var date = new Date(this.valueOf());
          date.setDate(date.getDate() + days);
          return date;
        };
        
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
                return; // Early return if no previous date
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

</script>
@stop
