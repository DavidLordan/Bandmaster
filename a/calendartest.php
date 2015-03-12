<?php
 
/* Base code provided by Sarah Bailey. 
Case Western Reserve University, Cleveland OH. scb89@case.edu.
Please do not email me for support. Post a comment instead.*/
 
 
//TO DEBUG UNCOMMENT THESE LINES
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
 
//INCLUDE THE GOOGLE API PHP CLIENT LIBRARY FOUND HERE 
//https://github.com/google/google-api-php-client
//DOWNLOAD IT AND PUT IT ON YOUR WEBSERVER IN THE ROOT FOLDER. 
include(__DIR__.'/google-api-php-client-master/src/Google/autoload.php'); 
 
//SET THE DEFAULT TIMEZONE SO PHP DOESN'T COMPLAIN. SET TO YOUR LOCAL TIME ZONE.
date_default_timezone_set('America/New_York');
 
//TELL GOOGLE WHAT WE'RE DOING
$client = new Google_Client();
$client->setApplicationName("My Calendar"); //DON'T THINK THIS MATTERS
$client->setDeveloperKey('YOUR_SERVER_API_KEY'); //GET AT AT DEVELOPERS.GOOGLE.COM
$cal = new Google_Service_Calendar($client);
//THE CALENDAR ID, FOUND IN CALENDAR SETTINGS. IF YOUR CALENDAR IS THROUGH GOOGLE APPS
//YOU MAY NEED TO CHANGE THE CENTRAL SHARING SETTINGS. THE CALENDAR FOR THIS SCRIPT
//MUST HAVE ALL EVENTS VIEWABLE IN SHARING SETTINGS.
$calendarId = 'you@you.com';
//TELL GOOGLE HOW WE WANT THE EVENTS
$params = array(
//CAN'T USE TIME MIN WITHOUT SINGLEEVENTS TURNED ON, 
//IT SAYS TO TREAT RECURRING EVENTS AS SINGLE EVENTS
    'singleEvents' => true, 
    'orderBy' => 'startTime',
    'timeMin' => date(DateTime::ATOM),//ONLY PULL EVENTS STARTING TODAY
     
);
//THIS IS WHERE WE ACTUALLY PUT THE RESULTS INTO A VAR
$events = $cal->events->listEvents($calendarId, $params); 
 
 
 
$count = 0; //SET COUNT TO 0
$items_to_show = 7; //PULL THE NUMBER OF EVENTS TO DISPLAY FROM THE THEME SETTINGS
 
 
    foreach ($events->getItems() as $event) { 
    if($count <= $items_to_show) {
        //Convert date to month and day
          
         $eventDateStr = $event->start->dateTime;
         if(empty($eventDateStr))
         {
             // it's an all day event
             $eventDateStr = $event->start->date;
         }
          
         $temp_timezone = $event->start->timeZone;
  
         if (!empty($temp_timezone)) {
         $timezone = new DateTimeZone($temp_timezone); //GET THE TIME ZONE
                 //Set your default timezone in case your events don't have one
     } else { $timezone = new DateTimeZone("America/New_York"); 
         }
 
         $eventdate = new DateTime($eventDateStr,$timezone);
 
         $newmonth = $eventdate->format("M");//CONVERT REGULAR EVENT DATE TO LEGIBLE MONTH
         $newday = $eventdate->format("j");//CONVERT REGULAR EVENT DATE TO LEGIBLE DAY
 
        ?>
        <div class="event-container">
        <div class="eventDate">
        <span class="month"><?php
 
        echo $newmonth;
 
         ?></span><br />
        <span class="day"><?php
  
        echo $newday;
 
         ?></span><span class="dayTrail"></span>
    </div>
    <div class="eventBody">
        <a href="<?php echo $event->htmlLink;
                //EVENT LINKS ARE STANDARDIZED, NO NEED TO ADD JUNK TO THE END ?>">
         
        <?php echo $event->summary; //SUMMARY = TITLE 
         ++$count; //INCREASE COUNT AND START AGAIN. 
     
        ?>
        </a>
    </div>
    </div>     
 <?php
  }
}
?>