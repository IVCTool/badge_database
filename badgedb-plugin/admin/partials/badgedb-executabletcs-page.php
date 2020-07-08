<?php

/**
 * Provide an interface to edit executable test case records.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php 
    //We need this to access the field max sizes for form validation
    include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');
?>

<div class="wrap">
    <h1>Executable Test Cases</h1>

    <p>Use this page to edit the executable test cases.</p>

    <p>WARNING!<br>
    Make sure the database has been backed-up recently before deleting badges!<br>
    Changes cannont be undone!</p>

    <h2>Add a new executable test case</h2>
    <p>
        <form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-executabletcs') ?>" method="post">
        <input type="hidden" name="whichform" value="new" />
        <b>Description:</b><br><textarea required name="description" rows="4" cols="50" maxlength="<?php echo Badgedb_Database::EXECUTABLETCS_DESCRIPTION_FIELD_MAX ?>"></textarea><br>
        <b>Class name:</b><br> <input type="text" name="classname" /><br>
        <b>Version: </b> <input type="text" name="version" /><br>
        <b>Abstract test case:</b><br> <?php echo Badgedb_Database::get_form_select('abstracttcs', true) ?><br>
        <br><input type="submit" value="Add"/>
        </form>
    </p>

    <?php 
        //This puts in the table of existing requirements
       include_once( plugin_dir_path(__FILE__) . 'badgedb-executabletcs-table.php');
    ?>
</div>