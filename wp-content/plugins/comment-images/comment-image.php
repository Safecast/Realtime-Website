<?php
/**
 * Comment Images
 *
 * Allow your readers easily to attach an image to their comments on posts and pages.
 *
 * @wordpress-plugin
 * Plugin Name: Comment Images
 * Plugin URI:  https://wordpress.org/plugins/comment-images/
 * Description: Allow your readers easily to attach an image to their comments on posts and pages.
 * Version:     1.24.3
 * Author:      talspotim
 * Author URI: https://www.spot.im/about/
 * Text Domain: comment-image-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} // end if

require_once( plugin_dir_path( __FILE__ ) . 'class-comment-image.php' );
Comment_Image::get_instance();
