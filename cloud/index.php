<?php
require_once('env.php');
require_once('google/appengine/api/cloud_storage/CloudStorageTools.php');
use google\appengine\api\cloud_storage\CloudStorageTools;
$options = ['gs_bucket_name' => $bucket_name];
$upload_url = CloudStorageTools::createUploadUrl('/upload', $options);
?>
<html>
   <head>
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Cloud Converter</title>
         <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
         <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
         <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
   </head>
   <body>
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <a class="navbar-brand" href="#">Cloud Converter</a>
      </nav>
      <div class="container">
         <div class="row mb-2 offset-1">
            <div class="col-10 text-center">
               Select file to convert, select options and then upload
            </div>
         </div>
         <form action="<?php echo $upload_url; ?>" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
               <div class="col-10 offset-1">
                  <div class="custom-file">
                     <input type="file" class="custom-file-input" id="customFile" name="convertFile" required>
                     <label class="custom-file-label" for="customFile">Choose file</label>
                  </div>
               </div>
            </div>
            <div class="row mb-3">
               <div class="col-10 offset-1 text-center">
                  <div class="custom-control custom-radio custom-control-inline">
                     <input type="radio" id="convertMP4" name="convert" value="mp4" class="custom-control-input" required>
                     <label class="custom-control-label" for="convertMP4">MP4</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input type="radio" id="convertAVI" name="convert" value="avi" class="custom-control-input" required>
                     <label class="custom-control-label" for="convertAVI">AVI</label>
                  </div>
                  <div class="custom-control custom-radio custom-control-inline">
                     <input type="radio" id="convertMP3" name="convert" value="mp3" class="custom-control-input" required>
                     <label class="custom-control-label" for="convertMP3">MP3</label>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-10 text-center offset-1">
                  <input type="submit" class="btn btn-primary" value="Upload">
               </div>
            </div>
         </form>
      </div>
      <script>
         $('.custom-file-input').on('change',function(){
            var fileName = $(this).val().replace(/C:\\fakepath\\/i, '');
            $(this).next('.custom-file-label').html(fileName);
         })
      </script>
   </body>
</html>
