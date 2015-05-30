<?php
session_start();

$config = array();

// Database configuration.
$config['database'] = array(
	'database_type' => 'mysql',
	'database_name' => 'soundboard',
	'server' => 'localhost',
	'username' => 'username',
	'password' => 'password',
	'charset' => 'utf8'
);

// Table prefix.
$config['table_prefix'] = 'soundboard_';

// Root directory.
$config['root'] = '/soundboard/';

// Root directory.
$config['root'] = '/soundboard/';

// Template directory.
$config['template_dir'] = 'templates';

// JavaScript directory.
$config['js_dir'] = 'js';

// Stylesheets directory.
$config['css_dir'] = 'css';

// A list of safe domains.
$config['domains'] = array(
	'127.0.0.1',
);

// The default page.
$config['home'] = 'dashboard';

// Upload validators.
$config['upload_directory'] = 'audio';
$config['max_file_size'] = 1024 * 1024 * 2; // 2MB
$config['file_type'] = 'audio/wav';

// Optionally stylesheet files to include on certain pages.
$config['css'] = array(
	'all' => array(
		'bootstrap.min',
		'style',
	),
	'dashboard' => array(
		'360player/360player',
		'360player/flashblock',
		'dashboard',
	),
	'recorder' => array(
		'recorder',
	),
);

// Optionally javascript files to include on certain pages.
$config['js'] = array(
	'all' => array(
		'jquery-1.11.2.min',
	),
	'dashboard' => array(
		'360player/berniecode-animator',
		'360player/soundmanager2',
		'360player/360player',
		'360player/soundmanager-config',
		'dashboard',
	),
	'recorder' => array(
		'jquery.popupoverlay',
		'recorder/jquery.upload',
		'recorder/audiodisplay',
		'recorder/recorder',
		'recorder/main',
	),
);
