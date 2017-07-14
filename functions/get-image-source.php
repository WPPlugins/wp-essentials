<?php
	if (!function_exists('wpe_get_image_source')) {
		function wpe_get_image_source($id,$size,$return = false) {
			$image = wp_get_attachment_image_src($id,$size);
			if ($return) {
				return $image[0];
			} else {
				echo $image[0];
			}
		}
	}
	
	if (!function_exists('get_image_source')) {
		function get_image_source($id,$size,$return = false) {
			$image = wp_get_attachment_image_src($id,$size);
			if ($return) {
				return $image[0];
			} else {
				echo $image[0];
			}
		}
	}