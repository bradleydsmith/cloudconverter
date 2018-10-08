<?php
require_once('env.php');
$gs_name = $_FILES['convertFile']['tmp_name'];
$id = uniqid();
move_uploaded_file($gs_name, "gs://" . $bucket_name . "/" . $id . ".txt");
var_dump($_FILES);
echo $id;
?>
