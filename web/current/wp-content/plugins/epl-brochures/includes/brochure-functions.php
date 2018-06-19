<?php
/**
 * Functions
 *
 * @package     EPL-BR
 * @subpackage  General Functions
 * @copyright   Copyright (c) 2014, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/**
 * Output Attachment Images on brochure
 *
 * @since 1.0
 */
function epl_br_attachment_images( $size = 'epl-image-medium-crop') {

	$postID = isset($_GET['id']) ? intval($_GET['id']) : '';
	global $epl_settings;

	$attachment_images	=	isset( $epl_settings['epl_br_attached_images'] ) ? $epl_settings['epl_br_attached_images'] : 2;

	$args = array(
		'numberposts' 		=> $attachment_images,
		'order' 		=> 'ASC',
		'post_mime_type' 	=> 'image',
		'post_parent' 		=> $postID,
		'post_status' 		=> null,
		'post_type' 		=> 'attachment',
		'offset' 		=> 1
	);

	$attachments = get_children( $args );

	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {
			$image_attributes = wp_get_attachment_image_src( $attachment->ID, $size )  ? wp_get_attachment_image_src( $attachment->ID, $size ) : wp_get_attachment_image_src( $attachment->ID, $size );

			?>
			<img src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>">
			<?php
		}
	}
}

/**
 * Brochure Style
 *
 * @since 1.0
 */
function epl_br_brochure_style_callback() {
	global $epl_settings,$post;
	$brochure				=	isset( $epl_settings['epl_br_brochure_style'] ) ? $epl_settings['epl_br_brochure_style'] : 'default';
	$arg_list 				= 	get_defined_vars();

	$brochure_layouts = apply_filters( 'epl_brochure_layouts', array('wide','row') );

	if( in_array($brochure,$brochure_layouts) ) {
		epl_br_get_template_part('brochure-'.$brochure.'.php',$arg_list);
	}
	else {
		epl_br_get_template_part('brochure.php',$arg_list);
	}
}
add_action( 'epl_br_brochure_style' , 'epl_br_brochure_style_callback' );

/**
 * Allowed HTML
 *
 * @since 1.1
 */
function epl_br_allowed_html() {
	$allowed_html = apply_filters( 'epl_br_allowed_html_filter' ,array(
		'a' => array(
			'href' => array(),
			'title' => array()
		),
		'img' => array(
			'href' => array(),
			'src' => array(),
			'height' => array(),
			'width' => array(),
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
			'style' => array()
		),
		'br' => array(),
		'hr' => array(),
		'em' => array(),
		'strong' => array(),
	) );
	return $allowed_html;
}

/**
 * Output Disclaimer
 *
 * @since 1.0
 */
function epl_br_disclaimer_callback() {
	global $epl_settings;
	$disclaimer	=	isset( $epl_settings['epl_br_disclaimer'] ) ? $epl_settings['epl_br_disclaimer'] : '';

	if ( $disclaimer != '' ) {
		$disclaimer = '<div class="epl-br-disclaimer">' . $disclaimer . '</div>';
		echo wp_kses( $disclaimer , epl_br_allowed_html() );
	}

}
add_action( 'epl_br_disclaimer' , 'epl_br_disclaimer_callback' );

/**
 * Output Office Details
 *
 * @since 1.0
 */
function epl_br_office_details_callback() {
	global $epl_settings;
	$office_details	=	isset( $epl_settings['epl_br_office_details'] ) ? $epl_settings['epl_br_office_details'] : '';

	$allowed_html = array(
		'a' => array(
			'href' => array(),
			'title' => array()
		),
		'img' => array(
			'href' => array(),
			'src' => array(),
			'height' => array(),
			'width' => array(),
			'class' => array(),
		),
		'p' => array(
			'class' => array(),
			'style' => array()
		),
		'br' => array(),
		'em' => array(),
		'strong' => array(),
	);

	if ( $office_details != '' ) {
		echo '<div class="epl-br-office-details">';
		echo apply_filters( 'the_content' , wp_kses( $office_details , epl_br_allowed_html() ) );
		echo '</div>';
	}

}
add_action( 'epl_br_office_details' , 'epl_br_office_details_callback' );

/**
 * Author Contact Details
 *
 * @since 1.0
 */
