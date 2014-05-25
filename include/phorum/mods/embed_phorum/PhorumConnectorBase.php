<?php
/**
 * The base class for a Phorum embedding connector.
 */
class PhorumConnectorBase
{
    // ----------------------------------------------------------------------
    // Class variables
    // ----------------------------------------------------------------------

    /** 
     * The name to use for this connector. Mainly used for being able
     * to give the connector module a name in error messages.
     */
    var $name = "<unnamed>";

    // ----------------------------------------------------------------------
    // Event methods
    // ----------------------------------------------------------------------

    /**
     * Called during Phorum's "common_pre" hook.
     */
    function hook_common_pre() {}

    /**
     * Called during Phorum's "common" hook.
     */
    function hook_common() {}

    /**
     * Called during Phorum's "common_post_user" hook.
     */
    function hook_common_post_user() {}


    // ----------------------------------------------------------------------
    // Configuration methods
    // ----------------------------------------------------------------------

    /**
     * Returns the page to redirect to for various events. 
     * Possible events are:
     *
     * admin_only   - Phorum is in admin-only mode.
     * disabled     - Phorum is in disabled mode.
     * register     - the registration page is requested
     * login        - the login page is requested
     * logout       - logout is requested
     *
     * @param $event - The event name.
     * @return The page to redirect to or NULL for no redirect
     */
    function get_redirect_page($event)
    {
        switch ($event)
        {
            case "admin_only":
            case "disabled":
              return "/";
              break;

            default:
              return NULL;
        }
    }

    /**
     * Returns an array of the user table fields that the slave (Phorum)
     * is allowed to edit (through the user's control center). Supported
     * fields (those the embed_phorum template is aware of) for this
     * method are:
     *
     * - real_name
     * - signature
     * - email
     * - hide_activity
     * - hide_email
     * - moderation_email
     * - tz_offset
     * - is_dst
     * - user_language
     * - user_template
     * - threaded_list
     * - threaded_read
     * - email_notify
     * - show_signature
     * - pm_email_notify
     *
     * Apart from these fields, you are free to put all the fields that
     * you like (from Phorum's users table) in here. When used in 
     * templates, this array of fields will be available in the templates
     * as {ALLOW_CHANGE->fieldname}
     *
     * @return An array of user fields that the slave (Phorum) may change
     */
    function get_slave_fields()
    {
        return array();
    }


    // ----------------------------------------------------------------------
    // Master application interfacing  methods
    // ----------------------------------------------------------------------

    /**
     * This method will generate custom URL's for Phorum.
     * The arguments match the arguments that the normal function
     * phorum_custom_get_url() would get.
     *
     * This method has a direct relation with the parse_request() method.
     *
     * @param $page - The Phorum page to which should be linked
     * @param $query_items - An array of query items to put in the URL
     * @param $suffix - Extra data that has to be added to the URL
     * @return $url - The completely formatted URL
     */
    function get_url($page, $query_items, $suffix)
    {
        $url = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") 
             ? "https://" : "http://") . 
             $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?$page";
        if (count($query_items)) $url .= "," . implode(",", $query_items);
        if (!empty($suffix)) $url .= $suffix;

