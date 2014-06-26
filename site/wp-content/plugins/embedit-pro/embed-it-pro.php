<?php
/* Plugin Name: EmbedIt Pro by SuperThemes
Plugin URI: http://superthemes.org/?p=7279
Description:  Embed any HTML code (Youtube, UStream or whatever HTML) in a post, page or widget, deciding precisely where to embed it, either  on-the-fly from custom fields OR from your saved HTML Code Snippets. Super easy for newbies but powerful for developers, since it supports not only shortcodes but PHP functions too to return or display the snippets in your templates. Visit plugin site for videos and instructions. Ideal also for handling multilanguage labels in WordPress, if used with the qTranslate Multilanguage plugin.
Version: 1.11
Plugin Author: Matteo Ionescu
Author URI: http://www.superthemes.org
 */




 
 /////////////////////// EMBEDIT Reloaded starts here //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
add_filter('widget_text', 'do_shortcode'); //enable shortcodes in widgets too
 
 

function ste_ob_wp_get_content() {
global $post;
ob_start();
the_content();
$output = ob_get_contents();
ob_end_clean();
 return $output; 
}
 
 
 //add custom post type for HTML snippets//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
     add_action( 'init', 'register_cpt_html_snippet' );
    function register_cpt_html_snippet() {
    $labels = array(
    'name' => _x( 'HTML Snippets', 'html_snippet' ),
    'singular_name' => _x( 'HTML Snippet', 'html_snippet' ),
    'add_new' => _x( 'Add New', 'html_snippet' ),
    'add_new_item' => _x( 'Add New HTML Snippet', 'html_snippet' ),
    'edit_item' => _x( 'Edit HTML Snippet', 'html_snippet' ),
    'new_item' => _x( 'New HTML Snippet', 'html_snippet' ),
    'view_item' => _x( 'View HTML Snippet', 'html_snippet' ),
    'search_items' => _x( 'Search HTML Snippets', 'html_snippet' ),
    'not_found' => _x( 'No html snippets found', 'html_snippet' ),
    'not_found_in_trash' => _x( 'No html snippets found in Trash', 'html_snippet' ),
    'parent_item_colon' => _x( 'Parent HTML Snippet:', 'html_snippet' ),
    'menu_name' => _x( 'HTML Snippets', 'html_snippet' ),
    );
    $args = array(
    'labels' => $labels,
    'hierarchical' => false,
    'description' => 'HTML Snippets can be embedded in your posts with the [embedit snippet=Name] shortcode',

    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 6,
    'show_in_nav_menus' => false,
    'publicly_queryable' => false,
    'exclude_from_search' => true,
    'has_archive' => false,
    'query_var' => true,
    'can_export' => true,
    'rewrite' => false,
    'capability_type' => 'post',
    
      //  'supports' => array( 'title' )
	  'supports' => array( 'title', 'editor'  )
	);
    register_post_type( 'html_snippet', $args );
    }
    
    
    
 
/////////SHORTCODE FUNCTION/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
///shortcode handler - the plugin's core functionality! //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
    
 
 function embedit_html_shortcode_handler ($atts)
 
 {
	 global $post;
	   
	   
	 if (isset($atts[cf]))   {
	   
	       return (  get_post_meta($post->ID, $atts[cf],true));
	     
	     }
	 
	 
	 
	 if (isset($atts[snippet]))   {
	   
		$name= ($atts[snippet]);
		
		   // The Query
	       $the_query = new WP_Query( 'showposts=1&post_type=html_snippet&name='.$name );
	       
	       // The Loop
	       $found=FALSE;
	       
	       while ( $the_query->have_posts() ) : $the_query->the_post();
		     
		     $custom_field_html=get_post_meta($post->ID,'html_snippet_content',TRUE);
		     
		     if (strlen(get_the_content())<2)  $out=$custom_field_html; else  $out=ste_ob_wp_get_content();
		     
		     $found=TRUE;
	      
	       endwhile;
	       
	       
	       
	       
		// feedback for the user on the front end
		 global $user_ID;
		 if( $user_ID && !is_admin()&& (is_single() or is_page()) ) 
			 {
			  


			if (!$found )  {
			   
				     
				     
		  
				    return "<div style='padding:20px; font-size:16px; width:95%;text-align:center;background:lightyellow;border:1px solid yellow; margin:20px 0 20px 0'>
				 
				Sorry, no valid slug was used to call the snippet. Try editing a snippet in <a href=\"".get_bloginfo(url)."/wp-admin/edit.php?post_type=html_snippet\">the list</a> to learn how to   call it.
				 
				       <div style=\"float:right;font-size:11px;\">Thank you for using Embedit PRO by <a target='_blank' href=\"http://superthemes.org/\">SuperThemes</a></div>
				 </div>";
			}
			
			 else $out.= "	<div> <a style='bottom: auto;    left: 50px;    position: absolute;    right: auto;    background: none repeat scroll 0 0 #EEEEEE;    border-radius: 3px 3px 3px 3px;    color: #666666;
                                         float: right;    font-size: 10px;    font-weight: 300;    line-height: 1.5em;    padding: 0 8px;    text-decoration: none;' href='".  get_bloginfo(url) ."/wp-admin/post.php?action=edit&post=".$post->ID ."'>Edit Snippet</a></div>";
		     
		     
		      } // end if user_id
	       
	       // Reset Post Data
	       wp_reset_postdata();
       
	   return $out;

 
    
                 }
  
 }
 
 
 
 
 add_shortcode( 'embedit', 'embedit_html_shortcode_handler' );
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 ////tinymce buttons//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
function add_htmlsnippetembed_button() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_htmlsnippetembed_tinymce_plugin");
     add_filter('mce_buttons', 'register_htmlsnippetembed_button');
   }
}
 
