<?php
/**
 * Safe alternative for $_GET.
 * All keys and values are parsed through htmlspecialchars.
 *
 * @param $key
 *   String used as key in the $_GET array.
 *
 * @return
 *   The value of $_GET[$key] or the whole $_GET array.
 */
function get($key = '') {
	$params = array();

	// Check if $key isn't empty.
	if (!empty($key)) {
		// Check if $key exists in $_GET.
		if (isset($_GET[ $key ])) {
			// Return a safe value.
			return htmlspecialchars($_GET[ $key ], ENT_QUOTES);
		}
		// Return false if $key doesn't exists in $_GET.
		return FALSE;
	}

	// If $key is empty, return the whole $_GET array if it isn't empty.
	if (!empty($_GET)) {
		foreach ($_GET as $get_key => $get_value) {
			$get_key = htmlspecialchars($get_key, ENT_QUOTES);
			$get_value = htmlspecialchars($get_value, ENT_QUOTES);

			$params[ $get_key ] = $get_value;
		}
	}

	return $params;
}

/**
 * Safe alternative for $_POST.
 * All keys and values are parsed through htmlspecialchars.
 *
 * @param $key
 *   String used as key in the $_POST array.
 *
 * @return
 *   The value of $_POST[$key] or the whole $_POST array.
 */
function post($key = '') {
	$params = array();

	// Check if $key isn't empty.
	if (!empty($key)) {
		// Check if $key exists in $_POST.
		if (isset($_POST[ $key ])) {
			// Return a safe value.
			return htmlspecialchars($_POST[ $key ], ENT_QUOTES);
		}
		// Return false if $key doesn't exists in $_POST.
		return FALSE;
	}

	// If $key is empty, return the whole $_POST array if it isn't empty.
	if (!empty($_POST)) {
		foreach ($_POST as $post_key => $post_value) {
			$post_key = htmlspecialchars($post_key, ENT_QUOTES);
			$post_value = htmlspecialchars($post_value, ENT_QUOTES);

			$params[ $post_key ] = $post_value;
		}
	}

	return $params;
}

/**
 * Add a log to the database and clear old logs.
 */
function add_log($subject, $message) {
	global $config;

	// Connect to the database.
	$db = new medoo($config['database']);

	// Insert the new log.
	$db->insert($config['table_prefix'] . 'log', array(
		'subject' => htmlspecialchars($subject, ENT_QUOTES),
		'message' => htmlspecialchars($message, ENT_QUOTES),
		'date' => time(),
	));

	// Check if the log count is over 500.
	if ($db->count('log') > 500) {
		// Get the oldest log (with the lowest id).
		$oldest_log = $db->min('log', 'id');
		// Delete it.
		$db->delete('log', array(
			'AND' => array(
				'id' => $oldest_log,
			),
		));
	}
}

function global_validate() {
	global $config;
	
	if (!isset($_SERVER['HTTP_REFERER'])) {
		add_log('POST', 'No referer found.');
		set_message('Something went wrong. Please try again.');
		return FALSE;
	}

	if (!in_array($_SERVER['HTTP_HOST'], $config['domains'])) {
		add_log('POST', 'Invalid request: HTTP_REFERER (' . $_SERVER['HTTP_REFERER'] . ') is not one of the specified domains.');
		set_message('Something went wrong. Please try again.');
		return FALSE;
	}

	return TRUE;
}

function set_message($message = '', $type = 'confirm') {
	if (empty($message)) {
		return FALSE;
	}

	if (empty($_SESSION['message'])) {
		$_SESSION['message'] = array();
	}

	if (empty($_SESSION['message'][ $type ])) {
		$_SESSION['message'][ $type ] = array();
	}

	$_SESSION['message'][ $type ][] = $message;

	return TRUE;
}

function clear_messages($type = 'confirm') {
	if (!empty($_SESSION['message'][ $type ])) {
		unset($_SESSION['message'][ $type ]);
		return TRUE;
	}
	return FALSE;
}

function get_messages($type = 'all') {
	$output = '';
	$list = '';

	if (empty($_SESSION['message'])) {
		return $output;
	}

	if ($type == 'all') {
		foreach ($_SESSION['message'] as $type => $messages) {
			if (!empty($messages)) {
				$list .= '<ul class="' . $type . '">';
				foreach ($messages as $message) {
					$list .= '<li>' . $message . '</li>';
				}
				$list .= '</ul>';
			}

			unset($_SESSION['message'][ $type ]);
		}
	} elseif (!empty($_SESSION['message'][ $type ])) {
		$list .= '<ul class="' . $type . '">';
		foreach ($_SESSION['message'][ $type ] as $message) {
			$list .= '<li>' . $message . '</li>';
		}
		$list .= '</ul>';

		unset($_SESSION['message'][ $type ]);
	}

	if (!empty($list)) {
		$output .= '<div class="messages">' . $list . '</div>';
	}

	return $output;
}