<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Badgedb
 * @subpackage Badgedb/admin/partials
 */


?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1>Welcome to the NATO Interoperability Badge Database</h1>

    <p><b><i>Version 1.0.0</i></b></p>

    <p>The sub-menus give you access to the tools you need to maintain the badge database.  There is a 
    separate sub-menu for interoperability catagories, for requirements, for badges, for abstract test cases, 
    and for executable test cases.</p>

    <p>Your installation includes the data available when the plugin was released so there is something there to 
    start with.  Please contact the NATO Modelling & Simulation Centre of Excellence for the latest data.</p>

    <p><b>Note: </b> badges and abstract test cases have files associated with them.  Unfortunately this version of the 
    plugin does not populate these on installation.

    <h1>Administration</h1>

    <p>Any user in the "administrators" group in your WordPress site will be able to edit the information within the database.</p>

    <h1>The Public Interface<h1>

    <p>The public interface to the database is handled by WordPress "short codes" that may be inserted into any page.  Due to 
    limitations of the WordPress environment only one short code can be inserted into a page (more precisly, only the first one 
    inserted in any given page will function).  There is a single short code that takes two parameters:<br><pre><code>
    General form:
    [badgedbpi interface="single|table" indentifier="SAMPLE-IDENTIFIER"]

    Show a summary table of all the badges (identifier is not required):
    [badgedbpi interface="table"]

    Show all the information relevant to a single badge:
    [badgedbpi interface="table" indentifier="NETN-ENTITY-2016"]</code></pre></p>

    <p>This version of the plugin doesn't have any tools for the public to search and filter the information in the database.  
    Instead the site administrators are expected to organise and present it to the public.  This has the advantage of allowing 
    a narrative to be wrapped around the raw database information, and gives the administrators the ability to organise the 
    information as they wish.</P>


    <h1>More Information, Suggestions, and Feed-back</h1>

    <p>For more documentation and to get the latest releases please vist our Github site: 
    <a href="https://github.com/IVCTool/badge_database">Badge Database on Github</a>.  To report bugs, request 
    enhancements, and provide feed-back, please feel free to create an issue at the Github repository.</p>

</div>