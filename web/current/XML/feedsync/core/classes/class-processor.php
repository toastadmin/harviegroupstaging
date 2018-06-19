<?php

class FEEDSYNC_PROCESSOR {


    /** handles db queries **/
    protected $db;

    /** xml file to be processed **/
    protected $xmlFile;

    /** path of xml file to be processed **/
    protected $path;

    /** xpath of object of xml file to be processed **/
    protected $xpath;

    /** total files pending to be processed **/
    protected $total_files;

    /** contains list of geocoded address in each iteration*/
    protected $geocoded_addreses_list;

    /** operating in cron mode ? **/
    protected $cron_mode = false;

    protected $log_report = array(
        'listings_created'    =>  0,
        'listings_updated'    =>  0,
        'listings_skipped'    =>  0
    );

    /**
     * Instantiate reaxml processor
     * @param $cron_mode boolean
     */
    function __construct($cron_mode = false){

        global $feedsync_db;

        // setting cron mode to false so that it shows message & status for processing
        $cron_debug = ( isset($_GET['cron_debug']) && $_GET['cron_debug'] == 'true' ) ? true : false ;

        $this->cron_mode    = $cron_debug ? false : $cron_mode;
        $this->db           = $feedsync_db;
        $this->init();
    }

    /**
     * Init the import process
     * @since  :      3.0.1
     * @return [type] [description]
     */
    function init() {

        $this->xmls         = $this->get_xmls();
        $this->total_files  = count($this->xmls);

        if( $this->get_xml_to_process() ){

            /** configuration **/
            $this->xmlFile = new DOMDocument('1.0','UTF-8');
            libxml_use_internal_errors(true);
            $this->xmlFile->formatOutput = true;
            $this->xmlFile->preserveWhiteSpace = false;
            $this->xmlFile->recover = TRUE;
            $this->xmlFile->load($this->path);
            $this->xpath = new DOMXPath($this->xmlFile);
            /** configuration - end **/

            /** check if xml is not empty & and is valid **/
            $this->handle_blank_xml();
            $this->handle_invalid_xml();

            /** extract dom elements and cache it as class property **/
            $this->dom_elements();

        } else {
            //@TODO return status message and die !
        }

    }

    /**
     * Resets class properties
     * @return [type]
     */
    function reset() {
        $this->elements = array();
    }

    function get_sub_path(){
        return '';
    }

    function get_path($folder) {
        $sub_path = $this->get_sub_path();

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
        }

