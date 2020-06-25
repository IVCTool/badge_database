<?php
/**
 * Handles form submissions from the abstract test case editor.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */

    //check that the user is logged in as someone with the rights to do this
    //TODO 

    //We need the database class with all that info
    include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');

    //Now check the data lengths
    $dataLengthOk = false;
    if (strlen($_POST['name']) <= Badgedb_Database::ABSTRACT_TEST_CASES_NAME_FIELD_MAX && 
        strlen($_POST['identifier']) <= Badgedb_Database::ABSTRACT_TEST_CASES_IDENTIFIER_FIELD_MAX && 
        strlen($_POST['version']) <= Badgedb_Database::ABSTRACT_TEST_CASES_VERSION_FIELD_MAX && 
        strlen($_POST['description']) <= Badgedb_Database::ABSTRACT_TEST_CASES_DESCRIPTION_FIELD_MAX
        ) {
        $dataLengthOk = true;
        
    }//end check lengths


    //Try to upload the file
    $fileUploaded = false;
    $fileID = "";
    require_once( ABSPATH . 'wp-admin/includes/media.php' );
    if ( isset($_FILES['atcsfile'])) {
        $fileID = media_handle_upload('atcsfile', 0); //0 means it's not attached to a post

        if (is_wp_error($fileID)) {
            error_log($fileID->get_error_message());
            error_log($_FILES['atcsfile']['name']);
            error_log($_FILES['atcsfile']['error']);
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

    //if we meet all the conditions then go ahead and add it.
    if ($isPost && $isAdmin && $dataLengthOk && $fileUploaded) {
    $sid = sanitize_text_field($_POST['identifier']);
    $sname = sanitize_text_field($_POST['name']);
    $sdesc = sanitize_text_field($_POST['description']);
    $sversion = sanitize_text_field($_POST['version']);
    $srequirements = array();
    if (isset($_POST['requirements'])) {
        foreach ($_POST['requirements'] as $r) {
            array_push($srequirements, sanitize_text_field($r));
        }
    }
    Badgedb_Database::insert_new_atcs($sid, $sdesc, $sname, $fileID, $sversion, $srequirements);
}//end insert into DB
 ?>