<?php
/**
 * Custom functions
 */

define('ACF_LITE', true);

require_once(WP_CONTENT_DIR.'/plugins/advanced-custom-fields/acf.php');

function safecast_sensor_sidebar_path() {
  return new Roots_Wrapping('templates/sidebar-sensor.php');
}

function safecast_sensor_page_path() {
  return new Roots_Wrapping('templates/page-sensor.php');
}

if (!function_exists('safecastStyle')) {
	function safecastStyle() {
	   	wp_enqueue_style('safecast_main', get_template_directory_uri().'/assets/css/safecast-main.css', array('roots_main'));
	}
}

add_action('wp_enqueue_scripts', 'safecastStyle');

if (!function_exists('roots_wrap_base_cpts')) {
	function roots_wrap_base_cpts($templates) {
	  $cpt = get_post_type(); // Get the current post type
	  if ($cpt) {
	     array_unshift($templates, 'base-' . $cpt . '.php'); // Shift the template to the front of the array
	  }
	  return $templates; // Return our modified array with base-$cpt.php at the front of the queue
	}
}

add_filter('roots_wrap_base', 'roots_wrap_base_cpts'); // Add our function to the roots_wrap_base filter

/**
 * Safecast sensors post type register
 */

if (!function_exists('post_type_sensors')) {	
	function post_type_sensors() {
		$labels               = array(
			'name'               => _x("Sensors", "Post type name", 'safecast'),
			'singular_name'      => _x("Sensor", "Post type singular name", 'safecast'),
			'add_new'            => _x("Add New Sensor", "sensor item", 'safecast'),
			'add_new_item'       => __("Add New Sensor", 'safecast'),
			'edit_item'          => __("Edit Sensor", 'safecast'),
			'new_item'           => __("New Sensor", 'safecast'),
			'view_item'          => __("View Sensor", 'safecast'),
			'search_items'       => __("Search Sensor", 'safecast'),
			'not_found'          => __("Not found", 'safecast'),
			'not_found_in_trash' => __("Trash is empty", 'safecast'),
			'parent_item_colon'  => ''
		);
		
		register_post_type('sensors', array(
			'label'               =>_x('Sensors', 'Type', 'safecast'),
			'description'         =>__('Special type of post for creating sensors', 'safecast'),
			'labels'              => $labels,
			'public'              => true,
			'menu_position'       => 5,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'query_var'           => true,
			'menu_icon'           => 'dashicons-editor-removeformatting',
			'rewrite'             => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array('title', 'editor', 'author', 'comments', 'post-formats', 'revisions', 'page-attributes')
			)
		 ); 
		
		flush_rewrite_rules();
	}
}

if (!function_exists('manage_post_type_sensors_columns')) {	
	function manage_post_type_sensors_columns($columns) {
	    unset($columns['language']);

	    $id       = array('sensor_id' 		=> _x('ID', 'column name'));
	    $location = array('sensor_location' => _x('Location', 'column name'));
	    $city     = array('sensor_city' 	=> _x('City', 'column name'));
	    $province = array('sensor_province' => _x('Province/Region', 'column name'));
	    $country  = array('sensor_country' 	=> _x('Country', 'column name'));

	    $columns  = array_slice($columns, 0, 2, true) + $id + array_slice($columns, 2, NULL, true);
	    $columns  = array_slice($columns, 0, 3, true) + $location + array_slice($columns, 3, NULL, true);
	    $columns  = array_slice($columns, 0, 4, true) + $city + array_slice($columns, 4, NULL, true);
	    $columns  = array_slice($columns, 0, 5, true) + $province + array_slice($columns, 5, NULL, true);
	    $columns  = array_slice($columns, 0, 6, true) + $country + array_slice($columns, 6, NULL, true);
 
	    return $columns;
	}
}

if (!function_exists('custom_sensors_column')) {
	function custom_sensors_column($column, $post_id) {
	    switch ($column) {
	        case 'sensor_id' :
		    echo get_post_meta($post_id, 'sensor_id', true); 
	            break;
	        case 'sensor_location' :
		    echo get_post_meta($post_id, 'sensor_location', true); 
	            break;
	        case 'sensor_city' :
		    echo get_post_meta($post_id, 'sensor_city', true); 
	            break;
	        case 'sensor_province' :
		    echo get_post_meta($post_id, 'sensor_province', true); 
	            break;
	        case 'sensor_country' :
		    echo get_post_meta($post_id, 'sensor_country', true); 
	            break;
	    }
	}
}

