<?php
/**
 * SHORTCODE :: epl_brochures_list [epl_brochures_list]
 *
 * @package     EPL-BR
 * @subpackage  Shortcode
 * @copyright   Copyright (c) 2014, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Only load on front
if( is_admin() ) {
	return; 
}

function epl_br_shortcode_listing_callback( $atts ) {

	$property_types = epl_get_active_post_types();
	if(!empty($property_types)) {
		 $property_types = array_keys($property_types);
	}
	$atts = 
	shortcode_atts( array(
		'label'			=>	__('Brochure','epl-br'),
		'title'			=>	'',
		'post_type' 		=>	$property_types, //Post Type
		'status'		=>	array('current' , 'sold' , 'leased' ),
		'limit'			=>	'10', // Number of maximum posts to show
		'template'		=>	false, // Template can be set to "slim" for home open style template
		'location'		=>	'', // Location slug. Should be a name like sorrento
		'tools_top'		=>	'off', // Tools before the loop like Sorter and Grid on or off
		'tools_bottom'		=>	'off', // Tools after the loop like pagination on or off
		'sortby'		=>	'', // Options: price, date : Default date
		'sort_order'		=>	'DESC',
		'query_object'		=>	'' // only for internal use . if provided use it instead of custom query 
	), $atts );
	
	ob_start();
	
	$query_string =  http_build_query( array_filter($atts) ); ?>
	
	<div class="epl-button button-br">
		<a 
			href="<?php echo get_bloginfo('url').'?epl_br_action=generate_list&'.$query_string; ?>" 
			class="epl_brochures_list_button" 
			target="_blank" 
			rel="nofollow"
		><?php echo $atts['label']; ?></a>
	</div> <?php
	return ob_get_clean();
}


function epl_brochures_listing( $atts ) {
	global $epl_settings;
	
	$property_types = epl_get_active_post_types();
	if(!empty($property_types)) {
		 $property_types = array_keys($property_types);
	}
	
	extract( shortcode_atts( array(
		'title' 		=>	'', //Title
		'post_type' 		=>	$property_types, //Post Type
		'status'		=>	array('current' , 'sold' , 'leased' ),
		'limit'			=>	'10', // Number of maximum posts to show
		'template'		=>	false, // Template can be set to "slim" for home open style template
		'location'		=>	'', // Location slug. Should be a name like sorrento
		'tools_top'		=>	'off', // Tools before the loop like Sorter and Grid on or off
		'tools_bottom'		=>	'off', // Tools after the loop like pagination on or off
		'sortby'		=>	'', // Options: price, date : Default date
		'sort_order'		=>	'DESC',
		'query_object'		=>	'' // only for internal use . if provided use it instead of custom query 
	), $atts ) );
	
	if(is_string($post_type) && $post_type == 'rental') {
		$meta_key_price = 'property_rent';
	} else {
		$meta_key_price = 'property_price';
	}
	
	$sort_options = array(
		'price'			=>	$meta_key_price,
		'date'			=>	'post_date'
	);
	if( !is_array($post_type) ) {
		$post_type 			= array_map('trim',explode(',',$post_type) );
	}
	ob_start();
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$args = array(
		'post_type' 		=>	$post_type,
		'posts_per_page'	=>	$limit,
		'paged' 		=>	$paged
	);
	
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

	if( empty($template) ) {
		$template =	isset( $epl_settings['epl_br_brochure_list_template'] ) ? $epl_settings['epl_br_brochure_list_template'] : false;
	}

	if(!empty($status)) {
		if(!is_array($status)) {
			$status = explode(",", $status);
			$status = array_map('trim', $status);
			
			$args['meta_query'][] = array(
				'key' => 'property_status',
				'value' => $status,
				'compare' => 'IN'
			);
			
			add_filter('epl_sorting_options',function($sorters) {
				foreach($sorters as $key	=>	 &$sorter) {
					if($sorter['id'] == 'status_asc' || $sorter['id'] == 'status_desc') {
						unset($sorters[$key]);
					}	
				}
				return $sorters;
			});
		}
	}

	if( $sortby != '' ) {
	
		if($sortby == 'price') {
			$args['orderby']	=	'meta_value_num';
			$args['meta_key']	=	$meta_key_price;
		} else {
			$args['orderby']	=	'post_date';
			$args['order']		=	'DESC';

		}
		$args['order']			=	$sort_order;
	}
	
	if( isset( $_GET['sortby'] ) ) {
		$orderby = sanitize_text_field( trim($_GET['sortby']) );
		if($orderby == 'high') {
			$args['orderby']	=	'meta_value_num';
			$args['meta_key']	=	$meta_key_price;
			$args['order']		=	'DESC';
		} elseif($orderby == 'low') {
			$args['orderby']	=	'meta_value_num';
			$args['meta_key']	=	$meta_key_price;
			$args['order']		=	'ASC';
		} elseif($orderby == 'new') {
			$args['orderby']	=	'post_date';
			$args['order']		=	'DESC';
		} elseif($orderby == 'old') {
			$args['orderby']	=	'post_date';
			$args['order']		=	'ASC';
		} elseif($orderby == 'status_desc') {
			$args['orderby']	=	'meta_value';
			$args['meta_key']	=	'property_status';
			$args['order']		=	'DESC';
		} elseif($orderby == 'status_asc') {
			$args['orderby']	=	'meta_value';
			$args['meta_key']	=	'property_status';
			$args['order']		=	'ASC';
		}
		
	}

	$query_open = new WP_Query( $args );
	
	if( is_object($query_object) ) {
		$query_open = $query_object;
	}
	
	if ( $query_open->have_posts() ) { ?>
		<?php
			if ( !empty($title) ) { ?>
			<div class="loop-header epl-brochure-header">
				<h1 class="entry-title"><?php echo $title; ?></h1>
			</div>
		<?php }?> 
		<div class="loop epl-shortcode">
			<div class="loop-content epl-shortcode-listing <?php echo epl_template_class( $template ); ?>">
				<?php
					if ( $tools_top == 'on' ) {
						do_action( 'epl_property_loop_start' );
					}
					while ( $query_open->have_posts() ) {
						$query_open->the_post();
						$template = str_replace('_','-',$template);
						epl_property_blog($template);
					}
					if ( $tools_bottom == 'on' ) {
						do_action( 'epl_property_loop_end' );
					}
				?>
			</div>
			<div class="loop-footer">
					<?php do_action('epl_pagination',array('query'	=>	$query_open)); ?>
			</div>
			
			<div class="entry-footer epl-clearfix">
				<?php do_action( 'epl_br_office_details' ); ?>
				<?php do_action( 'epl_br_disclaimer' ); ?>
			</div>
		</div>
		<?php
	} else {
		echo '<h3>'.__('Nothing found, please check back later.', 'epl-br').'</h3>';
	}
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'epl_brochures_list', 'epl_br_shortcode_listing_callback' );
