<?php
//Runs when plugin is activated
function abd_dwm() {
	global $wpdb, $current_user;
	if (is_admin() && is_user_logged_in()) {
		add_action('admin_menu', 'abd_dwm_admin_menu');
		add_action('wp_dashboard_setup', 'abd_dwm_find_core_dashboard_widgets');
		add_action('init', 'abd_dwm_settings_page_header'); //the header logic for the settings page
		
		wp_register_style('abd_dwm_stylesheet', plugins_url('abd_dwm_style.css', __FILE__));
		wp_enqueue_style('abd_dwm_stylesheet');			
		
		$active_roles = get_option('abd_dwm_user_roles');				
		$role = $wpdb->prefix . 'capabilities';
		$current_user->role = array_keys($current_user->$role);
		$role = $current_user->role[0];	
		if (array_key_exists($role, (array)$active_roles)) {
			add_action('wp_dashboard_setup', 'abd_dwm_remove_dashboard_widgets' );
			add_action('wp_dashboard_setup', 'abd_dwm_add_dashboard_widget' );
		}		
	}
}

function abd_dwm_install() {
	add_option('abd_dwm_user_roles', array('administrator' => 'true'));
	add_option('abd_dwm_show_widgets', array('dashboard_right_now' => 'true'));
	add_option('abd_dwm_widget_content', 'Edit this text or use html, the media uploader, and shortcodes to create your own widget.');
	add_option('abd_dwm_widget_title', 'Custom Widget');
	add_option('abd_dwm_avail_dashboard_widgets');
}


function abd_dwm_uninstall() {
	delete_option('abd_dwm_user_roles');
	delete_option('abd_dwm_show_widgets');
	delete_option('abd_dwm_widget_content');
	delete_option('abd_dwm_widget_title');
	delete_option('abd_dwm_avail_dashboard_widgets');	
}

function abd_dwm_get_core_dashboard_widgets() {
	$dashboard_widgets = get_option('abd_dwm_avail_dashboard_widgets');
	if(empty($dashboard_widgets)) {
		$dashboard_widgets = array (
			'dashboard_right_now' => array (
				'name' => 'Right Now',
				'context' => 'normal'
			),		
			'dashboard_recent_comments' => array (
				'name' => 'Recent Comments',
				'context' => 'normal'
			),		
			'dashboard_incoming_links' => array (
				'name' => 'Incoming Links',
				'context' => 'normal'
			),		
			'dashboard_plugins' => array (
				'name' => 'Plugins',
				'context' => 'normal'
			),		
			'dashboard_quick_press' => array (
				'name' => 'Quick Press',
				'context' => 'side'
			),		
			'dashboard_recent_drafts' => array (
				'name' => 'Recent Drafts',
				'context' => 'side'
			),		
			'dashboard_primary' => array (
				'name' => 'Primary',
				'context' => 'side'
			),		
			'dashboard_secondary' => array (
				'name' => 'Secondary',
				'context' => 'side'
			)
		
		);
	}
	$custom_widget = array (
		'abd_dwm_custom_widget' => array(
			'name' => 'Custom Widget',
			'context' => 'side'		
		)
	);
	$dashboard_widgets = array_merge($dashboard_widgets, $custom_widget);
	return $dashboard_widgets;
}

function abd_dwm_settings_page() {
	global $wpdb, $wp_roles, $current_user;
	
	$dashboard_widgets = abd_dwm_get_core_dashboard_widgets();
	?>  
    
    <div class="wrap">
        <div class="icon32" id="icon-options-general"></div>    
        <h2 >ABD Dashboard Widget Manager</h2>
        <p style="float:left;">Take control of your WordPress Dashboard! </p><p style="float:right"><a href="http://aaronbday.com" target="_blank">Visit ABD Web Design</a></p>
        
        
        <div id="poststuff" class="metabox-holder">
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables ui-sortable">            
                    <form method=post id="dwm_options" action="" enctype="multipart/form-data">
                        <div class="postbox optionsbox">
                            <h3>User roles to target</h3>
                            <div class="inside">
                            	
                            	<p>Select which user roles you want these settings to apply to. (This is perfect for customizing the Dashboard for just your administrators or just your subscribers)</p>
                                <?php $all_roles = $wp_roles->roles;
                                foreach($all_roles as $role_key => $role) {	
                                    $active_roles = get_option('abd_dwm_user_roles');
                                    $checked = isset($active_roles[$role_key]) == 'true' ? 'checked="checked"' : '';
                                    ?>                           				
                                    <input type="checkbox" name="roles[<?php echo $role_key; ?>]" value="true" <?php echo $checked; ?> />
                                    <strong><?php echo $role['name'];?></strong>&nbsp;&nbsp;&nbsp;&nbsp;		
                                    <?php $checked = '';					
                                } ?>
                                <div class="spacer">&nbsp;</div>
                            </div>
                        </div>
                        
                        <div class="postbox optionsbox">
                            <h3>Dashboard Widgets</h3>
                            <div class="inside">
                            	<p>Select which widgets to display for the selected user roles.</p>
                                <?php 
                                foreach($dashboard_widgets as $widget_key => $widget) {
                                    $deactivated_widgets = get_option('abd_dwm_show_widgets');	
                                    $checked = isset($deactivated_widgets[$widget_key]) == 'true' ? 'checked="checked"' : '';
                                    ?>
                                    <div class="abd_dwm_widget_column"> 
                                    	<input type="checkbox" name="widgets[<?php echo $widget_key; ?>]" value="true" <?php echo $checked; ?> />           		               
                                        <strong><?php echo $widget['name'];?></strong>	                                       
                                    </div>					
                                    <?php $checked = '';					
                                } ?>
                                <div class="abd_dwm_clear spacer">&nbsp;</div>
                            </div>
                        </div>
                        
                        <div class="postbox optionsbox">
                            <h3>Custom Widget</h3>
                            <div class="inside">
                                <p>Use this widget to display additional content for the user roles that you've selected.</p>
                                <strong>Title:</strong>
                                <?php
                                $widget_title = get_option('abd_dwm_widget_title');
                                ?>				
                                <input type="text" name="widget_title" id="widget_title_input" value="<?php echo $widget_title; ?>"/>
                                <div class="spacer">&nbsp;</div>
                                <?php wp_editor(stripcslashes(get_option('abd_dwm_widget_content')), 'widget_content');
                                $checked = '';
                                ?>
                            </div>
                        </div>
                                                
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('abd_custom_widget_nonce'); ?>" />
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings" />
                    </form>       
                </div>
            </div>
        </div>
    </div>
    
<?php
}

