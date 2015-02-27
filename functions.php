<?php

set_include_path('ChromePHP.php');

$file = file_get_contents('JSON/songs.json', true);
$data = json_decode($file, true);
unset($file);

array_splice($data, $_POST['index'], 1);

$result = json_encode($data);
file_put_contents('JSON/songs.json', $result);
unset($result);

$fileToRemove = 'uploads/' . (string)$_POST['filename'];
unlink($fileToRemove);

exit;
