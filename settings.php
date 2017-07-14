<?php
	global $wpdb;
	global $license;
		
	// Update Settings
		if (!get_option('wpe_total_user_roles')) { update_option('wpe_total_user_roles',1); }
		if (isset($_POST['submitted'])) {
			update_option('wpe_cleanup',$_POST['cleanup']);
			update_option('wpe_lowercase_filenames',$_POST['lowercase_filenames']);
			delete_option('wpe_user_role_1');
			$edit_dashboard = (isset($_POST['1_edit_dashboard'])) ? 1 : 0;
			$edit_files = (isset($_POST['1_edit_files'])) ? 1 : 0;
			$edit_theme = (isset($_POST['1_edit_theme'])) ? 1 : 0;
			$manage_others_pages = (isset($_POST['1_manage_others_pages'])) ? 1 : 0;
			$manage_others_posts = (isset($_POST['1_manage_others_posts'])) ? 1 : 0;
			$manage_pages = (isset($_POST['1_manage_pages'])) ? 1 : 0;
			$manage_posts = (isset($_POST['1_manage_posts'])) ? 1 : 0;
			$manage_users = (isset($_POST['1_manage_users'])) ? 1 : 0;
			$manage_categories = (isset($_POST['1_manage_categories'])) ? 1 : 0;
			$manage_links = (isset($_POST['1_manage_links'])) ? 1 : 0;
			$manage_options = (isset($_POST['1_manage_options'])) ? 1 : 0;
			$manage_comments = (isset($_POST['1_manage_comments'])) ? 1 : 0;
			$manage_plugins = (isset($_POST['1_manage_plugins'])) ? 1 : 0;
			$update_core = (isset($_POST['1_update_core'])) ? 1 : 0;
			add_option('wpe_user_role_1',$_POST['1_user_role'].';'.$edit_dashboard.';'.$edit_files.';'.$edit_theme.';'.$manage_others_pages.';'.$manage_others_posts.';'.$manage_pages.';'.$manage_posts.';'.$manage_users.';'.$manage_categories.';'.$manage_links.';'.$manage_options.';'.$manage_comments.';'.$manage_plugins.';'.$update_core);
			update_option('wpe_total_user_roles',$_POST['wpe_total_user_roles']);
			include("system/user-roles.php");
			update_option('wpe_error_reports_google_analytics',$_POST['error_reports_google_analytics']);
			update_option('wpe_error_reports_search_engines',$_POST['error_reports_search_engines']);
			update_option('wpe_error_reports_check_username',$_POST['wpe_error_reports_check_username']);
			update_option('wpe_google_analytics',$_POST['google_analytics']);
			update_option('wpe_google_link_tracking',$_POST['google_link_tracking']);
			update_option('wpe_footer_link',$_POST['footer_link']);
			update_option('wpe_php_date',$_POST['php_date']);
			update_option('wpe_debug_mode',$_POST['debug_mode']);
			for($wpe_s=1;$wpe_s<=get_option('wpe_custom_image_sizes');$wpe_s++) {
				delete_option('wpe_image_size_'.$wpe_s);
			}
			for($wpe_s=1;$wpe_s<=$_POST['wpe_total_image_sizes'];$wpe_s++) {
				add_option(
					'wpe_image_size_'.$wpe_s,
					$_POST[$wpe_s.'_image_name'].';'.
					$_POST[$wpe_s.'_image_width'].';'.
					$_POST[$wpe_s.'_image_height'].';'.
					$_POST[$wpe_s.'_image_crop']
				);
			}
			update_option('wpe_custom_image_sizes',$_POST['wpe_total_image_sizes']);
			update_option('wpe_image_quality',$_POST['image_quality']);
			update_option('wpe_facebook',$_POST['facebook']);
			update_option('wpe_flickr_username',$_POST['flickr_username']);
			update_option('wpe_flickr_api',$_POST['flickr_api']);
			update_option('wpe_google_maps',$_POST['google_maps']);
			for($wpe_s=1;$wpe_s<=1;$wpe_s++) {
				delete_option('wpe_twitter_1');
			}
			for($wpe_s=1;$wpe_s<=1;$wpe_s++) {					
				add_option(
					'wpe_twitter_'.$wpe_s,
					$_POST[$wpe_s.'_twitter_username'].';'.
					$_POST[$wpe_s.'_consumer_key'].';'.
					$_POST[$wpe_s.'_consumer_secret'].';'.
					$_POST[$wpe_s.'_oauth_access_token'].';'.
					$_POST[$wpe_s.'_oauth_access_token_secret']
				);
			}
			update_option('wpe_responsive',$_POST['responsive']);
			update_option('wpe_email',$_POST['email']);
			update_option('wpe_video',$_POST['video']);
			update_option('wpe_get',$_POST['get']);
			update_option('wpe_excerpt',$_POST['excerpt']);
			update_option('wpe_get_image_source',$_POST['get_image_source']);
			update_option('wpe_link_it',$_POST['link_it']);
			update_option('wpe_relative_time',$_POST['relative_time']);
			
			echo '<div class="updated"><p>Settings saved. <a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=wp-essentials">Refresh the page to see your changes.</a></p></div>';
		}
