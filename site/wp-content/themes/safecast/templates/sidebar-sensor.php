<?php
	$latitude  = get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude', true);
	$longitude = get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', true);
	$gmt       = get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', true);
	$timeSince = time() - strtotime($gmt);
	$color     = 'blue';
	
	if ($timeSince >= TIME_OFFLINE_LONG) {
		$color = 'red'; 
	}  else if ($timeSince >= TIME_OFFLINE_SHORT) {
		$color = 'orange'; 
	}
	
	$location  = get_post_meta(get_the_ID(), 'sensor_location', true);
	$city      = get_post_meta(get_the_ID(), 'sensor_city', 	true);
	$province  = get_post_meta(get_the_ID(), 'sensor_province', true);
	$country   = get_post_meta(get_the_ID(), 'sensor_country', 	true);
	$location  = $country
		.($province ? ', '.$province : '')
		.($city ? ', '.$city : '')
		.($location ? ', '.$location : '');
?>

<div class="map">
	<a href="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $latitude ?>,<?php echo $longitude ?>&zoom=7&size=800x500&maptype=roadmap
		&markers=color:<?php echo $color ?>%7C<?php echo $latitude ?>,<?php echo $longitude ?>&sensor=false" rel="lightbox" title="<?php echo $location ?>">
		<img class="img-responsive img-thumbnail" alt="" src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $latitude ?>,<?php echo $longitude ?>&zoom=6&size=360x200&maptype=roadmap
		&markers=color:<?php echo $color ?>%7C<?php echo $latitude ?>,<?php echo $longitude ?>&sensor=false" />
	</a>
</div>
