<?php
/**
 * Media Library Assistant Custom Style/Markup Template handler(s)
 *
 * @package Media Library Assistant
 * @since 2.30
 */

/**
 * Class MLA (Media Library Assistant) Custom Style/Markup Template Support provides functions that
 * define, import and export custom style and markup templates for MLA shortcodes.
 *
 * @package Media Library Assistant
 * @since 2.30
 */
class MLATemplate_Support {
	/**
	 * $mla_template_definitions defines the structure of the style and markup templates
	 * and the labels, etc. required to render them in the Settings/Shortcodes tab
	 *
	 * The array must be populated at runtime in MLATemplate_Support::mla_localize_template_definitions();
	 * localization calls cannot be placed in the "public static" array definition itself.
	 *
	 * Template types are indexed by 'style'/'markup' and then shortcode name. Each template type is defined by:
	 *     'slug' =>  Sanitized version of the shortcode name
	 *     'default_names' => Names of the default templates
	 *     'label' => Styles only; label for the template textbox
	 *     'help' => Styles only; help text displayed below the textbox
	 *     'sections' => Markup only; array of template section definitions
	 *     'sections'[] elements:
	 *         'label' => Label for the section textbox
	 *         'help' => Help text displayed below the textbox
	 *         'order' => Where the section appears in the template
	 */
	 
	public static $mla_template_definitions = array ();

