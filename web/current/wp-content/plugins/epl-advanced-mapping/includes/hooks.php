<?php
/**
 * Add License Key Option
 * @since 2.0
**/
function epl_am_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'advanced_mapping',
				'label'	=>	'Advanced Mapping license key',
				'type'	=>	'text'
			)
		)
	);
	return $fields;
}
add_filter('epl_license_options_filter', 'epl_am_license_options_filter', 10, 3);

/**
 * JS / CSS scripts
 * @since 1.0
 */
function epl_am_enqueue_scripts() {
	global $epl_settings;
	//Gmap Scripts
	wp_enqueue_style( 'epl-am-map-icon-style', 		EPL_AM_PLUGIN_URL . 'css/map-icons.min.css', 	array(), 			EPL_AM_VERSION);
	wp_enqueue_style( 'epl-am-style', 			EPL_AM_PLUGIN_URL . 'css/style.css', 		array(), 			EPL_AM_VERSION);
	wp_dequeue_script('google-map-v-3');

	$googleapiurl = '//maps.googleapis.com/maps/api/js?v=3.exp&libraries=places,geometry';

	if( epl_get_option('epl_google_api_key') != '' ) {
		$googleapiurl = $googleapiurl.'&key='.epl_get_option('epl_google_api_key');
	}

	if( !isset($epl_settings['epl_am_google_api']) || $epl_settings['epl_am_google_api'] == 1 )
		wp_enqueue_script( 'epl-am-map-api', 		$googleapiurl, 					array('jquery'),		EPL_AM_VERSION );

	wp_enqueue_script( 'epl-am-map-icon', 			EPL_AM_PLUGIN_URL . 'js/map-icons.js', 		array('epl-am-map-api'),	EPL_AM_VERSION);
	wp_enqueue_script( 'epl-am-gmap', 			EPL_AM_PLUGIN_URL . 'js/gmap3.min.js', 		array('epl-am-map-api'),	EPL_AM_VERSION);
	wp_enqueue_script( 'epl-am-markerclusterer-script', 	EPL_AM_PLUGIN_URL . 'js/markerclusterer.js', 	array('epl-am-map-api'),	EPL_AM_VERSION);
	wp_enqueue_script( 'epl-am-scripts', 			EPL_AM_PLUGIN_URL . 'js/scripts.js',  		array('epl-am-map-api'),	EPL_AM_VERSION);

	//Bpopup Scripts
	wp_enqueue_style( 'bpopup-style', 			EPL_AM_PLUGIN_URL . 'css/bpopup.css', 		array(), 			EPL_AM_VERSION );
	wp_enqueue_script( 'bpopup-script', 			EPL_AM_PLUGIN_URL . 'js/jquery.bpopup.min.js',	array('jquery'), 		EPL_AM_VERSION);
	wp_enqueue_script( 'jquery-easing-script', 		EPL_AM_PLUGIN_URL . 'js/jquery.easing.1.3.js',  array('bpopup-script'),		EPL_AM_VERSION );
}
add_action( 'wp_enqueue_scripts', 'epl_am_enqueue_scripts', 10, 3 );

/**
 * Map Shortcode
 * @since 1.0
 */
