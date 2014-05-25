<?php

$MASTER_DIR = getcwd();
$PHORUM_DIR = "include/phorum";
require_once("$PHORUM_DIR/mods/embed_phorum/PhorumConnectorBase.php");
require_once("$PHORUM_DIR/mods/embed_phorum/homeo_connector.php");
global $PHORUM_CONNECTOR;
$PHORUM_CONNECTOR = new HomeophorumConnector();
if (! chdir($PHORUM_DIR)) {
    die("embed_phorum error: Cannot change directory to " .
        '"' . htmlspecialchars($PHORUM_DIR) . '".');
}
include_once ("./common.php");
chdir($MASTER_DIR);

// This script provides functionality for synchronizing user data from
// a master system to the Phorum system. It is supposed to be loaded
// from another script, in which the Phorum database already was setup
// (so basically, Phorum's common.php should be loaded).

if(!defined("PHORUM")) return;

// Synchronize a user with the Phorum system. This is done based on
// the user_id field. The $syncuser argument should contain fields
// like they are in Phorum's users table. Synchronization based on
// the username only is not yet handled.
function embed_phorum_syncuser($syncuser)
{
    // Check the user_id in the request.
    if (! isset($syncuser["user_id"])) {
        die("embed_phorum_syncuser(): no user_id found in the syncuser data");
    }
    if (! preg_match('/^\d+$/',  $syncuser["user_id"])) {
        die("syncuser error: non-numerical user_id found in the syncuser data");
    }

    // Check if we already have a user for the given user_id.
    $user = phorum_user_get($syncuser["user_id"], false, false);

    // For extra security, we do not want to store a password 
    // when it is not used. For admins, we need to store the password
    // so they can access Phorum's admin interface.
    if (!isset($syncuser["admin"]) || !$syncuser["admin"]) {
        $syncuser["password"] = "";
    }

    // Also, we always tell Phorum that the user is active. Since 
    // the master application handles authentication, there's no need
    // to have that in Phorum too. Furthermore, the active field 
    // could be easily forgotten when syncing users.
    $syncuser["active"] = 1;

    // Existing user found. Do a user update.
    if ($user) 
    {
        // We do not support changing usernames (yet). If Phorum supports
        // this, we can add it here too.
        if ($user["username"] != $syncuser["username"]) {
            die("syncuser error: usernames cannot be changed, but for " .
                "user_id " . $user["user_id"] . " the username was " .
                "changed from " . $user["username"] . " to " .
                $syncuser["username"]);
        }

        // Collect all fields that are changed.
        $user_update = array(); 
        foreach ($syncuser as $key => $val) {
            if (! isset($user[$key]) || $user[$key] !== $syncuser[$key]) {
                $user_update[$key] = $syncuser[$key];
            }
        }

        // If changes are found, update the database.
        if (count($user_update)) {
            $user_update["user_id"] = $user["user_id"];
            phorum_user_save($user_update);
        }
    }
    // No user found. Add the user.
    else
    {
        phorum_user_add($syncuser);
    }

    // A special hack to make it possible to feed Phorum already MD5 encrypted 
    // passwords. If the password is formatted as $MD5$<mdpassword>, then the
    // database will be updated with this MD5 password.
    if (isset($syncuser["password"]) && 
        strlen($syncuser["password"]) == 37 &&
        substr($syncuser["password"], 0, 5) == '$MD5$') {

        $md5 = substr($syncuser["password"], 5);
        phorum_db_user_save(array(
            "user_id" => $syncuser["user_id"],
            "password" => $md5
        ));
    }
}

function embed_phorum_deleteuser($syncuser)
{
    // Check the user_id in the request.
    if (! isset($syncuser["user_id"])) {
        die("embed_phorum_deleteuser(): no user_id found in the syncuser data: " . var_dump ($syncuser));
    }

    phorum_db_user_delete($syncuser["user_id"]);
}

?>
