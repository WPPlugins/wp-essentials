<?php
	function login_notification($user_login, $user) {
		
		add_filter('wp_mail_content_type','set_html_content_type');
		function set_html_content_type(){ return 'text/html'; }
		
		$message = '<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" bgcolor="#EAF2FA" style="margin:0px;padding:0px;background:#EAF2FA;width:100%;height:100%">';
		$message .= '<tr>';
		$message .= '<td align="center" valign="top"><p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">&nbsp;</p></td>';
		$message .= '</tr>';
		$message .= '<tr>';
		$message .= '<td align="center" valign="top">';
		$message .= '<table cellpadding="0" cellspacing="0" border="0" width="500px" style="margin:0px;padding:0px;border:1px solid #C7D7E2;background:#fff;width:500px;text-align:left;">';
		$message .= '<tr>';
		$message .= '<td width="20px" style="width:20px;text-align:left;"><p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">&nbsp;</p></td>';
		$message .= '<td width="460px" style="width:460px;text-align:left;">';
		$message .= '<p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">&nbsp;</p>';
		$message .= '<h1 style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:18px;color:#000;">WordPress Login Notification</h1>';
		$message .= '<p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">&nbsp;</p>';
		$message .= '<p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">';
		$message .= 'This is an automated email to let you know that your user account <strong>'.$user->data->user_login.'</strong> has just logged in to <a href="'.get_site_url().'" style="color:#21759B;text-decoration:none;"><span style="color:#21759B;text-decoration:none;">'.get_site_url().'</span></a> from the IP address: <a href="http://ip-adress.com/ip_tracer/'.wpe_get_ip().'" style="color:#21759B;text-decoration:none;"><span style="color:#21759B;text-decoration:none;">'.wpe_get_ip().'</span></a>';
		$message .= '<br><br>';
		$message .= 'If this isn&rsquo;t you or someone you know, please change your password and reset your wp-config.php unique keys.';
		$message .= '<br><br>';
		$message .= 'Thank you.';
		$message .= '</p>';
		$message .= '<p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">&nbsp;</p>';
		$message .= '<p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:11px;color:#000;text-align:right;"><a href="http://www.wp-essentials.net" style="color:#21759B;text-decoration:none;"><span style="color:#21759B;text-decoration:none;">Powered by WP Essentials</span></a></p>';
		$message .= '<p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">&nbsp;</p>';
		$message .= '</td>';
		$message .= '<td width="20px" style="width:20px;text-align:left;"><p style="margin:0px;padding:0px;font-family:arial,sans-serif;font-size:12px;color:#000;">&nbsp;</p></td>';
		$message .= '</tr>';
		$message .= '</table>';
		$message .= '</td>';
		$message .= '</tr>';
		$message .= '</table>';
		
		wp_mail($user->data->user_email,'WordPress Login Notification',$message);
		
		remove_filter('wp_mail_content_type','set_html_content_type');
	}
	
	function wpe_get_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	add_action('wp_login', 'login_notification', 10, 2);