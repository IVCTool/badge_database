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
    if (strlen($_POST['description']) <= Badgedb_Database::EXECUTABLETCS_DESCRIPTION_FIELD_MAX && 
    strlen($_POST['classname']) <= Badgedb_Database::EXECUTABLETCS_CLASSNAME_FIELD_MAX &&
    strlen($_POST['version']) <= Badgedb_Database::EXECUTABLETCS_VERSION_FIELD_MAX
        ) {
        $dataLengthOk = true;
    }//end check lengths

    //check that this is a post and that the user is an admin
    if ($isPost && $isAdmin && $dataLengthOk) {
        //sanitising doesn't work with the wpdb update function for some reason, so, no sanitisation.
        Badgedb_Database::modify_executabletcs($_POST['id'], $_POST['description'], $_POST['classname'], $_POST['version'], $_POST['abstracttcs']);
    }//end if

 ?>