<?php
/**
 * Meta-Box template function for frontend
 */
function epl_fs_do_meta_boxes( $screen, $context, $object ) {
	global $wp_meta_boxes;
	static $already_sorted = false;

	if ( empty( $screen ) )
		return;

	$page = $screen;

	// do not show these meta boxes
	$hidden = array();

	printf('<div id="%s-sortables" class="meta-box-sortables">', htmlspecialchars($context));

	$i = 0;
	do {
		// Grab the ones the user has manually sorted. Pull them out of their previous context/priority and into the one the user chose
		if ( !$already_sorted && $sorted = get_user_option( "meta-box-order_$page" ) ) {
			foreach ( $sorted as $box_context => $ids ) {
				foreach ( explode(',', $ids ) as $id ) {
					if ( $id && 'dashboard_browser_nag' !== $id )
						add_meta_box( $id, null, null, $screen, $box_context, 'sorted' );
				}
			}
		}
		$already_sorted = true;

		if ( !isset($wp_meta_boxes) || !isset($wp_meta_boxes[$page]) || !isset($wp_meta_boxes[$page][$context]) )
			break;

		foreach ( array('high', 'sorted', 'core', 'default', 'low') as $priority ) {
			if ( isset($wp_meta_boxes[$page][$context][$priority]) ) {
				foreach ( (array) $wp_meta_boxes[$page][$context][$priority] as $box ) {
					if ( false == $box || ! $box['title'] )
						continue;
					$i++;
					
					// provide a way to filter metabox for front end
					if( !in_array($box['id'] , apply_filters('epl_fs_hide_metabox',array() ) ) ) {
						$hidden_class = in_array($box['id'], $hidden) ? ' hide-if-js' : '';
						echo '<div id="' . $box['id'] . '" class="epl-fs-postbox postbox ' . epl_fs_postbox_classes($box['id'], $page) . $hidden_class . '" ' . '>' . "\n";
						if ( 'dashboard_browser_nag' != $box['id'] )
							echo '<div class="epl-fs-container handlediv" title="' . esc_attr__('Click to toggle') . '"><br /></div>';
						echo "<h3 class='epl-fs-title hndle'><span>{$box['title']}</span></h3>\n";
						echo '<div class="epl-fs-inside inside">' . "\n";
						call_user_func($box['callback'], $object, $box);
						echo "</div>\n";
						echo "</div>\n";
					}
				}
			}
		}
	} while(0);

	echo "</div>";

	return $i;

}


function epl_fs_post_thumbnail_meta_box( $post ) {
	$thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
	echo epl_fs_wp_post_thumbnail_html( $thumbnail_id, $post->ID );
}

/*
** Render html meta box for non hirarchical taxonomies 
*/
function epl_fs_post_tags_meta_box($post, $box) {
	$defaults = array('taxonomy' => 'post_tag');
	if ( !isset($box['args']) || !is_array($box['args']) )
		$args = array();
	else
		$args = $box['args'];
	extract( wp_parse_args($args, $defaults), EXTR_SKIP );
	$tax_name = esc_attr($taxonomy);
	$taxonomy = get_taxonomy($taxonomy);
	$comma = _x( ',', 'tag delimiter' );
?>
<div class="fstagsdiv" id="<?php echo $tax_name; ?>">
	<div class="epl-fs-tags" id="epl-fs-tags-<?php echo $tax_name; ?>">
		<?php 
			$terms = epl_fs_get_terms_to_edit( $post->ID, $tax_name );
			
			$terms = explode(',',$terms);
			$terms = array_filter($terms);
			if(!empty($terms)) {
				foreach($terms as $term) {
					echo '<span class="epl-fs-tag"><input type="hidden" name="tax_input['.$tax_name.'][]" value="'.$term.'"/>'.$term.'</span>';
				}
			}
			
			
		?>
	</div>
		<input type="text" id="new-tag-<?php echo $tax_name; ?>" data-tagname="<?php echo $tax_name; ?>" name="newtag[<?php echo $tax_name; ?>]" class="epl-fs-newtag form-input-tip" size="16" autocomplete="off" value="" />
</div>
<?php
}

function epl_fs_get_terms_to_edit( $post_id, $taxonomy = 'post_tag' ) {

	$post_id = (int) $post_id;
	if ( !$post_id )
		return false;

	$tags = wp_get_post_terms($post_id, $taxonomy, array());

	if ( !$tags )
		return false;

	if ( is_wp_error($tags) )
		return $tags;

	foreach ( $tags as $tag )
		$tag_names[] = $tag->name;
	$tags_to_edit = join( ',', $tag_names );
	$tags_to_edit = esc_attr( $tags_to_edit );

	$tags_to_edit = apply_filters( 'terms_to_edit', $tags_to_edit, $taxonomy );
	return $tags_to_edit;
}


