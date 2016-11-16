<?php
/*
Plugin Name: ABD Dashboard Widget Manager
Plugin URI: http://aaronbday.com/
Author: ABD Web Design
Author URI: http://aaronbday.com/
Description: This plugin gives you an easy way to customize your WordPress administrator dashboard. Simply select which admin widgets you'd like to show and for which user roles. You also get the option to add a new admin widget: just give it a title and enter your content into the WYSIWYG editor. 
Version: 1.1
*/

require_once(WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) . "/functions.php");

add_action('init', 'abd_dwm', 0);
register_deactivation_hook( __FILE__, 'abd_dwm_uninstall' );
register_activation_hook(__FILE__,'abd_dwm_install');

