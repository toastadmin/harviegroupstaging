<?php
/**
 * Add License Key Option
 * @since 1.0
**/
function epl_fs_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'frontend_submissions',
				'label'	=>	'Frontend Submissions license key',
				'type'	=>	'text'
			)
		)
	);
	return $fields;
}
add_filter('epl_license_options_filter', 'epl_fs_license_options_filter', 10, 3);


function epl_fs_ajax_tag_search() {
	global $wpdb;

	if ( isset( $_GET['tax'] ) ) {
		$taxonomy = sanitize_key( $_GET['tax'] );
		$tax = get_taxonomy( $taxonomy );
		if ( ! $tax )
			wp_die( 0 );
	} else {
		wp_die( 0 );
	}

	$s = wp_unslash( $_GET['q'] );

	$comma = _x( ',', 'tag delimiter' );
	if ( ',' !== $comma )
		$s = str_replace( $comma, ',', $s );
	if ( false !== strpos( $s, ',' ) ) {
		$s = explode( ',', $s );
		$s = $s[count( $s ) - 1];
	}
	$s = trim( $s );
	if ( strlen( $s ) < 2 )
		wp_die(); // require 2 chars for matching

	$results = get_terms( $taxonomy, array( 'name__like' => $s, 'fields' => 'names', 'hide_empty' => false ) );

	echo json_encode($results);
	wp_die();
}

add_action('wp_ajax_epl_fs_ajax_tag_search','epl_fs_ajax_tag_search');



function show_current_user_attachments( $query = array() ) {
	
	// admins get to see everything
	if ( ! current_user_can( 'manage_options' ) )
		$query['author'] = get_current_user_id();
	return $query; 
}
add_filter( 'ajax_query_attachments_args', 'show_current_user_attachments', 10, 1 );

/*
** Set or unset a post thumbnail
*/
function epl_fs_set_post_thumbnail() {
	$json = ! empty( $_REQUEST['json'] ); // New-style request

	$post_ID = intval( $_POST['post_id'] );

	$thumbnail_id = intval( $_POST['thumbnail_id'] );

	if ( $thumbnail_id == '-1' ) {
		if ( delete_post_thumbnail( $post_ID ) ) {
			$return = epl_fs_wp_post_thumbnail_html( null, $post_ID );
			$json ? wp_send_json_success( $return ) : $return;
		} else {
			die;
		}
	}

	if ( set_post_thumbnail( $post_ID, $thumbnail_id ) ) {
		$return = epl_fs_wp_post_thumbnail_html( $thumbnail_id, $post_ID );
		$json ? wp_send_json_success( $return ) : $return;
	}

	die;
}

add_action('wp_ajax_epl_fs_set_post_thumbnail','epl_fs_set_post_thumbnail');

/*
** Add meta boxes for custom non - hierarchical taxonomies and also for feature Image
*/
function epl_fs_frontend_meta_box() {

	if(is_admin())
		return;
	
	$epl_posts = array('property','land', 'commercial', 'business', 'commercial_land' , 'location_profile','rental','rural','post');
	foreach($epl_posts as $post) {	
		foreach ( get_object_taxonomies( $post ) as $tax_name ) {
			$taxonomy = get_taxonomy( $tax_name );
			if ( ! $taxonomy->show_ui || false === $taxonomy->meta_box_cb )
				continue;

			$label = $taxonomy->labels->name;

			if ( ! is_taxonomy_hierarchical( $tax_name ) )
				$tax_meta_box_id = 'tagsdiv-' . $tax_name;
			else
				$tax_meta_box_id = $tax_name . 'div';

			add_meta_box( $tax_meta_box_id, $label, 'epl_fs_post_tags_meta_box', $post, 'side', 'core', array( 'taxonomy' => $tax_name ) );
		}
		add_meta_box('postimagediv', __('Featured Image'), 'epl_fs_post_thumbnail_meta_box', $post, 'side', 'low');
		add_meta_box('postdefaultfields', __('Title & Description'), 'epl_fs_post_default_fields', $post, 'normal', 'high');
	}
	
}
add_action( 'add_meta_boxes', 'epl_fs_frontend_meta_box' );

/*
** Renders the opening form tag with required hidden fields 
*/
function epl_fs_form_top($post=null) {
	$form_action 	= 'epl_fs_editpost';
	$form_extra  	= "<input type='hidden' id='post_ID' name='ID' value='" . esc_attr($post->ID) . "' />";
	$nonce_action 	= 'update-post_' . $post->id;
	ob_start(); ?>
	<div class="epl-frontend-submission-wrapper">
	<form class="epl-frontend-submission" name="post" method="post" id="post"<?php do_action( 'post_edit_form_tag', $post ); ?>>
	<?php wp_nonce_field($nonce_action,'epl_fs_nonce'); ?>
	<input type="hidden" id="hiddenaction" name="action" value="<?php echo esc_attr( $form_action ) ?>" />
	<input type="hidden" id="originalaction" name="originalaction" value="<?php echo esc_attr( $form_action ) ?>" />
	<input type="hidden" id="post_type" name="post_type" value="<?php echo esc_attr( $post->post_type ) ?>" />
	<input type="hidden" id="post_author" name="post_author" value="<?php echo get_current_user_id() ?>" />
	<?php echo $form_extra; 
	echo ob_get_clean();
}

