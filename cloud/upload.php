<?php
require_once('env.php');
require_once 'google/appengine/api/cloud_storage/CloudStorageTools.php';
use google\appengine\api\cloud_storage\CloudStorageTools;
$gs_name = $_FILES['convertFile']['tmp_name'];
$id = uniqid();
$convertFormat = $_POST['convert'];
$filename = "gs://" . $bucket_name . "/" . $id;
//move_uploaded_file($gs_name, "gs://" . $bucket_name . "/" . $id . ".txt");
$options = array('gs'=>array('acl'=>'public-read','Content-Type' => $_FILES['convertFile']['type']));
$ctx = stream_context_create($options);
if (false == rename($_FILES['convertFile']['tmp_name'], $filename, $ctx)) {
  die('Could not rename.');
}
$object_public_url = CloudStorageTools::getPublicUrl($filename, true);

$conn = new mysqli($mysql_server, $mysql_username, $mysql_pass, $mysql_db);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
$sql = "INSERT INTO uploads (genid, upfilename, fileconvert) VALUES ('" . $id . "', '" . $object_public_url . "', '" . $convertFormat . "')";
$conn->query($sql);
?>
<html>
   <head>
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Cloud Converter</title>
         <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
   </head>
   <body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <a class="navbar-brand" href="/">Cloud Converter</a>
      </nav>
      <div class="container">
		  <div class="row mb-2 offset-1">
			  <div class="col-10 text-center">
				  Unique Code: <?php echo $id; ?>
			  </div>
		  </div>
		  <div class="row mb-2 offset-1">
			  <div class="col-10 text-center">
				  <div class="progress">
					  <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="33.33" aria-valuemin="0" aria-valuemax="100" style="width: 33.33%"></div>
				  </div>
			  </div>
		  </div>
		  <div class="row mb-2 offset-1">
			  <div class="col-10 text-center">
				  Status: <span class="statusTxt">Uploaded...</span>
			  </div>
		  </div>
		  <div class="row mb-2 offset-1">
			  <div class="col-10 text-center">
				  <a id="downloadLink" href=""></a>
			  </div>
		  </div>
      </div>
      <script>
		  window.uploadID = "<?php echo $id; ?>";
		  function updateProgress() {
			  console.log("Updating");
			  $.getJSON("/status?genid=" + uploadID, function (result) {
				  if (result.status == 1) {
					  progressVal = 66.66;
					  $('.statusTxt').text("Processing...");
					  $('.progress-bar').css('width', progressVal+'%').attr('aria-valuenow', progressVal);
					  window.updateTimer = setTimeout(function(){ updateProgress(); }, 3000);
				  } else if (result.status == 2) {
					  progressVal = 100;
					  $('.statusTxt').text("Finished");
					  $('#downloadLink').attr('href', result.url);
					  $('#downloadLink').text(result.url);
					  $('.progress-bar').css('width', progressVal+'%').attr('aria-valuenow', progressVal);
					  $('.progress-bar').removeClass('progress-bar-animated');
				  } else if (result.status == 0) {
					  window.updateTimer = setTimeout(function(){ updateProgress(); }, 3000);
				  }
			});
		  }
		  window.updateTimer = setTimeout(function(){ updateProgress(); }, 3000);
      </script>
   </body>
</html>
