<?php
/**
 * Handles form submissions to add new requirements.
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
    if (strlen($_POST['identifier']) <= Badgedb_Database::REQUIREMENTS_IDENTIFIER_FIELD_MAX && 
        strlen($_POST['catagory']) <= Badgedb_Database::REQUIREMENTS_CATAGORY_FIELD_MAX &&
        strlen($_POST['description']) <= Badgedb_Database::REQUIREMENTS_DESCRIPTION_FIELD_MAX
    ) {
        $dataLengthOk = true;
    }//end check lengths

    //if we meet all the conditions then go ahead and add it.
    if ($isPost && $isAdmin && $dataLengthOk) {
    $sid = sanitize_text_field($_POST['identifier']);
    $scatagory = sanitize_text_field($_POST['catagory']);
    $sdesc = sanitize_text_field($_POST['description']);
    Badgedb_Database::insert_new_requirement($sid, $sdesc, $scatagory);
}//end insert into DB
 ?>