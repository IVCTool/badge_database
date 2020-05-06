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
    <h1>REQCAT!!!</h1>

    <p>
        <!--form action="test.php" method="post"-->
        <form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-reqcat') ?>" method="post">
        New catagory: <input type="text" name="newcat" />
        <input type="submit"/>
        </form>
    </p>
</div>