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
define('API_URI_INFO_FORMAT', 'https://api.safecast.org/devices/%s.json');
define('API_URI_CREATE_FORMAT', 'https://api.safecast.org/en-US/measurements.json?device_id=%s&per_page=1000&order=captured_at+asc');
define('API_URI_UPDATE_FORMAT', 'https://api.safecast.org/en-US/measurements.json?device_id=%s&per_page=1000&captured_after="%s"&order=captured_at+asc');


function updateSensorInformation($postId, $information) {
	update_post_meta($postId, 'sensor_type', $information->sensor);
	update_post_meta($postId, 'sensor_manufacturer', $information->manufacturer);
	update_post_meta($postId, 'sensor_model', $information->model);
}

function updateSensorMeasurement($postId, $measurement) {
	$cpm            = (float)$measurement->value;
	$maxCpm         = (float)get_post_meta($postId, 'sensor_measurement_max_cpm', TRUE);
	$sensorType     = get_post_meta(get_the_ID(), 'sensor_type', TRUE);
	
	/* Based on tube type the conversion from cpm differs */
	if (strpos($sensorType, "LND712") !== false ||
		strpos($sensorType, "LND 712") !== false) {
		$usvh = number_format(($cpm / 120.5), 3);
	} else {
		$usvh = number_format(($cpm / 334), 3);
	}

	update_post_meta($postId, 'sensor_measurement_last_usvh', $usvh);
	update_post_meta($postId, 'sensor_measurement_last_cpm', $cpm);
	update_post_meta($postId, 'sensor_measurement_last_gmt', $measurement->captured_at);
	update_post_meta($postId, 'sensor_measurement_last_latitude', $measurement->latitude);
	update_post_meta($postId, 'sensor_measurement_last_longitude', $measurement->longitude);
	update_post_meta($postId, 'sensor_measurement_last_id', $measurement->id);
	update_post_meta($postId, 'sensor_measurement_last_user_id', $measurement->user_id);
	
	if ($cpm >= $maxCpm) {
		update_post_meta($postId, 'sensor_measurement_max_usvh', $usvh);
		update_post_meta($postId, 'sensor_measurement_max_cpm', $cpm);
		update_post_meta($postId, 'sensor_measurement_max_gmt', $measurement->captured_at);
		update_post_meta($postId, 'sensor_measurement_max_latitude', $measurement->latitude);
		update_post_meta($postId, 'sensor_measurement_max_longitude', $measurement->longitude);
		update_post_meta($postId, 'sensor_measurement_max_id', $measurement->id);
		update_post_meta($postId, 'sensor_measurement_max_user_id', $measurement->user_id);
	}
}

function getSensorType($postId, $sensorId, $reset) {
	$type = get_post_meta($postId, 'sensor_type', TRUE);
	
	if (!$type || $reset) {
		if (VERBOSE) {
			printf("Retrieving information for device %s\n", $sensorId);
		}
		$uri	= sprintf(API_URI_INFO_FORMAT, $sensorId);
		
		/* Querying the API */
		$response    = \Httpful\Request::get($uri)->send();
		$information = $response->body;
		
		updateSensorInformation($postId, $information);
		$type = get_post_meta($postId, 'sensor_type', TRUE);
	}
	
	return $type;
}

function logMeasurement($filehandle, $postId) {
	$sensorId             = get_post_meta(get_the_ID(), 'sensor_id', TRUE);
	$sensorType           = get_post_meta(get_the_ID(), 'sensor_type', TRUE);
	$sensorManufacturer   = get_post_meta(get_the_ID(), 'sensor_manufacturer', TRUE);
	$sensorModel          = get_post_meta(get_the_ID(), 'sensor_model', TRUE);
	$measurementId        = get_post_meta(get_the_ID(), 'sensor_measurement_last_id', TRUE);
	$measurementUserId    = get_post_meta(get_the_ID(), 'sensor_measurement_last_user_id', TRUE);
	$measurementMsv       = get_post_meta(get_the_ID(), 'sensor_measurement_last_usvh', TRUE);
	$measurementCpm       = get_post_meta(get_the_ID(), 'sensor_measurement_last_cpm', TRUE);
	$measurementGmt       = get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', TRUE);
	$measurementLatitude  = get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude', TRUE);
	$measurementLongitude = get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', TRUE);
	$fields               = array($measurementId, $measurementUserId,
		$measurementMsv, $measurementCpm, $measurementGmt,
		$measurementLatitude, $measurementLongitude, $sensorId,
		$sensorType, $sensorManufacturer, $sensorModel);
	
	fputcsv($filehandle, $fields);
}

