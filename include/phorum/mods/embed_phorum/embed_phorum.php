<?php

if(!defined("PHORUM")) return;

define('MOD_EMBED_SESSION_COOKIE', 'phorum_mod_embed_session');

// For PHORUM_SESSION_LONG_TERM definition.
include_once("include/users.php");

function phorum_mod_embed_phorum_common_pre()
{
    // We do not want to run this when we're in the admin or upgrade interface.
    if (defined('PHORUM_ADMIN') || defined('PHORUM5_CONVERSION')) return;

    if (! isset($GLOBALS["PHORUM_CONNECTOR"])) die(" 
        The embed_phorum module needs a global variable \$PHORUM_CONNECTOR
        that contains a PhorumConnector class. This is not the case, which
        probably means that the Phorum is accessed directly instead of 
        through the master application in which Phorum was embedded.
    ");
    global $PHORUM_CONNECTOR;

    // Give the connector a chance to act on the common_pre hook.
    $GLOBALS["PHORUM_CONNECTOR"]->hook_common_pre();

    // Fake an environment where we have no cookies and no tight security.
    // Because Phorum is run as embedded slave software, we only want
    // Phorum to simply recognize the user as being logged in.
    $GLOBALS["PHORUM"]["tight_security"] = false;
    $GLOBALS["PHORUM"]["use_cookies"] = false;

    // Retrieve the user id for the logged in user. If no user is logged
    // in, the connector can simply return NULL.
    $user_id = $PHORUM_CONNECTOR->get_user_id();

    // Cleanup login stuff in case no user is logged in.
    if ($user_id == NULL) {
        phorum_mod_embed_phorum_sessioncookie(NULL);
        unset($_COOKIE[MOD_EMBED_SESSION_COOKIE]);
        return;
    }

    // Check if we already have a session cookie set for the logged in user.
    $set_cookie = true;
    if (isset($_COOKIE[MOD_EMBED_SESSION_COOKIE])) {
        $cookie = $_COOKIE[MOD_EMBED_SESSION_COOKIE];
        list ($sessuser, $sessid) = explode(":", $cookie);
        if ($sessuser == $user_id) {
            $set_cookie = false;
        }
    }

    // No session id cookie found for this user?
    if ($set_cookie)
    {
        // Try to fetch the user from the Phorum database.
        $user = phorum_db_user_get($user_id, false);

        // The user was not found in Phorum's database.
        // The master application probably has not synchronized the
        // user data to the Phorum database.
        if (! $user) {
            die("No user data found for user id $user_id. " .
                "The master application for Phorum probably did " .
                "not synchronize the user data.");
        }

        // Check if the user already has a cookie value stored
        // in the database. If this is the case, then recycle that cookie.
        // Else, create a new session cookie for the user. We could always
        // generate a new cookie at this point, but I have had some trouble
        // with cookies dissappearing from the browser request for unknown
        // reasons (no reproducable pattern, only the $_COOKIE variable
        // was not set, while in the followup request it would be
        // available again; could be a browser issue). This way this
        // problem is fully tackled.

        // Do we have a session id that was set by this module?
        // Then recycle it.
        if (! empty($user["cookie_sessid_lt"]) &&
            substr($user["cookie_sessid_lt"], 0, 6) == "embed_") {

            // Recycle existing session id.
            $sessid = $user["cookie_sessid_lt"];

        // Create a new session id. We have 50 chars in the database for
        // storing the session id, so we append some extra info for even
        // more uniqueness.
        } else {

            // Generate a new session id. We do not use the password
            // field here like Phorum does. Because we're running as
            // a slave, the password field is not used by Phorum and
            // it could be an empty field. Therefore, we generate a
            // random password to use for this.
            require_once("include/profile_functions.php");
            $random = phorum_gen_password();
            $sessid = "embed_{$user_id}_" . md5($user["username"] . $random. microtime());
        }

        // Store the generated session id in the Phorum database for the user.
        require_once("include/users.php");
        phorum_user_save_simple(array(
            "user_id" => $user_id,
            "cookie_sessid_lt" => $sessid,
            "sessid_st" => $sessid
        ));

        // Set a session cookie, so for the next call we do
        // not have to generate and store one again.
        phorum_mod_embed_phorum_sessioncookie("{$user_id}:{$sessid}");
    }

    // Setup (fake) URI authentication for Phorum to recognize the user.
    $GLOBALS["PHORUM"]["args"][PHORUM_SESSION_LONG_TERM] = $sessid;
}

function phorum_mod_embed_phorum_common_post_user()
{
    // We do not want to run this when we're in the admin or upgrade interface.
    if (defined('PHORUM_ADMIN') || defined('PHORUM5_CONVERSION')) return;

    // Feed Phorum a specific template if the connector returns one.
    phorum_mod_embed_set_template();

    // Give the connector a chance to act on the common_post_user hook.
    $GLOBALS["PHORUM_CONNECTOR"]->hook_common_post_user();

    // Because Phorum is lured into authenticating the user through
    // URI authentication, Phorum will generate URL's with URI auth
    // in them. We do not want that, so here we clean up that mess.
    unset($GLOBALS["PHORUM"]["DATA"]["GET_VARS"][PHORUM_SESSION_LONG_TERM]);
    // Unfortunately POST_VARS isn't an array, so we have to do more work here.
    $GLOBALS["PHORUM"]["DATA"]["POST_VARS"] = preg_replace(
        '/<[^>]+name="'.PHORUM_SESSION_LONG_TERM.'"[^>]+>/',
        '',
        $GLOBALS["PHORUM"]["DATA"]["POST_VARS"]
    );

    // If Phorum is in admin-only or disabled mode, we have to handle
    // this ourselves to make it work smoothly. If we do not do that,
    // Phorum itself will display an error and call exit() from common.php.
    // When that happens, the embedding code will never be reached and
    // control will never be handed back over to the master application.
    //
    // The only thing that we can do here is redirect the user to
    // another page. Which page to redirect to can be configured by
    // setting the appropriate class variables in the connector class.
    //
    $redir = NULL;
    if(isset($GLOBALS["PHORUM"]["status"])) {
        if ($GLOBALS["PHORUM"]["status"]=="admin-only" &&
            !$GLOBALS["PHORUM"]["user"]["admin"]) {
            $redir = $GLOBALS["PHORUM_CONNECTOR"]->get_redirect_page('admin_only');
            $redir = is_null($redir) ? "/" : $redir; // paranoia
        } elseif ($GLOBALS["PHORUM"]["status"]=="disabled") {
            $redir = $GLOBALS["PHORUM_CONNECTOR"]->get_redirect_page('disabled');
            $redir = is_null($redir) ? "/" : $redir; // paranoia
        }
    }
    if ($redir != NULL) {
        phorum_redirect_by_url($redir);
        exit;
    }
}

// This function takes care of setting the embedding session cookie.
// Because we are running embedded, the master application might already
// have sent the HTTP headers to the client. So we cannot rely on simply
// calling setcookie() here (that might spawn the infamous "Headers already
// sent" error). As a workaround, we will load an image which will set
// the cookie instead. This function simply determines whether we are
// displaying the image or another part of the application and act upon that.
//
// The parameter $value can be either the cookie value to set or NULL
// to drop the cookie.
function phorum_mod_embed_phorum_sessioncookie($value)
{
	// Check if we need to display the image and set the cookie.
	if (isset($GLOBALS["PHORUM"]["args"]["mod_embed_session_cookie"]))
	{
		// Display a 1x1 transparent gif image.
		header("Content-type: image/gif");
		print "\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00\x91\x00\x00" .
		"\x00\x66\x99\x00\x00\x00\x00\x00\x00\x00\x00\x00\x21" .
		"\xf9\x04\x09\x00\x00\x00\x00\x2c\x00\x00\x00\x00\x01" .
		"\x00\x01\x00\x00\x08\x04\x00\x01\x04\x04\x00\x3b";
	
		// Set or delete the session cookie.
		if ($value == NULL) {
		$value = "";
		$expire = time() - 86400;
		} else {
		$expire = 0;
		}
		setcookie(
		MOD_EMBED_SESSION_COOKIE,
		$value, $expire, 
		$GLOBALS["PHORUM"]["session_path"],
		$GLOBALS["PHORUM"]["session_domain"]
		);
	
		// We're done!
		exit;
	}
	
	// Put in special image code if needed.
	if (defined('phorum_page')) {
		switch (phorum_page)
		{
			// NOOP for these pages. These pages have to produce
			// raw output, so we never put a cookie image in there.
			case 'rss':
			case 'file':
			break;
		
			// All other pages include a link to the setcookie image code.
			// Here we store the cookie in a variable, so in the after_header
			// hook, we can print the image. We cannot print it here, because
			// then the image code will fall outside the [element] blocks of
			// the embedding template.
			default:
			$url = phorum_get_url(PHORUM_CUSTOM_URL, 'index', false, 'mod_embed_session_cookie=1');
			$GLOBALS["PHORUM"]["MOD_EMBED_SESSION_COOKIE_IMAGE"] = 
				'<img style="display:none; border:none" width="0" height="0" '.
				'alt="" src="' . htmlspecialchars($url) . '" />';
		}
	}
}

function phorum_mod_embed_phorum_common()
{
	// We do not want to run this when we're in the admin or upgrade interface.
	if (defined('PHORUM_ADMIN') || defined('PHORUM5_CONVERSION')) return;
	
	// Feed Phorum a specific template if the connector returns one.
	phorum_mod_embed_set_template();
	
	$CONNECTOR =& $GLOBALS["PHORUM_CONNECTOR"];
	
	// Give the connector a chance to act on the common hook.
	$CONNECTOR->hook_common();
	
	// Special actions that we have to do for different Phorum pages.
	if (defined('phorum_page')) {
		switch (phorum_page)
		{
			// Login and registration.
			case "login":
			case "register":
			// If the user is logged in, we redirect to the index page
			// when accessing the login or registration page.
			if ($GLOBALS["PHORUM"]["DATA"]["LOGGEDIN"]) {
				phorum_redirect_by_url(phorum_get_url(PHORUM_INDEX_URL));
				exit();
			}
		
			// In case we have set a redirect for the page in the
			// connector object, we use that to redirect the user.
			$redir = $CONNECTOR->get_redirect_page(phorum_page);
			if ($redir != NULL) {
				phorum_redirect_by_url($redir);
				exit();
			}
		
			// No redirect was set. Since logging in and registration are
			// a task of the master application, we'll empty the request
			// environment, so Phorum will never handle them.
			$_REQUEST = $_GET = $_POST = array();
		
			break;
		
			// The user's control center.
			case "control":
			// Put the user fields that may be changed by Phorum
			// into the template data, so it can be used to hide
			// the fields that may not be changed.
			$allow = $CONNECTOR->get_slave_fields();
			$GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"] = array();
			foreach ($allow as $field) {
				$GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"][$field] = 1;
			}
		
			// Some fields that depend on other fields. Make sure that
			// dependancies are met.
			if (isset($GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"]["tz_offset"]))
				$GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"]["is_dst"] = 1;
			if (isset($GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"]["email"]))
				$GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"]["email_temp"] = 1;
		
			// Remember values for checkboxes. This code makes it
			// possible to handle checkboxes on pages where Phorum does
			// not expect them. This can be done by adding a hidden
			// field to the form (next to the checkbox code), looking like:
			// <input type="hidden" name="checkbox[fieldname]" value="..."/>
			// The value can be either "+" or "-", based on whether the
			// checkbox should be handled positive or negative (some of
			// Phorum's user settings are formulated inverse from the 
			// stored setting value). Final handling is done in the hook
			// function phorum_mod_embed_phorum_cc_save_user().
			if (isset($_POST["checkbox"]) and is_array($_POST["checkbox"])) {
				foreach ($_POST["checkbox"] as $field => $type) {
				$value = isset($_POST[$field]) ? 1 : 0;
				if ($type == '-') $value = $value ? 0 : 1;
				$GLOBALS["PHORUM"]["EMBED_CHECKBOXES"][$field] = $value;
				}
			}
		
			// The email field is mandatory in the forms, but we might
			// have removed it from the forms because Phorum is not
			// allowed to edit it. Make sure that no errors are spawned
			// by setting it to the already configured email address.
			if (count($_POST) && ! isset($GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"]["email"])) {
				$_POST["email"] = $GLOBALS["PHORUM"]["user"]["email"];
			}
		
			break;
		}
	}
}

function phorum_mod_embed_phorum_after_header()
{
    global $PHORUM;
    $CONNECTOR =& $GLOBALS["PHORUM_CONNECTOR"];

    // Put some URL's that can be overridden by the connector
    // object in the template data.
    $PHORUM["DATA"]["URL"]["REGISTER"] =
        $CONNECTOR->get_redirect_page("register");

    if ($PHORUM["DATA"]["LOGGEDIN"]) {
        $PHORUM["DATA"]["URL"]["LOGINOUT"] =
            $CONNECTOR->get_redirect_page("logout");
    } else {
        $PHORUM["DATA"]["URL"]["LOGINOUT"] =
            $CONNECTOR->get_redirect_page("login");
    }

    // Print a cookie image if one is set.
    if (isset($PHORUM["MOD_EMBED_SESSION_COOKIE_IMAGE"])) {
        print $PHORUM["MOD_EMBED_SESSION_COOKIE_IMAGE"];
    }
}

function phorum_mod_embed_phorum_cc_save_user($data)
{
    // Put in the values of checkboxes that were indicated using the
    // special <input type="hidden" name="checkbox[fieldname]" value="+/-"/>
    // construction (which makes it possible to use checkboxes on panels
    // where Phorum does not expect them). These checkbox values were
    // determined in the phorum_mod_embed_phorum_common() function.
    if (!empty($GLOBALS["PHORUM"]["EMBED_CHECKBOXES"])) {
      foreach ($GLOBALS["PHORUM"]["EMBED_CHECKBOXES"] as $field => $val) {
          $data[$field] = $val;
      }
    }

    // Make sure that only fields that Phorum may change are sent
    // to the database.
    $filtered = array();
    $filtered["user_id"] = $data["user_id"];
    foreach ($GLOBALS["PHORUM"]["DATA"]["ALLOW_CHANGE"] as $field => $dummy) {
        if (array_key_exists($field, $data)) {
            $filtered[$field] = $data[$field];
        }
    }

    return $filtered;
}

// Feed Phorum a specific template if the connector returns one.
function phorum_mod_embed_set_template()
{
    static $tpl;
    if (! isset($tpl)) $tpl = $GLOBALS["PHORUM_CONNECTOR"]->get_template();
    if ($tpl != NULL) {
        $GLOBALS["PHORUM"]["template"] = $tpl;
        $GLOBALS["PHORUM"]["default_template"] = $tpl;
        $GLOBALS["PHORUM"]["user"]["user_template"] = $tpl;
        $GLOBALS["PHORUM"]["SETTINGS"]["user_template"] = $tpl;
        $GLOBALS["PHORUM"]["user_template"] = 0;
    }

    return $tpl;
}

?>
