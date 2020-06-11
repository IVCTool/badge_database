<?php

/**
 * Provide an interface to edit abstract test cases.
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
        $cases = Badgedb_Database::get_abstract_test_cases();
        //Make sure it didn't return null, because if it does we don't need to show this table.
        if (is_null($cases)) {
            echo "<p>There are currently no abstract test cases in the database.</p>";
        } else {
           //Go ahead and show the table of existing records.
           echo "<h2>Edit or Delete Existing Abstract Test Case</h2>";
        
    ?>

    <!-- Just some tweaks to the table style. -->
    <style>
        table#atcs { border: 1px solid black; }
        th, tr, td {
            padding: 3px;
            border: 1px solid black;
        }
    </style>

    <p>
        <table id="atcs">
            <tr><th>Identifier</th><th>Name</th><th>Description</th></tr>
            <?php
                foreach ($cases as $row) {
            ?>
                    <tr> 
                        <td><form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-abstract') ?>" method="post">
                        <input type="hidden" name="whichform" value="update" />
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                        <input type="text" name="identifier" value="<?php echo $row['identifier'] ?>" maxlength="<?php echo Badgedb_Database::ABSTRACT_TEST_CASES_IDENTIFIER_FIELD_MAX ?>" /></td>
                        <td><input type="text" name="name" value="<?php echo $row['name'] ?>" maxlength="<?php echo Badgedb_Database::ABSTRACT_TEST_CASES_NAME_FIELD_MAX ?>" /></td>
                        <td><textarea name="description" rows="4" cols="50" " maxlength="<?php echo Badgedb_Database::ABSTRACT_TEST_CASES_DESCRIPTION_FIELD_MAX ?>" ><?php echo $row['description'] ?></textarea></td>
                        <td><b>Replace file:</b> <input required type="file" name="newatcsfile" /></td>
                        <td><input type="submit" value="Edit" />
                        </form></td>
                        <td><form action="<?php menu_page_url('badgedb-plugin-admin-menu-sub-abstract') ?>" method="post">
                        <input type="hidden" name="whichform" value="delete" />
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                        <input type="hidden" name="wpid" value="<?php echo $row['wpid']?>" />
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