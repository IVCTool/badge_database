<?php
/**
 * Handles form submissions from thebadge editor.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */

    //We need the database class with all that info
    include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');

    //Now check the data lengths
    $dataLengthOk = false;
    if (strlen($_POST['identifier']) <= Badgedb_Database::BADGES_IDENTIFIER_FIELD_MAX && 
        strlen($_POST['description']) <= Badgedb_Database::BADGES_DESCRIPTION_FIELD_MAX
        ) {
        $dataLengthOk = true;
        
    }//end check lengths


    //Try to upload the file
    $fileUploaded = false;
    $fileID = "";
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    if ( isset($_FILES['graphicFile'])) {
        $fileID = media_handle_upload('graphicFile', 0); //0 means it's not attached to a post

        if (is_wp_error($fileID)) {
            error_log($fileID->get_error_message());
            error_log($_FILES['graphicFile']['name']);
            error_log($_FILES['graphicFile']['error']);
        } else {
            //No error, so we should be good to continue.
            $fileUploaded = true;
        }//end if ok
    }//end if var is set

    //make an array of the requirements
    //$requirements = [];
    //if (isset($_POST['requirements'])) {
    //    $requirements = $_POST['requirements'];
    //}

    $fileUploaded = true;  //just until we actually implement the file part

    //if we meet all the conditions then go ahead and add it.
    if ($isPost && $isAdmin && $dataLengthOk && $fileUploaded) {
    $sidentifier = sanitize_text_field($_POST['identifier']);
    $sdesc = sanitize_text_field($_POST['description']);
    $srequirements = array();
    if (isset($_POST['requirements'])) {
        foreach ($_POST['requirements'] as $r) {
            array_push($srequirements, sanitize_text_field($r));
        }
    }
    $sbadgedeps = array();
    if (isset($_POST['badgedeps'])) {
        foreach ($_POST['badgedeps'] as $b) {
            array_push($sbadgedeps, sanitize_text_field($b));
        }
    }
    Badgedb_Database::insert_new_badge($sidentifier, $sdesc, $srequirements, $sbadgedeps);
}//end insert into DB
 ?>