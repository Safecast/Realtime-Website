<?php
	$id            = get_post_meta(get_the_ID(), 'sensor_id', true);
	$langIsJap     = qtrans_getLanguage() == "jp";
	$lastCpm       = get_post_meta(get_the_ID(), 'sensor_measurement_last_cpm', true);
	$lastUsievert  = get_post_meta(get_the_ID(), 'sensor_measurement_last_usvh', true);
	$lastLatitude  = get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude', true);
	$lastLongitude = get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', true);
	$lastGmt       = get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', true);
	$lastTimestamp = strtotime($lastGmt);
	$lastTimeSince = time() - $lastTimestamp;
	$lastTimeAgo   = human_time_diff($lastTimestamp).($langIsJap ? '前' : ' ago');
	
	$maxCpm        = get_post_meta(get_the_ID(), 'sensor_measurement_max_cpm', true);
	$maxUsievert   = get_post_meta(get_the_ID(), 'sensor_measurement_max_usvh', true);
	$maxLatitude   = get_post_meta(get_the_ID(), 'sensor_measurement_max_latitude', true);
	$maxLongitude  = get_post_meta(get_the_ID(), 'sensor_measurement_max_longitude', true);
	$maxGmt        = get_post_meta(get_the_ID(), 'sensor_measurement_max_gmt', true);
	$maxTimestamp  = strtotime($maxGmt);
	$maxTimeSince  = time() - $maxTimestamp;
	$maxTimeAgo    = human_time_diff($maxTimestamp).($langIsJap ? '前' : ' ago');
	
	$status      = $langIsJap ? 'オンライン' : 'Online';
	$statusClass = 'info';

	if ($lastTimeSince >= TIME_OFFLINE_LONG) {
		$status      = $langIsJap ? 'オフライン（長）' : 'Offline long';
		$statusClass = 'danger';
	}  else if ($lastTimeSince >= TIME_OFFLINE_SHORT) {
		$status      = $langIsJap ? 'オフライン（短）' : 'Offline short';
		$statusClass = 'warning';
	}
	
	
	$download  = $langIsJap ? 'データセットのダウンロード' : 'Download the dataset';
	$api       = $langIsJap ? 'APIデータへ' : 'See API data';
	$uploadDir = wp_upload_dir();
	$fileURI   = $uploadDir['baseurl'].sprintf("/measurements/device_%s.csv", $id);
	$apiURI    = sprintf("https://api.safecast.org/%s/measurements?device_id=%s", $langIsJap ? 'ja' : 'en-US', $id);
?>

<div class="sensor-page-header container-fluid">
	<div class="row">
		<h1 class="entry-title page-header">
			<?php the_title(); ?>
			<div class="status btn btn-sm btn-<?php echo $statusClass ?>"><?php echo $status ?></div>
		</h1>
		<div class="container-fluid">
			<div class="row">
				<div class="graph col-md-6 text-center">
					<table class="table table-hover">
						<thead>
							<th class="last text-center"><?php echo $lastTimeAgo ?></th>
							<th class="max text-center"><?php echo $maxTimeAgo ?></th>
						</thead>
						<tbody>
							<tr>
								<td><span class="last value"><?php echo $lastCpm ?></span><span class="last unit">cpm</span></td>
								<td><span class="max value"><?php echo $maxCpm ?></span><span class="max unit">cpm</span></td>
							</tr>
							<tr>
								<td><span class="last value"><?php echo $lastUsievert ?></span><span class="last unit">μSv/h</span></td>
								<td><span class="max value"><?php echo $maxUsievert ?></span><span class="max unit">μSv/h</span></td>
							</tr>
						</tbody>	
					</table>
				</div>
				<div class="graph col-md-6">
					<a href="/plots/<?php echo $id ?>.png" rel="lightbox">
						<img class="img-responsive" alt="" src="/plots/<?php echo $id ?>_small.png" />
					</a>
					<div class="download">
						<a href="<?php echo $fileURI; ?>" target="_blank" class="btn btn-default btn-primary"><?php echo $download; ?></a>
					</div>
					<div class="download api">
						<a href="<?php echo $apiURI; ?>" target="_blank" class="btn btn-default btn-primary"><?php echo $api; ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	  
