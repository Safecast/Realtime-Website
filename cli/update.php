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

define('USAGE', sprintf("Usage: $ php [-v] %s\n", $argv[0]));

/* Checking parameters */
$usageError	= false;
if ($argc > 2) {
	$usageError	= true;
} else if ($argc == 2) {
	$options	= $argv[1];
	if (strlen($options) > 1 && $options[0] == '-') {
		for ($i = 1; $i < strlen($options); $i++) {
			switch ($options[$i]) {
			case 'v':
				defined('VERBOSE') || define('VERBOSE', true);
				break;
				echo sprintf("Unknown option -%s\n", $options[$i]);
				$usageError = true;
				break;
			}
		}
	} else {
		$usageError = true;
	}
}

if ($usageError) {
	echo USAGE;
	return -1;
}

/* Default verbose value */
defined('VERBOSE') || define('VERBOSE', false);

/* Defining environmental variables necessary to load WP */
define('DOING_AJAX', 	true);
define('WP_USE_THEMES',	false);

$_SERVER          = array(
	'HTTP_HOST'      => 'realtime.safecast.org',
	'SERVER_NAME'    => 'realtime.safecast.org',
	'REQUEST_URI'    => '/',
	'REQUEST_METHOD' => 'GET',
	'REMOTE_ADDR'    =>	'127.0.0.1',
	'DOCUMENT_ROOT'  => dirname(dirname(__FILE__)).'/site',
	'PHP_SELF'       => 'index.php',
	'SCRIPT_NAME'    => 'index.php',
);

/* Loading WP to enable post update */
require_once(dirname(dirname(__FILE__)).'/site/wordpress/wp-load.php');

/* Loading composer requirements */
require_once(dirname(__FILE__).'/vendor/autoload.php');

/* Loading functions */
require_once(dirname(__FILE__).'/updateSensorsMeasurements.php');
require_once(dirname(__FILE__).'/updateSensorsPlots.php');
require_once(dirname(__FILE__).'/updateSensorsListFile.php');
require_once(dirname(__FILE__).'/updateSensorsListCSV.php');

try {
	/* Update sensors measurements in Wordpress */
	updateSensorsMeasurements();

	/* Overwrite the Makefile.config file */

	updateSensorsPlots();

	/* Update the JSON sensor list file */

       updateSensorsListFile();

	/* Update the  CSV file for  DRE2CPM   */

	updateSensorsListCSV();

} catch (Exception $e) {
	printf("Error : %s\n", $e->getMessage());
}
