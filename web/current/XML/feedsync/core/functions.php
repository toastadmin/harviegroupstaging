<?php
require_once('classes/class-feedsync-error.php');
require_once('classes/class-hook.php');
require_once('classes/class-logger.php');
include_once('constants.php');
require_once('classes/class-validations.php');

global $feedsync_validations,$sitewide_notices,$feedsync_logger,$feedsync_mailer;

/**
 * Use this for debugging purposes. availability sitewide
 * @var PHPLogger
 *
 * Example :
 *
 * global $feedsync_logger;
 * $feedsync_logger->i('key','description' );
 *
 */
$feedsync_logger = new PHPLogger(SITE_ROOT.'feedsync.log');

$feedsync_validations = new GUMP();
$sitewide_notices = array();

function init_session() {
    @session_start();
}

add_action('init_session','init_session',21);
do_action('init_session');

/**
 * Autoload classes whereever required
 * @param  [type] $classname Class to be instantiated
 */
function __autoload($classname) {

    /** @var file name for class */
    $filename = 'class-'.str_replace( '_','-',strtolower($classname) ).'.php';

    if( file_exists(CORE_PATH.'classes/'.$filename) )
        include_once(CORE_PATH.'classes/'.$filename);

    /** @var file name for api file */
    $filename = str_replace( '_api','',strtolower($classname) ).'.php';
    if( file_exists(CORE_PATH.'api/'.$filename) )
        include_once(CORE_PATH.'api/'.$filename);

}

/** only purpose of this function is to provide easy migration to wordpress */
function __($str,$text_domain='') {
    return $str;
}

/** only purpose of this function is to provide easy migration to wordpress */
function _e($str,$text_domain='') {
    echo __($str,$text_domain);
}

function apply_filters($tag,$value){
    global $feedsync_hook;
    return $feedsync_hook->apply_filters($tag,$value);
}

function add_filter($tag,$callback,$priority=10,$accepted_args=1){
    global $feedsync_hook;
    $feedsync_hook->add_filter($tag,$callback,$priority,$accepted_args);
}

function do_action($tag){
    global $feedsync_hook;
    $feedsync_hook->do_action($tag);
}

function add_action($tag,$callback,$priority=10,$accepted_args=1){
    global $feedsync_hook;
    $feedsync_hook->add_action($tag,$callback,$priority,$accepted_args);
}

/**
 * creates and exposes global $feedsync_options
 * used by get_option function to retrives single values of option
 * @return [type] [description]
 */
function instantiate_options() {
    global $feedsync_db,$feedsync_options;
    $options = $feedsync_db->get_results("SELECT * FROM feedsync_options");
    if( !empty($options) ) {
        foreach($options as $option) {
            $value = is_serialized( $option->option_value ) ? unserialize( $option->option_value ) : $option->option_value;
            $feedsync_options[$option->option_name] = $value;
        }
    }
}

add_action('init_options','instantiate_options',1);


$current_version = '3.2.1';

$application_name = 'FeedSync REAXML Processor';

include_once('eac-functions.php');

include_once('setup-functions.php');

include_once('classes/class-feedsync-mailer.php');

include_once('classes/class-feedsync-error-handler.php');

include_once('export-functions.php');

include_once('license-functions.php');

do_action('after_functions_include');

/*
** handle form submission
*/
if( isset($_REQUEST['action']) ) {
    date_default_timezone_set(get_option('feedsync_timezone'));
    do_action('feedsync_form_'.$_REQUEST['action']);
}



function get_header($page_now='') {
    include_once(CORE_PATH.'header.php');
}

function get_footer() {
    include_once(CORE_PATH.'footer.php');
}

function home() {
    include_once(CORE_PATH.'home.php');
}

// Jumbotron Processor Button
function feedsync_description_jumbotron() { ?>
    <img src="<?php echo CORE_URL.'images/feedsync-icon.png' ?>" width="128" height="128" />
    <h1>FeedSync</h1>
    <p class="lead">If you have XML files below waiting to be processed you can manually process them to test your FeedSync settings. Once you successfully process your xml files manually, you can set a timed schedule on your server via a simple <a href="<?php echo CORE_URL.'pages/help.php' ?>#cron">cron</a> command that will process your xml files regularly.</p>
    <p><a class="btn btn-primary btn-lg" href="core/process.php" role="button">Process Feed</a></p> <?php
}