/*
** Feature Image box html
*/
function epl_fs_wp_post_thumbnail_html( $thumbnail_id = null, $post = null ) {
	global $content_width, $_wp_additional_image_sizes;
	$post = get_post( $post );

	$set_thumbnail_link = '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set featured image' ) . '" data-id="%s" href="#" id="epl-fs-set-post-thumbnail" >%s</a></p>';
	$content = sprintf( $set_thumbnail_link, $post->ID, esc_html__( 'Set featured image' ) );

	if ( $thumbnail_id && get_post( $thumbnail_id ) ) {
		$old_content_width = $content_width;
		$content_width = 266;
		if ( !isset( $_wp_additional_image_sizes['post-thumbnail'] ) )
			$thumbnail_html = wp_get_attachment_image( $thumbnail_id, array( $content_width, $content_width ) );
		else
			$thumbnail_html = wp_get_attachment_image( $thumbnail_id, 'post-thumbnail' );
		if ( !empty( $thumbnail_html ) ) {
			$content = sprintf( $set_thumbnail_link, $post->ID, $thumbnail_html );
			$content .= '<p class="hide-if-no-js"><a data-id="'.$post->ID.'" href="#" id="epl-fs-remove-post-thumbnail" >' . esc_html__( 'Remove featured image' ) . '</a></p>';
		}
		$content_width = $old_content_width;
	}

	echo apply_filters( 'epl_fs_post_thumbnail_html', $content, $post->ID );
}


/*
** Meta box function for front end
*/
if(!function_exists('add_meta_box') && !defined( 'DOING_AJAX' )  && !is_admin())  {
	function add_meta_box( $id, $title, $callback, $screen = null, $context = 'advanced', $priority = 'default', $callback_args = null ) {
		global $wp_meta_boxes;

		if ( empty( $screen ) )
			return;

		$page = $screen;

		if ( !isset($wp_meta_boxes) )
			$wp_meta_boxes = array();
		if ( !isset($wp_meta_boxes[$page]) )
			$wp_meta_boxes[$page] = array();
		if ( !isset($wp_meta_boxes[$page][$context]) )
			$wp_meta_boxes[$page][$context] = array();

		foreach ( array_keys($wp_meta_boxes[$page]) as $a_context ) {
			foreach ( array('high', 'core', 'default', 'low') as $a_priority ) {
				if ( !isset($wp_meta_boxes[$page][$a_context][$a_priority][$id]) )
					continue;

				// If a core box was previously added or removed by a plugin, don't add.
				if ( 'core' == $priority ) {
					// If core box previously deleted, don't add
					if ( false === $wp_meta_boxes[$page][$a_context][$a_priority][$id] )
						return;
					// If box was added with default priority, give it core priority to maintain sort order
					if ( 'default' == $a_priority ) {
						$wp_meta_boxes[$page][$a_context]['core'][$id] = $wp_meta_boxes[$page][$a_context]['default'][$id];
						unset($wp_meta_boxes[$page][$a_context]['default'][$id]);
					}
					return;
				}
				// If no priority given and id already present, use existing priority
				if ( empty($priority) ) {
					$priority = $a_priority;
				// else if we're adding to the sorted priority, we don't know the title or callback. Grab them from the previously added context/priority.
				} elseif ( 'sorted' == $priority ) {
					$title = $wp_meta_boxes[$page][$a_context][$a_priority][$id]['title'];
					$callback = $wp_meta_boxes[$page][$a_context][$a_priority][$id]['callback'];
					$callback_args = $wp_meta_boxes[$page][$a_context][$a_priority][$id]['args'];
				}
				// An id can be in only one priority and one context
				if ( $priority != $a_priority || $context != $a_context )
					unset($wp_meta_boxes[$page][$a_context][$a_priority][$id]);
			}
		}

		if ( empty($priority) )
			$priority = 'low';

		if ( !isset($wp_meta_boxes[$page][$context][$priority]) )
			$wp_meta_boxes[$page][$context][$priority] = array();

		$wp_meta_boxes[$page][$context][$priority][$id] = array('id' => $id, 'title' => $title, 'callback' => $callback, 'args' => $callback_args);
	}
}

