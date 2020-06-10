<?php

/**
 * Handles form submissions from the abstract test case editor.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */

 
 /** 
  * There are three different forms on the page that is calling this.  The processing for
  * each will be in a separate include.
 */

 //make sure we have a post, and that the user is actually an admin
 $isPost = false;
 $isAdmin = false;
 

 //set user flag
 $user = wp_get_current_user();
 if ( in_array('administrator', (array) $user->roles)) {
    $isAdmin = true;
 }

 //set is coming from a post
 if( $_SERVER['REQUEST_METHOD'] == 'POST') {
    $isPost = true;
 }//end if

 if ($isPost && $isAdmin) {
     //check which action it was and include that processor.
     if ($_POST['whichform'] == 'new') {
         include_once(plugin_dir_path(__FILE__) . 'badgedb-abstract-test-case-new-proc.php');
     }//end if it's a new catagory.

     if ($_POST['whichform'] == 'delete') {
        include_once(plugin_dir_path(__FILE__) . 'badgedb-abstract-test-case-delete-proc.php');
     }

     if ($_POST['whichform'] == 'update') {
      include_once(plugin_dir_path(__FILE__) . 'badgedb-abstract-test-case-edit-proc.php');
   }

 }//end if which processor should be included

?>