<?php
/**
 * Safecast sensors JSON generation script
 *
 * PHP version 5.X
 *
 * @package    Safecast
 * @author     Marc Rollin <rollin.marc@gmail.com>
 *             Modified by Rob Oudendijk (rob@safecast.org)
 * @copyright  2015/16 Safecast
 */

define("TIME_OFFLINE_SHORT",     660);
define("TIME_OFFLINE_LONG",     1560);


function getListFilehandle() {
    $uploadDir  = wp_upload_dir();
    $filename   = $uploadDir['basedir'].sprintf("/devices.json", $sensorId);
    $filehandle = fopen($filename, 'w');

    return $filehandle;
}

function updateSensorsListFile() {
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

            if ($sensorId) {
                $measurementMsv       = get_post_meta(get_the_ID(), 'sensor_measurement_last_usvh', TRUE);
                $measurementCpm       = get_post_meta(get_the_ID(), 'sensor_measurement_last_cpm', TRUE);
                $measurementGmt       = get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', TRUE);
                $measurementLatitude  = get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude', TRUE);
                $measurementLongitude = get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', TRUE);
                $sensorName              = get_the_title(get_the_ID());
                $articleUrl           = get_permalink(get_the_ID());
                $sensorType              = get_post_meta(get_the_ID(), 'sensor_type', TRUE);
                $DRE2CPM              = "334";

                    $timestamp   = strtotime($measurementGmt);
                    $timeAgo     = $timestamp ? human_time_diff($timestamp).($lang == 'jp' ? '前' : ' ago') : '';
                    $timeSince   = time() - $timestamp;
                    $status      = ($lang == 'jp' ? 'オンライン' : 'Online');
                    $statusValue = 0;

                        if ($timeSince >= TIME_OFFLINE_LONG) {
                            $status      = ($lang == 'jp' ? 'オフライン（長）' : 'Offline long');
                            $statusClass = 'danger';
                            $statusValue = 2;
                        }  else if ($timeSince >= TIME_OFFLINE_SHORT) {
                            $status      = ($lang == 'jp' ? 'オフライン（短）' : 'Offline short');
                            $statusClass = 'warning';
                            $statusValue = 1;

            }

                /* Based on tube type the conversion from cpm differs */
                if (strpos($sensorType, "LND712") !== false || strpos($sensorType, "LND 712") !== false) {
                    $DRE2CPM="120.5";
                }elseif (strpos($sensorType, "LND78017") !== false || strpos($sensorType, "LND 78017") !== false) {
                    $DRE2CPM="960";
                } else {
                    $DRE2CPM="334";
                }

                $sensorList    []= array(
                    "id"     => $sensorId,
                      "lat"     => $measurementLatitude,
                    "lon"     => $measurementLongitude,
                     "location" => $sensorName,
                    "updated" => $measurementGmt,
                    "usvh" => $measurementMsv,
                    "cpm" => $measurementCpm,
                    "article_url" => $articleUrl,
                    "chart_url" => sprintf("http://rt.safecast.org/plots/out/%d_small.png", $sensorId), // Hardcoded
                    "chart_width" => 480, // Hardcoded
                    "chart_height" => 200, // Hardcoded
                        "Status" => $status,
                    "DRE2CPM" => $DRE2CPM
                );
            }
        }
    }

    wp_reset_query();

    $handle        = getListFilehandle();
    $content     = json_encode($sensorList);

    fwrite($handle, $content);

    fclose($handle);
}