if (!function_exists('update_sensors_post_title')) {
	function update_sensors_post_title($post_id) {		
		$postTitle = get_the_title($post_id);
		$postType  = get_post_type($post_id);
	
		if ($postType == 'sensors') {
			$id        = get_post_meta($post_id, 'sensor_id', 		true);
			$location  = get_post_meta($post_id, 'sensor_location', true);
			$city      = get_post_meta($post_id, 'sensor_city', 	true);
			$province  = get_post_meta($post_id, 'sensor_province', true);
			$country   = get_post_meta($post_id, 'sensor_country', 	true);
			$location  = $country
				.($province ? ', '.$province : '')
				.($city ? ', '.$city : '')
				.($location ? ', '.$location : '');
			
			global $wpdb;
			$wpdb->update($wpdb->posts, array('post_title' => $location), array('ID' => $post_id));
		}
	}
}

// Custom post type for sensors
add_action('init', 'post_type_sensors');
add_filter('manage_edit-sensors_columns', 'manage_post_type_sensors_columns');
add_action('manage_sensors_posts_custom_column', 'custom_sensors_column', 10, 2);
add_action('save_post', 'update_sensors_post_title');

/*
 *	Sensors table generation
*/

define("TIME_OFFLINE_SHORT", 	660);
define("TIME_OFFLINE_LONG", 	1560);
define("SENSOR_TABLE_PAGE", 	17);

if (!function_exists('generateSensorsTable')) {
	function generateSensorsTable($sensors, $lang = 'en') {
		$html 		= sprintf('<input id="filter" class="form-control" type="text" placeholder="%s">
<table id="sensors" class="table table-striped footable toggle-arrow-tiny" data-page-size="10" data-filter="#filter" data-filter-text-only="true">
	<thead>
		<tr>
			<th data-sort-initial="ascending">%s</th>
			<th data-hide="phone" data-type="numeric">%s</th>
			<th data-hide="phone" data-type="numeric">%s</th>
			<th data-type="numeric">%s</th>
			<th data-type="numeric">%s</th>
			<th data-hide="phone,tablet" data-type="numeric">%s</th>
			<th data-hide="phone,tablet" data-type="numeric">%s</th>
			<th data-hide="phone">%s</th>
		</tr>
	</thead>
	<tbody>',
		($lang == 'jp' ? '検索' : 'Search'),
		($lang == 'jp' ? '場所' : 'Location'),
		'ID',
		($lang == 'jp' ? '撮影時 (GMT)' : 'Time of Capture (GMT)'),
		'µSv/h',
		'cpm',
		($lang == 'jp' ? '緯度' : 'Latitude'),
		($lang == 'jp' ? '経度' : 'Longitude'),
		($lang == 'jp' ? 'オン／オフライン' : 'On/Offline')
	);

		foreach ($sensors as $sensor) {
			$id          = addslashes($sensor['id']);
			$permalink   = addslashes($sensor['permalink']);
			$location    = addslashes($sensor['location']);
			$timeGMT     = addslashes($sensor['measurement']['gmt']);
			$timestamp   = strtotime($sensor['measurement']['gmt']);
			$timeAgo     = $timestamp ? human_time_diff($timestamp).($lang == 'jp' ? '前' : ' ago') : '';
			$timeSince   = time() - $timestamp;
			$usievert    = addslashes($sensor['measurement']['usieverts']);
			$cpm         = addslashes($sensor['measurement']['cpm']);
			$latitude    = addslashes($sensor['measurement']['latitude']);
			$longitude   = addslashes($sensor['measurement']['longitude']);
			$status      = ($lang == 'jp' ? 'オンライン' : 'Online');
			$statusValue = 0;
			$statusClass = 'info';

			if ($timeSince >= TIME_OFFLINE_LONG) {
				$status      = ($lang == 'jp' ? 'オフライン（長）' : 'Offline long');
				$statusClass = 'danger';
				$statusValue = 2;
			}  else if ($timeSince >= TIME_OFFLINE_SHORT) {
				$status      = ($lang == 'jp' ? 'オフライン（短）' : 'Offline short');
				$statusClass = 'warning';
				$statusValue = 1;
			}

			$html 		.= sprintf('
		<tr id="sensor-%s">
				<td class="location footable-first-column">
					<span class="footable-toggle"></span>
						<a href="%s">%s</a>
				</td>
				<td class="id">%s</td>
				<td class="time" data-value="%s"><span class="ago">%s</span><span class="gmt">%s</span></td>
				<td><span class="measure-sievert">%s</span></td>
				<td><span class="measure-cpm">%s</span></td>
				<td class="latitude">%s</td>
				<td class="longitude">%s</td>
				<td data-value="%d" class="footable-last-column">
					<span class="status"><a href="%s" class="btn btn-sm btn-%s">%s</a></span>
				</td>
		</tr>',
	$id, $permalink, $location, $id, $timestamp, $timeAgo, $timeGMT, $usievert, $cpm,
	$latitude, $longitude, $statusValue, $permalink, $statusClass, $status, $id);
		}

		$html 			.= '
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7">
				<div class="pagination pagination-centered hide-if-no-paging"></div>
			</td>
		</tr>
	</tfoot>
</table>
<script type="text/javascript">
	$(function () {
		$(\'.footable\').footable({
			breakpoints: {
				phone: 480,
				tablet: 760
			}
		});
	});
</script>';

		return $html;
	}
}

