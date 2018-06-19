<?php
/*
Template Name: Archive Projects Page
*/

get_header();

?>

<div id="et-main-area">
	<div id="main-content">
		<div class="entry-content et-archive-project">

			<div class="et_pb_row project-custom-row">
				<?php if ( have_posts() ) : ?>

				<?php
					// Start the loop.
					while ( have_posts() ) : the_post(); ?>

						<?php
						/*
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						?>

				<div class="et_pb_column project-custom-column-1-in-3">
					<div class="et_pb_blurb et_pb_module et_pb_bg_layout_light et_pb_text_align_left  et_pb_blurb_0 et_pb_blurb_position_top">
						<div class="et_pb_blurb_content">
							<div class="et_pb_main_blurb_image"><a href="<?php the_permalink();?>"><img src="<?php echo get_the_post_thumbnail_url();?>" alt="" class="et-waypoint et_pb_animation_top et-animated"></a></div>
								<div class="et_pb_blurb_container">
									<h4><a href="<?php the_permalink();?>"><?php the_field('project_address');?></a></h4>
									<p><?php the_field('project_suburb');?>, <?php the_field('project_state');?> <?php the_field('project_postcode');?> </p>
								</div>
						</div> <!-- .et_pb_blurb_content -->
					</div>
				</div>

				
				<?php

					// End the loop.
					endwhile;

					// Previous/next page navigation.
					the_posts_pagination( array(
						'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
						'next_text'          => __( 'Next page', 'twentyfifteen' ),
						'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
					) );

				// If no content, include the "No posts found" template.
				else :
					get_template_part( 'content', 'none' );

				endif;
				?>

			</div>

		</div>
	</div>
</div>


<?php get_footer(); ?>