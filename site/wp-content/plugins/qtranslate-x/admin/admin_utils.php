<?php

function qtranxf_detect_admin_language($url_info) {
	global $q_config;
	$cs=null;
	$lang=null;
	if(isset($_COOKIE[QTX_COOKIE_NAME_ADMIN])){
		$lang=qtranxf_resolveLangCase($_COOKIE[QTX_COOKIE_NAME_ADMIN],$cs);
		$url_info['lang_cookie_admin'] = $lang;
	}
	if(!$lang){
		$locale = get_locale();
		$url_info['locale'] = $locale;
		$lang = qtranxf_resolveLangCase(substr($locale,0,2),$cs);
		$url_info['lang_locale'] = $lang;
		if(!$lang) $lang = $q_config['default_language'];
	}
	$url_info['doing_front_end'] = false;
	$url_info['lang_admin'] = $lang;
	return $url_info;
}
add_filter('qtranslate_detect_admin_language','qtranxf_detect_admin_language');

function qtranxf_mark_default($text) {
	global $q_config;
	$blocks = qtranxf_get_language_blocks($text);
	if( count($blocks) > 1 ) return $text;//already have other languages.
	$content=array();
	foreach($q_config['enabled_languages'] as $language) {
		if($language == $q_config['default_language']) {
			$content[$language] = $text;
		}else{
			$content[$language] = '';
		}
	}
	return qtranxf_join_c($content);
}

function qtranxf_get_term_joined($obj,$taxonomy=null) {
	global $q_config;
	if(is_object($obj)) {
		// object conversion
		if(isset($q_config['term_name'][$obj->name])) {
			//'[:'.$q_config['language'].']'.$obj->name
			$obj->name = qtranxf_join_b($q_config['term_name'][$obj->name]);
			//qtranxf_dbg_log('qtranxf_get_term_joined: object:',$obj);
		} 
	}elseif(isset($q_config['term_name'][$obj])) {
		$obj = qtranxf_join_b($q_config['term_name'][$obj]);
		//'[:'.$q_config['language'].']'.$obj.
		//qtranxf_dbg_echo('qtranxf_get_term_joined: string:',$obj,true);//never fired, we probably do not need it
	}
	return $obj;
}

function qtranxf_get_terms_joined($terms, $taxonomies=null, $args=null) {
	global $q_config;
	if(is_array($terms)){
		// handle arrays recursively
		foreach($terms as $key => $term) {
			$terms[$key] = qtranxf_get_terms_joined($term);
		}
	}else{
		$terms = qtranxf_get_term_joined($terms);
	}
	return $terms;
}

function qtranxf_useAdminTermLibJoin($obj, $taxonomies=null, $args=null) {
	global $pagenow;
	//qtranxf_dbg_echo('qtranxf_useAdminTermLibJoin: $pagenow='.$pagenow);
	//qtranxf_dbg_echo('qtranxf_useAdminTermLibJoin: $obj:',$obj);
	//qtranxf_dbg_echo('qtranxf_useAdminTermLibJoin: $taxonomies:',$taxonomies);
	//qtranxf_dbg_echo('qtranxf_useAdminTermLibJoin: $args:',$args);
	switch($pagenow){
		case 'nav-menus.php':
		case 'edit-tags.php':
			return qtranxf_get_terms_joined($obj);
		default: return qtranxf_useTermLib($obj);
	}
}
add_filter('get_term', 'qtranxf_useAdminTermLibJoin',0, 2);
add_filter('get_terms', 'qtranxf_useAdminTermLibJoin',0, 3);

//does someone use it?
function qtranxf_useAdminTermLib($obj) {
	//qtranxf_dbg_echo('qtranxf_useAdminTermLib: $obj: ',$obj,true);
	if ($script_name==='/wp-admin/edit-tags.php' &&
		strstr($_SERVER['QUERY_STRING'], 'action=edit' )!==FALSE)
	{
		return $obj;
	}
	else
	{
		return qtranxf_useTermLib($obj);
	}
}
//add_filter('get_term', 'qtranxf_useAdminTermLib',0);
//add_filter('get_terms', 'qtranxf_useAdminTermLib',0);


