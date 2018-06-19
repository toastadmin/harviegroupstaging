<?php
/*

Title: XML Feed Processor Help Page
Program Author URI: http://realestateconnected.com.au/
Description: Program created and written to process Australian REAXML feed for easy import into WordPress.
The program will process the input files that are places in the XML directory from your feed provider and save the results into
three XML output files in the /feedsync/outputs directory. These files contain the results of the input files.

Author: Merv Barrett
Author URI: http://realestateconnected.com.au/

Version: 2.0

Version History
	See history.txt
*/
	require_once('../../config.php');
	require_once('../functions.php');

	global $feedsync_db;
	$page_now = 'geocode';
	do_action('init');
	$input_addr = '1600 Amphitheatre Parkway, Mountain View, CA';
	$addr = $input_addr;
	$addr = str_replace(" ", "+", $addr);
	$geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$addr&components=country:AU&sensor=false");
	$output = json_decode($geocode);
	$result = (array) json_decode($geocode);
	get_header('geocode');
?>
		<div class="page-header">
			<h1>Geocode Status</h1>
		</div>

		<h3 style="margin-top:2em;">Test Geocode Credit Status</h3>

		<p>FeedSync uses the <a href="https://developers.google.com/maps/documentation/geocoding/">Google Geocoding API</a> to convert the property addresses in your XML file during import into lat/long coordinates so your website can display the address with a map. <em>NOTE: Google has usage limits of 2,500 requests per day and you can check the status with the button below.</em></p>

		<p>To allow geocoding to function correctly please create a <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Google Maps API Key</a> and enter it on the <a href="<?php echo feedsync_nav_link('../settings.php') ?>">Settings page</a>.</p>


		<h4>Processing Address</h4>
		<p><?php echo $input_addr; ?></p>


			<div class="button-margin" style="margin: 2em 0;">
			<?php
			if ($result['status'] == 'OK') { ?>

					<button type="button" class="btn btn-lg btn-success">Geocode Successful!</button>

			<?php
			} else { ?>
				<button type="button" class="btn btn-lg btn-warning">Warning :( <?php echo $result['status']; ?></button>
			<?php
			}
			?>
			</div>
<?php echo get_footer(); ?>
