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

        //This section displays what's in the database already, along with delete and edit buttons.

        //we need the database object
        include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');
        $badges = Badgedb_Database::get_badges();
        //Make sure it didn't return null, because if it does we don't need to show this table.
        if (is_null($badges)) {
            echo "<p>There are currently no interoperability badges in the database.</p>";
        } else {
           //Go ahead and show the table of existing records.
           echo "<h2>Edit or Delete Existing Badges</h2>";
        
    ?>

    <!-- Just some tweaks to the table style. -->
    <style>
        table#badge { border: 1px solid black; }
        th, tr, td {
            padding: 3px;
            border: 1px solid black;
        }
    </style>

    <p>
        <table id="badge">
            <tr><th>Identifier</th><th>Description</th><th>Interoperability Requirements</th><th>Badge Prerequisits</th></tr>
            <?php
                foreach ($badges as $row) {
            ?>
                    <tr> 
                        <td><form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-badges') ?>" method="post">
                        <input type="hidden" name="whichform" value="update" />
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                        <input type="text" name="identifier" value="<?php echo $row['identifier'] ?>" maxlength="<?php echo Badgedb_Database::BADGES_IDENTIFIER_FIELD_MAX ?>" /></td>
                        <td><textarea name="description" rows="4" cols="50" maxlength="<?php echo Badgedb_Database::BADGES_DESCRIPTION_FIELD_MAX ?>"><?php echo $row['description'] ?></textarea></td>
                        <td><?php echo Badgedb_Database::get_form_multi_select('badges-req', 'requirements', true, $row['id']) ?></td>
                        <td><?php echo Badgedb_Database::get_form_multi_select('badges-badge', 'badgedeps', true, $row['id']) ?></td>
                        <td><input type="submit" value="Edit" />
                        </form></td>
                        <td><form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-badges') ?>" method="post">
                        <input type="hidden" name="whichform" value="delete" />
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                        <input type="submit" value="Delete" />
                        </form></td>
                    </tr>

        <?php
                }//end loop over records
        ?>  
        </table>             
    </p>
    <?php

    }//end if check for null recordset
    ?>