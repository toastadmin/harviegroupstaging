<?php

namespace FEEDSYNCMLS;

require_once(CORE_PATH.'classes/mls/vendor/autoload.php');

use \PHRETS\Configuration;
use \PHRETS\Session;
use \Monolog\Logger;

class MLS {

    /**
     * Rets configuration params
     * @var [type]
     */
    public $config;

    /**
     * Array containing all resources and its classes offered by rets server
     * @var [type]
     */
    public $resources;

    /**
     * Init mls class
     * @since :      1.0.0
     * @param [type] $url     [description]
     * @param [type] $user    [description]
     * @param [type] $pass    [description]
     * @param [type] $version [description]
     */
    function __construct($url,$user,$pass,$version) {
        
        $this->url = $url;
        $this->user = $user;
        $this->pass = $pass;
        $this->version = $version;
        $this->set_log_config();
        $this->create_session();
        if( $this->login() ){
            $this->set_resources_and_classes();
            $this->set_resource_objects();

        }
        // $results = $this->search('Property','Listing','(ListAgent_MUI = 9629564)',2,2);
        // echo "<pre>";
        // print_r($results->toArray() ); die;
    }

    /**
     * Set logger and configuration for rets
     * @since : 1.0.0
     */
    function set_log_config() {

        /** @var Logger [description] */
        $this->log = new Logger('PHRETS');
        $this->log->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));

        /** configuration */
        $this->config = new Configuration;
        $this->config->setLoginUrl($this->url)
        ->setUsername($this->user)
        ->setPassword($this->pass)
        ->setRetsVersion($this->version);
    }

    /**
     * Create session with rets server for transactions
     * @since  :      1.0.0
     * @return [type] [description]
     */
    function create_session() {

        $this->session = new \PHRETS\Session($this->config);

        /** also set logger */
        $this->session->setLogger($this->log);
    }

    /**
     * Login to rets server
     * @since  :      1.0.0
     * @return [type] [description]
     */
    function login() {
        try {
            $connect = $this->session->Login();
            $status = is_a($connect, '\PHRETS\Models\Bulletin') ? true : false;
            
        } catch( \GuzzleHttp\Exception\ClientException  $e) {

            add_sitewide_notices($e->getMessage(),$code='danger');
            $status =  false;
        }
        return $status;
    }

    /**
     * Get system metadata
     * @since  :      1.0.0
     * @return [type] [description]
     */
    function get_system_metadata() {
        try {
            return $this->session->GetSystemMetadata();
        } catch( \PHRETS\Exceptions\CapabilityUnavailable $e) {
            echo $e->getMessage();
            die;
        }
    }

    /**
     * Get capabilities offered by rets server to active account
     * @since  :      1.0.0
     * @return [type] [description]
     */
    function get_capabilities() {

        $caps = $this->session->getCapabilities();
        $caps =  (array) unserialize( serialize($caps) );
        return $caps;
    }

    /**
     * Check if active user has a particular capability
     * @since  :       1.0.0
     * @param  [type]  $cap  [description]
     * @return boolean       [description]
     */
    function is_capable($cap) {

        $caps = $this->get_capabilities();
        return isset($caps[$cap]) ? true : false;
    }

    /**
     * Set resources and their classes in single array
     * @since : 1.0.0
     */
    function set_resources_and_classes() {

        $system = $this->get_system_metadata();
        $resources = $system->getResources();
        foreach($resources as $key => $resource) {
            
            $classes = $resource->getClasses();

            $array = (array)$resource;
            $prefix = chr(0).'*'.chr(0);
            $this->resources[$key]['values'] =   $array[$prefix.'values'];
            foreach($classes as $class_key => $class) {
                $class_array = (array)$class;
                $prefix = chr(0).'*'.chr(0);
                $this->resources[$key]['classes'][$class_key] =   $class_array[$prefix.'values'];
            }

        }
    }

    /**
     * Get classes of a resource
     * @since  :      1.0.0
     * @param  [type] $resource_id [description]
     * @return [type]              [description]
     */
    function get_classes($resource_id) {
        return isset($this->resources[$resource_id]) ? $this->resources[$resource_id] : false;
    }

    /**
     * Get meta fields of a class
     * @since  :      1.0.0
     * @param  [type] $resource [description]
     * @param  [type] $class    [description]
     * @return [type]           [description]
     */
    function get_class_fields($resource,$class) {

       $fields = $this->session->GetTableMetadata($resource, $class);
       return $this->return_values($fields);
    }

    /**
     * Returns the unique key from all fields
     * @since  :      1.0.0
     * @param  [type] $fields field array as returned from get_class_fields method
     * @return [type]         key if found or false
     */
    function get_field_unique_id($fields) {

        foreach($fields as $key => $field) {
            if($field['Unique'] == 1)
                return $key;
        }

        return false;
    }

    /**
     * Get lookup values for a class field
     * @since  :      1.0.0
     * @param  [type] $resource [description]
     * @param  [type] $lookup   [description]
     * @return [type]           [description]
     */
    function get_lookup_values($resource,$lookup) {

        $fields = $this->session->GetLookupValues($resource,$lookup);
        return $this->return_values($fields);
    }

    private function set_resource_objects() {

        foreach($this->resources as $resource_key => $resource_data) {
            $objects = $this->session->GetObjectMetadata($resource_key);
            $objects = $this->return_values($objects);
            if( !empty($objects) ) {
                $this->resources[$resource_key]['objects'] = $objects;
            }
        }
    }

    /**
     * Get objects offered by resources
     * @since  :      1.0.0
     * @param  [type] $resource_id [description]
     * @return [type]              [description]
     */
    function get_resource_objects($resource_id) {

        $objects = $this->session->GetObjectMetadata($resource_id);
        return $this->return_values($objects);
    }

    /**
     * Handy function to convert a complex rets object to simple array
     * @since  :      1.0.0
     * @param  [type] $fields [description]
     * @return [type]         [description]
     */
    function return_values($fields) {

        $array = (array)$fields;
        $prefix = chr(0).'*'.chr(0);

        $field_array = array();
        foreach($fields as $key => $field) {
            $field = (array)$field;
            $prefix = chr(0).'*'.chr(0);
            $field_array[$key] =   $field[$prefix.'values'];
        }

        return $field_array;
    }

    function search($resource,$class,$query = '*',$limit = 10 ,$offset = 0,$args = array()) {

        $default_args = array(
            'QueryType'     => 'DMQL2',
            'Count'         => 1, // count and records
            'Format'        => 'COMPACT-DECODED',
            'Limit'         => intval($limit),
            'StandardNames' => 0,
            'Offset'        => intval($offset)
        );

        $args = array_merge($default_args, $args);


        if( is_array($query) ) {
            $query_string = array();
            foreach( $query as $k => $v){
                $query_string[] = '('.$k.' = '.$v.' )';
            }
            $query = implode(', ', $query_string);
        }
        
        $this->last_search = $this->session->Search($resource,$class,$query,$args);

        return $this->last_search;
    }

    /**
     * Returns the total result of the last search query
     * @since  :      1.0.0
     * @return [type] [description]
     */
    function get_total_search_results() {
        return $this->last_search->getTotalResultsCount();
    }

    /**
     * Get objects for classes
     * @since  :      1.0.0
     * @param  [type] $resource   resource id
     * @param  [type] $object     object name
     * @param  [type] $content_id  id of the class item example : listing id
     * @param  string $object_ids [description]
     * @return [type]             [description]
     */
    function get_objects($resource,$object,$content_id,$object_ids="*") {
        return $this->session->GetObject($resource,$object,$content_id,$object_ids);
    }


    /**
     * Get listing types offered by MLS
     * @since  :      1.0.0
     * @return [type] [description]
     */
    function get_lookup_as_array($res,$field) {
        $types =  $this->get_lookup_values($res,$field);
        $return = array();
        if( !empty($types) ) {
            foreach($types as $type) {
                $return[$type['Value']] = $type['LongValue'];
            }
        }
        return $return;
    }
}