function epl_advanced_map( $atts ) {
	global $property,$post;

	if(is_null($post))
		return;

	extract( shortcode_atts( array(
		'post_type' 		=>	epl_all_post_types(), //Post Type
		'limit'			=>	'30', // Number of maximum posts to show
		'coords'		=>	'', //First property in center by default
		'display'		=>	'card', //card, slider, simple or popup
		'zoom'			=>	'17', //for set map zoom level
		'height'		=>	'', //for set map height level, pass integer value
		'cluster'		=>	false, //Icon grouping on Map
		'property_status'	=>	'',
		'home_open'		=>	false, // False and true
		'location'		=>	'',
		'search_result'		=>	false
	), $atts ) );

	if( !is_array($post_type) ) {
		if(!empty($post_type)) {
			$post_type = explode(",", $post_type);
		}
	}

	$args = array(
		'post_type'		=>	$post_type,
		'posts_per_page'	=>	$limit,
		'paged'			=>	'1',
		'epl_nopaging'		=> 	'true',
		'meta_query'		=>	array(
			array(
				'key'		=>	'property_address_coordinates',
				'value'		=>	'',
				'compare'	=>	'!='
			)
		)
	);


	if(!empty($property_status)) {

		if( !is_array($property_status) ) {
			if(!empty($property_status)) {
				$property_status = explode(",", $property_status);
				$property_status = array_filter($property_status);
			}
		}

		if(($key = array_search('withdrawn', $property_status)) !== false ) {
			unset($property_status[$key]);
		}

		if(($key = array_search('offmarket', $property_status)) !== false ) {
			unset($property_status[$key]);
		}


		$args['meta_query'][] = array(
			'key'		=>	'property_status',
			'value'		=>	$property_status,
			'compare'	=>	'IN'
		);

	}

	if(!empty($home_open)) {
		if( true == $home_open ) {
			$args['meta_query'][] = array(
				'key'		=>	'property_inspection_times',
				'value'		=>	'',
				'compare'	=>	'!='
			);
		}
	}

	// if user has specified location , use that
	if(!empty($location) ) {
		if( !is_array( $location ) ) {
			$location = explode(",", $location);
			$location = array_map('trim', $location);

			$args['tax_query'][] = array(
				'taxonomy' => 'location',
				'field' => 'slug',
				'terms' => $location
			);
		}
	}
	// if user has not specified location fetch property's location
	if( is_epl_post_single() ) {
		if(empty($location) && !is_archive() && !in_array($property->post_type,apply_filters('epl_am_skip_location_filter',array('epl_office') ) ) ) {

			$location = wp_get_object_terms( $property->post->ID,'location',array('fields'	=>	'slugs')) ;
			$args['tax_query'][] = array(
					'taxonomy' => 'location',
					'field' => 'slug',
					'terms' => $location
				);
		}
	}
	if( $search_result == true ){

		global $wp_query;
		$results = $wp_query;
	} else {
		$results = new Wp_Query($args);
	}

		global $property, $epl_settings;

		$cluster = ( $cluster == 'true' || $cluster == 'on' ) ? 'true' : 'false';

		$infobox_class = isset( $epl_settings['epl_am_infobox_style'] ) ? $epl_settings['epl_am_infobox_style'] : 'rounded';

		$return = '';
		$return .= "<div id='epl-advanced-map' class='epl-am-infobox-$infobox_class'>";
		$return .='
				<input type="hidden" name="slider[zoom]" value="'.$zoom.'" />
				<input type="hidden" name="slider[height]" value="'.$height.'" />
				<input type="hidden" name="slider[cluster]" value="'.$cluster.'" />
				<input type="hidden" name="slider[display]" value="'.$display.'" />

				<div  class="slider-map"></div>
				<div  class="slider-map-zoom-in"></div>
				<div  class="slider-map-zoom-out"></div>';

				if($display == 'slider') {
					$return .= '
						<div class="slider-map-featured">
							<div class="slider-map-featured-left" ></div>
							<div  class="slider-map-featured-right"></div>

							<ul>';
								if($results->have_posts()) :
									while( $results->have_posts()) : $results->the_post();

										if(has_post_thumbnail()) {
											$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
											$epl_am_prop_image = $image[0];
										} else {
											$epl_am_prop_image = EPL_AM_PLUGIN_URL_IMAGES . 'no-image.jpg';
										}

										$return .= '
										<li id="marker_featured_'.get_the_ID().'" class="marker-featured-slide ">
											<img src="'.$epl_am_prop_image.'" alt="'.get_the_title().'" title="'.get_the_title().'" />
											<span class="slider-title">'.get_the_title().'</span>
											<p>'.get_the_excerpt().'</p>';

											if ( !in_array(get_post_type(), apply_filters('epl_am_no_price',array('location_profile') ) ) ) {
												$return .= '
														<div class="slider-price">
															<span class="page-price">';
																$return .= '<span class="price_class">'.epl_get_property_price().'</span>';
																//$return .= '<span class="price_sticker">'.epl_get_price_sticker().'</span>';
																$return .= '
															</span>
														</div>
													';
											}
											$return .= '
											<div class="property-info">';
												//$return .= '<div class="epl-adv-popup-meta">'.epl_get_property_icons().'</div>';
												$return .= '<a href="'.get_permalink().'" title="'.get_the_title().'" class="view">View Property</a>
											</div>
										</li>
									';
									endwhile;
								endif;
								wp_reset_postdata();

								$return .= '
							</ul>
						</div>
						<div class="clear"></div>
					';
				} else if($display == 'popup') {
					$return .= '
						<div class="bpopup" id="bpopup-epl-am">
							<span class="b-close"><span>X</span></span>
							<div class="bpopup-inner" id="bpopup-inner"><!-- Ajax Loaded Data --></div>
						</div>
					';
				} else {

				}

				$return .= '
			</div>

			<script type="text/javascript">
				jQuery(document).ready(function() { ';
					if($results->have_posts()) :
						$post_counter = 1;
						while( $results->have_posts()) : $results->the_post();
							$property_address_coordinates = $property->get_property_meta('property_address_coordinates');
							$property_address_coordinates = explode(',', $property_address_coordinates);
							$epl_am_lat 	= trim($property_address_coordinates[0]);
							$epl_am_long 	= trim($property_address_coordinates[1]);
							if($post_counter == 1) {
								$center_lat = $epl_am_lat;
								$center_long = $epl_am_long;
							}
							if(has_post_thumbnail()) {
								$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
								$epl_am_prop_image = $image[0];
							} else {
								$epl_am_prop_image = EPL_AM_PLUGIN_URL_IMAGES . 'no-image.jpg';
							}

							$image_pin = epl_am_get_property_image($property);
							$content = epl_get_property_icons();
							$price_content = '';
							$price_content .= '
								<div class="slider-price">
									<span class="page-price">';
										$price_content .= '<span class="price_class">'.epl_get_property_price().'</span>';
										//$price_content .= '<span class="price_sticker">'.epl_get_price_sticker().'</span>';
										$price_content .= '
									</span>
								</div>
							';

							$content = '<div class="property-info"><span class="property_address_suburb">'.epl_property_get_the_full_address().'</span>'.$price_content.'<div class="epl-adv-popup-meta">'.$content.'</div></div>';

							$content = preg_replace('~[\r\n]+~', '', $content);
							$return .= '
								myGmap.addFeaturedMarker(\''.get_the_ID().'\', \''.addslashes(epl_property_get_the_full_address()).'\', \''.$epl_am_lat.'\', \''.$epl_am_long.'\', \''.$image_pin.'\', \''.get_permalink().'\', \''.$epl_am_prop_image.'\', \''.addslashes(get_the_title()).'\', \''.addslashes($content).'\');
							';
					$post_counter++ ;
					endwhile;
				endif;
				wp_reset_postdata();

					if($coords != '') {
						$center_coordinates = explode(',', $coords);
						$center_lat 		= trim($center_coordinates[0]);
						$center_long 		= trim($center_coordinates[1]);
					} else {
						if( !is_null($property) ) {
							$center_coordinates = $property->get_property_meta('property_address_coordinates');
							$center_coordinates = explode(',',$center_coordinates);
							$center_lat 		= trim($center_coordinates[0]);
							$center_long 		= trim($center_coordinates[1]);
						}
					}
					$return .= '
					set_markers( "'.EPL_AM_PLUGIN_URL_IMAGES.'" );
					var latlng = new google.maps.LatLng('.trim($center_lat).', '.trim($center_long).');
					myGmap.gmap3("get").setCenter(latlng);
					comparablesmap 	= myGmap.gmap3(\'get\');
					if (eplAmMapStyles.length > 0) {
						comparablesmap.set(\'styles\',eplAmMapStyles);
					}

				});
			</script>
		';

		return $return;

}
// Check for Easy Property Listings core is active
if ( class_exists( 'Easy_Property_Listings' ) ) {
	add_shortcode( 'advanced_map' , 'epl_advanced_map' );
}

/**
 * Popup content Ajax
 * @since 1.0
**/
 function epl_am_load_popup() {
	extract($_REQUEST);
	if($id > 0) {
		$post = get_post($id);

		$property_meta = epl_get_property_meta($result->id);

		$post->property_status = '';
		if(isset($property_meta['property_status']) && !empty($property_meta['property_status'])) {
			$post->property_status = $property_meta['property_status'][0];
		}

		if(has_post_thumbnail($post->ID)) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
			$image = $image[0];
		} else {
			$image = EPL_AM_PLUGIN_URL_IMAGES . 'no-image.jpg';
		}

		$return .= '
			<div class="epl-am-post-content">
				<h3>'.stripslashes($post->post_title).'</h3>
				<div class="fLeft">
					<img src="'.$image.'" alt="'.stripslashes($post->post_title).'" title="'.stripslashes($post->post_title).'" />
				</div>

				<div class="fRight">
					<p>'.substr(strip_tags(stripslashes($post->post_content)), 0, 200).'...</p>
					<a href="'.get_permalink($post->ID).'" title="'.stripslashes($post->post_title).'" class="button_link">View Property</a>
				</div>

				<div class="fBottom epl-property-icons">';

					if(isset($property_meta['property_bedrooms']) && !empty($property_meta['property_bedrooms'])) {
						$property_bedrooms = $property_meta['property_bedrooms'][0];
						if($property_bedrooms > 0) {
							$return .= epl_am_get_property_options($property_bedrooms, $post->post_type, $post->property_status, 'icon-beds');
						}
					}

					if(isset($property_meta['property_bathrooms']) && !empty($property_meta['property_bathrooms'])) {
						$property_bathrooms = $property_meta['property_bathrooms'][0];
						if($property_bathrooms > 0) {
							$return .= epl_am_get_property_options($property_bathrooms, $post->post_type, $post->property_status, 'icon-baths');
						}
					}

					if(isset($property_meta['property_garage']) && !empty($property_meta['property_garage'])) {
						$property_garage = $property_meta['property_garage'][0];
						if($property_garage > 0) {
							$return .= epl_am_get_property_options($property_garage, $post->post_type, $post->property_status, 'icon-garage');
						}
					}

					if(isset($property_meta['property_pool']) && !empty($property_meta['property_pool'])) {
						$property_pool = $property_meta['property_pool'][0];
						if($property_pool == 1 || $property_pool == 'yes') {
							$return .= epl_am_get_property_options($property_pool, $post->post_type, $post->property_status, 'icon-garage');
						}
					}

					if(isset($property_meta['property_air_conditioning']) && !empty($property_meta['property_air_conditioning'])) {
						$property_air_conditioning = $property_meta['property_air_conditioning'][0];
						if($property_air_conditioning == 1 || $property_air_conditioning == 'yes') {
							$return .= epl_am_get_property_options("", $post->post_type, $post->property_status, 'icon-air');
						}
					}

					$return .= '
					<div class="clear"></div>
				</div>

				<div class="clear"></div>
			</div>
		';

		wp_reset_postdata();
		echo $return;
		exit;
	}
}
add_action( 'wp_ajax_epl_am_load_popup', 'epl_am_load_popup' );
add_action( 'wp_ajax_nopriv_epl_am_load_popup', 'epl_am_load_popup' );

function epl_am_extensions_options_filter($epl_fields = null) {
	$fields = array();
	$epl_am_fields = array(
		'label'		=>	__('Advanced Mapping','epl-am'),
	);

	$fields[] = array(
		'label'		=>	__('Settings', 'epl-am'),
		'fields'	=>	apply_filters('epl_am_general_setting_tab',array(

			array(
				'name'		=>	'epl_am_settings_description_intro',
				'content'	=>	'<h3>' . __('The settings below adjust the configuration options for the maps display.','epl-am') . '</h3>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_am_custom_marker',
				'label'		=>	__('Use custom markers from theme folder', 'epl-am'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'		=>	__('Enable','epl-am'),
					'0'		=>	__('Disable','epl-am')
				),
				'default'	=>	'1',
				'help'		=>	__( 'Create a folder inside your theme called /easypropertylistings/map/ and copy the markers from the plugin. Edit the markers and keep the same the name format. NOTE: If you are not using custom markers in your theme make sure this is set to Disable.' , 'epl-am')
			),

			array(
				'name'		=>	'epl_am_infobox_position',
				'label'		=>	__('Info-box position relative to marker', 'epl-am'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'top'		=>	__('Top','epl-am'),
					'bottom'	=>	__('Bottom','epl-am'),
					'left'		=>	__('Left','epl-am'),
					'right'		=>	__('Right','epl-am')

				),
				'default'	=>	'top',
				'help'		=>	__( 'Position of the info-box relative to the marker on all maps.' , 'epl-am')
			),

			array(
				'name'		=>	'epl_am_infobox_style',
				'label'		=>	__('Info box style', 'epl-am'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'rounded'	=>	__('Rounded','epl-am'),
					'square'	=>	__('Square','epl-am')
				),
				'default'	=>	'rounded',
			),

			array(
				'name'		=>	'epl_am_disable_mousescroll',
				'label'		=>	__('Scroll mousewheel to zoom map?', 'epl-am'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'		=>	__('Enable','epl-am'),
					'0'		=>	__('Disable','epl-am')
				),
				'default'	=>	'1',
			),

			array(
				'name'		=>	'epl_am_google_api',
				'label'		=>	__('Google API', 'epl-am'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'		=>	__('Enable','epl-am'),
					'0'		=>	__('Disable','epl-am')
				),
				'default'	=>	'1',
				'help'		=>	__('Disable google api if some plugin/theme is already loading it','epl-am')
			),
			array(
				'name'		=>	'epl_am_map_styles',
				'label'		=>	__('Style Map', 'epl-am'),
				'type'		=>	'textarea',
				'help'		=>	'<p class="epl-clearfix">' . __('Get custom map styles from <a target="_blank" href="https://snazzymaps.com">Snazzy Maps</a> and paste in the code. Or refer to <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/styling#creating_a_styledmaptype">Google Map Style</a> for more info.','epl-am') . '</p>'
			)
		) )
	);

	$fields[] = array(
		'label'		=>	__('Single Listings Map', 'epl-am'),
		'fields'	=>	apply_filters('epl_am_list_core_map_tab', array(

			array(
				'name'		=>	'epl_am_settings_description_single_intro',
				'content'	=>	'<h3>' . __('The settings below adjust the map configuration options when viewing a single listing.','epl-am') . '</h3>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_am_map_enable',
				'label'		=>	__('Use Advanced Map on single listings?', 'epl-am'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'		=>	__('Enable','epl-am'),
					'0'		=>	__('Disable','epl-am')
				),
				'default'	=>	'1',
				'help'		=>	__( 'If you are using the map elsewhere in your template you can turn off the default map and add the map manually to your template.' , 'epl-am')

			),

			array(
				'name'		=>	'epl_am_single_map_height',
				'label'		=>	__('Map height in pixels on single listing page', 'epl-am'),
				'type'		=>	'number',
				'default'	=>	400,
				'help'		=>	__( 'Set the map height in pixels.' , 'epl-am')

			),

			array(
				'name'		=>	'epl_am_single_tab_position',
				'label'		=>	__('Map Tabs Postion?', 'epl-am'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'		=>	__('Above','epl-am'),
					'0'		=>	__('Below','epl-am')
				),
				'default'	=>	'1',
				'help'		=>	__( 'Position of the tabs map control menu.' , 'epl-am')
			),

			array(
				'name'		=>	'epl_am_settings_description_single_hr',
				'content'	=>	'<hr>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_am_default_map_type',
				'label'		=>	'<strong>' . __('Default Map Style', 'epl-am') . '</strong>',
				'type'		=>	'radio',
				'opts'		=>	array(
					'SATELLITE'	=>	__('Satellite','epl-am'),
					'ROADMAP'	=>	__('Road Map','epl-am')
				),
				'default'	=>	'SATELLITE',
			),

			array(
				'name'		=>	'epl_am_label_sat',
				'label'		=>	__('Tab Label', 'epl-am'),
				'type'		=>	'text',
				'default'	=>	__('Satellite', 'epl-am'),
			),

			array(
				'name'		=>	'epl_am_single_map_zoom_sat',
				'label'		=>	__('Zoom', 'epl-am'),
				'type'		=>	'select',
				'opts'		=>	range( 0, 20 , 1),
				'default'	=>	20,
			),

			array(
				'name'		=>	'epl_am_settings_description_single_hr',
				'content'	=>	'<hr>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_am_enable_street_view',
				'label'		=>	'<strong>' . __('Street View Tab', 'epl-am') . '</strong>',
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-am'),
					'0'	=>	__('Disable','epl-am')
				),
				'default'	=>	'0',
			),

			array(
				'name'		=>	'epl_am_label_street',
				'label'		=>	__('Tab Label', 'epl-am'),
				'type'		=>	'text',
				'default'	=>	__('Street View', 'epl-am'),
			),

			array(
				'name'		=>	'epl_am_settings_description_single_hr',
				'content'	=>	'<hr>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_am_enable_transit_view',
				'label'		=>	'<strong>' . __('Transit', 'epl-am') . '</strong>',
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-am'),
					'0'	=>	__('Disable','epl-am')
				),
				'default'	=>	'1',
			),

			array(
				'name'		=>	'epl_am_label_transit',
				'label'		=>	__('Tab Label', 'epl-am'),
				'type'		=>	'text',
				'default'	=>	__('Transit', 'epl-am'),
			),

			array(
				'name'		=>	'epl_am_single_map_zoom_transit',
				'label'		=>	__('Zoom', 'epl-am'),
				'type'		=>	'select',
				'opts'		=>	range( 0, 20 , 1),
				'default'	=>	16,
			),

			array(
				'name'		=>	'epl_am_settings_description_single_hr',
				'content'	=>	'<hr>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_am_enable_bike_view',
				'label'		=>	'<strong>' . __('Bike Tab', 'epl-am') . '</strong>',
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-am'),
					'0'	=>	__('Disable','epl-am')
				),
				'default'	=>	'1',
			),

			array(
				'name'		=>	'epl_am_label_bike',
				'label'		=>	__('Tab Label', 'epl-am'),
				'type'		=>	'text',
				'default'	=>	__('Bike', 'epl-am'),
			),

			array(
				'name'		=>	'epl_am_single_map_zoom_bike',
				'label'		=>	__('Zoom', 'epl-am'),
				'type'		=>	'select',
				'opts'		=>	range( 0, 20 , 1),
				'default'	=>	18,
			),

			array(
				'name'		=>	'epl_am_settings_description_single_hr',
				'content'	=>	'<hr>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_am_enable_comparables_view',
				'label'		=>	'<strong>' . __('Show Comparables Tab?', 'epl-am') . '</strong>',
				'type'		=>	'radio',
				'opts'		=>	array(
					'1'	=>	__('Enable','epl-am'),
					'0'	=>	__('Disable','epl-am')
				),
				'default'	=>	'1',
			),

			array(
				'name'		=>	'epl_am_label_comparables',
				'label'		=>	__('Tab Label', 'epl-am'),
				'type'		=>	'text',
				'default'	=>	__('Comparables', 'epl-am'),
			),

			array(
				'name'		=>	'epl_am_single_map_zoom_comparables',
				'label'		=>	__('Zoom', 'epl-am'),
				'type'		=>	'select',
				'opts'		=>	range( 0, 20 , 1),
				'default'	=>	14,
			)

		))
	);
	$epl_am_fields['fields'] 	= apply_filters('epl_am_setting_group',$fields);
	/** use epl_am_setting_group filter to add additional tabs to epl am from other extensions **/
	$epl_fields['advanced_mapping'] = $epl_am_fields;
	return $epl_fields;
}
add_filter('epl_extensions_options_filter_new', 'epl_am_extensions_options_filter', 10, 1);

