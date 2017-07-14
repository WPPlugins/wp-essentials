<?php
	if (!function_exists('error_report')) {
		function error_report() {
			$errors = '';
			
			if (!get_option('wpe_google_analytics')&&get_option('wpe_error_reports_google_analytics')) {
				$errors .= '<li>Your website does not have Google Analytics tracking installed. <a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=wp-essentials#wpe_google_analytics">Fix &raquo;</a></li>';
			}
			
			if (!get_option('blog_public')&&get_option('wpe_error_reports_search_engines')) {
				$errors .= '<li>Search engines are blocked from this website. <a href="'.get_bloginfo('wpurl').'/wp-admin/options-reading.php">Fix &raquo;</a></li>';
			}
			
			if (get_option('wpe_instagram_username') && get_option('wpe_instagram_client_id') && !get_option('wpe_instagram_user_id') && !get_option('wpe_instagram_access_token')) {
				$errors .= '<li>Your Instagram set up is almost complete. <a href="https://api.instagram.com/oauth/authorize/?client_id='.get_option('wpe_instagram_client_id').'&redirect_uri='.get_bloginfo('wpurl').'/wp-admin/index.php&response_type=code">Continue &raquo;</a></li>';
			}
			
			if (username_exists('admin')&&get_option('wpe_error_reports_check_username')) { 
				$errors .= '<li>The username <code>admin</code> is vulnerable to attacks. Please set up a new user and delete <code>admin</code> <a href="'.get_bloginfo('wpurl').'/wp-admin/user-new.php">Fix &raquo;</a></li>';
			}
			
			if (function_exists('wpe_error_reports')) { $errors.= wpe_error_reports(); }
			
			if ($errors) {
				echo '
					<div class="error wpe_error_reporting">
						<p style="float:right;">Powered by <a href="https://www.wp-essentials.net">WP Essentials</a></p>
						<p>The following issues have been found:</p>
						<ul style="margin:0px 20px;list-style:disc;">
							'.$errors.'
						</ul>
					</div>
				';
			}
		}
		add_filter('admin_notices', 'error_report');
	}