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
    if (strlen($_POST['identifier']) <= Badgedb_Database::REQUIREMENTS_IDENTIFIER_FIELD_MAX && 
    strlen($_POST['catagory']) <= Badgedb_Database::REQUIREMENTS_CATAGORY_FIELD_MAX &&
    strlen($_POST['description']) <= Badgedb_Database::REQUIREMENTS_DESCRIPTION_FIELD_MAX
        ) {
        $dataLengthOk = true;
    }//end check lengths

    //check that this is a post and that the user is an admin
    if ($isPost && $isAdmin && $dataLengthOk) {
        //sanitising doesn't work with the wpdb update function for some reason, so, no sanitisation.
        Badgedb_Database::update_requirement($_POST['id'], $_POST['identifier'], $_POST['description'], $_POST['catagory']);
    }//end if

 ?>