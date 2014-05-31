<?php
/**
 * Safecast update sensors'measurement cli
 *
 * PHP version 5.X
 *
 * @package    Safecast
 * @author     Marc Rollin <rollin.marc@gmail.com>
 * @copyright  2014 Safecast
 */

/* Checking parameters */
if ($argc != 1) {
	printf("Usage: $ php %s\n", $argv[0]);
	die;
}

/* Defining environmental variables necessary to load WP */
define('DOING_AJAX', 	true);
define('WP_USE_THEMES',	false);

$_SERVER             = array(
	'HTTP_HOST'      => 'realtime.safecast.com',
	'SERVER_NAME'    => 'realtime.safecast.com',
	'REQUEST_URI'    => '/',
	'REQUEST_METHOD' => 'GET',
	'REMOTE_ADDR'    =>	'127.0.0.1'
);

/* Loading WP to enable post update */
require_once('../wordpress/wp-load.php');

/* Loading composer requirements */
require_once('vendor/autoload.php');

/* Loading function to updatePost */
require_once('updateSensorPost.php');

/* Safecast API URL format */
define('API_URI_FORMAT', 'https://api.safecast.org/en-US/measurements?captured_after="%s"&captured_before="%s"&format=json');

try {
	/* Retrieve all sensors posts */
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

} catch (Exception $e) {
	printf("Error : %s\n", $e->getMessage());
}