function register_htmlsnippetembed_button($buttons) {
   array_push($buttons, "|", "htmlsnippet");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js 
function add_htmlsnippetembed_tinymce_plugin($plugin_array) {
   $plugin_array['htmlsnippet'] = WP_PLUGIN_URL.'/embedit-pro/tinymce_editor_plugin.php';
   return $plugin_array;
}
 
function my_refresh_mce($ver) {
  $ver += 3;
  return $ver;
}

// init process for button control
add_filter( 'tiny_mce_version', 'my_refresh_mce');
add_action('init', 'add_htmlsnippetembed_button');


///////tinymce 2////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
// Add these functions to your functions.php file

 

function add_cf_embed_button() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_cf_embed_tinymce_plugin");
     add_filter('mce_buttons', 'register_cf_embed_button');
   }
}
 
function register_cf_embed_button($buttons) {
   array_push($buttons, "|", "st_cf_embed");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_cf_embed_tinymce_plugin($plugin_array) {
   $plugin_array['st_cf_embed'] = WP_PLUGIN_URL.'/embedit-pro/tinymce_editor_plugin_cf.php';
   return $plugin_array;
}
 
 function my_refresh_mce2($ver) {
  $ver += 3;
  return $ver;
}


// init process for button control
add_filter( 'tiny_mce_version', 'my_refresh_mce2');
add_action('init', 'add_cf_embed_button');


//admin custom css in wp admin//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 

function ste_admin_head() {
        echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('wp-admin.css', __FILE__). '">';
}

add_action('admin_head', 'ste_admin_head');


  
//footer branding
add_filter('admin_footer_text', 'left_admin_footer_text_output'); //left side
function left_admin_footer_text_output($text) {
    $text.=    ' <b>EmbeditPro</b> is brought to you by <a target="_blank" href="http://superthemes.org"><img style="vertical-align:middle"height="20" src="'  .plugins_url('logo.png', __FILE__).'" alt="WordPress PortFolio Themes and more" /></a>  <br />';

    return $text;
}
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 //meta boxes for html///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
 
 
add_action( 'load-post.php', 'embed_it__post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'embed_it__post_meta_boxes_setup' );



 


/* Create one or more meta boxes to be displayed on the post editor screen. */
function embed_it__add_post_meta_boxes() {
                    
                            add_meta_box(
                                    'embed-it-edit-class',			// Unique ID
                                    esc_html__( 'Enter  your   HTML code, and hit the Publish button.  ', 'example' ),		// Title
                                    'embed_it__post_class_meta_box',		// Callback function
                                    'html_snippet',					// Admin page (or post type)
                                    'normal',					// Context
                                    'high'					// Priority
                            );
                    }
                    
                    
                     
global $_POST;




function embed_it__post_class_meta_box( $object, $box ) { ?>
                    
                            <?php wp_nonce_field( basename( __FILE__ ), 'embed_it__post_class_nonce' ); ?>
                            
                            <script>
                                        
                                        
                                         function switchMainEditor()
                                          {
                                         
                                              if( document.getElementById("wp-content-wrap").style.display == "block" )
                                               {
                                                 document.getElementById("wp-content-wrap").style.display = "none";
                                                 
                                                document.getElementById("embed-it-edit-class-textarea").style.display = "block";
                                                 
                                                 
                                                 
                                                 
                                               }
                                               else
                                               {
                                                 document.getElementById("wp-content-wrap").style.display = "block";
                                                 document.getElementById("embed-it-edit-class-textarea").style.display = "none";
                                                 document.getElementById("st-enable-wysiwyg-link").style.display = "none";
                                                    document.getElementById("disable-wysiwyg-suggestion").style.display = "block";
                                                 
                                               }
                                          }
                                        
                                        </script>
                            
					<style>  #content_htmlsnippet,#content_st_cf_embed,.updated a,#post-preview {display:none}
						</style>
					
					<?php  if (strlen($object->post_content)<2) { ?><style> #wp-content-wrap,#post-status-info,#disable-wysiwyg-suggestion {display:none}</style> <?php }
					else { ?> <style> #st-enable-wysiwyg-link,#embed-it-edit-class-textarea {display:none}</style> <?php  } ?>
					
					
					<h3>Paste here the HTML code:</h3>
						 <textarea rows="10" class="widefat" type="text" name="embed-it-edit-class" id="embed-it-edit-class-textarea"><?php echo esc_attr( get_post_meta( $object->ID, 'html_snippet_content', true ) ); ?></textarea>
					   
					   
					   
					<p>
					     <?php if ($object->post_name) { ?>  <div style='padding:20px; font-size:15px; width:85%;text-align:center;background:lightyellow;border:1px solid yellow; margin:20px 0 20px 0'>
											<label for="embed-it-edit-class"><?php _e( "You can embed the entered HTML code in your posts and pages pasting this shortcode: ", '' ); ?>&nbsp;<br /> <b style='font-size:16px'> [embedit snippet="<?= $object->post_name; ?>"] </b><br />
											</label>
											</div>
										<br />
											    
						<?php }Ê?>
					
						   <?php if ($object->post_name) { ?>
						
							<div style='background:lightyellow;width:40%; padding:10px;margin:0 0 30px;float:left '>
								<small>
								
											<i><b>For coders:</b></i><br /> You can call this snippet's title and content in your templates using the PHP code: <br />
										    
										    
										    &lt;?php echo <b>  embed_it_get_snippet_title(<?= $_GET['post']; ?>); </b> ?&gt; 
										    
										    
										    <br />and <br />
										    
										    
										    
										   &lt;?php echo <b>embed_it_get_snippet_content(<?= $_GET['post']; ?>); </b> ?&gt; 
							    <br /><br /><br /><i>
							    This can be extremely useful also if used with the qTranslate multilanguage plugin to handle <b>multilanguage labels</b> in your site's templates or content.</i>
								 </small>
							  </div>  
							    <?php }Ê?>
						<div style=' margin-top:10px;width:45%; padding:10px;margin:0 0 30px;float:right '>
						<span id="st-enable-wysiwyg-link" style="float:right;font-size:10px;margin:20px"> If you prefer, you can   build your snippet  <a href="#" onclick="switchMainEditor()">enabling the WYSIWYG editor</a>.
						<br />
						  </span>
						  
						  <span id="disable-wysiwyg-suggestion"><i>To switch off the WYSIWYG editor, delete all HTML content and click the update button.</i>
						  
						  </span>	
						</div>
						<br clear='all' />
						
						</p>
                            
                                 
<?php } //end function


/* Save post meta on the 'save_post' hook. */
add_action( 'save_post', 'embed_it__save_post_class_meta', 10, 2 );
/* Meta box setup function. */
function embed_it__post_meta_boxes_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'embed_it__add_post_meta_boxes' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'embed_it__save_post_class_meta', 10, 2 );
}



/* Save the meta box's post metadata. */
function embed_it__save_post_class_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['embed_it__post_class_nonce'] ) || !wp_verify_nonce( $_POST['embed_it__post_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;

	/* Get the posted data   */
	$new_meta_value = ( $_POST['embed-it-edit-class'] );

	/* Get the meta key. */
	$meta_key = 'html_snippet_content';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
}






///////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS
////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS/////////////////////
///////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS
////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS/////////////////////
///////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS
////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS/////////////////////
///////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS
////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS/////////////////////
///////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS
////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS/////////////////////
///////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS
////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS/////////////////////
///////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS
////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS////////////////////////////PHP TEMPLATE TAGS / FUNCTIONS/////////////////////




function embed_it_get_snippet_content($my_id)

{
	$post_id_data= get_post($my_id);
	
	$html_editor_content= apply_filters('the_content',$post_id_data->post_content);
	
		     
	if (strlen($html_editor_content)<2)  echo get_post_meta($my_id,'html_snippet_content',TRUE);
		     
				else  echo $html_editor_content;
		     	     
		     
}




function embed_it_get_snippet_title($my_id)

{
	$post_id_data= get_post($my_id);
	if ($_GET[editsnippets]==1) return "<a href='".  get_bloginfo(url) ."/wp-admin/post.php?action=edit&post=".$my_id ."'>".apply_filters('the_title',$post_id_data->post_title)."</a>";
	else
	return apply_filters('the_title',$post_id_data->post_title);
}



 









///////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET
////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET/////////////////////
///////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET
////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET/////////////////////
///////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET
////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET/////////////////////
///////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET
////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET/////////////////////
///////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET
////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET/////////////////////
///////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET
////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET/////////////////////
///////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET
////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET////////////////////////////WIDGET/////////////////////




class SuperThemes_html_snippet_Widget extends WP_Widget {

	//
	//	Constructor
	//
	function SuperThemes_html_snippet_Widget() {

		//	'widget_contentwidget' is the CSS class name assigned to the widget
		//	'description' is the widget description that appears in the 'Available Widgets' list in the backend
		$widget_array = array('classname' => 'widget_contentwidget', 'description' => __('Displays a  HTML Widget') );
		
		//	'st-query-widget', this will be the ID (st-query-widget-1, st-query-widget-2, etc)
		//	__('Random Picture') is the title of the widget in the backend
		$this->WP_Widget('st-query-widget', __('HTML Snippet'), $widget_array);
		
	}
	
	//
	//	widget() - outputs the content of the widget, in our case: a random picture. 
	//
	
	
	
	
	
	
	
	
	
	
	function widget($args,$instance) {
	
		extract($args);

	 

		//	Get the title of the widget and the specified wp_query_parameters of the image
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$wp_query_parameters = empty($instance['wp_query_parameters']) ? ' ' : $instance['wp_query_parameters'];
		$HtmlSnippet_template_id = empty($instance['HtmlSnippet_template_id']) ? ' ' : $instance['HtmlSnippet_template_id'];
			
		//	Outputs the widget in its standard ul li format.
		echo $before_widget;
		if (!empty( $title )) { 
			echo $before_title . $title . $after_title; 
		};
		 
					
			 
		        
					
											
		  echo embed_it_get_snippet_content($HtmlSnippet_template_id );
							  
		
		 wp_reset_postdata(); // reset the query
		 
		echo $after_widget;
		//	Done
	}
	
	
	
	
	
	
	
	
	
	
	
	
	//
	//	update() - processes widget options to be saved.
	//
	function update($new_instance, $old_instance) {
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		 
		$instance['HtmlSnippet_template_id'] = strip_tags($new_instance['HtmlSnippet_template_id']);



		return $instance;
		
	}
	
	
	
	
	
	
	
	
	
	//
	//	form() - outputs the options form on admin in Appearance => Widgets (backend). 
	//
	function form($instance) {

		//	Assigns values
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'wp_query_parameters' => '','HtmlSnippet_template_id'=>'' ) );
		$title = strip_tags($instance['title']);
		$wp_query_parameters = strip_tags($instance['wp_query_parameters']);
		$HtmlSnippet_template_id = strip_tags($instance['HtmlSnippet_template_id']);
		

		
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>


			 
			
			<p><label for="<?php echo $this->get_field_id('HtmlSnippet_template_id'); ?>"><?php echo __('Choose HTML Snippet'); ?>:
			 
			<select style="width:210px" id="<?php echo $this->get_field_id('HtmlSnippet_template_id'); ?>"
			name="<?php echo $this->get_field_name('HtmlSnippet_template_id'); ?>">
			
				<option selected="selected" value="<?= $this->get_field_name('HtmlSnippet_template_id');  ?>"><?= get_the_title($HtmlSnippet_template_id) ?></option>
				
				<?php
				$customPosts = new WP_Query();
				$customPosts->query('post_type=html_snippet');
				
			
				while ($customPosts->have_posts()) : $customPosts->the_post(); $count_snippets++; ?>
					 <option value="<?php the_ID(); ?>"><?php the_title(); ?></option>
				<?php  endwhile; ?>
				
				
				
			</select>
				<?php if (!$customPosts->have_posts()) {?><small>You can <a href="<?= get_bloginfo(url) ?>/wp-admin/post-new.php?post_type=html_snippet">create new snippets</a> if you want.</small><?php } ?>
			</label></p>
		
		
		
		<?php
		
	}

}






//
//	Register the SuperThemes_html_snippet_Widget widget class
//
add_action('widgets_init', create_function('', 'return register_widget("SuperThemes_html_snippet_Widget");'));





///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
//////////////////////////////////////////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////
///////////////////////////////////



?>