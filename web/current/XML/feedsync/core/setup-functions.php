<?php

/**
 * Reads debug configurations and accordingly displays or hides errors
 * recommended to turn FEEDSYNC_DEBUG = true on developement server
 * and false on production server
 * @return void
 */
function setup_environment() {

    ini_set("log_errors" , "1");
    ini_set("error_log" , LOG_PATH.LOG_FILE);

    if ( defined('FEEDSYNC_DEBUG') && (FEEDSYNC_DEBUG == true || FEEDSYNC_DEBUG == TRUE || FEEDSYNC_DEBUG == 1 ) ) {
        ini_set("display_errors" , "1");
    } else {
        ini_set("display_errors" , "0");
    }
    
    date_default_timezone_set(get_option('feedsync_timezone'));

}
add_action('init','setup_environment',5);

function make_current_url() {
    return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        $_SERVER['REQUEST_URI']
    );
}

function set_default_settings() {

    global $current_version;

    if( get_option('site_url',false) == '' && defined( 'SITE_URL' ) ){
        update_option('site_url', constant('SITE_URL') );
    }

    $options_to_db = array(
        'FEEDSYNC_MAX_FILESIZE' =>  '',
        'FEEDSYNC_CHUNK_SIZE'   =>  '',
        'FEEDTYPE'              =>  'reaxml',
        'GEO_ENABLED'           =>  '',
        'FORCE_GEOCODE'         =>  '',
        'FEEDSYNC_PAGINATION'   =>  '',
        'FEEDSYNC_GALLERY_PAGINATION' =>  '',
        'FEEDSYNC_TIMEZONE'     =>  '',
        'REC_LICENSED_URL'      =>  '',
        'REC_LICENSE'           =>  ''
    );

    foreach($options_to_db as $option_to_db => &$opt_value) {

        $opt_value      = defined( $option_to_db ) ? constant($option_to_db) : '';
        $opt_key        = strtolower($option_to_db);

        if($option_to_db == 'REC_LICENSED_URL') {
            $opt_key = strtolower('FEEDSYNC_LICENSE_URL');
        }

        if($option_to_db == 'REC_LICENSE') {
            $opt_key = strtolower('FEEDSYNC_LICENSE_KEY');
        }
       
        if( get_option($opt_key,false) == '' && defined( $option_to_db ) ){

            if($opt_key == 'feedsync_timezone') {
                $timezones = get_single_timezone_array();
                $opt_value = in_array($opt_value,$timezones) ? $opt_value : 'Australia/Sydney';
                update_option($opt_key, $opt_value );
            } else {
                update_option($opt_key, strtolower($opt_value) );
            }
            
        }
    }

    /** set default settings */
    $default_opts = array(
        'feedsync_enable_access_key'    =>  'off',
        'feedsync_current_version'      =>  $current_version
    );

    foreach($default_opts as $default_opt_key => $default_opt) {
        if( get_option($default_opt_key) == '' ){
            update_option($default_opt_key, $default_opt );
        }
    }
}

/**
 * Initiate Database connection
 * also declares $feedsync_db global object to the database connection
 * creates tables required for feedsync if not already there.
 * upgrades table incase of feedsync upgradation from a lower version
 * @return void
 */
function init_db_connection() {
    global $feedsync_db;

    require_once "ez_sql_core.php";

    require_once "ez_sql_mysqli.php";

    if( !defined('DB_USER')  || !defined('DB_PASS')  || !defined('DB_NAME')  || !defined('DB_HOST')  ) {

        //$feedsync_errors[111] = 'database credentials not defined';
        die('<h2>database credentials not defined</h2>');

    } else {
        $feedsync_db = new ezSQL_mysqli(DB_USER,DB_PASS,DB_NAME,DB_HOST);

        $required_tables = array('feedsync','feedsync_users','feedsync_options','feedsync_temp','listing_meta','feedsync_logs');

        foreach($required_tables as $required_table) {
            $exists = $feedsync_db->get_results('show tables like "'.$required_table.'" ');
            if( is_null($exists) || empty($exists) )  {

                create_table();

                if( is_home() && get_option('site_url') == '' ) {
                    update_option('site_url', make_current_url() );
                }

                break;
            }
        }

        $sql = "SELECT *
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE table_name = 'feedsync'
                    AND table_schema = '".DB_NAME."'
                    AND column_name = 'street'";

        $col_exists = $feedsync_db->get_results($sql);
        if( is_null($col_exists) ) {
            upgrade_tables();
        }
    }

    $option_exist = $feedsync_db->query("SELECT * FROM feedsync_options WHERE option_name = 'option' ");
    if($option_exist) {
        // upgrade the option
       upgrade_options();
       // store site url
       if( is_home() && get_option('site_url') == '' ) {
            update_option('site_url', make_current_url() );
        }
    }

    $feedsync_db->show_errors = false;
}

