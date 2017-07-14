<?php
	// Database Set up
		if (!get_option('wpe_flickr_stream')) {
			update_option('wpe_flickr_stream',1);
			update_option('wpe_flickr_db',0);
		}
		
		if (get_option('wpe_flickr_username') && get_option('wpe_flickr_db') == 0) {
			global $wpdb;
			
			$table_name = $wpdb->prefix."wpe_flickr";
			
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
			update_option('wpe_flickr_db',1);
		}
		
	// Shortcode Setup
		// Flickr
			if (!function_exists('flickr')) {
				function flickr($atts) {
					global $wpdb;
					
					$table_name = $wpdb->prefix."wpe_flickr";
					
					extract(
						shortcode_atts(
							array(
								'count' => 3,
								'order' => null,
								'class' => 'wpe_flickr'
							),
							$atts
						 )
					);
					
					// Cache Check
					$check = $wpdb->get_row('SELECT * FROM '.$table_name.' LIMIT 1');
					$now = strtotime('-15 minutes');
					$last_update = strtotime($check->added);
					if ($now > $last_update) {
						wpe_getFlickrPhotos(get_option('wpe_flickr_api'),get_option('wpe_flickr_username'));
					}
					
					// Get Feed
					if ($order=="random") {
						$flickr = $wpdb->get_results('SELECT * FROM '.$table_name.' ORDER BY RAND() LIMIT '.$count);
					} else {
						$flickr = $wpdb->get_results('SELECT * FROM '.$table_name.' LIMIT '.$count);
					}
					
					$photos = '<ul class="'.$class.'">';
					foreach($flickr as $photo) {
						$photos .= '<li><a href="'.$photo->permalink.'" target="_blank" rel="nofollow"><img border="0" alt="'.$photo->content.'" title="'.$photo->content.'" src="'.$photo->media.'"></a></li>
						';
					}
					$photos .= '</ul>
					';
					return $photos;
				}
				add_shortcode('flickr','flickr');
			}
			
	// Widget Set up
		if (get_option('wpe_flickr_username')) {
			class Flickr extends WP_Widget {
				function __construct() {
					parent::WP_Widget('flickr', 'Flickr', array( 'description' => 'Add a Flickr feed to your page.' ) );
				}
				
				function widget($args,$instance) {
					extract($args);
					$title = apply_filters('count',$instance['title']);
					$count = apply_filters('count',$instance['count']);
						if ($count) { $args = 'count="'.$count.'"'; }
					$order = apply_filters('order',$instance['order']);
						if ($order) { $args .= ' order="random"'; }
					$class = apply_filters('class',$instance['class']);
						if ($class) { $args .= ' class="'.$class.'"'; }
					echo $before_widget;
					if ($title) { echo '<h3 class="widget-title">'.$title.'</h3>'; }
					echo do_shortcode('[flickr '.$args.']');
					echo $after_widget;
				}
				
				function update($new_instance,$old_instance) {
					$instance = $old_instance;
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['count'] = strip_tags($new_instance['count']);
					$instance['order'] = strip_tags($new_instance['order']);
					$instance['class'] = strip_tags($new_instance['class']);
					return $instance;
				}
				
				function form($instance) {
					if ($instance) {
						$title = esc_attr($instance['title']);
						$count = esc_attr($instance['count']);
						$order = esc_attr($instance['order']);
						$class = esc_attr($instance['class']);
					}
					?>
					<p>
						<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Sidebar Title'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of Images'); ?></label> 
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
					<?php 
				}
			
			}
			register_widget('Flickr');
		}
		
	// Cache set up
		add_action('wp_ajax_wpe_flickr_cache', 'wpe_flickr_cache');
		add_action('wp_ajax_nopriv_wpe_flickr_cache', 'wpe_flickr_cache');
	
		function wpe_flickr_cache() {
			wpe_getFlickrPhotos(get_option('wpe_flickr_api'),get_option('wpe_flickr_username'));
			die();
		}
		
		add_action('admin_footer', 'wpe_flickr_javascript');
		function wpe_flickr_javascript() { ?>
			<script type="text/javascript" >
				jQuery(document).ready(function() {
					jQuery("#wpe_cache_flickr").on("click",function(e){
						e.preventDefault();
						jQuery(this).hide().after('<img src="'+path_url+'/images/loading.gif" id="wpe_flickr_cache_loading">');
						var data = {
							action: 'wpe_flickr_cache'
						};
						
						jQuery.post(ajaxurl,data,function(response) {
							jQuery("#wpe_flickr_cache_loading").hide();
							jQuery("#wpe_flickr_cache_message").fadeIn();
							setTimeout(function(){
								jQuery("#wpe_flickr_cache_message").hide();
								jQuery("#wpe_cache_flickr").fadeIn();
							},5000);
						});
					}).after('<span id="wpe_flickr_cache_message">The flickr cache has been cleared.</span>');
					jQuery("#wpe_flickr_cache_message").hide();
				});
			</script>
		<?php }
		
	/* Functions */	
		function wpe_getFlickrPhotos($api,$username) {
			global $wpdb;
			$table_name = $wpdb->prefix."wpe_flickr";
			
			$get_user  = 'https://api.flickr.com/services/rest/?method=flickr.people.findByUsername';
			$get_user .= '&api_key='.$api;
			$get_user .= '&username='.$username;
			$get_user .= '&format=json';
			$get_user .= '&nojsoncallback=1';
			$response = json_decode(file_get_contents($get_user));
			$get_photos = 'https://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos';
			$get_photos .= '&api_key='.$api;
			$get_photos .= '&user_id='.$response->user->nsid;
			$get_photos .= '&per_page=500';
			$get_photos .= '&format=json';
			$get_photos .= '&nojsoncallback=1';
			$response = json_decode(file_get_contents($get_photos));
			$photos = $response->photos->photo;
			
			if ($photos) {
				$wpdb->query('TRUNCATE TABLE '.$table_name);
				foreach($photos as $photo) {
					$farm_id = $photo->farm;
					$server_id = $photo->server;
					$photo_id = $photo->id;
					$secret_id = $photo->secret;
					$title = $photo->title;
					$size = 'q';
					$photo_url = 'https://www.flickr.com/photos/'.$username.'/'.$photo_id;
					$photo_src = '//farm'.$farm_id.'.staticflickr.com/'.$server_id.'/'.$photo_id.'_'.$secret_id.'_'.$size.'.'.'jpg';

					$sql = $wpdb->prepare('INSERT INTO '.$table_name.' VALUES ("","flickr",%s,%s,%s,%s,"",%s,NOW())',get_option('wpe_flickr_username'),$photo_url,$title,$photo_src,$post->created_time);
					$wpdb->query($sql);
				}
			}
		}