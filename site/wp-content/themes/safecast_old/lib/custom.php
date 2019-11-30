<?php
/**
 * Custom functions
 */

@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );


define('ACF_LITE', false);

require_once(WP_CONTENT_DIR.'/plugins/advanced-custom-fields/acf.php');

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}

if (!function_exists('mass_update_posts')) {
    function mass_update_posts() {
            
        $args = array(    'post_type'=>'sensors', //whatever post type you need to update 
                        'posts_per_page'   => -1);
            
        $my_posts = get_posts($args);
        
        foreach($my_posts as $key => $my_post){
            $meta_values = get_post_meta( $my_post->ID);
            foreach($meta_values as $meta_key => $meta_value ){
                update_field($meta_key, $meta_value[0], $my_post->ID);
            }
        }
    }
}

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_alarm',
		'title' => 'Alarm',
		'fields' => array (
			array (
				'key' => 'field_57f50ca516276',
				'label' => '',
				'name' => 'alarm',
				'type' => 'number',
				'instructions' => 'Alarm is three times the average CPM',
				'default_value' => 150,
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sensors',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}


if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_inactive',
		'title' => 'Inactive',
		'fields' => array (
			array (
				'key' => 'field_57306ca144aa3',
				'label' => 'Inactive',
				'name' => 'retired_sensor',
				'type' => 'checkbox',
				'choices' => array (
					'true' => 'Inactive',
				),
				'default_value' => 'false',
				'layout' => 'vertical',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sensors',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 2,
	));
}

if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_update',
		'title' => '<!--:en-->Update<!--:--><!--:ja-->Update<!--:-->',
		'fields' => array (
			array (
				'key' => 'field_53bfd0a2fbacb',
				'label' => 'Mutex',
				'name' => 'sensor_mutex',
				'type' => 'true_false',
				'required' => 1,
				'message' => '',
				'default_value' => 0,
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sensors',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 42,
	));
	register_field_group(array (
		'id' => 'acf_last-measurement',
		'title' => 'Last measurement',
		'fields' => array (
			array (
				'key' => 'field_5386e49735466',
				'label' => 'µSv/h',
				'name' => 'sensor_measurement_last_usvh',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5386e41335463',
				'label' => 'cpm',
				'name' => 'sensor_measurement_last_cpm',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5386e4e135468',
				'label' => 'Time in GMT',
				'name' => 'sensor_measurement_last_gmt',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5386e64a35469',
				'label' => 'Latitude',
				'name' => 'sensor_measurement_last_latitude',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5386e6853546a',
				'label' => 'Longitude',
				'name' => 'sensor_measurement_last_longitude',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sensors',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_max-measurement',
		'title' => 'Max measurement',
		'fields' => array (
			array (
				'key' => 'field_5386e7e7907c0',
				'label' => 'µSv/h',
				'name' => 'sensor_measurement_max_usvh',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5386e83e907c1',
				'label' => 'cpm',
				'name' => 'sensor_measurement_max_cpm',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5386e85f907c2',
				'label' => 'Time in GMT',
				'name' => 'sensor_measurement_max_gmt',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5386e870907c3',
				'label' => 'Latitude',
				'name' => 'sensor_measurement_max_latitude',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5386e887907c4',
				'label' => 'Longitude',
				'name' => 'sensor_measurement_max_longitude',
				'type' => 'number',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sensors',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
	register_field_group(array (
		'id' => 'acf_general-information',
		'title' => 'General information',
		'fields' => array (
			array (
				'key' => 'field_5386e7024bf30',
				'label' => 'ID',
				'name' => 'sensor_id',
				'type' => 'number',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'min' => '',
				'max' => '',
				'step' => '',
			),
			array (
				'key' => 'field_5386df91bedaf',
				'label' => 'Location name',
				'name' => 'sensor_location',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'none',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5386dffcbedb0',
				'label' => 'City name',
				'name' => 'sensor_city',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5386e01fbedb1',
				'label' => 'State/Province',
				'name' => 'sensor_province',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_5386e02abedb2',
				'label' => 'Country',
				'name' => 'sensor_country',
				'type' => 'text',
				'required' => 1,
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53996cd482f8f',
				'label' => 'Sensor type',
				'name' => 'sensor_type',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53996f385f892',
				'label' => 'Manufacturer',
				'name' => 'sensor_manufacturer',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
			array (
				'key' => 'field_53996f4e5f893',
				'label' => 'Model',
				'name' => 'sensor_model',
				'type' => 'text',
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'formatting' => 'html',
				'maxlength' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'sensors',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 2,
	));
}

add_filter('post_type_link', 'qtrans_convertURL'); 

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

define("TIME_OFFLINE_SHORT",    3960);
define("TIME_OFFLINE_LONG", 	7920);
define("TIME_OFFLINE_VERY_LONG", 63072000);
define("SENSOR_TABLE_PAGE", 	17);

if (!function_exists('generateSensorsTable')) {
	function generateSensorsTable($sensors, $lang = 'en') {
		$html 		= sprintf('<input id="filter" class="form-control" type="text" placeholder="%s">
<table id="sensors" class="table table-striped footable toggle-arrow-tiny" data-page-size="50" data-filter="#filter" data-filter-text-only="true">
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
			<th data-hide="phone">%s</th>
			
		</tr>
	</thead>
	<tbody>',
		($lang == 'jp' ? '検索' : 'Search'),
		($lang == 'jp' ? '場所' : 'Location'),
		'ID',
		($lang == 'jp' ? 'Model' : 'Model'),					  
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
			$sensorModel = addslashes($sensor['model']);
			$location    = addslashes($sensor['location']);
			$timeGMT     = addslashes($sensor['measurement']['gmt']);
			$timestamp   = strtotime($sensor['measurement']['gmt']);
			$timeAgo     = $timestamp ? human_time_diff($timestamp).($lang == 'jp' ? '前' : ' ago') : '';
			$timeSince   = time() - $timestamp;
			$usievert    = addslashes($sensor['measurement']['usvh']);
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
				<td class="id">%s</td>
				<td class="time" data-value="%s">
				     <span class="ago">%s</span>
					 <span class="gmt">%s</span></td>
				<td><span class="measure-sievert">%s</span></td>
				<td><span class="measure-cpm">%s</span></td>
				<td class="latitude">%s</td>
				<td class="longitude">%s</td>
				<td data-value="%d" class="footable-last-column">
					<span class="status"><a href="%s" class="btn btn-sm btn-%s">%s</a></span>
				</td>
		</tr>',
	$id, $permalink, $location, $id, $sensorModel, $timestamp, $timeAgo, $timeGMT, $usievert, $cpm,
	$latitude, $longitude, $statusValue, $permalink, $statusClass, $status, $id);
		}

		$html 			.= '
	</tbody>
	<tfoot>
		<tr>
			<td colspan="9" class="text-center">
				<ul class="hide-if-no-paging pagination pagination-centered"></ul>
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
      
      
		    'posts_per_page'   => -1);
		$query               = null;
		$query               = new WP_Query($args);
		$sensors             = array();

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				
				$permalink = get_permalink(get_the_ID());
				$id        = get_post_meta(get_the_ID(), 'sensor_id', 		true);
				$model     = get_post_meta(get_the_ID(), 'sensor_model',    true);
				$location  = get_post_meta(get_the_ID(), 'sensor_location', true);
				$city      = get_post_meta(get_the_ID(), 'sensor_city', 	true);
				$province  = get_post_meta(get_the_ID(), 'sensor_province', true);
				$country   = get_post_meta(get_the_ID(), 'sensor_country', 	true);
        		$retired   = get_post_meta(get_the_ID(), 'retired_sensor', 	false);
				$location  = $country
					.($province ? ', '.$province : '')
					.($city ? ', '.$city : '')
					.($location ? ', '.$location : '');
				
				$lastMeasurement = array(
					'usvh'     => get_post_meta(get_the_ID(), 'sensor_measurement_last_usvh', true),
					'cpm'           => get_post_meta(get_the_ID(), 'sensor_measurement_last_cpm', 		true),
					'gmt'     		=> get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt', 		true),
					'latitude'      => get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude', 	true),
					'longitude'     => get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', true));
				
				$sensors[$id]  = array(
					'permalink'   => $permalink,
					'id'          => $id,
					'model'       => $model,
					'location'    => $location,
					'measurement' => $lastMeasurement);
			}
		}
		wp_reset_query();
	
		return $sensors;
	}
}


if (!function_exists('getRetiredSensors')) {
    function getRetiredSensors() {
        $type                = 'sensors';
        $args                = array(
          'post_type'        => $type,
          'post_status'      => 'publish',
          'meta_query' => array(
              array(
                'key'     => 'retired_sensor',
                'value'   => 'true',
                'compare' => 'LIKE'
              )
    
          ), // End of meta_query
          'posts_per_page'   => -1);
        $query               = null;
        $query               = new WP_Query($args);
      
        $sensors             = array();
        

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $permalink = get_permalink(get_the_ID());
                $id        = get_post_meta(get_the_ID(), 'sensor_id',       true);
				$model     = get_post_meta(get_the_ID(), 'sensor_model',    true);
                $location  = get_post_meta(get_the_ID(), 'sensor_location', true);
                $city      = get_post_meta(get_the_ID(), 'sensor_city',     true);
                $province  = get_post_meta(get_the_ID(), 'sensor_province', true);
                $country   = get_post_meta(get_the_ID(), 'sensor_country',  true);
                $retired   = get_post_meta(get_the_ID(), 'retired_sensor', 	true);
              
                $location  = $country
                    .($province ? ', '.$province : '')
                    .($city ? ', '.$city : '')
                    .($location ? ', '.$location : '');
                
                $lastMeasurement = array(
                    'usvh'     => get_post_meta(get_the_ID(), 'sensor_measurement_last_usvh', true),
                    'cpm'           => get_post_meta(get_the_ID(), 'sensor_measurement_last_cpm',         true),
                    'gmt'             => get_post_meta(get_the_ID(), 'sensor_measurement_last_gmt',         true),
                    'latitude'      => get_post_meta(get_the_ID(), 'sensor_measurement_last_latitude',     true),
                    'longitude'     => get_post_meta(get_the_ID(), 'sensor_measurement_last_longitude', true));
                
                $sensors[$id]  = array(
                    'permalink'   => $permalink,
                    'id'          => $id,
					'model'       => $model,
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

if (!function_exists('sensorsRetiredTable')) {
    function sensorsRetiredTable($atts) {
        extract(shortcode_atts(array(
            'lang' => 'en',
        ), $atts, 'bartag'));

        return generateSensorsTable(getRetiredSensors(), $lang);
    }
}

add_shortcode('sensors_table', 'sensorsTable');
add_shortcode('sensors_retired_table', 'sensorsRetiredTable');

if (!function_exists('addFooTable')) {
	function addFooTable() {
	   	if (is_page('home')) {		
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

/*
 * Sensors Map generation
*/

if (!function_exists('generateMap')) {
	function generateMap($sensors, $lang = 'en') {
		$markers  = '';
		
		foreach ($sensors as $sensor) {
			$id          = addslashes($sensor['id']);
			$permalink   = addslashes($sensor['permalink']);
			$location    = addslashes($sensor['location']);
			$timestamp   = strtotime($sensor['measurement']['gmt']);
			$timeAgo     = $timestamp ? human_time_diff($timestamp).($lang == 'jp' ? '前' : ' ago') : '';
			$timeSince   = time() - $timestamp;
			$usvh        = addslashes($sensor['measurement']['usvh']);
			$cpm         = addslashes($sensor['measurement']['cpm']);
			$latitude    = addslashes($sensor['measurement']['latitude']);
			$longitude   = addslashes($sensor['measurement']['longitude']);
			$graphPath   = get_template_directory_uri()."/../../../../plots/";
			$status	     = '#269abc';

			if ($timeSince >= TIME_OFFLINE_LONG) {
				$status = '#ac2925';
			}  else if ($timeSince >= TIME_OFFLINE_SHORT) {
				$status = '#eea236';
			}
				
			$markers	.= sprintf("addMarker('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
			", $id, $permalink, $timeAgo, $location, $latitude, $longitude, $cpm, $usvh, $graphPath, $status);
		}
		
		//var_dump($markers);die;
		
		return '<div id="map-canvas"/>
			<script type="text/javascript">
			function setupMarkers() {
				'.$markers.'
			}
			google.maps.event.addDomListener(window, \'load\', setupMarkers);
			</script>';
	}
}

if (!function_exists('sensorsMap')) {
	function sensorsMap($atts) {
		extract(shortcode_atts(array(
			'lang' => 'en',
		), $atts, 'bartag'));
		
		return generateMap(getAllSensors(), $lang);
	}
}

add_shortcode('sensors_map', 'sensorsMap');

if (!function_exists('addMap')) {
	function addMap() {
	   	if (is_page('map')) {		
			wp_enqueue_script('gmap', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAB0jjFVo07ZIVR_xkKaa_x4HVb2yiQPIE', array(), null, false);
			wp_enqueue_script('map', get_template_directory_uri().'/assets/js/map.js', array('gmap'), null, false);
		}
	}
}

add_action('wp_enqueue_scripts', 'addMap');