/*
    ** Initialize database connection
    */
add_action('init_db','init_db_connection');
do_action('init_db');
do_action('init_options');
do_action('init_constants');

/** set default settings */
set_default_settings();


/**
 * Save feedsync settings
 */
add_action('feedsync_form_feedsync_settings','save_feedsync_settings');

function save_feedsync_settings() {

    global $feedsync_db;
    $data  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    if( !empty($data) ) {
        foreach($data as $key => $value) {
            update_option($key,$value);
        }
    }
    delete_option('feedsync_mls_offset');
    delete_option('mls_fetched_since');
    // reinstantiate options to avoid refreshing 2nd time to see the save value
    instantiate_options();
}


function update_option($key,$value) {
    global $feedsync_db;
    $exist = $feedsync_db->query("SELECT * FROM feedsync_options WHERE option_name = '{$key}' ");
    $value = (is_array($value) || is_object($value) ) ? serialize($value) : $value;
    if($exist) {
        // update the option
       $status = $feedsync_db->query("UPDATE feedsync_options SET option_value =  '".$value."' WHERE option_name = '{$key}' ");
    } else {
        // insert the option
       $status = $feedsync_db->query("INSERT INTO feedsync_options(option_name,option_value) VALUES ('{$key}','".$value."') ");
    }
    return $status;
}

function delete_option($key) {

    global $feedsync_db;
    $status = false;
    if($key == '') {
        return $status;
    }

    $exist = $feedsync_db->query("SELECT * FROM feedsync_options WHERE option_name = '{$key}' ");
    if($exist) {
        // delete the option
       $status = $feedsync_db->query("DELETE FROM feedsync_options WHERE option_name = '{$key}' ");
    }
    return $status;
}

function get_post_meta($post_id,$meta_key='',$single=false) {

    global $feedsync_db;

    if( $post_id <= 0 )
        return;

    if($meta_key == '')
        $meta = $feedsync_db->get_Results("SELECT meta_value FROM listing_meta WHERE listing_id = '{$post_id}' ");
    else
        $meta = $feedsync_db->get_row("SELECT meta_value FROM listing_meta WHERE meta_key = '{$meta_key}' AND listing_id = '{$post_id}' ");

    if( is_null($meta) ) {
        return false;
    }
    if($single) {
        $meta = current($meta);
    }

    return is_serialized( $meta ) ? unserialize( $meta ) : $meta;
}

