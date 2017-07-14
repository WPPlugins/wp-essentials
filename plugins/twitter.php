<?php
	// Database Set up
		if (!get_option('wpe_twitter_stream')) {
			update_option('wpe_twitter_stream',1);
			update_option('wpe_twitter_db',0);
		}
		
		if (get_option('wpe_twitter_accounts') > 0 && get_option('wpe_twitter_db') == 0) {
			global $wpdb;
			
			$table_name = $wpdb->prefix."wpe_twitter";
			
			$wpdb->query('DROP TABLE '.$table_name);
			
			$sql = "CREATE TABLE ".$table_name." (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				type VARCHAR(255) DEFAULT '' NOT NULL,
				username VARCHAR(255) DEFAULT '' NOT NULL,
				permalink VARCHAR(255) DEFAULT '' NOT NULL,
				content VARCHAR(255) DEFAULT '' NULL,
				media VARCHAR(255) DEFAULT '' NULL,
				keyword VARCHAR(255) DEFAULT '' NULL,
				posted VARCHAR(255) DEFAULT '' NULL,
				added VARCHAR(255) DEFAULT '' NOT NULL,
				UNIQUE KEY id (id)
			);";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			update_option('wpe_twitter_db',1);
		}
		
		if (get_option('wpe_twitter_username')) {
			$twitter_account = get_option('wpe_twitter_username','').';'.get_option('wpe_twitter_consumer_key','').';'.get_option('wpe_twitter_consumer_secret','').';'.get_option('wpe_twitter_oauth_access_token','').';'.get_option('wpe_twitter_oauth_access_token_secret','');
			
			delete_option('wpe_twitter_username');
			delete_option('wpe_twitter_oauth_access_token');
			delete_option('wpe_twitter_oauth_access_token_secret');
			delete_option('wpe_twitter_consumer_key');
			delete_option('wpe_twitter_consumer_secret');
			
			add_option('wpe_twitter_1',$twitter_account);
			add_option('wpe_twitter_accounts',1);
			
			global $wpdb;
			$table_name = $wpdb->prefix."wpe_twitter";
			$wpdb->query('ALTER TABLE '.$table_name.' ADD username VARCHAR(255) NOT NULL AFTER id'); 
		}
		
	// Shortcode Setup
		// Twitter
			if (!function_exists('twitter')) {
				function twitter($atts) {
					global $wpdb;
					
					$table_name = $wpdb->prefix."wpe_twitter";
					
					extract(
						shortcode_atts(
							array(
								'count' => 3,
								'order' => null,
								'class' => 'wpe_twitter',
							),
							$atts
						 )
					);
					
					$accounts = get_option('wpe_twitter_1');
					$account = explode(';',$accounts);
					$username = $account[0];
					
					// Cache Check
						$check = $wpdb->get_row('SELECT * FROM '.$table_name.' WHERE username = "'.$username.'" LIMIT 1');
						$now = strtotime('-15 minutes');
						$last_update = strtotime($check->added);
						if ($now > $last_update) {						
							// Make Requests
								$settings = array(
									'consumer_key' => $account[1],
									'consumer_secret' => $account[2],
									'oauth_access_token' => $account[3],
									'oauth_access_token_secret' => $account[4],
								);
								$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
								$getfield = '?screen_name='.$account[0].'&count=200';
								$requestMethod = 'GET';
								
								$twitter = new TwitterAPIExchange($settings);
								$response = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
								$data = json_decode($response);
								$total=count($data);
								$errors = $data->errors[0];
								if ($errors) {
									echo '<p>Twitter Error: '.$errors->message.'</p>';
								} else if ($total>0) {
									$wpdb->query('DELETE FROM '.$table_name.' WHERE username = "'.$username.'"');
									
									for($i=0;$i<$total;$i++){
										$username=$account[0];
										$status=$data[$i]->id_str;
										$content=str_replace("…","&hellip;",$data[$i]->text);
										$media=str_replace(array('http:','https:'),"",$data[$i]->entities->media[0]->media_url);
										$posted=strtotime($data[$i]->created_at);
										
										$sql = $wpdb->prepare('INSERT INTO '.$table_name.' VALUES ("","twitter",%s,%s,%s,%s,%s,%s,NOW())',$username,$status,$content,$media,'',$posted);
										$wpdb->query($sql);
									}
								}
						}
					
					// Get Tweets
					if ($order=="random") {
						$twitter = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE username = '.$username.' ORDER BY RAND() LIMIT '.$count);
					} else {
						$twitter = $wpdb->get_results('SELECT * FROM '.$table_name.' WHERE username = "'.$username.'" ORDER BY posted DESC LIMIT '.$count);
					}
					
					$tweets = '<ul class="'.$class.'">';
					foreach($twitter as $tweet) {
						$wpe_twitter_media = ($tweet->media ? '<span class="wpe_twitter_media"><img src="'.$tweet->media.'" alt="Twitter attachment"></span>' : '');
						$tweets .= '<li><span class="wpe_twitter_author"><a href="http://twitter.com/'.$username.'" target="_blank" rel="nofollow" title="'.$username.'">'.$username.'</a></span> '.$wpe_twitter_media.' '.link_tweet($tweet->content).'<p><span class="wpe_twitter_interact"><a href="https://twitter.com/intent/tweet?in_reply_to='.$tweet->permalink.'"><span class="wpe-redo"></span></a> <a href="https://twitter.com/intent/retweet?tweet_id='.$tweet->permalink.'"><span class="wpe-loop"></span></a> <a href="https://twitter.com/intent/favorite?tweet_id='.$tweet->permalink.'"><span class="wpe-star"></span></a></span> <span class="wpe_twitter_date"><a href="http://twitter.com/'.$username.'/status/'.$tweet->permalink.'" target="_blank" rel="nofollow" title="'.date('D, j M Y h:i:s T',$tweet->posted).'">'.relative_time($tweet->posted).'</a></span></p></li>';
					}
					$tweets .= '</ul>
					';
					return $tweets;
				}
				add_shortcode('twitter','twitter');
			}
			
	// Widget Set up
		if (get_option('wpe_twitter_accounts') > 0) {
			class Twitter extends WP_Widget {
				function __construct() {
					parent::WP_Widget('twitter', 'Twitter', array( 'description' => 'Add a Twitter feed to your page.' ) );
				}
				
				function widget($args,$instance) {
					extract($args);
					$title = apply_filters('title',$instance['title']);
					$count = apply_filters('count',$instance['count']);
						if ($count) { $args = 'count="'.$count.'"'; }
					$order = apply_filters('order',$instance['order']);
						if ($order) { $args .= ' order="random"'; }
					$class = apply_filters('class',$instance['class']);
						if ($class) { $args .= ' class="'.$class.'"'; }
					echo $before_widget;
					if ($title) { echo '<h3 class="widget-title">'.$title.'</h3>'; }
					echo do_shortcode('[twitter '.$args.']');
					echo $after_widget;
				}
				
				function update($new_instance,$old_instance) {
					$instance = $old_instance;
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['count'] = strip_tags($new_instance['count']);
					$instance['order'] = strip_tags($new_instance['order']);
					$instance['class'] = strip_tags($new_instance['class']);
					$instance['username'] = strip_tags($new_instance['username']);
					return $instance;
				}
				
				function form($instance) {
					if ($instance) {
						$title = esc_attr($instance['title']);
						$count = esc_attr($instance['count']);
						$order = esc_attr($instance['order']);
						$class = esc_attr($instance['class']);
						$username = esc_attr($instance['username']);
					}
					?>
					<p>
						<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Sidebar Title'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username'); ?></label> 
						<select class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>">
							<?php
								for($wpe_t=1;$wpe_t<=1;$wpe_t++) {
									$account = get_option('wpe_twitter_'.$wpe_t);
									$accounts = explode(';',$account);
									$selected = ($username == $accounts[0] ? $selected = 'selected="selected"' : $selected = '');
									echo '<option value="'.$accounts[0].'" '.$selected.'>'.$accounts[0].'</option>';
								}
							?>
						</select>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of Tweets'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Random'); ?></label> 
						<input id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="checkbox" value="1" <?php if ($order) { echo 'checked="checked"'; } ?>>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('class'); ?>"><?php _e('Class name (Optional)'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('class'); ?>" name="<?php echo $this->get_field_name('class'); ?>" type="text" value="<?php echo $class; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('search'); ?>"><?php _e('Hash tag filter (Comma separated for multiple hashtags)'); ?></label> 
						<input class="widefat" id="" name="" type="text" value="WP Essentials Premium Only" disabled="disabled">
					</p>
					<?php 
				}
			}
			register_widget('Twitter');
		}
		
	// Functions
		if (!function_exists('link_tweet')) {
			function link_tweet($tweet) {
				$tweet = preg_replace('/(^|\s)@(\w+)/','\1<a href="http://www.twitter.com/\2" target="_blank" rel="nofollow">@\2</a>',$tweet);
				$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t<]*)#ise", "'\\1<a href=\"\\2\" rel=\"nofollow\" target=\"_blank\">\\2</a>'", $tweet);
				$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://\\2\" rel=\"nofollow\" target=\"_blank\">\\2</a>'", $tweet);
				$tweet = preg_replace('/(^|\s)#(\w+)/','\1<a href="https://twitter.com/search?q=%23\2&mode=realtime" rel="nofollow" target="_blank">#\2</a>',$tweet);
				$tweet = str_replace(" & "," &amp; ",$tweet);
				return $tweet;
			}
		}
		
	// Twitter Class
		class TwitterAPIExchange {
			private $oauth_access_token;
			private $oauth_access_token_secret;
			private $consumer_key;
			private $consumer_secret;
			private $postfields;
			private $getfield;
			protected $oauth;
			public $url;
		
			public function __construct(array $settings)
			{
				if (!in_array('curl', get_loaded_extensions())) 
				{
					throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
				}
				
				if (!isset($settings['oauth_access_token'])
					|| !isset($settings['oauth_access_token_secret'])
					|| !isset($settings['consumer_key'])
					|| !isset($settings['consumer_secret']))
				{
					throw new Exception('Make sure you are passing in the correct parameters');
				}
		
				$this->oauth_access_token = $settings['oauth_access_token'];
				$this->oauth_access_token_secret = $settings['oauth_access_token_secret'];
				$this->consumer_key = $settings['consumer_key'];
				$this->consumer_secret = $settings['consumer_secret'];
			}
		
			public function setPostfields(array $array)
			{
				if (!is_null($this->getGetfield())) 
				{ 
					throw new Exception('You can only choose get OR post fields.'); 
				}
				
				if (isset($array['status']) && substr($array['status'], 0, 1) === '@')
				{
					$array['status'] = sprintf("\0%s", $array['status']);
				}
				
				$this->postfields = $array;
				
				return $this;
			}
		
			public function setGetfield($string)
			{
				if (!is_null($this->getPostfields())) 
				{ 
					throw new Exception('You can only choose get OR post fields.'); 
				}
				
				$search = array('#', ',', '+', ':');
				$replace = array('%23', '%2C', '%2B', '%3A');
				$string = str_replace($search, $replace, $string);  
				
				$this->getfield = $string;
				
				return $this;
			}
		
			public function getGetfield()
			{
				return $this->getfield;
			}
		
			public function getPostfields()
			{
				return $this->postfields;
			}
		
			public function buildOauth($url, $requestMethod)
		
			{
				if (!in_array(strtolower($requestMethod), array('post', 'get')))
				{
					throw new Exception('Request method must be either POST or GET');
				}
				
				$consumer_key = $this->consumer_key;
				$consumer_secret = $this->consumer_secret;
				$oauth_access_token = $this->oauth_access_token;
				$oauth_access_token_secret = $this->oauth_access_token_secret;
				
				$oauth = array( 
					'oauth_consumer_key' => $consumer_key,
					'oauth_nonce' => time(),
					'oauth_signature_method' => 'HMAC-SHA1',
					'oauth_token' => $oauth_access_token,
					'oauth_timestamp' => time(),
					'oauth_version' => '1.0'
				);
				
				$getfield = $this->getGetfield();
				
				if (!is_null($getfield))
				{
					$getfields = str_replace('?', '', explode('&', $getfield));
					foreach ($getfields as $g)
					{
						$split = explode('=', $g);
						$oauth[$split[0]] = $split[1];
					}
				}
				
				$base_info = $this->buildBaseString($url, $requestMethod, $oauth);
				$composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
				$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
				$oauth['oauth_signature'] = $oauth_signature;
				
				$this->url = $url;
				$this->oauth = $oauth;
				
				return $this;
			}
		
			public function performRequest($return = true)
			{
				if (!is_bool($return)) 
				{ 
					throw new Exception('performRequest parameter must be true or false'); 
				}
				
				$header = array($this->buildAuthorizationHeader($this->oauth), 'Expect:');
				
				$getfield = $this->getGetfield();
				$postfields = $this->getPostfields();
		
				$options = array( 
					CURLOPT_HTTPHEADER => $header,
					CURLOPT_HEADER => false,
					CURLOPT_URL => $this->url,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false
				);
		
				if (!is_null($postfields))
				{
					$options[CURLOPT_POSTFIELDS] = $postfields;
				}
				else
				{
					if ($getfield !== '')
					{
						$options[CURLOPT_URL] .= $getfield;
					}
				}
		
				$feed = curl_init();
				curl_setopt_array($feed, $options);
				$json = curl_exec($feed);
				curl_close($feed);
		
				if ($return) { return $json; }
			}
		
			private function buildBaseString($baseURI, $method, $params) 
			{
				$return = array();
				ksort($params);
				
				foreach($params as $key=>$value)
				{
					$return[] = "$key=" . $value;
				}
				
				return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $return)); 
			}
			
			private function buildAuthorizationHeader($oauth) 
			{
				$return = 'Authorization: OAuth ';
				$values = array();
				
				foreach($oauth as $key => $value)
				{
					$values[] = "$key=\"" . rawurlencode($value) . "\"";
				}
				
				$return .= implode(', ', $values);
				return $return;
			}
		}