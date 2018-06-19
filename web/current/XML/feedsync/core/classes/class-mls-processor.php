<?php
include('class-processor.php');
include_once(CORE_PATH.'classes/class-array-to-xml.php');

class MLS_PROCESSOR extends FEEDSYNC_PROCESSOR {

    private $logger;

    function __construct() {

        global $feedsync_db;

        // setting cron mode to false so that it shows message & status for processing
        $this->cron_mode    = false;
        $this->db           = $feedsync_db;
        $this->logger = new PHPLogger(SITE_ROOT.'mls.log');

        $this->process_params();

        require_once(CORE_PATH.'classes/class-mls.php');

        $url        = get_option('feedsync_mls_login_url');
        $user       = get_option('feedsync_mls_user_name');
        $pass       = get_option('feedsync_mls_password');

        if( !empty($url) && !empty($user) && !empty($pass) )
              $this->mls = new \FEEDSYNCMLS\MLS($url,$user,$pass,'1.5');
        else
              $this->mls = false;

        if( !$this->mls ) {
              json_encode( array('status'   =>    'fail', 'message' =>    'unable to connect to mls server') );
              die;
        }


    }

    /**
       * Performs implode with ,
       * @param  array $val
       * @return string
       */
      function array_to_csv($val) {
            if(is_array($val))
                  return implode(',', array_filter($val) );
            else
                  return $val;
      }

    function process_params() {
            global $feedsync_options;
            $params = array();
            foreach($feedsync_options as $key   =>    $value) {
                  if(startsWith($key,'mls_') ) {

                        $params[str_replace('mls_','',$key)] = $value;
                  }
            }
            $this->params = array_filter(array_map(array($this,'array_to_csv'), $params));
      }

    function get_sub_path(){
        return '';
    }

    function configuration() {

        /** configuration **/
        $this->xmlFile = new DOMDocument('1.0');
        libxml_use_internal_errors(true);
        $this->xmlFile->formatOutput = true;
        $this->xmlFile->preserveWhiteSpace = false;
        $this->xmlFile->recover = TRUE;
        $this->xmlFile->loadXML($this->xml_data);
        $this->xpath = new DOMXPath($this->xmlFile);
        /** configuration - end **/

        /** check if xml is not empty & and is valid **/
        $this->handle_blank_xml();
        $this->handle_invalid_xml();

        /** extract dom elements and cache it as class property **/
        $this->dom_elements();
    }

    function handle_blank_xml() {}

    function handle_invalid_xml() {}
    /**
     * parses dom elements to be procesessed in file
     * @return [type]
     */
    function dom_elements() {
        $this->elements = $this->xmlFile->documentElement;
        $this->item     = current($this->elements);
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

        // return item for further processing;
        return $item;

    }

    /**
     * get address from a listing element
     * @param  [domDocument Object]
     * @param  boolean
     * @return [mixed]
     */
    function get_address($item,$comma_seperated = true) {

        $this->address['streetnumber']      = $this->get_node_value($item,'StreetNumber');
        $this->address['street']            = $this->get_node_value($item,'StreetName');
        $this->address['suburb']            = $this->get_node_value($item,'City');
        $this->address['state']             = $this->get_node_value($item,'StateOrProvince');
        $this->address['postcode']          = $this->get_node_value($item,'PostalCode');

        return $comma_seperated == true ? implode(", ", $this->address) : implode(" ", $this->address);

    }

    /**
     * Geocode the listing item :)
     * @param  [domDocument Object]
     * @return [domDocument Object]
     */
    function geocode($item,$process_missing = false){

        $this->geocoded_addreses_list = "\n";

        /** add feedsyncGeocode node if not already there or if force geocode mode is on **/
        if( !$this->has_node($item,'feedsyncGeocode') || $this->force_geocode() ) {

            // if item has geocode node, extract value from it and save it to feedsyncGeocode node
            if( $this->has_node($item,'latitude') && $this->get_node_value($item,'latitude') > 0 ) {


                $item = $this->geocode_from_geocode_node($item);

            } else {

                // if item doesnt have geocode node, geocode it
                if( $this->geocode_enabled() || $this->force_geocode()  || $process_missing )
                    $item = $this->geocode_from_google($item);
            }
        } else {
           $this->coord = $this->get_node_value($item,'feedsyncGeocode');
        }

        return $item; // return processed item
    }

