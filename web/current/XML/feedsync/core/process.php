<?php
/*
 * Title: FeedSync REAXML Pre-processor
 * Program URI: https://www.easypropertylistings.com.au
 * Description: Program created and written to process Australian REAXML feed for easy import into WordPress.
 * The program will process the input files and store them in a database on your server. The output is generated on the fly as requested by your import software.
 * Author: Merv Barrett
 * Author URI: http://realestateconnected.com.au/
 * Version: 2.2
 *
 * Copyright 2016 Merv Barrett
 *
 * FeedSync is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once('../config.php');
require_once('functions.php');
require_once(CORE_PATH.'classes/class-chunks.php');
require_once(CORE_PATH.'classes/class-zip.php');
require_once(CORE_PATH.'classes/class-feedsync-setup-preprocessor.php');
$feedsync_hook->do_action('init');
global $feedsync_db;
set_time_limit(0);


new FEEDSYNC_SETUP_PROCESSOR();

get_header('process'); ?>

<?php if ( get_option('force_geocode') == 'on' ) { ?>
	<div class="alert bg-warning">
		<p>Force Geocode is set to ON, once you have re-processed your coordinates, set to OFF in the Settings.</p>
	</div>
<?php } ?>


<div class="panel panel-default">
	<div class="panel-heading">
		<input type="button" id="import_listings" value="Process" class="btn btn-primary">
		<?php feedsync_manual_processing_buttons(); ?>
	</div>
	<div class="alert alert-success">
		<p>Click on process to start processing files.</p>
	</div>
</div>

<?php echo get_footer(); ?>

