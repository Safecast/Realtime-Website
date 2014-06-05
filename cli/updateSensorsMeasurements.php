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

/* Safecast API URL format */
define('API_URI_CREATE_FORMAT', 'https://api.safecast.org/en-US/measurements.json?device_id=%s');
define('API_URI_UPDATE_FORMAT', 'https://api.safecast.org/en-US/measurements.json?device_id=%s&since="%s"');

function updateSensorPost($postId, $measurement)
{
	$cpm            = (float)$measurement->value;
	$svt            = number_format(($cpm / 334), 3);
	$maxCpm         = (float)get_post_meta($postId, 'sensor_last_cpm', TRUE);
	$timeDifference = strtotime($measurement->captured_at)
		- strtotime(get_post_meta($postId, 'sensor_last_gmt', TRUE));
	
	if ($timeDifference > 0) {
		update_post_meta($postId, 'sensor_last_usieverts', $svt);
		update_post_meta($postId, 'sensor_last_cpm', $cpm);
		update_post_meta($postId, 'sensor_last_gmt', $measurement->captured_at);
		update_post_meta($postId, 'sensor_last_latitude', $measurement->latitude);
		update_post_meta($postId, 'sensor_last_longitude', $measurement->longitude);
	}
	
	if ($cpm >= $maxCpm) {
		update_post_meta($postId, 'sensor_max_usieverts', $svt);
		update_post_meta($postId, 'sensor_max_cpm', $cpm);
		update_post_meta($postId, 'sensor_max_gmt', $measurement->captured_at);
		update_post_meta($postId, 'sensor_max_latitude', $measurement->latitude);
		update_post_meta($postId, 'sensor_max_longitude', $measurement->longitude);
	}
}

function updateSensorsMeasurements()
{
	$args                = array(
	  'post_type'        => 'sensors',
	  'posts_per_page'   => -1);
	$query               = new WP_Query($args);

	if ($query->have_posts()) {
		/* Update each sensor if we find them in the measurements */
		while ($query->have_posts()) {
			$query->the_post();
			
			$id = get_post_meta(get_the_ID(), 'sensor_id', TRUE);
			
			if ($id) {
				if (IS_VERBOSE) {
					printf("Retrieving measurements for device %s\n", $id);
				}
				
				$sinceTimestamp = strtotime(get_post_meta(get_the_ID(), 'sensor_last_gmt', TRUE)) + 2;
				$since          = gmdate("Y-m-d\TH:i:s\Z", $sinceTimestamp);
			
				/* Preparing the query for the API */
				if ($since) {
					$uri	= sprintf(API_URI_UPDATE_FORMAT, $id, $since);
				} else {
					$uri	= sprintf(API_URI_CREATE_FORMAT, $id);
				}
			
				/* Querying the API */
				$response     = \Httpful\Request::get($uri)->send();
				$measurements = $response->body;
			
				if (is_array($measurements) && count($measurements)) {
					if (IS_VERBOSE) {
						printf("> %d new measurement(s) since %s\n", count($measurements), $since);
					}
					
					foreach ($measurements as $measurement)  {
						updateSensorPost(get_the_ID(), $measurement);
					}
				} else {
					if (IS_VERBOSE) {
						printf("> No new measurement since %s\n", $since);
					}
				}
			}
		}
	}

	wp_reset_query();
}
