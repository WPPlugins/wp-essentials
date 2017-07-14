<?php
	// Shortcode Set up
		if (!function_exists('google_maps')) {
			function google_maps($atts) {
				extract(
					shortcode_atts(
						array(
							'address' => null,
							'zoom' => 14,
							'controls' => "true",
							'marker' => "true",
							'width' => '300px',
							'height' => '300px',
							'icon' => false
						),
						$atts
					 )
				);
				if (!function_exists('google_api')) {
					function google_api() {
						echo '<script src="//maps.google.com/maps/api/js?sensor=false"></script>';
						echo '<script src="'.ESSENTIALS_PATH.'/scripts/wpe-google-maps.js"></script>';
					}
					add_action('wp_footer', 'google_api');
				}
				
				$map = '<div id="google_map_'.rand(100,999).'" class="google_map" data-zoom="'.$zoom.'" data-controls="'.$controls.'" data-marker="'.$marker.'" data-icon="'.$icon.'" style="width:'.$width.';height:'.$height.';">'.$address.'</div>';
				return $map;
			}
			add_shortcode('google_maps','google_maps');
		}
			
	// Widget Set up
		if (function_exists('google_maps')) {
			class Google_Maps extends WP_Widget {
				function __construct() {
					parent::WP_Widget('google_maps', 'Google Map', array( 'description' => 'Add a Google map to your page.' ) );
				}
				
				function widget($args,$instance) {
					extract($args);
					$title = apply_filters('title',$instance['title']);
					$address = apply_filters('address',$instance['address']);
						if ($address) { $args = 'address="'.$address.'"'; }
					$zoom = apply_filters('zoom',$instance['zoom']);
						if ($zoom) { $args .= ' zoom="'.$zoom.'"'; }
					$controls = apply_filters('controls',$instance['controls']);
						if ($controls) { $args .= ' controls="false"'; }
					$marker = apply_filters('marker',$instance['marker']);
						if ($marker) { $args .= ' marker="false"'; }
					$width = apply_filters('width',$instance['width']);
						if ($width) { $args .= ' width="'.$width.'"'; }
					$height = apply_filters('height',$instance['height']);
						if ($height) { $args .= ' height="'.$height.'"'; }
					$icon = apply_filters('icon',$instance['icon']);
						if ($icon) { $args .= ' icon="'.$icon.'"'; }
					echo $before_widget;
					if ($title) { echo '<h3 class="widget-title">'.$title.'</h3>'; }
					echo do_shortcode('[google_maps '.$args.']');
					echo $after_widget;
				}
				
				function update($new_instance,$old_instance) {
					$instance = $old_instance;
					$instance['title'] = strip_tags($new_instance['title']);
					$instance['address'] = strip_tags($new_instance['address']);
					$instance['zoom'] = strip_tags($new_instance['zoom']);
					$instance['controls'] = strip_tags($new_instance['controls']);
					$instance['marker'] = strip_tags($new_instance['marker']);
					$instance['width'] = strip_tags($new_instance['width']);
					$instance['height'] = strip_tags($new_instance['height']);
					$instance['icon'] = strip_tags($new_instance['icon']);
					return $instance;
				}
				
				function form($instance) {
					if ($instance) {
						$title = esc_attr($instance['title']);
						$address = esc_attr($instance['address']);
						$zoom = esc_attr($instance['zoom']);
						$controls = esc_attr($instance['controls']);
						$marker = esc_attr($instance['marker']);
						$width = esc_attr($instance['width']);
						$height = esc_attr($instance['height']);
						$icon = esc_attr($instance['icon']);
					}
					?>
					<p>
						<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Sidebar Title'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo $address; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('zoom'); ?>"><?php _e('Zoom Level (0-21)'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>" type="text" value="<?php echo $zoom; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('controls'); ?>"><?php _e('Disable Controls'); ?></label> 
						<input id="<?php echo $this->get_field_id('controls'); ?>" name="<?php echo $this->get_field_name('controls'); ?>" type="checkbox" value="1" <?php if ($controls) { echo 'checked="checked"'; } ?>>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('icon'); ?>"><?php _e('Custom Marker URL'); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('icon'); ?>" name="<?php echo $this->get_field_name('icon'); ?>" type="text" value="<?php echo $icon; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('marker'); ?>"><?php _e('Disable Marker'); ?></label> 
						<input id="<?php echo $this->get_field_id('marker'); ?>" name="<?php echo $this->get_field_name('marker'); ?>" type="checkbox" value="1" <?php if ($marker) { echo 'checked="checked"'; } ?>>
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width (px or %)'); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>">
					</p>
					<p>
						<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height (px or %)'); ?></label>
						<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>">
					</p>
					<?php 
				}
			}
			register_widget('Google_Maps');
		}