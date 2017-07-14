<?php
	global $license;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"https://www.wp-essentials.net/wp-essentials-premium/validate.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array('domain'=>get_site_url(),'key'=>get_option('wpe_license_key'))));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$license = curl_exec($ch);
	curl_close($ch);