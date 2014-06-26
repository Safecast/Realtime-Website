<?php
/*
Plugin Name:Easy Maintenance Mode
Description: Easy Maintenance Mode plugin allows you to quickly create a launch / under construction page for your website. Collect E-mails and connect with users on Social Networks.
Version: 0.7
Author:Deepesh paliwal
Author URI: http://webriti.com
Plugin URI: http://webriti.com/easy-coming-soon-pro-detail-page/
*/

/*
 * Default Constants
*/
###	Run 'Install' script on plugin activation ###
register_activation_hook( __FILE__, 'DefaultScriptData' );
function DefaultScriptData()
{
	include('functions/default-data.php');
}





define( 'WBR_EM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) ); 
// Example output: /Applications/Xxampp/htdocs/wordpress/wp-content/plugins/webriti-coming-soon-pro/
define( 'WBR_EM_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); 
// Example output: http://localhost/wordpress/wp-content/plugins/webriti-coming-soon-pro/


/*
* Webriti coming soon admin menu hook add
*/
add_action('admin_menu','easy_maintenance_mode_menu');

/*
* Coming Soon menu
*
* coming soon add plugin  menu function
*
*/
function easy_maintenance_mode_menu()
{
    //plugin menu name for coming soon plugin
    $menu = add_menu_page('Easy Maintenance Mode', 'Easy Maintenance Mode','administrator', 'easy-maintenance-mode','easy_maintenance_mode_plugin_construction');

  
    
    //add hook to add styles and scripts for coming soon admin page
    add_action( 'admin_print_styles-' . $menu, 'easy_maintenance_mode_plugin_js_css' );

}



/*
* Coming Soon plugin js and css
*
* attach file css and js from plugin directory
*
*/
function easy_maintenance_mode_plugin_js_css()
{

    //enequeue scripts page for coming soon plugin admin panel
   
    wp_enqueue_script('theme-preview');
    wp_enqueue_script('media-uploads',WBR_EM_PLUGIN_URL.'js/media-upload-script.js',array('media-upload','thickbox','jquery'));
    wp_enqueue_script('wbr_soon_pro_tab',WBR_EM_PLUGIN_URL.'js/wbr_soon_pro_tab.js');
	wp_enqueue_script('my-color-picker-script', WBR_EM_PLUGIN_URL.'js/my-color-picker-script.js', array( 'wp-color-picker' ), false, true );
	
	
	
	//enequeue css page for coming soon plugin admin panel
    wp_enqueue_style('dashboard');
	wp_enqueue_style('wp-color-picker');
    wp_enqueue_style('style-option', WBR_EM_PLUGIN_URL.'css/easy_maintenance_mode_panel_style.css');
    wp_enqueue_style('my-bootstrap', WBR_EM_PLUGIN_URL.'css/easy_maintenance_mode_bootstrap.css');
	wp_enqueue_style('tooltip', WBR_EM_PLUGIN_URL.'css/easy_maintenance_mode_tooltip.css');
	wp_enqueue_style('jquery-ui', WBR_EM_PLUGIN_URL.'css/easy_maintenance_mode_jquery-ui.css');
	wp_enqueue_style('thickbox');
	 
}

//custom admin css for admin menu bar 
function load_custom_easy_maintenance_mode_wp_admin_style() 
{
        wp_register_style( 'custom_wbr_admin_css', WBR_EM_PLUGIN_URL.'/css/wbr-admin-menu.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_wbr_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_easy_maintenance_mode_wp_admin_style' );
		
//admin bar menu function hook
add_action('admin_bar_menu', 'easy_maintenance_mode_custom_menu', 1000);
function easy_maintenance_mode_custom_menu()
{
    global $wp_admin_bar;
	$value=get_option('easy_maintenance_mode_general_settings');
    if($value['status']=='0') return;
    // Add Parent Menu
	if($value['status']=='1'){
    $argsParent=array(
        'id' => 'wbrmenu',
        'title' => 'Maintenance Mode Enable',
		 //'parent' => 'top-secondary',
        'href' => '?page=easy-maintenance-mode',
		'meta'   => array( 'class' => 'wbr-admin-menu' ),
    );
    $wp_admin_bar->add_menu($argsParent);
	}
	

   
}




/*
* Webriti coming soon pro plugin admin page function to create plugin panel
*
*/
function easy_maintenance_mode_plugin_construction()
{  
$template = get_option('wbr_soon_pro_template_setting_save');
$template_value = $template['template_selected'];


 ?>

<div class="wrap" id="framework_wrap">
    <!----"content wrap is start here" ----->
    <div id="content_wrap">
        <!-- plugin panel header div start(webriti link,support,instruction etc).. -->
        <div class="cc-header">
            <h3>
                <div style="float:left;margin-top:-1%;padding-left:10px;">
                    <a href="http://www.webriti.com/" target="_new"><img src="<?php echo WBR_EM_PLUGIN_URL.'/images/png.png';?>"></a>
                </div>
                <h3 style="float:left;text-shadow: 0 1px 0 #64cceb;font-size:15px">Design</h3>
                <span style="font-size:14px;float:right;color:#fff;padding-right:10px;">
					<a class="btn btn-primary "  href="http://wordpress.org/plugins/easy-maintenance-mode-coming-soon/" target="_new"><?php _e('Rate us on Wordpress','');?></a>
                    <a class="btn btn-danger "  href="http://webriti.com/easy-coming-soon-pro-detail-page/" target="_new"><?php _e('Upgrade to Pro version','');?></a>
                    <a class="btn btn-warning"  href="http://webriti.com/easy-coming-soon-pro-detail-page/" target="_new"><?php _e('View Pro Demo','');?></a>
                   
                </span>
            </h3>
        </div>
        <!--  header div is end. -->

        <!--  plugin title header div start... -->
        <div class="cc-submenu"style="border-bottom:none;">
            <img style="float:left;padding-left:10px;" src="<?php echo WBR_EM_PLUGIN_URL.'/images/Home-icon.png'; ?>">
            <div class="plugin-title">
                <h1><?php _e('Easy Maintenance Mode','');?></h1>
            </div>
            <div class="cc-submenu-links">
			
                <a class="btn btn-success thickbox-preview" href="<?php echo home_url(); ?>?easy_maintenance_mode_preview=true&TB_iframe=true&width=500&height=532" title="<?php _e('Webriti Coming Soon Pro Preview Window','') ?>" ><?php _e('Live Preview','');?> </a>
				<input type="button" class="btn btn-inverse"  value="<?php _e('Reset Default', '');?>" onclick="set_default()"/>
            </div><!-- cc-submenu-links -->
            <div class="clear"></div>
        </div>
        <!--  plugin title header div end. -->

        <!--  content div start here.. -->
        <div id="content">
            <div id="options_tabs" class="ui-tabs ">
			
                <ul class="options_tabs ui-tabs-nav" role="tablist" id="nav">
					<!-- nav-shadow is use for menu side shadow -->
					<div id="nav-shadow"></div>
                    <li class="active">
                        <div class="arrow1"><div></div></div>
                        <a href="#" id="1"><span class="icon genral"></span><?php _e('General Settings','');?></a>
                       
                    </li>
					 <li>
                        <div class="arrow1"><div></div></div>
                        <a href="#" id="11"><span class="icon design"></span><?php _e('Design Settings','');?></a>
						<ul>
							<li><a href="#" id="ui-id-11"><?php _e('Background','');?></a></li>
                            <li><a href="#" id="ui-id-12"><?php _e('Text & Color','');?></a></li>
							
						</ul>
                    </li>
					<li>
                        <div class="arrow1"><div></div></div>
                        <a href="#" id="ui-id-3"><span class="icon template"></span><?php _e('Templates','webriti_soon_pro');?></a>
                    </li>
						
                   
					<li style="display:none">
                        <div class="arrow1"><div></div></div>
                        <a href="#" id="13"><img style="vertical-align:middle" src="<?php echo WBR_EM_PLUGIN_URL.'/images/plugin-naked.png'; ?>">&nbsp;<?php _e('Get Premium','');?></a>
						
                    </li>
					
					<li>
                        <div class="arrow1"><div></div></div>
                        <a href="#" id="ui-id-18"><span class="icon pro"></span><?php _e('Pro Features','webriti_soon_pro');?></a>
                    </li>

                   
                    
                    
                </ul>

                <!---------  menu and submenu page content add here ---------------------->
                <!------------------------------   ---------------------------------------->

                <!--content includes here-->
                
                <?php require_once('includes/includes.php');?>
                
				<br class="clear">
            </div>
        </div>
        <!--  content div is end here.. -->

        <!-- plugin panel footer div start-->
        <div class="cc-submenu" style="border-radius:0 0 12px 12px;">
		
             <div class="cc-submenu-links">
			  <div id="reset_data" class="reset_message" ><br><?php _e('Data Reset sucessfully','');?></div>
                <a class="btn btn-success thickbox-preview" href="<?php echo home_url(); ?>?easy_maintenance_mode_preview=true&TB_iframe=true&width=500&height=532"  title="<?php _e('Webriti Coming Soon pro Preview Window','') ?>" ><?php _e('Live Preview','');?> </a>
				<input type="button" class="btn btn-inverse"  value= "<?php _e('Reset Default', '');?>" onclick="set_default()"/>
             </div><!-- cc-submenu-links -->
            <div class="clear"></div>
        </div>
        <!-- plugin panel footer div end here-->

    </div>
    <!----"content wrap is end here" ----->
</div>
<?php
 
}
/*
 *coming soon plugin admin page function End...
 */
 
/*coming soon plugin admin page function End... */
 require_once('functions/ajax-post.php');
  require_once('functions/reset-post.php');
 
/*template redirect */
 require_once('redirect.php');
 
?>