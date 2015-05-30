	<div id="complete_upload_popup" class="well">
    <div class="upload_progress"></div>
    <div class="upload_complete_content"></div>
  </div>

	<?php print get_messages();?>

	<a href="<?php print $config['root'];?>">Dashboard</a>

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
      scrolllock: true,
      blur: false,
      escape: false,
    });
	</script>