<?php

set_include_path('ChromePHP.php');

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

    exit;
} else if ($_POST['func'] == "addTask") {

    $file = file_get_contents('JSON/taskList.json', true);
    $data = json_decode($file, true);
    unset($file);

    $newTask = $_POST['newTask'];
    $data[] = array('task' => $newTask);

    $result = json_encode($data);
    file_put_contents('JSON/taskList.json', $result);
    unset($result);

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

    exit;
    
}