function epl_fs_postbox_classes( $id, $page ) {
	if ( isset( $_GET['edit'] ) && $_GET['edit'] == $id ) {
		$classes = array( '' );
	} elseif ( $closed = get_user_option('closedpostboxes_'.$page ) ) {
		if ( !is_array( $closed ) ) {
			$classes = array( '' );
		} else {
			$classes = in_array( $id, $closed ) ? array( 'closed' ) : array( '' );
		}
	} else {
		$classes = array( '' );
	}

	/**
	 * Filter the postbox classes for a specific screen and screen ID combo.
	 * @param array $classes An array of postbox classes.
	 */
	 
	$classes = apply_filters( "postbox_classes_{$page}_{$id}", $classes );
	return implode( ' ', $classes );
}

/**
 * Default post information to use when populating the "Write Post" form.
*/
function epl_fs_default_post( $post_type = 'post', $create_in_db = false ) {
	global $wpdb;

	$post_title = '';
	if ( !empty( $_REQUEST['post_title'] ) )
		$post_title = esc_html( wp_unslash( $_REQUEST['post_title'] ));

	$post_content = '';
	if ( !empty( $_REQUEST['content'] ) )
		$post_content = esc_html( wp_unslash( $_REQUEST['content'] ));

	$post_excerpt = '';
	if ( !empty( $_REQUEST['excerpt'] ) )
		$post_excerpt = esc_html( wp_unslash( $_REQUEST['excerpt'] ));

	if ( $create_in_db ) {
		$post_id = wp_insert_post( array( 'post_title' => __( 'Auto Draft' ), 'post_type' => $post_type, 'post_status' => 'auto-draft' ) );
		$post = get_post( $post_id );
		if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) && get_option( 'default_post_format' ) )
			set_post_format( $post, get_option( 'default_post_format' ) );
	} else {
		$post = new stdClass;
		$post->ID = 0;
		$post->post_author = '';
		$post->post_date = '';
		$post->post_date_gmt = '';
		$post->post_password = '';
		$post->post_type = $post_type;
		$post->post_status = 'draft';
		$post->to_ping = '';
		$post->pinged = '';
		$post->comment_status = get_option( 'default_comment_status' );
		$post->ping_status = get_option( 'default_ping_status' );
		$post->post_pingback = get_option( 'default_pingback_flag' );
		$post->post_category = get_option( 'default_category' );
		$post->page_template = 'default';
		$post->post_parent = 0;
		$post->menu_order = 0;
		$post = new WP_Post( $post );
	}

	/**
	 * Filter the default post content initially used in the "Write Post" form.
	 * @param string  $post_content Default post content.
	 * @param WP_Post $post         Post object.
	 */
	$post->post_content = apply_filters( 'default_content', $post_content, $post );

	/**
	 * Filter the default post title initially used in the "Write Post" form.
	 * @param string  $post_title Default post title.
	 * @param WP_Post $post       Post object.
	 */
	$post->post_title = apply_filters( 'default_title', $post_title, $post );

	/**
	 * Filter the default post excerpt initially used in the "Write Post" form.
	 * @param string  $post_excerpt Default post excerpt.
	 * @param WP_Post $post         Post object.
	 */
	$post->post_excerpt = apply_filters( 'default_excerpt', $post_excerpt, $post );
	$post->post_name = '';

	return $post;
}

/*
** Renders the title box and content box for the post
*/
function epl_fs_post_default_fields($post) {
	if ( post_type_supports($post->post_type, 'title') ) { ?>

		<div id="epl-fs-post-title" class="epl-fs-padding-h">
			<label class="screen-reader-text" id="title-prompt-text" for="title">
				<?php echo apply_filters( 'enter_title_here', __( 'Enter title here' ), $post ); ?>
			</label>
			<input type="text" name="post_title" size="30" value="<?php echo esc_attr( htmlspecialchars( $post->post_title ) ); ?>" id="title" autocomplete="off" />
		</div>

		<?php
	}
	
	if ( post_type_supports($post->post_type, 'editor') ) {
		?>
		<div id="epl-fs-post-desc" class="postarea edit-form-section epl-fs-padding-h">
		<?php wp_editor( $post->post_content, 'post_content', array(
			'editor_height' => 250,
			'tinymce' => array(
				'resize' => false,
				'add_unload_trigger' => false,
			),
		) ); ?>
		</div>
		<?php 
	}
}

/*
** Handles edit form data
*/

function epl_fs_save_post () {
	if(isset($_POST['action']) && $_POST['action'] == 'epl_fs_editpost') {
	
		if(wp_insert_post($_POST)) {
			wp_redirect($_SERVER['HTTP_REFERER']);
			die;
		}
		if ( true === wp_verify_nonce( $_POST['_wpnonce'], 'update-post_' . $_POST['ID'] ) ) {
			
		} else {
		}
	
	}
}
add_action('init','epl_fs_save_post');