	/**
	 * Localize $mla_option_definitions array
	 *
	 * Localization must be done at runtime; these calls cannot be placed in the
	 * "public static" array definition itself. Called from MLATest::initialize.
	 *
	 * @since 2.30
	 *
	 * @return	void
	 */
	public static function mla_localize_template_definitions() {
		self::$mla_template_definitions = array (
			'style' => array(
				'gallery' => array(
					'slug' => 'gallery',
					'default_names' => array( 'default' ),
					'label' => __( 'Styles', 'media-library-assistant' ),
					'help' => __( 'List of substitution parameters, e.g., [+selector+], on Documentation tab.', 'media-library-assistant' ),
				),
				'tag_cloud' => array(
					'slug' => 'tag-cloud',
					'default_names' => array( 'tag-cloud' ),
					'label' => __( 'Styles', 'media-library-assistant' ),
					'help' => __( 'List of substitution parameters, e.g., [+selector+], on Documentation tab.', 'media-library-assistant' ),
				),
				'term_list' => array(
					'slug' => 'term-list',
					'default_names' => array( 'term-list' ),
					'label' => __( 'Styles', 'media-library-assistant' ),
					'help' => __( 'List of substitution parameters, e.g., [+selector+], on Documentation tab.', 'media-library-assistant' ),
				),
			),
			'markup' => array(
				'gallery' => array(
					'slug' => 'gallery',
					'default_names' => array( 'default' ),
					'sections' => array(
						'arguments' => array(
							'label' => __( 'Arguments', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Default shortcode parameter values.', 'media-library-assistant' ),
							'order' => 0,
						),
						'row-open' => array(
							'label' => __( 'Row', 'media-library-assistant' ) . '&nbsp;' . __( 'Open', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the beginning of each row in the gallery.', 'media-library-assistant' ),
							'order' => 2,
						),
						'open' => array(
							'label' => __( 'Open', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the beginning of the gallery. List of parameters, e.g., [+selector+], on Documentation tab.', 'media-library-assistant' ),
							'order' => 1,
						),
						'item' => array(
							'label' => __( 'Item', 'media-library-assistant' ),
							'rows' => 6,
							'help' => __( 'Markup for each item/cell of the gallery.', 'media-library-assistant' ),
							'order' => 3,
						),
						'row-close' => array(
							'label' => __( 'Row', 'media-library-assistant' ) . '&nbsp;' . __( 'Close', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the end of each row in the gallery.', 'media-library-assistant' ),
							'order' => 4,
						),
						'close' => array(
							'label' => __( 'Close', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the end of the gallery.', 'media-library-assistant' ),
							'order' => 5,
						),
					),
				),
				'tag_cloud' => array(
					'slug' => 'tag-cloud',
					'default_names' => array( 'tag-cloud', 'tag-cloud-ul', 'tag-cloud-dl' ),
					'sections' => array(
						'arguments' => array(
							'label' => __( 'Arguments', 'media-library-assistant' ),
							'rows' => 3,
							'help' =>  __( 'Default shortcode parameter values.', 'media-library-assistant' ),
							'order' => 0,
						),
						'row-open' => array(
							'label' => __( 'Row', 'media-library-assistant' ) . '&nbsp;' . __( 'Open', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the beginning of each row in the cloud; grid format only.', 'media-library-assistant' ),
							'order' => 2,
						),
						'open' => array(
							'label' => __( 'Open', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the beginning of the cloud. List of parameters, e.g., [+selector+], on Documentation tab.', 'media-library-assistant' ),
							'order' => 1,
						),
						'item' => array(
							'label' => __( 'Item', 'media-library-assistant' ),
							'rows' => 6,
							'help' => __( 'Markup for each item/cell of the cloud.', 'media-library-assistant' ),
							'order' => 3,
						),
						'row-close' => array(
							'label' => __( 'Row', 'media-library-assistant' ) . '&nbsp;' . __( 'Close', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the end of each row in the cloud; grid format only.', 'media-library-assistant' ),
							'order' => 4,
						),
						'close' => array(
							'label' => __( 'Close', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the end of the cloud.', 'media-library-assistant' ),
							'order' => 5,
						),
					),
				),
				'term_list' => array(
					'slug' => 'term-list',
					'default_names' => array( 'term-list-ul', 'term-list-dl', 'term-list-dropdown', 'term-list-checklist' ),
					'sections' => array(
						'arguments' => array(
							'label' => __( 'Arguments', 'media-library-assistant' ),
							'rows' => 3,
							'help' =>  __( 'Default shortcode parameter values.', 'media-library-assistant' ),
							'order' => 0,
						),
						'child-open' => array(
							'label' => __( 'Child', 'media-library-assistant' ) . '&nbsp;' . __( 'Open', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the beginning of each level in the hierarchy; list format only.', 'media-library-assistant' ),
							'order' => 3,
						),
						'child-item' => array(
							'label' => __( 'Child', 'media-library-assistant' ) . '&nbsp;' . __( 'Item', 'media-library-assistant' ),
							'rows' => 6,
							'help' => __( 'Markup for each lower-level item in the hierarchy; list format only.', 'media-library-assistant' ),
							'order' => 4,
						),
						'child-close' => array(
							'label' => __( 'Child', 'media-library-assistant' ) . '&nbsp;' . __( 'Close', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the end of each level in the hierarchy; list format only.', 'media-library-assistant' ),
							'order' => 5,
						),
						'open' => array(
							'label' => __( 'Open', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the beginning of the list. List of parameters, e.g., [+selector+], on Documentation tab.', 'media-library-assistant' ),
							'order' => 1,
						),
						'item' => array(
							'label' => __( 'Item', 'media-library-assistant' ),
							'rows' => 6,
							'help' => __( 'Markup for each item/cell in the list.', 'media-library-assistant' ),
							'order' => 2,
						),
						'close' => array(
							'label' => __( 'Close', 'media-library-assistant' ),
							'rows' => 3,
							'help' => __( 'Markup for the end of the list.', 'media-library-assistant' ),
							'order' => 6,
						),
					),
				),
			),
		);
//error_log( __LINE__ . ' mla_localize_template_definitions MLATemplate_Support::$mla_template_definitions = ' . var_export( MLATemplate_Support::$mla_template_definitions, true ), 0 );
	}
	
	/**
	 * Style and Markup templates
	 *
	 * Templates are indexed by 'style'/'markup' and then shortcode name. Each template type is defined by:
	 *
	 * @since 2.30
	 *
	 * @var	array
	 */
	private static $mla_custom_templates = NULL;

	/**
	 * Load style and markup templates to $mla_custom_templates
	 *
	 * @since 2.30
	 *
	 * @return	void
	 */
	public static function mla_load_custom_templates() {
		if ( empty( MLATemplate_Support::$mla_template_definitions ) ) {
			MLATemplate_Support::mla_localize_template_definitions();
		}

		MLATemplate_Support::$mla_custom_templates = NULL;
		$default_templates = MLACore::mla_load_template( 'mla-custom-templates.tpl' );

		/* 	
		 * Load the default templates
		 */
		if ( is_null( $default_templates ) ) {
			MLACore::mla_debug_add( '<strong>mla_debug mla_load_custom_templates()</strong> ' . __( 'error loading tpls/mla-custom-templates.tpl', 'media-library-assistant' ), MLACore::MLA_DEBUG_CATEGORY_ANY );
			return;
		} elseif ( !$default_templates ) {
			MLACore::mla_debug_add( '<strong>mla_debug mla_load_custom_templates()</strong> ' . __( 'tpls/mla-custom-templates.tpl not found', 'media-library-assistant' ), MLACore::MLA_DEBUG_CATEGORY_ANY );
			return;
		}

		/*
		 * Record explicit shortcode assignments
		 */
		$mla_shortcode_slugs = array();
		foreach ( $default_templates as $key => $value ) {
			$mla_shortcode_slug = NULL;
			
			$match_count = preg_match( '#\<!-- mla_shortcode_slug="(.+)" --\>[\r\n]*#', $value, $matches, PREG_OFFSET_CAPTURE );
			if ( $match_count == 0 ) {
				$match_count = preg_match( '#mla_shortcode_slug="(.+)"[ \r\n]*#', $value, $matches, PREG_OFFSET_CAPTURE );
			}

			if ( $match_count > 0 ) {
				$mla_shortcode_slug = $matches[ 1 ][ 0 ];
//error_log( __LINE__ . " default template {$key}, {$mla_shortcode_slug} matches = " . var_export( $matches, true ), 0 );
				$value = substr_replace( $value, '', $matches[ 0 ][ 1 ], strlen( $matches[ 0 ][ 0 ] ) );
				if ( empty( $value ) ) {
					unset( $default_templates[ $key ] );
//error_log( __LINE__ . " unset default template {$key}, {$mla_shortcode_slug}", 0 );
				} else {
					$default_templates[ $key ] = $value;
//error_log( __LINE__ . " replace default template {$key}, {$mla_shortcode_slug} value = " . MLAData::mla_hex_dump( $value ), 0 );
				}
			}

			if ( !empty( $mla_shortcode_slug ) ) {
				$tail = strrpos( $key, '-style' );
				if ( ! ( false === $tail ) ) {
					$mla_shortcode_slugs['style'][ substr( $key, 0, $tail ) ] = $mla_shortcode_slug;
				} else {
					$tail = strrpos( $key, '-arguments-markup' );
					if ( ! ( false === $tail ) ) {
						$mla_shortcode_slugs['markup'][ substr( $key, 0, $tail ) ] = $mla_shortcode_slug;
					}
				}
			}
		}
//error_log( __LINE__ . " mla_shortcode_slugs = " . var_export( $mla_shortcode_slugs, true ), 0 );
		
		/*
		 * Find the shortcode and template type for array indices
		 */
		foreach ( $default_templates as $key => $value ) {
			$tail = strrpos( $key, '-style' );
			if ( ! ( false === $tail ) ) {
				$name = substr( $key, 0, $tail );
				
				if ( isset( $mla_shortcode_slugs['style'][ $name ] ) ) {
					// Assign to the declared shortcode
					MLATemplate_Support::$mla_custom_templates['style'][ $mla_shortcode_slugs['style'][ $name ] ][ $name ] = $value;
					$name = NULL;
				} else {
					// Guess at the shortcode
					foreach( MLATemplate_Support::$mla_template_definitions['style'] as $slug => $definition ) {
						if ( isset( $definition['default_names'] ) && in_array( $name, $definition['default_names'] ) ) {
							MLATemplate_Support::$mla_custom_templates['style'][ $definition['slug'] ][ $name ] = $value;
							$name = NULL;
							break;
						}
					}
				}

				// Can't find the shortcode; assume it's mla_gallery				
				if ( $name ) {
					MLATemplate_Support::$mla_custom_templates['style']['gallery'][ $name ] = $value;
				}
				
				continue;
			}

			$tail = strrpos( $key, '-markup' );
			if ( ! ( false === $tail ) ) {
				$name = substr( $key, 0, $tail );
				
				// Look for explicit assignment
				foreach( $mla_shortcode_slugs['markup'] as $root_name => $mla_shortcode_slug ) {
					$root = strpos( $name, $root_name );
					if ( 0 === $root ) {
						$section_name = substr( $name, strlen( $root_name ) + 1 );
//error_log( __LINE__ . " assign default template {$key}, to [{$mla_shortcode_slug}][{$root_name}][{$section_name}]", 0 );
						// Assign to the declared shortcode
						MLATemplate_Support::$mla_custom_templates['markup'][ $mla_shortcode_slug ][ $root_name ][ $section_name ] = $value;
						$name = NULL;
						break;
					}
				}

				if ( $name ) {
					// Guess at the shortcode
					foreach( MLATemplate_Support::$mla_template_definitions['markup'] as $slug => $definition ) {
						if ( isset( $definition['default_names'] ) ) {
							foreach( $definition['default_names'] as $default_name ) {
								$root = strpos( $name, $default_name );
								if ( 0 === $root ) {
									foreach( $definition['sections'] as $section_name => $section_value ) {
										$tail = strrpos( $name, '-' . $section_name );
										if ( ! ( false === $tail ) ) {
											$name = substr( $name, 0, $tail );
											MLATemplate_Support::$mla_custom_templates['markup'][ $definition['slug'] ][ $name ][ $section_name ] = $value;
										}
									}
									
									$name = NULL;
									break;
								}
							}
						}
					}
				}

				// Can't find the shortcode; assume it's mla_gallery				
				if ( $name ) {
					foreach( MLATemplate_Support::$mla_template_definitions['markup']['gallery']['sections'] as $section_name => $section_value ) {
						$tail = strrpos( $name, '-' . $section_name );
						if ( ! ( false === $tail ) ) {
							$name = substr( $name, 0, $tail );
							MLATemplate_Support::$mla_custom_templates['markup']['gallery'][ $name ][ $section_name ] = $value;
						}
					}
				}
			}
		}

		/*
		 * Add user-defined Style and Markup templates
		 *
		 * MLA versions before 2.40 will not have separate 'attributes' and 'content' elements,
		 * but version 2.3x must "go both ways" to allow for reversions.
		 */
		$templates = MLACore::mla_get_option( 'style_templates' );
		if ( is_array(	$templates ) ) {
			foreach ( $templates as $name => $value ) {
				// Check for 2.40+ format
				if ( is_array( $value ) ) {
					$attributes = $value['attributes'];
					MLATemplate_Support::$mla_custom_templates['style'][ $attributes['mla_shortcode_slug'] ][ $name ] = $value['content'];
					continue;
				}
				
				// Check for explicit assignment
				$match_count = preg_match( '#\<!-- mla_shortcode_slug="(.+)" --\>[\r\n]*#', $value, $matches, PREG_OFFSET_CAPTURE );
				if ( $match_count > 0 ) {
//error_log( __LINE__ . " default template {$name} matches = " . var_export( $matches, true ), 0 );
					$value = substr_replace( $value, '', $matches[ 0 ][ 1 ], strlen( $matches[ 0 ][ 0 ] ) );
//error_log( __LINE__ . " replace default template {$name} value = " . MLAData::mla_hex_dump( $value ), 0 );
					MLATemplate_Support::$mla_custom_templates['style'][ $matches[ 1 ][ 0 ] ][ $name ] = $value;
					continue;
				}

				// Guess from content
				foreach( MLATemplate_Support::$mla_template_definitions['style'] as $slug => $definition ) {
					if ( false !== strpos( $value, '.' . $definition['slug'] ) ) {
						MLATemplate_Support::$mla_custom_templates['style'][ $definition['slug'] ][ $name ] = $value;
						$name = NULL;
						break;
					}
				}
				
				if ( $name ) {
					MLATemplate_Support::$mla_custom_templates['style']['gallery'][ $name ] = $value;
				}
			} // foreach $templates
		} // is_array

		$templates = MLACore::mla_get_option( 'markup_templates' );
		if ( is_array(	$templates ) ) {
			foreach ( $templates as $name => $value ) {
				// Check for 2.40+ format
				if ( isset( $value['attributes'] ) ) {
					$attributes = $value['attributes'];
					unset( $value['attributes'] );
					MLATemplate_Support::$mla_custom_templates['markup'][ $attributes['mla_shortcode_slug'] ][ $name ] = $value['content'];
					continue;
				}
				
				// Check for explicit assignment
				if ( isset( $value['arguments'] ) ) {
					$match_count = preg_match( '#mla_shortcode_slug="(.+)"[ \r\n]*#', $value['arguments'], $matches, PREG_OFFSET_CAPTURE );
				} else {
					$match_count = 0;
				}
	
				if ( $match_count > 0 ) {
//error_log( __LINE__ . " user template {$key} matches = " . var_export( $matches, true ), 0 );
					$value['arguments'] = substr_replace( $value['arguments'], '', $matches[ 0 ][ 1 ], strlen( $matches[ 0 ][ 0 ] ) );
					if ( empty( $value['arguments'] ) ) {
//error_log( __LINE__ . " unset user template {$key}", 0 );
						unset( $value['arguments'] );
					} else {
//error_log( __LINE__ . " replace user {$key} value = " . MLAData::mla_hex_dump( $value['arguments'] ), 0 );
					}
					
					MLATemplate_Support::$mla_custom_templates['markup'][ $matches[ 1 ][ 0 ] ][ $name ] = $value;
					continue;
				}

				// Guess from content
				$full_text = ''; // for guessing shortcode name
				foreach( $value as $section_name => $section_value ) {
					$full_text .= $section_value;
				}
				
				foreach( MLATemplate_Support::$mla_template_definitions['markup'] as $slug => $definition ) {
					if ( preg_match( '#class=[\'\"]*.*' . $definition['slug'] . '#', $full_text, $matches ) ) {
						MLATemplate_Support::$mla_custom_templates['markup'][ $definition['slug'] ][ $name ] = $value;
						$name = NULL;
						break;
					}
				}
				
				if ( $name ) {
					MLATemplate_Support::$mla_custom_templates['markup']['gallery'][ $name ] = $value;
				}
			} // foreach $templates
		} // is_array
//error_log( __LINE__ . ' mla_load_custom_templates MLATemplate_Support::$mla_custom_templates = ' . var_export( MLATemplate_Support::$mla_custom_templates, true ), 0 );
	}

	/**
	 * Fetch style or markup template from $mla_templates
	 *
	 * @since 2.30
	 *
	 * @param	string	Template name
	 * @param	string	Shortcode slug; 'gallery' (default), 'tag-cloud' or 'term-list'
	 * @param	string	Template type; 'style' (default) or 'markup'
	 * @param	string	Template section (markup templates); default '[not supplied]'
	 *
	 * @return	string|boolean|null	requested template, false if not found or null if no templates
	 */
	public static function mla_fetch_custom_template( $key, $shortcode = 'gallery', $type = 'style', $section = '[not supplied]' ) {
//MLACore::mla_debug_add( "<strong>mla_fetch_custom_template( {$key}, {$shortcode}, {$type}, {$section} )</strong> " . __( 'calling parameters', 'media-library-assistant' ), MLACore::MLA_DEBUG_CATEGORY_ANY );
		if ( ! is_array( MLATemplate_Support::$mla_custom_templates ) ) {
			MLACore::mla_debug_add( '<strong>mla_fetch_custom_template()</strong> ' . __( 'no templates exist', 'media-library-assistant' ), MLACore::MLA_DEBUG_CATEGORY_ANY );
			return NULL;
		}

		if ( array_key_exists( $type, MLATemplate_Support::$mla_custom_templates ) ) {
			if ( array_key_exists( $shortcode, MLATemplate_Support::$mla_custom_templates[ $type ] ) ) {
				if ( array_key_exists( $key, MLATemplate_Support::$mla_custom_templates[ $type ][ $shortcode ] ) ) {
					if ( 'style' == $type ) {
						return MLATemplate_Support::$mla_custom_templates['style'][ $shortcode ][ $key ];
					}
					
					if ( array_key_exists( $section, MLATemplate_Support::$mla_custom_templates['markup'][ $shortcode ][ $key ] ) ) {
						return MLATemplate_Support::$mla_custom_templates['markup'][ $shortcode ][ $key ][ $section ];
					}
					
					/*
					 * No error - not every section is required
					 */
					return false;
				}
			}
		}

		MLACore::mla_debug_add( "<strong>mla_fetch_custom_template( {$key}, {$shortcode}, {$type}, {$section} )</strong> " . __( 'not found', 'media-library-assistant' ), MLACore::MLA_DEBUG_CATEGORY_ANY );
		return false;
	}

	/**
	 * Get ALL style templates from $mla_custom_templates, including 'default'
	 *
	 * @since 2.30
	 *
 	 * @param	string	Shortcode to which the template(s) apply; default ''
	 *
	 * @return	array|null	name => value for all style templates or null if no templates
	 */
	public static function mla_get_style_templates( $shortcode = '' ) {
		if ( ! is_array( MLATemplate_Support::$mla_custom_templates ) ) {
			MLACore::mla_debug_add( '<strong>mla_debug mla_get_style_templates()</strong> ' . __( 'no templates exist', 'media-library-assistant' ) );
			return NULL;
		}

		if ( !empty( $shortcode ) ) {
			if ( array_key_exists( $shortcode, MLATemplate_Support::$mla_custom_templates['style'] ) ) {
				return MLATemplate_Support::$mla_custom_templates['style'][ $shortcode ];
			}
			
			return NULL;
		}
		
		$templates = array();
		foreach ( MLATemplate_Support::$mla_custom_templates['style'] as $shortcode => $value ) {
			$templates = array_merge( $templates, $value );
		} // foreach

		return $templates;
	}

	/**
	 * Put user-defined style templates to $mla_custom_templates and database
	 *
	 * @since 2.30
	 *
	 * @param	array	name => value for all user-defined style templates
	 * @return	boolean	true if success, false if failure
	 */
	public static function mla_put_style_templates( $templates ) {
		if ( MLACore::mla_update_option( 'style_templates', $templates ) ) {
			MLATemplate_Support::mla_load_custom_templates();
			return true;
		}

		return false;
	}

	/**
	 * Get ALL markup templates from $mla_custom_templates, including 'default'
	 *
	 * @since 2.30
	 *
 	 * @param	string	Shortcode to which the template(s) apply; default 'gallery'
	 *
	 * @return	array|null	name => value for all markup templates or null if no templates
	 */
	public static function mla_get_markup_templates( $shortcode = '' ) {
		if ( ! is_array( MLATemplate_Support::$mla_custom_templates ) ) {
			MLACore::mla_debug_add( '<strong>mla_debug mla_get_markup_templates()</strong> ' . __( 'no templates exist', 'media-library-assistant' ) );
			return NULL;
		}

		if ( !empty( $shortcode ) ) {
			if ( array_key_exists( $shortcode, MLATemplate_Support::$mla_custom_templates['markup'] ) ) {
				return MLATemplate_Support::$mla_custom_templates['markup'][ $shortcode ];
			}
			
			return NULL;
		}
		
		$templates = array();
		foreach ( MLATemplate_Support::$mla_custom_templates['markup'] as $shortcode => $value ) {
			$templates = array_merge( $templates, $value );
		} // foreach

		return $templates;
	}

	/**
	 * Put user-defined markup templates to $mla_custom_templates and database
	 *
	 * @since 2.30
	 *
	 * @param	array	name => value for all user-defined markup templates
	 * @return	boolean	true if success, false if failure
	 */
	public static function mla_put_markup_templates( $templates ) {
		if ( MLACore::mla_update_option( 'markup_templates', $templates ) ) {
			MLATemplate_Support::mla_load_custom_templates();
			return true;
		}

		return false;
	}
} // Class MLATemplate_Support

MLATemplate_Support::mla_load_custom_templates();
?>