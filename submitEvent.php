<?php

echo "submitEvent script loaded.";


// Loading existing data:
//$file = file_get_contents("jsoon.json");
//$data = json_decode($file, true);
//When TRUE, returned objects will be converted into associative arrays.
//unset($file);
// Adding new data:
//$data = array();
//$data{"event_name"} = $_POST['name'];
//$data{"event_location"} = $_POST['location'];
// Writing modified data:
//file_put_contents('jsoon.json', json_encode($data, JSON_FORCE_OBJECT));

$path = $_POST['user_name'];

echo $path;

$file = file_get_contents($path . '/JSON/calendarBuildParameters.json', true);
//$file = file_get_contents('test_write.json', true);
$build = json_decode($file, true);
unset($file);

//uID = unique ID
$uID = $build['nextAvailableIndex'];

//$build['nextAvailableIndex'] = $path;
$build['nextAvailableIndex'] += 1;
$updated = json_encode($build);
file_put_contents($path . '/JSON/calendarBuildParameters.json', $updated);
//file_put_contents('test_write.json', $updated);


$file = file_get_contents($path . '/JSON/events.json', true);
$data = json_decode($file, true);
unset($file);

//$index = $data[0];

$event_name = $_POST['event_name'];
$event_location = $_POST['event_location'];
$time_start = $_POST['time_start'];
$date_start = $_POST['date_start'];
$time_end = $_POST['time_end'];
$date_end = $_POST['date_end'];



//need to add new data as next index of data.
$data[] = array(
    'title' => $event_name, 'location' => $event_location,
    'start' => $date_start  . 'T' . $time_start,
    'end'   => $date_end    . 'T' . $time_end,
    'uID' => $uID);

//$data = array_values($data);
$result = json_encode($data);
file_put_contents($path . '/JSON/events.json', $result);
error_log(json_last_error_msg());
unset($result);

file_put_contents($path . 'error_log.txt', print_r($variable, true));
?>