// Jumbotron login box
function feedsync_login_jumbotron() { ?>
    <img src="<?php echo CORE_URL.'images/feedsync-icon.png' ?>" width="128" height="128" />
    <h1>FeedSync</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <input type="hidden" name="action" value="admin_login" />
                                <input type="submit" name="login_submit" class="btn btn-lg btn-success btn-block" value="Login" />
                            </fieldset>
                        </form>
                    </div>
                </div>
                <?php sitewide_notices(); ?>
            </div>
        </div>
    </div>

<?php
}

function get_files_list($folder,$pattern) {

    $pattern    = "/^.*\.(".$pattern.")$/";
    $dir        = new DirectoryIterator($folder);
    $ite        = new IteratorIterator($dir);
    $files      = new RegexIterator($ite, $pattern);
    $fileList = array();
    foreach($files as $file) {
        $fileList[] = $file->getpathname();
    }
      return $fileList;

}

function get_recursive_files_list($folder,$pattern) {

    $pattern    = "/^.*\.(".$pattern.")$/";
    $dir        = new RecursiveDirectoryIterator($folder);
    $ite        = new RecursiveIteratorIterator($dir);
    $files      = new RegexIterator($ite, $pattern);
    $fileList = array();
    foreach($files as $file) {
        $fileList[] = $file->getpathname();
    }
    return $fileList;

}

function get_sub_path() {

    $feedtype = get_option('feedtype');
    $path = DS;
    switch($feedtype) {

        case 'blm' :
        case 'reaxml' :
        case 'expert_agent' :
        case 'rockend' :
        case 'jupix' :
        case 'mls' :
           $path = '';
        break;

    }
    return $path;
}

function get_path($folder) {
    $sub_path = get_sub_path();

    switch($folder) {

        case 'input' :
            $path =  INPUT_PATH.$sub_path;
        break;

        case 'output' :
            $path =  OUTPUT_PATH.$sub_path;
        break;

        case 'processed' :
            $path =  PROCESSED_PATH.$sub_path;
        break;

        case 'temp' :
            $path =  TEMP_PATH.$sub_path;
        break;

        case 'zip' :
            $path =  ZIP_PATH.$sub_path;
        break;

        case 'images' :
            $path =  IMAGES_PATH.$sub_path;
        break;

        case 'upgrade' :
            $path =  UPGRADE_PATH;
        break;

        case 'logs' :
            $path =  LOGS_FOLDER_PATH;
        break;
    }

    return $path;
}

function get_url($folder) {
    $sub_path = get_sub_path();

    switch($folder) {

        case 'input' :
            $path =  INPUT_URL.$sub_path;
        break;

        case 'output' :
            $path =  OUTPUT_URL.$sub_path;
        break;

        case 'procesessed' :
            $path =  PROCESSED_URL.$sub_path;
        break;

        case 'temp' :
            $path =  TEMP_URL.$sub_path;
        break;

        case 'zip' :
            $path =  ZIP_URL.$sub_path;
        break;

        case 'images' :
            $path =  IMAGES_URL.$sub_path;
        break;

        case 'logs' :
            $path =  LOGS_FOLDER_URL;
        break;

    }
    return $path;
}

function get_input_xml() {
    $folder     = get_path('input');
    $pattern    = "xml|XML|zip|ZIP|blm|BLM";
    $files =  get_files_list($folder,$pattern);
    sort($files);
    return $files;
}

function get_output_xml() {
    return get_files_list(get_path('output'),"xml|XML");
}

function get_processed_xml() {
    return get_files_list(get_path('processed'),"xml|XML");
}

function feedsync_format_date( $date ) {
    // supress any timezone related notice/warning;
    error_reporting(0);
    $date_example = '2014-07-22-16:45:56';

    $tempdate = explode('-',$date);
    $date = $tempdate[0].'-'.$tempdate[1].'-'.$tempdate[2].' '.$tempdate[3];
    return  date("Y-m-d H:i:s",strtotime($date));
}

function feedsync_format_sold_date( $date ) {
    // supress any timezone related notice/warning;
    error_reporting(0);
    $date_example = '2014-07-22-16:45:56';

    $tempdate = explode('-',$date);
    $date = $tempdate[0].'-'.$tempdate[1].'-'.$tempdate[2];
    return  $date;
}

