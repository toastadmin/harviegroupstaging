<?php

function epl_frontend_submission($atts) {

	extract( 
		shortcode_atts( 
			array(
				'post_type' 	=>	'property',
				'id'		=>	0
			),
		$atts
		)
	);
	
	global $wp_meta_boxes;
	if((int) $id == 0 )
		$post = epl_fs_default_post( $post_type, true );
	else
	$post = get_post($id);
	
	$post_type = $post->post_type;
	// Add meta boxes for frontend
	do_action('add_meta_boxes', $post_type, $post);
	do_action('add_meta_boxes_' . $post_type, $post);

	// Show Meta Boxes
	ob_start();
	do_action('epl_fs_form_top',$post);
	epl_fs_do_meta_boxes( $post_type, 'normal', $post );
	epl_fs_do_meta_boxes( $post_type, 'advanced', $post );
	epl_fs_do_meta_boxes( $post_type, 'side', $post );
	do_action('epl_fs_form_bottom',$post);
	return ob_get_clean();
}

add_shortcode('epl_frontend_submission','epl_frontend_submission');


// show listing of current user
function epl_fs_user_listings() {
	
	if( is_user_logged_in() ) {
		
		$args = array(
			'post_type'	=>	array_keys( epl_get_active_post_types() ),
			'author'	=>	get_current_user_id(),
		);
		
		
		$listings = new WP_Query($args);
	
		if( !empty($listings) ) {
		
			ob_start();
			echo '<ul class="epl-fs-user-listings">';
			while ( $listings->have_posts() ) {
				$listings->the_post();
				echo '<li>'.
						get_the_title().'
						<span class="epl-fs-edit">
							<a target="_blank" href="'.get_permalink().'">'.
								__('View','epl').'
							</a>
							&nbsp;&nbsp;
							<a target="_blank" href="?fsid='.get_the_ID().'&epl_fs_edit_action=epl-fs-fedit">'.
								__('Edit','epl').'
							</a>
						</span>
					</li>';
			}
			echo '</ul>';
			do_action('epl_pagination',array('query'	=>	$listings) );
			wp_reset_postdata();
			return ob_get_clean();
		}
	}
}
add_shortcode('epl_frontend_user_listings','epl_fs_user_listings');

