<?php
/**
 * Template functions used on front end
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/Template
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Load and enqueue scripts and stylesheets
 *
 * @since 2.3
 */
function epl_sd_enqueue_scripts() {

	wp_enqueue_style(	'epl-sd-styles', 	EPL_SD_CSS_URL . 'style.css',	FALSE,	EPL_SD_VER );
}
add_action( 'wp_enqueue_scripts', 'epl_sd_enqueue_scripts' );

/**
 * Modify Archive Directory sort order to default to menu_order
 *
 * @since 1.0
 */
function epl_sd_archive_order( $query ) {
	if ( is_admin() || ! $query->is_main_query() )
		return;

	if ( is_post_type_archive( 'directory' ) ) {
		$query->set( 'orderby', 'menu_order post_parent' );
		$query->set( 'order', 'ASC' );
		return;
	}
}
add_action( 'pre_get_posts', 'epl_sd_archive_order', 1 );

/**
 * Directory Content Page / Author Page
 *
 * @since 1.0
 */
function epl_sd_single_content() {

	global $epl_settings, $epl_author;

	$author_style 			= isset($epl_settings['epl_sd_link_to']) ? $epl_settings['epl_sd_link_to'] : '';
	$author_single_style	= isset($epl_settings['epl_sd_staff_single_style']) ? $epl_settings['epl_sd_staff_single_style'] : '';
	$arg_list 				= get_defined_vars();
	if ( $author_single_style == 1 ) {
		epl_sd_get_template_part('content-directory-single-big-image.php',$arg_list);
	} else {
		epl_sd_get_template_part('content-directory-single.php',$arg_list);
	}
}

/**
 * Directory Content Page :  Recent Posts Cards
 *
 * @since 1.0
 */
function epl_sd_single_post_cards() {
	epl_sd_get_template_part('content-directory-post-card.php');
}

/**
 * Directory Content Page :  section titles
 *
 * @since 2.1
 */
function epl_sd_loop_template( $epl_loop_counter = '', $tpl='simple-card') {

    global $epl_settings,$epl_author,$epl_loop_counter;

	$author_image_type 	= $epl_settings['epl_sd_staff_image_type'];
	$author_image_size 	= $epl_settings['epl_sd_staff_image_size_loop'];
	$author_excerpt 	= $epl_settings['epl_sd_archive_excerpt'];
	$show_position 		= isset($epl_settings['epl_sd_archive_show_position']) ? $epl_settings['epl_sd_archive_show_position'] : true;
	$show_mobile 		= isset($epl_settings['epl_sd_archive_show_mobile']) ? $epl_settings['epl_sd_archive_show_mobile'] : true;
	$show_icons 		= isset($epl_settings['epl_sd_archive_show_icons']) ? $epl_settings['epl_sd_archive_show_icons'] : true;
	$show_vcard 		= isset($epl_settings['epl_sd_archive_show_vcard']) ? $epl_settings['epl_sd_archive_show_vcard'] : true;
	$author_style 		= $epl_settings['epl_sd_link_to'];
	$view_type 		= isset($epl_settings['epl_sd_archive_style']) ? $epl_settings['epl_sd_archive_style'] : '';
	$view_cols 		= isset($epl_settings['epl_sd_grid_archive_cols']) ? intval($epl_settings['epl_sd_grid_archive_cols']) : 1;
	$grid_class		= '';

	if($view_type == 'grid' && $view_cols > 0) {

		$grid_class = 'epl-container-grid-'.$view_cols;

		if($epl_loop_counter % $view_cols == 0 ) {
			$grid_class .= ' epl-container-grid-last epl-container-grid-'.$view_cols.'-last';
		}

		if($epl_loop_counter % ($view_cols + 1) == 0 || $epl_loop_counter == 1) {
			$grid_class .= ' epl-container-grid-first epl-container-grid-'.$view_cols.'-first';
		}

	}

	$arg_list = get_defined_vars();

	epl_sd_get_template_part('content-directory-box-'.$tpl.'.php',$arg_list);

	$epl_loop_counter++;
}

