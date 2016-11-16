<?php

/**
 * Title         : Options Page settings
 * Description   : All the code related to the Settings is here 
 * Version       : 1.0.4
 * Author        : Massoud Shakeri
 * Author URI    : http://www.blazingspider.com/
 * Documentation : https://github.com/BlazingSpider/blazing-charts
 * Plugin URI    : http://blazingspider.com/plugins/blazing-charts
 * License       : GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * IMPORTANT NOTE: The above statement does NOT substitute HighCharts terms of use. HighCharts use is free for a personal or non-profit project under the Creative Commons Attribution-NonCommercial 3.0 License. Anyway please refer to HighCharts license page http://shop.highsoft.com/highcharts.html to check the HighCharts precise license conditions.  
 * @class 		 Blazing_Charts_Options_Page
*/

if ( ! class_exists( 'Blazing_Charts_Options_Page' ) ) {

class Blazing_Charts_Options_Page {
    /**
     * Holds the values to be used in the fields callbacks
     */
    var $options = array();
	var $settings_name = 'blazing_charts_settings'; // The settings string name for this plugin in options table
	var $bc_settings_ver = '1.0';

    /**
     * Start up
     */
	public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings_and_Fields' ) );
	}

    /**
     * Add options page
     */
	public function admin_menu () {
		add_options_page('Bazing Charts Settings', 'Bazing Charts Settings', 'administrator', __FILE__, array( $this, 'settings_page' ));
	}

    /**
     * Options page callback
     */
	public function  settings_page () {
        // Set class property
		if (!($bc_settings = get_option( $this->settings_name )) ) {
			$bc_settings = array(
				//'highcharts_cdn' => '0',
				'morris_cdn' => '0',
//				'zingchart_cdn' => '0',
				'chartjs_cdn' => '0',
//				'google_cdn' => '0',
				'd3_cdn' => '0',
				'chartist_cdn' => '0',
//				'smoothie_cdn' => '0',
				'flot_cdn' => '0',
//				'amcharts_cdn' => '0',
				'version' => $this->bc_settings_ver	// see on top class
			);
			update_option( $this->settings_name, $bc_settings);
		}
		$this->options = $bc_settings;

		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Blazing Charts Settings</h2>
			<h3>HighCharts:</h3>
			<p><strong>HighCharts</strong> is not used by default, and its auxiliary libraries are loaded remotely if the user's site opts in. To load auxiliary libraries, along with main library, you should specify them, as a comma-separated list, in the shortcode as following:<br>
			[BlazingChart charttype="highcharts" source="slug of the chart snippet" options="more,3d,exporting"]</p>
			<p>This plugin does NOT substitute <strong>HighCharts</strong> terms of use. HighCharts use is free for a personal or non-profit project under the Creative Commons Attribution-NonCommercial 3.0 License.<br>Anyway please refer to HighCharts license page http://shop.highsoft.com/highcharts.html to check the precise license conditions.</p>
			<h3>ZingChart</h3>
			<p><strong>ZingChart</strong> license is not GPL compatible and it is not used by default. The library is loaded remotely if the user's site opts in. To load the library, you should specify it in the shortcode as following:<br>
			[BlazingChart charttype="zingchart" source="slug of the chart snippet"]</p>
			<p>This plugin does NOT substitute <strong>ZingChart</strong> terms of use. Please refer to ZingChart license page https://www.zingchart.com/buy/details/branded-license/ to check the precise license conditions.</p>
			<h3>Google Charts</h3>
			<p><strong>Google Charts</strong> can only be loaded remotely. To reduce the size of the libraries loaded for Google Charts, that library decides which portions of the library to be included, depending to the type of the chart.</p>
			<h3>Smoothie Charts</h3>
			<p><strong>Smoothie Charts</strong> can only be loaded locally, and I could not find a CDN hosting it.
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields('blazing_charts_group');
				do_settings_sections(__FILE__);
				submit_button();
			?>
			</form>
		</div>
		<?php
	}
	
    /**
     * Register and add settings
     */
	public function register_settings_and_Fields() {
		register_setting(
			'blazing_charts_group', // Option group
			$this->settings_name, // Option name
            array( $this, 'sanitize' ) // Sanitize
			); // 3rd param = optional cb
		add_settings_section(
			'blazing_main_section', //ID
			'CDN Settings', //Title of section
			array($this, 'blazing_main_section_cb'), //cb
			__FILE__ //which page?
			);
		
		//add_settings_field('highcharts_cdn', 'HighCharts', array($this, 'highcharts_cdn_set'), __FILE__, 'blazing_main_section');
		add_settings_field('morris_cdn', 'Morris.js', array($this, 'morris_cdn_set'), __FILE__, 'blazing_main_section');
		//add_settings_field('zingchart_cdn', 'ZingChart', array($this, 'zingchart_cdn_set'), __FILE__, 'blazing_main_section');
		add_settings_field('chartjs_cdn', 'Chart.js', array($this, 'chartjs_cdn_set'), __FILE__, 'blazing_main_section');
		//add_settings_field('google_cdn', 'Google Charts', array($this, 'google_cdn_set'), __FILE__, 'blazing_main_section');
		add_settings_field('d3_cdn', 'D3.js', array($this, 'd3_cdn_set'), __FILE__, 'blazing_main_section');
		add_settings_field('chartist_cdn', 'Chartist.js', array($this, 'chartist_cdn_set'), __FILE__, 'blazing_main_section');
		//add_settings_field('smoothie_cdn', 'Smoothie', array($this, 'smoothie_cdn_set'), __FILE__, 'blazing_main_section');
		add_settings_field('flot_cdn', 'Flot Charts', array($this, 'flot_cdn_set'), __FILE__, 'blazing_main_section');
		//add_settings_field('amcharts_cdn', 'AMCharts', array($this, 'amcharts_cdn_set'), __FILE__, 'blazing_main_section');
	}
	public function blazing_main_section_cb($arg) {
		echo "<h3>Choose which one of theses Chart Libraries to be loaded from CDN (Content Delivery Network)</h3>";
	}

    /** 
     * Get the settings option array and print one of its values
     */
//	public function highcharts_cdn_set() {
//		$checked = ( isset ($this->options['highcharts_cdn']) && $this->options['highcharts_cdn']) ? "checked='checked'" : "";
//		echo "<input value='1' id='highcharts_cdn' name='blazing_charts_settings[highcharts_cdn]' type='checkbox' {$checked} />";
//		echo "<label for='highcharts_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
//	}
	public function morris_cdn_set() {
		$checked = ( isset ($this->options['morris_cdn']) && $this->options['morris_cdn']) ? "checked='checked'" : "";
		echo "<input value='1' id='morris_cdn' name='blazing_charts_settings[morris_cdn]' type='checkbox' {$checked} />";
		echo "<label for='morris_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
	}
//	public function zingchart_cdn_set() {
//		$checked = ( isset ($this->options['zingchart_cdn']) && $this->options['zingchart_cdn']) ? "checked='checked'" : "";
//		echo "<input value='1' id='zingchart_cdn' name='blazing_charts_settings[zingchart_cdn]' type='checkbox' {$checked} />";
//		echo "<label for='zingchart_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
//	}
	public function chartjs_cdn_set() {
		$checked = ( isset ($this->options['chartjs_cdn']) && $this->options['chartjs_cdn']) ? "checked='checked'" : "";
		echo "<input value='1' id='chartjs_cdn' name='blazing_charts_settings[chartjs_cdn]' type='checkbox' {$checked} />";
		echo "<label for='chartjs_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
	}
//	public function google_cdn_set() {
//		$checked = ( isset ($this->options['google_cdn']) && $this->options['google_cdn']) ? "checked='checked'" : "";
//		echo "<input value='1' id='google_cdn' name='blazing_charts_settings[google_cdn]' type='checkbox' {$checked} />";
//		echo "<label for='google_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
//	}
	public function d3_cdn_set() {
		$checked = ( isset ($this->options['d3_cdn']) && $this->options['d3_cdn']) ? "checked='checked'" : "";
		echo "<input value='1' id='d3_cdn' name='blazing_charts_settings[d3_cdn]' type='checkbox' {$checked} />";
		echo "<label for='d3_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
	}
	public function chartist_cdn_set() {
		$checked = ( isset ($this->options['chartist_cdn']) && $this->options['chartist_cdn']) ? "checked='checked'" : "";
		echo "<input value='1' id='chartist_cdn' name='blazing_charts_settings[chartist_cdn]' type='checkbox' {$checked} />";
		echo "<label for='chartist_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
	}
//	public function smoothie_cdn_set() {
//		$checked = ( isset ($this->options['smoothie_cdn']) && $this->options['smoothie_cdn']) ? "checked='checked'" : "";
//		echo "<input value='1' id='smoothie_cdn' name='blazing_charts_settings[smoothie_cdn]' type='checkbox' {$checked} />";
//		echo "<label for='smoothie_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
//	}
	public function flot_cdn_set() {
		$checked = ( isset ($this->options['flot_cdn']) && $this->options['flot_cdn']) ? "checked='checked'" : "";
		echo "<input value='1' id='flot_cdn' name='blazing_charts_settings[flot_cdn]' type='checkbox' {$checked} />";
		echo "<label for='flot_cdn'>Check if you want to get this library and all its auxiliary libraries from CDN, Uncheck for loading local copy</label>";
	}
//	public function amcharts_cdn_set() {
//		$checked = ( isset ($this->options['amcharts_cdn']) && $this->options['amcharts_cdn']) ? "checked='checked'" : "";
//		echo "<input value='1' id='amcharts_cdn' name='blazing_charts_settings[amcharts_cdn]' type='checkbox' {$checked} />";
//		echo "<label for='amcharts_cdn'>Check if you want to get the library from CDN, Uncheck for loading local copy</label>";
//	}

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array(
			//'highcharts_cdn' => '0',
			'morris_cdn' => '0',
//			'zingchart_cdn' => '0',
			'chartjs_cdn' => '0',
//			'google_cdn' => '0',
			'd3_cdn' => '0',
			'chartist_cdn' => '0',
//			'smoothie_cdn' => '0',
			'flot_cdn' => '0',
//			'amcharts_cdn' => '0',
		);
//        if( isset( $input['highcharts_cdn'] ) ) {
//            $new_input['highcharts_cdn'] = $input['highcharts_cdn'];
//		}
        if( isset( $input['morris_cdn'] ) ) {
            $new_input['morris_cdn'] = $input['morris_cdn'];
		}
//        if( isset( $input['zingchart_cdn'] ) ) {
//            $new_input['zingchart_cdn'] = $input['zingchart_cdn'];
//		}
       if( isset( $input['chartjs_cdn'] ) ) {
           $new_input['chartjs_cdn'] = $input['chartjs_cdn'];
		}
//        if( isset( $input['google_cdn'] ) ) {
//            $new_input['google_cdn'] = $input['google_cdn'];
//		}
        if( isset( $input['d3_cdn'] ) ) {
            $new_input['d3_cdn'] = $input['d3_cdn'];
		}
        if( isset( $input['chartist_cdn'] ) ) {
            $new_input['chartist_cdn'] = $input['chartist_cdn'];
		}
//        if( isset( $input['smoothie_cdn'] ) ) {
//            $new_input['smoothie_cdn'] = $input['smoothie_cdn'];
//		}
        if( isset( $input['flot_cdn'] ) ) {
            $new_input['flot_cdn'] = $input['flot_cdn'];
		}
//        if( isset( $input['amcharts_cdn'] ) ) {
//            $new_input['amcharts_cdn'] = $input['amcharts_cdn'];
//		}
		$new_input['version'] = $this->bc_settings_ver ; // because not in input !

        return $new_input;
    }
}
}

if( is_admin() ) {
    $my_settings_page = new Blazing_Charts_Options_Page();
}
