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

 //Set it up for an error just in case
 $returnval = <<< EOT
 <div <p style="color:red;">An error occured while trying to display a single badge using the <em>badgedbpi</em> shortcode.</p></div>
 EOT;

//Now lets make sure the identifier is set and go from there
if(isset($atts['identifier'])) {
    //we can continue
    include_once( plugin_dir_path(__FILE__) . '../../includes/class-badgedb-database.php');
    $badgeInfo = Badgedb_Database::get_all_badge_info_by_identifier($atts['identifier']);

    $bident = $badgeInfo['badge']['identifier'];
    $bdesc = $badgeInfo['badge']['description'];
    $bgraphicurl = "";
    if (is_numeric($badgeInfo['badge']['wpid'])) {
        $bgraphic = wp_get_attachment_url($badgeInfo['badge']['wpid']);
        $returnval = <<<EOB
        <div class="badge-info">
        <img src="$bgraphic" style="float:left" />
        <h2>Identifier: $bident</h2>
        <p>Description: $bdesc</p>
        EOB;    
    } else {
        $returnval = <<<EOB2
        <div class="badge-info">
        <h2>Identifier: $bident</h2>
        <p>Description: $bdesc</p>
        EOB2;
    }
    //Now the requirements.
    $returnval .= "<h3>Requirements for this badge</h3>";
    if (!is_null($badgeInfo['reqs'])) {
        foreach ($badgeInfo['reqs'] as $r) {
            $rident = $r['identifier'];
            $rdesc = $r['description'];
            $returnval .= <<<ENDR
            <P><b>$rident:</b> $rdesc</p>
            ENDR;
        }//end loop over requirement information
    } else {
        $returnval .= "<p>No requirements found for this badge.</p>";
    }

    //And the abstract test cases related to any of the requirements.
    $returnval .= "<h3>Abstract test cases related to this badge</h3>";
    //if there were no results the element for the atcs should be null
    if (!is_null($badgeInfo['atcs'])) {
        $appenda = "<p>No abstract test cases found for this badge.</p>";
        foreach ($badgeInfo['atcs'] as $a) {
            if ($appenda == "<p>No abstract test cases found for this badge.</p>") {
                $appenda = "";
            }
            $aident = $a['identifier'];
            $aname = $a['name'];
            $adescription = $a['description'];
            $aversion = $a['version'];
            $fileUrl = wp_get_attachment_url($a['wpid']);
            $appenda .= <<<EOA
            <p><b>$aident:</b> $aname.<br>
            $adescription<br>
            EOA;
            if ($fileUrl != false) {
                $appenda .= <<<EOU
                Abstract test case document. <a href="$fileUrl">$fileUrl</a></p>
                EOU;
            } else {
                $returnval .= "No document for this abstract test case.</P>";
            }
        }
    } //end abstract test case
    $returnval .= $appenda;

    //Just close off the DIV tag
    $returnval .= "</div>";

} else {
    $returnval = <<<EOE
    <div>
    <p style="color:red;">An error occured while trying to display a single badge using the <em>badgedbpi</em> shortcode. To show
     a single badge's information the <em>identifier</em> attribute must be set to a valid badge identifier.</p>
     </div>
    EOE;
}//end isset identifier


?>