/**
 * add "add map" button to the posts edit screen which lets user to generate shortcode on fly
 */
function epl_am_add_form_button() {

   echo '<style>
   .epl_am_adv_map_icon:before {
   		font: 400 18px/1 dashicons;
   		content:""
   }

   </style>
   <a href="#TB_inline?width=480&height=700&inlineId=epl_am_select_map_options"
  		class="thickbox button epl_am_media_link"
  		id="epl_am_add_map_shortcode"
  		title="' . __("Add Map", 'epl-am') . '">
	    <span class="epl_am_adv_map_icon"></span> ' . __("Add Map", "epl-am") . '</a>';
}
add_action('media_buttons', 'epl_am_add_form_button', 20);

/**
 * Action target that displays the popup to insert a map to a post
 */
function epl_am_add_mce_popup() {
    ?>
    <style>
    	#TB_window {
    		overflow:auto;
    	}
    </style>
    <script>
        function eplAmInsertMap(){

        	var shortcode = "[advanced_map ";
			var listingtypes = jQuery('#epl_am_listing_types').val();
			listingtypes = listingtypes.join(',');

			if(listingtypes != '') {
				shortcode += "post_type='"+listingtypes+"' ";
			}
			var display = jQuery('#epl_am_display_type').val();

			if(display != '') {
				shortcode += "display='"+display+"' ";
			}

			var location = jQuery('#epl_am_listing_location').val();

			if(location != '') {
				shortcode += "location='"+location+"' ";
			}

			var limit = jQuery('#epl_am_listing_limit').val();

			if(limit != '') {
				shortcode += "limit='"+parseInt(limit)+"' ";
			}

			var zoom = jQuery('#epl_am_map_zoom').val();

			if(zoom != '') {
				shortcode += "zoom='"+parseInt(zoom)+"' ";
			}

			var height = jQuery('#epl_am_map_height').val();

			if(height != '') {
				shortcode += "height='"+parseInt(height)+"' ";
			}

			var cluster = jQuery('#epl_am_map_clustering').is(':checked');
			if(cluster == true)
				shortcode += "cluster='"+cluster+"' ";

			var home_open = jQuery('#epl_am_map_inspection').is(':checked');
			if(home_open == true)
				shortcode += "home_open='"+home_open+"' ";

			shortcode += "]";
            window.send_to_editor(shortcode);
        }
    </script>

    <div id="epl_am_select_map_options" style="display:none;">
        <div class="wrap">

                <div style="padding:15px 15px 0 15px;">
                    <h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php _e("Insert A Map", "epl-am"); ?></h3>
 				 <span>
	                <?php _e("Select listing types which will be shown in map", "epl-am"); ?>
	            </span>

                </div>


                <div style="padding:15px 15px 0 15px;">

                    <select id="epl_am_listing_types" multiple="multiple">
                        <option value="">  <?php _e("Select Listing Types", "epl-am"); ?>  </option>
                        <?php

		                    $listing_types = array('property', 'rental', 'land', 'rural', 'commercial', 'business', 'commercial_land' , 'location_profile');
		                    foreach($listing_types as $listing_type) {
		                        ?>
		                        <option value="<?php echo esc_html($listing_type) ?>"><?php echo esc_html($listing_type) ?></option>
		                        <?php
		                    }
                        ?>
                    </select> <br/>
		           	<span>
		            	<?php _e("Select map display type", "epl-am"); ?>
		        	</span>

               </div>


                <div style="padding:15px 15px 0 15px;">
	                <select id="epl_am_display_type">
	                    <option value="">  <?php _e("Display type", "epl-am"); ?>  </option>
	                    <?php
	                        $display_types = array('card', 'slider', 'simple', 'popup');
	                        foreach($display_types as $display_type) {
	                            ?>
	                            <option value="<?php echo esc_html($display_type) ?>"><?php echo esc_html($display_type) ?></option>
	                            <?php
	                        }
	                    ?>
	                </select> <br/>
					<span>
						<?php _e("Show listings of only a specific location", "epl-am"); ?>
					</span>

	            </div>


	             <div style="padding:15px 15px 0 15px;">
	                <select id="epl_am_listing_location">
	                    <option value="">  <?php _e("Location", "epl-am"); ?>  </option>
	                    <?php
	                        $locations = get_terms('location');
	                        foreach($locations as $location) {
	                            ?>
	                            <option value="<?php echo esc_html($location->slug) ?>"><?php echo esc_html($location->name) ?></option>
	                            <?php
	                        }
	                    ?>
	                </select> <br/>
	            </div>

                <div style="padding:15px 15px 0 15px;">

                	<label for="epl_am_listing_limit" style="width:50%;display: inline-block;">
                		<?php _e("Number of Listings to show (default is 30)", "epl-am"); ?>
            		</label> &nbsp;&nbsp;&nbsp;

                    <input type="number" id="epl_am_listing_limit" />
                    </br></br>
                	<label for="epl_am_map_zoom" style="width:50%;display: inline-block;">
                		<?php _e("Map Zoom level (default is 17)", "epl-am"); ?>
            		</label> &nbsp;&nbsp;&nbsp;

                    <input type="number" id="epl_am_map_zoom" />
					</br></br>
                	<label for="epl_am_map_height" style="width:50%;display: inline-block;">
                		<?php _e("Map Height ( optional )", "epl-am"); ?>
            		</label> &nbsp;&nbsp;&nbsp;

                    <input type="number" id="epl_am_map_height" />
                	</br></br>
                    <label for="epl_am_map_clustering" style="width:50%;display: inline-block;">
                		<?php _e("Allow marker clustering on map", "epl-am"); ?>
            		</label> &nbsp;&nbsp;&nbsp;

                    <input type="checkbox" id="epl_am_map_clustering" />
                    </br></br>
                    <label for="epl_am_map_height" style="width:50%;display: inline-block;">
                		<?php _e("Only show listings open for inspection", "epl-am"); ?>
            		</label> &nbsp;&nbsp;&nbsp;

                    <input type="checkbox" id="epl_am_map_inspection" />
                    </br></br>

                </div>
                <div style="padding:15px;">
                    <input type="button" class="button-primary" value="<?php _e("Insert Map", "epl-am"); ?>" onclick="eplAmInsertMap();"/>&nbsp;&nbsp;&nbsp;
                <a class="button" style="color:#bbb;" href="#" onclick="tb_remove(); return false;"><?php _e("Cancel", "epl-am"); ?></a>
                </div>
            </div>
        </div>
    </div>

    <?php
}
add_action('admin_footer',  'epl_am_add_mce_popup');

/** option for epl slider to show in adv map tabs **/

function epl_am_add_slider_to_tabs($fields) {

	$fields[] = array(
		'name'	=>	'epl_slider_eam_mode',
		'label'	=>	'<strong>' . __('Advanced Mapping Extension:','epl-am') . '</strong> ' . __('Switch to tab mode','epl-am'),
		'type'	=>	'radio',
		'opts'	=>	array(
			'true'	=>	__('Enable','epl-am'),
			'false'	=>	__('Disable','epl-am'),
		),
		'default'	=>	'false',
		'help'		=>	__('Add slider to Advanced Mapping tab.','epl-am'),
	);
	return $fields;
}
add_filter('epl_slider_tab_settings','epl_am_add_slider_to_tabs');
