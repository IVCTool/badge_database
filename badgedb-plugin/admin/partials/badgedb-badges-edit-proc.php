<?php
/**
 * Handles form submissions from the badges editor.
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
    if (strlen($_POST['identifier']) <= Badgedb_Database::BADGES_IDENTIFIER_FIELD_MAX && 
        strlen($_POST['description']) <= Badgedb_Database::BADGES_DESCRIPTION_FIELD_MAX
        ) {
        $dataLengthOk = true;
        
    }//end check lengths


    //Replace the file IF a new one was submitted
    $fileUploaded = false;
    // $uploadGood = false;
    // $newFileID = "";
    // require_once( ABSPATH . 'wp-admin/includes/media.php' );
    // if ( isset($_FILES['newatcsfile'])) {
    //     //Upload the new attachement now, and then deal with it when we alter the record.
    //     $newFileID = media_handle_upload('newatcsfile', 0); //0 means it's not attached to a post
    //     error_log("File replacement requested for Abstract Test Case!");
    //     if (is_wp_error($newFileID)) {
    //         error_log($newFileID->get_error_message());
    //         error_log($_FILES['newatcsfile']['name']);
    //         error_log($_FILES['newatcsfile']['error']);
    //     } else {
    //         //No error, so we should be good to continue.
    //         error_log($_FILES['newatcsfile']['name'] . " uploaded with id " . $newFileID);
    //         $fileUploaded = true;
    //         $uploadGood = true;
    //     }//end if ok
    // } //end if the file is there


    //if we meet all the conditions then go ahead and add it.
    if ($isPost && $isAdmin && $dataLengthOk) {
    $sIdent = sanitize_text_field($_POST['identifier']);
    $sdesc = sanitize_text_field($_POST['description']);
    $srequirements = array();
    if (isset($_POST['requirements'])) {
        foreach ($_POST['requirements'] as $r) {
            array_push($srequirements, sanitize_text_field($r));
        }
    }
    $sbadgedeps = array();
    if (isset($_POST['badgedeps'])) {
        foreach($_POST['badgedeps'] as $b) {
            array_push($sbadgedeps, sanitize_text_field($b));
        }
    }
    if ($fileUploaded) {
        Badgedb_Database::modify_badge($_POST['id'], $sIdent, $sdesc, $srequirements, $sbadgedeps, true, $newFileID);
    } else {
        Badgedb_Database::modify_badge($_POST['id'], $sIdent, $sdesc, $srequirements, $sbadgedeps, false);
    }
}//end modify into DB
 ?>