function get_listings_sub_header($page_now='') {
    include_once(CORE_PATH.'sub-pages/listings-sub-header.php');
}


function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
}

function sanitize_user_name( $title) {
    $title = strip_tags($title);
    // Preserve escaped octets.
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    $title = str_replace('%', '', $title);
    // Restore octets.
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    $title = str_replace('.', '-', $title);

    // Convert nbsp, ndash and mdash to hyphens
    $title = str_replace( array( '%c2%a0', '%e2%80%93', '%e2%80%94' ), '-', $title );

    // Strip these characters entirely
    $title = str_replace( array(
        // iexcl and iquest
        '%c2%a1', '%c2%bf',
        // angle quotes
        '%c2%ab', '%c2%bb', '%e2%80%b9', '%e2%80%ba',
        // curly quotes
        '%e2%80%98', '%e2%80%99', '%e2%80%9c', '%e2%80%9d',
        '%e2%80%9a', '%e2%80%9b', '%e2%80%9e', '%e2%80%9f',
        // copy, reg, deg, hellip and trade
        '%c2%a9', '%c2%ae', '%c2%b0', '%e2%80%a6', '%e2%84%a2',
        // acute accents
        '%c2%b4', '%cb%8a', '%cc%81', '%cd%81',
        // grave accent, macron, caron
        '%cc%80', '%cc%84', '%cc%8c',
    ), '', $title );

    // Convert times to x
    $title = str_replace( '%c3%97', 'x', $title );

    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');

    return $title;
}

function import_listings($cron_mode = false,$args = array() ) {

    /** if user processes feed but DB is not updated, do it prior to processing */
    if( feedsync_upgrade_required() ){

        $_REQUEST['action'] = 'database_upgrade'; // to prevent processor class from logging / resulting in error
        
        $fu = new feedsync_upgrade();
        $fu->dispatch();

        die(
            json_encode(
                array(
                    'status'    =>  'success',
                    'message'   =>  'Database upgrade Process Complete!, Feed processing will follow',
                    'buffer'    =>  'processing'
                )
            )
        );
    }

    $feedtype = get_option('feedtype');

    switch($feedtype) {

        case 'blm' :
            $rex = new BLM_PROCESSOR($cron_mode);
            $rex->import();
        break;

        case 'reaxml' :
            $rex = new REAXML_PROCESSOR($cron_mode);
            $rex->import();
        break;

        case 'expert_agent' :
            $rex = new Expert_Agent_PROCESSOR($cron_mode);
            $rex->import();
        break;

        case 'eac' :
            $rex = new EAC_API($cron_mode);
            $rex->process($args);

        break;

        case 'rockend' :
            $rex = new ROCKEND_PROCESSOR($cron_mode);
            $rex->import();
        break;

        case 'jupix' :
            $rex = new JUPIX_PROCESSOR($cron_mode);
            $rex->import();
        break;

        case 'mls' :
            $rex = new MLS_PROCESSOR($cron_mode);
            $rex->import();
        break;
    }

}
add_action('ajax_import_listings','import_listings');

// Navigation Settings
function feedsync_settings_navigation( $page ) {
?>
<div id="feedsync-settings-navigation">
    <ul class="nav nav-pills">

        <li<?php if ( $page == "Updates")
    echo " class=\"active\""; ?>>
            <a href="<?php echo CORE_URL;?>pages/updates.php">Updates</a>

        <li<?php if ( $page == "License")
        echo " class=\"active\""; ?>>
            <a href="<?php echo CORE_URL;?>pages/license.php">Status</a>

        <li<?php if ( $page == "Activate")
            echo " class=\"active\""; ?>>
            <a href="<?php echo CORE_URL;?>/pages/activate.php">Activate</a>

    </ul>
</div>
<?php
}

function process_missing_coordinates() {
    $feedtype = get_option('feedtype');
    switch($feedtype) {

        case 'blm' :
            $rex = new BLM_PROCESSOR();
            $rex->process_missing_geocode();
        break;
        case 'reaxml' :
            $rex = new REAXML_PROCESSOR();
            $rex->process_missing_geocode();
        break;
        case 'expert_agent' :
            $rex = new Expert_Agent_PROCESSOR();
            $rex->process_missing_geocode();
        break;
        case 'eac' :
             $rex = new EAC_API(false);
            $rex->process_missing_geocode();
        break;
        case 'rockend' :
            $rex = new ROCKEND_PROCESSOR();
            $rex->process_missing_geocode();
        break;
        case 'jupix' :
            $rex = new JUPIX_PROCESSOR();
            $rex->process_missing_geocode();
        break;

        case 'mls' :
            $rex = new MLS_PROCESSOR();
            $rex->process_missing_geocode();
        break;
    }
}

