<?php
/**
 * Functions - Child theme custom functions
 */


/*****************************************************************************************************************
************************** Caution: do not remove or edit anything within this section **************************/

/**
 * Loads the Divi parent stylesheet.
 * Do not remove this or your child theme will not work unless you include a @import rule in your child stylesheet.
 */
function dce_load_divi_stylesheet() {

	wp_enqueue_script( 'select2', get_stylesheet_directory_uri() . '/js/select2.min.js', array('jquery'), false, true );
	wp_enqueue_style( 'select2css', get_stylesheet_directory_uri() . '/css/select2.min.css' );
	
    wp_enqueue_style( 'divi-parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_script( 'divi-child-script', get_stylesheet_directory_uri() . '/theme.js', array('select2'), false, true );
}
add_action( 'wp_enqueue_scripts', 'dce_load_divi_stylesheet' );

/**
 * Makes the Divi Children Engine available for the child theme.
 * Do not remove this or you will lose all the customization capabilities created by Divi Children Engine.
 */
require_once('divi-children-engine/divi_children_engine.php');

/****************************************************************************************************************/
/**
 * disable fancy box for the gallery page
 */
function disable_fancybox() {
	if ( is_page( 100 ) ) {
		remove_action('wp_enqueue_scripts', array('easyFancyBox', 'enqueue_styles'), 999);
		remove_action('wp_head', array('easyFancyBox', 'main_script'), 999);
		remove_action('wp_print_scripts', array('easyFancyBox', 'register_scripts'), 999);
		remove_action('wp_footer', array('easyFancyBox', 'enqueue_footer_scripts'));
		remove_action('wp_footer', array('easyFancyBox', 'on_ready'), 999);
	}
}

add_action( 'wp_head', 'disable_fancybox', 0 );

function my_epl_modify_listing_quantity( $query ) {
	// Do nothing if in dashboard or not an archive page
	if ( is_admin() || ! $query->is_main_query() )
		return;

	// Do nothing if Easy Property Listings is not active
	if ( ! function_exists( 'epl_all_post_types' ) )
		return;

	// Modify the number of all EPL listing types displayed on archive page
	if ( is_epl_post_archive() || epl_is_search() ) {

/*		if($_GET['post_type'] == 'development'){
			
			$meta_query = $query->get('meta_query');
			$meta_query[] = array(
				'key'	=>	'property_status',
				'value'	=>	'development'
			);
			$meta_query = $query->set('meta_query',$meta_query);
			$query->set( 'post_type' , 'property' );
			epl_print_r($query,true);
		}*/
        if($_GET['project_type'] == 'development'){
        	$str = $_GET['property_address_suburb_postcode'];
           	$meta_query = $query->get('meta_query');
           /*$meta_query[] = array(
                'key'	=>	'project_suburb',
               'value'	=>	'Roseville'
            );*/
            $meta_query[] = array(
                'multiple'	=>	true,
                'query'		=>	'meta',
                'relation'	=>	'OR',
                'sub_queries'	=> array(
                    'relation' => 'OR',
                    array(
                        'key'		=>	'project_address',
                        'compare' => 'LIKE',
                        'value'	=>	 $str
                    ),
                    array(
                        'key'		=>	'project_suburb',
                        'compare' => 'LIKE',
                        'value'	=>	$str
                    ),
                    array(
                        'key'		=>	'project_postcode',
                        'compare' => 'LIKE',
                        'value'	=>	$str
                    )
                )
			);
            $meta_query = $query->set('meta_query',$meta_query);
            $query->set( 'post_type' , 'page' );
        }
        else if($_GET['project_type'] == 'currentproject'){
//            $meta_query = $query->get('meta_query');
//            $meta_query[] = array(
//                'key'	=>	'project_type',
//                'value'	=>	'Development'
//            );
//            $meta_query = $query->set('meta_query',$meta_query);
            $query->set( 'post_type' , 'page' );
        }
		else  if($_GET['post_type'] == 'property'){

                        $meta_query = $query->get('meta_query');
                        $meta_query[] = array(
                                'key'   =>      'property_status',
                                'value' =>      'current'
                        );
                        $meta_query = $query->set('meta_query',$meta_query);
                        #$query->set( 'post_type' , 'property' );
                        #epl_print_r($query,true);
		}




		$query->set( 'posts_per_page' , 9 ); // Adjust the quantity
		return;
	}
}
add_action( 'pre_get_posts', 'my_epl_modify_listing_quantity' , 999 );

function rec_search_widget_frontend_posttype_callback($fields) {

	foreach($fields as $k => &$field) {

		if($field['key'] == 'property_status'){
			unset($fields[$k]);
		}
		if($field['key'] == 'post_type'){
			$field = array(
				'key'			=>	'post_type',
				'meta_key'		=>	'post_type',
				'type'			=>	'select',
				'order'			=>	11,
				'config'		=>	'on',
				'class'			=>	'epl-search-row-half',
				'label'			=>	__('Property Status', 'easy-property-listings'),
				'option_filter'	=>	'property_type',
				'options'		=>	array(
					'property'		=>	__('For Sale','easy-property-listings'),
					'rental'		=>	__('For Rent','easy-property-listings'),
					/*'development'		=>  __('Development','easy-property-listings'),*/
                    'developmentsite'		=>  __('Development site','easy-property-listings'),
                    'CurrentProject'		=>  __('Current projects','easy-property-listings'),
				),
				'query'			=>	array(),
			);
		}

	}

	$fields[] = array(
		'key'			=>	'search_project_type',
		'meta_key'		=>	'project_type',
		'label'			=>	'Project Type',
		'type'			=>	'select',
		'option_filter'		=>	'city',
		'options'		=>	epl_get_unique_post_meta_values('project_type', $post_type ),
		'query'			=>	array('query'	=>	'meta'),
		'class'			=>	'epl-search-row-half',
		'order'			=>	50
	);
	
    return $fields;
}
add_filter('epl_search_widget_fields_frontend', 'rec_search_widget_frontend_posttype_callback');

function rec_epl_search_widget_label_post_type() {
	$label = 'For Sale';
	return $label;
}
add_filter( 'epl_search_widget_option_label_property_type' , 'rec_epl_search_widget_label_post_type' );

function my_custom_epl_search_placeholder($fields) {
	foreach($fields as &$field) {       

		if($field['key'] == 'search_address'){
			$field['placeholder'] = 'Search Suburb, Postcode, Address';
		}
	}
	return $fields;
}
add_filter( 'epl_search_widget_fields_frontend' , 'my_custom_epl_search_placeholder' );

function rec_epl_search_widget_option_label_category() {
 $label = 'Property Type';
 return $label;
}
add_filter( 'epl_search_widget_option_label_category' , 'rec_epl_search_widget_option_label_category' );

function rec_epl_search_widget_option_label_location() {
 $label = 'Suburbs';
 return $label;
}
add_filter( 'epl_search_widget_option_label_location' , 'rec_epl_search_widget_option_label_location' );

function rec_epl_search_widget_option_label_price_from() {
 $label = 'Price Min';
 return $label;
}
add_filter( 'epl_search_widget_option_label_price_from' , 'rec_epl_search_widget_option_label_price_from' );

function rec_epl_search_widget_option_label_price_to() {
 $label = 'Price Max';
 return $label;
}
add_filter( 'epl_search_widget_option_label_price_to' , 'rec_epl_search_widget_option_label_price_to' );

function rec_epl_search_widget_option_label_bedrooms() {
 $label = 'Bedrooms';
 return $label;
}
add_filter( 'epl_search_widget_option_label_bedrooms_min' , 'rec_epl_search_widget_option_label_bedrooms' );

function rec_epl_search_widget_option_label_bathrooms() {
 $label = 'Bathrooms';
 return $label;
}
add_filter( 'epl_search_widget_option_label_bathrooms' , 'rec_epl_search_widget_option_label_bathrooms' );

function rec_epl_search_widget_option_label_carport() {
 $label = 'Parking';
 return $label;
}
add_filter( 'epl_search_widget_option_label_carport' , 'rec_epl_search_widget_option_label_carport' );

function my_epl_custom_search_widget_templates($fields) {

	$fields[] = array(
		'key'		=>	'view',
		'label'		=>	__('View','easy-property-listings'),
		'default'	=>	'default',
		'type'		=>	'select',
		'options'	=>	array(
			'default'	=>	__('Default' , 'easy-property-listings'),
			'custom'	=>	__('Custom' , 'easy-property-listings'),
			'side'		=>	__('Side' , 'easy-property-listings'),
			'home'		=>	__('Home' , 'easy-property-listings'),
		)
	);
	return $fields;
}
add_filter('epl_search_widget_fields','my_epl_custom_search_widget_templates');

function rec_epl_property_bed(){
	global $property;
	echo $property->get_property_bed();
}
add_action('epl_property_bed','rec_epl_property_bed');

function rec_epl_property_category(){
	global $property;
	echo $property->get_property_category();
}
add_action('epl_property_category','rec_epl_property_category');


add_action( 'wp_footer', 'mycustom_wp_footer',100 );
 
function mycustom_wp_footer() {
?>
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {
    if ( '4032' == event.detail.contactFormId ) {
        $('.wpcf7').hide();$('#thankyou3').show();
    }
    if ( '2702' == event.detail.contactFormId ) {
        $('.wpcf7').hide();$('#thankyou').show();$('#thankyou2').show();
    }
}, false );
</script>
<?php
}

?>
