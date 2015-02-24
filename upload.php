<?php


if (isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {

    $extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

    if (move_uploaded_file($_FILES['upl']['tmp_name'], 'uploads/' . $_FILES['upl']['name'])) {
        echo '{"status":"success"}';


        $file = file_get_contents('JSON/GB_songs.json', true);
        $data = json_decode($file, true);
        unset($file);

        $basename = pathinfo($_FILES['upl']['name'], PATHINFO_BASENAME);
        //$data[0]["name"] = $basename;

        $data[] = array('name' => $basename);

        $result = json_encode($data);
        file_put_contents('JSON/GB_songs.json', $result);
        unset($result);

        exit;
    }
}

echo '{"status":"error"}';
exit;
