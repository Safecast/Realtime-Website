<?php
/**
 * General.php file
 *
 * using for enable or disable coming soon page
 */
?>
<script>
function general_setting_save()
{
	var status= jQuery('input:radio[name="status"]:checked').val();
	var headline = jQuery('#headline').val();
	var description = jQuery('#description').val();
	var google_code = jQuery('#google-code').val();	
	var fb = jQuery('#fb').val();
	var twit= jQuery('#twit').val();
	var gp= jQuery('#gp').val();	
			
	jQuery('#loading').show();
	jQuery.ajax(
            {
	    	    type: "POST",
		        url: ajaxurl,
	
		        data : {
			    'action':'maintenance_general_setting_save_post',
			    'status':status,
				'headline':headline,
				'description':description,
				'google_code':google_code,
				'fb':fb,
				'twit':twit,
				'gp':gp,
			   
			        },
                success : function(data){
									jQuery('#loading').hide();
                                   jQuery('#success_message_general_save_settings').fadeIn(500);
								   jQuery("#success_message_general_save_settings").fadeOut(500);
								   location.href='?page=easy-maintenance-mode';
								  
                                   }			
            });
 
 }
</script>
 <?php $value=get_option('easy_maintenance_mode_general_settings'); ?>
 <table class="form-table">
        <tr>
		<th scope="row" class="page-title"><span class="image-title genral-title"></span><?php _e('General Settings','')?></th>
		<td></td>
		</tr>
		
		<tr>
            <th scope="row" ><?php _e('Status','')?></th>
            <td>
                <!-- status tooltip ---->
                <span class="icon help" style="float:right">
                    <span class="tooltip"><?php _e('Enable coming soon page from here.','')?></span>
                </span>

                <input type="radio" name="status" value="0" id="status" <?php if($value['status']=='0') echo 'checked' ?> />&nbsp;<?php _e('Disabled','')?><br>
               
                  <input type="radio" name="status" value="1" id="status"  <?php if($value['status']=='1') echo 'checked' ?> />&nbsp;<?php _e('Enable Maintenance Mode','')?>
            </td>
        </tr>
				<tr>
            <th scope="row" ><?php _e('Headline','')?></th>
            <td>
               <!-- Headline tooltip ---->
                <span class="icon help" style="float:right">
                    <span class="tooltip"><?php _e('Enter your coming page headline which is display before description.','')?></span>
                </span>
				
               <input type="text" class="pro_text" id="headline" name="headline" placeholder="<?php _e('Site coming soon!','')?>" size="56" value="<?php echo $value['headline'] ?>" />
            </td>
        </tr>
		<tr>
			<th scope="row"><?php _e('Description','')?></th>
			<td>
					<!-- Headline tooltip ---->
				<span class="icon help" style="float:right">
					<span class="tooltip"><?php _e('Enter coming soon page description.','')?></span>
				</span>
				
					<textarea rows="6"  class="pro_text" id="description" name="description" placeholder="<?php _e('Page decribe here...','')?>"><?php echo $value['description'] ?></textarea>
			</td>
		</tr>
		<tr >
			<th scope="row"><?php _e('Google Analytics Code','')?></th>
			<td>
					<!-- Google Analytics tooltip ---->
				<span class="icon help" style="float:right">
					<span class="tooltip"><?php _e('Insert the tracking code here. For example Google Analytics code can be inserted here.Get your','')?> <a href="http://www.google.com/analytics/" target="_new"><?php _e('Google Analytics','')?></a> <?php _e('code.','')?></span>
				</span>
				
					<textarea rows="6" class="pro_text"  id="google-code" name="google-code"><?php echo $value['google_code'] ?></textarea>
			</td>
		</tr>
		<tr style="border-bottom:none">
			<th scope="row"><?php _e('Social Media Links','')?></th>
			<td>
			<!-- Social links tooltip ---->
				<span class="icon help" style="float:right">
					<span class="tooltip"><?php _e('Enter your social media url with http://','')?></span>
				</span>
			<div class="description"><?php _e('Enter your social profiles with complete url','')?></div>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
				<!-- facebook field  -->
				<img src="<?php echo WBR_EM_PLUGIN_URL.'/images/social-icons1/facebook.png'; ?>" align="left" style="vertical-align:middle;width:40px;height:40px" />
				<input type="text" name="fb" id="fb" value="<?php echo $value['fb']; ?>" class="profile" placeholder="Enter facbook url"  />
				<br><br>
				 <!-- twitter field -->
				<img src="<?php echo WBR_EM_PLUGIN_URL.'/images/social-icons1/twitter.png'; ?>" align="left" style="vertical-align:middle;width:40px;height:40px" />
				<input type="text" name="twit" id="twit" class="profile" value="<?php echo $value['twit']; ?>" placeholder="Enter twitter url"  />
				<br><br>
				<!-- google plus field with checkbox -->
				<img src="<?php echo WBR_EM_PLUGIN_URL.'/images/social-icons1/googleplus.png'; ?>" align="left" style="vertical-align:middle;width:40px;height:40px" />
				<input type="text" name="gp" id="gp" class="profile" value="<?php echo $value['gp']; ?>" placeholder="Enter google-plus url" />
			</td>
		</tr>
        <tr style="border-bottom:none;">
            <td>
                <input type="button" class="btn btn-primary"  value= "<?php _e('Save Changes', '');?>" onclick="general_setting_save()"/>
				<img id="loading" class="loading" src="<?php echo WBR_EM_PLUGIN_URL.'/images/loading.gif';?>" />
            </td>
           <td>
		     
			 <div id="success_message_general_save_settings" class="success_message" ><?php _e('Data save sucessfully','');?></div>
		   </td>
        </tr>
 </table>
 