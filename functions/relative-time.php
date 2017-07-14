<?php
	if (!function_exists('wpe_relative_time')) {
		function wpe_relative_time($time, $now = false) {
			$time = (int) $time;
			$curr = $now ? $now : time();
			$shift = $curr - $time;
		
			if ($shift < 45):
				$diff = $shift;
				$term = "second";
			elseif ($shift < 2700):
				$diff = round($shift/60);
				$term = "minute";
			elseif ($shift < 64800):
				$diff = round($shift/60/60);
				$term = "hour";
			else:
				$diff = round($shift/60/60/24);
				$term = "day";
			endif;
		
			if ($diff != 1) $term .= "s";
			return $diff." ".$term." ago";
		}
	}
	
	if (!function_exists('relative_time')) {
		function relative_time($time, $now = false) {
			$time = (int) $time;
			$curr = $now ? $now : time();
			$shift = $curr - $time;
		
			if ($shift < 45):
				$diff = $shift;
				$term = "second";
			elseif ($shift < 2700):
				$diff = round($shift/60);
				$term = "minute";
			elseif ($shift < 64800):
				$diff = round($shift/60/60);
				$term = "hour";
			else:
				$diff = round($shift/60/60/24);
				$term = "day";
			endif;
		
			if ($diff != 1) $term .= "s";
			return $diff." ".$term." ago";
		}
	}