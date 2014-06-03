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

/* Loading functions */
require_once('updateSensorsMeasurements.php');
require_once('updateSensorsMap.php');

/* Safecast API URL format */
define('API_URI_FORMAT', 'https://api.safecast.org/en-US/measurements?captured_after="%s"&captured_before="%s"&format=json');

try {
	/* Update sensors measurements in Wordpress */
	updateSensorsMeasurements();
	
	/* Creates XML files and the KML file for MAP */
	updateSensorsMap();
} catch (Exception $e) {
	printf("Error : %s\n", $e->getMessage());
}
