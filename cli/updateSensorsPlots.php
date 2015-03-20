<?php
/**
 * Safecast update sensors plots generation Makefile.config
 *
 * PHP version 5.X
 *
 * @package    Safecast
 * @author     Marc Rollin <rollin.marc@gmail.com>
 * @copyright  2014 Safecast
 */

function updateSensorsPlots()
{
	$args              = array(
	  'post_type'      => 'sensors',
	  'posts_per_page' => -1);
	$query             = new WP_Query($args);
	$sensorList        = '';

	if ($query->have_posts()) {
		/* Update each sensor if we find them in the measurements */
		while ($query->have_posts()) {
			$query->the_post();

			$id = get_post_meta(get_the_ID(), 'sensor_id', TRUE);

			if ($id) {
				$sensorList	.= " $id";
			}
		}
	}

	$configContent = "LIVE_SENSORS := $sensorList

# syntax: option to `find` command
MAX_AGE_TO_CACHE := -mmin +30

# syntax: option to `date` command
# NOTE: Do NOT forget the single quotes below!
PLOT_SINCE := '30 days ago'

# in px
CONFIG_WIDTH_SMALL := 480
CONFIG_HEIGHT_SMALL := 200

CONFIG_WIDTH_BIG := 1024
CONFIG_HEIGHT_BIG := 600

CONFIG_WIDTH_ALL := 1024
CONFIG_HEIGHT_ALL := 768

# long and short version of graph timezone (server local TZ does NOT matter)
CONFIG_TIMEZONE := 'America/New_York'
CONFIG_TZ := 'EDT'

#PUBLISH_CMD := rsync -HavPS --exclude=/.run --delete-excluded --delete out/ WEBSERVER:DIRECTORY/
#VIEW_CMD := firefox out/index.html";

	file_put_contents(dirname(dirname(__FILE__)).'/../plots/Makefile.config', $configContent);

	wp_reset_query();
}
