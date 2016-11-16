<?php
/*
Plugin Name: MLA UI Elements Example
Plugin URI: http://fairtradejudaica.org/media-library-assistant-a-wordpress-plugin/
Description: Provides shortcodes to improve user experience for [mla_term_list], [mla_tag_cloud] and [mla_gallery] shortcodes
Author: David Lingren
Version: 1.02
Author URI: http://fairtradejudaica.org/our-story/staff/

Copyright 2016 David Lingren

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You can get a copy of the GNU General Public License by writing to the
	Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA
*/

/*
 * User notes
 *
 * 1. If you add "use_filters=true" to an [mla_term_list] shortcode this plugin will retain the selected
 *    terms when the page is refreshed and pass them back into the shortcode.
 *
 * 2. If you add "add_filters_to=any" to an [mla_gallery] shortcode this plugin will retain settings for
 *    terms search, keyword search, taxonomy queries and posts_per_page when the page is refreshed or
 *    pagination moves to a new page.
 *
 * 3. If you add "add_filters_to=<taxonomy_slug>" to an [mla_gallery] shortcode this plugin will do the
 *    actions in 2. and will also match the taxonomy_slug to a simple taxonomy query (if present) and
 *    add that query to the taxonomy queries. If the simple query is 'muie-no-terms', it will be ignored.
 *
 * 4. Three shortcodes are provided to generate text box controls and retain their settings when the page 
 *    is refreshed or pagination moves to a new page:
 *
 *    [muie_per_page] generates an items per page text box
 *    [muie_terms_search] generates a terms search text box
 *    [muie_keyword_search] generates a keyword search text box
 *
 * 5. With a bit of work you can add a tag cloud that works with these filters. Here's an example you can
 * adapt for your application:
 *
 * <style type='text/css'>
 * #mla-tag-cloud .mla_current_item {
 * 	color:#FF0000;
 * 	font-weight:bold}
 * </style>
 * <span id=mla-tag-cloud>
 * <strong>Tag Cloud</strong>
 * [mla_tag_cloud taxonomy=attachment_tag number=20 current_item="{+request:current_item+}" mla_link_href="{+page_url+}?current_item={+term_id+}&tax_input{{+query:taxonomy+}}{}={+slug+}&muie_per_page={+template:({+request:muie_per_page+}|5)+}" mla_link_class="{+current_item_class+}"]
 * </span>
 */
 
/**
 * Class MLA UI Elements Example provides shortcodes to improve user experience for
 * [mla_term_list], [mla_tag_cloud] and [mla_gallery] shortcodes
 *
 * Created for support topic "How do I provide a front-end search of my media items using Custom Fields?"
 * opened on 4/15/2016 by "direys".
 * https://wordpress.org/support/topic/how-do-i-provide-a-front-end-search-of-my-media-items-using-custom-fields
 *
 * Enhanced for support topic "Dynamic search and filters"
 * opened on 5/28/2016 by "ghislainsc".
 * https://wordpress.org/support/topic/dynamic-search-and-filters
 *
 * Enhanced for support topic "Limiting search results to attachment tags/'Justifying' gallery grids""
 * opened on 7/2/2016 by "ceophoetography".
 * https://wordpress.org/support/topic/limiting-search-results-to-attachment-tagsjustifying-gallery-grids
 *
 * @package MLA UI Elements Example
 * @since 1.00
 */
