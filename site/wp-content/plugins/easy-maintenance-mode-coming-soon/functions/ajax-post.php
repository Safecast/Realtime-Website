<?php 
/* general settings page data post function
********************************************************************/
add_action('wp_ajax_maintenance_general_setting_save_post', 'maintenance_general_setting_save_post');
function maintenance_general_setting_save_post()
{
	
	if(isset($_POST['action']) == "maintenance_general_setting_save_post")
	{
		$status = mysql_real_escape_string($_POST['status']);
		$headline = stripslashes($_POST['headline']);
		$description = stripslashes($_POST['description']);
		$google_code = stripslashes($_POST['google_code']);
		$fb = mysql_real_escape_string($_POST['fb']);
		$twit = mysql_real_escape_string($_POST['twit']);
		$gp = mysql_real_escape_string($_POST['gp']);

		//all data is sotre in array form
		$general_setting_save = array( 'status' => $status,'headline' => $headline,'description' => $description,'google_code' => $google_code,'fb'=> $fb,'twit'=> $twit,'gp'=> $gp
		
		);
		// data is insert into option table
		update_option("easy_maintenance_mode_general_settings", $general_setting_save);
	}
}




/*Text design  settings page data post function
********************************************************************/
add_action('wp_ajax_maintenance_text_design_setting_save_post', 'maintenance_text_design_setting_save_post');
function maintenance_text_design_setting_save_post()
{
	
	if(isset($_POST['action']) == "maintenance_text_design_setting_save_post")
	{
		$headline_color  = mysql_real_escape_string($_POST['headline_color']);
		$description_color = mysql_real_escape_string($_POST['description_color']);
		$headline_size = mysql_real_escape_string($_POST['headline_size']);
		$description_size = mysql_real_escape_string($_POST['description_size']);
		$headline_family_post_title = mysql_real_escape_string($_POST['headline_family_post_title']);
		$description_family_post_title = mysql_real_escape_string($_POST['description_family_post_title']);
		
		
		
		

		//all data is sotre in array form
		$text_design_setting_save = array( 'headline_color' => $headline_color,'description_color' => $description_color,'headline_size' => $headline_size,'description_size' => $description_size,'headline_family_post_title' => $headline_family_post_title,'description_family_post_title' => $description_family_post_title
		);
		// data is insert into option table
		update_option("easy_maintenance_mode_text_design_setting", $text_design_setting_save);
	}
}


/*Background Settings  settings page data post function
********************************************************************/
add_action('wp_ajax_maintenance_background_setting_save_post', 'maintenance_background_setting_save_post');
function maintenance_background_setting_save_post()
{
	
	if(isset($_POST['action']) == "maintenance_background_setting_save_post")
	{
		$background_option  = mysql_real_escape_string($_POST['background_option']);
		$background_color  = mysql_real_escape_string($_POST['background_color']);
		$background_effect  = mysql_real_escape_string($_POST['background_effect']);
		$background_text  = mysql_real_escape_string($_POST['background_text']);
	
		
		
	
		//all data is sotre in array form
		$background_setting_save = array( 'background_option' => $background_option,'background_color' => $background_color,'background_effect' =>$background_effect,'background_text' =>$background_text
		);
		// data is insert into option table
		update_option("easy_maintenance_mode_background_setting_save", $background_setting_save);
	}
}


?>