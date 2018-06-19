<?php

$feedsync_hook->add_action('feedsync_form_exporter','feedsync_form_exporter');

function feedsync_form_exporter() {


    global $feedsync_db;
    $query = "SELECT * FROM feedsync WHERE 1 = 1 ";


    $types      = array('rental','property','residential','commercial','land','rural','business','commercialLand','holidayRental');
    $type       = trim($_POST['listingtype']);

    $statuses       = array('leased','sold','withdrawn','current','offmarket','invalid','deleted');
    $status         = trim($_POST['listingstatus']);

    if( in_array($type,$types) ) {
        $query .= " AND type = '{$type}' ";
    }

    if( isset($_GET['recent']) ) {
        $query .= " AND STR_TO_DATE(mod_date,'%Y-%m-%d') = CURDATE() ";
    }

    if( in_array($status,$statuses) ) {
        $query .= " AND status = '{$status}' ";
    } elseif($status == 'all' ) {
        // Exclude Deleted and invalid
        $query .= " AND status NOT IN ('invalid','deleted') ";
    }else {
        $query .= " AND status NOT IN ('withdrawn','offmarket','invalid','deleted') ";
    }

    $results = $feedsync_db->get_results($query);
    export_data($results);

}

/*
    ** agents exporter form
    */
$feedsync_hook->add_action('feedsync_form_export_agents','feedsync_form_export_agents');

function feedsync_form_export_agents() {


    global $feedsync_db;
    $query = "SELECT * FROM feedsync_users WHERE 1 = 1 ";

    $results = $feedsync_db->get_results($query);
    export_data($results);

}

$feedsync_hook->add_action('feedsync_form_do_output','feedsync_form_do_output');
function feedsync_form_do_output() {

    global $feedsync_db;
    $type       = isset($_GET['type']) ? trim($_GET['type']) : '';

    $key_required = get_option('feedsync_enable_access_key');

    // settings not saved, get default
    if($key_required == false || $key_required == '') {
        $key_required = get_access_key_default_status();
    }

    if($key_required == 'on') {
        if( !isset($_GET['access_key']) ) {
            die( json_encode( array('status'    =>  'fail','message'    =>  'no access key provided')) );

        }

        if( isset($_GET['access_key']) && $_GET['access_key'] != get_option('feedsync_access_key')) {
            die( json_encode( array('status'    =>  'fail','message'    =>  'invalid access key')) );
        }
    }


    if($type == 'agents') {
        $query      = "SELECT * FROM feedsync_users WHERE 1 = 1 ";
    } else {

        $query      = "SELECT * FROM feedsync WHERE 1 = 1 AND address != '' ";
        $types      = array('rental','property','residential','commercial','land','rural','business','commercialLand','holidayRental');
        $filters    = array('suburb','street','state','postcode','country');

        foreach($filters as $filter) {
            if( isset($_GET[$filter]) ){
                $query .= " AND feedsync.{$filter} = '{$feedsync_db->escape($_GET[$filter])}' ";
            }
        }


        $statuses       = array('leased','sold','withdrawn','current','offmarket','invalid','deleted');
        $status         = isset($_GET['status']) ? trim($_GET['status']) : '';

        if( in_array($type,$types) ) {
            $query .= " AND feedsync.type = '{$type}' ";
        }

        $agent_id   = isset($_GET['agent_id']) ? $feedsync_db->escape(trim($_GET['agent_id'])): '';
        if( $agent_id != '' ) {
            $query .= " AND feedsync.agent_id = '{$agent_id}' ";
        }

        $listing_agent   = isset($_GET['listing_agent']) ? $feedsync_db->escape(trim($_GET['listing_agent'])): '';
        //$listing_agent   = ucwords( str_replace('-', ' ', $listing_agent) );
        if( $listing_agent != '' ) {
            $query .= " AND feedsync.xml LIKE '%<agentUserName>{$listing_agent}</agentUserName>%' ";
        }

        $date   = isset($_GET['date']) ? $feedsync_db->escape(trim($_GET['date'])) : '';
        if( $date != '' ) {
            if($date == 'today') {
                $date = date ( 'Y-m-d' );
            }
            $query .= " AND DATE(`mod_date`) = '{$date}' ";
        }
        $days_back   = isset($_GET['days_back']) ? $feedsync_db->escape(trim($_GET['days_back'])) : '';
        if( intval($days_back) > 0 ) {
            $date_today = date ( 'Y-m-d' );
            $days_back  = date ( 'Y-m-d', strtotime('- '.$days_back.' days') );
            $query .= " AND DATE(`mod_date`) BETWEEN '{$days_back}' AND  '{$date_today}'";
        }
        if( in_array($status,$statuses) ) {
            $query .= " AND status = '{$status}' ";
        }  elseif($status == 'all' ) {
            // exclude deleted and invalid
             $query .= " AND feedsync.status NOT IN ('invalid','deleted') ";
        } else {
            $query .= " AND feedsync.status NOT IN ('withdrawn','offmarket','invalid','deleted') ";
        }
    }
    $results = $feedsync_db->get_results($query);
    header("Content-type: text/xml");
    ob_start();
    echo '<?xml version="1.0" standalone="no"?>
<!DOCTYPE '.get_parent_element().' SYSTEM "http://reaxml.realestate.com.au/'.get_parent_element().'.dtd">
<'.get_parent_element().'>'."\n";

    if ( $results != '' ) {
        foreach($results as $listing) {
            echo $listing->xml."\n";
        }
    }
    echo '</'.get_parent_element().'>';
    $xml =  ob_get_clean();
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($xml);
    $dom->formatOutput = TRUE;
    echo $dom->saveXml();
    exit;
}