function qtranxf_updateTermLibrary() {
	global $q_config;
	if(!isset($_POST['action'])) return;
	switch($_POST['action']) {
		case 'editedtag':
		case 'addtag':
		case 'editedcat':
		case 'addcat':
		case 'add-cat':
		case 'add-tag':
		case 'add-link-cat':
			if(isset($_POST['qtrans_term_'.$q_config['default_language']]) && $_POST['qtrans_term_'.$q_config['default_language']]!='') {
				$default = htmlspecialchars(qtranxf_stripSlashesIfNecessary($_POST['qtrans_term_'.$q_config['default_language']]), ENT_NOQUOTES);
				if(!isset($q_config['term_name'][$default]) || !is_array($q_config['term_name'][$default])) $q_config['term_name'][$default] = array();
				foreach($q_config['enabled_languages'] as $lang) {
					$_POST['qtrans_term_'.$lang] = qtranxf_stripSlashesIfNecessary($_POST['qtrans_term_'.$lang]);
					if($_POST['qtrans_term_'.$lang]!='') {
						$q_config['term_name'][$default][$lang] = htmlspecialchars($_POST['qtrans_term_'.$lang], ENT_NOQUOTES);
					} else {
						$q_config['term_name'][$default][$lang] = $default;
					}
				}
				update_option('qtranslate_term_name',$q_config['term_name']);
			}
		break;
	}
}

function qtranxf_updateTermLibraryJoin() {
	global $q_config;
	if(!isset($_POST['action'])) return;
	$action=$_POST['action'];
	if(!isset($_POST['qtrans_term_field_name'])) return;
	$field=$_POST['qtrans_term_field_name'];
	$default_name_original=$_POST['qtrans_term_field_default_name'];
	//qtranxf_dbg_log('$_POST:',$_POST);
	$field_value = qtranxf_stripSlashesIfNecessary($_POST[$field]);
	//qtranxf_dbg_log('$field_value='.$field_value);
	$names=qtranxf_split($field_value);
	//qtranxf_dbg_log('names=',$names);
	$default_name=htmlspecialchars($names[$q_config['default_language']], ENT_NOQUOTES);
	$_POST[$field]=$default_name;
	if(empty($default_name))
		return;//will generate error later from WP
	foreach($names as $lang => $name){
		$q_config['term_name'][$default_name_original][$lang] = htmlspecialchars($name, ENT_NOQUOTES);
	}
	if($default_name_original != $default_name){
		$q_config['term_name'][$default_name]=$q_config['term_name'][$default_name_original];
		unset($q_config['term_name'][$default_name_original]);
	}
	update_option('qtranslate_term_name',$q_config['term_name']);
}

/*
function qtranxf_edit_terms($term_id, $taxonomy){
	//qtranxf_dbg_log('qtranxf_edit_terms: $name='.$name);
}
add_action('edit_terms','qtranxf_edit_terms');
*/

function qtranxf_language_columns($columns) {
	return array(
		'flag' => __('Flag', 'qtranslate'),
		'name' => __('Name', 'qtranslate'),
		'status' => __('Action', 'qtranslate'),
		'status2' => '',
		'status3' => ''
		);
}

function qtranxf_languageColumnHeader($columns){
	$new_columns = array();
	if(isset($columns['cb'])) $new_columns['cb'] = '';
	if(isset($columns['title'])) $new_columns['title'] = '';
	if(isset($columns['author'])) $new_columns['author'] = '';
	if(isset($columns['categories'])) $new_columns['categories'] = '';
	if(isset($columns['tags'])) $new_columns['tags'] = '';
	$new_columns['language'] = __('Languages', 'qtranslate');
	return array_merge($new_columns, $columns);;
}

function qtranxf_languageColumn($column) {
	global $q_config, $post;
	if ($column == 'language') {
		$available_languages = qtranxf_getAvailableLanguages($post->post_content);
		$missing_languages = array_diff($q_config['enabled_languages'], $available_languages);
		$available_languages_name = array();
		$missing_languages_name = array();
		foreach($available_languages as $language) {
			$available_languages_name[] = $q_config['language_name'][$language];
		}
		$available_languages_names = join(", ", $available_languages_name);
		
		echo apply_filters('qtranslate_available_languages_names',$available_languages_names);
		do_action('qtranslate_languageColumn', $available_languages, $missing_languages);
	}
	return $column;
}

