<?php

  set_include_path('ChromePHP.php');
  date_default_timezone_set('America/New_York');

  if ($_POST['func'] == "deleteFile") {

      $file = file_get_contents('JSON/songs.json', true);
      $data = json_decode($file, true);
      unset($file);

      array_splice($data, $_POST['index'], 1);

      $result = json_encode($data);
      file_put_contents('JSON/songs.json', $result);
      unset($result);

      $fileToRemove = 'uploads/' . (string) $_POST['filename'];
      unlink($fileToRemove);

      $log = fopen("file_log.txt", "a");
      fwrite($log, "\n" . date("m/d/Y @ g:i:sA") . " - File Deleted: uploads/" . (string) $_POST['filename']);
      fclose($log);   

      echo "server - file " . $fileToRemove . " removed successfully";
      exit;
  } 
  else if ($_POST['func'] == "addTask") {

      $file = file_get_contents('JSON/taskList.json', true);
      $data = json_decode($file, true);
      unset($file);

      $newTask = $_POST['newTask'];
      $data[] = array('task' => $newTask);

      $result = json_encode($data);
      file_put_contents('JSON/taskList.json', $result);
      unset($result);

      $log = fopen("task_log.txt", "a");
      fwrite($log, "\n" . date("m/d/Y @ g:i:sA") . " - Task Added: " . $newTask);;
      fclose($log);   

      echo "server - task " . $newTask . " added successfully";
      exit;
  }
  else if ($_POST['func'] == "deleteTask") {

      $file = file_get_contents('JSON/taskList.json', true);
      $data = json_decode($file, true);
      unset($file);

      array_splice($data, $_POST['index'], 1);

      $result = json_encode($data);
      file_put_contents('JSON/taskList.json', $result);
      unset($result);

      $log = fopen("task_log.txt", "a");
      fwrite($log, "\n" . date("m/d/Y @ g:i:sA") . " - Task Deleted");;
      fclose($log); 

      echo "server - task removed successfully";
      exit;
  }
?>