function update_post_meta($post_id,$meta_key='',$value='') {

    global $feedsync_db;

     if( $post_id <= 0 )
        return;

    if( $meta_key == '' )
        return;

    $exist = get_post_meta($post_id,$meta_key,true);
    $value = (is_array($value) || is_object($value) ) ? serialize($value) : $value;
    if($exist) {
        // update the meta
       $status = $feedsync_db->query("UPDATE listing_meta SET meta_value =  '".$value."' 
                                        WHERE listing_id = '{$post_id}' AND meta_key = '{$meta_key}' ");

    } else {
        // insert the meta
       $status = $feedsync_db->query("INSERT INTO listing_meta(meta_key,listing_id,meta_value) VALUES ('{$meta_key}','{$post_id}','".$value."') ");
       
    }
    return $status;
}

function delete_post_meta($post_id,$meta_key='') {

    global $feedsync_db;

     if( $post_id <= 0 )
        return;

    if( $meta_key == '' )
        return;

    $status = false;
    $exist = get_post_meta($post_id,$meta_key,true);
    if($exist) {
        // update the meta
       $status = $feedsync_db->query("DELETE FROM listing_meta WHERE listing_id = '{$post_id}' AND meta_key = '{$meta_key}' ");
    }

    return $status;
}


/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @since 3.0
 *
 * @param string $data   Value to check to see if was serialized.
 * @param bool   $strict Optional. Whether to be strict about the end of the string. Default true.
 * @return bool False if not serialized and true if it was.
 */
function is_serialized( $data, $strict = true ) {
    // if it isn't a string, it isn't serialized.
    if ( ! is_string( $data ) ) {
        return false;
    }
    $data = trim( $data );
    if ( 'N;' == $data ) {
        return true;
    }
    if ( strlen( $data ) < 4 ) {
        return false;
    }
    if ( ':' !== $data[1] ) {
        return false;
    }
    if ( $strict ) {
        $lastc = substr( $data, -1 );
        if ( ';' !== $lastc && '}' !== $lastc ) {
            return false;
        }
    } else {
        $semicolon = strpos( $data, ';' );
        $brace     = strpos( $data, '}' );
        // Either ; or } must exist.
        if ( false === $semicolon && false === $brace )
            return false;
        // But neither must be in the first X characters.
        if ( false !== $semicolon && $semicolon < 3 )
            return false;
        if ( false !== $brace && $brace < 4 )
            return false;
    }
    $token = $data[0];
    switch ( $token ) {
        case 's' :
            if ( $strict ) {
                if ( '"' !== substr( $data, -2, 1 ) ) {
                    return false;
                }
            } elseif ( false === strpos( $data, '"' ) ) {
                return false;
            }
            // or else fall through
        case 'a' :
        case 'O' :
            return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
        case 'b' :
        case 'i' :
        case 'd' :
            $end = $strict ? '$' : '';
            return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
    }
    return false;
}

/**
 * uses global variable $feedsync_options to retrive single option values and returns in
 * fallsback to constant if no settings are retrived
 * 
 * @param  string $key option key to get value of . $key name is same as contant name but in all lower case .
 * @return mixed      returns value if found or false
 */
function get_option($key,$constant = true) {
    global $feedsync_options;
    $value = isset($feedsync_options[$key]) ? $feedsync_options[$key] : false;
    if( ! $value && $constant && defined( strtoupper($key) ) ) {
        $value = constant(strtoupper($key));
    } else {
        $value = is_serialized( $value ) ? unserialize( $value ) : $value;
    }

    return $value;
}

/**
 * Checks if correct username & password is transferred. 
 * Logs in the admin
 * @return void
 */
function feedsync_form_admin_login() {

    if( isset($_POST['username']) && isset($_POST['password'])  ) {

        if( $_POST['username'] == FEEDSYNC_ADMIN &&  $_POST['password'] == FEEDSYNC_PASS ){
            $_SESSION['uid'] = 1; // user id static for now since only one user is there
        } else {
            add_sitewide_notices('Username or password is incorrect','danger');
        }
    }
}
add_action('feedsync_form_admin_login','feedsync_form_admin_login');

/**
 * Logout of the feedsync application
 * @return void
 */
function feedsync_form_logout() {

    $_SESSION = array();
    session_destroy();
    
}
add_action('feedsync_form_logout','feedsync_form_logout');

/**
 * A little handly function to print a variable and exits it
 * @param  mixed $data 
 * @return void
 */
function print_exit($data) {
    echo "<pre>";
    print_r($data);
    die;
}

function slugify($text) {

  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  if( function_exists('iconv') ){
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  }

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

/**
 * Adds css link to the page
 * @param  array  $css files to be linked
 */
function enqueue_css($css = array() ) {
    global $current_version;
    if( !empty ($css) ) {
        foreach($css as $file) {
            echo '<link rel="stylesheet" href="'.CSS_URL.$file.'?version='.slugify($current_version).'" />';
        }
    }
}

/**
 * Adds script url to the page
 * @param  array  $js js files to added
 */
function enqueue_js($js = array() ) {
    global $current_version;
    if( !empty ($js) ) {
        foreach($js as $file) {
            $prefix = is_absolute_url($file) ? '' : JS_URL;
            echo '<script type="text/javascript" src="'.$prefix.$file.'?version='.slugify($current_version).'" ></script>';
        }
    }
}

function is_absolute_url($url) {
    $pattern = "/^(?:ftp|https?|feed):\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*
    (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:
    (?:[a-z0-9\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?]
    (?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/xi";

    return (bool) preg_match($pattern, $url);
}

/**
 * Generates bootstrap error html
 * @param  string $error [description]
 * @return [type]        [description]
 */
function get_error_html($error='') {
    return '
            <div class="alert alert-danger" role="alert">
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
              <span class="sr-only">Error:</span>
              '.$error.'
            </div>
        ';
}

/**
 * Generates bootstrap success html
 * @param  string $msg [description]
 * @return [type]      [description]
 */
function get_success_html($msg='') {
    return '
            <div class="alert alert-success" role="alert">
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
              <span class="sr-only">Error:</span>
              '.$msg.'
            </div>
        ';
}

/**
 * Test if we have correct db credentials
 * @return [type] [description]
 */
function test_connection() {
    error_reporting(0);
    global $feedsync_db;
    parse_str($_POST['formData'],$_POST);
    $errors = '';
    $required = array(
        'user_name' =>  'Username is required',
        'user_pass' =>  'Password is required',
        'db_name'   =>  'Database is required',
        'host_name' =>  'Hostname is required'
    );

    foreach($required as $key   =>  $error) {
        if( !array_key_exists($key,$required) || $_POST[$key] == '' ) {
            $errors .= get_error_html($error);
        }
    }

    if($errors != '') {
        die( json_encode( array( 'status'   =>  'fail', 'message'   =>  $errors) ) );
    }

    require_once CORE_PATH."ez_sql_core.php";
    require_once CORE_PATH."ez_sql_mysqli.php";

    $feedsync_db = new ezSQL_mysqli($_POST['user_name'],$_POST['user_pass'],$_POST['db_name'],$_POST['host_name']);
    $feedsync_db->show_errors   = false;
    $tables = $feedsync_db->get_results('show tables');

    if( $feedsync_db->last_error != ''  ) {
        $con_error = 'connection cannot be established, please check database details and try again !';
        die( json_encode( array( 'status'   =>  'fail', 'message'   =>  get_error_html($con_error)) ) );
    } else {

        die( json_encode( array( 'status'   =>  'success', 'message'    =>  get_success_html('Connection successful')) ) );
    }
}

$feedsync_hook->add_action('ajax_test_connection','test_connection');

/**
 * Creates table on first install
 * @return [type] [description]
 */
function create_table() {
    global $feedsync_db;
    $sql = 'CREATE TABLE IF NOT EXISTS `feedsync` (
                  `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `unique_id` varchar(120) NOT NULL,
                  `feedsync_unique_id` varchar(120) NOT NULL,
                  `agent_id` varchar(128) NOT NULL,
                  `mod_date` varchar(28) NOT NULL,
                  `type` varchar(28) NOT NULL,
                  `status` varchar(28) NOT NULL,
                  `xml` longtext NOT NULL,
                  `firstdate` varchar(28) NOT NULL,
                  `geocode` varchar(50) NOT NULL,
                  `street` varchar(256) NOT NULL,
                  `suburb` varchar(256) NOT NULL,
                  `state` varchar(256) NOT NULL,
                  `postcode` varchar(256) NOT NULL,
                  `country` varchar(256) NOT NULL,
                  `address` varchar(512) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ; ';

    $feedsync_db->query($sql);

    $sql = '
                CREATE TABLE IF NOT EXISTS `feedsync_users` (
                   `id` bigint(20) NOT NULL AUTO_INCREMENT,
                  `office_id` varchar(128) NOT NULL,
                  `name` varchar(128) NOT NULL,
                  `telephone` varchar(128) NOT NULL,
                  `email` varchar(128) NOT NULL,
                   `xml` text NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `name` (`name`)
                ) ;

        ';


    $feedsync_db->query($sql);

    $sql = '
            CREATE TABLE IF NOT EXISTS `feedsync_options` (
                `option_id` bigint(20) NOT NULL AUTO_INCREMENT,
                `option_name` varchar(191) NOT NULL,
                `option_value` longtext NOT NULL,
              PRIMARY KEY (`option_id`)
            ) ;

    ';

    $feedsync_db->query($sql);

    $sql = '
            CREATE TABLE IF NOT EXISTS `feedsync_temp` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `unique_id` varchar(191) NOT NULL,
                `mod_date` varchar(28) NOT NULL,
                `value` longtext NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `unique_id` (`unique_id`)
            ) ;

    ';

    $feedsync_db->query($sql);

    $sql = '
            CREATE TABLE IF NOT EXISTS `listing_meta` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `listing_id` bigint(20) NOT NULL,
                `meta_key` varchar(191) NOT NULL,
                `meta_value` longtext NOT NULL,
              PRIMARY KEY (`id`)
            ) ;

    ';

    $feedsync_db->query($sql);

    $sql = '
            CREATE TABLE IF NOT EXISTS `feedsync_logs` (
              `id` bigint(20) NOT NULL AUTO_INCREMENT,
              `file_name` varchar(256) NOT NULL,
              `action` varchar(256) NOT NULL,
              `status` varchar(256) NOT NULL,
              `summary` longtext NOT NULL,
              `log_file` varchar(256) NOT NULL,
              PRIMARY KEY (`id`)
            ) ; 
    ';
    $feedsync_db->query($sql);

}

/**
 * Upgrades table on upgradation from lower to higher version
 * @return [type] [description]
 */
function upgrade_tables() {
    global $feedsync_db;
    /** add columns in case of upgrade to this version **/
    $sql = "
            ALTER TABLE `feedsync`
                ADD `agent_id` varchar(256) NOT NULL,
                ADD `street` varchar(256) NOT NULL,
                ADD `suburb` varchar(256) NOT NULL,
                ADD `state` varchar(256) NOT NULL,
                ADD `postcode` varchar(256) NOT NULL,
                ADD `country` varchar(256) NOT NULL;
        ";
    $feedsync_db->query($sql);

}

function upgrade_options() {

    global $feedsync_db;

    $data = $feedsync_db->get_row("SELECT * FROM feedsync_options WHERE option_name = 'option' ");
    $data = !is_null($data) ? unserialize($data->option_value) : array() ;

    if( !empty($data) ) {
        foreach($data as $key => $value) {
            update_option($key,$value);
        }

        delete_option('option');
    }
}

/**
 * Check if feedsync's required folders exists already
 * If not already there, it attempts to create them
 * @return [type] [description]
 */
function check_folders_existance() {

    $paths = array(INPUT_PATH,OUTPUT_PATH,IMAGES_PATH,PROCESSED_PATH,ZIP_PATH,TEMP_PATH,LOG_PATH,UPGRADE_PATH,LOGS_FOLDER_PATH);

    foreach($paths as $path) {

        if (!file_exists($path) ) {
            @mkdir($path, 0755, true);

        } else {
            @chmod($path, 0755);

        }
    }
}

$feedsync_hook->add_action('init','check_folders_existance');

function upgrade_table_data() {

    $fu = new feedsync_upgrade();
    $fu->dispatch();

    die(
        json_encode(
            array(
                'status'    =>  'success',
                'message'   =>  'Upgrade Process Complete!',
                'buffer'    =>  'complete'
            )
        )
    );

}

add_action('ajax_upgrade_table_data','upgrade_table_data');


function all_agents_name() {
    global $feedsync_db;
    $alllistings = $feedsync_db->get_results("select * from feedsync");

    if( !empty( $alllistings ) ) {
        $data_agent = array();
        foreach($alllistings as $listing) {

            $xmlFile = new DOMDocument;
            $xmlFile->preserveWhiteSpace = FALSE;
            $xmlFile->loadXML($listing->xml);
            $xmlFile->formatOutput = TRUE;
            $newxml = $listing->xml;

            /** missing listing agent processing - start **/
            if($xmlFile->getElementsByTagName("listingAgent")->length != 0) {

                foreach($xmlFile->getElementsByTagName("listingAgent") as $listing_agent) {

                    if($listing_agent->getElementsByTagName('name')->length != 0) {
                        $data_agent[] = $listing_agent->getElementsByTagName('name')->item(0)->nodeValue;

                    }
                }
            }
        }
    }
    print_exit( array_unique($data_agent) );
}
// $feedsync_hook->add_action('init','all_agents_name',5); **/

/**
 * Generate processing buttons on process page
 *
 * @since 2.2
 */
function feedsync_manual_processing_buttons() {

    $geocode_button_style       = '';
    $geocode_button_label       = 'Process Missing Coordinates';

    // Force Geocode Processing Button Warning
    if ( strtolower(FORCE_GEOCODE) == 'on' ) {
        $geocode_button_style       = 'btn-warning';
        $geocode_button_label       = 'Re-Process All Coordinates';
    }
    if( is_reset_enabled() ) :
    echo '<input type="button" class="btn btn-info pull-right" value="Reset Feedsync" id="reset_feedsync">';
    endif;
    echo "<input type='button' class='btn $geocode_button_style btn-info pull-right' value='$geocode_button_label' id='process_missing_coordinates'>";
    echo '<input type="button" class="btn btn-info pull-right" value="Process Listing Agents" id="process_missing_listing_agents">';

    if( feedsync_upgrade_required() ):
        echo '<input type="button" class="btn btn-info pull-right" value="Database Upgrade" id="upgrade_table_data">'; 
    endif;
    ?>

    <?php if( is_reset_enabled() ) : ?>
    <div class="feedsync-reset-wrap">
      <div class="alert alert-danger">
            <p>Please continue only if you know what you are doing. <b>This process cannot be undone</b>.</p>
        </div>
        <input class="reset_confirm_pass" id="reset_confirm_pass" placeholder="Enter admin password" type="text">
        <input class="btn btn-info pull-right" value="Confirm Reset" id="confirm_table_reset" type="button">
    </div> <?php
    endif;
}

function update_option_data($name='',$data) {

    if($name == '')
        return false;

    global $feedsync_db;
    $data   = serialize($data);
    $exist = $feedsync_db->query("SELECT * FROM feedsync_options WHERE option_name = '{$name}' ");
    if($exist) {
        // update the option
       $status = $feedsync_db->query("UPDATE feedsync_options SET option_value =  '".$data."' WHERE option_name = '{$name}' ");
    } else {
        // insert the option
       $status = $feedsync_db->query("INSERT INTO feedsync_options(option_name,option_value) VALUES ('{$name}','".$data."') ");
    }
}

function get_option_data($name) {
    global $feedsync_db;
    $query = "SELECT * FROM feedsync_options WHERE option_name = '{$name}' ";
    $data = $feedsync_db->get_row($query);
    return !is_null($data) ? unserialize($data->option_value) : array() ;
}

function is_reset_enabled() {
    if( defined( 'FEEDSYNC_RESET') && FEEDSYNC_RESET == true) {
        return true;
    }

    return false;
}

function is_home() {

    if( defined( 'FEEDSYNC_HOME') && FEEDSYNC_HOME == true) {
        return true;
    }

    return false;
}


function get_single_timezone_array() {
    return timezone_identifiers_list();
}

/**
 * Get region wise timezones
 * @since  :      3.0
 * @return array
 */
function get_timezone_array() {

    $zones = timezone_identifiers_list();
        
    foreach ($zones as $zone) 
    {
        $zoneExploded = explode('/', $zone); // 0 => Continent, 1 => City
        
        // Only use "friendly" continent names
        if ($zoneExploded[0] == 'Africa' || $zoneExploded[0] == 'America' || $zoneExploded[0] == 'Antarctica' || $zoneExploded[0] == 'Arctic' || $zoneExploded[0] == 'Asia' || $zoneExploded[0] == 'Atlantic' || $zoneExploded[0] == 'Australia' || $zoneExploded[0] == 'Europe' || $zoneExploded[0] == 'Indian' || $zoneExploded[0] == 'Pacific')
        {        
            if (isset($zoneExploded[1]) != '')
            {
                $area = str_replace('_', ' ', $zoneExploded[1]);
                
                if (!empty($zoneExploded[2]))
                {
                    $area = $area . ' (' . str_replace('_', ' ', $zoneExploded[2]) . ')';
                }
                
                $time = new DateTime(NULL, new DateTimeZone($zone));
                /** 12 hour format with am - pm */
                $ampm = $time->format('g:i a');
                $locations[$zoneExploded[0]][$zone] = $area .' - '.$ampm; // Creates array(DateTimeZone => 'Friendly name')
            } 
        }
    }
    return $locations;
}

function get_access_key_default_status() {

    $access_key = get_option('feedsync_access_key');

    return (false !== $access_key && $access_key != '') ? 'on' : 'off';
}

function is_update_available() {
    
    $dev_mode = defined('FEEDSYNC_UPDATE_MODE') ? FEEDSYNC_UPDATE_MODE : '';

    if($dev_mode == 'dev') {
        return true;
    }
    
    $update_available = get_transient('feedsync_update_available');

    // no transient exist
    if( !$update_available ){
        
        global $current_version;

        if( !class_exists('FEEDSYNC_PROCESSOR') )
            include_once(CORE_PATH.'classes/class-feedsync-updater.php');

        $feedsync_updater = new feedsync_updater();
        $feedsync_updater->init() ;

        $update_available = $feedsync_updater->version > $current_version ? 'yes' : 'no';

        set_transient( 'feedsync_update_available', $update_available, 24*60*60 );
    }

    return $update_available == 'yes' ? true : false;
}


function set_transient($key,$value,$expiration) {

    $key                =  '_transient_'.$key;
    $key_timeout        = '_transient_timeout_' . $key;

    $expiration = time() + $expiration;
    update_option($key_timeout, $expiration);
    return update_option($key, $value);
}

function get_transient($key) {

    $key                =  '_transient_'.$key;
    $key_timeout        = '_transient_timeout_' . $key;

    $timeout = get_option( $key_timeout );
    if ( false !== $timeout && $timeout < time() ){
        delete_option( $key  );
        delete_option( $key_timeout );
        $value = false;
    }

    if ( ! isset( $value ) )
        $value = get_option( $key );

    return $value;
}

/**
 * Add notice if zip file is there to process but zip extension is not enabled
 * @return [type]
 */
function feedsync_show_extension_errors() {

    $z_ex = get_files_list(get_path('input'),"zip|ZIP");
    if( !empty($z_ex) && !class_exists('ZipArchive') ) {
        add_sitewide_notices('Zip Extension is required','danger');
    }
    
}

add_action('after_functions_include','feedsync_show_extension_errors');

function feedsync_upgrade_required() {

    global $current_version;

    if( get_option('db_version') < $current_version ) {
        return true;
    } else {
        return false;
    }
}

function is_permalinks_enabled() {

    $permalinks = get_option('feedsync_enable_permalinks');

    return $permalinks == ''  ? false : $permalinks;
}

function feedsync_nav_link($url) {
   $replace =  is_permalinks_enabled() ? '.php' : '';
   return str_replace($replace,'',$url);
}

function feedsync_navigation() { 

    global $page_now;
    $base_url = is_permalinks_enabled() ? SITE_URL : CORE_URL;
    $pages_url = is_permalinks_enabled() ? SITE_URL : CORE_URL.'pages/';


    $hide_settings_menu = false;
    $hide_help_menu     = false;

    if( defined('FEEDSYNC_SETTINGS_DISABLED') && FEEDSYNC_SETTINGS_DISABLED == true ) {
        $hide_settings_menu = true;
    }

    if( defined('FEEDSYNC_HELP_DISABLED') && FEEDSYNC_HELP_DISABLED == true  ) {
        $hide_help_menu = true;
    }

    ?>
    <div id="feedsync-navigation">
        <ul class="nav nav-pills pull-right">
            <li class="<?php echo $page_now == 'home' ? 'active':''; ?>">
                <a href="<?php echo SITE_URL ?>">Home</a>
            </li>
            <li class="<?php echo $page_now == 'process' ? 'active':''; ?>">
                <a href="<?php echo feedsync_nav_link($base_url.'process.php') ?>">Process</a>
            </li>
            <li class="<?php echo $page_now == 'export' ? 'active':''; ?>">
                <a href="<?php echo feedsync_nav_link($base_url.'export.php') ?>">Export</a>
            </li>
            <li class="<?php echo $page_now == 'listings' ? 'active':''; ?>">
                <a href="<?php echo feedsync_nav_link($base_url.'listings.php') ?>">Listings</a>
            </li>
            <?php if(!$hide_help_menu) : ?>
            <li class="<?php echo $page_now == 'help' ? 'active':''; ?>">
                <a href="<?php echo feedsync_nav_link($pages_url.'help.php') ?>">Help</a>
            </li>
            <?php endif; ?>

            <?php if( is_user_logged_in() ) { ?>

                <?php if(!$hide_settings_menu) : ?>
                <li class="<?php echo $page_now == 'settings' ? 'active':''; ?>">
                    <a href="<?php echo feedsync_nav_link($base_url.'settings.php') ?>">Settings</a>
                </li>
                <?php endif; ?>

                <li>
                    <a href="<?php echo SITE_URL.'?action=logout' ?>">Logout</a>
                </li>

            <?php } ?>

            <?php if( is_update_available() && !$hide_help_menu ) { ?>

                <li class="<?php echo $page_now == 'update' ? 'active':''; ?>">
                    <a class="btn btn-warning" href="<?php echo feedsync_nav_link($pages_url.'updates.php') ?>">Update</a>
                </li>

            <?php } ?>
        </ul>
    </div>
<?php
}