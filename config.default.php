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

// A list of safe domains.
$config['domains'] = array(
	'127.0.0.1',
);

// Upload validators.
$config['upload_directory'] = 'audio';
$config['max_file_size'] = 1024 * 1024 * 2; // 2MB
$config['file_type'] = 'audio/wav';