/**
 * Bio Author Box Version : Unset Register Default EPL Author Box and Replace with Staff Directory Advanced Author Box
 *
 * @since 1.0
 */
function epl_sd_advanced_author_box($epl_author = array() ) {
	global $epl_settings;

	if(empty($epl_author))
		global $epl_author;

	$permalink 	= apply_filters('epl_author_profile_link', get_author_posts_url($epl_author->author_id) , $epl_author);
	$author_title	= apply_filters('epl_author_profile_title',get_the_author_meta( 'display_name',$epl_author->author_id ) ,$epl_author );

	?>
	<div id="epl-box" class="epl-author-box-container epl-author-box-flat">

		<div class="epl-author-box-outer-wrapper epl-clearfix">
			<div class="epl-author-box epl-author-box-3-col epl-author-box-3-col-left epl-author-image">
				<a href="<?php echo $permalink ?>">
					<?php do_action('epl_author_thumbnail',$epl_author); ?>
				</a>
			</div>

			<div class="epl-author-box epl-author-box-3-col epl-author-box-3-col-middle epl-author-details">
					<div class="epl-author-box-contact-details epl-author-contact-details">

						<h5 class="epl-author-title">
							<a href="<?php echo $permalink ?>">
								<?php echo $author_title;  ?>
							</a>
						</h5>
						<div class="epl-author-position">
							<span class="label-position"></span>
							<span class="mobile"><?php echo $epl_author->get_author_position() ?></span>
						</div>

						<div class="epl-author-contact">
							<span class="label-mobile"></span>
							<span class="mobile"><?php echo $epl_author->get_author_mobile() ?></span>
						</div>
					</div>
					<div class="epl-clearfix"></div>
					<div class="epl-author-social-buttons">
						<?php
							$social_icons = apply_filters('epl_display_author_social_icons',array('email','facebook','twitter','google','linkedin','skype'));
							foreach($social_icons as $social_icon){
								echo call_user_func(array($epl_author,'get_'.$social_icon.'_html'));
							}
						?>
					</div>
			</div>
			<div class="epl-author-box epl-author-box-3-col epl-author-box-3-col-right epl-author-bio">
				<?php
					epl_author_tab_description($epl_author);
				?>
			</div>
		</div>

	</div> <?php
}

add_action( 'epl_sd_advanced_author_box' , 'epl_sd_advanced_author_box' , 10, 2 );

/**
 * Add additional tabs to author box
 *
 * @since 2.2
 */
function epl_sd_advanced_author_box_tabs($tabs) {
	global $epl_author;

	$tabs['recent_listings'] = __('Recent Listings','epl-staff-directory');
	$tabs['client_feedback'] = __('Client Feedback','epl-staff-directory');

	return $tabs;
}
add_filter('epl_author_tabs','epl_sd_advanced_author_box_tabs');

/**
 * Staff Directory Author Widget
 *
 * @since 1.0
 */
function epl_sd_author_widget( $d_image , $image , $d_icons , $d_bio, $d_vcard) {

	global $property,$epl_author,$epl_settings;

	$arg_list = get_defined_vars();

	epl_sd_get_template_part('widget-staff-directory-author.php',$arg_list);

	// Second Author
	if ( is_single() && !is_null($property) ) {
		$property_second_agent = $property->get_property_meta('property_second_agent');
		if ( '' != $property_second_agent ) {
			$second_author = get_user_by( 'login' , $property_second_agent );
			if($second_author !== false){
				$epl_author_secondary = new EPL_Author_meta($second_author->ID);
				$arg_list['epl_author'] = $epl_author_secondary;
				epl_sd_get_template_part('widget-staff-directory-author.php',$arg_list);
			}
			epl_reset_post_author();
		}
	}
}

/**
 * Link with Testimonial Manager
 *
 * @since 1.0
 */
function epl_sd_author_box_testimonial_tab($epl_author = array()  ) {
	if ( class_exists( 'EPL_Testimonial_Manager') ) {
		if(empty($epl_author)) {
			global $epl_author;
		}

		// Recent Testimonial Query
		$testq = new WP_Query( array (
			'post_type'		=> array( 'testimonial' ),
			'author'		=> $epl_author->author_id,
			'posts_per_page'	=> 1
		) );

		return $testq;
	} else {
		return;
	}
}

