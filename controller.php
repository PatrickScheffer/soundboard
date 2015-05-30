<?php
require_once('config.php');
require_once('lib/medoo.min.php');
require_once('lib/global.php');

$error = FALSE;
$error_msg = array();
$complete_upload_form = '
	<form action="controller.php" method="post" class="filename_form">
		<label for="file_name">Filename:</label>
		<input type="text" id="file_name" name="file_name" />
		<input type="submit" value="Submit" />
		<a href="controller.php?cancel">Cancel and delete file</a>
		<div class="form_error"></div>
	</form>
	<script>
	$(".filename_form").submit(function(event) {
    if ($("#file_name").val() == "") {
    	$(".form_error").html("Please enter a valid name.");
    	event.preventDefault();
  	}
  });
	</script>
';

$post_data = post();

if (!empty($_FILES)) {
	if (!empty($_SESSION['file_id'])) {
		print 'Your previous audio file is pending completion.<br />Give it a name to complete the upload and record a new audio file.';
		print $complete_upload_form;
	} else {
		if (global_validate() && $file = file_validate()) {
			upload_file($file);
		} else {
			print get_messages();
		}
	}
} elseif (!empty($post_data['file_name'])) {
	update_file_name($post_data['file_name']);
} elseif (isset($_GET['cancel']) && !empty($_SESSION['file_id'])) {
	if (delete_file($_SESSION['file_id'])) {
		unset($_SESSION['file_id']);
	}
	header('Location: ' . $config['root'] . 'recorder');
} else {
	header('Location: ' . $config['root']);
}

function file_validate() {
	global $config;

	$filename = key($_FILES);
	$file = $_FILES[ $filename ];

	// Check if target dir exists.
	if (!is_dir($config['upload_directory'])) {
		add_log('File Upload', 'No upload directory found.');
		set_message('Something went wrong. Please try again.', 'error');
		return FALSE;
	}

	// Check if there is a file.
	if (empty($file)) {
		add_log('File Upload', 'Empty file.');
		set_message('Empty file found.', 'error');
		return FALSE;
	}

	// Check if the file type is audio.
	if (empty($file['type']) || $file['type'] != $config['file_type']) {
		add_log('File Upload', 'File type does not match.');
		set_message('Invalid file type.', 'error');
		return FALSE;
	}

	// Check if the file size does not exceeds the max file size.
	if (empty($file['size']) || $file['size'] > $config['max_file_size']) {
		add_log('File Upload', 'Invalid file size.');
		set_message('File size exceeds limit (' . ($config['max_file_size'] / (1024 * 1024)) . 'mb)', 'error');
		return FALSE;
	}

	// Check if the file exists in the tmp folder.
	if (empty($file['tmp_name']) || !file_exists($file['tmp_name'])) {
		add_log('File Upload', 'No temporary name found.');
		set_message('Something went wrong. Please try again.', 'error');
		return FALSE;
	}

	return $file;
}

function upload_file($file = array()) {
	global $config, $complete_upload_form;

	if (empty($file)) {
		return FALSE;
	}

	// Set the current time as filename to avoid duplicate filenames.
	$filename = time();

	// Set target.
	$target = $config['upload_directory'] . '/' . $filename . '.wav';

	// Move the file from tmp to the target.
	if (move_uploaded_file($file['tmp_name'], $target)) {
		// Connect to the database.
		$db = new medoo($config['database']);

		// Insert the file in the database.
		$file_id = $db->insert($config['table_prefix'] . 'files', array(
			'path' => htmlspecialchars($target, ENT_QUOTES),
			'timestamp' => time(),
		));

		if ($file_id) {
			$_SESSION['file_id'] = $file_id;

			print 'Successfully uploaded your audio file. Give it a name to complete the upload.';
			print $complete_upload_form;
		} else {
			print 'Error uploading your audio file. Please try again.';
		}
	} else {
		print 'Error uploading your audio file. Please try again.';
	}
}

function update_file_name($filename = '') {
	global $config;

	if (empty($_SESSION['file_id'])) {
		set_message('There is no audio file found to give a name to.', 'error');
		return FALSE;
	}

	if (empty($filename)) {
		set_message('Please enter a valid filename.', 'error');
		return FALSE;
	}

	$filename = htmlspecialchars(trim($filename), ENT_QUOTES);
	
	if (empty($filename)) {
		set_message('Please enter a valid filename.', 'error');
		header('Location: ' . $config['root']);
		return FALSE;
	}

	// Connect to the database.
	$db = new medoo($config['database']);

	// Insert the file in the database.
	$db->update($config['table_prefix'] . 'files', array(
		'name' => $filename,
	), array(
		'id' => htmlspecialchars($_SESSION['file_id'], ENT_QUOTES),
	));

	unset($_SESSION['file_id']);

	set_message('Successfully added a name to your audio file.');

	header('Location: ' . $config['root']);
}

function delete_file($file_id = 0) {
	global $config;

	// Stop if we have no file id.
	if (empty($file_id)) {
		return FALSE;
	}

	// Connect to the database.
	$db = new medoo($config['database']);

	// Load the file path.
	$result = $db->select($config['table_prefix'] . 'files', array(
		'path',
	), array(
		'id' => htmlspecialchars($file_id, ENT_QUOTES),
	));

	// Check if we have a file path.
	if (!empty($result[0]['path'])) {
		// Check if the path existss.
		if (file_exists($result[0]['path'])) {
			// Remove the file.
			if (!unlink($result[0]['path'])) {
				set_message('Failed deleting your file. Please try again.', 'error');
				return FALSE;
			}
		}

		// Also remove the file from the database.
		$rows_deleted = $db->delete($config['table_prefix'] . 'files', array(
			'id' => htmlspecialchars($file_id, ENT_QUOTES),
		));

		if (!empty($rows_deleted)) {
			return TRUE;
		}
	}

	return FALSE;
}