$feedsync_hook->add_action('ajax_process_missing_coordinates','process_missing_coordinates');

function process_missing_listing_agents() {

    $feedtype = get_option('feedtype');
    switch($feedtype) {

        case 'reaxml' :
            $rex = new REAXML_PROCESSOR();
            $rex->process_missing_listing_agents();
        break;

    }

}

$feedsync_hook->add_action('ajax_process_missing_listing_agents','process_missing_listing_agents');

function convert_blm_to_xml() {
    include_once(CORE_PATH.'classes/class-bml-parser.php');
}

function is_user_logged_in() {

    return isset($_SESSION['uid']) ? true : false;
}

$feedsync_hook->add_action('init','restrict_access',30);
function restrict_access() {

    if( !is_user_logged_in() ) {
        header('Location: '.SITE_URL.'core/login.php');
        die;
    }
    global $page_now;

    $settings = array('settings');

    $help = array('help','info','license','geocode','updates','activate');

    if( defined('FEEDSYNC_SETTINGS_DISABLED') && FEEDSYNC_SETTINGS_DISABLED == true && in_array($page_now,$settings) ) {
        header('Location: '.SITE_URL);
        die;
    }

    if( defined('FEEDSYNC_SETTINGS_DISABLED') && FEEDSYNC_SETTINGS_DISABLED == true && in_array($page_now,$help) ) {
        header('Location: '.SITE_URL);
        die;
    }

}
function startsWith($haystack, $needle){
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function endsWith($haystack, $needle){
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function reset_feedsync_table() {

    if($_POST['pass'] != FEEDSYNC_PASS)
        die( json_encode(array( 'status'    =>  'danger', 'message'   =>  'incorrect password')) );

    global $feedsync_db;
    $status_feedsync    =  $feedsync_db->query("TRUNCATE TABLE feedsync");
    $status_temp        =  $feedsync_db->query("TRUNCATE TABLE feedsync_temp");
    $status_users       =  $feedsync_db->query("TRUNCATE TABLE feedsync_users");
    die(json_encode(array( 'status'    =>  'success', 'message'   =>  'All listings in the FeedSync database have been removed.')));
}

add_action('ajax_reset_feedsync_table','reset_feedsync_table');

function get_site_url() {
    return get_option('site_url');
}


function feedsync_js_vars() {

    $ajax_url = get_site_url().'core/ajax.php';

    if( is_permalinks_enabled() ) {

        $ajax_url = get_site_url().'ajax';

    }
    $vars = array(
        'ajax_url'  =>  $ajax_url
    );

    echo '<script> var fs = '.json_encode($vars).'</script>';
}

add_action('feedsync_head','feedsync_js_vars');

function rrmdir($dir) {
    $ds = DIRECTORY_SEPARATOR;
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (is_dir($dir.$ds.$object))
           rrmdir($dir.$ds.$object);
         else
           unlink($dir.$ds.$object);
       }
     }
     rmdir($dir);
   }
 }

function feedsync_update_version() {

    $step = $_POST['step'];

    if($step == '')
        return;

    $dev_mode = defined('FEEDSYNC_UPDATE_MODE') ? FEEDSYNC_UPDATE_MODE : '';

    if($dev_mode == 'dev') {
        $fu = new feedsync_updater_dev();
    } else {
        $fu = new feedsync_updater();
    }

    

    switch ($step) {

        case 'clean':
            $fu->clean_upgrade_folder();
        break;

        case 'download':
            $fu->download_url();
        break;

        case 'unzip':
            $fu->unzip_package();
        break;

        case 'update':
            $fu->update_files();
        break;

        case 'clean_end':
            $fu->clean_upgrade_folder_end();
        break;

        case 'db_upgrade':
            $fu->db_upgrade();
        break;
    }

}

add_action('ajax_feedsync_update_version','feedsync_update_version');

