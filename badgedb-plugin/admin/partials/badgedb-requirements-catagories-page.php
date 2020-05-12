<?php

/**
 * Provide an interface to edit requirement catagories.
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
    <h1>Interoperability Requirement Catagories</h1>

    <p>Use this page to edit the catagories of requirements.</p>

    <p>WARNING!<br>
    Make sure the database has been backed-up recently before deleting catagories!<br>
    Changes cannont be undone!</p>

    <h2>Add a new catagory</h2>
    <p>
        <form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-reqcat') ?>" method="post">
        <input type="hidden" name="whichform" value="new" />
        <b>Identifier (max 10 characters):</b> <input type="text" name="identifier" maxlength="<?php echo Badgedb_Database::REQCATEGORIES_IDENTIFIER_FIELD_MAX ?>" /><br>
        <b>Name (max 255 characters):</b> <input type="text" name="name" maxlength="<?php echo Badgedb_Database::REQCATEGORIES_NAME_FIELD_MAX ?>" /><br>
        <b>Description:</b><br><textarea name="description" rows="4" cols="50" maxlength="<?php echo Badgedb_Database::REQCATEGORIES_DESCRIPTION_FIELD_MAX ?>"> </textarea> 
        <br><input type="submit" value="Add"/>
        </form>
    </p>

    <?php 
        //This puts in the table of existing requirement catagories
        include_once( plugin_dir_path(__FILE__) . 'badgedb-requirements-catagories-table.php');
    ?>
</div>