<?php
	if (!function_exists('wpe_force_lowercase')) {
		function wpe_force_lowercase($filename) {
			$info = pathinfo($filename);
			$ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
			$name = basename($filename,$ext);
			return strtolower($name).$ext;
		}
		add_filter('sanitize_file_name', 'wpe_force_lowercase', 10);
	}