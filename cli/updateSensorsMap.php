<?php
/**
 * Safecast update sensors map
 *
 * PHP version 5.X
 *
 * @package    Safecast
 * @author     Marc Rollin <rollin.marc@gmail.com>
 * @copyright  2014 Safecast
 */

function updateSensorsMap()
{
	$args              = array(
	  'post_type'      => 'sensors',
	  'posts_per_page' => -1);
	$query             = new WP_Query($args);
	
	// Defining XML format and filenames
	$xmlFilename       = get_template_directory().'/assets/map/device_%s.xml';
	$xmlURI            = get_template_directory_uri().'/assets/map/device_%s.xml';
	$xmlTemplate       = '<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss">
	<updated>%s</updated>
	<title>Safecast Device %s RSS feed</title>
	<subtitle>Real-time radiation measured from device</subtitle>
	<link href="%s"/>
	<author><name>Safecast</name></author>
	<id>%s</id>
	<icon>http://blog.safecast.org/favicon.ico</icon>
	<entry><id>%s</id><title>%s CPM</title><updated>%s</updated><georss:point>%s %s</georss:point><summary>%s CPM</summary></entry>
	<entry><id>%s</id><title>%s µSv/h</title><updated>%s</updated><georss:point>%s %s</georss:point><summary>%0.3f µSv/h</summary></entry>
</feed>';

	// Defining KML format and filenames
	$kmlFilename       = get_template_directory().'/assets/map/devices.kml';
	$kmlTemplate       = '<?xml version="1.0" encoding="UTF-8"?>
<kml xmlns="http://www.opengis.net/kml/2.2">
	<Folder>
		<name>Devices Safecast</name>
		<visibility>1</visibility>
		<open>1</open>
		<description>Safecast Fixed devices</description>%s
	</Folder>
</kml>';
	$kmlEntry         = '
		<NetworkLink>
			<name>Device %s</name>
			<visibility>0</visibility>
			<open>0</open>
			<description>Device %s</description>
			<refreshVisibility>0</refreshVisibility>
			<flyToView>1</flyToView>
			<Link>
				<href>%s</href>
			</Link>
		</NetworkLink>';
	$kmlContent       = '';

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			
			$id = get_post_meta(get_the_ID(), 'sensor_id', true);
			
			if ($id) {
				$permalink  = get_permalink(get_the_ID());
				$cpm        = get_post_meta(get_the_ID(), 'sensor_last_cpm', true);
				$usieverts  = get_post_meta(get_the_ID(), 'sensor_last_usieverts', true);
				$gmt        = get_post_meta(get_the_ID(), 'sensor_last_gmt', true);
				$latitude   = get_post_meta(get_the_ID(), 'sensor_max_latitude', true);
				$longitude  = get_post_meta(get_the_ID(), 'sensor_max_longitude', true);
				$xmlContent = sprintf($xmlTemplate,
				$gmt, $id, $permalink, $id,
				// @todo replace last $id by uuid.uuid5(uuid.NAMESPACE_DNS, name))
				$id, $cpm, $gmt, $latitude, $longitude, $cpm,
				$id, $usieverts, $gmt, $latitude, $longitude, $usieverts);
				
				file_put_contents(sprintf($xmlFilename, $id), $xmlContent);
				
				/* Create XML file for each sensor */
				$kmlContent .= sprintf($kmlEntry, $id, $id, sprintf($xmlURI, $id));
			}
		}
		
		/* Create a uniq KML file containing a link to each sensor's xml file */
		file_put_contents($kmlFilename, sprintf($kmlTemplate, $kmlContent));
	}
}
