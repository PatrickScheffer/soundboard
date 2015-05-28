<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Soundboard</title>

	<?php if (!empty($regions['js'])):?>
		<?php print $regions['js'];?>
	<?php endif;?>

	<script type="text/javascript">

soundManager.setup({
  // path to directory containing SM2 SWF
  url: '../../swf/'
});

threeSixtyPlayer.config.scaleFont = (navigator.userAgent.match(/msie/i)?false:true);
threeSixtyPlayer.config.showHMSTime = true;

// enable some spectrum stuffs

threeSixtyPlayer.config.useWaveformData = true;
threeSixtyPlayer.config.useEQData = true;

// enable this in SM2 as well, as needed

if (threeSixtyPlayer.config.useWaveformData) {
  soundManager.flash9Options.useWaveformData = true;
}
if (threeSixtyPlayer.config.useEQData) {
  soundManager.flash9Options.useEQData = true;
}
if (threeSixtyPlayer.config.usePeakData) {
  soundManager.flash9Options.usePeakData = true;
}

if (threeSixtyPlayer.config.useWaveformData || threeSixtyPlayer.flash9Options.useEQData || threeSixtyPlayer.flash9Options.usePeakData) {
  // even if HTML5 supports MP3, prefer flash so the visualization features can be used.
  soundManager.preferFlash = true;
}

// favicon is expensive CPU-wise, but can be enabled.
threeSixtyPlayer.config.useFavIcon = false;

</script>

	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>

<h1>Soundboard</h1>

<?php print get_messages();?>

<?php if (!empty($regions['body'])):?>
	<?php print $regions['body'];?>
<?php endif;?>

</body>
</html>