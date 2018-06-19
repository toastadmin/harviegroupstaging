<?php
/**
 * Metabox functions
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/Metaboxes
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Registers meta boxes
 *
 * @since 1.0
 * @return void
 */
function epl_sd_add_meta_box( $meta_box) {

	$meta_fields = array(

		array(
			'id'		=>	'epl_directory_id',
			'label'		=>	__('Contact Details', 'epl-staff-directory'),
			'post_type'	=>	array('directory'),
			'context'	=>	'normal',
			'priority'	=>	'high',
			'groups'	=>	array(
				array(
					'columns'	=>	'1',
					'label'		=>	'',
					'fields'	=>	array(
						array(
							'name'		=>	'epl_sd_section',
							'label'		=>	__('Staff Section', 'epl-staff-directory'),
							'type'		=>	'checkbox_single',
							'opts'		=>	array(
									'yes'	=>	__('Yes' , 'epl-staff-directory'),
								),
							'help'		=>	__('Enable this to create a section', 'epl-staff-directory')
						)
					)
				)
			)
		)
	);
	foreach($meta_fields as $blocks) {
		$meta_box[] = $blocks;
	}
	return  $meta_box;
}
add_filter('epl_listing_meta_boxes','epl_sd_add_meta_box');

/**
 * Add metabox for author details
 *
 * @since 2.2
 */
function epl_sd_add_meta_box_author_details() {
	global $post;

	$section	= get_post_meta( $post->ID, 'epl_sd_section', true );

	if ( $section != 'yes' )
		add_meta_box( 'epl_sd_linked_author_details', __( 'Linked Author Details', 'epl-staff-directory' ), 'epl_sd_add_meta_box_author_details_callback', 'directory' , 'normal' , 'high');
}
add_action( 'add_meta_boxes', 'epl_sd_add_meta_box_author_details' );

/**
 * User details populate in metabox
 *
 * @since 2.2
 */
function epl_sd_add_meta_box_author_details_callback( $post ) {

	$epl_author 	= new EPL_Author_meta($post->post_author);

	$details = apply_filters( 'epl_sd_meta_boxes_details_fields' , array(

		'mobile'	=>	__('Mobile', 'epl-staff-directory'),
		'email'		=>	__('Email', 'epl-staff-directory'),
		'position'	=>	__('Position', 'epl-staff-directory'),
		'slogan'	=>	__('Slogan', 'epl-staff-directory'),
		'description'	=>	__('Bio', 'epl-staff-directory'),
		'video'		=>	__('Video', 'epl-staff-directory'),
		'facebook'	=>	__('Facebook', 'epl-staff-directory'),
		'linkedin'	=>	__('LinkedIn', 'epl-staff-directory'),
		'google'	=>	__('Google', 'epl-staff-directory'),
		'twitter'	=>	__('Twitter', 'epl-staff-directory'),
		'skype'		=>	__('Skype', 'epl-staff-directory'),
		'google'	=>	__('Google', 'epl-staff-directory'),

	) );

	?>
	<div class="epl-inner-div col-1 table-normal">
		<div>
		<span><?php _e('Edit User' , 'epl-staff-directory' ); ?> <a href="<?php echo get_edit_user_link( $epl_author->author_id ); ?>"><?php echo $epl_author->name; ?></a></span>
		</div>
		<table class="form-table epl-form-table">
			<tbody>
				<?php
				foreach ( $details as $key => $value ) { ?>
					<tr class="form-field">
						<th valign="top" scope="row" style="padding: 0 0 1em">
							<label for="property_heading"><?php echo $value; ?></label>
						</th>
						<td style="padding: 0 0 1em">
							<?php echo $epl_author->$key; ?>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
}
