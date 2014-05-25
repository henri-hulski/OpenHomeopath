<?php

// ----------------------------------------------------------------------
//
// Script: run_phorum.php
// Author: Maurice Makaay
//
// This script can be included into other code and it will run 
// the Phorum code without outputting data to the client. The
// output will be captured and sent to a PhorumConnector object
// which then can handle the data.
//
// ----------------------------------------------------------------------

// The script that has included this file should have created a 
// PhorumConnector object to work with. This object must be stored
// in the global variable $PHORUM_CONNECTOR.
if (! isset($GLOBALS["PHORUM_CONNECTOR"])) die("
    No PhorumConnector object found in the global variable
    \$PHORUM_CONNECTOR. Please create a PhorumConnector object
    before including the " . basename(__FILE__) . " script");

// Go to the Phorum install dir, so our includes will function.
// Remember the dir we are in now, so we can return afterwards.
// The $MASTER_DIR and $PHORUM_DIR can also be set before including
// this file. Normally, filling them automatically should work.
if (! isset($MASTER_DIR)) $MASTER_DIR = getcwd();
if (! isset($PHORUM_DIR)) $PHORUM_DIR = dirname(__FILE__) . "/../../";
if (! chdir($PHORUM_DIR)) {
    die("embed_phorum error: Cannot change directory to " .
        '"' . htmlspecialchars($PHORUM_DIR) . '".');
}

// Setup query environment for Phorum and get the page that we have to load.
$phorum_embed_page = $PHORUM_CONNECTOR->parse_request($_SERVER["QUERY_STRING"]);

// The requested page does not exist. Fallback to the index.
if (! file_exists("./{$phorum_embed_page}.php")) {
    $phorum_embed_page = "index";
}

// Process the Phorum page.
phorum_process_page($phorum_embed_page);

// Go back to the original directory.
chdir($MASTER_DIR);

// We're done with processing Phorum.
return;

// Let the connector create customized URL's.
function phorum_custom_get_url ($phorum_embed_page, $query_items, $suffix)
{
    global $PHORUM_CONNECTOR;

    // Give the connector a chance to create a custom profile url.
    // This mostly provides for an easy connector method to do this,
    // because the get_url() method could handle this as well.
    if ($phorum_embed_page == 'profile' && ! isset($GLOBALS["PHORUM"]["ignore_get_profile_link"])) {
        $link = $PHORUM_CONNECTOR->get_profile_link($query_items[1]);
        if ($link == -1) $GLOBALS["PHORUM"]["ignore_get_profile_link"] = true;
        elseif ($link != NULL) return $link;
    }

    return $PHORUM_CONNECTOR->get_url($phorum_embed_page,$query_items,$suffix);
}

// Create a separate namespace for Phorum by running this inside
// a function and process the page output.
function phorum_process_page($phorum_embed_page)
{
    global $PHORUM;
    global $PHORUM_CONNECTOR;

    // Capture the output of the Phorum software.
    ob_start();
    include_once("./$phorum_embed_page.php");
    $content = ob_get_contents();
    if (ob_get_level()) ob_end_clean();

    // Pages for which direct and raw output is needed.
    if ($phorum_embed_page == "rss" ||
        // Phorum 5.1 compatibility
        ($phorum_embed_page == "file" && isset($send_file) && $send_file)) {
        // Flush any buffered data, since that would break the output.
        while (ob_get_level() > 0) ob_end_clean();

        print $content;
        exit;
    }

    // Apply fixes to the content. In the default connector,
    // relative img URL's are rewritten, because Phorum is running
    // in a different location than the embedded Phorum in most
    // cases.
    $newcontent = $PHORUM_CONNECTOR->fix_content($content);
    if ($newcontent != NULL) $content = $newcontent;

    // The templates for the embedded phorum contain special tags,
    // which are used for splitting the page in multiple elements.
    // These tags look like:
    //
    // [element <varname>]
    //
    // Based on this, an array will be filled. That array is 
    // passed on to the connector object to put the page elements
    // in the right places.

    // Our default list of page elements. Those that have NULL here
    // have to be available in the templates. Not having them available
    // there will result in an error message.
    $elements = array(
        'style'         => '',
        'base_href'     => '',
        'http_path'     => $GLOBALS["PHORUM"]["http_path"],
        'rss_link'      => '',
        'rss_url'       => '',
        'redirect_meta' => '',
        'redirect_url'  => '',
        'redirect_time' => '',
        'lang_meta'     => '',
        'title'         => NULL,
        'head_data'     => '',
        'body_onload'   => '',
        'body_data'     => NULL,
    );

    // Transfer page data to the elements array.
    $parts = explode("[element ", $content);
    foreach ($parts as $id => $part) 
    {
        
        // The first part should be empty. If it's not, we store
        // the output that we got in "unexpected_output".
        if ($id == 0) {
            $part = trim($part);
            if ($part != '') {
                $elements["unexpected_output"] = $part;
            }
        }

        $keyval = explode("]", $part, 2);
        if (isset($keyval[1])) {
            $elements[$keyval[0]] = $keyval[1];
        }
    }

    // Check for missing elements in the template.
    foreach ($elements as $key => $val) {
        if ($val == NULL) {
            print "embed_phorum module error: page element \"$key\" " .
                  "is missing in the templates. Did you select the " .
                  "correct template for this forum? The output that we " .
                  "did get is the following:<hr><br>" .
                  $content;
            die();
        }
    }

    // Hand over the page elements to the connector for further processing.
    $PHORUM_CONNECTOR->process_page_elements($elements);
}
?>