?>
<div class="wrap">
	<h2 class="title"><span class="wpe-fire"></span> WP Essentials</h2>
	
	<div id="post-stuff">
	<form action="admin.php?page=wp-essentials" method="post" id="wpe-settings-form">
		<div id="post-body" class="metabox-holder wpe-settings-container columns-2 clearfix">
			<div id="wpe_left">
				<h2>System</h2>
				<div class="postbox" id="wpe_cleanup">
					<h3 class="hndle"><span><span class="wpe-bin"></span> Cleanup</span></h3>						
					<div class="inside">
						<p>The Cleanup function performs a few different tasks:</p>
						<ul>
							<li>Sets up a URL friendly permalink structure (if one hasn&rsquo;t already been set)</li>
							<li>Removes useless widgets from the Dashboard</li>
							<li>Removes superfluous meta tags from your theme head (including the WordPress version number)</li>
							<li>Removes detailed login errors</li>
						</ul>
						<p>
						<label for="cleanup"><input type="checkbox" name="cleanup" id="cleanup" value="1" <?php if (get_option('wpe_cleanup')==1) { ?>checked="checked"<?php } ?>> Enable Cleanup</label>
					</div>
				</div>
				<div class="postbox" id="wpe_lowercase_filenames">
					<h3 class="hndle"><span><span class="wpe-font-size"></span> Case-sensitive Filenames</span></h3>						
					<div class="inside">
						<p>Any new uploads will be forced to use lowercase filenames. This is to stop issues with <code>Photo.jpg</code> and <code>photo.jpg</code> being two different files, which can give you problems if you were to move from Linux to Windows.</p>
						<p>
						<label for="lowercase_filenames"><input type="checkbox" name="lowercase_filenames" id="lowercase_filenames" value="1" <?php if (get_option('wpe_lowercase_filenames')==1) { ?>checked="checked"<?php } ?>> Enable Case-sensitive Filenames</label>
					</div>
				</div>
				<div class="postbox" id="wpe_user_roles">
					<h3 class="hndle"><span><span class="wpe-users"></span> User Roles</span></h3>
					<div class="inside">
                    	<p>Use this table to control what your additional User Role can access.</p>
                        <p>Additional User Roles can only be added by upgrading to <a href="http://www.wp-essentials.net/">WP Essentials Premium</a>.</p>
						<table id="user_role_table" cellpadding="0" cellspacing="0">
							<thead>
                            	<tr>
                                    <th>Role</th>
                                    <th class="center">Edit Dashboard</th>
                                    <th class="center">Edit Files</th>
                                    <th class="center">Edit Theme</th>
                                    <th class="center">Manage Others Pages</th>
                                    <th class="center">Manage Others Posts</th>
                                    <th class="center">Manage Pages</th>
                                    <th class="center">Manage Posts</th>
                                    <th class="center">Manage Users</th>
                                    <th class="center">Manage Categories</th>
                                    <th class="center">Manage Links</th>
                                    <th class="center">Manage Options</th>
                                    <th class="center">Manage Comments</th>
                                    <th class="center">Manage Plugins</th>
                                    <th class="center">Update Core</th>
                                    <th class="center">&nbsp;</th>
                                </tr>
							</thead>
							<tbody>
                            	<?php
									$role = get_option('wpe_user_role_1');
									$roles = explode(';',$role);
								?>
                                <tr>
                                    <td><input type="text" class="medium-text" name="1_user_role" id="user_role" value="<?php echo $roles[0]; ?>"></td>
                                    <td class="center"><input type="checkbox" name="1_edit_dashboard" <?php if ($roles[1]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_edit_files" <?php if ($roles[2]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_edit_theme" <?php if ($roles[3]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_others_pages" <?php if ($roles[4]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_others_posts" <?php if ($roles[5]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_pages" <?php if ($roles[6]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_posts" <?php if ($roles[7]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_users" <?php if ($roles[8]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_categories" <?php if ($roles[9]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_links" <?php if ($roles[10]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_options" <?php if ($roles[11]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_comments" <?php if ($roles[12]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_manage_plugins" <?php if ($roles[13]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><input type="checkbox" name="1_update_core" <?php if ($roles[14]==1) { echo 'checked'; } ?>></td>
                                    <td class="center"><button class="button button-secondary delete_user_role" disabled="disabled">Delete</button></td>
                                </tr>
							</tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="16" class="center"><button id="add_user_role" class="button button-secondary" disabled="disabled">Add User Role</button></td>
                                </tr>
                            </tfoot>
						</table>
                        <input type="hidden" name="wpe_total_user_roles" id="wpe_total_user_roles" value="1">
					</div>
				</div>
				<div class="postbox pro_version" id="wpe_database_backups">
					<h3 class="hndle"><span><span class="wpe-download"></span> Database Backups</span></h3>
					<div class="inside">
						<p>Use the field below to email a full database backup.</p>
						<input type="text" class="regular-text" name="database_backup" id="database_backup" class="regular-text ltr" value="<?php if ($license==1) { echo get_option('admin_email'); } ?>"> <a href="#" id="email_backup">Email a full database backup.</a>
						<p>Weekly database backups can also be automatically emailed to the site administrator.</p>
						<label for="backup"><input type="checkbox" name="backup" id="backup" value="1" <?php if (get_option('wpe_backup')==1) { ?>checked="checked"<?php } ?>> Enable Weekly Backups</label>
					</div>
				</div>
				<div class="postbox" id="wpe_debug_mode">
					<h3 class="hndle"><span><span class="wpe-cogs"></span> Debug Mode</span></h3>
					<div class="inside">
						<p>Debug Mode turns on descriptive error reporting for logged in Admins only; the public will still see the site as normal.</p>
						<label for="debug_mode"><input type="checkbox" name="debug_mode" id="debug_mode" value="1" <?php if (get_option('wpe_debug_mode')==1) { ?>checked="checked"<?php } ?>> Enable Debug Mode</label>
					</div>
				</div>
				<div class="postbox" id="wpe_error_reporting">
					<h3 class="hndle"><span><span class="wpe-terminal"></span> WordPress Error Reporting</span></h3>
					<div class="inside">
						<p>WP Essentials can alert you to several errors that are important when a site goes live.</p>
						<label for="error_reports_google_analytics"><input type="checkbox" name="error_reports_google_analytics" id="error_reports_google_analytics" value="1" <?php if (get_option('wpe_error_reports_google_analytics')==1) { ?>checked="checked"<?php } ?>> Ensure Google Analytics is installed</label><br>
						<label for="error_reports_search_engines"><input type="checkbox" name="error_reports_search_engines" id="error_reports_search_engines" value="1" <?php if (get_option('wpe_error_reports_search_engines')==1) { ?>checked="checked"<?php } ?>> Ensure search engines aren&rsquo;t blocked</label><br>
						<label for="wpe_error_reports_check_username"><input type="checkbox" name="wpe_error_reports_check_username" id="wpe_error_reports_check_username" value="1" <?php if (get_option('wpe_error_reports_check_username')==1) { ?>checked="checked"<?php } ?>> Check for <code>admin</code> username</label>
						<h4 class="pro_version">Custom Error Messages <sup>Premium License Required</sup></h4>
						<p>This function supports custom error messages by adding the following code to your <code>functions.php</code> file:</p>
						<pre>
function wpe_error_reports() {
&nbsp;&nbsp;&nbsp;&nbsp;// Each error message should appear in an &lt;li>
&nbsp;&nbsp;&nbsp;&nbsp;$errors = '&lt;li>Your first error message.&lt;/li>';
&nbsp;&nbsp;&nbsp;&nbsp;$errors .= '&lt;li>Your second error message.&lt;/li>';
&nbsp;&nbsp;&nbsp;&nbsp;return $errors;
}</pre>
					</div>
				</div>
				<div class="postbox" id="wpe_footer_link">
					<h3 class="hndle"><span><span class="wpe-link"></span> WP Essentials Footer Link</span></h3>
					<div class="inside">
						<p>Please consider giving us credit for this free plugin.</p>
						<p>It&rsquo;s completely optional, and won&rsquo;t affect any support you receive.</p>
						<label for="footer_link"><input type="checkbox" name="footer_link" id="footer_link" value="1" <?php if (get_option('wpe_footer_link')==1) { ?>checked="checked"<?php } ?>> Enable Footer Link</label>
					</div>
				</div>
				<div class="postbox" id="wpe_image_sizes">
					<h3 class="hndle"><span><span><span class="wpe-image"></span> Custom Image Sizes</span></h3>
					<div class="inside">
                    	<p>Use this table to control your custom image sizes.</p>
						<p><em>Please note: changes won&rsquo;t take effect until you rebuild the thumbnails.</em></p>
						<table id="image_size_table" cellpadding="0" cellspacing="0">
							<thead>
                            	<tr>
                                    <th class="center">Name</th>
                                    <th class="center">Width (px)</th>
                                    <th class="center">Height (px)</th>
                                    <th class="center">Crop</th>
                                    <th class="center">&nbsp;</th>
                                </tr>
							</thead>
							<tbody>
                            	<?php
									for($wpe_s=1;$wpe_s<=get_option('wpe_custom_image_sizes');$wpe_s++) {
										$size = get_option('wpe_image_size_'.$wpe_s);
										$sizes = explode(';',$size);
								?>
                                    <tr>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_s; ?>_image_name" id="custom_image_sizes" value="<?php echo $sizes[0]; ?>"></td>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_s; ?>_image_width" value="<?php echo $sizes[1]; ?>"></td>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_s; ?>_image_height" value="<?php echo $sizes[2]; ?>"></td>
                                        <td class="center">
											<select name="<?php echo $wpe_s; ?>_image_crop">
												<option value="99" <?php if ($sizes[3]==99) { echo 'selected="selected"'; } ?>>No cropping</option>
												<option value="1" <?php if ($sizes[3]==1) { echo 'selected="selected"'; } ?>>Top / Left</option>
												<option value="2" <?php if ($sizes[3]==2) { echo 'selected="selected"'; } ?>>Top / Center</option>
												<option value="3" <?php if ($sizes[3]==3) { echo 'selected="selected"'; } ?>>Top / Right</option>
												
												<option value="4" <?php if ($sizes[3]==4) { echo 'selected="selected"'; } ?>>Center / Left</option>
												<option value="5" <?php if ($sizes[3]==5 || !$sizes[3]) { echo 'selected="selected"'; } ?>>Center / Center</option>
												<option value="6" <?php if ($sizes[3]==6) { echo 'selected="selected"'; } ?>>Center / Right</option>
												
												<option value="7" <?php if ($sizes[3]==7) { echo 'selected="selected"'; } ?>>Bottom / Left</option>
												<option value="8" <?php if ($sizes[3]==8) { echo 'selected="selected"'; } ?>>Bottom / Center</option>
												<option value="9" <?php if ($sizes[3]==9) { echo 'selected="selected"'; } ?>>Bottom / Right</option>
											</select>
										</td>
                                        <td class="center"><button class="button button-secondary delete_image_size" <?php if ($wpe_s==1) { echo 'disabled="disabled"'; } ?>>Delete</button></td>
                                    </tr>
                                <?php } ?>
							</tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="16" class="center"><button id="add_image_size" class="button button-secondary">Add Image Size</button></td>
                                </tr>
                            </tfoot>
						</table>
                        <input type="hidden" name="wpe_total_image_sizes" id="wpe_total_image_sizes" value="">
					</div>
				</div>
				<div class="postbox" id="wpe_image_quality">
					<h3 class="hndle"><span><span class="wpe-image"></span> Image Quality</span></h3>
					<div class="inside clearfix">
						<div id="wpe-image-content">
							<p>Change the quality of images that are uploaded to WordPress.</p>
							<p><em>Please note: changes won&rsquo;t take effect until you rebuild the thumbnails.</em></p>
							<div class="clearfix">
								<div id="wpe-slider"></div> <span id="wpe-slider-label"><?php echo get_option('wpe_image_quality'); ?></span>%
							</div>
						</div>
						<input type="text" class="regular-text" name="image_quality" id="image_quality" value="<?php echo get_option('wpe_image_quality'); ?>">
						<div id="wpe-image-preview">
							<p>Preview</p>
							<div id="wpe-image-container">
								<img src="<?php echo ESSENTIALS_PATH; ?>/images/quality-1.jpg" class="image-1" style="opacity:<?php echo (get_option('wpe_image_quality')/100); ?>;">
								<img src="<?php echo ESSENTIALS_PATH; ?>/images/quality-2.jpg" class="image-2">
							</div>
						</div>
					</div>
				</div>
				<div class="postbox pro_version" id="wpe_login_notification">
					<h3 class="hndle"><span><span class="wpe-envelope"></span> Login Notification</span></h3>
					<div class="inside">
						<p>Sends an automatic notification to a user&rsquo;s email address whenever their account has logged in.</p>
						<label for="login_notification"><input type="checkbox" name="login_notification" id="login_notification" value="1" <?php if (get_option('wpe_login_notification')==1) { ?>checked="checked"<?php } ?>> Enable Login Notifications</label>
					</div>
				</div>
				<div class="postbox pro_version" id="wpe_holding_page">
					<h3 class="hndle"><span><span><span class="wpe-file-text"></span> Holding Page</span></h3>
					<div class="inside">
						<p>Displays a holding page message to regular visitors.</p>
						<label for="holding_page"><input type="checkbox" name="holding_page" id="holding_page" value="1"> Enable Holding Page</label>
						<p><label for="holding_page_header"><input type="text" class="regular-text" name="" id="holding_page_header" value="" placeholder=""> Header</label></p>
						<p><label for="holding_page_message"><input type="text" class="regular-text" name="" id="holding_page_message" value="" placeholder=""> Message</label></p>
						<p><label for="holding_page_password"><input type="password" class="regular-text" name="" id="holding_page_password" placeholder="" value="" placeholder=""> Password</label></p>
						<h4>Notes</h4>
						<p>The password field is optional and stops the holding page from being turned off by another user.</p>
						<p>If a password has been set, it must entered to unlock the holding page options.</p>
					</div>
				</div>
				<h2>Plugins</h2>
				<div class="postbox" id="wpe_email">
					<h3 class="hndle"><span><span class="wpe-envelope"></span> Email</span></h3>
					<div class="inside">
						<p>The WYSIWYG editor comes with an email button for easily adding <code>mailto:</code> links without any HTML knowledge.</p>
						<label for="email"><input type="checkbox" name="email" id="email" value="1" <?php if (get_option('wpe_email')==1) { ?>checked="checked"<?php } ?>> Enable Email Button</label>
					</div>
				</div>
				<div class="postbox" id="wpe_google_analytics">
					<h3 class="hndle"><span><span class="wpe-stats-dots"></span> Google Analytics</span></h3>
					<div class="inside">
						<p>Adds Google Analytics tracking code to every page.</p>
						<h4>Setup</h4>
						<p><label for="google_analytics"><input type="text" class="regular-text" name="google_analytics" id="google_analytics" value="<?php echo get_option('wpe_google_analytics'); ?>" placeholder="UA-XXXXXXXX-X"> Google Analytics Tracking IDs</label></p>
						<p><em>Please note: you can add multiple tracking IDs by comma separating them.</em></p>
						<p><label for="google_link_tracking"><input type="checkbox" name="google_link_tracking" id="google_link_tracking" value="1" <?php if (get_option('wpe_google_link_tracking')==1) { ?>checked="checked"<?php } ?>> Track outbound links</label></p>
					</div>
				</div>
				<div class="postbox" id="wpe_facebook_likebox">
					<h3 class="hndle"><span><span class="wpe-facebook"></span> Facebook Like Box</span></h3>
					<div class="inside">
						<p>The <code>[facebook]</code> shortcode will display a Facebook Like Box for any Facebook Page.</p>
						<h4>Usage</h4>
						<p>The shortcode supports the following:</p>
						<ul>
							<li><code>[facebook page="http://www.facebook.com/yourpage"]</code> This is the URL of the Facebook Page.</li>
							<li><code>[facebook colorscheme="dark"]</code> Uses the dark color scheme instead of the default scheme.</li>
							<li><code>[facebook connections="12"]</code> This is the number of Facebook profiles the Like Box will display. (Stream must be <code>true</code> if set to zero.)</li>
							<li><code>[facebook width="300"]</code> This is the width of your Like Box.</li>
							<li><code>[facebook height="300"]</code> This is the height of your Like Box.</li>
							<li><code>[facebook stream="true"]</code> Includes your pages stream of latest posts.</li>
							<li><code>[facebook header="true"]</code> Includes the Facebook header at the top of the Like Box.</li>
							<li><code>[facebook border="false"]</code> Removes the default Facebook border from around your Like Box.</li>
						</ul>
						<h4>Output</h4>
						<p>Your fanbox will be displayed with a <code>&lt;div&gt;</code> with the class <code>.fanbox</code>.</p>
						<label for="facebook"><input type="checkbox" name="facebook" id="facebook" value="1" <?php if (get_option('wpe_facebook')==1) { ?>checked="checked"<?php } ?>> Enable Facebook Fanbox</label>
					</div>
				</div>
				<div class="postbox" id="wpe_flickr_feed">
					<h3 class="hndle"><span><span class="wpe-flickr"></span> Flickr Feed</span></h3>
					<div class="inside">
						<p>The <code>[flickr]</code> shortcode is our own built in Flickr feed with cache support (Refreshes every hour).</p>
						<h4>Usage</h4>
						<p>Enter your Flickr username and API key below, it&rsquo;ll enable a Flickr feed on the website. You can then display the feed by using the <code>[flickr]</code> shortcode.</p>
						<p>The shortcode also supports the following:</p>
						<ul>
							<li><code>[flickr count="3"]</code> This will display the latest 3 photos.</li>
							<li><code>[flickr order="random"]</code> This will display random photos.</li>
							<li><code>[flickr class="photos"]</code> This will give your Flickr <code>&lt;ul&gt;</code> a custom class name.</li>
						</ul>
						<h4>Setup</h4>
						<label for="flickr_username"><input type="text" class="regular-text" name="flickr_username" id="flickr_username" value="<?php echo get_option('wpe_flickr_username'); ?>"> Flickr Username</label><br>
						<label for="flickr_api"><input type="text" class="regular-text" name="flickr_api" id="flickr_api" value="<?php echo get_option('wpe_flickr_api'); ?>"> Flickr API Key (<a href="http://www.flickr.com/services/api/keys/apply/" target="_blank">Get it here</a>)</label>
						<h4>Cache</h4>
						<p><a href="#" id="wpe_cache_flickr">Click here to force a cache refresh.</a></p>
					</div>
				</div>
				<div class="postbox" id="wpe_google_maps">
					<h3 class="hndle"><span><span class="wpe-location"></span> Google Maps</span></h3>
					<div class="inside">
					<p>The <code>[google_maps]</code> shortcode allows you to embed a Google Map anywhere on your site.</p>
					<h4>Usage</h4>
					<p>Just add <code>[google_maps address="{your_address}"]</code> to your page and it will embed a Google Map in its place. The map will be inside a <code>&lt;div&gt;</code> with the class name <code>google_map</code>. This class name is required and cannot be changed.</p>
					<p class="pro_version">To create multiple markers, separate your addresses with the pipe (<code>|</code>) character. <sup><span class="wpe-lock"></span> <em>Premium License Required</em></sup></p>
					<p>The shortcode also supports the following:</p>
					<ul>
						<li><code>[google_maps zoom="5"]</code> The zoom level for the map. Default: 14</li>
						<li><code>[google_maps controls="false"]</code> This will disable the zoom / street view controls. Default: true</li>
						<li><code>[google_maps marker="false"]</code> This will disable the red marker on your address. Default: true</li>
						<li><code>[google_maps width="200px" height="200px"]</code> This will allow you to customise the width and height of your map Default: 300px x 300px</li>
						<li><code>[google_maps icon="http://www.example.com/icon.png"]</code> This allows you to add a custom marker icon.</li>
						<li><code>[google_maps static="true"]</code> This will use a static image map instead of an interactive map (cannot be used with custom icons). Default: false</li>
					</ul>
						<label for="google_maps"><input type="checkbox" name="google_maps" id="google_maps" value="1" <?php if (get_option('wpe_google_maps')==1) { ?>checked="checked"<?php } ?>> Enable Google Maps</label>
					</div>
				</div>
				<div class="postbox pro_version" id="wpe_instagram_feed">
					<h3 class="hndle"><span><span class="wpe-instagram"></span> Instagram Feed</span></h3>
					<div class="inside">
					<p>The <code>[instagram]</code> shortcode is our own built in Instagram feed with cache support (Refreshes every 15 minutes).</p>
					<h4>Usage</h4>
					<p>Enter your enter the client&rsquo;s Instagram username and Application access codes below, it&rsquo;ll enable a Instagram feed on the website. You can then display the feed by using the <code>[instagram]</code> shortcode.</p>
					<p>The shortcode also supports the following:</p>
					<ul>
						<li><code>[instagram count="3"]</code> This will display the latest 3 tweets.</li>
						<li><code>[instagram order="random"]</code> This will display random tweets.</li>
						<li><code>[instagram keyword="snowflake"]</code> This will display images from a hashtag search.</li>
						<li><code>[instagram class="instagram"]</code> This will give your Instagram <code>&lt;ul&gt;</code> a custom class name.</li>
					</ul>
						<h4>Setup</h4>
						<label for="instagram_username"><input type="text" class="regular-text" name="instagram_username" id="instagram_username" value="<?php echo get_option('wpe_instagram_username'); ?>"> Instagram Username</label><br>
						<label for="instagram_client_id"><input type="text" class="regular-text" name="instagram_client_id" id="instagram_client_id" value="<?php echo get_option('wpe_instagram_client_id'); ?>"> Client ID (<a href="https://instagram.com/developer/clients/manage/" target="_blank">Get it here</a>)</label> <code>OAuth redirect_uri: <?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=wp-essentials'; ?></code><br>
						<label for="instagram_client_secret"><input type="text" class="regular-text" name="instagram_client_secret" id="instagram_client_secret" value="<?php echo get_option('wpe_instagram_client_secret'); ?>"> Client Secret</label><br>
						<?php if (get_option('wpe_instagram_username') && get_option('wpe_instagram_client_id') && !get_option('wpe_instagram_user_id') && !get_option('wpe_instagram_access_token')) { ?>
							<p><a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo get_option('wpe_instagram_client_id'); ?>&redirect_uri=<?php echo get_bloginfo('wpurl').'/wp-admin/index.php'; ?>&response_type=code">Click here to authorize your Instagram access code</a></p>
						<?php } ?>
						<h4>Cache</h4>
						<p><a href="#" id="wpe_cache_instagram">Click here to force a cache refresh.</a></p>
					</div>
				</div>
				<div class="postbox" id="wpe_twitter_feed">
					<h3 class="hndle"><span><span class="wpe-twitter"></span> Twitter Feed</span></h3>
					<div class="inside">
						<p>The <code>[twitter]</code> shortcode is our own built in Twitter feed with cache support (Refreshes every 15 minutes).</p>
						<p>There's also a &lsquo;Post to Twitter&rsquo; checkbox included when adding / editing Posts.</p>
						<h4>Usage</h4>
						<p>Enter your Twitter username and Application access codes below, it&rsquo;ll enable a Twitter feed on the website. You can then display the feed by using the <code>[twitter]</code> shortcode.</p>
						<p>The shortcode also supports the following:</p>
						<ul>
							<li><code>[twitter count="3"]</code> This will display the latest 3 tweets.</li>
							<li><code>[twitter order="random"]</code> This will display random tweets.</li>
							<li><code>[twitter class="tweets"]</code> This will give your Twitter <code>&lt;ul&gt;</code> a custom class name.</li>
							<li><code>[twitter search="#keyword"]</code> This will filter your Tweets with the hastag keyword you choose. <sup><span class="wpe-lock"></span> <em>Premium License Required</em></sup></li>
						</ul>
						<h4 class="pro_version">Custom Tweet Format <sup><span class="wpe-lock"></span> <em>Premium License Required</em></sup></h4>
						<p>Tweets are displayed in the site inside a <code>&lt;ul></code>. You can change the format of the tweet by adding the following code to your <code>functions.php</code> file:</p>
						<pre>
function wpe_twitter_format($data) {
&nbsp;&nbsp;&nbsp;&nbsp;$wpe_twitter_format = '&lt;a href="'.$data['link'].'">'.$data['date'].'&lt;/a> '.$data['content'].' by '.$data['author'];
&nbsp;&nbsp;&nbsp;&nbsp;return $wpe_twitter_format;
}
						</pre>
						<p>You can use the following options below to build your custom tweet format:</p>
						<ul>
							<li><code>$data['author']</code> The author&rsquo;s name.</li>
							<li><code>$data['link']</code> The direct link to the tweet.</li>
							<li><code>$data['content']</code> The tweet content.</li>
							<li><code>$data['date']</code> The datestamp of the tweet.</li>
							<li><code>$data['interact']</code> The reply/retweet/favourite links.</li>
						</ul>
						<h4>Setup</h4>
						<table id="twitter_accounts_table" cellpadding="0" cellspacing="0">
							<thead>
                            	<tr>
                                    <th class="center">Username</th>
                                    <th class="center">Consumer Key (<a href="https://dev.twitter.com/apps/new" target="_blank">Get it here</a>)</th>
                                    <th class="center">Consumer Secret</th>
                                    <th class="center">OAuth Access Token</th>
                                    <th class="center">OAuth Access Token Secret</th>
                                    <th class="center">&nbsp;</th>
                                </tr>
							</thead>
							<tbody>
                            	<?php
									for($wpe_t=1;$wpe_t<=1;$wpe_t++) {
										$account = get_option('wpe_twitter_'.$wpe_t);
										$accounts = explode(';',$account);										
								?>
                                    <tr>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_t; ?>_twitter_username" id="" value="<?php echo $accounts[0]; ?>"></td>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_t; ?>_consumer_key" value="<?php echo $accounts[1]; ?>"></td>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_t; ?>_consumer_secret" value="<?php echo $accounts[2]; ?>"></td>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_t; ?>_oauth_access_token" value="<?php echo $accounts[3]; ?>"></td>
                                        <td class="center"><input type="text" class="medium-text" name="<?php echo $wpe_t; ?>_oauth_access_token_secret" value="<?php echo $accounts[4]; ?>"></td>
                                        <td class="center"><button class="button button-secondary delete_twitter_account" disabled="disabled">Delete</button></td>
                                    </tr>
                                <?php } ?>
							</tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="6" class="center pro_version"><button id="add_twitter_account" class="button button-secondary" disabled="disabled">Add Twitter Account</button> <sup><span class="wpe-lock"></span> <em>Premium License Required</em></sup></td>
                                </tr>
                            </tfoot>
						</table>
                        <input type="hidden" name="wpe_twitter_accounts" id="wpe_twitter_accounts" value="">
					</div>
				</div>
				<div class="postbox pro_version" id="wpe_social_stream">
					<h3 class="hndle"><span><span><span class="wpe-share2"></span> Social Stream <sup>BETA</sup></span></h3>
					<div class="inside">
						<p>The <code>[social_stream]</code> shortcode is a feed that combines the Twitter, Instagram and Flickr feeds into one stream, ordered by date (most recent first.)</p>
						<p>
							<label for="wpe_social_stream_label"><input type="text" class="regular-text" name="wpe_social_stream_label" id="wpe_social_stream_label" value="<?php echo get_option('wpe_social_stream_label'); ?>"> Load more text</label>
						</p>
						<p>
							<label for="wpe_social_stream_per_page"><input type="text" class="regular-text" name="wpe_social_stream_per_page" id="wpe_social_stream_per_page" value="<?php echo get_option('wpe_social_stream_per_page'); ?>"> Per Page</label>
						</p>
						<p>
							<label for="wpe_social_stream_twitter">
								<input type="checkbox" name="wpe_social_stream_twitter" id="wpe_social_stream_twitter" value="1" <?php if (get_option('wpe_social_stream_twitter')==1) { ?>checked="checked"<?php } ?>> Include Twitter</code>
							</label>
							<br>
							<label for="wpe_social_stream_instagram">
								<input type="checkbox" name="wpe_social_stream_instagram" id="wpe_social_stream_instagram" value="1" <?php if (get_option('wpe_social_stream_instagram')==1) { ?>checked="checked"<?php } ?>> Include Instagram</code>
							</label>
							<br>
							<label for="wpe_social_stream_flickr">
								<input type="checkbox" name="wpe_social_stream_flickr" id="wpe_social_stream_flickr" value="1" <?php if (get_option('wpe_social_stream_flickr')==1) { ?>checked="checked"<?php } ?>> Include Flickr</code>
							</label>
							<br>
							<label for="wpe_social_stream_infinite">
								<input type="checkbox" name="wpe_social_stream_infinite" id="wpe_social_stream_infinite" value="1" <?php if (get_option('wpe_social_stream_infinite')==1) { ?>checked="checked"<?php } ?>> Use infinite scrolling</code>
							</label>
						</p>
						<p><strong>Tip:</strong> Social Stream also supports <a href="http://isotope.metafizzy.co/" target="_blank">Isotope</a> and <a href="http://imagesloaded.desandro.com/" target="_blank">ImagesLoaded</a>. Just include the libraries on your page and WP Essentials will do the rest.</p>
					</div>
				</div>
				<div class="postbox pro_version" id="wpe_styling">
					<h3 class="hndle"><span><span class="wpe-quill"></span> Styling</span></h3>
					<div class="inside">
						<p>Choose whether your website uses CSS, <a href="http://sass-lang.com/" target="_blank">SASS</a>, or <a href="http://lesscss.org/" target="_blank">LESS</a>.</p>
						<label for="none"><input type="radio" name="style" id="none" value="none" <?php if (get_option('wpe_style_none')=='none') { ?>checked="checked"<?php } ?>> None</label>
						<label for="css"><input type="radio" name="style" id="css" value="css" <?php if (get_option('wpe_style')=='css') { ?>checked="checked"<?php } ?>> CSS</label>
						<label for="sass"><input type="radio" name="style" id="sass" value="sass" <?php if (get_option('wpe_style')=='sass') { ?>checked="checked"<?php } ?>> SASS</label>
						<label for="less"><input type="radio" name="style" id="less" value="less" <?php if (get_option('wpe_style')=='less') { ?>checked="checked"<?php } ?>> LESS</label>
						<p class="style_css" <?php if (get_option('wpe_style')!='css') { ?>style="display:none;"<?php } ?>>Please save your CSS file in <code><?php bloginfo('template_url'); ?>/css/style.css</code></p>
						<p class="style_sass" <?php if (get_option('wpe_style')!='sass') { ?>style="display:none;"<?php } ?>>Please save your SASS file to <code><?php bloginfo('template_url'); ?>/css/style.scss</code></p>
						<p class="style_sass" <?php if (get_option('wpe_style')!='sass') { ?>style="display:none;"<?php } ?>><em>Please note: an empty <code>style_sass.css</code> file must also be saved.</em></p>
						<p class="style_less" <?php if (get_option('wpe_style')!='less') { ?>style="display:none;"<?php } ?>>Please save your LESS file to <code><?php bloginfo('template_url'); ?>/css/style.less</code></p>
					</div>
				</div>
				<div class="postbox" id="wpe_responsive">
					<h3 class="hndle"><span><span><span class="wpe-mobile"></span> Responsive</span></h3>
					<div class="inside">
						<p>Enable <a href="http://mobiledetect.net/" target="_blank">Mobile Detect</a> to support responsive detection.</p>
						<label for="responsive"><input type="checkbox" name="responsive" id="responsive" value="1" <?php if (get_option('wpe_responsive')==1) { ?>checked="checked"<?php } ?>> Enable Responsive</label>
						<h4>Usage</h4>
						<p>Moble Detect works directly in your PHP files. To use it in your PHP, refer to the official documentation. However you don't have to include the <code>Mobile_Detect.php</code> file or define the class, just replace their <code>$detect</code> variable with <code>$wpe_responsive</code>.</p>
						<p>Example:</p>
						<p><code>&lt;?php if ($wpe_responsive->isMobile()) { /* Code */ } ?&gt;</code></p>
						<p>You can also use the detection as a shortcode instead.</p>
						<p>Example:</p>
						<p><code>[wpe_responsive] Mobile and tablet content. [/wpe_responsive]</code></p>
						<p><code>[wpe_responsive not="tablet"] Mobile only content. [/wpe_responsive]</code></p>
						<p><code>[wpe_responsive not="mobile"] Tablet only content. [/wpe_responsive]</code></p>
						<p>At the moment, these are the only valid rules for the shortcode, but the PHP code is unrestricted.</p>
					</div>
				</div>
				<div class="postbox" id="wpe_email">
					<h3 class="hndle"><span><span><span class="wpe-envelope"></span> Email</span></h3>
					<div class="inside">
						<p>The WYSIWYG editor comes with an email button for easily adding <code>mailto:</code> links without any HTML knowledge.</p>
						<label for="email"><input type="checkbox" name="email" id="email" value="1" <?php if (get_option('wpe_email')==1) { ?>checked="checked"<?php } ?>> Enable Email Button</label>
					</div>
				</div>
				<div class="postbox" id="wpe_date">
					<h3 class="hndle"><span><span class="wpe-calendar"></span> Date</span></h3>
					<div class="inside">
						<p>The <code>[wpe_date]</code> shortcode will display today&rsquo;s date.</p>
						<h4>Usage</h4>
						<p>Simply use <code>[wpe_date]</code> as a shortcode and it will output the date in the format: DD/MM/YYYY.</p>
						<p>The date format can be changed by using any of the parameters from the <a href="http://php.net/manual/en/function.date.php" target="_blank">PHP Date manual</a>.</p>
						<p>Example: <code>[wpe_date format="l jS F Y"]</code></p>
						<p>Output: <?php echo date("l jS F Y"); ?></p>
						<label for="php_date"><input type="checkbox" name="php_date" id="php_date" value="1" <?php if (get_option('wpe_php_date')==1) { ?>checked="checked"<?php } ?>> Enable <code>[date]</code></label>
					</div>
				</div>
				<div class="postbox pro_version" id="wpe_get">
					<h3 class="hndle"><span><span><span class="wpe-browser"></span> Get Query</span></h3>
					<div class="inside">
						<p>Use shortcodes to display content based on query strings in the URL.</p>
						<h4>Usage</h4>
						<p><strong>Show content</strong></p>
						<p>Wrap your content in the <code>[wpe_get query="" value=""] [/wpe_query]</code> shortcode.</p>
						<p>Example: <code>[wpe_get query="id" value="123"]This content will only appear if the URL contains <code>?id=123</code>[/wpe_get]</code></p>
						<p>The <code>query</code> attribute is required, but <code>value</code> is optional.</p>
						<p><strong>Show the variable</strong></p>
						<p>Add the <code>[wpe_get display=""]</code> shortcode to your existing content to display the value of a query string.
						<p>Example: <code>Your ID is [wpe_get display="id"].</code></p>
						<p>Output: Your ID is 123.</p>
						<label for="get"><input type="checkbox" name="get" id="get" value="1" <?php if (get_option('wpe_get')==1) { ?>checked="checked"<?php } ?>> Enable <code>[wpe_get]</code></label>
					</div>
				</div>
				<h2>PHP Functions</h2>
                <p class="warning">The PHP Function names have been changed to include the new <code>wpe_</code> naming convention. The old names will work but will be phased out eventually.</p>
				<div class="postbox" id="wpe_custom_excerpt">
					<h3 class="hndle"><span><span class="wpe-code"></span> Custom Excerpt</span></h3>
					<div class="inside">
						<p>Allows you use a custom excerpt length.</p>
						<h4>Usage</h4>
						<p>The function echos out the post or page content and cuts off at your specified length.</p>
						<p>The function supports the following options:</p>
						<ul>
							<li><code>wpe_excerpt(50);</code> This will display the first 50 characters.</li>
							<li><code>wpe_excerpt(50, 'Read more');</code> This will display a &lsquo;Read more&rsquo; link after the cut off.</li>
							<li><code>wpe_excerpt(50, 'Read more', false);</code> This will remove the hyperlink from &lsquo;Read more&rsquo;.</li>
							<li><code>wpe_excerpt(50, 'Read more', false, 123);</code> This will show the excerpt of post ID <code>123</code>.</li>
							<li><code>wpe_excerpt(50, 'Read more', false, 123, false);</code> This will <code>return</code> the excerpt instead of <code>echo</code>.</li>
						</ul>
						<label for="custom_excerpt"><input type="checkbox" name="excerpt" id="custom_excerpt" value="1" <?php if (get_option('wpe_excerpt')==1) { ?>checked="checked"<?php } ?>> Enable <code>wpe_excerpt()</code></label>
					</div>
				</div>
				<div class="postbox" id="wpe_get_image_source">
					<h3 class="hndle"><span><span class="wpe-code"></span> Get Image Source</span></h3>
					<div class="inside">
					<p>Allows you to grab an image source based on the attachment ID.</p>
					<h4>Usage</h4>
					<p>The function echos out the image source based on the attachment ID and thumbnail size.</p>
					<p>Example: <code>wpe_get_image_source('123','thumbnail',false);</code></p>
					<p>Outout: <code>http://www.domain.com/wp-content/uploads/01/01/image.jpg</code></p>
					<p>If the last option is set to <code>true</code> then the image source will be returned rather than echoed.</p>
					<p><em>Please Note: this function is very useful (and recommended) when using image IDs via Advanced Custom Fields.</em></p>
						<label for="get_image_source"><input type="checkbox" name="get_image_source" id="get_image_source" value="1" <?php if (get_option('wpe_get_image_source')==1) { ?>checked="checked"<?php } ?>> Enable <code>wpe_get_image_source()</code></label>
					</div>
				</div>
				<div class="postbox" id="wpe_link_it">
					<h3 class="hndle"><span><span class="wpe-code"></span> Link It</span></h3>
					<div class="inside">
						<p>This function allows you to hyperlink any website or email addresses that may otherwise be plain text.</p>
						<h4>Usage</h4>
						<p>Example: <code>wpe_link_it('http://www.wp-essentials.net');</code></p>
						<p>Output: <code><?php echo wpe_link_it('http://www.wp-essentials.net'); ?></code></p>
						<label for="link_it"><input type="checkbox" name="link_it" id="link_it" value="1" <?php if (get_option('wpe_link_it')==1) { ?>checked="checked"<?php } ?>> Enable <code>wpe_link_it()</code></label>
					</div>
				</div>
				<div class="postbox" id="wpe_relative_time">
					<h3 class="hndle"><span><span class="wpe-code"></span> Relative Time</span></h3>
					<div class="inside">
						<p>Allows you to display a relative time based on a Unix timestamp.</p>
						<h4>Usage</h4>
						<p>The function echos out the relative time based on the datestamp.</p>
						<p>Example: <code>wpe_relative_time("<?php echo strtotime("5 minutes ago"); ?>");</code></p>
						<p>Outout: <code><?php echo wpe_relative_time(strtotime("5 minutes ago")); ?></code></p>
						<label for="relative_time"><input type="checkbox" name="relative_time" id="relative_time" value="1" <?php if (get_option('wpe_relative_time')==1) { ?>checked="checked"<?php } ?>> Enable <code>wpe_relative_time()</code></label>
					</div>
				</div>
				<h2>Deprecated Functions</h2>
                <p class="warning">This is a list of old functions that will no longer recieve updates and will eventually be phased out.</p>
				<div class="postbox" id="wpe_video">
					<h3 class="hndle"><span><span><span class="wpe-play"></span> Video</span></h3>
					<div class="inside">
						<p>Converts YouTube, Vimeo, or Facebook links into embedded videos.</p>
						<h4>Usage</h4>
						<p>Wrap either a YouTube, Vimeo, or Facebook URL in the <code>[wpe_video]</code> shortcode.</p>
						<p>Example: <code>[wpe_video]http://www.youtube.com/watch?v=ZH986n94ELA[/wpe_video]</code></p>
						<p>You can also set a custom width and height:</p>
						<p>Example: <code>[wpe_video width="200" height="200"]http://www.youtube.com/watch?v=ZH986n94ELA[/wpe_video]</code></p>
						<label for="video"><input type="checkbox" name="video" id="video" value="1" <?php if (get_option('wpe_video')==1) { ?>checked="checked"<?php } ?>> Enable <code>[wpe_video]</code></label>
					</div>
				</div>
			</div>
			<div id="wpe_right">
				<input type="hidden" name="submitted" value="true">
				<div class="postbox">
					<h3 class="hndle"><span><span class="wpe-spinner"></span> Update</span></h3>
					<div class="inside center">
						<?php submit_button(__( 'Update Settings','plugin_domain'),'primary large','submit'); ?>
						</form>
					</div>
				</div>
				<div class="postbox">
					<h3 class="hndle"><span><span class="wpe-fire"></span> About this Plugin <span class="wpe_version">v<?php echo ESSENTIALS_VERSION; ?></span></span></h3>
					<div class="inside">
						<p>WP Essentials is developed and maintained by Craig at <a href="http://www.iprogress.co.uk">iprogress</a>.</p>
						<p>You can also follow me on twitter <a href="http://twitter.com/ceefitzpee">@ceefitzpee</a>.</p>
					</div>
				</div>
				<div class="postbox">
					<h3 class="hndle"><span><span class="wpe-star"></span> Review This Plugin</span></h3>
					<div class="inside">
						<p>If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/wp-essentials#postform">write me a review</a>.</p>
					</div>
				</div>
				<div class="postbox" id="license_check">
					<h3 class="hndle"><span><span class="wpe-heart"></span> Keep this plugin free!</span></h3>
					<div class="inside center">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="9FULNK8LF2V56">
							<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
						</form>
					</div>
				</div>
				<div class="postbox" id="license_check">
					<h3 class="hndle"><span><span class="wpe-key"></span> WP Essentials Premium</span></h3>
					<div class="inside">
                    	<p>WP Essentials Premium includes:</p>
						<ul>
							<li>Security &amp; DB backups</li>
							<li>Instagram integration</li>
							<li>Custom styling options</li>
							<li>Multiple Twitter accounts</li>
							<li>Social Stream feature</li>
							<li>Direct technical support</li>
						</ul>
						<p class="center"><strong><span style="text-decoration:line-through;">$19.99</span> <span style="color:#f00;">$9.99</span></strong></p>
						<p class="center"><a href="http://www.wp-essentials.net" class="button button-primary button-large">Purchase Now</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>