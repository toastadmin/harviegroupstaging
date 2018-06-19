<?php
/**
 * Slider CSS
 *
 * @package     EPL-SLIDERS
 * @subpackage  Classes/CSS
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class EPL_SLIDER_CSS {

	private static $instance;

	/**
	 * constructor.
	 *
	 * @since 1.0.0
	 */
	function EPL_SLIDER_CSS ($slider) {
		$this->slider = $slider;
	}

	/**
	 * Ensure only one instance of this class is running
	 *
	 * @since 1.0.0
	 */
	public static function get_instance($slider) {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof EPL_SLIDER_CSS ) ) {
			self::$instance = new EPL_SLIDER_CSS($slider);
	}

	return self::$instance;

	}

	/**
	 * Get Slider Option
	 *
	 * @since 1.0.0
	 */
	function slide_get_option($key) {
		return	$this->slider->slide_get_option($key);
	}

	/**
	 * Right Hand Thumbnails
	 *
	 * @since 1.0.0
	 */
	function thumbs_on_right() {
		global $post,$property,$epl_settings;

		if( is_null($post) )
			return;

		/** calculate dynamic attributes **/
		if(wp_is_mobile()) {
			$slider_height 			= $this->slide_get_option('epl_slider_height_mobile');
			$slider_width 			= $this->slide_get_option('epl_slider_width_mobile');

		} else {
			$slider_height 			= $this->slide_get_option('epl_slider_height');
			$slider_width 			= $this->slide_get_option('epl_slider_width');
		}
		$slider_thumb_height 			= $this->slide_get_option('epl_slider_thumb_height');
		$slider_thumb_width 			= $this->slide_get_option('epl_slider_thumb_width');
		$lanes					= $this->slide_get_option('epl_slider_thumb_lanes');
		$thumbcontainerw			= $this->slide_get_option('epl_slider_thumb_lanes') * $this->slide_get_option('epl_slider_thumb_width');
		$thumbcontainerw			= $thumbcontainerw + 3 * $this->slide_get_option('epl_slider_spacingx')	;
		/** calculate dynamic attributes **/

		ob_start();
		$this->common();
		?>
		<style>
			.epl_slider_container {
				position: relative; top: 0px; right: 0px; width: <?php echo $slider_width; ?>px;
				height: <?php echo $slider_height; ?>px; overflow: hidden;
			}

			.epl-slider-slides {
				cursor: move; position: absolute; right: <?php echo $thumbcontainerw;?>px; top: 0px; width: <?php echo $slider_width; ?>px;
				height: <?php echo $slider_height; ?>px; overflow: hidden;
			}
			.epl-slider-left-nav, .epl-slider-right-nav {
				display: block;
				position: absolute;
				/* size of arrow element */
				width: 50px;
				height: 50px;
				cursor: pointer;
				background: url(<?php echo EPL_SLIDER_PLUGIN_URL ?>/img/arrows/<?php echo $this->slide_get_option('epl_slider_arrow_style')?>) no-repeat;
				overflow: hidden;
			}

			.epl-slider-left-nav { background-position: -5px -35px; top: 158px; left: 8px; }
			.epl-slider-right-nav { background-position: -65px -35px; top: 158px; right: <?php echo $thumbcontainerw + 10;?>px }
			.epl-slider-left-nav:hover { background-position: -125px -35px; }
			.epl-slider-right-nav:hover { background-position: -185px -35px; }
			.epl-slider-left-nav.epl-slider-left-navdn { background-position: -245px -35px; }
			.epl-slider-right-nav.epl-slider-right-navdn { background-position: -305px -35px; }

			.epl-slider-thumb-container {
				position: absolute;
				/* size of thumbnail navigator container */
				width: <?php echo $thumbcontainerw;?>px;
				height: <?php echo 2*$thumbcontainerw;?>px;
				right:0;
			}

			.epl-slider-thumb-container .p {
				position: absolute;
				top: 0;
				right: 0;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
			}

			.epl-slider-thumb-container .t {
				position: absolute;
				top: 0;
				right: 0;
				width: 100%;
				height: 100%;
				border: none;
			}

			.epl-slider-thumb-container .w {
				position: absolute;
				top: 0px;
				right: 0px;
				width: 100%;
				height: 100%;
			}

			.epl-slider-thumb-container .c {
				position: absolute;
				top: 0px;
				right: 0px;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				box-sizing: content-box;
				background: url(<?php echo EPL_SLIDER_PLUGIN_URL ?>img/t01.png) -800px -800px no-repeat;
				_background: none;
			}

			.epl-slider-thumb-container .pav .c {
				top: 2px;
				_top: 0px;
				right: 2px;
				_left: 0px;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				background-position: 50% 50%;
			}

			.epl-slider-thumb-container .p:hover .c {
				top: 0px;
				right: 0px;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				border: #fff 1px solid;
				background-position: 50% 50%;
			}

			.epl-slider-thumb-container .p.pdn .c {
				background-position: 50% 50%;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				border: #000 2px solid;
			}

			* html .epl-slider-thumb-container .c, * html .epl-slider-thumb-container .pdn .c, * html .epl-slider-thumb-container .pav .c {
				/* ie quirks mode adjust */
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
			}

			.epl-slider-slides img{
				margin: 0;
				padding:0;
			}
			.epl-slider-thumb-container img{
				margin: 0;
				padding:0;
			}
		</style><?php

		return ob_get_clean();
	}

	/**
	 * Horizontal Thumbnails
	 *
	 * @since 1.0.0
	 */
	function horizontal_thumbnails() {
		global $post,$property,$epl_settings;

		if( is_null($post) )
			return;

		/** calculate dynamic attributes **/
		if(wp_is_mobile()) {
			$slider_height 			= $this->slide_get_option('epl_slider_height_mobile');
			$slider_width 			= $this->slide_get_option('epl_slider_width_mobile');

		} else {
			$slider_height 			= $this->slide_get_option('epl_slider_height');
			$slider_width 			= $this->slide_get_option('epl_slider_width');
		}
		$slider_thumb_height 		= $this->slide_get_option('epl_slider_thumb_height');
		$slider_thumb_width 		= $this->slide_get_option('epl_slider_thumb_width');
		$lanes						= $this->slide_get_option('epl_slider_thumb_lanes');
		$thumbcontainerw			= $this->slide_get_option('epl_slider_thumb_lanes') * $slider_thumb_height;
		$spacing_factor 			= $this->slide_get_option('epl_slider_thumb_lanes') + 1;
		$thumbcontainerw			= $thumbcontainerw + $spacing_factor * $this->slide_get_option('epl_slider_spacingx')	;

		/**add to slider height if thumbs & on & orientation is horizontal **/
		if($this->slide_get_option('epl_slider_use_thumbnails') == 2 && $this->slide_get_option('epl_slider_thumb_orientation') == 1) {

			if(!$this->slider->thumbs_on_mobile() )
				$slider_height = $slider_height + $thumbcontainerw;
		}

		/** calculate dynamic attributes **/

		ob_start();
		$this->common();
		?>
		<style>
			.epl_slider_container {
				position: relative; width: <?php echo $slider_width; ?>px;
				height: <?php echo $slider_height; ?>px; overflow: hidden;
			}

			.epl-slider-slides {
				cursor: move; position: absolute; left: 0px; top: 0px; width: <?php echo $slider_width; ?>px;
				height: <?php echo $slider_height; ?>px; overflow: hidden;
			}
			.epl-slider-left-nav, .epl-slider-right-nav {
				display: block;
				position: absolute;
				/* size of arrow element */
				width: 50px;
				height: 50px;
				cursor: pointer;
				background: url(<?php echo EPL_SLIDER_PLUGIN_URL ?>/img/arrows/<?php echo $this->slide_get_option('epl_slider_arrow_style')?>) no-repeat;
				overflow: hidden;
			}

			.epl-slider-left-nav { background-position: -5px -35px; top: 158px; left: 8px; }
			.epl-slider-right-nav { background-position: -65px -35px; top: 158px; right: 8px }
			.epl-slider-left-nav:hover { background-position: -125px -35px; }
			.epl-slider-right-nav:hover { background-position: -185px -35px; }
			.epl-slider-left-nav.epl-slider-left-navdn { background-position: -245px -35px; }
			.epl-slider-right-nav.epl-slider-right-navdn { background-position: -305px -35px; }

			.epl-slider-thumb-container {
				position: absolute;
				/* size of thumbnail navigator container */
				width: <?php echo $slider_width;?>px;
				height: <?php echo $thumbcontainerw;?>px;
				left:0;
				bottom:0;
			}

			.epl-slider-thumb-container .p {
				position: absolute;
				top: 0;
				right: 0;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
			}

			.epl-slider-thumb-container .t {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				border: none;
			}

			.epl-slider-thumb-container .w {
				position: absolute;
				top: 0px;
				left: 0px;
				width: 100%;
				height: 100%;
			}

			.epl-slider-thumb-container .c {
				position: absolute;
				top: 0px;
				left: 0px;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				box-sizing: content-box;
				background: url(<?php echo EPL_SLIDER_PLUGIN_URL ?>img/t01.png) -800px -800px no-repeat;
				_background: none;
			}

			.epl-slider-thumb-container .pav .c {
				top: 2px;
				_top: 0px;
				left: 2px;
				_left: 0px;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				background-position: 50% 50%;
			}

			.epl-slider-thumb-container .p:hover .c {
				top: 0px;
				left: 0px;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				border: #fff 1px solid;
				background-position: 50% 50%;
			}

			.epl-slider-thumb-container .p.pdn .c {
				background-position: 50% 50%;
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
				border: #000 2px solid;
			}

			* html .epl-slider-thumb-container .c, * html .epl-slider-thumb-container .pdn .c, * html .epl-slider-thumb-container .pav .c {
				/* ie quirks mode adjust */
				width: <?php echo $this->slide_get_option('epl_slider_thumb_width')?>px;
				height: <?php echo $this->slide_get_option('epl_slider_thumb_height')?>px;
			}

			.epl-slider-slides img{
				margin: 0;
				padding:0;
			}
			.epl-slider-thumb-container img{
				margin: 0;
				padding:0;
			}
		</style><?php
		return ob_get_clean();
	}

	function common() { ?>
		
		<style>
			body .epl-property-blog {
			    -webkit-transition: none;
			    -moz-transition: none;
			    -o-transition: none;
			    transition: none;
			}
			body .epl-property-blog.epl-listing-grid-view {
			    -webkit-transition: none;
			    -moz-transition: none;
			    -o-transition: none;
			    transition: none;
			}
		</style> <?php
	}
}