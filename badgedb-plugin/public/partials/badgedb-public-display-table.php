<?php

/**
 * Provide a summary table of all the badges.  This won't be super
 * useful for learning but will at lest show you everything.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 * 
 * THE HTML RETRUNED MUST BE SENT AS A RETURN VALUE, DO NOT ECHO OR HAVE RAW HTML
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/public/partials
 */

 // Set the return value to something.  It will be overwritten.
 $returnval = "BADGE DATA PLACEHOLDER";



//we need the database object
include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');
$badges = Badgedb_Database::get_badges();
//Make sure it didn't return null, because if it does we don't need to show this table.
if (is_null($badges)) {
    $returnval = "<p>There are currently no interoperability badges in the database.</p>";
} else {
    //Go ahead and show the table of existing records.
    $returnval = <<< EOT2
        <h2>Capability Badge Summary Table</h2>
        <style>
            table#badge { border: 1px solid black; }
            th, tr, td {
            padding: 3px;
            border: 1px solid black;
            }
        </style>

        <p>
            <table id="badge">
                <tr><th>Identifier</th><th>Description</th><th>Interoperability Requirements</th><th>Badge Prerequisits</th><th>Badge Graphic</th></tr>
EOT2;
    foreach ($badges as $row) {
     $returnval .= "<tr> ";
    $returnval .= "<td>" . $row['identifier'] . "</td>";
    $returnval .= "<td>" . $row['description'] . "</td>";
    $returnval .= "<td>" . Badgedb_Database::get_form_multi_select('badges-req', 'requirements', true, $row['id']) . "</td>";
    $returnval .= "<td>" . Badgedb_Database::get_form_multi_select('badges-badge', 'badgedeps', true, $row['id']) . "</td>";
    $returnval .= "<td> <a href=\"" . wp_get_attachment_url($row['wpid']) . "\">current file</a></td>";
    $returnval .= "</tr>";

    }//end loop over records
    $returnval .= "</table></p>";
} //endif
?>