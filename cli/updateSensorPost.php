<?php
/**
 * Safecast update sensor post function
 *
 * PHP version 5.X
 *
 * @package    Safecast
 * @author     Marc Rollin <rollin.marc@gmail.com>
 * @copyright  2014 Safecast
 */

function updateSensorPost($postId, $measurement)
{
	$cpm	= (float)$measurement->value;
	$maxCpm	= (float)get_post_meta($postId, 'sensor_last_cpm', true);
	
	update_post_meta($postId, 'sensor_last_usieverts', number_format(($cpm / 334), 3));
	update_post_meta($postId, 'sensor_last_cpm', $cpm);
	update_post_meta($postId, 'sensor_last_gmt', $measurement->captured_at);
	update_post_meta($postId, 'sensor_last_latitude', $measurement->latitude);
	update_post_meta($postId, 'sensor_last_longitude', $measurement->longitude);
	
	if ($cpm >= $maxCpm) {
		update_post_meta($postId, 'sensor_max_usieverts', get_post_meta($postId, 'sensor_last_usieverts', true));
		update_post_meta($postId, 'sensor_max_cpm', get_post_meta($postId, 'sensor_last_cpm', true));
		update_post_meta($postId, 'sensor_max_gmt', get_post_meta($postId, 'sensor_last_gmt', true));
		update_post_meta($postId, 'sensor_max_latitude', get_post_meta($postId, 'sensor_last_latitude', true));
		update_post_meta($postId, 'sensor_max_longitude', get_post_meta($postId, 'sensor_last_longitude', true));
	}
}