function abd_dwm_settings_page_header() {
	global $wpdb;
	
	if(isset($_POST['submit']) && wp_verify_nonce($_POST['nonce'], 'abd_custom_widget_nonce')) {
		
		if(!empty($_POST["roles"])) {
			$roles = $_POST["roles"];
			update_option('abd_dwm_user_roles', $roles );			
		} else {
			update_option('abd_dwm_user_roles', '' );			
		}
		
		if(!empty($_POST["widgets"])) {
			$d_widgets = $_POST["widgets"];
			update_option('abd_dwm_show_widgets', $d_widgets );
		} else {
			update_option('abd_dwm_show_widgets', '' );
		}
		
		if(!empty($_POST["widget_title"])) {
			$widget_title = $_POST["widget_title"];
			update_option('abd_dwm_widget_title', $widget_title );	
		} else {
			update_option('abd_dwm_widget_title', 'Custom Widget');
		}
		
		if(!empty($_POST["widget_content"])) {
			$widget_content = $_POST["widget_content"];
			update_option('abd_dwm_widget_content', $widget_content );	
		} else {
			update_option('abd_dwm_widget_content', 'Edit this text or use html, the media uploader, and shortcodes to create your own widget.');
		}
		
	}	
}

function abd_dwm_admin_menu() {
	add_options_page('ABD Dashboard Widget Manager', 'Dashboard Manager', 'administrator', 'abd_dwm_settings', 'abd_dwm_settings_page');
}

function abd_dwm_remove_dashboard_widgets() {
	$active_widgets = get_option('abd_dwm_show_widgets');
	$dashboard_widgets = abd_dwm_get_core_dashboard_widgets();
	if(!empty($dashboard_widgets) && is_array($dashboard_widgets)) {
		foreach($dashboard_widgets as $widget_key => $widget) {			
			if(!isset($active_widgets[$widget_key])) {				
				remove_meta_box($widget_key, 'dashboard', $widget['context']);
			}			
		}		
	}
} 

function abd_dwm_dashboard_widget() {
	$widget_content = get_option('abd_dwm_widget_content');	
	if($widget_content != '') {
		echo wpautop(do_shortcode(stripcslashes($widget_content)));
		echo '<div class="abd_dwm_clear"></div>'; //clearing floating content		
	} else {		
		echo 'Missing content for widget';
	}
}

function abd_dwm_add_dashboard_widget() {
	$widget_title = get_option('abd_dwm_widget_title');
	if($widget_title == '') {
		$widget_title = 'Custom Widget';	
	}
	wp_add_dashboard_widget('abd_dwm_custom_widget', $widget_title, 'abd_dwm_dashboard_widget');
}

function abd_dwm_find_core_dashboard_widgets() {
	global $wp_meta_boxes;
	if (is_array($wp_meta_boxes['dashboard'])) {
		$avail_widgets = array();
		$normal_core_widgets = $wp_meta_boxes['dashboard']['normal']['core'];
		$side_core_widgets = $wp_meta_boxes['dashboard']['side']['core'];		  
		foreach($normal_core_widgets as $key => $widget) {
			if($key != 'abd_dwm_custom_widget') { //removing out custom widget from array to store add back on later					
				  $search = array("dashboard_", "_", "-");
				  $replace = array("", " ", " ");						
				  $name = ucwords(str_replace($search, $replace, $key));			  
				  $avail_widgets[$key] = array(
					  'name' => $name,
					  'context' => 'normal'
				  ); 
			}			 
		}
		foreach($side_core_widgets as $key => $widget) {		
			$search = array("dashboard_", "_");
			$replace = array("", " ");						
			$name = ucwords(str_replace($search, $replace, $key)); 			
			$avail_widgets[$key] = array(
				'name' => $name,
				'context' => 'side'
			); 			 		 
		} 
		if(!empty($avail_widgets)) {
			update_option('abd_dwm_avail_dashboard_widgets', $avail_widgets);			  			  				 	 
		}
	}	
}