class MLAUIElementsExample {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 1.00
	 */
	public static function initialize() {
		// The filters are only useful for front-end posts/pages; exit if in the admin section
		if ( is_admin() )
			return;

		add_filter( 'mla_term_list_attributes', 'MLAUIElementsExample::mla_term_list_attributes', 10, 1 );
		add_filter( 'mla_gallery_attributes', 'MLAUIElementsExample::mla_gallery_attributes', 10, 1 );

		// Add the custom shortcode for generating the items per page
		add_shortcode( 'muie_per_page', 'MLAUIElementsExample::muie_per_page' );

		// Add the custom shortcode for generating "sticky" term search text box
		add_shortcode( 'muie_terms_search', 'MLAUIElementsExample::muie_terms_search' );

		// Add the custom shortcode for generating "sticky" keyword search text box
		add_shortcode( 'muie_keyword_search', 'MLAUIElementsExample::muie_keyword_search' );

		// Add the custom shortcode for generating assigned terms counts
		add_shortcode( 'muie_assigned_items_count', 'MLAUIElementsExample::muie_assigned_items_count' );
	}

	/**
	 * Look for 'muie_filters' that pass the selected parameters from page to page of a paginated gallery
	 *
	 * The $shortcode_attributes array is where you will find any of your own parameters that are coded in the
	 * shortcode, e.g., [mla_term_list use_filters=true].
	 *
	 * @since 1.00
	 *
	 * @param	array	the shortcode parameters passed in to the shortcode
	 */
	public static function mla_term_list_attributes( $shortcode_attributes ) {

		// See if this is a "filtered" term list
		if ( !empty( $shortcode_attributes['use_filters'] )  && ( 'true' == strtolower( $shortcode_attributes['use_filters'] ) ) ) {
			// Pagination links, e.g. Previous or Next, have muie_filters that encode the form parameters
			if ( !empty( $_REQUEST['muie_filters'] ) ) {
				$filters = json_decode( trim( stripslashes( $_REQUEST['muie_filters'] ), '"' ), true );

				if ( !empty( $filters['tax_input'] ) ) {
					$_REQUEST['tax_input'] = $filters['tax_input'];
				}
			}

			// If nothing is set for this taxonomy we're done
			if ( empty( $_REQUEST['tax_input'] ) || !array_key_exists( $shortcode_attributes['taxonomy'], $_REQUEST['tax_input'] ) ) {
				return $shortcode_attributes;
			}

			$terms = $_REQUEST['tax_input'][ $shortcode_attributes['taxonomy'] ];

			// Check for a dropdown control with "All Terms" selected
			$option_all = array_search( '0', $terms );
			if ( false !== $option_all ) {
				unset( $terms[ $option_all ] );
			}

			// Pass selected terms to the shortcode
			if ( !empty( $terms ) ) {
				$shortcode_attributes[ $shortcode_attributes['mla_item_parameter'] ] = implode( ',', $_REQUEST['tax_input'][ $shortcode_attributes['taxonomy'] ] );
			}

			unset( $shortcode_attributes['use_filters'] );
		}

		return $shortcode_attributes;
	} // mla_term_list_attributes

