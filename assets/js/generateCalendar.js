/*
  generateCalendar.js

  This file sets up the calendar for the 
  administrator page and the public page.
*/
$(document).ready(function () {
  /*function onCalLoad(isLoading, view) {
   if (isLoading) {
   setTimeout(onCalLoad, 300);
   } else {
   if ($('#calendar').fullCalendar('clientEvents').length === 0) {
   console.log("clientEvents loaded successfully.");
   console.log($('#calendar').fullCalendar('clientEvents').length);
   }
   var loc = window.location.pathname;
   var dir = loc.substring(0, loc.lastIndexOf('/'));
   console.log(dir);
   }
   }*/
  console.log('username is: ' + username);
  console.log(location.pathname);

  var getEventsURL;
  if (location.pathname.indexOf('admin') !== -1) {
    console.log('from Admin');
    getEventsURL = '../BandMaster_final/assets/php/fullcalendar/get-events.php';
  } else {
    console.log('from user');
    getEventsURL = '../../assets/php/fullcalendar/get-events.php';
  }

  $('#calendar').fullCalendar({
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    //defaultDate: '2015-02-12',

    /* disables dragging and dropping*/
    editable: false,
    eventLimit: true, // allow "more" link when too many events
    //type: POST,
    events: {
      data: {
        username: username
      },
      url: getEventsURL,
      error: function () {
        console.log("failed");
        $('#script-warning').show();
      }
    },
    /*eventSources: [
     'json/events.json'
     ],*/
    loading: function (bool, isLoading) {
      $('#loading').toggle(bool);
      /*if (!isLoading)
       {
       console.log($('#calendar').fullCalendar('clientEvents').length);
       }*/
    },
    eventClick: function (calEvent, jsEvent, view) {
      var $eventID = calEvent.uID;

      $('#eventDialog').html("<table class=\"formAlign\">" +
              "<tr><td><span><b>Name:</b></td><td>" + calEvent.title + "</span></td></tr>" +
              "<tr><td><span><b>Location:</b></td><td>" + calEvent.location + "</span></td></tr>" +
              "<tr><td><span><b>Start:</b></td><td>" + calEvent.start._d + "</span></td></tr>" +
              "<tr><td><span><b>End:</b></td><td>" + calEvent.end._d + "</span></td></tr></table>");
      console.log(calEvent);
      console.log("iddd" + $eventID);

      console.log("admin?" + (location.pathname.indexOf('admin') !== -1));
      
      if (location.pathname.indexOf('admin') !== -1)
      {
        $("#eventDialog").dialog({
          resizable: false,
          height: 280,
          width: 500,
          modal: true,
          title: 'Event Information',
          buttons: {
            CLOSE: function () {
              $("#eventDialog").dialog("close");
              console.log("Closed, id =");
              console.log($eventID);
            },
            "DELETE": function () {
              $.ajax({
                type: "POST",
                data: {
                  eventID: $eventID,
                  username: username
                },
                url: "deleteEvent.php",
                success: function () {
                  console.log("Event deleted successfully.");
                  console.log("success " + $eventID);
                  /* refetch events before close in order to reduce duration of
                   * user seeing the calendar flash while the calendar refreshes */
                  $("#calendar").fullCalendar('refetchEvents');
                  $("#eventDialog").dialog("close");
                },
                error: function () {
                  console.log("Event failed to delete.");
                  var loc = window.location.pathname;
                  var dir = loc.substring(0, loc.lastIndexOf('/'));
                  console.log(dir);
                }
              });
            }
          }
        });
      }
      else
      {
        $("#eventDialog").dialog({
          resizable: false,
          height: 280,
          width: 500,
          modal: true,
          title: 'Event Information',
          buttons: {
            CLOSE: function () {
              $("#eventDialog").dialog("close");
              console.log("Closed, id =");
              console.log($eventID);
            }
          }
        });
      }
    }
  });
});