function feedsync_list_listing_type( $type = '', $status = '' ) {

    global $feedsync_db;
    $query = "SELECT * FROM feedsync WHERE 1 = 1 ";

    $types      = array('rental','property','residential','commercial','land','rural','business','commercialLand','holidayRental');
    $statuses   = array('leased','sold','withdrawn','current','offmarket','deleted');

    if( in_array($type,$types) ) {
        $query .= " AND type = '{$type}' ";
    }

    if( in_array($status,$statuses) ) {
        $query .= " AND status = '{$status}' ";
    }  elseif($status == 'all' ) {
        // do nothing
    } else {
        $query .= " AND status NOT IN ('withdrawn','offmarket','deleted') ";
    }
    
    $orders = array('id','address','street','suburb','state','country','postcode','geocode','firstdate','status','type','mod_date','agent_id','unique_id','feedsync_unique_id');

    foreach($orders as $filter) {
        $filter_value   = isset($_GET[$filter]) ? $feedsync_db->escape(trim($_GET[$filter])): '';
        if( $filter_value != '' ) {
            $query .= " AND {$filter} LIKE '%{$filter_value}%' ";
        }
    }

    if( !empty($_GET['orderby']) && in_array($_GET['orderby'],$orders) ) {
        $order = (isset($_COOKIE['order']) && in_array($_COOKIE['order'], array('ASC','DESC')) ) ? $_COOKIE['order'] : 'DESC';
        $query .= " order by {$_GET['orderby']} $order";
    }
    return $feedsync_db->get_results($query);

}

function feedsync_get_import_logs() {

    global $feedsync_db;
    $query = "SELECT * FROM feedsync_logs WHERE 1 = 1 order by id DESC ";

    return $feedsync_db->get_results($query);

}

