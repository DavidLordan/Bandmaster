<?php

  if (isset($_FILES['upl'])) {
    
    /* If the file size limit specified in php.ini is exceeded.  */
    if ($_FILES['upl']['error'] == 1) {
      echo "server - File was too large. Upload Failed.";
      exit;
    }

    /* If the file is not null and there is no error, the file uploaded with success. */
    if ($_FILES['upl']['error'] == 0) {
      
      $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

      if (move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/' . $_FILES['upl']['name'])) {
        $file = file_get_contents('JSON/songs.json', true);
        $data = json_decode($file, true);
        unset($file);

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