if (!function_exists('getAllSensors')) {
	function getAllSensors() {
		$type                = 'sensors';
		$args                = array(
		  'post_type'        => $type,
		  'post_status'      => 'publish',
		  'posts_per_page'   => -1);
		$query               = null;
		$query               = new WP_Query($args);
		$sensors             = array();

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				
				$permalink = get_permalink(get_the_ID());
				$id        = get_post_meta(get_the_ID(), 'sensor_id', 		true);
				$location  = get_post_meta(get_the_ID(), 'sensor_location', true);
				$city      = get_post_meta(get_the_ID(), 'sensor_city', 	true);
				$province  = get_post_meta(get_the_ID(), 'sensor_province', true);
				$country   = get_post_meta(get_the_ID(), 'sensor_country', 	true);
				$location  = $country
					.($province ? ', '.$province : '')
					.($city ? ', '.$city : '')
					.($location ? ', '.$location : '');
				
				$lastMeasurement = array(
					'usieverts'     => get_post_meta(get_the_ID(), 'sensor_last_usieverts', true),
					'cpm'           => get_post_meta(get_the_ID(), 'sensor_last_cpm', 		true),
					'gmt'     		=> get_post_meta(get_the_ID(), 'sensor_last_gmt', 		true),
					'latitude'      => get_post_meta(get_the_ID(), 'sensor_last_latitude', 	true),
					'longitude'     => get_post_meta(get_the_ID(), 'sensor_last_longitude', true));
				
				$sensors[$id]  = array(
					'permalink'   => $permalink,
					'id'          => $id,
					'location'    => $location,
					'measurement' => $lastMeasurement);
			}
		}
		wp_reset_query();
	
		return $sensors;
	}
}

if (!function_exists('sensorsTable')) {
	function sensorsTable($atts) {
		extract(shortcode_atts(array(
			'lang' => 'en',
		), $atts, 'bartag'));

		return generateSensorsTable(getAllSensors(), $lang);
	}
}

add_shortcode('sensors_table', 'sensorsTable');

if (!function_exists('addFooTable')) {
	function addFooTable() {
	   	if (is_page('Fixed sensor information')) {		
			wp_enqueue_script('footable-js', get_template_directory_uri().'/assets/js/footable.js', array(), '2-0-1', true);
			wp_enqueue_script('footable-js-sort', get_template_directory_uri().'/assets/js/footable.sort.js', array(), '2-0-1', true);
			wp_enqueue_script('footable-js-filter', get_template_directory_uri().'/assets/js/footable.filter.js', array(), '2-0-1', true);
			wp_enqueue_script('footable-js-paginate', get_template_directory_uri().'/assets/js/footable.paginate.js', array(), '2-0-1', true);
			wp_enqueue_style('footable-css', get_template_directory_uri().'/assets/css/footable.core.min.css', array('safecast_main'));
			wp_enqueue_style('realtimetable', get_template_directory_uri().'/assets/css/realtimetable.css', array('footable-css'));
		}
	}
}

add_action('wp_enqueue_scripts', 'addFooTable');