/**
 * Display recent listings in author box tab
 *
 * @since 2.0
 */
function epl_author_tab_recent_listings( $epl_author = array() ) {
 	global $property,$post,$epl_settings;

	if(empty($epl_author)) {
		global $epl_author;
	}

	$quantity = isset( $epl_settings['epl_sd_recent_listings_tab_count'] ) ? $epl_settings['epl_sd_recent_listings_tab_count'] : '5';
	$owned_listings = epl_sd_recent_listings($epl_author , $quantity );

	echo '<ul>';
	if( $owned_listings ) {
		global $post;
		foreach( $owned_listings as $post ) {
			setup_postdata($post);
			  epl_property_widget_list_option();
		}
		$epl_post_types = epl_get_core_post_types();

		if( !is_singular( $epl_post_types ) && !isset( $_GET['epl_br_action']) ) {
			$property = null;
		}
		wp_reset_postdata();
	}
	echo '</ul>';
}

/**
 * Testimonial Tab content
 *
 * @since 2.0
 */
function epl_author_tab_client_feedback( $epl_author = array() ) {

	if(empty($epl_author)) {
		global $epl_author;
	}

	$testq = epl_sd_author_box_testimonial_tab($epl_author );
	if(!is_null($testq)) {
		if ( $testq->have_posts() ) {
			$testq->the_post();

			if( function_exists('epl_the_excerpt') ) {
	        		epl_the_excerpt();
	    		} else {
				the_excerpt();
	    		}
		}
	}
}

/**
 * Staff member Image for Author Box and Posts
 *
 * @since 2.2
 */
function epl_sd_author_tab_image( $imagehtml, $epl_author = array() ) {
	global $epl_settings;
	if(empty($epl_author)) {
		global $epl_author;
	}
	if(isset($epl_settings['epl_sd_staff_image_type']) && $epl_settings['epl_sd_staff_image_type'] == 1) {
		$author_image_size 	= $epl_settings['epl_sd_staff_image_size_box'];
		$author_ID = get_the_author_meta('ID');
		$author_args = array(
			'post_type'	=> 'directory',
			'author'	=> $epl_author->author_id
		);
		$the_author_image_query = new WP_Query( $author_args );
		if ( $the_author_image_query->have_posts() ) {
			while ( $the_author_image_query->have_posts() ) {
				$the_author_image_query->the_post();
				if ( has_post_thumbnail() ) {
					$imagehtml = get_the_post_thumbnail( get_the_ID(), $author_image_size);
				}
			}
		}
		wp_reset_postdata();

	}
	return $imagehtml;
}
add_filter('epl_author_tab_image','epl_sd_author_tab_image',10,2);

/**
 * Staff member image for use in widgets
 *
 * @since 2.1
 */
function epl_sd_author_tab_image_widget($epl_author = array(),$image ) {

	global $epl_settings;

	if(empty($epl_author)) {
		global $epl_author;
	}

	$author_args = array(
		'post_type' => 'directory',
		'author' 	=> $epl_author->author_id
	);

	$the_author_image_query = new WP_Query( $author_args );

	if ( $the_author_image_query->have_posts() ) {
		while ( $the_author_image_query->have_posts() ) {
			$the_author_image_query->the_post();
			if ( has_post_thumbnail() ) {
				echo apply_filters('epl_author_widget_tab_image',get_the_post_thumbnail( get_the_ID(), $image),$epl_author,$image );
			}
		}
		wp_reset_postdata();
	} else {
		echo  apply_filters('epl_author_widget_tab_image',get_avatar( $epl_author->email , '150' ),$epl_author );
	}
}
add_action('epl_author_widget_thumbnail','epl_sd_author_tab_image_widget',10,2);

/**
 * Read More Link
 *
 * @since 2.0
 */