function getLogFilehandle($sensorId, $reset) {
	$uploadDir  = wp_upload_dir();
	$filename   = $uploadDir['basedir'].sprintf("/measurements/device_%s.csv", $sensorId);
	$filehandle = fopen($filename, $reset ? 'w' : 'a');
	
	if ($reset || (0 == filesize($filename))) {
		fputcsv($filehandle, array('id', 'userId', 'μSv/h', 'cpm',
			'capturedAt', 'latitude', 'longitude',
			'device', 'type', 'manufacturer', 'model'));
	}
	
	return $filehandle;
}

function updateSensorsMeasurements() {
	$args                = array(
	  'post_type'        => 'sensors',
	  'posts_per_page'   => -1);
	$query               = new WP_Query($args);

	if ($query->have_posts()) {
		if (CLEAN_MUTEX) {
			/* Update each sensor if we find them in the measurements */
			while ($query->have_posts()) {
				$query->the_post();
				
				if (VERBOSE) {
					printf("Cleaned mutex for post %s\n", get_the_ID());
				}
				update_post_meta(get_the_ID(), 'sensor_mutex', FALSE);
			}
		}
		
		/* Update each sensor if we find them in the measurements */
		while ($query->have_posts()) {
			$query->the_post();
			
			$sensorId = get_post_meta(get_the_ID(), 'sensor_id', TRUE);
			$reset    = RESET;
			
			if ($sensorId) {
				$mutex = get_post_meta(get_the_ID(), 'sensor_mutex', TRUE);
				
				if (!$mutex) {
					/* Blocking the sensor */
					update_post_meta(get_the_ID(), 'sensor_mutex', TRUE);
					
					try {
						if (getSensorType(get_the_ID(), $sensorId, $reset)) {
							if (VERBOSE) {
								printf("Retrieving measurements for device %s\n", $sensorId);
							}
							
							/* Iterate while there is measurements */
							while (42) {
								$sinceTimestamp = strtotime(get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', TRUE));
								
								/* Preparing the query for the API */
								if ($reset || !$sinceTimestamp) {
									$since = 'inception';
									$uri   = sprintf(API_URI_CREATE_FORMAT, $sensorId);
								} else {
									$since = gmdate("Y-m-d\TH:i:s\Z", $sinceTimestamp);
									$uri   = sprintf(API_URI_UPDATE_FORMAT, $sensorId, $since);
								}
								if (VERBOSE) {
									printf("> Quering the API for new measurements since %s\n", $since);
								}
								
								/* Querying the API */
								$response     = \Httpful\Request::get($uri)->send();
								$measurements = $response->body;
								
								if (is_array($measurements) && count($measurements)) {
									if (VERBOSE) {
										printf("> %d new measurement(s)\n", count($measurements));
									}
									
									$filehandle	= getLogFilehandle($sensorId, $reset);
									
									foreach ($measurements as $measurement) {
										updateSensorMeasurement(get_the_ID(), $measurement);
										logMeasurement($filehandle, get_the_ID());
										
										$reset	= false;
									}
									
									fclose($filehandle);
								} else {
									if (VERBOSE) {
										printf("> No new measurement\n");
									}
									
									break;
								}
							}
						}
					} catch (\Exception $e) {
						printf("Error while updating device %s\n", $sensorId);
					}
					
					/* Freeing the sensor */
					update_post_meta(get_the_ID(), 'sensor_mutex', FALSE);
				} else {
					if (VERBOSE) {
						printf("Device %s is already beeing processed\n", $sensorId);
					}
				}
			}
		}
	}

	wp_reset_query();
}
