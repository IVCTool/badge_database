<?php

/**
 * Provide an interface to edit badges.
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
    <h1>Interoperability Badges</h1>

    <p>Use this page to edit the interoperability badges.</p>

    <p>WARNING!<br>
    Make sure the database has been backed-up recently before deleting badges!<br>
    Changes cannont be undone!</p>

    <h2>Add a new badge</h2>
    <p>
        <form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-badges') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="whichform" value="new" />
        <b>Identifier (max 10 characters):</b> <input required type="text" name="identifier" maxlength="<?php echo Badgedb_Database::BADGES_IDENTIFIER_FIELD_MAX ?>" /><br>
        <b>Description:</b><br><textarea required name="description" rows="4" cols="50" maxlength="<?php echo Badgedb_Database::BADGES_DESCRIPTION_FIELD_MAX ?>"></textarea><br>
        <b>Interoperability Requirements (select all that apply):</b><br> <?php echo Badgedb_Database::get_form_multi_select('badges-req', 'requirements', false) ?><br>
        <b>Badge Prerequisits (select all that apply):</b><br> <?php echo Badgedb_Database::get_form_multi_select('badges-badge', 'badgedeps', false) ?><br>
        <b>Badge Graphic:</b><br> <input type="file" name="graphicFile" required /><br>
        <br><input type="submit" value="Add"/>
        </form>
    </p>

    <?php 
        //This puts in the table of existing requirements
        include_once( plugin_dir_path(__FILE__) . 'badgedb-badges-table.php');
    ?>
</div>