function epl_sd_excerpt_more_link( $more ) {
	global $epl_settings,$property,$post;
	if( is_post_type_archive( 'directory' ) ) {

		$read_more_label = ( isset( $epl_settings['epl_sd_staff_read_more'] ) && $epl_settings['epl_sd_staff_read_more'] != '' ) ? $epl_settings['epl_sd_staff_read_more'] : __('Read More &rarr;','epl-staff-directory');

		$more  =  '...<a href="'. get_permalink( $post->ID ) . '" class="epl-more-link">' . $read_more_label . '</a>';
	}
	return $more;
}
add_filter('excerpt_more', 'epl_sd_excerpt_more_link');

/**
 * Staff member link to directory or theme author template
 *
 * @since 2.0
 */
function epl_sd_author_profile_link( $link, $epl_author = array() ) {

	if(empty($epl_author)) {
		global $epl_author;
	}

	global $epl_settings;

	if( !empty($epl_settings) && isset($epl_settings['epl_sd_link_to']) &&  $epl_settings['epl_sd_link_to'] == 1) {

		$author_args = array(
			'post_type' 	=> 'directory',
			'author' 	=> $epl_author->author_id
		);

		$the_author_image_query = new WP_Query( $author_args );

		if ( $the_author_image_query->have_posts() ) {

			while ( $the_author_image_query->have_posts() ) {
				$the_author_image_query->the_post();
				$link = get_permalink();
			}
		}
		wp_reset_postdata();
	}
	return $link;
}
add_filter('epl_author_profile_link','epl_sd_author_profile_link',10,2);

/**
 * Staff member title
 *
 * @since 2.0
 */
function epl_sd_author_profile_title( $title, $epl_author = array() ) {

	global $epl_settings;
	if( empty ($epl_author) ) {
		global $epl_author;
	}

	if( !empty($epl_settings) && isset($epl_settings['epl_sd_link_to']) &&  $epl_settings['epl_sd_link_to'] == 1) {

		$author_args = array(
			'post_type' 	=> 'directory',
			'author' 	=> $epl_author->author_id
		);

		$the_author_image_query = new WP_Query( $author_args );

		if ( $the_author_image_query->have_posts() ) {
			while ( $the_author_image_query->have_posts() ) {
				$the_author_image_query->the_post();
				$title = get_the_title();
			}
		}
		wp_reset_postdata();
	}
	return $title;
}
add_filter('epl_author_profile_title','epl_sd_author_profile_title',10,2);

/**
 * Modify Author box
 *
 * @since 2.0
 */
function epl_modify_listings_author_box() {
	global $epl_settings;
	if( isset($epl_settings['epl_sd_staff_author_box_type']) && $epl_settings['epl_sd_staff_author_box_type'] == 1) {
		if( has_action( 'epl_single_author', 'epl_property_author_box' ) ) {

			remove_action( 'epl_single_author', 'epl_property_author_box' );

			add_action('epl_single_author','epl_sd_property_author_box');
		}
	}
}
add_action('init','epl_modify_listings_author_box');

/**
 * Staff member author box
 *
 * @since 2.2
 */
function epl_sd_property_author_box() {
	global $property,$epl_author;

	epl_sd_advanced_author_box( $epl_author );

	if( $property != NULL ) {
		$property_second_agent = $property->get_property_meta('property_second_agent');
		if ( '' != $property_second_agent ) {
			$second_author = get_user_by( 'login' , $property_second_agent );
			if($second_author !== false){
				$epl_author_secondary = new EPL_Author_meta($second_author->ID);
				epl_sd_advanced_author_box($epl_author_secondary);
			}
			epl_reset_post_author();
		}
	}
}

/**
 * Read more label
 *
 * @since 2.0
 */
function epl_author_read_more_label( $read_more ) {
	$read_more = epl_get_option('epl_sd_staff_read_more' , __('Read More','epl-staff-directory') );
	return $read_more;
}
add_filter('epl_author_read_more_label','epl_author_read_more_label');

/**
 * Attempts to load templates in order of priority
 *
 * @since 2.0
 */
