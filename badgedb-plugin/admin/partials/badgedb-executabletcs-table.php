<?php

/**
 * Provide an interface to edit executable test cases.
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
        $etcRecords
         = Badgedb_Database::get_executabletcss();
        //Make sure it didn't return null, because if it does we don't need to show this table.
        if (is_null($etcRecords
        )) {
            echo "<p>There are currently no executable test cases in the database.</p>";
        } else {
           //Go ahead and show the table of existing records.
           echo "<h2>Edit or Delete Existing Executable Test Cases</h2>";
        
    ?>

    <!-- Just some tweaks to the table style. -->
    <style>
        table#etcs { border: 1px solid black; }
        th, tr, td {
            padding: 3px;
            border: 1px solid black;
        }
    </style>

    <p>
        <table id="etcs">
            <tr><th>Description</th><th>Class Name</th><th>Version</th><th>Abstract Test Case</th></tr>
            <?php
                foreach ($etcRecords as $row) {
            ?>
                    <tr> 
                        <td>
                        <form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-executabletcs') ?>" method="post">
                        <input type="hidden" name="whichform" value="update" />
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                        <textarea required name="description" rows="4" cols="50" maxlength="<?php echo Badgedb_Database::EXECUTABLETCS_DESCRIPTION_FIELD_MAX ?>"><?php echo $row['description'] ?></textarea>
                        </td>
                        <td><input required type="text" name="classname" value="<?php echo $row['classname'] ?>" maxlength="<?php echo Badgedb_Database::EXECUTABLETCS_CLASSNAME_FIELD_MAX ?>" /></td>
                        <td><input required type="text" name="version" value="<?php echo $row['version'] ?>" maxlength="<?php echo Badgedb_Database::EXECUTABLETCS_VERSION_FIELD_MAX ?>" /></td>
                        <td><?php echo Badgedb_Database::get_form_select("abstracttcs", true, $row['abstracttcs_id']) ?></td>
                        <td><input type="submit" value="Edit" />
                        </form></td>
                        <td><form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-executabletcs') ?>" method="post">
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