function qtranxf_admin_list_cats($text) {
	global $pagenow;
	//qtranxf_dbg_echo('qtranxf_admin_list_cats: $text',$text);
	switch($pagenow){
		case 'edit-tags.php':
			//replace [:] with <:>
			$blocks = qtranxf_get_language_blocks($text);
			if(count($blocks)<=1) return $text;
			$texts = qtranxf_split_blocks($blocks);
			$text = qtranxf_join_c($texts);
			return $text;
		default: return qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($text);
	}
}
add_filter('list_cats', 'qtranxf_admin_list_cats',0);

function qtranxf_admin_dropdown_cats($text) {
	global $pagenow;
	//qtranxf_dbg_echo('qtranxf_admin_list_cats: $text',$text);
	switch($pagenow){
		case 'edit-tags.php':
			return $text;
		default: return qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($text);
	}
}
add_filter('wp_dropdown_cats', 'qtranxf_admin_dropdown_cats',0);

function qtranxf_admin_category_description($text) {
	global $pagenow;
	switch($pagenow){
		case 'edit-tags.php':
			return $text;
		default: return qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($text);
	}
}
add_filter('category_description', 'qtranxf_admin_category_description',0);

function qtranxf_admin_the_title($title) {
	global $pagenow;
	switch($pagenow){
		//case 'edit-tags.php':
		case 'nav-menus.php':
			return $title;
		default: return qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($title);
	}
}
add_filter('the_title', 'qtranxf_admin_the_title', 0);//WP: fires for display purposes only

//filter added in qtranslate_hooks.php
function qtranxf_trim_words( $text, $num_words, $more, $original_text ) {
	global $q_config;
	//qtranxf_dbg_log('qtranxf_trim_words: $text: ',$text);
	//qtranxf_dbg_log('qtranxf_trim_words: $original_text: ',$original_text);
	$blocks = qtranxf_get_language_blocks($original_text);
	//qtranxf_dbg_log('qtranxf_trim_words: $blocks: ',$blocks);
	if ( count($blocks) <= 1 )
		return $text;
	$lang = $q_config['language'];
	$texts = qtranxf_split_blocks($blocks);
	foreach($texts as $key => $txt){
		$texts[$key] = wp_trim_words($txt, $num_words, $more);
	}
	return qtranxf_join_b($texts);//has to be 'b', because 'c' gets stripped in /wp-admin/includes/nav-menu.php:182: esc_html( $item->description )
}

/**
 * The same as core wp_htmledit_pre in /wp-includes/formatting.php,
 * but with last argument of htmlspecialchars $double_encode off,
 * which makes it to survive multiple applications from other plugins,
 * for example, "PS Disable Auto Formatting" (https://wordpress.org/plugins/ps-disable-auto-formatting/)
 * cited on support thread https://wordpress.org/support/topic/incompatibility-with-ps-disable-auto-formatting.
 * @since 2.9.8.9
*/
if(!function_exists('qtranxf_htmledit_pre')){
function qtranxf_htmledit_pre($output) {
	if ( !empty($output) )
		$output = htmlspecialchars($output, ENT_NOQUOTES, get_option( 'blog_charset' ), false ); // convert only < > &
	return apply_filters( 'htmledit_pre', $output );
}
}

function qtranxf_the_editor($editor_div)
{
	// remove wpautop, which causes unmatched <p> on combined language strings
	if('html' != wp_default_editor()) {
		remove_filter('the_editor_content', 'wp_richedit_pre');
		add_filter('the_editor_content', 'qtranxf_htmledit_pre', 99);
	}
	return $editor_div;
}
//applied in /wp-includes/class-wp-editor.php
add_filter('the_editor', 'qtranxf_the_editor');

add_filter('manage_language_columns', 'qtranxf_language_columns');
add_filter('manage_posts_columns', 'qtranxf_languageColumnHeader');
add_filter('manage_posts_custom_column', 'qtranxf_languageColumn');
add_filter('manage_pages_columns', 'qtranxf_languageColumnHeader');
add_filter('manage_pages_custom_column', 'qtranxf_languageColumn');
?>
