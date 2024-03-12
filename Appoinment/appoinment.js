$(document).ready(function(){
    $('#framework').multiselect({
     nonSelectedText: 'Select Blood Test Here',
     enableFiltering: true,
     enableCaseInsensitiveFiltering: true,
     buttonWidth:'100%'
     
    });
    
    $('#booking-form').on('submit', function(event){
     event.preventDefault();
     var form_data = $(this).serialize();
     $.ajax({
      url:"connection.php",
      method:"POST",
      data:form_data,
      success:function(data)
      {
       $('#framework option:selected').each(function(){
        $(this).prop('selected', false);
       });
       $('#framework').multiselect('refresh');
       alert(data);
      }
     });
    });
    
    
   });