    /**
     * attempts to fetch geocode from geocode node, if present
     * @param  [domDocument Object]
     * @return [domDocument Object]
     */
    function geocode_from_geocode_node($item){

         $lat                = $this->get_node_value($item,'latitude');
         $long               = $this->get_node_value($item,'longitude');

         // make coordinates class wide available
         $this->coord        = $lat.','.$long;

        return $this->update_feedsync_node($item,$coord);
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

                return $this->update_feedsync_node($item,$this->coord);
            } else {

                return $item;
            }
        }
    }


    function add_required_nodes_and_atts($item,$data) {

        $item->setAttribute('status',$data['status']);
        $item->setAttribute('modTime',$data['mod_date']);
        return $item;
    }

    function guess_property_type($item) {


        $department = $this->get_node_value($item,'PropertyType');

        $map = array(
            'Residential Rental'  =>  'rental',
        );
        $type = isset( $map[$department] ) ? $map[$department] : 'residential';

        $this->set_node_value($item,'PropertyType',$type);

        return $type;
    }

    /**
     * get initial values required to be updated / insereted for listing
     * @param  domDocument Object
     * @return domDocument Object
     */
    function get_initial_values($item){

        $db_data                    = array();
        $db_data['type']            = $this->guess_property_type($item);
        $db_data['unique_id']       = $this->get_node_value($item,'Matrix_Unique_ID');
        $db_data['agent_id']        = $this->get_node_value($item,'ListOffice_MUI');
        $db_data['feedsync_unique_id']          = $this->get_node_value($item,'feedsyncUniqueID');
        $mod_date                   = $this->get_node_value($item,"MatrixModifiedDT");
        $db_data['mod_date']        = date("Y-m-d H:i:s",strtotime($mod_date));
        $db_data['status']          = $this->get_node_value($item,"Status");
        $db_data['geocode']         = '';

        if( $this->geocode_enabled() )
            $db_data['geocode']         = $this->get_node_value($item,'feedsyncGeocode');

        $street_num = $this->get_node_value($item,'StreetNumber');
        $db_data['street']          = $street_num.' '.$this->get_node_value($item,'StreetName');
        $db_data['suburb']          = $this->get_node_value($item,'City');
        $db_data['state']           = $this->get_node_value($item,'StateOrProvince');
        $db_data['postcode']        = $this->get_node_value($item,'PostalCode');
        $db_data['country']         = 'US';
        $db_data['address']         = $this->get_address($item,true);

        $item                       = $this->add_required_nodes_and_atts($item,$db_data);
        $db_data['xml']             = $this->xmlFile->saveXML( $item);

        return $db_data;

    }

    function settings_mapped() {

            return array(
                  'property_type'           =>    'PropertyType',
                  'property_status'         =>    'Status',
                  'listing_agent_id'        =>    'ListAgent_MUI',
                  'property_city'           =>    'City',
                  'property_state'          =>    'StateOrProvince',
                  'property_bed'            =>    'BedsTotal',
                  'property_bath'           =>    'BathsTotal'
            );
    }

    /**
     * Add EPL nodes
     *
     * Add Image mod date
     * @return [type]
     */
    function epl_nodes($item) {

        $node_to_add = !empty($this->xmlFile) ? $this->xmlFile : $item;
        
        $image_mod_date                   = $this->get_node_value($item,'PhotoModificationTimestamp');
        if($image_mod_date == '') {
            $image_mod_date = date("Y-m-d H:i:s",time());
        }
        $image_mod_date        = feedsync_format_date( $image_mod_date );


        if( ! $this->has_node($item,'feedsyncImageModtime') ) {
            // if node not already exists, add it

            $element = $this->add_node($node_to_add,'feedsyncImageModtime',$image_mod_date);
            $item->appendChild($element);
        } else {
            // if node already exists, just update the value
            $item = $this->set_node_value($item,'feedsyncImageModtime',$image_mod_date);
        }

        /** Feedsync Unique ID ( Unique ID + Agent ID ) */

        $feedsync_unique_id = $this->get_node_value($item,'Matrix_Unique_ID');

        if( $this->has_node($item,'ListOffice_MUI') ) {

            $feedsync_unique_id = $this->get_node_value($item,'ListOffice_MUI').'-'.$feedsync_unique_id;

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

        if(!empty($this->xmlFile) ) {
            $this->xmlFile->save($this->path);
        }

        return $item;
    }

    /**
     * Import listings to database
     * @return json
     */
    function import(){

        $mls_fetched_since = get_option('mls_fetched_since') ? get_option('feedsync_mls_offset') : '1970-01-31T11:18:55.397+';
        $params = array('MatrixModifiedDT'  =>  $mls_fetched_since);
        $param_map = $this->settings_mapped();
        foreach($this->params as $p_key => $param){
              $key = isset($param_map[$p_key]) ? $param_map[$p_key] : $p_key;
              $params[$key] = $param;
        }

        $per_page           = get_option('feedsync_mls_listings_per_request');
        $mls_listing_offset = get_option('feedsync_mls_offset') ? get_option('feedsync_mls_offset') : 0;

        /** search for listings */
        $this->results = $this->mls->search('Property','Listing',$params,$per_page,$mls_listing_offset + 1);
        $this->result_array = $this->results->toArray();

        if( empty($this->result_array) ) {
            die(
                json_encode(
                    array(
                        'status'    =>  'success',
                        'message'   =>  'Search query returned no results or no updates since last sync',
                        'geocoded'  =>  '',
                        'buffer'    =>  'complete'
                    )
                )
            );
        }
        $xml = Array2XML::createXML('propertyList', array('property'    =>  $this->results->toArray()) );
        $this->xml_data =  $xml->saveXML();
        $this->configuration();

        foreach($this->elements->childNodes as $item) {

            if( isset($item->tagName) && !is_null($item->tagName) ) {

                /** process agents **/
                $this->process_agents($item);

                /** process geocode **/
                if( $this->geocode_enabled() )
                    $this->geocode($item);

                /** process image **/
                if( get_option('feedsync_mls_process_images') == 'on')
                    $this->process_images($item);

                $this->epl_nodes($item);

                $db_data = $this->get_initial_values($item);

                /** check if listing exists already **/
                $exists = $this->db->get_row("SELECT * FROM feedsync where feedsync_unique_id = '{$db_data['feedsync_unique_id']}' ");

                if( !empty($exists) ) {

                    /** update if we have updated data **/
                    if(  strtotime($exists->mod_date) < strtotime($db_data['mod_date']) ) {

                        /** add firstDate node to xml if its already not there **/

                        if ( !$this->has_node($item,'firstDate')) {

                            $firstDateValue             = $this->add_node($this->xmlFile,'firstDate',$exists->firstdate);
                            $item->appendChild($firstDateValue);
                            $db_data['xml']             = $this->xmlFile->saveXML( $item);
                        }

                        if ( get_post_meta($exists->id,'fav_listing',true) == 'yes' ) {

                            if ( !$this->has_node($item,'feedsyncFeaturedListing')) {

                                $fav             = $this->add_node($this->xmlFile,'feedsyncFeaturedListing','yes');
                                $item->appendChild($fav);
                                $db_data['xml']             = $this->xmlFile->saveXML( $item);
                            }
                        }

                        $db_data    =   array_map(array($this->db,'escape'), $db_data);

                        $this->update_listing($db_data);
                    }

                } else {

                    /** insert firstDate node **/

                    if ( !$this->has_node($item,'firstDate')) {

                        $firstDate      = $this->xmlFile->createElement('firstDate', $db_data['mod_date']);
                        $item->appendChild($firstDate);
                    }

                    $db_data['xml'] = $this->xmlFile->saveXML( $item);
                    $db_data        =   array_map(array($this->db,'escape'), $db_data);

                    $this->insert_listing($db_data);
                }
            }

        }

        $mls_listing_offset = $mls_listing_offset + $per_page ;

        update_option('feedsync_mls_offset',$mls_listing_offset);

        try {
            if( $this->results->getTotalResultsCount() <=  $mls_listing_offset ) {

                update_option('feedsync_mls_offset',0);
                update_option('mls_fetched_since', date('Y-m-d\TH:i:s.000',time() ));

                if(!$this->cron_mode) {

                    $res_data = json_encode(
                                    array(
                                        'params'        =>  array($params,'per_page'    =>  $per_page,'offset'    =>  $mls_listing_offset),
                                        'max_reached'   =>  $this->results->isMaxRowsReached(),
                                        'status'    =>  'success',
                                        'message'   =>  'all listings processed',
                                        'geocoded'  =>  $this->geocoded_addreses_list,
                                        'buffer'    =>  'complete'
                                    )
                                );
                    $this->logger->i('MLS IMPORT',$res_data );
                    die($res_data);
                }

            } else {

                if(!$this->cron_mode) {
                    $res_data = json_encode(
                                    array(
                                        'params'        =>  array($params,'per_page'    =>  $per_page,'offset'    =>  $mls_listing_offset),
                                        'max_reached'   =>  $this->results->isMaxRowsReached(),
                                        'status'    =>  'success',
                                        'message'   =>  $mls_listing_offset.'  of '.$this->results->getTotalResultsCount().' listings processed <br> <strong>Currently processing your listings, do not navigate away from this page. </strong>',
                                        'geocoded'  =>  $this->geocoded_addreses_list,
                                        'buffer'    =>  'processing'
                                    )
                                );
                    $this->logger->i('MLS IMPORT',$res_data );
                    die($res_data);
                }
            }
        } catch(Exception $e) {
            if(!$cron_mode) {
                echo $e->getMessage(); die;
            }
        }

    }

    /**
     * Processes image
     * @return [domDocument Object]
     */
    function process_images($item) {

        $node_to_add = !empty($this->xmlFile) ? $this->xmlFile : $item;

        $unique_id = $this->get_node_value($item,'Matrix_Unique_ID');

        $image_mod_date = $this->get_node_value($item,'PhotoModificationTimestamp');


        $objects = $this->mls->get_objects('Property', 'LargePhoto', $unique_id, '*', 0);
        if( !empty( $objects) ) {

            /** Add parent images node */
            $listing = $item->getElementsByTagName('Property')->item(0);
            $photo_node  = $this->add_node($node_to_add,'images','');
            $item->appendChild($photo_node);
            $photo_node->setAttribute('modTime',date("Y-m-d H:i:s",strtotime($image_mod_date)) );

            foreach($objects as $object) {

                /** Skip the first image objectID = 0 as its same as objectID = 1 */
                if($object->getObjectId() == 0)
                    continue;
                
                $path = $this->get_path('images')."{$object->getContentId()}-{$object->getObjectId()}.jpg";
                $url = $this->get_url('images')."{$object->getContentId()}-{$object->getObjectId()}.jpg";

                file_put_contents($path,  $object->getContent());
                $this_node = $this->add_node($node_to_add,'img',$url);
                $photo_node->appendChild($this_node);
            }
        }

    }

    /**
     * Import agents to database
     */
    function process_agents($item){

        $node_to_add = !empty($this->xmlFile) ? $this->xmlFile : $item;


        $listing_agent  = $this->add_node($node_to_add,'listingAgent','');
        $item->appendChild($listing_agent);

       /** init values **/
        $data_agent                     = array();
        $data_agent['agent_id']         = $this->get_node_value($item,'ListAgent_MUI');
        $data_agent['office_id']        = $this->get_node_value($item,'ListOffice_MUI');
        $data_agent['name']             = $this->get_node_value($item,'ListAgentFullName');
        $data_agent['telephone']        = $this->get_node_value($item,'ListOfficePhone');
        $data_agent['email']            = '';
        $data_agent['username']         = '';

        if( !$this->has_node($listing_agent,'office_id') ) {
            $create_office_id       = $node_to_add->createElement('office_id', $data_agent['office_id']);
            $listing_agent->appendChild($create_office_id);
        }

        if( !$this->has_node($listing_agent,'telephone') ) {
            $telephone       = $node_to_add->createElement('telephone', $data_agent['telephone']);
            $listing_agent->appendChild($telephone);
        }


        if( !$this->has_node($listing_agent,'agent_id') ) {
            $agent_id       = $node_to_add->createElement('agent_id', $data_agent['agent_id']);
            $listing_agent->appendChild($agent_id);
        }

        if( !$this->has_node($listing_agent,'name') ) {

            $name       = $node_to_add->createElement('name', $data_agent['name']);
            $listing_agent->appendChild($name);
            $agent_full_name        = explode(' ',$data_agent['name']);

            if( !$this->has_node($listing_agent,'agentFirstName') ) {
                $agent_first        = $agent_full_name[0];
                $create_fname       = $node_to_add->createElement('agentFirstName',  htmlentities($agent_first) );
                $listing_agent->appendChild($create_fname);
            }
            if( !$this->has_node($listing_agent,'agentLastName') ) {
                $agent_last         = isset($agent_full_name[1]) ? $agent_full_name[1] : '';
                $create_lname       = $node_to_add->createElement('agentLastName', htmlentities($agent_last) );
                $listing_agent->appendChild($create_lname);
            }
            if( !$this->has_node($listing_agent,'agentUserName') ) {
                $create_uname       = $node_to_add->createElement('agentUserName',sanitize_user_name($data_agent['name']));
                $listing_agent->appendChild($create_uname);
                $data_agent['username'] = sanitize_user_name($data_agent['name']);
            } else {
                $data_agent['username']       = $this->get_node_value($listing_agent,'agentUserName');
            }

        }

        $data_agent['xml']  = $node_to_add->saveXML( $listing_agent);
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

            //print_exit($query);
            $this->db->query($query);
            //print_exit($this->db);
        }

    }

    function get_result_counts() {

        $mls_fetched_since = get_option('mls_fetched_since') ? get_option('feedsync_mls_offset') : '1970-01-31T11:18:55.397+';
        $params = array('MatrixModifiedDT'  =>  $mls_fetched_since);
        $param_map = $this->settings_mapped();
        foreach($this->params as $p_key => $param){
              $key = isset($param_map[$p_key]) ? $param_map[$p_key] : $p_key;
              $params[$key] = $param;
        }

        $per_page           = get_option('feedsync_mls_listings_per_request');
        $mls_listing_offset = get_option('feedsync_mls_offset') ? get_option('feedsync_mls_offset') : 0;

        /** search for listings */
        $results = $this->mls->search('Property','Listing',$params);
        return $results->getTotalResultsCount();
    }
}