function sitewide_notices() {
    global $sitewide_notices;
    if( !empty($sitewide_notices) ) {
        foreach($sitewide_notices as $notice) {
            if(is_fs_error($notice) ){
                echo '<div class="alert alert-'.$notice->get_error_code().'" >';
                    echo $notice->get_error_message();
                echo '</div>';
            }
        }
    }
}

function add_sitewide_notices($message,$code='warning') {
    global $sitewide_notices;
    $sitewide_notices[] = new FS_Error( $code, __( $message, "feedsync" ) );

}

/*
Dom helper functions
*/
/**
 * check if parent node has a child node
 * @param  [domDocument Object]
 * @param  [string]
 * @return boolean
 */
function has_node($item,$node){
    return $item->getElementsByTagName($node)->length == 0 ? false : true;
}

/**
 * get child nodes from parent nodes
 * @param  [domDocument Object]
 * @param  [string]
 * @return [domDocument Object]
 */
function get_nodes($item,$node){
    return $item->getElementsByTagName($node);
}

/**
 * Get first child node from parent node
 * @param  [domDocument Object]
 * @param  [string]
 * @return [domDocument Object]
 */
function get_first_node($item,$node){
    $nodes = get_nodes($item,$node);
    return $nodes->item(0);

}

/**
 * add node to element
 * @param [domDocument Object]
 * @param [string]
 * @param [mixed]
 */
function add_node($item,$node,$value){
    return $item->createElement($node, $value);
}

/**
 * get value of a node
 * @param  [domDocument Object]
 * @param  [string]
 * @return [mixed]
 */
function get_node_value($item,$node){
    if( has_node($item,$node) ) {
        return !is_null($item) ?  $item->getElementsByTagName($node)->item(0)->nodeValue : '';
    }
    return '';
}

/**
 * set node value and returns it
 * @param [domDocument Object]
 * @param [string]
 * @param [domDocument Object]
 */
function set_node_value($item,$node,$value){
    $item->getElementsByTagName($node)->item(0)->nodeValue = $value;
    return $item;
}

function feedsync_mark_fav() {

   global $feedsync_db;
   $id = intval($_POST['id']);

    if($id <= 0)
        return;

    $listing = $feedsync_db->get_row("select * from feedsync where id = {$id} ");

    $xmlFile = new DOMDocument('1.0', 'UTF-8');
    $xmlFile->preserveWhiteSpace = FALSE;
    $xmlFile->loadXML($listing->xml);
    $xmlFile->formatOutput = TRUE;
    $xpath = new DOMXPath($xmlFile);
    $item = $xmlFile->documentElement;

    if( ! has_node($item,'feedsyncFeaturedListing') ) {
        // if node not already exists, add it
        $element = add_node($xmlFile,'feedsyncFeaturedListing','yes');
        update_post_meta($listing->id,'fav_listing','yes');
        $item->appendChild($element);
    } else {
        // if node already exists, remove it
        $fl = get_first_node($item,'feedsyncFeaturedListing');
        $item->removeChild($fl);
        delete_post_meta($listing->id,'fav_listing');
    }
    $mod_date = date("Y-m-d H:i:s",strtotime($listing->mod_date) + 5 );
    $xmlFile->documentElement->setAttribute('modTime', $mod_date );
    $newxml         = $xmlFile->saveXML($xmlFile->documentElement);

    $db_data   = array(
        'xml'       =>  $newxml,
        'mod_date'  =>  $mod_date
    );

    $db_data    =   array_map(array($feedsync_db,'escape'), $db_data);

    $query = "UPDATE feedsync SET
                    xml             = '{$db_data['xml']}',
                    mod_date        = '{$db_data['mod_date']}'
                    WHERE id        = '{$listing->id}'
                ";

   $status = $feedsync_db->query($query);
   print_exit($status);
}
add_action('ajax_feedsync_mark_fav','feedsync_mark_fav');

/** generate a unique ID everytime */
function generate_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0C2f ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
    );

}

function is_logging_enabled() {
    $status = get_option('feedsync_enable_logging') == 'on' ? true : false;

    if( $status ) {
        if( defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['action']) && $_REQUEST['action'] == 'import_listings' ){
            return true;
        }

        if( defined('DOING_CRON') && DOING_CRON ){
            return true;
        }
    }

    return false;
}
