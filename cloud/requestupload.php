<?php
require_once('env.php');
require_once('google/appengine/api/cloud_storage/CloudStorageTools.php');
use google\appengine\api\cloud_storage\CloudStorageTools;
$options = ['gs_bucket_name' => $bucket_name];
$upload_url = CloudStorageTools::createUploadUrl('/convertupload', $options);
echo $upload_url;
?>
