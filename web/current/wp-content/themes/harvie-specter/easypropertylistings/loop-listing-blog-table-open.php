<?php
/**
 * Loop Property Template: Table Open
 *
 * @package     EPL
 * @subpackage  Templates/Content
 * @copyright   Copyright (c) 2015, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
global $property;
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('epl-listing-post epl-property-blog epl-property-table epl-table epl-table-open epl-clearfix epl-desktop'); ?>  onclick="location.href='<?php the_permalink(); ?>';">
<div class="epl_open_homes_tbl">
	<?php do_action('epl_property_before_content'); ?>		
		<div class="epl_open_homes_col1">
			<?php do_action('epl_property_inspection_times'); ?>
		</div>

		<div class="epl_open_homes_col2">
			<?php do_action('epl_property_address'); ?>		
		</div>

		<div class="epl_open_homes_col3">
			<?php do_action('epl_property_price'); ?>
		</div>

		<div class="epl_open_homes_col4">
			<?php do_action('epl_property_bed'); ?>
		</div>

		<div class="epl_open_homes_col5">
			<?php do_action('epl_property_category'); ?>
		</div>

		<div class="epl_open_homes_col6">
			<?php the_author_meta('display_name', $epl_author->author_id); ?>              
		</div>		
	<?php do_action('epl_property_after_content'); ?>
</div>
</div>