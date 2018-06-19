<?php
/**
 * Template for Tabs
 *
 * @package     EPL-LOCATION-PROFILES
 * @subpackage  Template/Tabs
 * @copyright   Copyright (c) 2016, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
?>

<div id="epl-box" class="epl-location-profiles-box location-profiles-box-outer-wrapper epl-clearfix">
	<h3><?php the_title() ?></h3>
	<div class="location-profiles-tabs-left epl-location-profiles-list">
		<?php epl_lp_tab_box_menu_items() ?>
	</div>
	<div class="location-profiles-tabs-left epl-location-profiles-content">
		<?php epl_lp_tab_box_content() ?>
	</div>

</div>
