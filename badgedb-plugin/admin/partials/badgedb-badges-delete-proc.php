<?php
/**
 * Handles deleting badges.
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


    //if we meet all the conditions then go ahead and delete it.
    //if ($isPost && $isAdmin && isset($_POST['id']) && isset($_POST['wpid'])) {
    if ($isPost && $isAdmin && isset($_POST['id'])) {  //TODO need to put the wpid back in once that's working 
        Badgedb_Database:: delete_badge($_POST['id']);
    }//end insert into DB

 ?>