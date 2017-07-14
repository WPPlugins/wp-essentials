<?php
	/*
		Plugin Name: WP Essentials
		Plugin URI: http://www.wp-essentials.net
		Description: All-in-one bundle of essential plugins and functions for all WordPress websites.
		Version: 2.3
		Author: wp-essentials
		Author URI: http://www.wp-essentials.net
	*/
	
	// Essentails Setup
		define('ESSENTIALS_VERSION', '2.3');
		define('ESSENTIALS_DATE', '2015-07-04');
		define('ESSENTIALS_DIR', dirname(__FILE__));
		define('ESSENTIALS_PATH', plugins_url().'/wp-essentials');
		
	// Add Options		
		function add_options() {
			add_option('wpe_cleanup',1);
			add_option('wpe_lowercase_filenames',1);
			add_option('wpe_total_user_roles',1);
			add_option('wpe_user_role_1','Client;1;1;1;1;1;1;1;1;1;1;1;1;0;0');
			add_option('wpe_custom_image_sizes',1);
			add_option('wpe_error_reports',1);
			add_option('wpe_error_reports_google_analytics',1);
			add_option('wpe_error_reports_search_engines',1);
			add_option('wpe_error_reports_check_username',1);
			add_option('wpe_footer_link',0);
			add_option('wpe_image_quality',75);
			add_option('wpe_javascript',1);
			add_option('wpe_google_analytics','');
			add_option('wpe_google_link_tracking',0);
			add_option('wpe_php_date',1);
			add_option('wpe_debug_mode',0);
			add_option('wpe_facebook',1);
			add_option('wpe_flickr_db',0);
			add_option('wpe_flickr_username','');
			add_option('wpe_flickr_api','');
			add_option('wpe_google_maps',1);
			add_option('wpe_twitter_db',0);
			add_option('wpe_twitter_username','');
			add_option('wpe_twitter_consumer_key','');
			add_option('wpe_twitter_consumer_secret','');
			add_option('wpe_twitter_oauth_access_token','');
			add_option('wpe_twitter_oauth_access_token_secret','');
			add_option('wpe_style','css');
			add_option('wpe_responsive',1);
			add_option('wpe_email',1);
			add_option('wpe_video',1);
			add_option('wpe_excerpt',1);
			add_option('wpe_get_image_source',1);
			add_option('wpe_link_it',1);
			add_option('wpe_relative_time',1);
		}
		
		register_activation_hook(__FILE__,'add_options');
		
	// Load Plugins
		function essentials_load(){			
			// System
				if (get_option('wpe_cleanup')==1) { include("system/cleanup.php"); }
				if (get_option('wpe_lowercase_filenames')==1) { include("system/lowercase-filenames.php"); }
				include("system/user-roles.php");
				include("system/custom-image-sizes.php");
				if (get_option('wpe_debug_mode')==1) { include("system/debug-mode.php"); }
				if (get_option('wpe_error_reports')==1) { include("system/error-reports.php"); }
				if (get_option('wpe_footer_link')==1) { include("system/footer-link.php"); }
				include("system/image-quality.php");
				if (get_option('wpe_javascript')==1) { include("system/javascript.php"); }
			
			// Plugins
				if (get_option('wpe_email')==1) { include("plugins/email.php"); }
				include("plugins/analytics.php");
				if (get_option('wpe_facebook')==1) { include("plugins/facebook.php"); }
				include("plugins/flickr.php");
				if (get_option('wpe_google_maps')==1) { include("plugins/google-maps.php"); }
				include("plugins/twitter.php");
				if (get_option('wpe_responsive')==1) { include("plugins/responsive.php"); }
				
			// Shortcodes
				if (get_option('wpe_php_date')==1) { include("shortcodes/php-date.php"); }
				if (get_option('wpe_video')==1) { include("shortcodes/video.php"); }
				
			// Custom Functions
				if (get_option('wpe_excerpt')==1) { include("functions/excerpt.php"); }
				if (get_option('wpe_get_image_source')==1) { include("functions/get-image-source.php"); }
				if (get_option('wpe_link_it')==1) { include("functions/link-it.php"); }
				if (get_option('wpe_relative_time')==1) { include("functions/relative-time.php"); }
		}
		add_action('widgets_init', 'essentials_load');
		
	// Load Scripts & Styles
		function wpe_scripts() {
			
			// Load Font CSS
				wp_enqueue_style('wpe_fonts',ESSENTIALS_PATH.'/styles/wpe-font.css',false);
			
			// Load WPE Styles
				$screen = get_current_screen();
				if ($screen->base == 'toplevel_page_wp-essentials') {
					wp_enqueue_script('wpe_slider',ESSENTIALS_PATH.'/scripts/wpe-slider.js',false);
					wp_enqueue_script('wpe_settings',ESSENTIALS_PATH.'/scripts/wpe-settings.js',false);
					wp_enqueue_style('wpe_styles',ESSENTIALS_PATH.'/styles/wpe-style.css',false);
				}
		}
		add_action('admin_enqueue_scripts','wpe_scripts');
		
	// Load Public Scripts & Styles
		if (!is_admin()) {
			wp_enqueue_style('wpe_public_styles',ESSENTIALS_PATH.'/styles/wpe-public-styles.css',false);	
			wp_enqueue_script('wpe_public_settings',ESSENTIALS_PATH.'/scripts/wpe-public-settings.js',array('jquery'));	
		}
		
	// Load jQuery
		if (!is_admin()) wp_enqueue_script("jquery");
	
	// Add Essentails Menu
		function wpe_menu() {
			add_menu_page('WP Essentials', 'WP Essentials', 'manage_options', 'wp-essentials', 'wpe_function','none');
			
			// System
				add_submenu_page('wp-essentials','Cleanup','Cleanup','manage_options','admin.php?page=wp-essentials#wpe_cleanup');
				add_submenu_page('wp-essentials','Case-sensitive','Case-sensitive','manage_options','admin.php?page=wp-essentials#wpe_lowercase_filenames');
				add_submenu_page('wp-essentials','User Roles','User Roles','manage_options','admin.php?page=wp-essentials#wpe_user_roles');
				add_submenu_page('wp-essentials','Database Backups','Database Backups','manage_options','admin.php?page=wp-essentials#wpe_database_backups');
				add_submenu_page('wp-essentials','Debug Mode','Debug Mode','manage_options','admin.php?page=wp-essentials#wpe_debug_mode');
				add_submenu_page('wp-essentials','Error Reporting','Error Reporting','manage_options','admin.php?page=wp-essentials#wpe_error_reporting');
				add_submenu_page('wp-essentials','Footer Link','Footer Link','manage_options','admin.php?page=wp-essentials#wpe_footer_link');
				add_submenu_page('wp-essentials','Custom Image Sizes','Custom Image Sizes','manage_options','admin.php?page=wp-essentials#wpe_image_sizes');
				add_submenu_page('wp-essentials','Image Quality','Image Quality','manage_options','admin.php?page=wp-essentials#wpe_image_quality');
				add_submenu_page('wp-essentials','Login Notification','Login Notification','manage_options','admin.php?page=wp-essentials#wpe_login_notification');
				add_submenu_page('wp-essentials','Holding Page','Holding Page','manage_options','admin.php?page=wp-essentials#wpe_holding_page');
				
			// Plugins
				add_submenu_page('wp-essentials','Google Analytics','Google Analytics','manage_options','admin.php?page=wp-essentials#wpe_google_analytics');
				add_submenu_page('wp-essentials','Facebook Like Box','Facebook Like Box','manage_options','admin.php?page=wp-essentials#wpe_facebook_likebox');
				add_submenu_page('wp-essentials','Flickr Feed','Flickr Feed','manage_options','admin.php?page=wp-essentials#wpe_flickr_feed');
				add_submenu_page('wp-essentials','Google Maps','Google Maps','manage_options','admin.php?page=wp-essentials#wpe_google_maps');
				add_submenu_page('wp-essentials','Instagram Feed','Instagram Feed','manage_options','admin.php?page=wp-essentials#wpe_instagram_feed');
				add_submenu_page('wp-essentials','Twitter Feed','Twitter Feed','manage_options','admin.php?page=wp-essentials#wpe_twitter_feed');
				add_submenu_page('wp-essentials','Styling','Styling','manage_options','admin.php?page=wp-essentials#wpe_styling');
				add_submenu_page('wp-essentials','Responsive','Responsive','manage_options','admin.php?page=wp-essentials#wpe_responsive');
				
			// Shortcodes
				add_submenu_page('wp-essentials','Email Shortcode','Email Shortcode','manage_options','admin.php?page=wp-essentials#wpe_email');
				add_submenu_page('wp-essentials','Date Shortcode','Date Shortcode','manage_options','admin.php?page=wp-essentials#wpe_date');
				add_submenu_page('wp-essentials','Video Shortcode','Video Shortcode','manage_options','admin.php?page=wp-essentials#wpe_video');
				add_submenu_page('wp-essentials','Get Shortcode','Get Shortcode','manage_options','admin.php?page=wp-essentials#wpe_get');
				
			// PHP Functions
				add_submenu_page('wp-essentials','Custom Excerpt','Custom Excerpt','manage_options','admin.php?page=wp-essentials#wpe_custom_excerpt');
				add_submenu_page('wp-essentials','Get Image Source','Get Image Source','manage_options','admin.php?page=wp-essentials#wpe_get_image_source');
				add_submenu_page('wp-essentials','Link It','Link It','Cleanup','manage_options','admin.php?page=wp-essentials#wpe_link_it');
				add_submenu_page('wp-essentials','Relative Time','Relative Time','manage_options','admin.php?page=wp-essentials#wpe_relative_time');
		}
		
		function wpe_function() {
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			include("settings.php");
		}
		add_action('admin_menu', 'wpe_menu');