<?php
/**
 * Safecast update sensors measurements functions
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

function updateSensorsMeasurements()
{
	$args                = array(
	  'post_type'        => 'sensors',
	  'posts_per_page'   => -1);
	$query               = new WP_Query($args);

	if ($query->have_posts()) {
		/* Retrieving information from the API */
		/* Preparing the Query (time params needs to be in UTC) */
		date_default_timezone_set("UTC");
		
		$before     = time();
		$after      = $before - 5 * 60;
		$uri 		= sprintf(API_URI_FORMAT,
			urlencode(strftime("%Y/%m/%d %H:%M:%S", $after)),
			urlencode(strftime("%Y/%m/%d %H:%M:%S", $before)));

		/* Querying the API */
		$response     = \Httpful\Request::get($uri)->send();
		$measurements = $response->body;

		if ($measurements) {
			/* Update each sensor if we find them in the measurements */
			while ($query->have_posts()) {
				$query->the_post();
			
				$id = get_post_meta(get_the_ID(), 'sensor_id', true);
			
				if ($id) {
					$post = get_post(get_the_ID());
					
					foreach ($measurements as $measurement)  {
						if ($id == $measurement->device_id) {
							updateSensorPost(get_the_ID(), $measurement);
							break;
						}
					}
				}
			}
		}
	}
	wp_reset_query();
}