	/**
	 * Add the taxonomy query to the shortcode, limit posts_per_page and encode filters for pagination links
	 *
	 * The $shortcode_attributes array is where you will find any of your own parameters that are coded in the
	 * shortcode, e.g., [mla_gallery random_category="abc"].
	 *
	 * @since 1.00
	 *
	 * @param	array	the shortcode parameters passed in to the shortcode
	 */
	public static function mla_gallery_attributes( $shortcode_attributes ) {
		/*
		 * Only process shortcodes that allow filters
		 */
		if ( empty( $shortcode_attributes['add_filters_to'] ) ) {
			return $shortcode_attributes;
		}

		// Unpack filter values encoded for pagination links
		if ( !empty( $_REQUEST['muie_filters'] ) ) {
			$filters = json_decode( trim( stripslashes( $_REQUEST['muie_filters'] ), '"' ), true );

			if ( isset( $filters['muie_terms_search'] ) ) {
				$_REQUEST['muie_terms_search'] = $filters['muie_terms_search'];
			}

			if ( isset( $filters['muie_keyword_search'] ) ) {
				$_REQUEST['muie_keyword_search'] = $filters['muie_keyword_search'];
			}

			if ( isset( $filters['tax_input'] ) ) {
				$_REQUEST['tax_input'] = $filters['tax_input'];
			}
		}

		// Adjust posts_per_page/numberposts
		if ( !empty( $_REQUEST['muie_per_page'] ) ) {
			if ( isset( $shortcode_attributes['numberposts'] ) && ! isset( $shortcode_attributes['posts_per_page'] )) {
				$shortcode_attributes['posts_per_page'] = $shortcode_attributes['numberposts'];
				unset( $shortcode_attributes['numberposts'] );
			}

			$shortcode_attributes['posts_per_page'] = $_REQUEST['muie_per_page'];
		}

		// Add the terms search parameters, if present
		if ( !empty( $_REQUEST['muie_terms_search'] ) && is_array( $_REQUEST['muie_terms_search'] ) && !empty( $_REQUEST['muie_terms_search']['mla_terms_phrases'] ) ) {
			$muie_terms_search = $_REQUEST['muie_terms_search'];
			foreach( $muie_terms_search as $key => $value ) {
				if ( !empty( $value ) ) {
					$shortcode_attributes[ $key ] = $value;
				}
			}
		} else {
			$muie_terms_search = array();
		}

		// Add the keyword search parameters, if present
		if ( !empty( $_REQUEST['muie_keyword_search'] ) && is_array( $_REQUEST['muie_keyword_search'] ) && !empty( $_REQUEST['muie_keyword_search']['s'] ) ) {
			$muie_keyword_search = $_REQUEST['muie_keyword_search'];
			foreach( $muie_keyword_search as $key => $value ) {
				if ( !empty( $value ) ) {
					$shortcode_attributes[ $key ] = $value;
				}
			}
		} else {
			$muie_keyword_search = array();
		}

		// Add the taxonomy filter(s), if present
		$filter_taxonomy = $shortcode_attributes['add_filters_to'];
		$tax_input = !empty( $_REQUEST['tax_input'] ) ? $_REQUEST['tax_input'] : array();

		if ( ! ( empty( $shortcode_attributes[ $filter_taxonomy ] ) && empty( $tax_input ) ) ) {
			$tax_query = '';

			// Look for the optional "simple taxonomy query" as an initial filter
			if ( !empty( $shortcode_attributes[ $filter_taxonomy ] ) ) {
				if ( 'muie-no-terms' !== $shortcode_attributes[ $filter_taxonomy ] ) {
					$values = "array( '" . implode( "', '", explode( ',', $shortcode_attributes[ $filter_taxonomy ] ) ) . "' )";
					$tax_query .= "array('taxonomy' => '{$filter_taxonomy}' ,'field' => 'slug','terms' => {$values}, 'operator' => 'IN'), ";
				}

				unset( $shortcode_attributes[ $filter_taxonomy ] );
			}

			foreach ( $tax_input as $taxonomy => $terms ) {
				// simple taxonomy query overrides tax_input
				if ( $taxonomy == $filter_taxonomy ) {
					continue;
				}

				// Check for a dropdown control with "All Terms" selected
				$option_all = array_search( '0', $terms );
				if ( false !== $option_all ) {
					unset( $terms[ $option_all ] );
				}

				if ( !empty( $terms ) ) {
					$field = 'term_id';
					foreach ( $terms as $term ) {
						if ( ! is_integer( $term ) ) {
							$field = 'slug';
							break;
						}
					}
					
					if ( 'term_id' == $field ) {
						$values = 'array( ' . implode( ',', $terms ) . ' )';
					} else {
						$values = "array( '" . implode( "','", $terms ) . "' )";
					}
					
					$tax_query .= "array('taxonomy' => '{$taxonomy}' ,'field' => '{$field}','terms' => {$values}, 'operator' => 'IN'), ";
				}
			}

			if ( ! empty( $tax_query ) ) {
				$shortcode_attributes['tax_query'] = "array( 'relation' => 'AND', " . $tax_query . ')';
			}
		}

		/*
		 * Add the filter settings to pagination URLs
		 */
		if ( !empty( $shortcode_attributes['mla_output'] ) ) {

			$filters = urlencode( json_encode( array( 'tax_input' => $tax_input, 'muie_terms_search' => $muie_terms_search, 'muie_keyword_search' => $muie_keyword_search ) ) );
			$shortcode_attributes['mla_link_href'] = '[+new_url+]?[+new_page_text+]&muie_filters=' . $filters;

			if ( !empty( $shortcode_attributes['posts_per_page'] ) ) {
				$shortcode_attributes['mla_link_href'] .= '&muie_per_page=' . $shortcode_attributes['posts_per_page'];
			}
		}

		unset( $shortcode_attributes['add_filters_to'] );
		return $shortcode_attributes;
	} // mla_gallery_attributes

