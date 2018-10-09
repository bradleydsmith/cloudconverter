<?php
require_once('env.php');
require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
use google\appengine\api\cloud_storage\CloudStorageTools;
$gs_name = $_FILES['convertFile']['tmp_name'];
$id = uniqid();
$filename = "gs://" . $bucket_name . "/" . $id . ".txt";
//move_uploaded_file($gs_name, "gs://" . $bucket_name . "/" . $id . ".txt");
$options = array('gs'=>array('acl'=>'public-read','Content-Type' => $_FILES['convertFile']['type']));
$ctx = stream_context_create($options);
if (false == rename($_FILES['convertFile']['tmp_name'], $filename, $ctx)) {
  die('Could not rename.');
}
$object_public_url = CloudStorageTools::getPublicUrl($filename, true);
var_dump($_FILES);
echo $id;
$conn = new mysqli($mysql_server, $mysql_username, $mysql_pass, $mysql_db);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$sql = "INSERT INTO uploads (genid, upfilename, fileconvert) VALUES ('" . $id . "', '" . $id . "', 'MP4')";
$conn->query($sql);
?>
<br>
<br>
<a href="<?php echo $object_public_url?>">Public</a>
