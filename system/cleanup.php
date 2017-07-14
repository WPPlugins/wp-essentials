<?php
	// Set up permalink structure
		if (get_option('permalink_structure')=='') {
			function clean_urls() {
				global $wp_rewrite;
				$wp_rewrite->set_permalink_structure('/%category%/%postname%/');
			}
			add_action('init','clean_urls');
		}
	
	// Remove useless widgets from dashboard
		if (!function_exists('disable_default_dashboard_widgets')) {
			function disable_default_dashboard_widgets() {
				remove_meta_box('dashboard_plugins','dashboard','core');
				remove_meta_box('dashboard_primary','dashboard','core');
				remove_meta_box('dashboard_secondary','dashboard','core');
			}
			add_action('admin_menu','disable_default_dashboard_widgets');
		}
	
	// Remove superfluous header info
		remove_action('wp_head','rsd_link');
		remove_action('wp_head','wlwmanifest_link');
		remove_action('wp_head','wp_generator');
		remove_action('wp_head','start_post_rel_link');
		remove_action('wp_head','index_rel_link');
		remove_action('wp_head','adjacent_posts_rel_link');
	
	// Remove detailed login errors
		if (!function_exists('login_error_message')) {
			add_filter('login_errors','login_error_message');
			function login_error_message($error){
				$error = "<strong>ERROR:</strong> Your login details are incorrect.";
				return $error;
			}
		}