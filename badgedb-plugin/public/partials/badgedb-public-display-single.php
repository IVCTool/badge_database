<?php

/**
 * Provide a public-facing view for the plugin
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

 $returnval = <<< EOT
 <h1>HELLO WORLD!</h1>
 <p>This is where the badges will go!</p>
 EOT;

//This section displays what's in the database already, along with delete and edit buttons.

//we need the database object
include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');
$badges = Badgedb_Database::get_badges();
//Make sure it didn't return null, because if it does we don't need to show this table.
if (is_null($badges)) {
    $returnval = "<p>There are currently no interoperability badges in the database.</p>";
} else {
    //Go ahead and show the table of existing records.
    $returnval = <<< EOT2
        <h2>Badges</h2>
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