	/**
	 * Items per page shortcode
	 *
	 * This shortcode generates an HTML text box with a default muie_per_page value.
	 *
	 * @since 1.00
	 *
	 * @param	array	the shortcode parameters
	 *
	 * @return	string	HTML markup for the generated form
	 */
	public static function muie_per_page( $attr ) {
		if ( isset( $attr['numberposts'] ) && ! isset( $attr['posts_per_page'] )) {
			$attr['posts_per_page'] = $attr['numberposts'];
			unset( $attr['numberposts'] );
		}

		if ( !empty( $_REQUEST['muie_per_page'] ) ) {
			$posts_per_page = $_REQUEST['muie_per_page'];
		} else {
			$posts_per_page = isset( $attr['posts_per_page'] ) ? $attr['posts_per_page'] : 6;
		}

		return '<input name="muie_per_page" id="muie-per-page" type="text" size="2" value="' . $posts_per_page . '" />';
	} // muie_per_page

	/**
	 * Terms search generator shortcode
	 *
	 * This shortcode generates an HTML text box with a default mla_terms_phrases value,
	 * and adds hidden parameters for the other Terms Search parameters
	 *
	 * @since 1.00
	 *
	 * @param	array	the shortcode parameters
	 *
	 * @return	string	HTML markup for the generated form
	 */
	public static function muie_terms_search( $attr ) {
		$default_arguments = array(
			'mla_terms_phrases' => '',
			'mla_terms_taxonomies' => '',
			'mla_phrase_delimiter' => '',
			'mla_term_delimiter' => '',
			'mla_phrase_connector' => '',
			'mla_term_delimiter' => '',
			'mla_term_connector' => '',
		);

		// Make sure $attr is an array, even if it's empty
		if ( empty( $attr ) ) {
			$attr = array();
		} elseif ( is_string( $attr ) ) {
			$attr = shortcode_parse_atts( $attr );
		}

		// Accept only the attributes we need and supply defaults
		$arguments = shortcode_atts( $default_arguments, $attr );

		// Pagination links, e.g. Previous or Next, have muie_filters that encode the form parameters
		if ( !empty( $_REQUEST['muie_filters'] ) ) {
			$filters = json_decode( trim( stripslashes( $_REQUEST['muie_filters'] ), '"' ), true );

			if ( !empty( $filters['muie_terms_search'] ) ) {
				$_REQUEST['muie_terms_search'] = $filters['muie_terms_search'];
			}
		}

		// muie_terms_search has settings from the form or pagination link
		if ( !empty( $_REQUEST['muie_terms_search'] ) && is_array( $_REQUEST['muie_terms_search'] ) ) {
			foreach ( $arguments as $key => $value ) {
				if ( !empty( $_REQUEST['muie_terms_search'][ $key ] ) ) {
					$arguments[ $key ] = stripslashes( $_REQUEST['muie_terms_search'][ $key ] );
				}
			}
		}

		// Always supply the terms phrases text box, with the appropriate quoting
		if ( false !== strpos( $arguments['mla_terms_phrases'], '"' ) ) {
			$delimiter = '\'';
		} else {
			$delimiter = '"';
		}

		$return_value = '<input name="muie_terms_search[mla_terms_phrases]" id="muie-terms-phrases" type="text" size="20" value=' . $delimiter . $arguments['mla_terms_phrases'] . $delimiter . " />\n";		
		unset( $arguments['mla_terms_phrases'] );

		// Add optional parameters
		foreach( $arguments as $key => $value ) {
			if ( !empty( $value ) ) {
				$id_value = str_replace( '_', '-', substr( $key, 4 ) );
				$return_value .= sprintf( '<input name="muie_terms_search[%1$s]" id="muie-%2$s" type="hidden" value="%3$s" />%4$s', $key, $id_value, $value, "\n" );		
			}
		}

		return $return_value;
	} // muie_terms_search

