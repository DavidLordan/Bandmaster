<?php

  if (isset($_FILES['upl'])) {
    
    /* If the file size limit specified in php.ini is exceeded.  */
    if ($_FILES['upl']['error'] == 1) {
      echo "server - File was too large. Upload Failed.";
      date_default_timezone_set('America/New_York');
      $log = fopen("file_log.txt", "a");
      fwrite($log, "\n" . date("m/d/Y @ g:i:sA") . " - Failed to Upload File: " . 'uploads/' . $_FILES['upl']['name']);
      fclose($log);
      exit;
    }

    /* If the file is not null and there is no error, the file uploaded with success. */
    if ($_FILES['upl']['error'] == 0) {
      
      $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

      if (move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/' . $_FILES['upl']['name'])) {
        $file = file_get_contents('JSON/songs.json', true);
        $data = json_decode($file, true);
        unset($file);
        date_default_timezone_set('America/New_York');
        $log = fopen("file_log.txt", "a");
        fwrite($log, "\n" . date("m/d/Y @ g:i:sA") . " - File Uploaded: " . 'uploads/' . $_FILES['upl']['name']);
        fclose($log);

        $basename = pathinfo($_FILES['upl']['name'], PATHINFO_BASENAME);
        //$data[0]["name"] = $basename;

        $data[] = array('name' => $basename);

        $result = json_encode($data);
        file_put_contents('JSON/songs.json', $result);
        unset($result);
        
        echo "server - File Uploaded Successfully.";
        exit;
      }
    }
  }

  echo "server - Error Uploading file";
  exit;
?>
