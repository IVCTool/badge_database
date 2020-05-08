<?php

/**
 * Provide an interface to edit requirement catagories.
 *
 * @link       https://github.com/IVCTool/badge_database/tree/master/badgedb-plugin
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin
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
        Identifier (max 10 characters): <input type="text" name="identifier" maxlength="<?php echo Badgedb_Database::REQCATEGORIES_IDENTIFIER_FIELD_MAX ?>" /><br>
        Name (max 255 characters): <input type="text" name="name" maxlength="<?php echo Badgedb_Database::REQCATEGORIES_NAME_FIELD_MAX ?>" /><br>
        <!-- note: this is max for mysql longtext with the UTF-8 character set-->
        Description: <input type="textarea" name="description" maxlength="<?php echo Badgedb_Database::REQCATEGORIES_DESCRIPTION_FIELD_MAX ?>" /> 
        <input type="submit"/>
        </form>
    </p>
</div>