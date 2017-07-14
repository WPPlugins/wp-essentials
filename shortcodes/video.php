<?php
	if (!function_exists('render_video')) {
		function render_video($video,$width,$height) {
			parse_str(parse_url($video,PHP_URL_QUERY),$embed_code);
			if (strpos($video,'youtube') !== false) {
				return '<iframe width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed/'.$embed_code['v'].'?rel=0&amp;wmode=transparent" allowfullscreen></iframe>';
			} else if (strpos($video,'facebook') !== false) {
				if (!function_exists('add_fb_script')) {
					function add_fb_script() {
						echo '<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\'));</script>';
					}
					add_action('wp_footer', 'add_fb_script');
				}
				return '<div class="fb-post" data-href="'.$video.'" data-width="'.$width.'"></div>';
			} else if (strpos($video,'vimeo') !== false) {
				sscanf(parse_url($video, PHP_URL_PATH), '/%d', $embed_code);
				return '<iframe src="//player.vimeo.com/video/'.$embed_code.'?title=1&amp;byline=1&amp;portrait=1&amp;wmode=transparent" width="'.$width.'" height="'.$height.'" allowFullScreen></iframe>';
			}
		}
	}
	// Shortcode Setup
		if (!function_exists('video_buttons')) {
			function video_buttons($buttons) {
				$key = array_search('link',$buttons);
				if (false === $key) {
					$buttons[] = 'video';
				} else {
					$before = array_slice($buttons,0,$key);
					$after = array_slice($buttons,$key);
					$buttons = array_merge($before,array('video'),$after);
				}
				return $buttons;
			}
		}
	
		if (!function_exists('video_external_plugins')) {
			function video_external_plugins( $plugins ) {
				$plugins['video'] = ESSENTIALS_PATH."/scripts/video-button.js";
				return $plugins;
			}
			add_filter('mce_buttons','video_buttons');
			add_filter('mce_external_plugins','video_external_plugins');
		}
	
	if (!function_exists('embed_video')) {
		function embed_video($atts,$content) {
			extract(
				shortcode_atts(
					array(
						'width' => '480',
						'height' => '360'
					),
					$atts
				 )
			);
			return render_video($content,$width,$height);
		}
		add_shortcode('wpe_video','embed_video');
	}
			
	// Widget Set up
		if (!function_exists('render_video')) {		
			class Video extends WP_Widget {
				function __construct() {
					parent::WP_Widget('video','Video',array('description'=>'Embed a video to your page.'));
				}
				
				function widget($args,$instance) {
					extract($args);
					$url = apply_filters('url',$instance['url']);
					$width = apply_filters('width',$instance['width']);
						if ($width) { $args .= ' width="'.$width.'"'; }
					$height = apply_filters('height',$instance['height']);
						if ($height) { $args .= ' height="'.$height.'"'; }
					echo $before_widget;
					echo do_shortcode('[wpe_video '.$args.']'.$url.'[/wpe_video]');
				}
				
				function update($new_instance,$old_instance) {
					$instance = $old_instance;
					$instance['url'] = strip_tags($new_instance['url']);
					$instance['width'] = strip_tags($new_instance['width']);
					$instance['height'] = strip_tags($new_instance['height']);
					return $instance;
				}
				
				function form($instance) {
					if ($instance) {
						$url = esc_attr($instance['url']);
						$width = esc_attr($instance['width']);
						$height = esc_attr($instance['height']);
					}
					?>
					<p>
						<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('YouTube or Vimeo URL'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php if ($width) { echo $width; } else { echo '480'; } ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php if ($height) { echo $height; } else { echo '360'; } ?>">
					</p>
					<?php 
				}
			}
			register_widget('Video');
		}