<?php 

add_action('wp_ajax_reset_data_post', 'reset_plugin_data_post');
function reset_plugin_data_post()
{

/* general settings page data post function
********************************************************************/	
		$status = "0";
		$headline = "Site is under maintenance";
		$description = "Site will be launching soon, Thanks for visit Our site";
		$google_code = "";
		$fb = "";
		$twit = "";
		$gp = "";

		//all data is sotre in array form
		$general_setting_save = array( 'status' => $status,'headline' => $headline,'description' => $description,'google_code' => $google_code,'fb'=> $fb,'twit'=> $twit,'gp'=> $gp
		
		);
		// data is insert into option table
		update_option("easy_maintenance_mode_general_settings", $general_setting_save);
	
	
/*Text design  data post function
********************************************************************/
		$headline_color  = "#ffffff";
		$description_color = "#ffffff";
		$headline_size = "30";
		$description_size = "18";
		$headline_family_post_title = "Arial";
		$description_family_post_title = "Arial";
		
		//all data is sotre in array form
		$text_design_setting_save = array( 'headline_color' => $headline_color,'description_color' => $description_color,'headline_size' => $headline_size,'description_size' => $description_size,'headline_family_post_title' => $headline_family_post_title,'description_family_post_title' => $description_family_post_title
		);
		// data is insert into option table
		update_option("easy_maintenance_mode_text_design_setting", $text_design_setting_save);


		
/*Background Settings  data post function
********************************************************************/

		$background_option  = "static-background-color";
		$background_color  = "#2f96b4";
		$background_effect  = "none";
		$background_text  = "";
	
		//all data is sotre in array form
		$background_setting_save = array( 'background_option' => $background_option,'background_color' => $background_color,'background_effect' =>$background_effect,'background_text' =>$background_text
		);
		// data is insert into option table
		update_option("easy_maintenance_mode_background_setting_save", $background_setting_save);
		
		
}


?>