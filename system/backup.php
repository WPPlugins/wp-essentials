<?php
	// Automatic weekly backup
		if (!function_exists('auto_backup') && $license==1) {
			add_action('weekly_backup', 'weekly_hook');
			function auto_backup() {
				if ( !wp_next_scheduled('weekly_backup')) {
					wp_schedule_event(time(),'weekly','weekly_backup');
				}
			}
			add_action('wp','auto_backup');
			function weekly_hook() {
				include(ESSENTIALS_DIR."/backups/auto.php");
			}
			add_filter('cron_schedules','filter_cron_schedules');
			function filter_cron_schedules($param) {
				return array('weekly'=>array(
					'interval' => 604800,
					'display'  => __('Weekly')
				));
			}
		}
		
		
	// Manual Backup
		add_action('wp_ajax_wpe_database_backup', 'wpe_database_backup');
		add_action('wp_ajax_nopriv_wpe_database_backup', 'wpe_database_backup');
	
		function wpe_database_backup() {
			global $wpdb;
			include(ESSENTIALS_DIR.'/backups/index.php');
			die();
		}
		
		add_action('admin_footer', 'wpe_database_javascript');
		function wpe_database_javascript() { ?>
			<script type="text/javascript" >
				jQuery(document).ready(function() {
					jQuery("#email_backup").on("click",function(e){
						e.preventDefault();
						jQuery(this).hide().after('<img src="'+path_url+'/images/loading.gif" id="backup_loading">');
						var data = {
							action: 'wpe_database_backup',
							email: jQuery("#database_backup").val()
						};
						
						jQuery.post(ajaxurl,data,function(response) {
							jQuery("#backup_loading").hide();
							jQuery("#email_message").fadeIn();
							setTimeout(function(){
								jQuery("#email_message").hide();
								jQuery("#email_backup").fadeIn();
							},5000);
						});
					}).after('<span id="email_message">Your backup has been emailed.</span>');
					jQuery("#email_message").hide();
				});
			</script>
		<?php }