function feedsync_render_log_table($results,$page) {

    ob_start();

    get_header('listings');
    get_listings_sub_header( $page );

    if( !empty($results) ) {

        // how many records should be displayed on a page?
        $records_per_page = get_option('feedsync_pagination',false);

        // include the pagination class
        require 'pagination.php';

        // instantiate the pagination object
        $pagination = new Zebra_Pagination();

        // set position of the next/previous page links
        $pagination->navigation_position(isset($_GET['navigation_position']) && in_array($_GET['navigation_position'], array('left', 'right')) ? $_GET['navigation_position'] : 'outside');

        // the number of total records is the number of records in the array
        $pagination->records(count($results));

        // records per page
        $pagination->records_per_page($records_per_page);

        // here's the magick: we need to display *only* the records for the current page
        $results = array_slice(
            $results,                                             //  from the original array we extract
            (($pagination->get_page() - 1) * $records_per_page),    //  starting with these records
            $records_per_page                                       //  this many records
        );

            $table = '<div class="listings-list-panel panel panel-default">
                        <table data-toggle="table" class="table table-hover">
                            <tr>
                                <th class="log-id" nowrap="">
                                    ID
                                </th>
                                <th class="log-file" nowrap="">
                                    File
                                </th>
                                <th us"class="log-action nowrap="">
                                    Action
                                </th>
                                <th us"class="log-stat nowrap="">
                                    Status
                                </th>
                                <th class="log-summary" nowrap="">
                                    Summary
                                </th>
                                <th class="log-download" nowrap="">
                                    Info & Details
                                </th>
                            </tr>';

            $sno = 1;
            foreach($results as $result) {

                $table .='
                <tr>
                    <td class="log-id" >'.$result->id.'</td>
                    <td class="log-file" >'.$result->file_name.'</td>
                    <td class="log-action" >'.$result->action.'</td>
                    <td class="log-status" >'.$result->status.'</td>
                    <td class="log-summary">'.nl2br($result->summary).'</td>
                    <td class="log-download">
                        <a download="'.$result->file_name.'" href="'.get_url('logs').$result->log_file.'"><span>Download</span>
                        </a>
                    </td>
                </tr>';

                $sno++;
            }

            $table .= '</table></div>';
            $table .= '<div class="row"> <div class="col-lg-12"> '.$pagination->render(true).' </div> </div>';
        //$table .= '</div>';
        echo $table;
        get_footer();

    }

    return ob_get_clean();
}

function feedsync_list_listing_agent( ) {

    global $feedsync_db;
    $query = "SELECT * FROM feedsync_users WHERE 1 = 1 ";

    return $feedsync_db->get_results($query);

}

function export_sorting_class($key) {
    $class = '';
    if( !empty($_GET['orderby']) && $_GET['orderby'] == $key ) {
        $class = ' feedsync-sorted ';
    }
    return $class;
}

function imported_files_sorting_class($key) {
    $class = '';
    if( isset($_GET['sort_what']) && $_GET['sort_what'] == $key ) {
        $class = ' feedsync-sorted ';
    }
    return $class;
}

