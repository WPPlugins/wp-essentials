<?php
	// Shortcode Setup
		if (!function_exists('show_date')) {
			function show_date($atts) {
				extract(
					shortcode_atts(
						array(
							'format' => 'd/m/Y'
						),
						$atts
					 )
				);
				return date($format);
			}
			add_shortcode('wpe_date','show_date');
		}
			
	// Widget Setup
		if (!function_exists('show_date')) {
			class Date extends WP_Widget {
				function __construct() {
					parent::WP_Widget('date', 'Date', array( 'description' => 'Display today&rsquo;s date in your sidebar.' ) );
				}
				
				function widget($args,$instance) {
					extract($args);
					$format = apply_filters('format',$instance['format']);
					echo $before_widget;
					echo do_shortcode('[wpe_date format="'.$format.'"]');
				}
				
				function update($new_instance,$old_instance) {
					$instance = $old_instance;
					$instance['format'] = strip_tags($new_instance['format']);
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
						<label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Date Format (<a href="http://php.net/manual/en/function.date.php" target="_blank">Reference</a>)'); ?></label> 
						<input class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" type="text" value="<?php if ($format) { echo $format; } else { echo 'd/m/Y'; } ?>">
					</p>
					<?php
				}
			}
			register_widget('Date');
		}