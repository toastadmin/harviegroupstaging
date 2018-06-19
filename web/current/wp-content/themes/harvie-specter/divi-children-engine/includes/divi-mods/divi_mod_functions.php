<?php
/**
 * Functions - Child theme custom functions which modify non-pluggable Divi functions
 *
 * Created by Divi Children plugin, http://divi4u.com/divi-children-plugin/ 
 */


function Divichild_theme_setup() {

	// Condition Added for Divi 2.4:
	if ( shortcode_exists( 'et_pb_blog' ) ) {
		remove_shortcode( 'et_pb_blog' );
		add_shortcode( 'et_pb_blog', 'et_pb_blog_Divichild' );
	}

	// Condition Added for Divi 2.4:
	if ( shortcode_exists( 'et_pb_cta' ) ) {	
		remove_shortcode( 'et_pb_cta' );
		add_shortcode( 'et_pb_cta', 'et_pb_cta_Divichild' );
	}

}
add_action( 'after_setup_theme', 'Divichild_theme_setup' );


function et_pb_blog_Divichild( $atts ) {
	extract( shortcode_atts( array(
			'module_id' => '',
			'module_class' => '',
			'fullwidth' => 'on',
			'posts_number' => 10,
			'include_categories' => '',
			'meta_date' => 'M j, Y',
			'show_thumbnail' => 'on',
			'show_content' => 'off',
			'show_author' => 'on',
			'show_date' => 'on',
			'show_categories' => 'on',
			'show_pagination' => 'on',
			'offset_number' => 0,
			'background_layout' => 'light',
			'show_more' => 'off',
		), $atts
	) );
	global $paged;
	$container_is_closed = false;
	if ( 'on' !== $fullwidth ){
		wp_enqueue_script( 'jquery-masonry-3' );
	}
	if ( $module_id != 'newest_post_feed' ) {
		$args = array( 'posts_per_page' => (int) $posts_number );
		} else {
			$args = array( 'posts_per_page' => 1 );
	}
	$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );
	if ( is_front_page() ) {
		$paged = $et_paged;
	}
	if ( '' !== $include_categories )
		$args['cat'] = $include_categories;
	if ( ! is_search() ) {
		$args['paged'] = $et_paged;
	}
	if ( '' !== $offset_number && ! empty( $offset_number ) ) {
		$args['offset'] = (int) $offset_number;
	}
	ob_start();
	query_posts( $args );
	$newest_post = wp_get_recent_posts('1');
	$newest_post_ID = $newest_post['0']['ID'];	
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			if ( ( $module_id != 'no_newest_post_feed' ) OR ( ( $module_id == 'no_newest_post_feed' ) AND ( $newest_post_ID != get_the_ID() ) ) ) {
				$post_format = get_post_format();
				$thumb = '';
				$width = 'on' === $fullwidth ? 1080 : 400;
				$width = (int) apply_filters( 'et_pb_blog_image_width', $width );
				$height = 'on' === $fullwidth ? 675 : 250;
				$height = (int) apply_filters( 'et_pb_blog_image_height', $height );
				$classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';
				$titletext = get_the_title();
				$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
				$thumb = $thumbnail["thumb"];
				$no_thumb_class = '' === $thumb || 'off' === $show_thumbnail ? ' et_pb_no_thumb' : '';
				if ( in_array( $post_format, array( 'video', 'gallery' ) ) ) {
					$no_thumb_class = '';
				} ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' . $no_thumb_class ); ?>>
			<?php
				et_divi_post_format_content();
				if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
					if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
						printf(
							'<div class="et_main_video_container">
								%1$s
							</div>',
							$first_video
						);
					elseif ( 'gallery' === $post_format ) :
						et_gallery_images();
					elseif ( '' !== $thumb && 'on' === $show_thumbnail ) :
						if ( 'on' !== $fullwidth ) echo '<div class="et_pb_image_container">'; ?>
							<a href="<?php the_permalink(); ?>">
								<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
							</a>
					<?php
						if ( 'on' !== $fullwidth ) echo '</div> <!-- .et_pb_image_container -->';
					endif;
				} ?>
			<?php if ( 'off' === $fullwidth || ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ) ) ) { ?>
				<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) { ?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php } ?>
				<?php
					if ( 'on' === $show_author || 'on' === $show_date || 'on' === $show_categories ) {
						printf( '<p class="post-meta">%1$s %2$s %3$s %4$s %5$s</p>',
							(
								'on' === $show_author
									? sprintf( __( 'by %s', 'Divi' ), et_get_the_author_posts_link() )
									: ''
							),
							(
								( 'on' === $show_author && 'on' === $show_date )
									? ' | '
									: ''
							),
							(
								'on' === $show_date
									? sprintf( __( '%s', 'Divi' ), get_the_date( $meta_date ) )
									: ''
							),
							(
								(( 'on' === $show_author || 'on' === $show_date ) && 'on' === $show_categories)
									? ' | '
									: ''
							),
							(
								'on' === $show_categories
									? get_the_category_list(', ')
									: ''
							)
						);
					}
					if ( 'on' === $show_content ) {
						global $more;
						$more = null;
						the_content( __( 'read more...', 'Divi' ) );
					} else {
						if ( has_excerpt() ) {
							the_excerpt();
						} else {
							truncate_post( 270 );
						}
						$more = 'on' == $show_more ? sprintf( ' <a href="%1$s" class="more-link" >%2$s</a>' , esc_url( get_permalink() ), __( 'read more', 'Divi' ) )  : '';
						echo $more;
					} ?>
			<?php } // 'off' === $fullwidth || ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ?>
			</article> <!-- .et_pb_post -->
			<?php
			}
		} // endwhile
		if ( 'on' === $show_pagination && ! is_search() ) {
			echo '</div> <!-- .et_pb_posts -->';
			$container_is_closed = true;
			if ( function_exists( 'wp_pagenavi' ) )
				wp_pagenavi();
			else
				get_template_part( 'includes/navigation', 'index' );
		}
		wp_reset_query();
	} else {
		get_template_part( 'includes/no-results', 'index' );
	}
	$posts = ob_get_contents();	
	ob_end_clean();
	$class = " et_pb_bg_layout_{$background_layout}";
	$output = sprintf(
		'<div%5$s class="%1$s%3$s%6$s">
			%2$s
		%4$s',
		( 'on' === $fullwidth ? 'et_pb_posts' : 'et_pb_blog_grid clearfix' ),
		$posts,
		esc_attr( $class ),
		( ! $container_is_closed ? '</div> <!-- .et_pb_posts -->' : '' ),
		( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
		( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
	);
	if ( 'on' !== $fullwidth )
		$output = sprintf( '<div class="et_pb_blog_grid_wrapper">%1$s</div>', $output );
	return $output;
}


function et_pb_cta_Divichild( $atts, $content = null ) {
	extract( shortcode_atts( array(
			'module_id' => '',
			'module_class' => '',
			'title' => '',
			'button_url' => '',
			'button_text' => '',
			'background_color' => et_get_option( 'accent_color', '#7EBEC5' ),
			'background_layout' => 'dark',
			'text_orientation' => 'center',
			'use_background_color' => 'on',
		), $atts
	) );

	$class = " et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

	if ( $module_id === 'cta_outbound_link' ) {
			$link = '<a class="et_pb_promo_button" href="%1$s" target="_blank" >%2$s</a>';
		} else {
			$link = '<a class="et_pb_promo_button" href="%1$s">%2$s</a>';
	}
	
	$output = sprintf(
		'<div%6$s class="et_pb_promo%4$s%7$s%8$s"%5$s>
			<div class="et_pb_promo_description">
				%1$s
				%2$s
			</div>
			%3$s
		</div>',
		( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
		do_shortcode( et_pb_fix_shortcodes( $content ) ),
		(
			'' !== $button_url && '' !== $button_text
				// ? sprintf( '<a class="et_pb_promo_button" href="%1$s">%2$s</a>',
				? sprintf( $link,
					esc_url( $button_url ),
					esc_html( $button_text )
				)
				: ''
		),
		esc_attr( $class ),
		( 'on' === $use_background_color
			? sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) )
			: ''
		),
		( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
		( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
		( 'on' !== $use_background_color ? ' et_pb_no_bg' : '' )
	);

	return $output;
}




/**
 * Divi Children modifications to the new Divi PageBuilder
 */
function dce_modify_et_builder() {

	/**
	 * Override shortcode_callback of Divi Blog module to include newest_post_feed and no_newest_post_feed Magic Codes for Divi Children compatibility
	 */
	if ( class_exists( 'ET_Builder_Module_Blog' ) ) {
		class Divi_Children_Module_Blog extends ET_Builder_Module_Blog {
			function shortcode_callback( $atts, $content = null, $function_name ) {
				$module_id          = $this->shortcode_atts['module_id'];
				$module_class       = $this->shortcode_atts['module_class'];
				$fullwidth          = $this->shortcode_atts['fullwidth'];
				$posts_number       = $this->shortcode_atts['posts_number'];
				$include_categories = $this->shortcode_atts['include_categories'];
				$meta_date          = $this->shortcode_atts['meta_date'];
				$show_thumbnail     = $this->shortcode_atts['show_thumbnail'];
				$show_content       = $this->shortcode_atts['show_content'];
				$show_author        = $this->shortcode_atts['show_author'];
				$show_date          = $this->shortcode_atts['show_date'];
				$show_categories    = $this->shortcode_atts['show_categories'];
				$show_pagination    = $this->shortcode_atts['show_pagination'];
				$background_layout  = $this->shortcode_atts['background_layout'];
				$show_more          = $this->shortcode_atts['show_more'];
				$offset_number      = $this->shortcode_atts['offset_number'];
				$masonry_tile_background_color = $this->shortcode_atts['masonry_tile_background_color'];
				$use_dropshadow     = $this->shortcode_atts['use_dropshadow'];

				// Added for 'no_newest_post_feed' Divi Children Magic Code compatibility
				if ( $module_id === 'no_newest_post_feed' ) {
					$offset_number = 1;
				}
				
				global $paged;

				$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

				$container_is_closed = false;

				if ( '' !== $masonry_tile_background_color ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%%.et_pb_blog_grid .et_pb_post',
						'declaration' => sprintf(
							'background-color: %1$s;',
							esc_html( $masonry_tile_background_color )
						),
					) );
				}

				if ( 'on' !== $fullwidth ){
					if ( 'on' === $use_dropshadow ) {
						$module_class .= ' et_pb_blog_grid_dropshadow';
					}

					wp_enqueue_script( 'salvattore' );

					$background_layout = 'light';
				}

				// $args = array( 'posts_per_page' => (int) $posts_number );
				
				// Added for 'newest_post_feed' Divi Children Magic Code compatibility
				if ( $module_id != 'newest_post_feed' ) {
					$args = array( 'posts_per_page' => (int) $posts_number );
					} else {
						$args = array( 'posts_per_page' => 1 );
				}

				$et_paged = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );

				if ( is_front_page() ) {
					$paged = $et_paged;
				}

				if ( '' !== $include_categories )
					$args['cat'] = $include_categories;

				if ( ! is_search() ) {
					$args['paged'] = $et_paged;
				}

				if ( '' !== $offset_number && ! empty( $offset_number ) ) {
					/**
					 * Offset + pagination don't play well. Manual offset calculation required
					 * @see: https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
					 */
					if ( $paged > 1 ) {
						$args['offset'] = ( ( $et_paged - 1 ) * intval( $posts_number ) ) + intval( $offset_number );
					} else {
						$args['offset'] = intval( $offset_number );
					}
				}

				ob_start();

				query_posts( $args );

				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();

						$post_format = get_post_format();

						$thumb = '';

						$width = 'on' === $fullwidth ? 1080 : 400;
						$width = (int) apply_filters( 'et_pb_blog_image_width', $width );

						$height = 'on' === $fullwidth ? 675 : 250;
						$height = (int) apply_filters( 'et_pb_blog_image_height', $height );
						$classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';
						$titletext = get_the_title();
						$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
						$thumb = $thumbnail["thumb"];

						$no_thumb_class = '' === $thumb || 'off' === $show_thumbnail ? ' et_pb_no_thumb' : '';

						if ( in_array( $post_format, array( 'video', 'gallery' ) ) ) {
							$no_thumb_class = '';
						} ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' . $no_thumb_class ); ?>>

					<?php
						et_divi_post_format_content();

						if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
							if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
								printf(
									'<div class="et_main_video_container">
										%1$s
									</div>',
									$first_video
								);
							elseif ( 'gallery' === $post_format ) :
								et_gallery_images();
							elseif ( '' !== $thumb && 'on' === $show_thumbnail ) :
								if ( 'on' !== $fullwidth ) echo '<div class="et_pb_image_container">'; ?>
									<a href="<?php the_permalink(); ?>">
										<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
									</a>
							<?php
								if ( 'on' !== $fullwidth ) echo '</div> <!-- .et_pb_image_container -->';
							endif;
						} ?>

					<?php if ( 'off' === $fullwidth || ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ) ) ) { ?>
						<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) { ?>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php } ?>

						<?php
							if ( 'on' === $show_author || 'on' === $show_date || 'on' === $show_categories ) {
								printf( '<p class="post-meta">%1$s %2$s %3$s %4$s %5$s</p>',
									(
										'on' === $show_author
											? sprintf( __( 'by %s', 'et_builder' ), et_pb_get_the_author_posts_link() )
											: ''
									),
									(
										( 'on' === $show_author && 'on' === $show_date )
											? ' | '
											: ''
									),
									(
										'on' === $show_date
											? sprintf( __( '%s', 'et_builder' ), get_the_date( $meta_date ) )
											: ''
									),
									(
										(( 'on' === $show_author || 'on' === $show_date ) && 'on' === $show_categories)
											? ' | '
											: ''
									),
									(
										'on' === $show_categories
											? get_the_category_list(', ')
											: ''
									)
								);
							}

							if ( 'on' === $show_content ) {
								global $more;
								$more = null;

								the_content( __( 'read more...', 'et_builder' ) );
							} else {
								if ( has_excerpt() ) {
									the_excerpt();
								} else {
									truncate_post( 270 );
								}
								$more = 'on' == $show_more ? sprintf( ' <a href="%1$s" class="more-link" >%2$s</a>' , esc_url( get_permalink() ), __( 'read more', 'et_builder' ) )  : '';
								echo $more;
							} ?>
					<?php } // 'off' === $fullwidth || ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ?>

					</article> <!-- .et_pb_post -->
			<?php
					} // endwhile

					if ( 'on' === $show_pagination && ! is_search() ) {
						echo '</div> <!-- .et_pb_posts -->';

						$container_is_closed = true;

						if ( function_exists( 'wp_pagenavi' ) )
							wp_pagenavi();
						else
							get_template_part( 'includes/navigation', 'index' );
					}

					wp_reset_query();
				} else {
					get_template_part( 'includes/no-results', 'index' );
				}

				$posts = ob_get_contents();

				ob_end_clean();

				$class = " et_pb_module et_pb_bg_layout_{$background_layout}";

				$output = sprintf(
					'<div%5$s class="%1$s%3$s%6$s"%7$s>
						%2$s
					%4$s',
					( 'on' === $fullwidth ? 'et_pb_posts' : 'et_pb_blog_grid clearfix' ),
					$posts,
					esc_attr( $class ),
					( ! $container_is_closed ? '</div> <!-- .et_pb_posts -->' : '' ),
					( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
					( 'on' !== $fullwidth ? ' data-columns' : '' )
				);

				if ( 'on' !== $fullwidth )
					$output = sprintf( '<div class="et_pb_blog_grid_wrapper">%1$s</div>', $output );

				return $output;
			}
		}
		new Divi_Children_Module_Blog;
	}

	/**
	 * Override shortcode_callback of Divi CTA module to include cta_outbound_link Magic Code for Divi Children compatibility
	 */
	if ( class_exists( 'ET_Builder_Module_CTA' ) ) {
		class Divi_Children_Module_CTA extends ET_Builder_Module_CTA {
			function shortcode_callback( $atts, $content = null, $function_name ) {
				$module_id            = $this->shortcode_atts['module_id'];
				$module_class         = $this->shortcode_atts['module_class'];
				$title                = $this->shortcode_atts['title'];
				$button_url           = $this->shortcode_atts['button_url'];
				$button_text          = $this->shortcode_atts['button_text'];
				$background_color     = $this->shortcode_atts['background_color'];
				$background_layout    = $this->shortcode_atts['background_layout'];
				$text_orientation     = $this->shortcode_atts['text_orientation'];
				$use_background_color = $this->shortcode_atts['use_background_color'];
				$url_new_window       = $this->shortcode_atts['url_new_window'];
				$max_width            = $this->shortcode_atts['max_width'];
				$custom_icon          = $this->shortcode_atts['button_icon'];
				$button_custom        = $this->shortcode_atts['custom_button'];

				// Added for 'cta_outbound_link' Divi Children Magic Code compatibility
				if ( $module_id === 'cta_outbound_link' ) {
					$url_new_window = 'on';
				}

				$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

				if ( is_rtl() && 'left' === $text_orientation ) {
					$text_orientation = 'right';
				}

				if ( '' !== $max_width ) {
					ET_Builder_Element::set_style( $function_name, array(
						'selector'    => '%%order_class%% .et_pb_promo_description',
						'declaration' => sprintf(
							'max-width: %1$s;%2$s',
							esc_html( et_builder_process_range_value( $max_width ) ),
							( 'center' === $text_orientation ? ' margin: 0 auto;' : '' )
						),
					) );
				}

				$class = " et_pb_module et_pb_bg_layout_{$background_layout} et_pb_text_align_{$text_orientation}";

				$output = sprintf(
					'<div%6$s class="et_pb_promo%4$s%7$s%8$s"%5$s>
						<div class="et_pb_promo_description">
							%1$s
							%2$s
						</div>
						%3$s
					</div>',
					( '' !== $title ? '<h2>' . esc_html( $title ) . '</h2>' : '' ),
					$this->shortcode_content,
					(
						'' !== $button_url && '' !== $button_text
							? sprintf( '<a class="et_pb_promo_button et_pb_button%5$s" href="%1$s"%3$s%4$s>%2$s</a>',
								esc_url( $button_url ),
								esc_html( $button_text ),
								( 'on' === $url_new_window ? ' target="_blank"' : '' ),
								'' !== $custom_icon && 'on' === $button_custom ? sprintf(
									' data-icon="%1$s"',
									esc_attr( et_pb_process_font_icon( $custom_icon ) )
								) : '',
								'' !== $custom_icon && 'on' === $button_custom ? ' et_pb_custom_button_icon' : ''
							)
							: ''
					),
					esc_attr( $class ),
					( 'on' === $use_background_color
						? sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) )
						: ''
					),
					( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
					( 'on' !== $use_background_color ? ' et_pb_no_bg' : '' )
				);

				return $output;
			}
		}
		new Divi_Children_Module_CTA;
	}
	
}
add_action( 'init', 'dce_modify_et_builder', 100 );

?>