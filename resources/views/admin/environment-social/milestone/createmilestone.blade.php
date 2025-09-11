@extends('layouts.admin')

@section('content')

<div>
    <div class="row col-md-12" style="display: inline-block;padding-left:12px;">
            <button style="padding:5px 20px;" class="btn btn-md btn-primary pull-right previousButton"> 
        <i class="fa fa-arrow-left" aria-hidden="true"></i>  </button>
        <h4> Porject MileStone | Project : {{ $project->name ?? '' }}</h4>
    
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ url('//projects/social/environment') }}">Project Monitoring</a></li>
                <li class="breadcrumb-item active"><a href="#">Project Milestones</a></li>
            </ol>
        </nav>
    </div>
</div>

<div class="x_panel">

    <div class="x_title">
        <button id="addData" class="btn btn-success btn-md pull-right" > + Add </button>
        <h2 style="font-weight:700;">Project Milestones</h2>
        <div class="clearfix"></div>
    </div>
    <div class="x_content">
            <br>
            <form autocomplete="off" data-method="POST" data-action="{{ route('es.store') }}"  class="ajax-form form-horizontal form-label-left">
                @csrf   
                
            <div class="row">
                <div class="col-md-6">
                        <label ><b>Start Date:</b></label>
                        <input type="date" class="form-control" id="start_date" name="start_date"  />
                     <br><br>
                </div>
               
            </div>
            
                <input type="hidden" name="id" value="{{ $project->id }}" />
                
                <table class="table table-striped projects">
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
                    <tbody id="tableData" >
                            <span id="addInputs"></span>
                    </tbody>
                </table>
    
                <div class="ln_solid"></div>
                <span id="end" style="display:none;"  >1</span>
                <div class="form-group">
                    <div class="col-md-12 col-sm-12">
                        <button id="submit-btn" type="submit" class="btn btn-success ">
                            <span class="loader" id="loader" style="display: none;"></span>
                            Create MileStone
                        </button>
                    </div>
                </div>
            </form>
        <br>
    </div>
  
</div>

<div style="display:none;">
    <input type="hidden" id="Number" name="number" value="1" />
</div>

@stop

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
            var days = parseInt($(this).val(), 10);
            var plannedDateInput = $(this).closest('tr').find('.planned-date');
            var startDateValue = $('#start_date').val();
            const originalDate = new Date(startDateValue);
        
            if (!isNaN(days) && days !== '') {
              dday += days;
              const newDate = originalDate.addDays(dday);
              
              const day = String(newDate.getDate()).padStart(2, '0'); // Get day with leading zero if needed
                const month = String(newDate.getMonth() + 1).padStart(2, '0'); // Get month with leading zero if needed (Note: January is 0)
                const year = newDate.getFullYear(); // Get full year
                const formattedDate = `${day}-${month}-${year}`;

            //   const formattedDate = newDate.toISOString().split('T')[0];
              plannedDateInput.val(formattedDate);
              
            //   console.log(plannedDateInput);
            } else {
              plannedDateInput.val('');
            }
          });
        }
        
        $(document).on('input', '#start_date, .days-input', updatePlannedDates);

        $('#start_date').on('change',function(){
                
                var startDateValue = $(this).val();
                
                if(startDateValue) {
                    $('.days-input').prop('readonly', false);
                    $('.number-input').prop('readonly', false);
                }  else{
                     $('.days-input').prop('readonly', true);
                     $('.number-input').prop('readonly', true);
                }
                
            });
        });
     
        $('#addData').click(function(){

                var html = "";
                var number = parseInt($('#Number').val(), 10);
                var startDateValue = $('#start_date').val();
    
                html += "<tr id='tr"+number+"'>";
                html += "<td class='count' >"+number+".</td>";
                html += "<th><input type='text'  minlength='1'  maxlength='200' name='data["+number+"][name]'  class='form-control'  required  /></th>";
                if(startDateValue){
                    html += "<td><input type='number' min='1' max='100' name='data["+number+"][weight]'    value='' class='form-control number-input'  required /></td>";
                    html += "<td><input  type='number' min='1' name='data["+number+"][days]' class='form-control days-input'  required  /></td>";   
                }else{
                    html += "<td><input type='number' min='1' max='100' name='data["+number+"][weight]'    value='' class='form-control number-input' readonly  required /></td>";
                    html += "<td><input  type='number' min='1' name='data["+number+"][days]' class='form-control days-input' readonly required  /></td>";   
                }
                html += "<td><input type='text' name='data["+number+"][planned_date]' class='form-control planned-date' readonly  required /></td> ";
                html += "<td><span data-tr='"+number+"'  class='btn btn-md btn-danger remove' >Remove</span></td>";
                html += " <input type='hidden'  name='data["+number+"][project_id]' value='{{ $project->id }}' />";
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
        
        $(document).on('click', '.remove', function() {
              var value = $(this).data('tr');
              var val = "#tr" + value;
              $(val).remove();
              
              // Call the function here
              updatePlannedDates1();
              updateSerialNumbers();
            });

        $('#startDate').on('input',function(){

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

</script>
@stop




