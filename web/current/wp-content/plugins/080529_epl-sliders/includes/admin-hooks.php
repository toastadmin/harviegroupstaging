<?php
/**
 * Admin Functions
 *
 * @package     EPL-SLIDERS
 * @subpackage  Functions/Admin
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Save Sliders License Key
 *
 * @since 1.0
 */
function epl_slider_license_options_filter($fields = null) {
	$fields[] = array(
		'label'		=>	'',
		'fields'	=>	array(
			array(
				'name'	=>	'sliders',
				'label'	=>	'Sliders license key',
				'type'	=>	'text'
			)
		)
	);

	return $fields;
}
add_filter('epl_license_options_filter', 'epl_slider_license_options_filter', 10, 3);

/**
 * Sliders Extension Options
 *
 * @since 1.0
 */
function epl_slider_extensions_options_filter( $epl_fields = null ) {

	$arrow_images = array_map( 'basename', glob(EPL_SLIDER_PLUGIN_PATH.'img/arrows/*.png') );
	$arrow_images = array_combine($arrow_images,$arrow_images );
	if ( function_exists( 'epl_get_thumbnail_sizes' ) ) {
		$opts_sizes = array();
		$sizes = epl_get_thumbnail_sizes();
		foreach ($sizes as $k=>$v) {
			$v = implode(" x ", $v);
			$opts_sizes[ $k ] = $k . ' ' . $v;
		}

		$arrow_icons = '<div class="epl-sliders-arrow-preview-wrapper" style="text-align: left;">';
		foreach ( $arrow_images as $arrow_icon ) {
			$arrow_icons .= '<div class="epl-sliders-arrow-preview" style="background: #ddd; margin: 2px; padding: 0 0.5em; display: inline-block;">' . '<p style="margin-bottom: 0;">' . $arrow_icon . '</p>' . '<img src="' . EPL_SLIDER_PLUGIN_URL . 'img/arrows/' . $arrow_icon  . '" width="auto" height="45px" /></div>';
		}
		$arrow_icons .= '</div>';
	}

	$fields = array();
	$epl_slider_fields = array(
		'label'		=>	__('Sliders')
	);
	$fields[] = array(
		'label'		=>	__('Settings','epl-sliders'),
		'fields'	=>	apply_filters('epl_slider_tab_settings',array(

			array(
				'name'		=>	'epl_slider_settings_description_intro',

				'content'	=>	'<h3>' . __('The settings below adjust the basic configuration options for the slider display.','epl-sliders') . '</h3>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_slider_width',
				'label'		=>	__('Width','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'',
				'help'		=>	__( 'Slider container width in pixels, leave blank for auto width. Setting a width is optimal.' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_height',
				'label'		=>	__('Height','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'',
				'help'		=>	__( 'Slider container height in pixels, leave blank for auto height. Setting a height is optimal.' , 'epl-sliders')
			),

			// Transition Speed
			array(
				'name'		=>	'epl_slider_animationSpeed',
				'label'		=>	__('Transition speed in milliseconds','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	1200,
				'help'		=>	__( 'Navigation speed between slides and slideshow transition effect.' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_feature_image',
				'label'		=>	__('Include Featured Image in Slider?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'true'		=>	__('Enable','epl-sliders'),
					'false'		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'true',
				'help'		=>	__( 'Disable if you do not want to show feature image in slider' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_reverseorder',
				'label'		=>	__('Reverse Image Order?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'true'		=>	__('Enable','epl-sliders'),
					'false'		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'true',
				'help'		=>	__( 'Use when importing listings and automatically generating sliders.' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_popup',
				'label'		=>	__('Show lightbox popout when clicked?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'true'		=>	__('Enable','epl-sliders'),
					'false'		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'false',
			),

			array(
				'name'		=>	'epl_slider_single_price_sticker',
				'label'		=>	__('Show listing status sticker?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'true'		=>	__('Enable','epl-sliders'),
					'false'		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'false',
			)
		) )
	);

	$fields[] = array(
		'label'		=>	__('Navigation', 'epl-sliders'),
		'fields'	=>	apply_filters('epl_slider_tab_navigation_settings',array(

			array(
				'name'		=>	'epl_slider_settings_description_navigation_intro',
				'content'	=>	'<h3>' . __('The settings below adjust the slider arrow options.','epl-sliders') . '</h3>',
				'type'		=>	'help',
			),

			// $ArrowNavigatorOptions.$ChanceToShow
			array(
				'name'		=>	'epl_slider_controlNav',
				'label'		=>	__('Show Slider Arrows?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'0'		=>	__('Disable','epl-sliders'),
					'1'		=>	__('On Mouse Hover','epl-sliders'),
					'2'		=>	__('Always','epl-sliders'),
				),
				'default'	=>	'1'
			),

			// $ArrowKeyNavigation
			array(
				'name'		=>	'epl_slider_keyboard',
				'label'		=>	__('Allow slider control using keyboard','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'true'		=>	__('Enable','epl-sliders'),
					'false'		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'true'
			),

			array(
				'name'		=>	'epl_slider_arrow_style',
				'label'		=>	__('Navigation Arrow Style','epl-sliders'),
				'type'		=>	'select',
				'opts'		=>	$arrow_images,
				'default'	=>	'a17.png'
			),

			// Display Arrows
			array(
				'name'		=>	'epl_slider_arrow_preview',
				'content'	=>	'<h3>'. __('Arrow Styles Preview','epl-sliders') . '</h3>',
				'type'		=>	'help',
				'help'		=>	$arrow_icons,
			),
		) )
	);

	$fields[] = array(
		'label'		=>	__('Thumbnails', 'epl-sliders'),
		'fields'	=>	apply_filters('epl_slider_tab_thumbnail_settings',array(

			array(
				'name'		=>	'epl_slider_settings_description_thumbs_intro',
				'content'	=>	'<h3>' . __('The settings below adjust the thumbnail options for the slider.','epl-sliders') . '</h3>',
				'type'		=>	'help',
			),

			// $ThumbnailNavigatorOptions.$ChanceToShow
			array(
				'name'		=>	'epl_slider_use_thumbnails',
				'label'		=>	__('Show Thumbnails?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					2		=>	__('Enable','epl-sliders'),
					0		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'true'
			),

			// $ArrowNavigatorOptions.$ChanceToShow
			array(
				'name'		=>	'epl_slider_thumb_orientation',
				'label'		=>	__('Thumbnail Orientation','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'2'		=>	__('Vertical','epl-sliders'),
					'1'		=>	__('Horizontal','epl-sliders'),
				),
				'default'	=>	'1'
			),

			array(
				'name'		=>	'epl_slider_thumb_lanes',
				'label'		=>	__('Thumbnail Lanes','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'2'		=>	__('Two','epl-sliders'),
					'1'		=>	__('One','epl-sliders'),
				),
				'default'	=>	'1'
			),

			//  $DisplayPieces
			array(
				'name'		=>	'epl_display_pieces',
				'label'		=>	__('Number of thumbnails to display','epl-sliders'),
				'type'		=>	'select',
				'opts'		=>	array(
					'1'		=>	__('1','epl-sliders'),
					'2'		=>	__('2','epl-sliders'),
					'3'		=>	__('3','epl-sliders'),
					'4'		=>	__('4','epl-sliders'),
					'5'		=>	__('5','epl-sliders'),
					'6'		=>	__('6','epl-sliders'),
					'7'		=>	__('7','epl-sliders'),
					'8'		=>	__('8','epl-sliders'),
					'9'		=>	__('9','epl-sliders'),
					'10'		=>	__('10','epl-sliders'),
				),
				'default'	=>	'6'
			),

			array(
				'name'		=>	'epl_slider_thumb_width',
				'label'		=>	__('Width','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'',
				'help'		=>	__( 'Provide width in pixels, leave blank for default thumbnail size.' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_thumb_height',
				'label'		=>	__('Height','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'',
				'help'		=>	__( 'Provide height in pixels, leave blank for default thumbnail size.' , 'epl-sliders')
			),

			//  $ThumbnailNavigatorOptions.$SpacingX
			array(
				'name'		=>	'epl_slider_spacingx',
				'label'		=>	__('Horizontal spacing in pixels','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'14'
			),
			//  $ThumbnailNavigatorOptions.$SpacingY
			array(
				'name'		=>	'epl_slider_spacingy',
				'label'		=>	__('Vertical spacing in pixels','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'12'
			),
		) )
	);

	$fields[] = array(
		'label'		=>	__('Slideshow', 'epl-sliders'),
		'fields'	=>	apply_filters('epl_slider_tab_slideshow_settings',array(

			array(
				'name'		=>	'epl_slider_settings_description_slideshow_intro',
				'content'	=>	'<h3>' . __('The settings below adjust the slideshow options.','epl-sliders') . '</h3>',
				'type'		=>	'help',
			),

			// $AutoPlay
			array(
				'name'		=>	'epl_slider_slideshow',
				'label'		=>	__('Auto play slideshow?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'true'		=>	__('Enable','epl-sliders'),
					'false'		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'true',
				'help'		=>	__('Setup a slide show for the slider to animate automatically','epl-sliders')
			),

			// $AutoPlayInterval
			array(
				'name'		=>	'epl_slider_slideshowSpeed',
				'label'		=>	__('Transition speed in milliseconds','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	5000,
			),

			// $SlideshowOptions._SlideshowTransitions
			array(
				'name'		=>	'epl_slider_transition',
				'label'		=>	__('Slideshow transition effect','epl-sliders'),
				'type'		=>	'select',
				'opts'		=>	array(
					'fade_in_l'		=> __('Fade In Left','epl-sliders'),
					'fade_in_r'		=> __('Fade In Right','epl-sliders'),
					'Fade' 			=> __('Fade','epl-sliders'),
					'Fade in T' 		=> __('Fade in T','epl-sliders'),
					'Fade in B' 		=> __('Fade in B','epl-sliders'),
					'Fade in LR' 		=> __('Fade in LR','epl-sliders'),
					'Fade in LR Chess' 	=> __('Fade in LR Chess','epl-sliders'),
					'Fade in TB' 		=> __('Fade in TB','epl-sliders'),
					'Fade in TB Chess' 	=> __('Fade in TB Chess','epl-sliders'),
					'Fade in Corners' 	=> __('Fade in Corners','epl-sliders'),
					'Fade out L' 		=> __('Fade out L','epl-sliders'),
					'Fade out R' 		=> __('Fade out R','epl-sliders'),
					'Fade out T' 		=> __('Fade out T','epl-sliders'),
					'Fade out B' 		=> __('Fade out B','epl-sliders'),
					'Fade out LR' 		=> __('Fade out LR','epl-sliders'),
					'Fade out LR Chess' 	=> __('Fade out LR Chess','epl-sliders'),
					'Fade out TB' 		=> __('Fade out TB','epl-sliders'),
					'Fade out TB Chess' 	=> __('Fade out TB Chess','epl-sliders'),
					'Fade out Corners' 	=> __('Fade out Corners','epl-sliders'),
					'Fade Fly in L' 	=> __('Fade Fly in L','epl-sliders'),
					'Fade Fly in R' 	=> __('Fade Fly in R','epl-sliders'),
					'Fade Fly in T' 	=> __('Fade Fly in T','epl-sliders'),
					'Fade Fly in B' 	=> __('Fade Fly in B','epl-sliders'),
					'Fade Fly in LR' 	=> __('Fade Fly in LR','epl-sliders'),
					'Fade Fly in LR Chess'	=> __('Fade Fly in LR Chess','epl-sliders'),
					'Fade Fly in TB' 	=> __('Fade Fly in TB','epl-sliders'),
					'Fade Fly in TB Chess'	=> __('Fade Fly in TB Chess','epl-sliders'),
					'Fade Fly in Corners'	=> __('Fade Fly in Corners','epl-sliders'),
					'Fade Fly out L' 	=> __('Fade Fly out L','epl-sliders'),
					'Fade Fly out R' 	=> __('Fade Fly out R','epl-sliders'),
					'Fade Fly out TB' 	=> __('Fade Fly out TB','epl-sliders'),
					'Fade Fly out TB Chess' => __('Fade Fly out TB Chess','epl-sliders'),
					'Fade Fly out Corners'	=> __('Fade Fly out Corners','epl-sliders'),
					'Fade Clip in H' 	=> __('Fade Clip in H','epl-sliders'),
					'Fade Clip in V' 	=> __('Fade Clip in V','epl-sliders'),
					'Fade Clip out H' 	=> __('Fade Clip out H','epl-sliders'),
					'Fade Clip out V' 	=> __('Fade Clip out V','epl-sliders'),
					'Fade Stairs' 		=> __('Fade Stairs','epl-sliders'),
					'Fade Random' 		=> __('Fade Random','epl-sliders'),
					'Fade Swirl' 		=> __('Fade Swirl','epl-sliders'),
					'Fade ZigZag' 		=> __('Fade ZigZag','epl-sliders'),
					'zoom_in'		=> __('Zoom In','epl-sliders'),
					'zoom_out'		=> __('Zoom Out','epl-sliders'),
					'rotate_zoom_in'	=> __('Rotate Zoom In','epl-sliders'),
					'rotate_zoom_out'	=> __('Rotate Zoom In','epl-sliders'),
					'hdouble_zoom_in'	=> __('Hdouble Zoom In','epl-sliders'),
					'hdouble_zoom_out'	=> __('Hdouble Zoom Out','epl-sliders'),
					'rotate_zoom_in_left'	=> __('Rotate Zoom In Left','epl-sliders'),
					'rotate_zoom_out_right'	=> __('Rotate Zoom Out Right','epl-sliders'),
					'rotate_zoom_out_left'	=> __('Rotate Zoom Out Left','epl-sliders'),
					'rotate_zoom_in_right'	=> __('Rotate Zoom In Right','epl-sliders'),
					'rotate_hdouble_in'	=> __('Rotate Hdouble In','epl-sliders'),
					'rotate_hdouble_out'	=> __('Rotate Hdouble Out','epl-sliders'),
					'rotate_vfork'		=> __('Rotate Vfork','epl-sliders'),
					'rotate_hfork'		=> __('Rotate Hfork','epl-sliders')
				),
				'default'	=>	'fade_in_l'
			),

			// $PauseOnHover
			array(
				'name'		=>	'epl_slider_pauseOnHover',
				'label'		=>	__('Pause slideshow on mouse hover?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'0'		=>	__('Disable','epl-sliders'),
					'1'		=>	__('Enable','epl-sliders'),
				),
				'default'	=>	'1'
			)
		) )
	);

	$fields[] = array(
		'label'		=>	__('Mobile', 'epl-sliders'),
		'fields'	=>	apply_filters('epl_slider_tab_mobile_settings',array(

			array(
				'name'		=>	'epl_slider_settings_description_mobile_intro',
				'content'	=>	'<h3>' . __('The settings below adjust configuration options for the slider display when viewed on a mobile device.','epl-sliders') . '</h3>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_slider_thumb_on_handheld',
				'label'		=>	__('Show Thumbnails on mobile devices?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'0'		=>	__('Disable','epl-sliders'),
					'1'		=>	__('Enable','epl-sliders'),
				),
				'default'	=>	'0',
			),

			array(
				'name'		=>	'epl_slider_width_mobile',
				'label'		=>	__('Image Width','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'',
				'help'		=>	__( 'Provide width in pixels, leave blank for auto width' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_height_mobile',
				'label'		=>	__('Image Height','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	'',
				'help'		=>	__( 'Provide height in pixels, leave blank for auto height' , 'epl-sliders')
			),

			//  $DragOrientation
			array(
				'name'		=>	'epl_allow_swipe',
				'label'		=>	__('Swipe to change slider?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'0'		=>	__('Disable','epl-sliders'),
					'1'		=>	__('Horizontal','epl-sliders'),
					'2'		=>	__('Vertical','epl-sliders'),
					'3'		=>	__('Both','epl-sliders'),
				),
				'default'	=>	'3'
			)
		) )
	);

	$fields[] = array(
		'label'		=>	__('Listing Archive','epl-sliders'),
		'fields'	=>	apply_filters('epl_slider_tab_archive_settings',array(

			array(
				'name'		=>	'epl_slider_settings_description_archive_intro',
				'content'	=>	'<h3>' . __('The settings below adjust the slider display when viewing your listings archive pages.','epl-sliders') . '</h3>',
				'type'		=>	'help',
			),

			array(
				'name'		=>	'epl_slider_enable_archive',
				'label'		=>	__('Use Slider on Archives','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					1		=>	__('Enable','epl-sliders'),
					0		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	0
			),

			array(
				'name'		=>	'epl_slider_archive_image_size',
				'label'		=>	__('Image size on Archives','epl-sliders'),
				'type'		=>	'select',
				'opts'		=>	$opts_sizes,
				'default'	=>	'epl-image-medium-crop'
			),

			array(
				'name'		=>	'epl_slider_archive_wrapper_width',
				'label'		=>	__('Slider Wrapper Width','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	300,
				'help'		=>	__( 'Slider wrapper width in pixels' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_archive_wrapper_height',
				'label'		=>	__('Slider Wrapper Height','epl-sliders'),
				'type'		=>	'number',
				'default'	=>	200,
				'help'		=>	__( 'Slider wrapper height in pixels' , 'epl-sliders')
			),

			array(
				'name'		=>	'epl_slider_archive_price_sticker',
				'label'		=>	__('Show listing status sticker?','epl-sliders'),
				'type'		=>	'radio',
				'opts'		=>	array(
					'true'		=>	__('Enable','epl-sliders'),
					'false'		=>	__('Disable','epl-sliders'),
				),
				'default'	=>	'false',
			),
		) )
	);
	$fields = apply_filters('epl_slider_option_fields',$fields);
	$epl_slider_fields['fields'] = $fields;
	$epl_fields['sliders'] = $epl_slider_fields;
	return $epl_fields;
}
add_filter('epl_extensions_options_filter_new', 'epl_slider_extensions_options_filter', 10, 3);

/**
 * Admin CSS and JS loaded on editing EPL post
 *
 * @since 1.0
 */
function epl_slider_admin_css() {
	$screen = get_current_screen();
	if( is_epl_post() ) {
		echo '
		<style>
		  	#epl-slider-post-attachments { list-style-type: none; margin: 0; padding: 0; }
			#epl-slider-post-attachments li {
				margin: 3px 3px 3px 0; padding: 1px; float: left;
				text-align: center; cursor: all-scroll;
				 position: relative;
			}
			.epl-slider-unattach {
			    cursor: pointer;
			    display: inline-block;
			    font-size: 12px;
			    font-weight: bold;
			    line-height: 19px;
			    position: absolute;
			    right: -8px;
			    top : -8px;
			    z-index: 9999;
			}
			.epl-slider-upload-btn {
				display:block!important;
				margin:20px 0!important;
			}
  		</style>';
  		echo '
		<script>
  			jQuery(function($) {

				if( $("#epl-slider-post-attachments").length ) {
				  $( "#epl-slider-post-attachments" ).sortable({
					update: function (event, ui) {
						 var order="";
						$("#epl-slider-post-attachments li").each(function(i) {
							if (order=="")
								order = $(this).attr("data-id");
							else
								order += "," + $(this).attr("data-id");
						});
						console.log(order);
						$.ajax({
							data: {order :order , id : $("#post_ID").val(), action : "epl_slider_save_order" },
							type: "POST",
							url: ajaxurl,

						});
					}
				  });
				  $( "#epl-slider-post-attachments" ).disableSelection();
			  	}


  				if ($(\'input[name="epl_slider_upload_button"]\').length > 0) {
					if ( typeof wp !== \'undefined\' && wp.media && wp.media.editor) {
					    $(document).on(\'click\', \'input[name="epl_slider_upload_button"]\', function(e) {
					        e.preventDefault();
					        var button = $(this);

					        var frame = wp.media({
		                       title : "add attachments to listing",
		                       frame: "post",
		                       multiple : true,
		                       library : {
		                                    type : "image",
		                                    uploadedTo : wp.media.view.settings.post.id
		                                  },
		                       button : { text : "Done" }
		                   });

					frame.on("close",function() {
						var selection = frame.state().get("selection");
						selection.each(function(attachment) {
		                      		attachment  = attachment.attributes;
		                      		console.log(attachment);
			                      	var tpl = `
						            	<li class="ui-state-default epl_slider_admin_thumb" data-id="`+attachment.id+`">
									<span class="epl-slider-unattach">
										<div class="epl-switch">
											<input type="checkbox" value="`+attachment.id+`" checked="checked" name="epl_slider_enabled_thumbs[]" class="epl-cmn-toggle epl-cmn-toggle-round" id="epl-cmn-toggle-`+attachment.id+`">
											<label for="epl-cmn-toggle-`+attachment.id+`"></label>
										</div>
									</span>
									<img src="`+attachment.sizes.thumbnail.url+`">
								</li>
						            `;
						            $("#epl-slider-post-attachments").append(tpl);
 		                      });
		                   });

					        frame.open(button);
					        return false;
					    });
					}
				}
			});
		</script>
		';
	}
}
add_action('admin_head','epl_slider_admin_css');

/**
 * Duplicate EPL Core Function to get all post types
 *
 * @since 1.1
 */
if ( ! function_exists('epl_all_post_types')) {
	function epl_all_post_types() {
		$epl_posts  = epl_get_active_post_types();
			$epl_posts	= array_keys($epl_posts);
			return apply_filters('epl_additional_post_types',$epl_posts);
	}
}

/**
 * Add meta box for slider image management on epl posts
 *
 * @since 1.1
 */
function epl_slider_attachments_mb() {

	foreach ( epl_all_post_types() as $screen) {

		add_meta_box(
			'epl_slider_attachments',
			__( 'Slider Images - Easy Property Listings', 'epl-sliders' ),
			'epl_slider_attachments_callback',
			$screen
		);
	}
}
add_action('add_meta_boxes','epl_slider_attachments_mb');

/**
 * Slider Attachments Callback for adding, disabling and ordering images
 *
 * @since 1.1
 */
function epl_slider_attachments_callback($post) {

	global $epl_settings;
	$args = array(
		'post_parent' 		=> $post->ID,
		'post_type'   		=> 'attachment',
		'numberposts' 		=> -1,
		'post_mime_type'	=> 'image',
		'orderby'		=> 'ID'
	);

	if(get_post_meta($post->ID,'epl_slides_order',true) != '') {
		$args['post__in'] 	= explode(',', get_post_meta($post->ID,'epl_slides_order',true) );
		$args['orderby']	= 'post__in';
	}

	if ( has_post_thumbnail($post->ID) ) {
		$featured_image 	= get_post_thumbnail_id($post->ID);
		$args['exclude'] 	= $featured_image;
		$attachments 		= get_posts($args);
	} else {
		$attachments 		= get_posts($args);
	}
	$reverse_note = '';
	if( isset($epl_settings['epl_slider_reverseorder']) && $epl_settings['epl_slider_reverseorder'] == 'true' ) {
		//$attachments = array_reverse($attachments);
		$reverse_note = '<strong>' . __('Reverse Image Order Enabled' , 'epl-sliders') . '</strong>';
	}
	if ( $attachments ) {
		$enabled = (array) get_post_meta($post->ID,'epl_slider_enabled_thumbs',true);
		$enabled = array_filter($enabled);
		$content = '<ul id="epl-slider-post-attachments" class="epl-slider-post-attachments">';
		foreach ( $attachments as $attachment ) {
			$checked = 'checked=checked';

			if( !empty($enabled) )
				$checked = in_array($attachment->ID,$enabled) ? 'checked=checked' : '';

			$thumb      	 = wp_get_attachment_image_src( $attachment->ID, 'epl-image-medium-crop' );
			$content 		.= '
				<li data-id="'.$attachment->ID.'" class="ui-state-default epl_slider_admin_thumb">
					<span class="epl-slider-unattach">
						<div class="epl-switch">
				            <input id="epl-cmn-toggle-'.$attachment->ID.'" class="epl-cmn-toggle epl-cmn-toggle-round" name="epl_slider_enabled_thumbs[]" '.$checked.' value="'.$attachment->ID.'" type="checkbox">
				            <label for="epl-cmn-toggle-'.$attachment->ID.'"></label>
				          </div>
					</span>
					<img src="'.$thumb[0].'" />

				</li>';

		}
		$content .= '</ul>';
		$content .= '<div class="epl-clearfix"></div>';
		$content .= '<div class="update-nag">'.__("Drag to reorder images and use the switch to remove images from the slider. Update the post to save changes. $reverse_note",'epl-sliders').'</div>';
	} else {
		//$content = '<div class="update-nag">'.__('Add images to the slider using add media button.','epl-sliders').'</div>';
	}
	echo $content;
	echo '<input type="button" class="btn button epl-slider-upload-btn" name="epl_slider_upload_button" value="'.__('Upload Images','epl-slider').'"/>';
}

/**
 * Slider Save Image Order
 *
 * @since 1.1
 */
function epl_slider_save_order() {
	if( is_admin() ) {
		if(isset($_POST['id']) && intval($_POST['id']) > 0 ) {
			$order = sanitize_text_field($_POST['order']);
			update_post_meta(intval($_POST['id']),'epl_slides_order',$order);
		}
	}
	die($order);
}
add_action('wp_ajax_epl_slider_save_order','epl_slider_save_order');

/**
 * Slider Detach Images
 *
 * @since 2.0
 */
function epl_slider_unattach() {
	if( is_admin() ) {
		if( isset($_POST['img_id']) ) {
			wp_update_post(
				array(
					'ID'		=>	absint($_POST['img_id']),
					'post_parent'	=>	0
				)
			);
		}
	}
	die;
}
add_action('wp_ajax_epl_slider_unattach','epl_slider_unattach');

/**
 * Slider Update Image Order
 *
 * @since 2.0
 */
function epl_slider_update_order($post_ID) {
	$post 	= get_post($post_ID);
	$parent = get_post($post->post_parent);
	if( get_post_meta($parent->ID,'epl_slides_order',true) != '') {
		$order = get_post_meta($parent->ID,'epl_slides_order',true);
		update_post_meta($parent->ID,'epl_slides_order',$order.','.$post_ID);
	}

}
add_action('add_attachment','epl_slider_update_order');

/**
 * Admin CSS Styles
 *
 * @since 2.0
 */
function epl_slider_admin_styles() {
	wp_enqueue_style('epl-slider-admin-style', EPL_SLIDER_PLUGIN_URL.'/css/style-admin.css' , __FILE__ , EPL_SLIDER_VERSION );
}
add_action('admin_enqueue_scripts','epl_slider_admin_styles');

/**
 * Save Sliders
 *
 * @since 2.0
 */
function epl_slider_save_hook_callback() {
	if( is_epl_post() ) {
		if( !empty($_POST['epl_slider_enabled_thumbs']) ) {
			update_post_meta($_POST['ID'],'epl_slider_enabled_thumbs',$_POST['epl_slider_enabled_thumbs']);
		}
	}
}
add_action('save_post','epl_slider_save_hook_callback');
