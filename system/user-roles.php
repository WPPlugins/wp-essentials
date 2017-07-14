<?php
	// Add Client role
		if (!function_exists('add_role_caps')) {
			function add_role_caps() {
				for($wpe_i=1;$wpe_i<=get_option('wpe_total_user_roles');$wpe_i++) {
					$role = get_option('wpe_user_role_'.$wpe_i);
					$roles = explode(';',$role);
					
					$role_title = strtolower($roles[0]);
					$edit_dashboard = $roles[1];
					$edit_files = $roles[2];
					$edit_theme = $roles[3];
					$manage_others_posts = $roles[4];
					$manage_others_pages = $roles[5];
					$manage_pages = $roles[6];
					$manage_posts = $roles[7];
					$manage_users = $roles[8];
					$manage_categories = $roles[9];
					$manage_links = $roles[10];
					$manage_options = $roles[11];
					$manage_comments = $roles[12];
					$manage_plugins = $roles[13];
					$update_core = $roles[14];
					
					remove_role('client');
					$role = get_role('client');
					if (!$role) $role = add_role('client',ucwords($role_title)); 
					
					$role->add_cap('read');
					$role->add_cap('level_1');
					
					if ($edit_dashboard==1) {
						$role->add_cap('edit_dashboard');
					}
					
					if ($edit_files==1) {
						$role->add_cap('edit_files');
						$role->add_cap('upload_files');
					}
					
					if ($edit_theme==1) {
						$role->add_cap('edit_theme_options');
						$role->add_cap('install_themes');
						$role->add_cap('delete_themes');
						$role->add_cap('edit_themes');
					}
					
					if ($manage_others_posts==1) {
						$role->add_cap('delete_others_posts');
						$role->add_cap('edit_others_posts');
					}
					
					if ($manage_others_pages==1) {
						$role->add_cap('delete_others_pages');
						$role->add_cap('edit_others_pages');
					}
					
					if ($manage_posts==1) {
						$role->add_cap('edit_posts');
						$role->add_cap('publish_posts');
						$role->add_cap('delete_posts');
						$role->add_cap('edit_private_posts');
						$role->add_cap('delete_private_posts');
						$role->add_cap('edit_published_posts');
						$role->add_cap('delete_published_posts');
						$role->add_cap('read_private_posts');
					}
					
					if ($manage_pages==1) {
						$role->add_cap('edit_pages');
						$role->add_cap('publish_pages');
						$role->add_cap('delete_pages');
						$role->add_cap('edit_private_pages');
						$role->add_cap('delete_private_pages');
						$role->add_cap('edit_published_pages');
						$role->add_cap('delete_published_pages');
						$role->add_cap('read_private_pages');
					}
					
					if ($manage_users==1) {
						$role->add_cap('create_users');
						$role->add_cap('delete_users');
						$role->add_cap('list_users');
						$role->add_cap('promote_users');
						$role->add_cap('remove_users');
						$role->add_cap('edit_users');
					}
					
					if ($manage_categories==1) {
						$role->add_cap('manage_categories');
					}
					
					if ($manage_links==1) {
						$role->add_cap('manage_links');
					}
					
					if ($manage_options==1) {
						$role->add_cap('manage_options');
					}
					
					if ($manage_comments==1) {
						$role->add_cap('moderate_comments');
					}
					
					if ($manage_plugins==1) {
						$role->add_cap('activate_plugins');
						$role->add_cap('delete_plugins');
						$role->add_cap('update_plugins');
						$role->add_cap('install_plugins');
						$role->add_cap('edit_plugins');
					}
					
					if ($update_core==1) {
						$role->add_cap('update_core');
					}
					
					$role->add_cap('frm_view_entries');
					$role->add_cap('frm_view_forms');
					$role->add_cap('frm_view_reports');
				}
			}
			add_action('admin_init','add_role_caps');
		
			class User_Caps {
				function User_Caps(){
					add_filter('editable_roles',array(&$this,'editable_roles'));
					add_filter('map_meta_cap',array(&$this,'map_meta_cap'),10,4);
				}
				function editable_roles($roles){
					if(isset($roles['administrator']) && !current_user_can('administrator')){
						unset($roles['administrator']);
					}
					return $roles;
				}
				function map_meta_cap($caps,$cap,$user_id,$args){
					switch($cap){
						case 'edit_user':
						case 'remove_user':
						case 'promote_user':
							if(isset($args[0]) && $args[0] == $user_id)
								break;
							elseif(!isset($args[0]))
								$caps[] = 'do_not_allow';
								$other = new WP_User(absint($args[0]));
							if( $other->has_cap('administrator')){
								if(!current_user_can('administrator')){
									$caps[] = 'do_not_allow';
								}
							}
						break;
						case 'delete_user':
						case 'delete_users':
							if(!isset($args[0]))
						break;
						$other = new WP_User(absint($args[0]));
						if( $other->has_cap('administrator')){
							if(!current_user_can('administrator')){
								$caps[] = 'do_not_allow';
							}
						}
						break;
						default:
						break;
					}
					return $caps;
				}
			}
			$user_caps = new User_Caps();
		}