<?php



$eventID = $_POST['eventID'];
$username = $_POST['username'];


$file = file_get_contents($username . '/JSON/events.json', true);
$data = json_decode($file, true);
unset($file);

//need to add new data as next index of data.
/* $data[] = array(
  'x' => $eventID); */


foreach ($data as $key => $value) {
  /* if ($value['uID'] == $eventID))
    {
    unset($data[$key]);
    } */
  /*$data[] = array(
    'key' => $key,
      'value' => $value);*/
  if ($value['uID'] == $eventID)
  {
    unset($data[$key]);
  }
}

//$data = array_values($data);
$result = json_encode($data);
file_put_contents($username . '/JSON/events.json', $result);
error_log(json_last_error_msg());
unset($result);

$log = fopen("delete_events_log.txt", "a");
  
  fwrite($log, 'user is: ' . $username . "\n");
?>
