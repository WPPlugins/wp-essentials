<?php	
	if (get_option('wpe_google_analytics')) {
		function google_analytics() {
			$analytics = get_option('wpe_google_analytics');
			$tracking = get_option('wpe_google_link_tracking');
			$codes = explode(",",$analytics);
			$i = 0;
			
			foreach($codes as $code) {
				if ($i==0) {
					$trackers .= "ga('create', '".$code."', 'auto');
					ga('send', 'pageview');";
				} else {
					$trackers .= "ga('create', '".$code."', 'auto', {'name': 'tracker_".$i."'});
					ga('tracker_".$i.".send', 'pageview');";
				}
				$i++;
			}
			
			echo "
				<!-- WP Essentials GA Script -->
				<script>
					(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
					(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
					m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
					})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
					".$trackers."
				</script>
			";
			
			if ($tracking == 1) {
				echo "
					<!-- WP Essentials Link Tracking Script -->
					<script>var trackOutboundLink=function(url){ga('send','event','outbound','click',url,{'hitCallback':function(){document.location=url;}});}</script> 
				";	
			}
		}
		add_action('wp_head', 'google_analytics');
	}