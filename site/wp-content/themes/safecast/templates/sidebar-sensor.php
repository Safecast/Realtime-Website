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
<iframe width="450" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?&q=<?php echo $latitude ?>%2C<?php echo $longitude ?>&key=AIzaSyDKPc8UZnj4YbrdGb_ccYSGHz9ujHngKec &zoom=11"></iframe>
  </div>
  