function display_export_data($results , $page = 'all') {

    global $feedsync_db;
    ob_start();
    get_header('listings');
    get_listings_sub_header( $page );

    //$table = '<div class="row"> <div class="col-lg-12"> '.$pagination->render(true).' </div> </div>';
    ?>
    <form method="post">
   
        <div class="row" style="margin-bottom: 1em;">
            <div class="col-md-12">
                 <?php if( is_reset_enabled() ) : ?>
                <div class="col-md-5 pull-left no-padding">
                    <input type="hidden" name="action" value="delete_enteries"/>
                    <button id="delete-enteries-btn" disabled class='btn btn-sm' type="submit" >Delete selected records?</button>
                </div>
                <?php endif; ?>
                <div class="col-md-6 text-right pull-right">
                    <?php
                        $filters = array('id','address','street','suburb','state','country','postcode','geocode','firstdate','status','type','mod_date','agent_id','unique_id','feedsync_unique_id');

                        $filter_val = '';
                        echo '<select id="filter-type">';    
                        foreach($filters as $filter) {
                            $label = ucwords( str_replace('id','ID ', str_replace('_',' ',$filter) ) );

                            $label = $filter == 'id' ? '#' : $label;
                            $filter_sel = '';

                            if( isset( $_GET[$filter] ) ){
                                $filter_sel = ' selected ' ;
                                $filter_val = $feedsync_db->escape(trim($_GET[$filter]));
                            }
                            
                            echo '<option '.$filter_sel.' value="'.$filter.'" >'.$label.'</option>';
                        }
                        echo '</select>';

                    ?>
                    <input type="text"  id="filter-val" value="<?php echo $filter_val; ?>"/>
                    <button id="filter-listings" class='btn btn-sm btn-primary' type="submit" >Filter</button>
                </div>
            </div>
        </div>

        <?php 

        $orderby = (isset($_COOKIE['order']) && in_array($_COOKIE['order'], array('ASC','DESC')) ) ? $_COOKIE['order'] : 'DESC';
        $orderclass = $orderby == 'DESC' ? 'sort-by-desc' : 'sort-by-asc';

        $table = "
            <div class='listings-list-panel panel panel-default'>
                <!--<div class='panel-heading'><span style='text-transform: capitalize;'>$page</span> Listings</div>-->
                     <table data-toggle='table' class='table table-hover'>
                        <thead>
                            <tr>";
                                if( is_reset_enabled() ){
                                    $table .= "<th class='cb'>
                                    <input  type=\"checkbox\" id=\"select_all_items\" />
                                    </th>";
                                }

                                $table .= "

                                <th nowrap class='id'>
                                    <a href='?orderby=id'><span>#</span>
                                    <i class=' ".export_sorting_class('id')." {$orderclass}' ></i>
                                    </a>
                                </th>
                                <th nowrap class='address'>
                                    <a href='?orderby=address'><span>Address</span>
                                    <i class=' ".export_sorting_class('address')." {$orderclass}' ></i>
                                    </a>
                                </th>
                                <th nowrap class='type'>
                                    <a href='?orderby=type'><span>Type</span><i class=' ".export_sorting_class('type')." {$orderclass}' ></i></a>
                                </th>
                                <th nowrap class='status'>
                                    <a href='?orderby=status'><span>Status</span><i class=' ".export_sorting_class('status')." {$orderclass}' ></i></a>
                                </th>
                                <th nowrap class='first-date'>
                                    <a href='?orderby=first_date'><span>First Date</span><i class=' ".export_sorting_class('first_date')." {$orderclass}' ></i></a>
                                </th>
                                <th nowrap class='mod-date'>
                                    <a href='?orderby=mod_date'><span>Mod Date</span><i class=' ".export_sorting_class('mod_date')." {$orderclass}' ></i></a>
                                </th>
                                <th nowrap class='unique-id'>
                                    <a href='?orderby=unique_id'><span>ID</span><i class=' ".export_sorting_class('unique_id')." {$orderclass}' ></i></a>
                                </th>
                                <th nowrap class='agent-id'>
                                    <a href='?orderby=agent_id'><span>Agent</span><i class=' ".export_sorting_class('agent_id')." {$orderclass}' ></i></a>
                                </th>
                                <th nowrap class='geocode'>
                                    <a href='?orderby=geocode'><span>Map</span><i class=' ".export_sorting_class('geocode')." {$orderclass}' ></i></a>
                                </th>

                            </tr>
                        </thead>";

        if( !empty($results) ) {


            // how many records should be displayed on a page?
            $records_per_page = get_option('feedsync_pagination',false);

            // include the pagination class
            require 'pagination.php';

            // instantiate the pagination object
            $pagination = new Zebra_Pagination();

            // set position of the next/previous page links
            $pagination->navigation_position(isset($_GET['navigation_position']) && in_array($_GET['navigation_position'], array('left', 'right')) ? $_GET['navigation_position'] : 'outside');

            // the number of total records is the number of records in the array
            $pagination->records(count($results));

            // records per page
            $pagination->records_per_page($records_per_page);

            // here's the magick: we need to display *only* the records for the current page
            $results = array_slice(
                $results,                                             //  from the original array we extract
                (($pagination->get_page() - 1) * $records_per_page),    //  starting with these records
                $records_per_page                                       //  this many records
            );

            $sno = 1;
            foreach($results as $result) {

                $map_img = get_option('site_url').'core/assets/images/feedsync-map-not-set.svg';
                $atts = ' class ="item-no-map"  ';
                if( !in_array($result->geocode, array('','NULL','-1,-1') ) ){
                     $map_img = get_option('site_url').'core/assets/images/feedsync-map.svg';
                     $atts = ' id="map-'.$result->unique_id.'" class="item-has-map" data-toggle="tooltip" data-html="true"  title="'.$result->geocode.'" data-placement="top" ';
                } else {
                    $atts = ' id="map-'.$result->unique_id.'" class="item-has-map" data-toggle="tooltip" data-html="true"  title="No coordinates set" data-placement="top" ';
                }

                $rated_class = strpos($result->xml, '<feedsyncFeaturedListing>') !== false ? 'rated' : '';
                $fav_title = strpos($result->xml, '<feedsyncFeaturedListing>') !== false ? 'Favourite Listing' : 'Mark Favourite';
                $table .='
                    <tr>';
                    if( is_reset_enabled() ){
                        $table .='
                        <td class="cb"><input type="checkbox" name="delete_items[]" value="'.$result->id.'" /></td>';
                    }
                        $table .='
                        <td data-id="'.$result->id.'" class="id">'.$result->id.'
                            <a href="javascript:void(0);" class="rating mark-fav" title="">
                                <span class="'.$rated_class.'">&#9734;</span>
                            </a>
                        </td>
                        <td class="address">'.$result->address.'</td>
                        <td class="type '.$result->type.'">'.$result->type.'</td>
                        <td class="status '.$result->status.'">'.$result->status.'</td>
                        <td class="first-date">'.$result->firstdate.'</td>
                        <td class="mod-date">'.$result->mod_date.'</td>
                        <td class="unique-id">'.$result->unique_id.'</td>
                        <td class="agent-id">'.$result->agent_id.'</td>
                        <td class="geocode"> 
                            <a href="#"  '.$atts.'>
                                <img src="'.$map_img.'"/>
                            </a>
                        </td>
                    </tr>';

                $sno++;
            }
        }

        $table .= '</table></div>';

        if( !empty($results) )
            $table .= '<div class="row"> <div class="col-lg-12"> '.$pagination->render(true).' </div> </div>';

    $table .= '</form>';
    echo $table;
    get_footer(); ?>
    <div id="confirm" class="modal fade" style="display: none">
      <div class="modal-body">
        Please confirm deletion
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
        <button type="button" data-dismiss="modal" class="btn">Cancel</button>
      </div>
    </div>
    <?php
    return ob_get_clean();

}

