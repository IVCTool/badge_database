<?php
/**
 * Handles form submissions from the requirements catagory editor.
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

    //All we need to check extra, is the data lengths
    $dataLengthOk = false;
    //Now check the data lengths
    if (strlen($_POST['name'] <= Badgedb_Database::REQCATEGORIES_NAME_FIELD_MAX) && 
        strlen($_POST['identifier'] <= Badgedb_Database::REQCATEGORIES_IDENTIFIER_FIELD_MAX && 
        strlen($_POST['description']) <= Badgedb_Database::REQCATEGORIES_DESCRIPTION_FIELD_MAX)
        ) {
        $dataLengthOk = true;
    }//end check lengths

    //if we meet all the conditions then go ahead and add it.
    if ($isPost && $isAdmin && $dataLengthOk) {
    $sid = sanitize_text_field($_POST['identifier']);
    $sname = sanitize_text_field($_POST['name']);
    $sdesc = sanitize_text_field($_POST['description']);
    Badgedb_Database::insert_new_reqcat($sid, $sname, $sdesc);
}//end insert into DB
 ?>