        return $path;
    }

    function get_url($folder) {
        $sub_path = $this->get_sub_path();

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
        }
        return $path;
    }


    /**
     * @return [array] array of xml files to be processed
    */
    function get_xmls(){
        $files =  get_files_list(get_path('input'),"xml|XML");
        sort($files);
        return $files;
    }

    /** returns next xml file to process **/
    function get_xml_to_process(){

        if( !empty($this->xmls) ){
            $this->path =  current($this->xmls);
            return true;
        }

        return false;

    }

    /**
     * process agents from a listing node
     * @param  [domDocument Object]
     * @return [domDocument Object]
     */
    function process_agents($item) {

        $listing_agents = $this->get_nodes($item,'listingAgent');

        if(!empty($listing_agents)) {
            foreach($listing_agents as $listing_agent) {

                /** init values **/
                $data_agent                 = array();
                $data_agent['agent_id']     = '';
                $data_agent['office_id']    = '';;
                $data_agent['name']         = '';
                $data_agent['telephone']    = '';
                $data_agent['email']        = '';
                $data_agent['agent_id']     = $listing_agent->getAttribute('id');
                $data_agent['agent_id']     = $data_agent['agent_id'] == '' ? 1 : $data_agent['agent_id'];
                $data_agent['username']     = '';

                $listing_agent->setAttribute('id',$data_agent['agent_id']);

                if( $this->has_node($item,'agentID') ) {
                    $data_agent['office_id'] = $this->get_node_value($item,'agentID');

                    if( !$this->has_node($listing_agent,'office_id') ) {
                        $create_office_id       = $this->xmlFile->createElement('office_id', $data_agent['office_id']);
                        $listing_agent->appendChild($create_office_id);
                    }

                }

                if( $this->has_node($listing_agent,'name') ) {

                    $data_agent['name']     = $this->get_node_value($listing_agent,'name');
                    $agent_full_name        = explode(' ',$data_agent['name']);

                    if( !$this->has_node($listing_agent,'agentFirstName') ) {
                        $agent_first        = $agent_full_name[0];
                        $create_fname       = $this->xmlFile->createElement('agentFirstName',  htmlentities($agent_first) );
                        $listing_agent->appendChild($create_fname);
                    }
                    if( !$this->has_node($listing_agent,'agentLastName') ) {
                        $agent_last         = isset($agent_full_name[1]) ? $agent_full_name[1] : '';
                        $create_lname       = $this->xmlFile->createElement('agentLastName', htmlentities($agent_last) );
                        $listing_agent->appendChild($create_lname);
                    }
                    if( !$this->has_node($listing_agent,'agentUserName') ) {
                        $create_uname       = $this->xmlFile->createElement('agentUserName',sanitize_user_name($data_agent['name']));
                        $listing_agent->appendChild($create_uname);
                        $data_agent['username']         = sanitize_user_name($data_agent['name']);
                    } else {
                        $data_agent['username']         = $this->get_node_value($listing_agent,'agentUserName');
                    }

                }

                if( $this->has_node($listing_agent,'email') ) {
                    $data_agent['email'] = $this->get_node_value($listing_agent,'email');
                }

                if( $this->has_node($listing_agent,'telephone') ) {
                    $tel_nos = array();
                    foreach($listing_agent->getElementsByTagName('telephone') as $agent_tel) {
                        $tel_nos[] =  $agent_tel->nodeValue;
                    }
                    $data_agent['telephone'] = implode(',',$tel_nos);
                }

                $data_agent['xml']  = $this->xmlFile->saveXML( $listing_agent);
                $data_agent         =   array_map(array($this->db,'escape'), $data_agent);


                /** check if listing agent exists already **/
                $agent_exists = $this->db->get_row("SELECT * FROM feedsync_users where name = '{$data_agent['name']}' ");

                if( empty($agent_exists) ) {

                    /** insert new data **/
                    $query = "INSERT INTO
                    feedsync_users (office_id,name,telephone,email,xml,listing_agent_id,username)
                    VALUES (
                        '{$data_agent['office_id']}',
                        '{$data_agent['name']}',
                        '{$data_agent['telephone']}',
                        '{$data_agent['email']}',
                        '{$data_agent['xml']}',
                        '{$data_agent['agent_id']}',
                        '{$data_agent['username']}'
                    )";

                    $this->logger_log('Imported Agent : Name : '.$data_agent['name'].' ID : '.$data_agent['office_id']);
                    //print_exit($query);
                    $this->db->query($query);
                    //print_exit($this->db);
                } else {

                }

            }
        }


    }

    /** handle invalid xml **/
    function handle_invalid_xml(){

        if($this->xmlFile->getElementsByTagName("RockendDataFeed")->length != 0) {
            try {
                if( rename($this->path,$this->get_path('processed').basename($this->path) ) ) {
                    if(!$this->cron_mode) {
                        die(
                            json_encode(
                                array(
                                    'status'    =>  'fail',
                                    'message'   =>  'Rockend File Format Detected and file skipped. Please ensure you select the RealEstate.com.au format as shown <a href="http://codex.easypropertylistings.com.au/article/40-rockend-rest-reaxml-setup-documentation">Here</a> when configuring Rockend.',
                                    'geocoded'  =>  '',
                                    'buffer'    =>  'processing'
                                )
                            )
                        );
                    }
                }
            } catch(Exception $e) {
                if(!$this->cron_mode) {
                    echo $e->getMessage(); die;
                }
            }
        }

    }

    /** handle blank xml **/
    function handle_blank_xml(){

        if($this->xmlFile->getElementsByTagName("propertyList")->length == 0) {
            try {
                if( rename($this->path,$this->get_path('processed').basename($this->path) ) ) {
                    if(!$this->cron_mode) {
                        die(
                            json_encode(
                                array(
                                    'status'    =>  'fail',
                                    'message'   =>  'empty file, skipped.',
                                    'geocoded'  =>  '',
                                    'buffer'    =>  'processing'
                                )
                            )
                        );
                    }
                }
            } catch(Exception $e) {
                if(!$cron_mode) {
                    echo $e->getMessage(); die;
                }
            }
        }


    }

    /**
     * parses dom elements to be procesessed in file
     * @return [type]
     */
    function dom_elements() {
        $this->elements = $this->xmlFile->documentElement;
        $this->item     = current($this->elements);
    }

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
        $nodes =$this->get_nodes($item,$node);
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

        if( !$this->has_node($item,$node) ) {
            return '';
        }
        
        return !is_null($item) ?  $item->getElementsByTagName($node)->item(0)->nodeValue : '';
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

    /**
     * check if force geocode mode is enabled
     * @return boolean
     */
    function force_geocode(){
        return get_option('force_geocode') == 'on' ? true : false;
    }

    /**
     * check if geocode mode is enabled
     * @return boolean
     */
    function geocode_enabled(){
        return get_option('geo_enabled') == 'on' ? true : false;
    }

    /**
     * get address from a listing element
     * @param  [domDocument Object]
     * @param  boolean
     * @return [mixed]
     */
    function get_address($item,$comma_seperated = true) {

        $address        = $this->get_first_node($item,'address');

        $this->address['streetnumber']      = $this->get_node_value($address,'streetNumber');
        $this->address['street']            = $this->get_node_value($address,'street');
        $this->address['suburb']            = $this->get_node_value($address,'suburb');
        $this->address['state']             = $this->get_node_value($address,'state');
        $this->address['postcode']          = $this->get_node_value($address,'postcode');

        if( $this->has_node($address,'country') ) {
            $this->address['country']        = $this->get_node_value($address,'country');
        } else {
            $this->address['country']        = "Australia";
        }

        $address_array = array_filter($this->address);

        $address_string =  $comma_seperated == true ? implode(", ", $address_array) : implode(" ", $address_array);

        $this->address['lotNumber']         = '';
        $this->address['subNumber']         = '';

        if( $this->has_node($address,'lotNumber') )
            $this->address['lotNumber']         = $this->get_node_value($address,'lotNumber');

        if( $this->has_node($address,'subNumber') )
            $this->address['subNumber']         = $this->get_node_value($address,'subNumber');

        if( $this->address['lotNumber'] != '' && $this->address['streetnumber'] != ''){
            $address_string = $this->address['lotNumber'].'/'.$address_string;
        }

        if( $this->address['subNumber'] != '' && $this->address['streetnumber'] != ''){
            $address_string = $this->address['subNumber'].'/'.$address_string;
        }

        return $address_string;
    }

    /**
     * get address components parsed out from get_address
     * @param  [string]
     * @return [string]
     */
    function get_address_component($key){
        return isset($this->address[$key]) ? $this->address[$key] : '';
    }

    /**
     * update feedsync node of listing
     * @param  [domDocument Object]
     * @param  [string]
     * @return [domDocument Object]
     */
    function update_feedsync_node($item,$coord) {

        $node_to_add = !empty($this->xmlFile) ? $this->xmlFile : $item;

        if( ! $this->has_node($item,'feedsyncGeocode') ) {
            // if node not already exists, add it

            $element = $this->add_node($node_to_add,'feedsyncGeocode',$coord);
            $item->appendChild($element);
        } else {
            // if node already exists, just update the value
            $item = $this->set_node_value($item,'feedsyncGeocode',$coord);
        }

        /** in case or processing missing geocodes $this->xmlFile will be empty and also $this->path */
        if( !empty($this->xmlFile) && !empty($this->path) ) {
            $this->xmlFile->save($this->path);
        }
        $this->logger_log('updated feedsyncGeocode with value : '.$coord);
        // return item for further processing;
        return $item;

    }

    /**
     * Add EPL nodes
     * @return [type]
     */
    function epl_nodes($item) {

        $node_to_add = !empty($this->xmlFile) ? $this->xmlFile : $item;

        $image_mod_date = false;

        /** Feedsync Image Mod Time */

        $imgs = $this->get_nodes($item,'img');

        if( $imgs->length > 0 ) {

            foreach ($imgs as $k=>$img) {
                $this_mod_date = trim($img->getAttribute('modTime'));
                if(!empty($this_mod_date)) {
                    $this_mod_date = feedsync_format_date( $this_mod_date );
                    if($image_mod_date != false) {
                        $image_mod_date = strtotime($this_mod_date) >  strtotime($image_mod_date) ? $this_mod_date : $image_mod_date;
                    } else {
                        $image_mod_date = $this_mod_date;
                    }
                }
            }

            if($image_mod_date) {
                if( ! $this->has_node($item,'feedsyncImageModtime') ) {
                    // if node not already exists, add it

                    $element = $this->add_node($node_to_add,'feedsyncImageModtime',$image_mod_date);

                    $item->appendChild($element);
                } else {
                    // if node already exists, just update the value
                    $item = $this->set_node_value($item,'feedsyncImageModtime',$image_mod_date);
                }

                $this->logger_log('feedsyncImageModtime processed : '.$image_mod_date);
            }

        }

        /** Feedsync Unique ID ( Unique ID + Agent ID ) */

        $feedsync_unique_id = $this->get_node_value($item,'uniqueID');

        if( $this->has_node($item,'agentID') ) {

            $feedsync_unique_id = $this->get_node_value($item,'agentID').'-'.$this->get_node_value($item,'uniqueID');

        }

         // if node not already exists, add it
        if( ! $this->has_node($item,'feedsyncUniqueID') ) {

            // if node not already exists, add it

            $element = $this->add_node($node_to_add,'feedsyncUniqueID',$feedsync_unique_id);
            $item->appendChild($element);

        } else {
            // if node already exists, just update the value
            $item = $this->set_node_value($item,'feedsyncUniqueID',$feedsync_unique_id);
        }

        $this->logger_log('feedsyncUniqueID processed : '.$feedsync_unique_id);

        /** Sold Date  */

        if( $this->has_node($item,'soldDetails') ) {

            $sold_details           = $this->get_first_node($item,'soldDetails');
            if( $this->has_node($sold_details,'date') ){
                $sold_date              = $this->get_node_value($sold_details,'date');
                $sold_date              = feedsync_format_sold_date($sold_date);
                $this->set_node_value($sold_details,'date',$sold_date);
                $this->logger_log('soldDetails processed : '.$sold_date);
            }

        }

        if(!empty($this->xmlFile) ) {
            $this->xmlFile->save($this->path);
        }

        return $item;
    }

    /**
     * process image modtime
     * @return
     */
    function process_image_modtime(){

        $alllistings = $this->db->get_results("select * from feedsync where 1=1 ");

        if( !empty($alllistings) ) {

            foreach($alllistings as $listing) {
                $this->xmlFile = new DOMDocument('1.0', 'UTF-8');
                $this->xmlFile->preserveWhiteSpace = FALSE;
                $this->xmlFile->loadXML($listing->xml);
                $this->xmlFile->formatOutput = TRUE;
                $this->xpath = new DOMXPath($this->xmlFile);
                $listingXml     = $this->epl_nodes($this->xmlFile->documentElement);
                $newxml         = $this->xmlFile->saveXML($this->xmlFile->documentElement);

                $db_data   = array(
                    'xml'       =>  $newxml,
                );

                $db_data    =   array_map(array($this->db,'escape'), $db_data);
                $query = "UPDATE feedsync SET
                                xml             = '{$db_data['xml']}'
                                WHERE id        = '{$listing->id}'
                            ";

               $this->db->query($query);

                // die(
                //     json_encode(
                //         array(
                //             'status'    =>  'success',
                //             'message'   =>  'image mod time updated for listing ID : '.$listing->id.' <br>',
                //             'buffer'    =>  'processing'
                //         )
                //     )
                // );

            }

        }  else {

            /*die(
                json_encode(
                    array(
                        'status'    =>  'success',
                        'message'   =>  'Geocode process complete',
                        'buffer'    =>  'complete'
                    )
                )
            );*/
        }
    }

    /**
     * process missing feedsync_unique_id < 3.2
     * @since 3.2
     * @return
     */
    function upgrade_for_version_3_2(){

        $alllistings = $this->db->get_results("select * from feedsync where 1=1 AND feedsync_unique_id  = '' ");

        if( !empty($alllistings) ) {

            foreach($alllistings as $listing) {
                $this->xmlFile = new DOMDocument('1.0', 'UTF-8');
                $this->xmlFile->preserveWhiteSpace = FALSE;
                $this->xmlFile->loadXML($listing->xml);
                $this->xmlFile->formatOutput = TRUE;
                $this->xpath = new DOMXPath($this->xmlFile);
                $listingXml     = $this->epl_nodes($this->xmlFile->documentElement);
                $newxml         = $this->xmlFile->saveXML($this->xmlFile->documentElement);

                $db_data   = array(
                    'xml'                   =>  $newxml,
                    'feedsync_unique_id'    =>  $this->get_node_value($this->xmlFile->documentElement,'feedsyncUniqueID')
                );

                if( $this->has_node($this->xmlFile->documentElement,'address') ) {
                    $db_data['address']     = $this->get_address($this->xmlFile->documentElement,true);
                }

                $db_data    =   array_map(array($this->db,'escape'), $db_data);
                $query = "UPDATE feedsync SET
                                address                         = '{$db_data['address']}',
                                xml                             = '{$db_data['xml']}',
                                feedsync_unique_id              = '{$db_data['feedsync_unique_id']}'
                                WHERE id                        = '{$listing->id}'
                            ";

               $this->db->query($query);

            }

        }  else {

            /*die(
                json_encode(
                    array(
                        'status'    =>  'success',
                        'message'   =>  'Listing upgrade process complete, checking for other upgrades',
                        'buffer'    =>  'processing'
                    )
                )
            );*/
        }
    }

    /**
     * process agents for extra columns < 3.2
     * @since 3.2
     * @return
     */
    function agent_upgrade_for_version_3_2(){

        $agents = $this->db->get_results("select * from feedsync_users where 1=1 ");

        if( !empty( $agents ) ) {

            foreach($agents as $agent) {

                $dom = new DOMDocument('1.0', 'UTF-8');
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($agent->xml);
                $dom->formatOutput = TRUE;

                $db_data = array(
                    'listing_agent_id'  =>  '',
                    'username'          =>  ''
                );

                $agent_id_node = get_option('feedtype') == 'mls' ? 'agent_id' : 'agentid'; 

                /** Save office_id 
                if( $this->has_node($dom->documentElement,'office_id') ) {
                    $db_data['office_id']     = $this->get_node_value($dom->documentElement,'office_id');
                }
                */
               
                /** Save listing_agent_id */
                if( $this->has_node($dom->documentElement,$agent_id_node) ) {
                    $db_data['listing_agent_id']     = $this->get_node_value($dom->documentElement,$agent_id_node);
                }

                /** Save username */
                if( $this->has_node($dom->documentElement,'agentUserName') ) {
                    $db_data['username']     = $this->get_node_value($dom->documentElement,'agentUserName');
                }

                $db_data    =   array_map(array($this->db,'escape'), $db_data);

                $query = "UPDATE feedsync_users SET
                                listing_agent_id                = '{$db_data['listing_agent_id']}',
                                username                        = '{$db_data['username']}'
                                WHERE id                        = '{$agent->id}'
                            ";

               $this->db->query($query);

               
            }
        } else {
            
        }
    }

    /**
     * attempts to fetch geocode from geocode node, if present
     * @param  [domDocument Object]
     * @return [domDocument Object]
     */
    function geocode_from_geocode_node($item){

        $geocodenode            = $this->get_first_node($item,'Geocode');

        if( !$this->has_node($geocodenode,'Latitude') ) {

            // if coordinates are saved in Geocode node as value
            $this->coord              = $this->get_node_value($item,'Geocode');
        } else {

            // if coordinates are saved in childnodes
            $lat                = $this->get_node_value($geocodenode,'Latitude');
            $long               = $this->get_node_value($geocodenode,'Longitude');

            // make coordinates class wide available
            $this->coord        = $lat.','.$long;
        }

        $this->logger_log('Geocoded from geocode node : '.$this->coord);

        return $this->update_feedsync_node($item,$this->coord);
    }

    /**
     * Geocode address from google geocode API
     * @param  [domDocument Object]
     * @return [domDocument Object]
     */
    function geocode_from_google($item){

        $addr_readable  = $this->get_address($item);
        $addr           = urlencode(strtolower($addr_readable));
        $this->coord    = 'NULL';

        /** try to get lat & long from google **/
        if( trim($addr) != '') {

            $query_address  = trim($addr);
             $googleapiurl = "https://maps.google.com/maps/api/geocode/json?address=$query_address&sensor=false";
            if( get_option('feedsync_google_api_key') != '' ) {
                $googleapiurl = $googleapiurl.'&key='.get_option('feedsync_google_api_key');
            }

            $geocode        = file_get_contents($googleapiurl);

            $this->geocoded_addreses_list .= "\n $query_address";

            $output         = json_decode($geocode);

            /** if address is validated & google returned response **/
            if( !empty($output->results) && $output->status == 'OK' ) {

                $lat            = $output->results[0]->geometry->location->lat;
                $long           = $output->results[0]->geometry->location->lng;
                $this->coord    = $lat.','.$long;
                $this->logger_log('Google Geocoded Result : '.$this->coord);
                return $this->update_feedsync_node($item,$this->coord);
            } else {
                /** cant geocode set to '' */

                return $this->update_feedsync_node($item,'');
            }
        }



    }

    /**
     * Geocode the listing item :)
     * @param  [domDocument Object]
     * @return [domDocument Object]
     */
    function geocode($item,$process_missing = false){

        $this->geocoded_addreses_list = "\n";

        /** add feedsyncGeocode node if not already there or if force geocode mode is on **/

        if( !$this->has_node($item,'feedsyncGeocode') || $this->force_geocode() || $this->get_node_value($item,'feedsyncGeocode') == '' ) {

            // if item has geocode node, extract value from it and save it to feedsyncGeocode node
            if( $this->has_node($item,'Geocode') ) {


                $item = $this->geocode_from_geocode_node($item);

            } else {

                // if item doesnt have geocode node, geocode it
                if( $this->has_node($item,'address') && ( $this->geocode_enabled() || $this->force_geocode()  || $process_missing ) )
                    $item = $this->geocode_from_google($item);
            }
        } else {
           $this->coord = $this->get_node_value($item,'feedsyncGeocode');
        }

        return $item; // return processed item
    }

    /**
     * process missing geocode of existing listings in db
     * @return domDocument Object
     */
    function process_missing_geocode(){

        // if force geocode mode is on delete all coordinates
        if( $this->force_geocode()  && (!isset($_COOKIE['coordinates_resetted']) || $_COOKIE['coordinates_resetted'] != 1) ) {
            $this->db->query("update feedsync set geocode = '' where 1 = 1");
            setcookie("coordinates_resetted", 1);
        }

        $alllistings = $this->db->get_results("select * from feedsync where geocode = '' AND address != '' LIMIT 1");

        if( !empty($alllistings) ) {

            foreach($alllistings as $listing) {

                $this->xmlFile = new DOMDocument('1.0', 'UTF-8');
                $this->xmlFile->preserveWhiteSpace = FALSE;
                $this->xmlFile->loadXML($listing->xml);
                $this->xmlFile->formatOutput = TRUE;
                $this->coord    = '';
                $this->xpath    = new DOMXPath($this->xmlFile);
                $listingXml     = $this->geocode($this->xmlFile->documentElement,true);
                $newxml         = $this->xmlFile->saveXML($this->xmlFile->documentElement);

                $db_data   = array(
                    'xml'       =>  $newxml,
                    'geocode'   =>  $this->coord
                );

                $db_data    =   array_map(array($this->db,'escape'), $db_data);
                $query = "UPDATE feedsync SET
                                xml             = '{$db_data['xml']}',
                                geocode         = '{$db_data['geocode']}'
                                WHERE id        = '{$listing->id}'
                            ";
                $this->db->query($query);

                $geocode_status = json_encode(
                                        array(
                                            'status'    =>  'success',
                                            'message'   =>  '<strong>Geocode Status</strong> <br>
                                                                    Address : <em>'.$this->get_address($this->xmlFile).'</em> <br>
                                                                    Geocode : <em>'.$this->coord.'</em> <br>',
                                            'buffer'    =>  'processing'
                                        )
                                    );

                die($geocode_status);

            }

        }  else {

            die(
                json_encode(
                    array(
                        'status'    =>  'success',
                        'message'   =>  'Geocode process complete',
                        'buffer'    =>  'complete'
                    )
                )
            );
        }
    }

    /**
     * process missing listing agent of existing listings in db
     * @return domDocument Object
     */
    function process_missing_listing_agents() {

        $alllistings = $this->db->get_results("select * from feedsync");

        if( !empty( $alllistings ) ) {

            foreach($alllistings as $listing) {

                $listingXml = new DOMDocument('1.0', 'UTF-8');
                $listingXml->preserveWhiteSpace = FALSE;
                $listingXml->loadXML($listing->xml);
                $listingXml->formatOutput = TRUE;
                $this->xmlFile = $listingXml;

                $this->process_agents($listingXml);
            }
        }
        die(
            json_encode(
                array(
                    'status'    =>  'success',
                    'message'   =>  'Listing Agents Update Completed!',
                    'buffer'    =>  'processed'
                )
            )
        );
    }


    /**
     * Processes image
     * @return [domDocument Object]
     */
    function process_image($item = null) {

        $imgs = $this->xpath->query('//img[@file]');

        if(!empty($imgs)) {
            foreach ($imgs as $k=>$img) {
                $img_name = trim($img->getAttribute('file'));
                if(!empty($img_name)) {
                    $img_name = basename($img_name);
                    $img_path = $this->get_url('images').$img_name;
                    $imgs->item($k)->setAttribute('url', $img_path);
                    $this->logger_log('Image processed : '.$img_path);
                }

            }
        }

        /** some formats have image as base 64 encoded format - process it */

        $imgs_encoded = $this->xpath->query('//img/base64Content');

        if(!empty($imgs_encoded)) {

            $img_name_prefix   = $this->get_node_value($item,'uniqueID');
            $img_name_prefix    = $img_name_prefix == '' ? uniqid() : $img_name_prefix;

            foreach ($imgs_encoded as $k=>$img_encoded) {

                $img_format     = trim( $img_encoded->parentNode->getAttribute('format') );
                $img_content    = $img_encoded->nodeValue;

                // decode content
                $img_content    = base64_decode($img_content);
                $img_name       = $img_name_prefix. '-'.$k . '.'. $img_format;
                $img_path       = $this->get_path('images') . $img_name;
                $img_url        = $this->get_url('images') . $img_name;
                file_put_contents($img_path, $img_content);
                $img_encoded->parentNode->setAttribute('url', $img_url);
                $img_encoded->parentNode->removeChild($img_encoded);

            }
        }

        /** some formats have image as base 64 encoded format - process it - End */

        $node_found = false;

        $imgs = $this->get_nodes($item,'img');

        if( $imgs->length > 1 ) {

            foreach ($imgs as $k=>$img) {
                if( $img->getAttribute('id') == 'm' ) {
                   $featured_img =  $img->parentNode->removeChild($img);
                   $node_found = true;
                   break;
                }

            }

            if( $node_found == true ) {
                $this->get_first_node($item,'img')->parentNode->insertBefore( $featured_img,$this->get_first_node($item,'img') );
            }
        }
        

        return $item;

    }

    function fix_encoding($html) {
       return str_replace('andbull;', '&bull;', $html);
    }

    /**
     * insert listing in feedsync
     * @param  array
     * @return boolean
     */
    function insert_listing($db_data){

        $db_data        =   array_map(array($this,'fix_encoding'), $db_data);

        $query = "INSERT INTO
        feedsync (type, agent_id,unique_id,feedsync_unique_id, mod_date, status,xml,firstdate,street,suburb,state,postcode,country,geocode,address)
        VALUES (
            '{$db_data['type']}',
            '{$db_data['agent_id']}',
            '{$db_data['unique_id']}',
            '{$db_data['feedsync_unique_id']}',
            '{$db_data['mod_date']}',
            '{$db_data['status']}',
            '{$db_data['xml']}',
            '{$db_data['mod_date']}',
            '{$db_data['street']}',
            '{$db_data['suburb']}',
            '{$db_data['state']}',
            '{$db_data['postcode']}',
            '{$db_data['country']}',
            '{$db_data['geocode']}',
            '{$db_data['address']}'
        )";

        return $this->db->query($query);
    }

    /**
     * update existing listing in feedsync database
     * @param  array
     * @return boolean
     */
    function update_listing($db_data){

        $db_data        =   array_map(array($this,'fix_encoding'), $db_data);

        $query = "UPDATE feedsync SET
            type            = '{$db_data['type']}',
            mod_date        = '{$db_data['mod_date']}',
            status          = '{$db_data['status']}',
            xml             = '{$db_data['xml']}',
            geocode         = '{$db_data['geocode']}',
            address         = '{$db_data['address']}',
            street          = '{$db_data['street']}',
            suburb          = '{$db_data['suburb']}',
            postcode        = '{$db_data['postcode']}',
            country         = '{$db_data['country']}',
            unique_id       = '{$db_data['unique_id']}'
            WHERE feedsync_unique_id = '{$db_data['feedsync_unique_id']}'
        ";

        return $this->db->query($query);

    }

    /**
     * get initial values required to be updated / insereted for listing
     * @param  domDocument Object
     * @return domDocument Object
     */
    function get_initial_values($item){

        $db_data                                = array();
        $db_data['type']                        = $item->tagName;
        $db_data['feedsync_unique_id']          = $this->get_node_value($item,'feedsyncUniqueID');
        $db_data['unique_id']                   = $this->get_node_value($item,'uniqueID');
        $db_data['agent_id']                    = $this->get_node_value($item,'agentID');
        $mod_date                               = $item->getAttribute('modTime');
        $db_data['mod_date']                    = feedsync_format_date( $mod_date );
        $db_data['status']                      = $item->getAttribute('status');
        $db_data['xml']                         = $this->xmlFile->saveXML( $item);
        $db_data['xml']                         = $db_data['xml'];
        $db_data['geocode']                     = $this->get_node_value($item,'feedsyncGeocode');
        $db_data['street']                      = '';
        $db_data['suburb']                      = '';
        $db_data['state']                       = '';
        $db_data['postcode']                    = '';
        $db_data['country']                     = '';
        $db_data['address']                     = '';
        if( $this->has_node($item,'address') ) {
            $db_data['address']     = $this->get_address($item,true);
            // address components are available only after get_address method call **/
            $db_data['street']      = $this->get_address_component('street');
            $db_data['suburb']      = $this->get_address_component('suburb');
            $db_data['state']       = $this->get_address_component('state');
            $db_data['postcode']    = $this->get_address_component('postcode');
            $db_data['country']     = $this->get_address_component('country');
        }

        return $db_data;

    }

    function logger_log($msg) {

        if( is_logging_enabled() ) {
            $this->logger->log($msg);
        }
    }

    function complete_log() {

        if( !is_logging_enabled() )
            return;

        $summary    = "Processing completed : ".date('[Y-m-d H:i:s]').PHP_EOL;
        $summary    .= $this->log_report['listings_created']." listings created ".PHP_EOL;
        $summary    .= $this->log_report['listings_updated']." listings updated ".PHP_EOL;
        $summary    .= $this->log_report['listings_skipped']." listings skipped ".PHP_EOL;

        if( intval($this->log_id) > 0) {

            $query = "UPDATE feedsync_logs SET
                status              = 'complete',
                summary             = '{$summary}'
                WHERE id            = '{$this->log_id}'";

            $this->db->query($query);
        }

    }

    function force_limited_logs() {

        $max_logs = get_option('feedsync_max_logs');
        $max_logs = $max_logs == '' ? 100 : intval($max_logs);
        $query = "SELECT * FROM
            feedsync_logs ORDER BY id DESC LIMIT $max_logs,99999999";

        $old_logs =  $this->db->get_results($query);

        if( !empty($old_logs) ) {
            foreach ($old_logs as $key => $details) {
                $log_path = get_path('logs').$details->log_file;
                
                if (file_exists($log_path)) {
                    @unlink($log_path);
                }

                $del_query = "DELETE FROM feedsync_logs WHERE id = {$details->id}";

                $this->db->get_results($del_query);
            }
        }
    }

    function init_log($file = '') {

        if( !is_logging_enabled() )
            return;

        if( defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['action']) ) {
            $action = filter_var($_REQUEST['action'], FILTER_SANITIZE_STRING);
        } elseif( defined('DOING_CRON') && DOING_CRON && isset($_REQUEST['action']) ) {
            $action = filter_var($_REQUEST['action'], FILTER_SANITIZE_STRING);
        }else{
            $action = 'cron import';
        }

        if($file == '' && isset($this->path) ) {
            $file = basename($this->path);
        }

        $log_file = generate_uuid().'.log';

        $log_file_path = get_path('logs').$log_file;

        if ( $file_handle = @fopen($log_file_path , 'a' ) ) {
            fwrite( $file_handle, '' );
            fclose( $file_handle );
            @chmod($log_file_path, 0644);
        }


        if($file_handle) {

            $query = "INSERT INTO
            feedsync_logs (file_name,action,status,summary,log_file)
            VALUES (
                '{$file}',
                '{$action}',
                'pending',
                '',
                '{$log_file}'
            )";

            $entry_created =  $this->db->query($query);

            /** log file is created && entry to db as well */
            if($entry_created) {

                $this->log_file     = $log_file;
                $this->log_id       = $this->db->insert_id;
                $this->logger       = new PHPLogger($log_file_path);
            }

            $this->force_limited_logs();
        }
    }

    /**
     * Import listings to database
     * @return json
     */
    function import(){

        if( empty($this->elements) ) {
            die( json_encode(array('status' =>  'success', 'message'    =>  'All files have been processed.', 'buffer'   =>  'complete')) );
        }

        $this->init_log();

        $this->logger_log('==== File processing Initiated  : '.basename($this->path).' ===='.PHP_EOL);

        foreach($this->elements->childNodes as $item) {

            if( isset($item->tagName) && !is_null($item->tagName) ) {

                $this->logger_log('---- Listing Process Initiated ----'.PHP_EOL);

                /** process agents **/
                $this->logger_log('Agent processing initiated...');
                $this->process_agents($item);
                $this->logger_log('Agent processing completed');

                /** process geocode **/
                $this->logger_log('Geocode processing initiated...');
                $this->geocode($item);
                $this->logger_log('Geocode processing completed');

                /** process image **/
                $this->logger_log('Image processing initiated...');
                $this->process_image($item);
                $this->logger_log('Image processing complted');

                /** add nodes */
                $this->logger_log('Node processing initiated...');
                $this->epl_nodes($item);
                $this->logger_log('Node processing completed');

                $db_data = $this->get_initial_values($item);
                $this->logger_log('Fetched initial values');

                /** check if listing exists already **/
                $exists = $this->db->get_row("SELECT * FROM feedsync where feedsync_unique_id = '{$db_data['feedsync_unique_id']}' ");

                if( !empty($exists) ) {

                    $this->logger_log('Duplicate listing detected with ID : '.$exists->id);

                    /** update if we have updated data **/
                    if(  strtotime($exists->mod_date) < strtotime($db_data['mod_date']) ) {

                        $this->logger_log('Updated content detected. New Mode Time : '.$db_data['mod_date'].'. Old Mode Time : '.$exists->mod_date);

                        /** add firstDate node to xml if its already not there **/

                        if ( !$this->has_node($item,'firstDate')) {

                            $firstDateValue             = $this->add_node($this->xmlFile,'firstDate',$exists->firstdate);
                            $item->appendChild($firstDateValue);
                            $db_data['xml']             = $this->xmlFile->saveXML( $item);
                            $this->logger_log('First Date not found, added firstDate:'.$exists->firstdate);
                        }

                        if ( get_post_meta($exists->id,'fav_listing',true) == 'yes' ) {

                            if ( !$this->has_node($item,'feedsyncFeaturedListing')) {

                                $fav             = $this->add_node($this->xmlFile,'feedsyncFeaturedListing','yes');
                                $item->appendChild($fav);
                                $db_data['xml']             = $this->xmlFile->saveXML( $item);
                                $this->logger_log('Fav listing detected, Set as fav');
                            }
                        }

                        /** check if this xml has img node */
                        if ( !$this->has_node($item,'img') ) {

                            $this->logger_log('No images detected, checking in existing xml in DB');

                            /** remove blank images node from XML */
                            if ( $this->has_node($item,'images') ) {
                                $parent_image_node = $this->get_first_node($item,'images');
                                $parent_image_node->parentNode->removeChild($parent_image_node);
                                $this->logger_log('blank images node removed');
                            }

                            /** Load existing xml from database */
                            $existing_xml = new DOMDocument('1.0', 'UTF-8');
                            $existing_xml->preserveWhiteSpace = FALSE;
                            $existing_xml->loadXML($exists->xml);
                            $existing_xml->formatOutput = TRUE;
                            
                            /** Import images node to XML if its not empty */
                            if( $this->has_node($existing_xml,'img') && $this->has_node($existing_xml,'images') ) {

                                /** fetch images node from xml in DB */
                                $backup_images = $this->get_first_node($existing_xml,'images');

                                $this->logger_log('images found in existing xml in DB');
                                $backup_images_node  = $this->xmlFile->importNode($backup_images, true);
                                $item->appendChild($backup_images_node);
                                $db_data['xml']             = $this->xmlFile->saveXML( $item);
                                $this->logger_log('Copied images from existing xml in DB');
                            }
                            
                        }


                        /** dont update whole xml if address is missing **/
                        if ( !$this->has_node($item,'address') ) {

                            $this->logger_log('Address missing, skip updating whole xml');

                            $existing_xml = new DOMDocument('1.0', 'UTF-8');
                            $existing_xml->preserveWhiteSpace = FALSE;
                            $existing_xml->loadXML($exists->xml);
                            $existing_xml->formatOutput = TRUE;
                            $existing_listing = $existing_xml->getElementsByTagName('*');
                            $existing_listing->item(0)->setAttribute('modTime', $db_data['mod_date']);
                            $existing_listing->item(0)->setAttribute('status', $db_data['status']);
                            $db_data['xml'] = $existing_xml->saveXML($existing_listing->item(0));
                            $existing_listing_item  = $existing_listing->item(0);

                            if($existing_listing->item(0)->getElementsByTagName("address")->length != 0) {
                                $db_data['address']     = $this->get_address($existing_listing_item);
                            }

                            if( $this->has_node($existing_listing_item,'feedsyncGeocode'))
                                $db_data['geocode'] = $this->get_node_value($existing_listing_item,'feedsyncGeocode');

                        }

                        $db_data    =   array_map(array($this->db,'escape'), $db_data);

                        $this->update_listing($db_data);
                        $this->log_report['listings_updated']++;
                        $this->logger_log('---- Updated listing ----'.PHP_EOL);
                    } else {
                        $this->log_report['listings_skipped']++;
                        $this->logger_log('---- No Updated content, Skipping ---- '.PHP_EOL);
                    }

                } else {

                    $this->logger_log('New listing detected');

                    /** insert firstDate node **/

                    if ( !$this->has_node($item,'firstDate')) {

                        $firstDate      = $this->xmlFile->createElement('firstDate', $db_data['mod_date']);
                        $item->appendChild($firstDate);
                        $this->logger_log('First Date added firstDate:'.$db_data['mod_date']);
                    }

                    $db_data['xml'] = $this->xmlFile->saveXML( $item);
                    $db_data        =   array_map(array($this->db,'escape'), $db_data);

                    $this->insert_listing($db_data);
                    $this->log_report['listings_created']++;
                    $this->logger_log('---- Inserted listing ----'.PHP_EOL);
                }
            }

        }

        $this->logger_log('---- File processing complete ----');

        try {
            if( rename($this->path,$this->get_path('processed').basename($this->path) ) ) {

                $this->logger_log('---- File successfully moved to processed folder ----');
                $this->complete_log();

                if(!$this->cron_mode) {
                    die(
                        json_encode(
                            array(
                                'status'    =>  'success',
                                'message'   =>  basename($this->path).'  processed .'.$this->total_files.' files remaining. Currently processing your files.',
                                'geocoded'  =>  $this->geocoded_addreses_list,
                                'buffer'    =>  'processing'
                            )
                        )
                    );

                } else {
                    echo json_encode(
                        array(
                            'status'    =>  'success',
                            'message'   =>  basename($this->path).'  processed .'.$this->total_files.' files remaining. <br /> Currently processing your files, do not navigate away from this page.',
                            'geocoded'  =>  $this->geocoded_addreses_list,
                            'buffer'    =>  'processing'
                        )
                    );

                    $this->reset();
                    $this->init();
                    $this->import();
                }
            }
        } catch(Exception $e) {
            if(!$cron_mode) {
                $this->logger_log('---- file moving error : '.$e->getMessage() );
                echo $e->getMessage(); die;
            }
        }

    }


}