function display_agents($results) {
    ob_start();
    get_header('listings');
    get_listings_sub_header( 'agents' );

    if( !empty($results) ) {

        // how many records should be displayed on a page?
        $records_per_page = defined('FEEDSYNC_PAGINATION') ? FEEDSYNC_PAGINATION : 1000;

        // include the pagination class
        require 'pagination.php';

        // instantiate the pagination object
        $pagination = new Zebra_Pagination();

        // set position of the next/previous page links
        $pagination->navigation_position(isset($_GET['navigation_position']) && in_array($_GET['navigation_position'], array('left', 'right')) ? $_GET['navigation_position'] : 'outside');

        // the number of total records is the number of records in the array
        $pagination->records(count($results));

        // records per page
        $pagination->records_per_page($records_per_page);

        // here's the magick: we need to display *only* the records for the current page
        $results = array_slice(
            $results,                                             //  from the original array we extract
            (($pagination->get_page() - 1) * $records_per_page),    //  starting with these records
            $records_per_page                                       //  this many records
        );

        ?>
        <form method="post"> <?php

        if( is_reset_enabled() ) : ?>
            <div class="row" style="margin-bottom: 1em;">
                <div class="col-md-12">
                    <input type="hidden" name="action" value="delete_agent_enteries"/>
                    <button id="delete-enteries-btn" disabled class='btn btn-sm' type="submit" >Delete selected records?</button>
                </div>
            </div>

            <?php endif;

        //$table = '<div class="row"> <div class="col-lg-12"> '.$pagination->render(true).' </div> </div>';
        $table = "
            
            <div class='listings-list-panel panel panel-default'>
                <!--<div class='panel-heading'><span style='text-transform: capitalize;'>Agents</span> Listings</div>-->
                     <table data-toggle='table' class='table table-hover'>
                        <thead>
                            <tr>";
                                if( is_reset_enabled() ){
                                    $table .= "<th class='cb'>
                                    <input  type=\"checkbox\" id=\"select_all_items\" />
                                    </th>";
                                }
                               $table .= "
                                <th class='id'>#</th>
                                <th class='agent_id'>Agent ID</th>
                                <th class='name'>Name</th>
                                <th class='email'>Email</th>
                                <th class='telephone'>Telephone</th>
                            </tr>
                        </thead>";

        $sno = 1;
        foreach($results as $result) {

            $table .= '<tr>';
            if( is_reset_enabled() ){
                $table .='
                <td class="cb"><input type="checkbox" name="delete_items[]" value="'.$result->id.'" /></td>';
            }
            $table .='
                    <td class="id">'.$result->id.'</td>
                    <td class="agent_id">'.$result->office_id.'</td>
                    <td class="name '.$result->name.'">'.$result->name.'</td>
                    <td class="email '.$result->email.'">'.$result->email.'</td>
                    <td class="telephone">'.$result->telephone.'</td>
                </tr>';

            $sno++;
        }

        $table .= '</table></div>';
        $table .= '<div class="row"> <div class="col-lg-12"> '.$pagination->render(true).' </div> </div>';
        $table .= '</form>';

        echo $table;
        get_footer(); ?>
        <div id="confirm" class="modal fade" style="display: none">
          <div class="modal-body">
            Please confirm deletion
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
            <button type="button" data-dismiss="modal" class="btn">Cancel</button>
          </div>
        </div> <?php

        return ob_get_clean();

    }

}
function export_data($results) {

    if( !empty($results) && is_user_logged_in() ) {

        header("Content-Type: application/force-download; name=\"export.xml");
        header("Content-type: text/xml");
        header("Content-Transfer-Encoding: binary");
        header("Content-Disposition: attachment; filename=\"export.xml");
        header("Expires: 0");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        ob_start();
        echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE '.get_parent_element().' SYSTEM "http://reaxml.realestate.com.au/'.get_parent_element().'.dtd">
<'.get_parent_element().'>'."\n";

        foreach($results as $listing) {
            echo $listing->xml."\n";
        }
        echo '</'.get_parent_element().'>';
        $xml =  ob_get_clean();
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = FALSE;
        @$dom->loadXML($xml);
        $dom->formatOutput = TRUE;
        echo $dom->saveXml();
        exit;

    }
}

