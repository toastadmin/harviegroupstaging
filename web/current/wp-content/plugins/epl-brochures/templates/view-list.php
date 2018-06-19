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
		<link rel="stylesheet" href="<?php echo set_url_scheme( EPL_BR_CSS_STRUCTURE . 'style-structure.css') . EPL_BR_CSS_VERSION_CORE; ?>" type="text/css" media="all" />
		<link rel="stylesheet" href="<?php echo set_url_scheme( EPL_BR_CSS_STRUCTURE . 'style.css') . EPL_BR_CSS_VERSION_CORE; ?>" type="text/css" media="all" />


		<link rel="stylesheet" href="<?php echo set_url_scheme( EPL_BR_CSS . 'style-brochures-structure.css') . EPL_BR_CSS_VERSION; ?>" type="text/css" media="all" />
		<link rel="stylesheet" href="<?php echo epl_br_get_custom_css_url('style-brochures-custom.css') . EPL_BR_CSS_VERSION; ?>" type="text/css" media="all" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
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
							$atts   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
							echo epl_brochures_listing( $atts );
						?>
					</div>
				</section>
			</div>
		</div>
	</body>
</html>

