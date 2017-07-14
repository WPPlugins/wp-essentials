<?php
	// Shortcode Setup
		if (!function_exists('mce_buttons')) {
			function mce_buttons($buttons) {
				$key = array_search('link',$buttons);
				if (false === $key) {
					$buttons[] = 'email';
				} else {
					$before = array_slice($buttons,0,$key);
					$after = array_slice($buttons,$key);
					$buttons = array_merge($before,array('email'),$after);
				}
				return $buttons;
			}
		
			function mce_external_plugins( $plugins ) {
				$plugins['email'] = ESSENTIALS_PATH."/scripts/email-button.js";
				return $plugins;
			}
			add_filter('mce_buttons','mce_buttons');
			add_filter('mce_external_plugins','mce_external_plugins');
		}