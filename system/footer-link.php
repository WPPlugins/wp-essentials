<?php
	if (!function_exists('wpe_footer_link')) {
		function wpe_footer_link() {
			echo '<p style="text-align:center;">Powered by <a href="http://www.wp-essentials.net">WP Essentials</a></p>';	
		}
		add_action('wp_footer', 'wpe_footer_link');
	}