function epl_sd_get_template_part( $template, $arguments = array() ) {

	$base_path	= apply_filters( 'epl_sd_templates_base_path' , EPL_SD_PATH_TEMPLATES_CONTENT );
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

/**
 * Recent listings on single staff member page
 *
 * @since 2.2
 */
function epl_sd_recent_listings( $epl_author = array() , $quantity = 5 , $show_sold_leased = 'no' , $leased_qty = 5 ) {
	global $wpdb, $epl_settings;
	if(empty($epl_author)) {
		global $epl_author;
	}
	$defined_vars 	= get_defined_vars();
	$user_info 	= get_userdata( $epl_author->author_id );
	$post_types 	= array_keys( epl_get_active_post_types() );
	$post_types	= "'".implode("', '",$post_types)."'";

	if( !isset($defined_vars['show_sold_leased']) )
		$show_sold_leased 	= isset($epl_settings['epl_sd_show_sold_leased']) ? $epl_settings['epl_sd_show_sold_leased'] : 'no';

	if( !isset($defined_vars['leased_qty']) )
		$leased_qty 		= isset($epl_settings['epl_sd_single_leased_listing_count']) ? $epl_settings['epl_sd_single_leased_listing_count'] : 5;

	$querystr 	= "
		SELECT $wpdb->posts.*
		FROM $wpdb->posts
		LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)";

		$querystr .= "INNER JOIN $wpdb->postmeta pm2 ON($wpdb->posts.ID = pm2.post_id AND pm2.meta_key = 'property_status')";

		$querystr .= "
		WHERE $wpdb->posts.post_status = 'publish'
		AND $wpdb->posts.post_type IN ($post_types)
		AND (
			$wpdb->posts.post_author =  $epl_author->author_id
			OR (
				$wpdb->postmeta.meta_key 	= 'property_second_agent'
				AND
				$wpdb->postmeta.meta_value 	= '$user_info->user_login'
			)
		)";

		$querystr .= " AND pm2.meta_key  = 'property_status'  AND pm2.meta_value NOT IN ('leased','sold') ";

		$querystr .= "
		GROUP BY $wpdb->posts.ID
		ORDER BY $wpdb->posts.post_date DESC
	 ";
		if($quantity != "-1") {
			$querystr .= "
						LIMIT 0,$quantity
			";
		}
	$current_results = $wpdb->get_results($querystr);

	// leased properties
	$leased_results = array();
	if( $show_sold_leased == 'yes' ) {

		$querystr 	= "
			SELECT $wpdb->posts.*
			FROM $wpdb->posts
			LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)";

			$querystr .= "INNER JOIN $wpdb->postmeta pm2 ON($wpdb->posts.ID = pm2.post_id AND pm2.meta_key = 'property_status')";

			$querystr .= "
			WHERE $wpdb->posts.post_status = 'publish'
			AND $wpdb->posts.post_type IN ($post_types)
			AND (
				$wpdb->posts.post_author =  $epl_author->author_id
				OR (
					$wpdb->postmeta.meta_key 	= 'property_second_agent'
					AND
					$wpdb->postmeta.meta_value 	= '$user_info->user_login'
				)
			)";

			$querystr .= " AND pm2.meta_key  = 'property_status' AND (pm2.meta_value  = 'leased' OR pm2.meta_value  = 'sold') ";

			$querystr .= "
			GROUP BY $wpdb->posts.ID
			ORDER BY $wpdb->posts.post_date DESC
		";

		if($quantity != "-1") {
			$querystr .= "
				LIMIT 0,$leased_qty
			";
		}

		$leased_results = $wpdb->get_results($querystr);
	}
	return array_merge($current_results,$leased_results);
}

/**
 * Recent Posts on Single Profile
 *
 * @since 2.0.4
 */
