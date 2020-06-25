<?php

/**
 * The menu page for dealing with Abstract Test Case records.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */


?>
<?php 
    //We need this to access the field max sizes for form validation
    include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');
?>

<div class="wrap">
    <h1>Abstract Test Cases</h1>

    <p>Use this page to edit the abstract test cases.  Abstract test cases describe how a particular 
    requirement (or group of requirements) should be tested.  This is not the actual test code, but 
    can be thought of as a design document for the developer that will write the test code.</p>

    <p>WARNING!<br>
    Make sure the database has been backed-up recently before deleting!<br>
    Changes cannont be undone!</p>

    <h2>Add a new abstract test case</h2>
    <p>
        <form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-abstract') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="whichform" value="new" />
        <b>Identifier (max 10 characters):</b> <input required type="text" name="identifier" maxlength="<?php echo Badgedb_Database::ABSTRACT_TEST_CASES_IDENTIFIER_FIELD_MAX ?>" /><br>
        <b>Name (max 255 characters):</b> <input required type="text" name="name" maxlength="<?php echo Badgedb_Database::ABSTRACT_TEST_CASES_NAME_FIELD_MAX ?>" /><br>
        <b>Description:</b><br><textarea required name="description" rows="4" cols="50" maxlength="<?php echo Badgedb_Database::ABSTRACT_TEST_CASES_DESCRIPTION_FIELD_MAX ?>"> </textarea><br>
        <b>Version (max 45 characters):</b> <input required type="text" name="version" maxlength="<?php echo Badgedb_Database::ABSTRACT_TEST_CASES_VERSION_FIELD_MAX ?>" /><br>
        <b>Interoperability Requirement Coverage (select all that apply):</b><br> <?php echo Badgedb_Database::get_form_multi_select('atcs-req', 'requirements') ?><br>
        <b>File:</b> <input required type="file" name="atcsfile" />
        <br><input type="submit" value="Add"/>
        </form>
    </p>

    <?php 
        //This puts in the table of existing abstract test cases
        include_once( plugin_dir_path(__FILE__) . 'badgedb-abstract-test-case-table.php');
    ?>
</div>