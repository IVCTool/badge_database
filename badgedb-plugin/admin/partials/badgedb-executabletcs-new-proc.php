<?php
/**
 * Handles form submissions to add new executable test cases.
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

    //error_log("Trying to make a new etcs");
    //All we need to check extra, is the data lengths
    $dataLengthOk = false;
    //Now check the data lengths
    if (strlen($_POST['classname']) <= Badgedb_Database::EXECUTABLETCS_CLASSNAME_FIELD_MAX && 
        strlen($_POST['version']) <= Badgedb_Database::EXECUTABLETCS_VERSION_FIELD_MAX && 
        strlen($_POST['description']) <= Badgedb_Database::EXECUTABLETCS_DESCRIPTION_FIELD_MAX
    ) {
        $dataLengthOk = true;
    }//end check lengths

    //if we meet all the conditions then go ahead and add it.
    if ($isPost && $isAdmin && $dataLengthOk) {
    $sdesc = sanitize_text_field($_POST['description']);
    $sclass = sanitize_text_field($_POST['classname']);
    $sversion = sanitize_text_field($_POST['version']);
    $satcs = sanitize_text_field($_POST['abstracttcs']);
    Badgedb_Database::insert_new_executabletcs($sdesc, $sclass, $sversion, $satcs);
}//end insert into DB
 ?>