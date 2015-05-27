<?php
require_once('config.php');
require_once('lib/global.php');
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Soundboard</title>

	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/jquery.popupoverlay.js"></script>
	<script src="js/jquery.upload.js"></script>
	<script src="js/audiodisplay.js"></script>
	<script src="js/recorder.js"></script>
	<script src="js/main.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

	<div id="complete_upload_popup" class="well">
    <div class="upload_progress"></div>
    <div class="upload_complete_content"></div>
  </div>

	<?php print get_messages();?>

	<div id="viz">
		<canvas id="analyser" width="500" height="200"></canvas>
		<canvas id="wavedisplay" width="500" height="200"></canvas>
		<div class="record_timer"></div>
	</div>
	<div id="controls">
		<div class="record_wrapper">
			<img id="record" src="images/mic128.png" onclick="toggleRecording(this);" />
		</div>
		<div class="save_wrapper">
			<a id="save" href="index.html#"><img src="images/save.svg"></a>
		</div>
	</div>

	<script>
		$('#complete_upload_popup').popup({
      transition: 'all 0.3s',
    });
	</script>

</body></html>