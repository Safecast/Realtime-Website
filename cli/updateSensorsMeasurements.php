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
define('API_URI_MAXIMUM_FORMAT', 'https://api.safecast.org/en-US/measurements.json?captured_after=&captured_before=&device_id=%s&unit=cpm&per_page=1&order=value+desc');
define('API_URI_LAST_FORMAT', 'https://api.safecast.org/en-US/measurements.json?device_id=%s&unit=cpm&per_page=1&order=captured_at+desc');

function updateSensorInformation($postId, $information) {
	update_post_meta($postId, 'sensor_type', $information->sensor);
	update_post_meta($postId, 'sensor_manufacturer', $information->manufacturer);
	update_post_meta($postId, 'sensor_model', $information->model);
}

//sensor_measurement_max
//sensor_measurement_last
function updateSensorMeasurement($postId, $uri, $measurementType) {
	/* Querying the API */
	if (VERBOSE) {
		printf("> Querying the API with URI %s\n", $uri);
	}

	$response     = \Httpful\Request::get($uri)->send();
	$measurements = $response->body;

	if (!is_array($measurements) || !count($measurements)) {
		if (VERBOSE) {
			printf("> No measurement\n");
		}

		return;
	}

	if (VERBOSE) {
		printf("> Measurement found\n");
	}

	$measurement		= current($measurements);
	$cpm						= (float)$measurement->value;
	$sensorType			= get_post_meta(get_the_ID(), 'sensor_type', TRUE);

	/* Based on tube type the conversion from cpm differs */
    if (strpos($sensorType, "LND712") !== false || strpos($sensorType, "LND 712") !== false || strpos($sensorType, "LND_7128") !== false || strpos($sensorType, "LND_7128ec") !== false || strpos($sensorType, "lnd_7128") !== false || strpos($sensorType, "lnd_7128ec") !== false) {
		$usvh = number_format(($cpm / 120.5), 3);
	}elseif (strpos($sensorType, "LND78017") !== false || strpos($sensorType, "LND 78017") !== false) {
		$usvh = number_format(($cpm / 960), 3);
	} else {
		$usvh = number_format(($cpm / 334), 3);
	}

	update_post_meta($postId, $measurementType.'_usvh', $usvh);
	update_post_meta($postId, $measurementType.'_cpm', $cpm);
	update_post_meta($postId, $measurementType.'_gmt', $measurement->captured_at);
	update_post_meta($postId, $measurementType.'_latitude', $measurement->latitude);
	update_post_meta($postId, $measurementType.'_longitude', $measurement->longitude);
	update_post_meta($postId, $measurementType.'_id', $measurement->id);
	update_post_meta($postId, $measurementType.'_user_id', $measurement->user_id);
}

function getSensorType($postId, $sensorId) {
	$type = get_post_meta($postId, 'sensor_type', TRUE);

	if (!$type) {
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

function updateSensorsMeasurements() {
	$args                = array(
	  'post_type'        => 'sensors',
	  'posts_per_page'   => -1);
	$query               = new WP_Query($args);

	if ($query->have_posts()) {
		/* Update each sensor if we find them in the measurements */
		while ($query->have_posts()) {
			$query->the_post();

			$sensorId = get_post_meta(get_the_ID(), 'sensor_id', TRUE);
			$reset    = RESET;

			if ($sensorId) {
				try {
					if (VERBOSE) {
						printf("Retrieving measurements for device %s\n", $sensorId);
					}

					if (getSensorType(get_the_ID(), $sensorId)) {
						updateSensorMeasurement(get_the_ID(),
							sprintf(API_URI_LAST_FORMAT, $sensorId),
							'sensor_measurement_last');

						updateSensorMeasurement(get_the_ID(),
							sprintf(API_URI_MAXIMUM_FORMAT, $sensorId),
							'sensor_measurement_max');
					}
				} catch (\Exception $e) {
					printf("Error while updating device %s : %s\n", $sensorId, $e->getMessage());
				}
			}
		}
	}

	wp_reset_query();
}
