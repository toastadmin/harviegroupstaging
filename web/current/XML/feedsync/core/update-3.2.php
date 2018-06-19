<?php

/**
 * Upgrades table, add feedsync_unique_id column
 * 
 */
function upgrade_table_3_2() {

    global $feedsync_db;

    /** alter table feedsync */

    $sql = "SELECT *
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = 'feedsync'
            AND table_schema = '".DB_NAME."'
            AND column_name = 'feedsync_unique_id'";

    $col_exists = $feedsync_db->get_results($sql);

    if( is_null($col_exists) ) {
        
        /** add columns in case of upgrade to this version **/
	    $sql = "
	            ALTER TABLE `feedsync`
	                ADD `feedsync_unique_id` varchar(256) NOT NULL
	        ";
	    $feedsync_db->query($sql);

        /** drop unique ID **/
        $sql = "
                ALTER TABLE `feedsync`
                    DROP INDEX unique_id
            ";
        $feedsync_db->query($sql);
    }

    /** alter table feedsync_users */

    $sql = "SELECT *
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = 'feedsync_users'
            AND table_schema = '".DB_NAME."'
            AND column_name = 'listing_agent_id'";

    $col_exists = $feedsync_db->get_results($sql);

    if( is_null($col_exists) ) {
        
        /** add columns in case of upgrade to this version **/
        $sql = "
                ALTER TABLE `feedsync_users`
                    ADD `listing_agent_id` varchar(256) NOT NULL,
                    ADD `username` varchar(256) NOT NULL;
            ";
        $feedsync_db->query($sql);

    }

}

/**
 * DB upgrade for 3.0.5 feedsyncImageModtime node 
 * 
 */
function upgrade_version_3_2() {

    global $feedsync_db;

    $feedtype = get_option('feedtype');
    $rex = new REAXML_PROCESSOR();
    
    switch($feedtype) {

        case 'reaxml' :
        case 'blm' :
        case 'expert_agent' :
        case 'rockend' :
        case 'jupix' :
        case 'mls' :
            
            $rex->upgrade_for_version_3_2();
            
        break;

         case 'eac' :
            $eac_api = new EAC_API(false);
            $eac_api->upgrade_for_version_3_2();

        break;

    }

    $rex->agent_upgrade_for_version_3_2();
}
upgrade_table_3_2();
upgrade_version_3_2();