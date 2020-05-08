<?php

/**
 * Provide an interface to edit requirement catagories.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1>Interoperability Requirement Catagories</h1>

    <p>Use this page to edit the catagories of requirements.</p>

    <p>WARNING!<br>
    Make sure the database has been backed-up recently before deleting catagories!<br>
    Changes cannont be undone!</p>

    <p>
        <!--form action="test.php" method="post"-->
        <form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-reqcat') ?>" method="post">
        New catagory: <input type="text" name="newcat" maxlength="255"/>
        <input type="submit"/>
        </form>
    </p>
</div>