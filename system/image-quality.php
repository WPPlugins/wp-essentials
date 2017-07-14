<?php
	add_filter('jpeg_quality',create_function('','return '.get_option('wpe_image_quality').';'));