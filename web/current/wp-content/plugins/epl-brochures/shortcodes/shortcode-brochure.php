<?php
/**
 * SHORTCODE :: epl_brochure [epl_brochure]
 * Output a single listing by post id
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

function epl_br_shortcode_single_listing_callback( $atts ) {

	$atts = 
	extract( shortcode_atts( array(
		'id'			=>	0,
		'property_unique_id'	=>	'',
		'label'			=>	__('Print Brochure:','epl-br'),
		'label_type'		=>	'', // address : display address
		'label_address'		=>	'', // internal use.
	), $atts ) );
	
	ob_start();
	
	if ( !empty ( $property_unique_id ) ) {
		$id = epl_br_get_post_id( 'property_unique_id' , $property_unique_id);
	}
	
	if ( $label_type == 'address' ) { 
		$label_address = get_the_title( $id );
		$label = $label . ' ' . $label_address;
	}

	?>
		<div class="epl-button button-br">
			<a 	href="<?php echo get_bloginfo('url').'?epl_br_action=generate&'. 'id=' . $id; ?>" 
				class="epl_brochures_list_button" 
				target="_blank" 
				rel="nofollow"
			><?php echo $label; ?></a>
		</div>
	<?php
	
	return ob_get_clean();
}
add_shortcode( 'epl_brochure', 'epl_br_shortcode_single_listing_callback' );