function epl_sd_single_recent_posts_callback() {

	global $epl_author;

	$display_posts 	= isset($epl_settings['epl_sd_single_recent_posts_display']) ? $epl_settings['epl_sd_single_recent_posts_display'] : 'yes';

	if ( $display_posts == 'no' )
		return;

	$count = isset($epl_settings['epl_sd_single_recent_posts_count']) ? intval($epl_settings['epl_sd_single_listing_count']) : 4;

	$post_args = array(
		'post_type'		=> 'post',
		'author'		=> $epl_author->author_id,
		'posts_per_page'	=> $count
	);
	$post_query = new WP_Query($post_args);

	if ($post_query->have_posts()) {  ?>
		<div class="epl-sd-posts directory-section epl-clearfix">
		<h4 class="epl-sd-section-title epl-tab-title"><?php apply_filters ( 'epl_sd_template_label_latest_updates' , _e( 'Latest Updates' , 'epl-staff-directory' ) );?></h4>
			<?php
			while ($post_query->have_posts()) {
				$post_query->the_post();
				echo epl_sd_single_post_cards();
			}
			wp_reset_postdata();
			?>
		</div>
	<?php
	}
}
add_action( 'epl_sd_single_recent_posts' , 'epl_sd_single_recent_posts_callback' );

/**
 * Recent Listings on Single Profile
 *
 * @since 2.0.4
 */
function epl_sd_single_staff_listings_callback() {
	global $epl_settings, $epl_author;

	$quantity 	= isset($epl_settings['epl_sd_single_listing_count']) ? $epl_settings['epl_sd_single_listing_count'] : '8';
	$owned_listings = epl_sd_recent_listings( $epl_author , $quantity  );
	$display 	= 'image';
	$image 		= 'thumbnail';
	$d_title 	= FALSE;
	$d_icons 	= FALSE;

	$listing_style 	= isset($epl_settings['epl_sd_listing_template']) ? $epl_settings['epl_sd_listing_template'] : 'blog'; // default is card

	if($owned_listings) {

		echo '<div class="epl-sd-listings directory-section epl-clearfix">';
		echo '<h4 class="epl-sd-section-title epl-tab-title">Listings</h4>';

		global $post;

		foreach($owned_listings as $post) {
			setup_postdata($post);
			epl_property_blog($listing_style);
		}

		wp_reset_postdata();
		echo '</div>';
	}
}
add_action( 'epl_sd_single_staff_listings' , 'epl_sd_single_staff_listings_callback' );

/**
 * Theme compatibility
 *
 * If theme compat mode is on add the template into post's content
 *
 * @since 2.1
 */
function epl_sd_theme_compat($content) {

	global $epl_settings,$post,$epl_loop_counter;

	if( !isset($epl_settings['epl_feeling_lucky']) || $epl_settings['epl_feeling_lucky'] != 'on') {
		return $content;
	}

	if ( is_singular('directory') ) {
			epl_sd_single_content();
	} elseif( is_post_type_archive('directory') ) {

			$epl_loop_counter = 1;
			if( is_sd_section_title() ) {

				epl_sd_loop_template( $epl_loop_counter, 'section-header' );

			} else {

				if ( has_post_thumbnail()  ) {
					epl_sd_loop_template( $epl_loop_counter, 'simple-card' );
				} else {
					epl_sd_loop_template( $epl_loop_counter, 'simple-grav' );
				}
			}
	} else {
		return $content;
	}
}
add_filter('the_content','epl_sd_theme_compat');

/**
 * Apply the theme compat options
 *
 * @since 2.1
 */
function epl_sd_apply_theme_compat_config() {

	global $epl_settings;

    // remove featured image on single pages in theme compat mode
    if( isset($epl_settings['epl_lucky_disable_single_thumb']) && $epl_settings['epl_lucky_disable_single_thumb'] == 'on') {

		if ( is_single('directory') ) {
			remove_all_actions( 'epl_property_featured_image' );
		}

	}

    // remove featured image on archive pages in theme compat mode
    if( isset($epl_settings['epl_lucky_disable_archive_thumb']) && $epl_settings['epl_lucky_disable_archive_thumb'] == 'on') {

    	if( is_post_type_archive('directory') ) {
			add_filter('post_thumbnail_html','epl_sd_remove_archive_thumbnail',20,5);
		}
	}

}
add_action('wp','epl_sd_apply_theme_compat_config',1);

/**
 * Thumbnail workaround with theme compatibility
 *
 * A workaround to avoid duplicate thumbnails for single listings being displayed on archive pages via theme & epl
 * attempts to null the post thumbnail image called from theme & display thumbnail image called from epl
 *
 * @since 2.2
 */
