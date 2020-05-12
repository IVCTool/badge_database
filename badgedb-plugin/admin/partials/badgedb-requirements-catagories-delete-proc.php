<?php

/**
 * Simply delete a requirement catagory
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */

    //we need the database object
    include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');

    //check that this is a post and that the user is an admin
    if ($isPost && $isAdmin) {
        $sid = sanitize_text_field($_POST['id']);
        Badgedb_Database::delete_reqcat($sid);
    }//end if

 ?>