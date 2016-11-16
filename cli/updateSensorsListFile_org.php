<?php
/**
 * * Safecast sensors JSON generation script
 * PHP version 5.X
 *
 * @package    Safecast
 * @author     Marc Rollin <rollin.marc@gmail.com>
 * @copyright  2015 Safecast
 */

function getListFilehandle() {
	$uploadDir  = wp_upload_dir();
	$filename   = $uploadDir['basedir'].sprintf("/devices.json", $sensorId);
	$filehandle = fopen($filename, 'w');
	
	return $filehandle;
}

function updateSensorsListFile() {
	$args              = array(
	  'post_type'      => 'sensors',
	  'posts_per_page' => -1);
	$query             = new WP_Query($args);
	$sensorList        = array();

	if ($query->have_posts()) {
		/* Update each sensor if we find them in the measurements */
		while ($query->have_posts()) {
			$query->the_post();
			
			$sensorId = get_post_meta(get_the_ID(), 'sensor_id', TRUE);

			if ($sensorId) {
				$measurementMsv       = get_post_meta(get_the_ID(), 'sensor_measurement_last_usvh', TRUE);
				$measurementCpm       = get_post_meta(get_the_ID(), 'sensor_measurement_last_cpm', TRUE);
				$measurementGmt       = get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', TRUE);
				$measurementLatitude  = get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude', TRUE);
				$measurementLongitude = get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', TRUE);
				$sensorName			  = get_the_title(get_the_ID());
				$articleUrl 		  = get_permalink(get_the_ID());

				$sensorList	[]= array(
					"id" 	=> $sensorId,
	      			"lat" 	=> $measurementLatitude,
		        	"lon" 	=> $measurementLongitude,
	 				"location" => $sensorName,
					"updated" => $measurementGmt,
		        	"usvh" => $measurementMsv,
			        "cpm" => $measurementCpm,
			        "article_url" => $articleUrl,
					"chart_url" => sprintf("http://rt.safecast.org/plots/%d_small.png", $sensorId), // Hardcoded
					"chart_width" => 480, // Hardcoded
					"chart_height" => 200 // Hardcoded
				);
			}
		}
	}

	wp_reset_query();

	$handle		= getListFilehandle();
	$content 	= json_encode($sensorList);

	fwrite($handle, $content);

	fclose($handle);
}
