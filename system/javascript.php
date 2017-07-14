<?php
	if (is_admin()) {
		if (!function_exists('loadJS')) {
			function loadJS() {
				echo '<script>var path_url = "'.ESSENTIALS_PATH.'";</script>';
			}
			add_action('admin_head','loadJS');
		}
	}