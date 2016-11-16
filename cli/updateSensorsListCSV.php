<?php
/**
 * Safecast sensors CSV generation script
 *
 * PHP version 5.X
 *
 * @package    Safecast
 * @author     Marc Rollin <rollin.marc@gmail.com>
 *             Modified by Rob Oudendijk (rob@safecast.org)
 * @copyright  2015/16 Safecast
 */

function getListFilehandleCSV() {
    $uploadDir  = wp_upload_dir();
    $filename   = $uploadDir['basedir'].sprintf("/devices.csv", $sensorId);
    $filehandle = fopen($filename, 'w');

    return $filehandle;
}

function updateSensorsListCSV() {
    $args              = array(
      'post_type'      => 'sensors',
    'meta_query' => array(
              'relation' => 'OR',
              array(
                  'key'=>'retired_sensor',
                  'compare' => 'NOT EXISTS'
              ),
              array(
                      'key'     => 'retired_sensor',
                      'value'   => 'true',
                      'compare' => 'NOT LIKE'
              	  )
          	), // End of meta_query

      'posts_per_page' => -1);
    $query             = new WP_Query($args);
    $sensorList        = array();

    if ($query->have_posts()) {
        /* Update each sensor if we find them in the measurements */
        while ($query->have_posts()) {
            $query->the_post();
            $sensorId = get_post_meta(get_the_ID(), 'sensor_id', TRUE);
	    $sensorGroupId = substr($sensorId, 0, -1);
            if ($sensorId) {
                $measurementMsv       = get_post_meta(get_the_ID(), 'sensor_measurement_last_usvh', TRUE);
                $measurementCpm       = get_post_meta(get_the_ID(), 'sensor_measurement_last_cpm', TRUE);
                $measurementGmt       = get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', TRUE);
                $measurementLatitude  = get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude', TRUE);
                $measurementLongitude = get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', TRUE);
                $sensorName              = get_the_title(get_the_ID());
                $articleUrl           = get_permalink(get_the_ID());
                $sensorType              = get_post_meta(get_the_ID(), 'sensor_type', TRUE);
                $DRE2CPM              ="334";

                /* Based on tube type the conversion from cpm differs */
                if (strpos($sensorType, "LND712") !== false || strpos($sensorType, "LND 712") !== false) {
                    $DRE2CPM = "120.5";
                }elseif (strpos($sensorType, "LND78017") !== false || strpos($sensorType, "LND 78017") !== false) {
                    $DRE2CPM = "960";
                } else {
                    $DRE2CPM = "334";
                }

                $sensorList    []= array(
                    $sensorId,
                    $sensorGroupId,
                    $DRE2CPM,
                    "fixed_sensor",
                    $sensorType
                );
            }
        }
    }

    wp_reset_query();

    $handleCSV        = getListFilehandleCSV();

       // CSV formating
        foreach ($sensorList as $fields) {
            fputcsv($handleCSV, $fields);
        }

    fclose($handleCSV);
}