function get_parent_element() {
    $feedtype = get_option('feedtype');
    $path = 'propertyList';
    switch($feedtype) {

        case 'blm' :
            $path = 'propertyList';
        break;
        case 'reaxml' :
            $path = 'propertyList';
        break;
        case 'expert_agent' :
           $path = 'properties';
        break;
        case 'rockend' :
           $path = 'Properties';
       case 'jupix' :
           $path = 'properties';
        break;
        case 'mls' :
           $path = 'propertyList';
        break;
    }

    return $path;

}

function delete_enteries() {

    global $feedsync_db;

    if( !empty($_POST['delete_items']) && is_reset_enabled() ){

        $ids = array_map('intval', $_POST['delete_items'] );
        $ids = join("','",$ids);
        $feedsync_db->query("DELETE FROM feedsync WHERE id IN ('$ids') ");
    }
}


$feedsync_hook->add_action('feedsync_form_delete_enteries','delete_enteries');

function delete_agent_enteries() {

    global $feedsync_db;

    if( !empty($_POST['delete_items']) && is_reset_enabled() ){

        $ids = array_map('intval', $_POST['delete_items'] );
        $ids = join("','",$ids);
        $feedsync_db->query("DELETE FROM feedsync_users WHERE id IN ('$ids') ");
    }
}


$feedsync_hook->add_action('feedsync_form_delete_agent_enteries','delete_agent_enteries');