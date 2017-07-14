<?php
	// Add Client role
		if (!function_exists('add_role_caps')) {
			function add_role_caps() {
				$role = get_role('client');
				if (!$role) $role = add_role('client','Client'); 
				
				$role->add_cap('read');
				$role->add_cap('create_users');
				$role->add_cap('delete_others_pages');
				$role->add_cap('delete_others_posts');
				$role->add_cap('delete_pages');
				$role->add_cap('delete_posts');
				$role->add_cap('delete_private_pages');
				$role->add_cap('delete_private_posts');
				$role->add_cap('delete_published_pages');
				$role->add_cap('delete_published_posts');
				$role->add_cap('delete_users');
				$role->add_cap('edit_dashboard');
				$role->add_cap('edit_files');
				$role->add_cap('edit_others_pages');
				$role->add_cap('edit_others_posts');
				$role->add_cap('edit_pages');
				$role->add_cap('edit_posts');
				$role->add_cap('edit_private_pages');
				$role->add_cap('edit_private_posts');
				$role->add_cap('edit_published_pages');
				$role->add_cap('edit_published_posts');
				$role->add_cap('edit_theme_options');
				$role->add_cap('list_users');
				$role->add_cap('manage_categories');
				$role->add_cap('manage_links');
				$role->add_cap('manage_options');
				$role->add_cap('moderate_comments');
				$role->add_cap('promote_users');
				$role->add_cap('publish_pages');
				$role->add_cap('publish_posts');
				$role->add_cap('read_private_pages');
				$role->add_cap('read_private_posts');
				$role->add_cap('read');
				$role->add_cap('remove_users');
				$role->add_cap('upload_files');
				$role->add_cap('level_1');
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