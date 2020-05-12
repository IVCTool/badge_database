<?php

/**
 * Updates a requirement catagory record.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */

    //we need the database object
    include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');

    //All we need to check extra, is the data lengths
    $dataLengthOk = false;
    //Now check the data lengths
    if (strlen($_POST['name'] <= Badgedb_Database::REQCATEGORIES_NAME_FIELD_MAX) && 
        strlen($_POST['identifier'] <= Badgedb_Database::REQCATEGORIES_IDENTIFIER_FIELD_MAX && 
        strlen($_POST['description']) <= Badgedb_Database::REQCATEGORIES_DESCRIPTION_FIELD_MAX)
        ) {
        $dataLengthOk = true;
    }//end check lengths

    //check that this is a post and that the user is an admin
    if ($isPost && $isAdmin && $dataLengthOk) {
        $sid = sanitize_text_field($_POST['id']);
        $sident = sanitize_text_field($_POST['identifier']);
        $sname = sanitize_text_field($_POST['name']);
        $sdesc = sanitize_text_field($_POST['description']);
        //Badgedb_Database::update_reqcat($sid, $sident, $sname, $sdesc);
        Badgedb_Database::update_reqcat($_POST['id'], $_POST['identifier'], $_POST['name'], $_POST['description']);
    }//end if

 ?>