<?php
require_once('env.php');
if (isset($_POST['genid'])) {
	$genid = $_POST['genid'];
} else if (isset($_GET['genid'])) {
	$genid = $_GET['genid'];
} else {
	$obj = new \stdClass;
	$obj->status = -1;
	$obj->url = "";
	echo json_encode($obj);
	exit();
}
$conn = new mysqli($mysql_server, $mysql_username, $mysql_pass, $mysql_db);
echo("SELECT status, downfilename from uploads WHERE genid = '" + $genid + "'");
$result = $conn->query("SELECT status, downfilename from uploads WHERE genid = '" + $genid + "'");
var_dump($result);
exit();
$row = $result->fetch_assoc();
$obj = new \stdClass;
$obj->status = $row["status"];
$obj->url = $row["downfilename"];
echo json_encode($obj);
?>
