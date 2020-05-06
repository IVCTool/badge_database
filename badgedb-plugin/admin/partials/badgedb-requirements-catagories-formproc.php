<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */

 global $wpdb;
 //echo "In form processing!";
 //TODO - all the sanitation and so on that should be in here
 //make sure we have something to actually deal with
 //if( $_SERVER['REQUEST_METHOD'] == 'POST') {
     //go ahead and process
    $sql = "INSERT INTO wp_badgedb_reqcategories (name) VALUES('test1');";
    $wpdb->query($sql);
 //} else {
 //    return;
 //}//end if

?>