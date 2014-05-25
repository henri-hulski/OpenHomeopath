<?php

// This file ist part of the German Language Files Package
// Get the complete package here:
// http://www.phorum.org/phorum5/read.php?17,116038

// Diese Datei ist Teil des Deutschen Sprachpakets
// Das komplette Paket gibt es hier:
// http://www.phorum.org/phorum5/read.php?17,116038

if (!defined('PHORUM')) return;

//
// Common hook: Store once module default settings.
//
function mod_german_language_support_common() {
    global $PHORUM;

    // Store once module default settings
    if (!isset($PHORUM['mod_german_language_support'])) {
        include('./mods/mod_german_language_support/install.php');
    }
}

//
// Add sanity checks
//
function mod_german_language_support_sanity_checks($sanity_checks) {

    if (    isset($sanity_checks)
         && is_array($sanity_checks) ) {
        $sanity_checks[] = array(
            'function'    => 'mod_german_language_support_do_sanity_checks',
            'description' => 'German Language Support Module'
        );
    }

    return $sanity_checks;
}

//
// Do sanity checks
//
function mod_german_language_support_do_sanity_checks() {
    global $PHORUM;

    // Check if module settings exists.
    if (    !isset($PHORUM['mod_german_language_support']['buddy'])
         || !$PHORUM['mod_german_language_support']['buddy']
         || !isset($PHORUM['mod_german_language_support']['sticky'])
         || !$PHORUM['mod_german_language_support']['sticky']
         || !isset($PHORUM['mod_german_language_support']['email_format'])
         || !$PHORUM['mod_german_language_support']['email_format'] ) {
          return array(
                     PHORUM_SANITY_CRIT,
                     'The default settings for the module are missing.',
                     'Login as administrator in Phorum´s administrative '
                         .'interface and go to the &quot;Modules&quot; section. Open '
                         .'the module settings for the German Language Support '
                         .'Module and save the default values.'
                 );
    }

    return array(PHORUM_SANITY_OK, NULL, NULL);
}

?>
