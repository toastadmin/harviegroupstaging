<?php
require_once('../../config.php');
require_once('../functions.php');
do_action('init');
global $feedsync_db;

$type = '';
$status = 'sold';

$results = feedsync_list_listing_type( $type , $status );
$page = 'sold';

echo display_export_data($results , $page );