        return $url;
    }

    /**
     * This method parses the request and must take care of:
     *
     * - setting the query string that Phorum should use in Phorum's query
     *   override variable $PHORUM_CUSTOM_QUERY_STRING
     * - returning the Phorum page that should be shown
     *
     * @param $qstr - The HTTP query string to process
     * @return The Phorum page to show
     */
    function parse_request($qstr)
    {
        // In case the Phorum page is at the start of the query string.
        if (preg_match("/^(\w[\w_-]+)(,|$)/", $qstr, $match))
        {
            $query = str_replace($match[0], "", $qstr);
            $GLOBALS["PHORUM_CUSTOM_QUERY_STRING"] = $query;
            $page = basename($match[1]);
        }
        // In case the Phorum page is sent as page=<page> in the query string.
        elseif (isset($_REQUEST["page"]))
        {
            $page = basename($_REQUEST["page"]);
            $getparts = array();
            foreach (explode("&", $qstr) as $q) {
                if (substr($q, 0, 5) != "page=") {
                    $getparts[] = $q;
                }
            }
            $GLOBALS["PHORUM_CUSTOM_QUERY_STRING"] = implode(",", $getparts);
        }
        // We do not know what page we are handling now. Fallback to the index.
        else {
            $page="index";
        }
        return $page;
    }

    /**
     * This method must return the (Phorum) user id of the logged in
     * user or NULL if no user is logged in. In this method on-the-fly
     * synchronization of users could be performed, before returning the
     * user_id. This is not the preferred way of synchronizing users,
     * but it might be the only way available for some systems.
     *
     * This method must be overridden in descendant classes.
     *
     * @return The user_id of the logged in user.
     */
    function get_user_id() {
        die("Phorum embedding connector \"" . $this->name . "\" " .
            "does not implement get_user_id()");
    }

    /**
     * This method can return a modified profile link for the given user_id.
     * If no modified link is needed, the method can return NULL.
     * The base implementation will return -1, to flag the calling appliaction
     * that the feature is not needed at all.
     *
     * The returned link will be the value for the href="..." parameter,
     * so it could even contain javascript by starting with "javascript:".
     *
     * @param The user_id for the user to create a link for
     * @return The profile link, NULL if no special link needed and
     *         -1 if this feature isn't used at all
     */
    function get_profile_link($user_id) {
        return -1;
    }

    /**
     * This method must return the Phorum template to use or NULL
     * in case Phorum can decide what template to use.
     *
     * @return The name of the Phorum template to use.
     */
    function get_template() {
        return NULL;
    }

    /**
     * This method will retrieve an array of Phorum page elements as its 
     * argument. It will have to fit those elements into the master
     * application's displaying system. 
     *
     * This method must be overridden in descendant classes.
     *
     * @param An array containing Phorum page elements.
     */
    function process_page_elements($elements)
    {
        die("Phorum embedding connector \"" . $this->name . "\" " .
            "does not implement process_page_elements()");
    }

    /** 
     * This method can apply last minute fixes to the content. The whole
     * content is passed as an argument. If nothing was changed, the
     * method can return NULL.
     *
     * The default implementation of this method tries to fix all relative
     * URLs in the Phorum output to point to the Phorum installation.
     * In case the parent application includes a <base href> in the
     * header that points to Phorum, the method can be overridden to
     * simply return NULL.
     *
     * @param The full Phorum page content.
     * @return The fixed content or NULL if no fixes were made.
     */ 
    function fix_content($content)
    {
        $PHORUM = $GLOBALS["PHORUM"];

        // Fix relative links in Phorum output
        // -----------------------------------
        //
        // Find spots where href or src references are used, containing
        // some reference relative to the Phorum web directory.
        // This code looks for constructions like:
        // href="*"
        // src="*"
        // *.href="*" (can occur in javascript code, like document.href="")
        // *.src="*"
        // url(*)     (can occur in css code)
        // It's not perfect because the HTML tree is not really parsed. But it
        // might be good enough for most cases.

        $match = '/
            ([\s\.](?:href|src)\s*=\s*["\']     # Match href=".." or src=".."
             |                                  # or
             :\s*(?:\S*)\s*url\()              # Match :url(..)
            ([^"\)\']+)                         # Match URL part
            (["\)\'])                           # Match end of construction
            /x';

        if (preg_match_all($match, $content, $m ))
        {
            for ($i = 0; isset($m[0][$i]); $i++)
            {
                $slashpos = strpos($m[2][$i], "/");
                if ($slashpos === false) continue;
                $firstpart = substr($m[2][$i], 0, $slashpos);
                if ($firstpart == '.' || 
                    $firstpart == 'mods' || 
                    $firstpart == 'templates' ||
                    $firstpart == 'images') {

                    $dst = str_replace(
                        $m[2][$i],
                        $PHORUM["http_path"] . "/" . $m[2][$i],
                        $m[0][$i]
                    );

                    $content = str_replace($m[0][$i], $dst, $content);
                }
            }

            return $content;
        } else {
            return NULL;
        }
    }
}

?>
