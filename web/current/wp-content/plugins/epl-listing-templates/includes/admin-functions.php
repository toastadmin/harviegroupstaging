<?php
/**
 * Admin Functions
 *
 * @package     EPL-LISTING-TEMPLATES
 * @subpackage  Functions/Admin
 * @copyright   Copyright (c) 2015, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display options configured from admin panel
 *
 * @since 1.0
 */
function epl_templ_display_options_filter( $epl_fields = null ) {

	$fields = array();
	$epl_templ_fields = array(
		'label'		=>	__('Listing Templates','epl-listing-templates'),
	);

	/** for single templates **/
	$fields[] = array(
		'label'		=> __('Single','epl-listing-templates'),
		'fields'	=> array(

			array(
				'name'	=> 'epl_display_featured_image',
				'label'	=> __('Single Listing Template: Featured Image','epl-listing-templates'),
				'type'	=> 'radio',
				'opts'	=> array(
					0 	=> __('Enable',	'epl-listing-templates'),
					1 	=> __('Disable','epl-listing-templates')
				)
			),

			array(
				'name'	=> 'epl_display_author_title',
				'label'	=> __('Author Box: Display the Real Estate agent or Property Manager title','epl-listing-templates'),
				'type'	=> 'radio',
				'opts'	=> array(
					1 	=> __('Enable','epl-listing-templates'),
					0 	=> __('Disable','epl-listing-templates')
				)
			),

			array(
				'name'	=> 'epl_display_single_property',
				'label'	=> __('Single Listing Template','epl-listing-templates'),
				'type'	=> 'select',
				'opts'	=> epl_temp_single_mapper(true)
			)
		)
	);

	/** for archive templates **/
	$fields[] = array(
		'label'		=> __( 'Archives','epl-listing-templates'),
		'fields'	=> array(

			array(
				'name'	=> 'listings_masonry',
				'label'	=> __('Masonry Effect?', 'epl-listing-templates'),
				'type'	=> 'radio',
				'opts'	=> array(
					1 	=> __('Enable','epl-listing-templates'),
					0 	=> __('Disable','epl-listing-templates')
				),
				'help' => __('Enable jQuery masonry effect when using grid view on listing archive pages.', 'epl-listing-templates')
			),

			array(
				'name'	=> 'epl_temp_hide_grid_list',
				'label'	=> __('Grid/List View', 'epl-listing-templates'),
				'type'	=> 'radio',
				'opts'	=> array(
					1 	=> __('Enable','epl-listing-templates'),
					0 	=> __('Disable','epl-listing-templates')
				),
				'default'=>	1,
				'help' => __('Show or hide the grid/list view option on listing archive pages.', 'epl-listing-templates')
			),

			array(
				'name'	=> 'epl_temp_read_more',
				'label'	=> __('Read More label', 'epl-listing-templates'),
				'type'	=> 'text',
				'default'=>	__( 'View &raquo;' , 'epl-listing-templates' ),
				'help' => __('Customise the Read More label on some templates.', 'epl-listing-templates')
			),

			array(
				'name'	=> 'epl_property_card_style',
				'label'	=> __('Loop Listing Template','epl-listing-templates'),
				'type'	=> 'select',
				'opts'	=> epl_temp_archive_mapper(true),
			)
		)
	);

	$epl_templ_fields['fields'] 		= $fields;
	$epl_fields['listing_templates'] 	= $epl_templ_fields;
	return $epl_fields;
}
add_filter('epl_extensions_options_filter_new', 'epl_templ_display_options_filter', 10, 3);

/**
 * Add option to add license of listing templates in admin
 *
 * @since 1.0
 */
function epl_templ_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=> '',
		'fields'	=> array(
			array(
				'name'	=> 'listing_templates',
				'label'	=> __('Listing Template Manager license key','epl-listing-templates'),
				'type'	=> 'text'
			)
		)
	);
	return $fields;
}
add_filter('epl_license_options_filter', 'epl_templ_license_options_filter', 10, 3);

/**
 * Register script and styles sheets for admin panel
 *
 * @since 1.0
 */
function epl_temp_admin_scripts() {
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_script('epl-temp-admin-js', EPL_TEMPLATES_JS . 'listing-templates-admin.js',	 false,		EPL_TEMPLATES_VERSION );
}
add_action('admin_enqueue_scripts', 'epl_temp_admin_scripts');

/**
 * Internal css for admin - extensions settings page
 *
 * @since 1.0
 */
function epl_temp_inline_css() { ?>
	<script>
		var epl_temp_single 	= <?php echo json_encode(epl_temp_single_mapper()); ?>;
		var epl_temp_archive 	= <?php echo json_encode(epl_temp_archive_mapper()); ?>;
		var epl_temp_url 	= '<?php echo EPL_TEMPLATES_URL; ?>';
	</script>
	<style>
		.single-temp-preview  img {
			max-width: 700px;
			width: 100%;
		}
		.archive-temp-preview.epl-field  img {
			max-width: 700px;
			width: 100%;
		}
		.archive-temp-preview {
			background: none repeat scroll 0 0 #ffffff;
			width: 100%;
		}
		.single-temp-preview.epl-third-right {
			background: none repeat scroll 0 0 #ffffff;
			min-height: 150px;
			width: 100%;
		}
	</style><?php
}
add_action('admin_head', 'epl_temp_inline_css');

/**
 * Notification for Core Legacy Styles
 *
 * @since 2.2
 */
function epl_temp_admin_notices() {

	if ( epl_get_option('epl_css_legacy') == 'on' ) {
		echo '<div class="error"><p>';
			$link = '';
			//_e( '', 'epl-listing-templates' );
			echo sprintf( __('Listing Templates: Legacy Styles currently enabled, disable for best results. Visit <a href="%s">Advanced Settings</a>.', 'epl-listing-templates' ) , esc_url( 'admin.php?page=epl-settings#epl-advanced' ) );
		echo '</p></div>';
	}
}
add_action( 'admin_notices', 'epl_temp_admin_notices' );
