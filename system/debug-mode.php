<?php
	if (current_user_can('administrator') && !is_admin()) {
		ini_set('display_errors',1); 
		error_reporting(E_ALL);
		include('php-error.php');
		\php_error\reportErrors(array(
			'wordpress' => true
		));
		function maintenance_mode() {
			echo '<div id="debug_mode" style="background:rgba(255,0,0,0.5);text-align:center;padding:5px 0px 3px;font-size:11px;color:#000;border-bottom:1px solid #f00;">Debug Mode: No errors found.</div>';
		}
		add_action('wp_head', 'maintenance_mode');
	}