	/**
	 * Keyword search generator shortcode
	 *
	 * This shortcode generates an HTML text box with a default "s" (search string) value,
	 * and adds hidden parameters for the other Keyword Search parameters
	 *
	 * @since 1.00
	 *
	 * @param	array	the shortcode parameters
	 *
	 * @return	string	HTML markup for the generated form
	 */
	public static function muie_keyword_search( $attr ) {
		$default_arguments = array(
			's' => '',
			'mla_search_fields' => '',
			'mla_search_connector' => '',
			'sentence' => '',
			'exact' => '',
		);

		// Make sure $attr is an array, even if it's empty
		if ( empty( $attr ) ) {
			$attr = array();
		} elseif ( is_string( $attr ) ) {
			$attr = shortcode_parse_atts( $attr );
		}

		// Accept only the attributes we need and supply defaults
		$arguments = shortcode_atts( $default_arguments, $attr );

		// Pagination links, e.g. Previous or Next, have muie_filters that encode the form parameters
		if ( !empty( $_REQUEST['muie_filters'] ) ) {
			$filters = json_decode( trim( stripslashes( $_REQUEST['muie_filters'] ), '"' ), true );

			if ( !empty( $filters['muie_keyword_search'] ) ) {
				$_REQUEST['muie_keyword_search'] = $filters['muie_keyword_search'];
			}
		}

		// muie_keyword_search has settings from the form or pagination link
		if ( !empty( $_REQUEST['muie_keyword_search'] ) && is_array( $_REQUEST['muie_keyword_search'] ) ) {
			foreach ( $arguments as $key => $value ) {
				if ( !empty( $_REQUEST['muie_keyword_search'][ $key ] ) ) {
					$arguments[ $key ] = stripslashes( $_REQUEST['muie_keyword_search'][ $key ] );
				}
			}
		}

		// Always supply the search text box, with the appropriate quoting
		if ( false !== strpos( $arguments['s'], '"' ) ) {
			$delimiter = '\'';
		} else {
			$delimiter = '"';
		}

		$return_value = '<input name="muie_keyword_search[s]" id="muie-s" type="text" size="20" value=' . $delimiter . $arguments['s'] . $delimiter . " />\n";		
		unset( $arguments['s'] );

		// Add optional parameters
		foreach( $arguments as $key => $value ) {
			if ( !empty( $value ) ) {
				$id_value = str_replace( '_', '-', substr( $key, 4 ) );
				$return_value .= sprintf( '<input name="muie_keyword_search[%1$s]" id="muie-%2$s" type="hidden" value="%3$s" />%4$s', $key, $id_value, $value, "\n" );		
			}
		}

		return $return_value;
	} // muie_keyword_search