add_action('epl_fs_form_top','epl_fs_form_top');

/*
** Renders the closing form tag with required fields 
*/
function epl_fs_form_bottom($post=null) {
	?>
	<input type="submit" value="Save" id="publish" class="button button-primary button-large" name="save">
	</form>
	</div>
	<?php
}

add_action('epl_fs_form_bottom','epl_fs_form_bottom');

/*
** Backend menu for front end submission
*/
function epl_fs_extensions_options_filter($epl_fields = null) {

	$epl_fs_roles = array();
	$roles = get_editable_roles();
	
	foreach($roles as $role_key	=>	$role_caps) {
		if(isset($role_caps['capabilities']['upload_files']) && $role_caps['capabilities']['upload_files'] == 1) {
			// these user roles can already submit. no extra capability required for them.
		} else {
			// let admin decide for rest .
			$epl_fs_roles[$role_key] = $role_caps['name'];
		}
		
	}
	
	$ep_fs_unprivileged_roles = get_option('ep_fs_unprivileged_roles');
	
	if(false === $ep_fs_unprivileged_roles) {
		// save default unprivileged roles only once
		update_option('ep_fs_unprivileged_roles',$epl_fs_roles);
		
	}else {
		$epl_fs_roles = get_option('ep_fs_unprivileged_roles');
	}
	
	$fields = array();
	$epl_lp_fields = array(
		'label'		=>	__('Frontend Submissions')
	);
	$fields[] = array(
		'label'		=>	'Frontend Submissions',
		'fields'	=>	array(
			array(
					'name'	=>	'epl_fs_allowed_roles',
					'label'	=>	__('Allow frontend submissions roles', 'epl'),
					'type'	=>	'checkbox',
					'opts'	=>	$epl_fs_roles,
					'help'	=>	__('These roles will be able to use frontend submissions form to submit listings' , 'epl')
				),
		)
	);
	$epl_lp_fields['fields'] = $fields;
	$epl_fields['frontend_submission'] = $epl_lp_fields;
	return $epl_fields;
}
add_filter('epl_extensions_options_filter_new', 'epl_fs_extensions_options_filter', 10, 1);

/*
** provide necessary capabilities to allowed user roles for frontend submission
*/
function epl_fs_modify_submission_caps () {
	global $epl_settings;
	$epl_fs_roles = get_option('ep_fs_unprivileged_roles');
	if(isset($epl_settings['epl_fs_allowed_roles'])) {
		$allowed_roles = $epl_settings['epl_fs_allowed_roles'];

		if(!empty($epl_fs_roles)) {
			foreach($epl_fs_roles as $epl_fs_role_key	=>	$epl_fs_role_name) {
			
				// give required caps to this role if allowed by admin
				if(!empty($allowed_roles) &&  in_array($epl_fs_role_key,$allowed_roles) ) {
					 $role = get_role( $epl_fs_role_key );
					 $role->add_cap( 'edit_posts' );
					 $role->add_cap( 'upload_files' );
					 $role->add_cap( 'manage_categories' );
				} else {
					// reset to default
					 $role = get_role( $epl_fs_role_key );
					 $role->remove_cap( 'edit_posts' );
					 $role->remove_cap( 'upload_files' );
					 $role->remove_cap( 'manage_categories' );
				}
				
			}
		}
	}
	
}

// add hook for front end only
add_action('init','epl_fs_modify_submission_caps');

// filter out additional metaboxes added by other extensions
function epl_fs_hide_metabox($removed_meta_boxes) {
	$removed_meta_boxes[] = 'epl_la_alerts_section';
	return $removed_meta_boxes;
}
add_filter('epl_fs_hide_metabox','epl_fs_hide_metabox');


function epl_fs_edit_shortcode($content) {
	
	if( ! is_user_logged_in() ) {
		return $content;
	}
	
	if( isset($_GET['fsid']) && intval($_GET['fsid']) > 0 && isset($_GET['epl_fs_edit_action']) && $_GET['epl_fs_edit_action'] == 'epl-fs-fedit' ) {
	
		$listing 		= get_post(intval($_GET['fsid']) );
		
		if( !is_null($listing) ) {
		
			$active_posts 	= array_keys( epl_get_active_post_types() );
		
			if( $listing->post_author == get_current_user_id() && in_array($listing->post_type,$active_posts) ) {
			
				$shortcode = '[epl_frontend_submission id="'.$listing->ID.'"]';
				//$content   = $content.' '.$shortcode;
				$content   = $shortcode;
			}
		}
	}
	return $content;
}
add_filter('the_content','epl_fs_edit_shortcode');
