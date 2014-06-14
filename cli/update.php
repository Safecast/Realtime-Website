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
if ($argc > 2
|| ($argc == 2 && $argv[1] != '-v')) {
	printf("Usage: $ php [-v] %s\n", $argv[0]);
	die;
}

/* Is verbose mode ON? */
define('IS_VERBOSE', $argc == 2);

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
require_once(dirname(dirname(__FILE__)).'/site/wordpress/wp-load.php');

/* Loading composer requirements */
require_once(dirname(__FILE__).'/vendor/autoload.php');

/* Loading functions */
require_once(dirname(__FILE__).'/updateSensorsMeasurements.php');
require_once(dirname(__FILE__).'/updateSensorsMap.php');
require_once(dirname(__FILE__).'/updateSensorsPlots.php');

try {
	/* Update sensors measurements in Wordpress */
	updateSensorsMeasurements();
	
	/* Creates XML files and the KML file for MAP */
	updateSensorsMap();
	
	/* Overwrite the Makefile.config file */
	updateSensorsPlots();
} catch (Exception $e) {
	printf("Error : %s\n", $e->getMessage());
}