	/**
	 * Assigned items count shortcode
	 *
	 * This shortcode returns the number of items assigned to any term(s) in the selected taxonomy
	 *
	 * @since 1.01
	 *
	 * @param	array	the shortcode parameters
	 *
	 * @return	string	HTML markup for the generated form
	 */
	public static function muie_assigned_items_count( $attr ) {
		global $wpdb;

		$default_arguments = array(
			'taxonomy' => '',
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => 'image',
		);

		// Make sure $attr is an array, even if it's empty
		if ( empty( $attr ) ) {
			$attr = array();
		} elseif ( is_string( $attr ) ) {
			$attr = shortcode_parse_atts( $attr );
		}

		// Accept only the attributes we need and supply defaults
		$arguments = shortcode_atts( $default_arguments, $attr );
		
		/*
		 * Build an array of individual clauses that can be filtered
		 */
		$clauses = array( 'fields' => '', 'join' => '', 'where' => '', 'order' => '', 'orderby' => '', 'limits' => '', );

		$clause_parameters = array();

		$clause[] = 'LEFT JOIN `' . $wpdb->term_relationships . '` AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id';
		$clause[] = 'LEFT JOIN `' . $wpdb->posts . '` AS p ON tr.object_id = p.ID';

		/*
		 * Add type and status constraints
		 */
		if ( is_array( $arguments['post_type'] ) ) {
			$post_types = $arguments['post_type'];
		} else {
			$post_types = array( $arguments['post_type'] );
		}

		$placeholders = array();
		foreach ( $post_types as $post_type ) {
			$placeholders[] = '%s';
			$clause_parameters[] = $post_type;
		}

		$clause[] = 'AND p.post_type IN (' . join( ',', $placeholders ) . ')';

		if ( is_array( $arguments['post_status'] ) ) {
			$post_stati = $arguments['post_status'];
		} else {
			$post_stati = array( $arguments['post_status'] );
		}

		$placeholders = array();
		foreach ( $post_stati as $post_status ) {
			if ( ( 'private' != $post_status ) || is_user_logged_in() ) {
				$placeholders[] = '%s';
				$clause_parameters[] = $post_status;
			}
		}
		$clause[] = 'AND p.post_status IN (' . join( ',', $placeholders ) . ')';

		$clause =  join(' ', $clause);
		$clauses['join'] = $wpdb->prepare( $clause, $clause_parameters );

		/*
		 * Start WHERE clause with a taxonomy constraint
		 */
		if ( is_array( $arguments['taxonomy'] ) ) {
			$taxonomies = $arguments['taxonomy'];
		} else {
			$taxonomies = array( $arguments['taxonomy'] );
		}

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! taxonomy_exists( $taxonomy ) ) {
				$error = new WP_Error( 'invalid_taxonomy', __( 'Invalid taxonomy', 'media-library-assistant' ), $taxonomy );
				return $error;
			}
		}

		$clause_parameters = array();
		$placeholders = array();
		foreach ($taxonomies as $taxonomy) {
		    $placeholders[] = '%s';
			$clause_parameters[] = $taxonomy;
		}

		$clause = array( 'tt.taxonomy IN (' . join( ',', $placeholders ) . ')' );
		if ( 'all' !== strtolower( $arguments['post_mime_type'] ) ) {
			$clause[] = str_replace( '%', '%%', wp_post_mime_type_where( $arguments['post_mime_type'], 'p' ) );
		}

		$clause =  join(' ', $clause);
		$clauses['where'] = $wpdb->prepare( $clause, $clause_parameters );

		/*
		 * Build the final query
		 */
		$query = array( 'SELECT' );
		$query[] = 'COUNT(*)'; // 'p.ID'; // $clauses['fields'];
		$query[] = 'FROM ( SELECT DISTINCT p.ID FROM `' . $wpdb->term_taxonomy . '` AS tt';
		$query[] = $clauses['join'];
		$query[] = 'WHERE (';
		$query[] = $clauses['where'];
		$query[] = ') ) as subquery';
		
		$query =  join(' ', $query);
		$count = $wpdb->get_var( $query );
		return number_format( (float) $count );
	} // muie_assigned_items_count
} // Class MLAUIElementsExample

/*
 * Install the filters at an early opportunity
 */
add_action('init', 'MLAUIElementsExample::initialize');
?>