<?php
require_once('env.php');
require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
use google\appengine\api\cloud_storage\CloudStorageTools;
$gs_name = $_FILES['convertFile']['tmp_name'];
$real_name = $_FILES['convertFile']['name'];
$filename = "gs://" . $bucket_name . "/" . $real_name;
//move_uploaded_file($gs_name, "gs://" . $bucket_name . "/" . $id . ".txt");
$options = array('gs'=>array('acl'=>'public-read','Content-Type' => $_FILES['convertFile']['type']));
$ctx = stream_context_create($options);
if (false == rename($_FILES['convertFile']['tmp_name'], $filename, $ctx)) {
  die('Could not rename.');
}
$object_public_url = CloudStorageTools::getPublicUrl($filename, true);
echo $object_public_url;
?>