function epl_sd_remove_archive_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
	if( is_admin() ) {
		return $html;
	}

	if( strpos($html, 'author-thumbnail') === FALSE ) {

		// the post thumbnail is probably theme's default . remove it
		$html = '';
	}

	return $html;
}

/**
 * Section title check
 *
 * @since 2.2
 */
function is_sd_section_title() {
	global $post;
	if( get_post_meta( $post->ID, 'epl_sd_section', true ) == 'yes') {
		return true;
	} else {
		return false;
	}
}

/**
 * Posts per page on archive page
 *
 * @since 2.2
 */
function epl_sd_archive_posts_per_page( $query ) {
	global $epl_settings;
	$ppp = isset($epl_settings['epl_sd_archive_per_page']) ? intval($epl_settings['epl_sd_archive_per_page']) : 20;
	if($ppp != 0) {
		if( ! is_admin()
		    && $query->is_post_type_archive( 'directory' )
		    && $query->is_main_query() ){
		        $query->set( 'posts_per_page', $ppp );
		}
	}
}
add_action( 'pre_get_posts', 'epl_sd_archive_posts_per_page' );

/**
 * Register directory post type with EPL Core
 *
 * @since 2.3
 */
function epl_sd_add_to_epl_posts($posts) {
	$posts[] = 'directory';
	return $posts;
}
add_filter('epl_additional_post_types','epl_sd_add_to_epl_posts');

/**
 * Switch single template
 *
 * @since 2.3
 */
function epl_sd_switch_single_template() {
	epl_sd_single_content();
}

/**
 * Switch loop template for section header or post
 *
 * @since 2.3
 */
function epl_sd_switch_loop_template() {

	//Backward compat
	$epl_loop_counter = '';

	if( is_sd_section_title() ) {

		epl_sd_loop_template( $epl_loop_counter , 'section-header' );

	} else {
		if ( has_post_thumbnail()  ) {
			epl_sd_loop_template( $epl_loop_counter , 'simple-card' );
		} else {
			epl_sd_loop_template( $epl_loop_counter , 'simple-grav' );
		}
	}
}

/**
 * Declare counter
 *
 * Declare counter global so that it can be used elsewhere
 *
 * @since 2.3
 */
function epl_sd_add_dir_counter() {
    //  declare counter global so that it can be used elsewhere
    global $epl_loop_counter;
    $epl_loop_counter = 1;
}

/**
 * Use EPL core template
 *
 * @since 2.3
 */
function epl_sd_template_overrides() {

	global $post;

	if( is_singular('directory')) {
		add_action('epl_single_template', 'epl_sd_switch_single_template');
		// Backward Compatibility
		add_action('epl_sd_single_content', 'epl_sd_switch_single_template');
	}

	if( is_post_type_archive( 'directory' ) || has_shortcode( $post->post_content, 'epl_directory' ) ) {
		remove_action( 'epl_property_loop_start' , 'epl_switch_views_sorting' , 20 );
		add_action( 'epl_property_loop_start' , 'epl_sd_add_dir_counter' );
		add_action( 'epl_loop_template', 'epl_sd_switch_loop_template');
	}
}
add_action('wp','epl_sd_template_overrides');

/**
 * Add Agent Search Items to EPL - Listing Search Widget Front End
 *
 * @since 2.3
 */
function epl_sd_author_search_widget_fields_frontend_callback($array) {
	$authors = get_users(
		array(
			'posts_per_page'    =>  -1
		)
	);
	$opts_array = array();
	foreach($authors as $author) {
		$opts_array[$author->data->user_nicename] = ucwords(str_replace('-',' ',$author->data->user_nicename));
	}
	$array[] = array(
		'key' 			=> 'search_agent',
		'meta_key' 		=> 'property_agent',
		'label' 		=> __('Agent', 'epl-sd'),
		'type' 			=> 'select',
		'option_filter'		=> 'agent',
		'options' 		=> $opts_array,
		'class'			=> 'epl-search-row-full',
		'order'         	=> 45
	);
	return $array;
}
add_filter('epl_search_widget_fields_frontend', 'epl_sd_author_search_widget_fields_frontend_callback');
