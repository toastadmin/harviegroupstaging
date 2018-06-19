<?php
/**
 * Widget EPL - Staff Directory Author
 *
 * @package     EPL-STAFF-DIRECTORY
 * @subpackage  Functions/Widget
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register widget
 *
 * @since 1.0
 */
class EPL_Widget_Staff_Directory extends WP_Widget {
	function __construct() {
		parent::__construct(
			false,
			__('EPL - Staff Directory Author', 'epl-staff-directory'),
			array( 'description' => __( 'Display Staff Author', 'epl-staff-directory' ) )
		);
	}
	function widget($args, $instance) {

		$defaults = array(
			'title'			=>	'',
			'display'		=>	0,
			'image'			=>	'thumbnail',
			'd_image'		=>	0,
			'd_icons'		=>	0,
			'd_bio'			=>	0,
			'd_vcard'		=>	0,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']);
		$display	= $instance['display'];
		$image		= $instance['image'];
		$d_image	= $instance['d_image'];
		$d_icons	= $instance['d_icons'];
		$d_bio		= $instance['d_bio'];
		$d_vcard	= $instance['d_vcard'];

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		epl_sd_author_widget( $d_image , $image , $d_icons , $d_bio, $d_vcard);
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 	= strip_tags($new_instance['title']);
		$instance['display'] 	= strip_tags($new_instance['display']);
		$instance['image'] 	= strip_tags($new_instance['image']);
		$instance['d_image'] 	= strip_tags($new_instance['d_image']);
		$instance['d_icons'] 	= strip_tags($new_instance['d_icons']);
		$instance['d_bio'] 	= strip_tags($new_instance['d_bio']);
		$instance['d_vcard'] 	= strip_tags($new_instance['d_vcard']);
		return $instance;
	}
	function form($instance) {
		$defaults = array(
			'title'		=>	'',
			'display'	=>	0,
			'image'		=>	'thumbnail',
			'd_image'	=>	0,
			'd_icons'	=>	0,
			'd_bio'		=>	0,
			'd_vcard'	=>	0,
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		$title 		= esc_attr($instance['title']);
		$display	= esc_attr($instance['display']);
		$image		= esc_attr($instance['image']);
		$d_image	= esc_attr($instance['d_image']);
		$d_icons	= esc_attr($instance['d_icons']);
		$d_bio		= esc_attr($instance['d_bio']);
		$d_vcard	= esc_attr($instance['d_vcard']);
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'epl-staff-directory'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image Size', 'epl-staff-directory'); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>">
				<?php
					$sizes = epl_get_thumbnail_sizes();
					foreach ($sizes as $k=>$v) {
						$v = implode(" x ", $v);
						echo '<option class="widefat" value="' . $k . '" id="' . $k . '"', $instance['image'] == $k ? ' selected="selected"' : '', '>', __($k, 'epl-staff-directory') . ' (' . __($v, 'epl-staff-directory') . ' )', '</option>';
					}
				?>
			</select>
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('d_image'); ?>" name="<?php echo $this->get_field_name('d_image'); ?>" <?php if ($instance['d_image']) echo 'checked="checked"' ?> />
			<label for="<?php echo $this->get_field_id('d_image'); ?>"><?php _e('Display Author Image', 'epl-staff-directory'); ?></label>
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('d_icons'); ?>" name="<?php echo $this->get_field_name('d_icons'); ?>" <?php if ($instance['d_icons']) echo 'checked="checked"' ?> />
			<label for="<?php echo $this->get_field_id('d_icons'); ?>"><?php _e('Display Icons', 'epl-staff-directory'); ?></label>
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('d_bio'); ?>" name="<?php echo $this->get_field_name('d_bio'); ?>" <?php if ($instance['d_bio']) echo 'checked="checked"' ?> />
			<label for="<?php echo $this->get_field_id('d_bio'); ?>"><?php _e('Display Bio', 'epl-staff-directory'); ?></label>
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('d_vcard'); ?>" name="<?php echo $this->get_field_name('d_vcard'); ?>" <?php if ($instance['d_vcard']) echo 'checked="checked"' ?> />
			<label for="<?php echo $this->get_field_id('d_vcard'); ?>"><?php _e('Display Vcard', 'epl-staff-directory'); ?></label>
		</p>

		<?php
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("EPL_Widget_Staff_Directory");') );
