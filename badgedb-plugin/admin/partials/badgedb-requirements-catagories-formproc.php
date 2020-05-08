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
 //make sure we have a post, that the data is not too long, and that the user is actually an admin
 $isPost = false;
 $isAdmin = false;
 $dataLengthOk = false;

 //set user flag
 $user = wp_get_current_user();
 if ( in_array('administrator', (array) $user->roles)) {
    $isAdmin = true;
 }

 //set is coming from a post
 if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    $isPost = true;
    //Now check the data lengths
    if (strlen($_POST['newcat'] <= 255)) {
        $dataLengthOk = true;
    }
 }//end if

//if we meet all the conditions then go ahead and add it.
if ($isPost && $isAdmin && $dataLengthOk) {
    $sanitised = sanitize_text_field($_POST['newcat']);
    Badgedb_Database::insert_new_reqcat($sanitised);
}

?>