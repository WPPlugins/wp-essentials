<?php
	if (!function_exists('add_image_sizes')) {
		function add_image_sizes($sizes) {
			global $_wp_additional_image_sizes;
			if (empty($_wp_additional_image_sizes))
				return $sizes;
			
			foreach ($_wp_additional_image_sizes as $id => $data) {
				if (!isset($sizes[$id]))
				$sizes[$id] = ucwords(str_replace('-', ' ',str_replace('_',' ',$id)));
			}
			
			return $sizes;
		}
		add_filter('image_size_names_choose','add_image_sizes');
	}
	
	if (get_option('wpe_custom_image_sizes')>0) {
		for($wpe_s=1;$wpe_s<=get_option('wpe_custom_image_sizes');$wpe_s++) {
			$wpe_size = get_option('wpe_image_size_'.$wpe_s);
			$wpe_sizes = explode(';',$wpe_size);
			if ($wpe_sizes[3]==99) { $wpe_crop = false; }
			else if ($wpe_sizes[3]==1) { $wpe_crop = array('left','top'); }
			else if ($wpe_sizes[3]==2) { $wpe_crop = array('center','top'); }
			else if ($wpe_sizes[3]==3) { $wpe_crop = array('right','top'); }
			else if ($wpe_sizes[3]==4) { $wpe_crop = array('left','center'); }
			else if ($wpe_sizes[3]==5) { $wpe_crop = array('center','center'); }
			else if ($wpe_sizes[3]==6) { $wpe_crop = array('right','center'); }
			else if ($wpe_sizes[3]==7) { $wpe_crop = array('left','bottom'); }
			else if ($wpe_sizes[3]==8) { $wpe_crop = array('center','bottom'); }
			else if ($wpe_sizes[3]==9) { $wpe_crop = array('right','bottom'); }
			else { $wpe_crop = array('center','center'); }
			
			add_image_size($wpe_sizes[0],$wpe_sizes[1],$wpe_sizes[2],$wpe_crop);
		}
	}