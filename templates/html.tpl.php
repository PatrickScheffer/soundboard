<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Soundboard</title>

	<?php if (!empty($regions['js'])):?>
		<?php print $regions['js'];?>
	<?php endif;?>

	<?php if (!empty($regions['css'])):?>
		<?php print $regions['css'];?>
	<?php endif;?>

</head>
<body>

<h1>Soundboard</h1>

<?php print get_messages();?>

<?php if (!empty($regions['body'])):?>
	<?php print $regions['body'];?>
<?php endif;?>

</body>
</html>