function epl_br_author_details_callback() {
	global $property;
	$epl_author = new EPL_Author_Meta($property->post->post_author);
	$name 		= apply_filters('epl_author_profile_title',get_the_author_meta( 'display_name',$epl_author->author_id ) ,$epl_author );
	$position 	= $epl_author->get_author_position();
	$mobile		= $epl_author->get_author_mobile();

	?>
	<div class="epl-brochure-author-wrapper">
		<div class="epl-brochure-author">
			<div class="epl-brochure-author-image">
				<?php do_action('epl_author_thumbnail',$epl_author); ?>
			</div>

			<div class="epl-brochure-author-details">
				<div class="epl-brochure-author-name">
					<?php echo $name; ?>
				</div>

				<div class="epl-brochure-author-position">
					<?php echo $position; ?>
				</div>

				<div class="epl-brochure-author-mobile">
					<?php echo $mobile; ?>
				</div>

				<div class="epl-brochure-author-description">
					<?php epl_author_tab_description($epl_author); ?>
				</div>
			</div>

		</div>
	</div>

	<?php
}
add_action( 'epl_br_author_details' , 'epl_br_author_details_callback' );

/**
 * Floor Plans
 *
 * @since 1.0
 */
function epl_br_floor_plan_callback() {
	global $property;

	$floor_plan	= $property->get_property_meta( 'property_floorplan' );
	$floor_plan_2	= $property->get_property_meta( 'property_floorplan_2' );

	$links = array();
	if(!empty($floor_plan)) {
		$links[] = $floor_plan;
	}
	if(!empty($floor_plan_2)) {
		$links[] = $floor_plan_2;
	}
	if ( !empty($links) ) {
		echo '<div class="page-break"></div>';
		echo '<div class="epl-brochure-floor-plan-wrapper">';
		foreach ( $links as $k=>$link ) {
			if(!empty($link)) {
				$number_string = '';
				if($k > 0) {
					$number_string = ' ' . $k + 1;
				}
				?>
				<div class="epl-brochure-floor-plan floor-plan-<?php echo $number_string; ?>">
					<img src="<?php echo $link; ?>"/>
				</div>
				<?php
			}
		}
		echo '</div>';
	}
}
add_action('epl_br_floor_plan', 'epl_br_floor_plan_callback');


/*
 * Attempts to load templates in order of priority
 *
 * @since 1.1
 */
function epl_br_get_template_part($template,$arguments=array()) {

	$base_path	= apply_filters('epl_br_templates_base_path',EPL_BR_PLUGIN_PATH_TEMPLATES);
	$default	= $template;
	$find[] 	= epl_template_path() . $template;
	$template       = locate_template( array_unique( $find ) );
	if(!$template) {
		$template	=	$base_path . $default;
	}
	if( !isset($arguments['epl_author']) ) {
		global $epl_author;
	}
	extract($arguments);
	include( $template);
}

/*
 * Output theme CSS
 *
 * @since 1.1
 */
function epl_br_theme_css() {
	global $epl_settings;
	$theme_css	=	isset( $epl_settings['epl_br_theme_css'] ) ? $epl_settings['epl_br_theme_css'] : 0;

	if ( $theme_css == 1 ) {
		$output = '<link rel="stylesheet" href="' . set_url_scheme( EPL_BR_THEME_CSS ) . EPL_BR_CSS_VERSION_CORE .'" type="text/css" media="all" />';
		echo $output;
	}

}

/*
 * Printable Icons
 *
 * @since 1.1
 */
function epl_br_get_property_icons() {
	global $property;
	return $property->get_property_bed( 'l' ).
		$property->get_property_bath( 'l' ).
		$property->get_property_parking( 'l' ).
		$property->get_property_air_conditioning( 'l' ).
		$property->get_property_pool( 'l' );
}

/*
 * Output Printable Icons
 *
 * @since 1.1
 */
function epl_br_property_icons() {
	echo epl_br_get_property_icons();
}

/*
 * Output Printable Icons
 *
 * @since 1.1
 */
function epl_br_get_post_id($key, $value) {
	global $wpdb;

	$key	= esc_sql( $key );
	$value	= esc_sql( $value );

	$meta = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".$key."' AND meta_value='".$value."'");
	if (is_array($meta) && !empty($meta) && isset($meta[0])) {
		$meta = $meta[0];
	}
	if (is_object($meta)) {
		return $meta->post_id;
	}
	else {
		return false;
	}
}

/*
 * Load Google Maps API Key from EPL Core
 *
 * @since 1.3
 */
function epl_br_google_maps_key_callback() {
	global $epl_settings;

	$key =  $epl_settings['epl_google_api_key'];

	if ( isset( $key ) )
		echo '&key='.$key;
}

/*
 * Load Custom CSS from active theme /easypropertylistings/css/ folder
 *
 * @since 1.3
 */
function epl_br_get_custom_css_url( $file ) {
	if ( file_exists( EPL_BR_CUSTOM_CSS . $file ) ) {
		$url = EPL_BR_CUSTOM_CSS_URL . $file;
	} else {
		$url = EPL_BR_CSS . $file;
	}
	return $url;
}