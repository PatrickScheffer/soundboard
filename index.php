<?php
require_once('config.php');
require_once 'lib/medoo.min.php';
require_once('lib/global.php');

$page = $config['home'];
if (arg(0)) {
	$page = arg(0);
}

$main_regions = array(
	'js' => load_javascript($page),
	'body' => load_template($page),
);

print load_template('html', $main_regions);
