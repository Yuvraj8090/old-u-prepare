// <script>

//         $('#startDate').on('input',function(){

//             var value = $(this).val();
//             var plannedDate = $('#startDate').val();

//             if(value != "" && plannedDate != ""){
//                 $('.days').removeAttr("readonly");
//                 $('.days').val('');
//                 // console.log(value);
//                 // console.log(plannedDate);
//             }
            
//         });

//         $('.days').on('input',function(){

//             var value = $(this).val();
//             var days = $(this).data('key');
            
//             var sameDate = '#date'+days;
//             var previousDateNumber =  '#date'+ (days-1);

//             if(previousDateNumber == "#date0"){
//                 return false;
//             }

//             if(previousDateNumber == "#date1"){
//                 previousDate = "#startDate"
//             }else{
//                 previousDate = previousDateNumber;
//             }

//             var end = $('#end').text();


//             for (var i = (days+1); i <= end; i++) {              
//                 $('#date'+i).val('');
//                 $('#days'+i).val('');
//                 $('#weight'+i).val('');
//             }

//             if(sameDate == "#date"+end){
//                 // console.log('triggeed');
//                     weight();
//             }

//             var previousDate = $(previousDate).val();
            
//             console.log(previousDate);
            
//             var currentDate = new Date(previousDate).addDays(value);

//             var formattedDate = currentDate.getFullYear() + '-' + 
//                 ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + 
//                 ('0' + currentDate.getDate()).slice(-2);

            
//             $(sameDate).val(formattedDate);

//         });

//         function weight(){

//                 var total = 0;
//                 var start = 1;
//                 var end = $('#end').text();

//                 $('.days').each(function() {
//                     var value = $(this).val();
//                     if (value) {
//                         total += parseFloat(value);
//                     }
//                 });

//                 for (var i = start; i <= end; i++) {
//                     var days =  $('#days'+i).val();
//                     var percentage = (days/total) * 100;
//                     percentage = percentage.toFixed(2)
//                     console.log(percentage);
//                     $('#weight'+i).val(percentage);
//                 }  
//         }

// </script>