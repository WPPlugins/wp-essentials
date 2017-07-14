<?php
	if (!function_exists('wpe_excerpt')) {
		function wpe_excerpt($words = 40, $end = '&hellip;', $link = true, $id = false, $echo = true) {
			if (!$id) { global $post; } else { $post = get_post($id); }
			$text = preg_replace("/(<h[1-6]>.*?<\/h[1-6]>|\[.*\])/m","",$post->post_content);
			$text = preg_replace("/\n/m","",$post->post_content);
			$text = preg_replace("/<img[^>]+\>/i","",$text);
			$text = preg_replace('/\[.*\]/', '', strip_tags($text));
			$text = str_replace(array("\r\n", "\r"), " ", $text);
			
			$text = explode(' ', $text);
		
			foreach($text as $key => $string){
				if ($string == "") {
					unset($text[$key]);	
				}
			}
			$text = array_merge(array(),$text);
		
			$tot = count($text);
			
			for ( $i=0; $i<$words; $i++ ) {
				if ($i==($words-1)) {
					$output .= $text[$i];
				} else {
					$output .= $text[$i] . ' ';
				}
			}
			
			$excerpt = force_balance_tags($output);
			if ($i<$tot) {
				if ($link==true) {
					$excerpt .= '<a href="'.get_permalink($post->ID).'">'.$end.'</a>';
				} else {
					$excerpt .= ''.$end;
				}
			}
			
			if ($echo) {
				echo $excerpt;	
			} else {
				return $excerpt;
			}
		}
	}
	
	if (!function_exists('excerpt')) {
		function excerpt($words = 40, $end = '&hellip;', $link = true, $id = false, $echo = true) {
			if (!$id) { global $post; } else { $post = get_post($id); }
			$text = preg_replace("/(<h[1-6]>.*?<\/h[1-6]>|\[.*\])/m","",$post->post_content);
			$text = preg_replace("/\n/m","",$post->post_content);
			$text = preg_replace("/<img[^>]+\>/i","",$text);
			$text = preg_replace('/\[.*\]/', '', strip_tags($text));
			$text = str_replace(array("\r\n", "\r"), " ", $text);
			
			$text = explode(' ', $text);
		
			foreach($text as $key => $string){
				if ($string == "") {
					unset($text[$key]);	
				}
			}
			$text = array_merge(array(),$text);
		
			$tot = count($text);
			
			for ( $i=0; $i<$words; $i++ ) {
				if ($i==($words-1)) {
					$output .= $text[$i];
				} else {
					$output .= $text[$i] . ' ';
				}
			}
			
			$excerpt = force_balance_tags($output);
			if ($i<$tot) {
				if ($link==true) {
					$excerpt .= '<a href="'.get_permalink($post->ID).'">'.$end.'</a>';
				} else {
					$excerpt .= ''.$end;
				}
			}
			
			if ($echo) {
				echo $excerpt;	
			} else {
				return $excerpt;
			}
		}
	}