<!DOCTYPE html>
<html lang="en-US">
	<head>
		<!-- Metadata -->
		<meta charset="UTF-8">
		<meta name="robots" content="noindex, nofollow">
		<!-- Title -->
		<title><?php _e('Brochures', 'epl-brochures'); ?></title>
		<!-- CSS -->
		<?php epl_br_theme_css(); ?>
		<link rel="stylesheet" href="<?php echo set_url_scheme( EPL_BR_CSS . 'style-brochures-structure.css') . EPL_BR_CSS_VERSION; ?>" type="text/css" media="all" />
		<link rel="stylesheet" href="<?php echo epl_br_get_custom_css_url('style-brochures-custom.css') . EPL_BR_CSS_VERSION; ?>" type="text/css" media="all" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp<?php epl_br_google_maps_key_callback(); ?>'></script>
		<script type='text/javascript' src='<?php echo set_url_scheme( EPL_BR_PLUGIN_URL . 'assets/js/map.js'); ?>'></script>
	</head>
	<body>
		<div id="main main-brochures">
			<header class="epl-brochures-header">
				<div class="epl-brochure-banner">
					<img alt="<?php get_bloginfo('name') ?>" src="<?php echo $banner; ?>" />
				</div>
			</header>
			 <div id="primary" class="epl-brochure-content site-content content-area epl-single-default">
				<section class="content">
					<div id="content" class="pad" role="main">
						<?php
						query_posts('p='.$id.'&post_type=any');
						if ( have_posts() ) : ?>
							<div class="loop">
								<div class="loop-content">
									<?php
										while ( have_posts() ) : // The Loop
											the_post();
											epl_br_brochure_style_callback();
										endwhile; // end of one post
									?>
								</div>
							</div>
						<?php endif; wp_reset_postdata(); ?>
					</div>
				</section>
			</div>
		</div>
	</body>
</html>

