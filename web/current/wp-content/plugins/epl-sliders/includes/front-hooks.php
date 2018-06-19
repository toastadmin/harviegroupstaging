<?php
/**
 * Frontend Hooks
 *
 * @package     EPL-SLIDERS
 * @subpackage  Classes/SliderConfig
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EPL_SLIDER_CONFIG {

	/**
	 * constructor.
	 *
	 * @since 1.0.0
	 */
	function EPL_SLIDER_CONFIG () {

		$this->hooks();
	}

	/**
	 * ensure only one instance of this class is running
	 *
	 * @since 1.0.0
	 */
	public static function get_instance() {

		return new EPL_SLIDER_CONFIG();

	}

	/**
	 * Slder defaults
	 *
	 * @since 1.1
	 */
	public function slider_defaults() {

		return array(
			// $ThumbnailNavigatorOptions.$ChanceToShow
			array(
				'name'		=>	'epl_slider_use_thumbnails',
				'default'	=>	'2'
			),
			//  $DisplayPieces
			array(
				'name'		=>	'epl_display_pieces',
				'default'	=>	'6'
			),
			// $SlideshowOptions._SlideshowTransitions
			array(
				'name'		=>	'epl_slider_transition',
				'default'	=>	'fade_in_l'
			),
			//  $DragOrientation
			array(
				'name'		=>	'epl_allow_swipe',
				'default'	=>	'3'
			),
			// $AutoPlay
			array(
				'name'		=>	'epl_slider_slideshow',
				'default'	=>	'false',
			),
			// $AutoPlayInterval
			array(
				'name'		=>	'epl_slider_slideshowSpeed',
				'default'	=>	'5000'
			),
			// $SlideDuration
			array(
				'name'		=>	'epl_slider_animationSpeed',
				'default'	=>	'1200'
			),
			//  $ThumbnailNavigatorOptions.$SpacingX
			array(
				'name'		=>	'epl_slider_spacingx',
				'default'	=>	'14'
			),
			//  $ThumbnailNavigatorOptions.$SpacingY
			array(
				'name'		=>	'epl_slider_spacingy',
				'default'	=>	'12'
			),
			// $ThumbnailNavigatorOptions.$ChanceToShow
			array(
				'name'		=>	'epl_slider_controlNav',
				'default'	=>	'1'
			),
			// $ArrowNavigatorOptions.$Orientation
			array(
				'name'		=>	'epl_slider_thumb_orientation',
				'default'	=>	'1'
			),
			// $PauseOnHover
			array(
				'name'		=>	'epl_slider_pauseOnHover',
				'default'	=>	'1'
			),
			array(
				'name'		=>	'epl_slider_reverseorder',
				'default'	=>	'false'
			),
			// $ArrowKeyNavigation
			array(
				'name'		=>	'epl_slider_keyboard',
				'default'	=>	'true'
			),
			array(
				'name'		=>	'epl_slider_thumb_lanes',
				'default'	=>	'1'
			),
			array(
				'name'		=>	'epl_slider_width',
				'default'	=>	'800',
			),
			array(
				'name'		=>	'epl_slider_height',
				'default'	=>	'600',
			),
			array(
				'name'		=>	'epl_slider_height_mobile',
				'default'	=>	'300',
			),
			array(
				'name'		=>	'epl_slider_width_mobile',
				'default'	=>	'400',
			),
			array(
				'name'		=>	'epl_slider_thumb_on_handheld',
				'default'	=>	'0',
			),
			array(
				'name'		=>	'epl_slider_thumb_height',
				'default'	=>	'120',
			),
			array(
				'name'		=>	'epl_slider_thumb_width',
				'default'	=>	'120',
			),
			array(
				'name'		=>	'epl_slider_arrow_style',
				'default'	=>	'a17.png'
			),
			array(
				'name'		=>	'epl_slider_popup',
				'default'	=>	'false'
			),
			array(
				'name'  	=>  	'epl_slider_enable_archive',
				'default'   	=>  	0
			),
			array(
				'name'  	=>  	'epl_slider_archive_image_size',
				'default'   	=>  	'epl-image-medium-crop'
			),
			array(
				'name'		=>	'epl_slider_archive_wrapper_height',
				'default'	=>	200,
			),
			array(
				'name'		=>	'epl_slider_archive_wrapper_width',
				'default'	=>	300,
			),
			array(
				'name'		=>	'epl_slider_archive_price_sticker',
				'default'	=>	'true',
			),
			array(
				'name'		=>	'epl_slider_single_price_sticker',
				'default'	=>	'false',
			),
		);
	}

	/**
	 * Slder Config Options
	 *
	 * This function returns an array mapping actual slider configuration to
	 * that of the slider settings field names in admin panel or custom settings
	 * in case of shortcodes. All the dynamic fields having prefix 'epl_' should
	 * be replaced by actual values prior to making it json object & using in slider.
	 *
	 * @since 1.1
	 * @return array mapping actual slider configuration to that of the slider settings field names
	 */
	public function slider_config_options() {

		return array(

			'$AutoPlay'			=> 'epl_slider_slideshow',
			'$AutoPlayInterval'		=> 'epl_slider_slideshowSpeed',
			'$PauseOnHover'			=> 'epl_slider_pauseOnHover',
			'$DragOrientation'		=> 'epl_allow_swipe',
			'$ArrowKeyNavigation'		=> 'epl_slider_keyboard',
			'$SlideDuration'		=> 'epl_slider_animationSpeed',

			'$SlideshowOptions'		=> array(
				'$Class'			=> '$JssorSlideshowRunner$',
				'$Transitions'			=> '_SlideshowTransitions',
				'$TransitionsOrder'		=> 1,
				'$ShowLink'			=> true,
			),

			'$ArrowNavigatorOptions'	=> array(
				'$Class'			=> '$JssorArrowNavigator$',
				'$ChanceToShow'			=> 'epl_slider_controlNav',
				'$AutoCenter'			=> 2,
				'$Steps'			=> 1
			),

			'$ThumbnailNavigatorOptions'	=> array(
				'$Class'			=> '$JssorThumbnailNavigator$',
				'$ChanceToShow'			=> 'epl_slider_use_thumbnails',
				'$ActionMode'			=> 1,
				'$Lanes'			=> 'epl_slider_thumb_lanes',
				'$SpacingX'			=> 'epl_slider_spacingx',
				'$SpacingY'			=> 'epl_slider_spacingy',
				'$DisplayPieces'		=> 'epl_display_pieces',
				'$ParkingPosition'		=> 156,
				'$Orientation'			=> 'epl_slider_thumb_orientation',
			)
		);
	}

	/**
	 * Check if the slider is active on the current page
	 *
	 * @since 1.1
	 * @return boolean true if slider is running on current page
	 */
	public function is_slider_active() {

		global $post;

		if(is_null($post))
			return;

		$epl_posts 	= epl_get_active_post_types();
		$epl_posts 	= array_keys($epl_posts);

		$epl_posts 	= apply_filters('epl_filter_slider_post_types',$epl_posts);
		if (
			( is_single() && in_array( get_post_type(), $epl_posts ) ) ||
			$this->is_allowed_archives() ||
			has_shortcode( $post->post_content, 'epl-slider-gallery')
		) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the slider is running via shortcode
	 *
	 * @since 1.1
	 * @return boolean true if slider is running via shortcode
	 */
	public function is_slider_shortcode() {
		global $post;

		if(!is_null($post)) {
			return has_shortcode( $post->post_content, 'epl-slider-gallery') ? true : false;
		}

		return false;
	}


	/**
	 * Slider Hooks
	 *
	 * @since 1.0.0
	 */
	function hooks() {

		global $epl_settings;

			add_action( 'wp_enqueue_scripts', array($this,'epl_slider_enqueue_scripts'), 10, 3 );

			add_action('wp', array($this,'epl_slider_overwrite_featured_image') );

			add_action('wp_head', array($this,'epl_slider_render_options') );

			add_shortcode('epl-slider-gallery', array($this,'slider_shortcode') );

			if( isset($epl_settings['epl_slider_eam_mode']) && $epl_settings['epl_slider_eam_mode'] == 'true') {

				add_action('wp', array($this,'epl_slider_action_overwrites'),99);

				add_filter('epl_adv_tabs',array($this,'epl_slider_advanced_map_box_tabs') );
		}
	}

	/**
	 * Enable Slider on Archive View
	 *
	 * @since 1.0.0
	 */
	function is_allowed_archives() {
		global $epl_settings;

		if(isset($epl_settings['epl_slider_enable_archive']) && $epl_settings['epl_slider_enable_archive'] == 1) {
			$epl_posts   = epl_get_active_post_types();
			$epl_posts  = array_keys($epl_posts);
			$epl_posts   = apply_filters('epl_filter_slider_post_types',$epl_posts);

			if( is_post_type_archive($epl_posts) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Enqueue necessary stylesheets & script files
	 *
	 * @since 1.0.0
	 */
	function epl_slider_enqueue_scripts() {

		if ( $this->is_slider_active() ) {
			wp_enqueue_script( 'epl-slider-jssor', 			EPL_SLIDER_PLUGIN_URL . 'js/jssor.slider.mini.js', 	__FILE__,EPL_SLIDER_VERSION );
			wp_enqueue_script( 'epl-slider-jquery.fancybox', 	EPL_SLIDER_PLUGIN_URL . 'js/jquery.fancybox.pack.js', 	__FILE__,EPL_SLIDER_VERSION  );
			wp_enqueue_script( 'epl-slider-script', 		EPL_SLIDER_PLUGIN_URL . 'js/script.js', 		__FILE__,EPL_SLIDER_VERSION  );
			wp_enqueue_style( 'epl-slider-fancybox-style', 		EPL_SLIDER_PLUGIN_URL . 'css/jquery.fancybox.css', 	__FILE__,EPL_SLIDER_VERSION );
		}
	}

	/**
	 * Remove EPL Core featured image actions
	 *
	 * @since 1.0.0
	 */
	function epl_slider_action_overwrites() {

		if( is_archive() ) {
			return;
		}

		$epl_posts 	= epl_get_active_post_types();
		$epl_posts 	= array_keys($epl_posts);

		/** add sliders to other post types **/
		$epl_posts 	= apply_filters('epl_filter_slider_post_types',$epl_posts);

		if ( (is_single() && in_array( get_post_type(), $epl_posts ) )  ) {
			remove_all_actions( 'epl_property_featured_image' );
		}

	        if( $this->is_allowed_archives() ) {
			remove_all_actions( 'epl_property_archive_featured_image' );
	        }
	}

	/**
	 * Check if listing's feature image is shown & overwrite it with slider
	 *
	 * @since 1.0.0
	 */
	function epl_slider_overwrite_featured_image() {

		$epl_posts 	= epl_get_active_post_types();
		$epl_posts 	= array_keys($epl_posts);

		/** add sliders to other post types **/
		$epl_posts 	= apply_filters('epl_filter_slider_post_types',$epl_posts);

		if ( (is_single() && in_array( get_post_type(), $epl_posts ) ) ) {

			// remove default featured image
			remove_action('epl_property_featured_image','epl_property_featured_image');

			// add action for jssor slider
			add_action('epl_property_featured_image',array($this,'epl_slider_gallery') );

			// remove video from single page
			remove_action('epl_property_content_after','epl_property_content_after');
		}

		if( $this->is_allowed_archives() ) {

			// remove default featured image
			remove_action('epl_property_archive_featured_image','epl_property_archive_featured_image');

			// add action for jssor slider
			add_action('epl_property_archive_featured_image',array($this,'epl_slider_gallery') );
		}
	}

	/**
	 * Get thumbnail of a vimeo video
	 *
	 * @since 1.0.0
	 */
	function epl_slider_vimeo_thumbnail($video) {

		$id = $this->epl_slider_vimeo_id($video);
		if (!function_exists('curl_init')) die('CURL is not installed!');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/$id.php");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = unserialize(curl_exec($ch));
		$output = $output[0];
		curl_close($ch);
		return $output['thumbnail_medium'];
	}

	/**
	 * Get id of a vimeo video via its url
	 *
	 * @since 1.0.0
	 */
	function epl_slider_vimeo_id($link){

		$regexstr = '~
		    # Match Vimeo link and embed code
		    (?:<iframe [^>]*src=")?     # If iframe match up to first quote of src
		    (?:                         # Group vimeo url
		        https?:\/\/             # Either http or https
		        (?:[\w]+\.)*            # Optional subdomains
		        vimeo\.com              # Match vimeo.com
		        (?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
		        \/                      # Slash before Id
		        ([0-9]+)                # $1: VIDEO_ID is numeric
		        [^\s]*                  # Not a space
		    )                           # End group
		    "?                          # Match end quote if part of src
		    (?:[^>]*></iframe>)?        # Match the end of the iframe
		    (?:<p>.*</p>)?              # Match any title information stuff
		    ~ix';

		preg_match($regexstr, $link, $matches);

		return $matches[1];
	}

	/**
	 * Get id of a youtube video via its url
	 *
	 * @since 1.0.0
	 */
	function epl_slider_youtube_id($text) {
		$text = preg_replace('~
			# Match non-linked youtube URL in the wild. (Rev:20130823)
			https?://         # Required scheme. Either http or https.
			(?:[0-9A-Z-]+\.)? # Optional subdomain.
			(?:               # Group host alternatives.
			  youtu\.be/      # Either youtu.be,
			| youtube         # or youtube.com or
			  (?:-nocookie)?  # youtube-nocookie.com
			  \.com           # followed by
			  \S*             # Allow anything up to VIDEO_ID,
			  [^\w\-\s]       # but char before ID is non-ID char.
			)                 # End host alternatives.
			([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
			(?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
			(?!               # Assert URL is not pre-linked.
			  [?=&+%\w.-]*    # Allow URL (query) remainder.
			  (?:             # Group pre-linked alternatives.
			    [\'"][^<>]*>  # Either inside a start tag,
			  | </a>          # or inside <a> element text contents.
			  )               # End recognized pre-linked alts.
			)                 # End negative lookahead assertion.
			[?=&+%\w.-]*        # Consume any URL (query) remainder.
			~ix',
			"$1",
			$text);
		return $text;
	}

	/**
	 * Get thumbnail of a youtube video
	 *
	 * @since 1.0.0
	 */
	function epl_slider_youtube_thumbnail($video) {
		$id = $this->epl_slider_youtube_id($video);
		return "http://img.youtube.com/vi/{$id}/mqdefault.jpg";
	}

	/**
	 * Method for cropping images
	 *
	 * @since 1.0.0
	 *
	 * @global object $wpdb The $wpdb database object.
	 *
	 * @param string $url      The URL of the image to resize.
	 * @param int $width       The width for cropping the image.
	 * @param int $height      The height for cropping the image.
	 * @param bool $crop       Whether or not to crop the image (default yes).
	 * @param string $align    The crop position alignment.
	 * @param bool $retina     Whether or not to make a retina copy of image.
	 * @param array $data      Array of slider data (optional).
	 * @return WP_Error|string Return WP_Error on error, URL of resized image on success.
	 */
	public function resize_image( $url, $width = null, $height = null, $crop = true, $align = 'c', $quality = 100, $retina = false, $data = array() ) {

		global $wpdb;

		// Get common vars.
		$args   = array( $url, $width, $height, $crop, $align, $quality, $retina, $data );
		$common = $this->get_image_info( $args );

		// Unpack variables if an array, otherwise return WP_Error.
		if ( is_wp_error( $common ) ) {
			return $common;
		} else {
			extract( $common );
		}

		// If the destination width/height values are the same as the original, don't do anything.
		if ( $orig_width === $dest_width && $orig_height === $dest_height ) {
			return $url;
		}

		// If the file doesn't exist yet, we need to create it.
		if ( ! file_exists( $dest_file_name ) ) {
			// We only want to resize Media Library images, so we can be sure they get deleted correctly when appropriate.
			$get_attachment = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid='%s'", $url ) );

			// Load the WordPress image editor.
			$editor = wp_get_image_editor( $file_path );

			// If an editor cannot be found, the user needs to have GD or Imagick installed.
			if ( is_wp_error( $editor ) ) {
				return new WP_Error( 'epl-slider-error-no-editor', __( 'No image editor could be selected. Please verify with your webhost that you have either the GD or Imagick image library compiled with your PHP install on your server.', 'epl-sliders' ) );
			}

			// Set the image editor quality.
			$editor->set_quality( $quality );

			// If cropping, process cropping.
			if ( $crop ) {
			        $src_x = $src_y = 0;
			        $src_w = $orig_width;
			        $src_h = $orig_height;

			        $cmp_x = $orig_width / $dest_width;
			        $cmp_y = $orig_height / $dest_height;

			        // Calculate x or y coordinate and width or height of source
			        if ( $cmp_x > $cmp_y ) {
					$src_w = round( $orig_width / $cmp_x * $cmp_y );
					$src_x = round( ($orig_width - ($orig_width / $cmp_x * $cmp_y)) / 2 );
			        } else if ( $cmp_y > $cmp_x ) {
					$src_h = round( $orig_height / $cmp_y * $cmp_x );
					$src_y = round( ($orig_height - ($orig_height / $cmp_y * $cmp_x)) / 2 );
			        }

			        // Positional cropping.
			        if ( $align && $align != 'c' ) {
					if ( strpos( $align, 't' ) !== false || strpos( $align, 'tr' ) !== false || strpos( $align, 'tl' ) !== false ) {
						$src_y = 0;
					}

					if ( strpos( $align, 'b' ) !== false || strpos( $align, 'br' ) !== false || strpos( $align, 'bl' ) !== false ) {
						$src_y = $orig_height - $src_h;
					}

					if ( strpos( $align, 'l' ) !== false ) {
						$src_x = 0;
					}

					if ( strpos ( $align, 'r' ) !== false ) {
						$src_x = $orig_width - $src_w;
					}
			        }

				// Crop the image.
				$editor->crop( $src_x, $src_y, $src_w, $src_h, $dest_width, $dest_height );
			} else {
				// Just resize the image.
				$editor->resize( $dest_width, $dest_height );
			}

			// Save the image.
			$saved = $editor->save( $dest_file_name );

			// Print possible out of memory errors.
			if ( is_wp_error( $saved ) ) {
				@unlink( $dest_file_name );
				return $saved;
			}

			// Add the resized dimensions and alignment to original image metadata, so the images
			// can be deleted when the original image is delete from the Media Library.
			if ( $get_attachment ) {
			$metadata = wp_get_attachment_metadata( $get_attachment[0]->ID );

				if ( isset( $metadata['image_meta'] ) ) {
					$md = $saved['width'] . 'x' . $saved['height'];

					if ( $crop ) {
						$md .= $align ? "_${align}" : "_epl_slider";
					}
					$metadata['image_meta']['resized_images'][] = $md;

					wp_update_attachment_metadata( $get_attachment[0]->ID, $metadata );
				}
			}

			// Set the resized image URL.
			$resized_url = str_replace( basename( $url ), basename( $saved['path'] ), $url );
		} else {
		// Set the resized image URL.
		$resized_url = str_replace( basename( $url ), basename( $dest_file_name ), $url );
		}

		// Return the resized image URL.
		return $resized_url;
	}

	/**
	 * Helper method to return common information about an image.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args      List of resizing args to expand for gathering info.
	 * @return WP_Error|string Return WP_Error on error, array of data on success.
	 */
	public function get_image_info( $args ) {

		// Unpack arguments.
		list( $url, $width, $height, $crop, $align, $quality, $retina, $data ) = $args;

		// Return an error if no URL is present.
		if ( empty( $url ) ) {
			return new WP_Error( 'epl-slider-error-no-url', __( 'No image URL specified for cropping.', 'epl-sliders' ) );
		}

		// Get the image file path.
		$urlinfo       = parse_url( $url );
		$wp_upload_dir = wp_upload_dir();

		// Interpret the file path of the image.
		if ( preg_match( '/\/[0-9]{4}\/[0-9]{2}\/.+$/', $urlinfo['path'], $matches ) ) {
			$file_path = $wp_upload_dir['basedir'] . $matches[0];
		} else {
			$pathinfo    = parse_url( $url );
			$uploads_dir = is_multisite() ? '/files/' : '/wp-content/';
			$file_path   = ABSPATH . str_replace( dirname( $_SERVER['SCRIPT_NAME'] ) . '/', '', strstr( $pathinfo['path'], $uploads_dir ) );
			$file_path   = preg_replace( '/(\/\/)/', '/', $file_path );
		}

		// Attempt to stream and import the image if it does not exist based on URL provided.
		if ( ! file_exists( $file_path ) ) {
			return new WP_Error( 'epl-slider-error-no-file', __( 'No file could be found for the image URL specified.', 'epl-sliders' ) );
		}

		// Get original image size.
		$size = @getimagesize( $file_path );

		// If no size data obtained, return an error.
		if ( ! $size ) {
			return new WP_Error( 'epl-slider-error-no-size', __( 'The dimensions of the original image could not be retrieved for cropping.', 'epl-sliders' ) );
		}

		// Set original width and height.
		list( $orig_width, $orig_height, $orig_type ) = $size;

		// Generate width or height if not provided.
		if ( $width && ! $height ) {
			$height = floor( $orig_height * ($width / $orig_width) );
		} else if ( $height && ! $width ) {
			$width = floor( $orig_width * ($height / $orig_height) );
		} else if ( ! $width && ! $height ) {
			return new WP_Error( 'epl-slider-error-no-size', __( 'The dimensions of the original image could not be retrieved for cropping.', 'epl-sliders' ) );
		}

		// Allow for different retina image sizes.
		$retina = $retina ? ( $retina === true ? 2 : $retina ) : 1;

		// Destination width and height variables
		$dest_width  = $width * $retina;
		$dest_height = $height * $retina;

		// Some additional info about the image.
		$info = pathinfo( $file_path );
		$dir  = $info['dirname'];
		$ext  = $info['extension'];
		$name = wp_basename( $file_path, ".$ext" );

		// Suffix applied to filename
		$suffix = "${dest_width}x${dest_height}";

		// Set alignment information on the file.
		if ( $crop ) {
			$suffix .= ( $align ) ? "_${align}_epl_slider" : "_epl_slider";
		}

		// Get the destination file name
		$dest_file_name = "${dir}/${name}-${suffix}.${ext}";

		// Return the info.
		return apply_filters( 'epl_slider_get_image_info', array(
			'dir'            => $dir,
			'name'           => $name,
			'ext'            => $ext,
			'suffix'         => $suffix,
			'orig_width'     => $orig_width,
			'orig_height'    => $orig_height,
			'orig_type'      => $orig_type,
			'dest_width'     => $dest_width,
			'dest_height'    => $dest_height,
			'file_path'      => $file_path,
			'dest_file_name' => $dest_file_name,
		), $data );
	}

	/**
	 * Get slides options
	 *
	 * @since 1.0
	 */
	function slide_get_option( $key='' ) {

		global $epl_settings;

		$fields = $this->slider_defaults();


		foreach($fields as $field ) {

			if($field['name']  == $key) {

				$value =  ( isset($epl_settings[$key]) && $epl_settings[$key] != '') ? $epl_settings[$key] : $field['default'];

				if($value == 'true' || $value == 'false') {
					if($value == 'true' ) {
						return true;
					}
					if($value == 'false' ) {
						return false;
					}
				}

				// return integer if value is numeric
				if( is_numeric($value) ) {

					$value = (int) $value;
				}
				return $value;
			}
		}
	}

	/**
	 * Thumbnails on mobile
	 *
	 * @since 2.0
	 */
	function thumbs_on_mobile() {

		if($this->slide_get_option('epl_slider_thumb_on_handheld') == 0 &&  wp_is_mobile() ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Gets slider configuration from settings and creates js object to be passed to slider
	 *
	 * @since 1.0.0
	 */
	function epl_slider_render_options() {

		global $post,$property,$epl_settings;

		if( is_null($post) )
			return;

		$use_thumbs = $this->thumbs_on_mobile() ? 0 : $this->slide_get_option('epl_slider_use_thumbnails');
    		$map_js_options = array(

			'$AutoPlay'			=> $this->is_allowed_archives() ? 0: $this->slide_get_option('epl_slider_slideshow'),
			'$AutoPlayInterval'		=> $this->slide_get_option('epl_slider_slideshowSpeed'),
			'$PauseOnHover'			=> $this->slide_get_option('epl_slider_pauseOnHover'),
			'$DragOrientation'		=> $this->is_allowed_archives() ? 0: $this->slide_get_option('epl_allow_swipe'),
			'$ArrowKeyNavigation'		=> $this->slide_get_option('epl_slider_keyboard'),
			'$SlideDuration'		=> $this->slide_get_option('epl_slider_animationSpeed'),

			'$SlideshowOptions'		=> array(
				'$Class'			=> '$JssorSlideshowRunner$',
				'$Transitions'			=> '_SlideshowTransitions',
				'$TransitionsOrder'		=> 1,
				'$ShowLink'			=> true,
			),

			'$ArrowNavigatorOptions'	=> array(
				'$Class'			=> '$JssorArrowNavigator$',
				'$ChanceToShow'			=> $this->slide_get_option('epl_slider_controlNav'),
				'$AutoCenter'			=> 2,
				'$Steps'			=> 1
			),

			'$ThumbnailNavigatorOptions'	=> array(
				'$Class'			=> '$JssorThumbnailNavigator$',
				'$ChanceToShow'			=> $this->is_allowed_archives() ? 0 :  $use_thumbs,
				'$ActionMode'			=> 1,
				'$Lanes'			=> $this->slide_get_option('epl_slider_thumb_lanes'),
				'$SpacingX'			=> $this->slide_get_option('epl_slider_spacingx'),
				'$SpacingY'			=> $this->slide_get_option('epl_slider_spacingy'),
				'$DisplayPieces'		=> $this->slide_get_option('epl_display_pieces'),
				'$ParkingPosition'		=> 156,
				'$Orientation'			=> $this->slide_get_option('epl_slider_thumb_orientation'),
			)
		);

		if ( $this->is_slider_active() ) {

			/** only for sliders on single & archive pages not slider with shortcode **/
			if( ! $this->is_slider_shortcode() ) {
				$eplSliderIsArchive = $this->is_allowed_archives() ? 1 : 0;
				echo '<script>
						var eplSliderIsArchive  = '.$eplSliderIsArchive.';
						var eplSliderOptions 	= '.json_encode($map_js_options).';
						var $_transitionChosen 	= "'.$this->slide_get_option("epl_slider_transition").'";
					</script>';

				echo $this->output_css();

				// custom css for full width slider with no thumbnail
				if($this->slide_get_option('epl_slider_use_thumbnails') == 0|| $this->thumbs_on_mobile() ) {
					?><style>
						.epl-slider-slides {
							right:0!important;
						}
						.epl-slider-thumb-container {
							width:0!important;
						}
						.epl-slider-right-nav{
							right:8px!important;
						}
					</style><?php
				}
			}
		}
	}

	/**
	 * Output Slider CSS
	 *
	 * @since 1.0
	 */
	function output_css() {

		global $post,$property,$epl_settings;

		if( is_null($post) )
			return;

		if( class_exists('EPL_SLIDER_CSS') ) {

			$epl_slider_css = EPL_SLIDER_CSS::get_instance($this);

			// horizontal navigation
			if($this->slide_get_option('epl_slider_thumb_orientation') == 1 || $this->is_allowed_archives() || $this->thumbs_on_mobile() ) {

				return $epl_slider_css->horizontal_thumbnails();
			} else {
				// vertical navigation - thumbs on right
				if($this->slide_get_option('epl_slider_thumb_orientation') == 2) {
					return $epl_slider_css->thumbs_on_right();
				}
			}
		}
	}

	/**
	 * Integration with Listing Unlimited
	 *
	 * @since 1.0
	 */
	function epl_slider_gallery() {

		global $property,$post, $epl_settings;

		$parent_id = $property->post->ID;
		if (  class_exists( 'EPL_Listing_Unlimited' ) ) {
			$lu_query = array (
				'post_type'	=>	'listing_unlimited',
				'meta_query'	=>	array(
					array(
						'key' 		=> 'property_unique_id',
						'value' 	=> get_property_meta( 'property_unique_id'),
						'compare' 	=> '=='
					)
				)
			);

			$lu = get_posts($lu_query);
			if(!empty($lu)) {
				$lu_parent_id = $lu[0]->ID;
				$media = get_attached_media( 'image' , $lu_parent_id );

				if ( !empty($media)) {
					$parent_id = $lu_parent_id;
				}
			}
		}

		$args = array(
			'post_parent' 		=> $parent_id,
			'post_type'   		=> 'attachment',
			'numberposts' 		=> -1,
			'post_mime_type'	=> 'image',
			'orderby'		=> 'ID'
		);
		if(get_post_meta($property->post->ID,'epl_slides_order',true) != '') {
			$args['post__in'] 	= explode(',', get_post_meta($property->post->ID,'epl_slides_order',true) );
			$args['orderby']	= 'post__in';
		}
		// if enabled / disabled attachments then revise post__in
		$enabled_thumbs = (array) get_post_meta($property->post->ID,'epl_slider_enabled_thumbs',true);
		$enabled_thumbs = array_filter($enabled_thumbs);
		if( !empty($enabled_thumbs) ) {
			$args['post__in'] 	= $enabled_thumbs;
		}

		if ( has_post_thumbnail($property->post->ID) ) {
			$featured_image = get_post_thumbnail_id($property->post->ID);
			$args['exclude'] = $featured_image;
			$images = get_posts($args);
		} else {
			$images = get_posts($args);
		}

		/** only show featured image if attachments are not present  **/
		if(count($images) < 1) {

			if( $this->is_allowed_archives() ) {
				epl_property_archive_featured_image();
			} else {
				do_action('epl_property_widgets_featured_image');
			}

			return;
		}

		if( isset($epl_settings['epl_slider_reverseorder']) && $epl_settings['epl_slider_reverseorder'] == 'true' ) {
			$images = array_reverse($images);
		}

		// add feature image only if enabled from settings
		// feature images should always be at top even after reverse 
		if ( has_post_thumbnail($property->post->ID) && epl_get_option('epl_slider_feature_image') != 'false' ) {
			$images =  array_merge( array( get_post($featured_image) ) ,  $images);
		}
		$images = array_filter($images);

		if ( !empty($images) ) {

			$inlinecss = '';

			/** inline height for archive pages only **/
			if( $this->is_allowed_archives() ) {

				if( isset($epl_settings['epl_slider_archive_image_size']) && $epl_settings['epl_slider_archive_image_size'] != '' ) {

					$archive_img_size = $epl_settings['epl_slider_archive_image_size'];

				} else {
					$archive_img_size = 'medium'; // default
				}

				if(intval($this->slide_get_option('epl_slider_archive_wrapper_height')) > 0)
					$inlinecss .= "height:".intval($this->slide_get_option('epl_slider_archive_wrapper_height'))."px; ";
				else
					$inlinecss .= "height:".intval(get_option( $archive_img_size . '_size_h' ))."px; ";


				if(intval($this->slide_get_option('epl_slider_archive_wrapper_width')) > 0)
					$inlinecss .= "width:".intval($this->slide_get_option('epl_slider_archive_wrapper_width'))."px; ";
				else
					$inlinecss .= "width:".intval(get_option( $archive_img_size . '_size_w' ))."px; ";

			}

			$epl_slider_wrapper_class = $this->is_allowed_archives() ? 'epl-slider-archive-wrapper':'epl-slider-single-wrapper';
		?>
			<div class="<?php echo $epl_slider_wrapper_class; ?>">
				<!-- Jssor Slider Begin -->
				<!-- To move inline styles to css file/block, please specify a class name for each element. -->
				<div id="epl_slider_container_<?php echo $post->ID?>" class="epl_slider_container" <?php echo 'style="'.$inlinecss.'"';?> >
					<?php if(
							( $this->is_allowed_archives() && $this->slide_get_option('epl_slider_archive_price_sticker') )
							||
							( is_epl_post() && $this->slide_get_option('epl_slider_single_price_sticker') )
						) {
					?>
					<div class="epl-stickers-wrapper">
						<?php echo epl_get_price_sticker(); ?>
					</div>

					<?php
						}
						if( !$this->is_allowed_archives() ) {

							if(wp_is_mobile()) {
								$height 	= $this->slide_get_option('epl_slider_height_mobile');
								$width 		= $this->slide_get_option('epl_slider_width_mobile');

							} else {
								$height 	= $this->slide_get_option('epl_slider_height');
								$width 		= $this->slide_get_option('epl_slider_width');
							}

						} else {

							$height 		= get_option( $archive_img_size . '_size_h' );
							$width 			= get_option( $archive_img_size . '_size_w' );
						}

						$slider_thumb_height 		= $this->slide_get_option('epl_slider_thumb_height');
						$slider_thumb_width 		= $this->slide_get_option('epl_slider_thumb_width');

						/* adjust width of slides in case of vertical thumb nav */
						if($this->slide_get_option('epl_slider_use_thumbnails') == 2 && $this->slide_get_option('epl_slider_thumb_orientation') == 2) {

							/** if thumbs on mobile is set to enable **/
							if( !$this->thumbs_on_mobile() ) {

								$lanes			= $this->slide_get_option('epl_slider_thumb_lanes');
								$thumbcontainerw	= $lanes * $this->slide_get_option('epl_slider_thumb_width');
								$thumbcontainerw	= $thumbcontainerw + (intval($lanes) + 1) * $this->slide_get_option('epl_slider_spacingx');
								$width			= $width - $thumbcontainerw;
							}

						}

						if( !$this->is_allowed_archives() ) {
							$inlinecss = '';
							// if( isset($epl_settings['epl_slider_height']) && $epl_settings['epl_slider_height'] != '' ) {
							// 	$inlinecss .= "height:".intval($epl_settings['epl_slider_height'])."px; ";
							// }
							$inlinecss .= "height:".$height."px; ";
							$inlinecss .= "width:".$width."px; ";
						}
					?>
					<!-- Slides Container -->
					<div u="slides" id="epl-slider-slides" class="epl-slider-slides" <?php echo 'style="'.$inlinecss.'"';?>>
			 		<?php
						foreach ( $images as $attachment_id => $attachment ) {

		                            if( $this->is_allowed_archives() ) {
		                                $default_image_size = $this->slide_get_option('epl_slider_archive_image_size');
		                            } else {
		                                $default_image_size = 'full';
		                            }
							$src        	= wp_get_attachment_image_src( $attachment->ID, $default_image_size );
							$thumb      	= wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
							$image_src      = ! empty( $src[0] ) ? $src[0] : false;
							$thumb      	= ! empty( $thumb[0] ) ? $thumb[0] : false;

							if( ($width != 0 || $height != 0 ) && !$this->is_allowed_archives() ) {

								$args   = apply_filters( 'epl_slider_crop_image_args',
									array(
										'position' => 'c',
										'width'    => $width,
										'height'   => $height,
										'quality'  => 100,
										'retina'   => false
									)
								);
								$allow_crop = $this->thumbs_on_mobile() ? false : true;
								$image = $this->resize_image(
										$image_src,
										$args['width'],
										$args['height'],
										$allow_crop,
										$args['position'],
										$args['quality'],
										$args['retina']
									);
							 }

							 if($this->slide_get_option('epl_slider_use_thumbnails') == 2 && !$this->is_allowed_archives()) {
								 if($slider_thumb_height != 0 || $slider_thumb_height != 0) {

									$args   = apply_filters( 'epl_slider_crop_thumb_image_args',
										array(
											'position' => 'c',
											'width'    => $slider_thumb_width,
											'height'   => $slider_thumb_height,
											'quality'  => 100,
											'retina'   => false
										)
									);

									$thumb = $this->resize_image(
										$image_src,
										$args['width'],
										$args['height'],
										true,
										$args['position'],
										$args['quality'],
										$args['retina']
									);
								}
							 }

							$thumb_html = '';
							if($this->slide_get_option('epl_slider_use_thumbnails') == 2 && !$this->is_allowed_archives()) {
								$thumb_html = "<img u=\"thumb\" src=\"{$thumb}\" />";
							}

							/** slider on archive pages **/
							if( $this->is_allowed_archives() ) {
								echo "
	 							<div>
	 								<a  href=\"".get_permalink($property->post->ID)."\" u=\"image\" >
										<img src=\"{$image_src}\" />
									</a>
								</div>";
						 	} else {
						 		/** slider on single pages **/
								if( isset($epl_settings['epl_slider_popup']) && $epl_settings['epl_slider_popup'] == 'true' ) {
								 	echo "
		 							<div>
		 								<a rel=\"epl_slider_fancy_gallery\" class=\"epl_slider_popup_image\" href=\"{$src[0]}\" u=\"image\" >
											<img src=\"{$image}\" />
										</a>
										{$thumb_html}
									</div>";
								} else {
		   						 	echo "
		 							<div>
										<img u=\"image\" src=\"{$image}\" />
										{$thumb_html}
									</div>";
								}
							}
						} ?>
					</div>

					<span u="arrowleft" class="epl-slider-left-nav" >
					</span>
					<span u="arrowright" class="epl-slider-right-nav" >
					</span>
					<?php if( !$this->is_allowed_archives() ) { ?>
						<div u="thumbnavigator" class="epl-slider-thumb-container" >
							<!-- Thumbnail Item Skin Begin -->
							<div u="slides" style="cursor: default;">
								<div u="prototype" class="p">
								    <div class=w><div u="thumbnailtemplate" class="t"></div></div>
								    <div class=c></div>
								</div>
							</div>
							<!-- Thumbnail Item Skin End -->
						</div>
					<?php } ?>
				</div>
				<!-- Jssor Slider End -->
			</div><?php
		}
	}

	/**
	 * Integration with Advanced Mapping extension
	 *
	 * @since 1.0
	 */
	function epl_slider_advanced_map_box_tabs($tabs) {

		$extra = array('gallery'	=>	__('Gallery','epl-sliders') );

		if(get_property_meta('property_video_url') != '') {
			$extra['video']	=	__('Video','epl-sliders');
		}
		$tabs = $extra + $tabs;
		return $tabs ;
	}

	/**
	 * Callback for shortcode
	 *
	 * @since 1.1
	 */
	function slider_shortcode( $atts = array() ) {

		$defaults = array();

		// get slider deault values
		$defaults_array = $this->slider_defaults();

		foreach($defaults_array as $default_key 	=>	$default_value) {
			$defaults[ltrim($default_value['name'],'epl_')] = $default_value['default'];
		}

		//merge user settings & defaults
		$atts 		= shortcode_atts( $defaults, $atts);
		// get slider configuration array
		$configs 	= $this->slider_config_options();

		// replace placeholders in configuration array to dynamic values
		array_walk_recursive( $configs, array($this,'slider_config_callback') , $atts);

		echo '<script>
				var eplSliderOptions 	= '.json_encode($configs).';
				var $_transitionChosen 	= "'.$atts['slider_transition'].'";
			</script>';

		echo $this->output_css();

		// custom css for full width slider with no thumbnail
		if($this->slide_get_option('epl_slider_use_thumbnails') == 0 || $this->thumbs_on_mobile() ) { ?>
			<style>
				.epl-slider-slides {
					right:0!important;
				}
				.epl-slider-thumb-container {
					width:0!important;
				}
				.epl-slider-right-nav{
					right:8px!important;
				}
			</style> <?php
		}
		echo "<pre>";
		print_r($configs);
		echo "</pre>";

	}

	/**
	 * Callback for shortcode options
	 *
	 * @since 1.1
	 */
	function slider_config_callback(&$value,$key,$atts) {

		if( isset($atts[ltrim($value,'epl_')]) ) {
			$value = $atts[ltrim($value,'epl_')];
		}

	}
}

// Load this class.
$epl_slider_config = EPL_SLIDER_CONFIG::get_instance();

/**
 * Integrate Slider in Advanced Maps Tab
 *
 * @since 1.1
 */
function epl_adv_tab_map_gallery( $epl_author = array() ) {
 	global $property,$post,$epl_settings;
 	$epl_slider_config = EPL_SLIDER_CONFIG::get_instance();
	$epl_slider_config->epl_slider_gallery();
}

/**
 * Integrate Slider Video in Advanced Maps Tab
 *
 * @since 1.1
 */
function epl_adv_tab_map_video( $epl_author = array() ) {
 	global $property,$post,$epl_settings;
	$video 	=  get_property_meta('property_video_url');
	if($video != '')
		echo wp_oembed_get($video);
}