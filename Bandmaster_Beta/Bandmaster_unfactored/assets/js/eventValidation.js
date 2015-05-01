$(document).ready(function () {

  $('#dateForm .time').timepicker({
    'showDuration': true,
    'timeFormat': 'g:ia'
  });
  $('#dateForm .date').datepicker({
    'startDate': '+0d',
    'format': 'm/d/yyyy',
    'autoclose': true
  });
  $('#dateForm').datepair();


  $('#addEvent').validate({
    rules: {
      event_name: {
        required: true
      },
      event_location: {
        required: true
      }/*,
       ccol_start: {
       required: true,
       digits: true,
       lessThanEqual: ccol_end
       },
       ccol_end: {
       required: true,
       digits: true
       }*/
      /* holding on to this method as a reference for how to use the equalTo method
       * confirm_password: {equalTo: '#password'},
       spam: "required"
       }, /* end rules */
      //},
    },
    messages: {
      event_name: {
        required: "Please supply the name for your event."
      },
      event_location: {
        required: "Please supply the location for your event."
      },
      /*ccol_start: {
       required: "Please supply the number to start the columns at.",
       lessThanEqual: "The starting column number cannot exceed the columns ending number."
       },
       ccol_end: {
       required: 'Please supply the number for the columns to end at.'
       }
       },*/
      errorPlacement: function (error, element) {
        error.appendTo(element.next());
      },
      submitHandler: function () {
        success: {

        }
      }
    }
  }); // end validate

});