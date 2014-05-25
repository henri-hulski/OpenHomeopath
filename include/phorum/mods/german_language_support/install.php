<?php

//
// This install file will be included by the module automatically at the first
// time that it is run. This file will take care of storing the default
// settings for this module. This way, the administrator won't have to call the
// settings page.
//

if (!defined('PHORUM')) return;

// Store module default setting.
$PHORUM['mod_german_language_support'] = array
    ( 'buddy' => 1,
      'sticky' => 3,
      'email_format' => 1 );

if (!phorum_db_update_settings(array('mod_german_language_support'=>$PHORUM['mod_german_language_support']))) {
    $error = 'Database error while updating settings.';
}

?>
