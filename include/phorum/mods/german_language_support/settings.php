<?php

// Make sure that this script is loaded from the admin interface.
if (!defined('PHORUM_ADMIN')) return;

// Store once module default settings
if (!isset($PHORUM['mod_german_language_support'])) {
    include('./mods/german_language_support/install.php');
}

// Save settings in case this script is run after posting
// the settings form.
if (    count($_POST)
     && isset($_POST['buddy'])
     && isset($_POST['sticky'])
     && isset($_POST['email_format']) ) {
    // Create the settings array for this module.
    $PHORUM['mod_german_language_support'] = array
        ( 'buddy' => $_POST['buddy'],
          'sticky' => $_POST['sticky'],
          'email_format' => $_POST['email_format'] );

    // Force some values to be an integer value.
    settype($PHORUM['mod_german_language_support']['buddy'], 'int');
    settype($PHORUM['mod_german_language_support']['sticky'], 'int');
    settype($PHORUM['mod_german_language_support']['email_format'], 'int');

    if (!phorum_db_update_settings(array('mod_german_language_support'=>$PHORUM['mod_german_language_support']))) {
        $error = 'Database error while updating settings.';
    } else {
        phorum_admin_okmsg('Settings Updated');
    }
}

// Apply default values for the settings.
if (!isset($PHORUM['mod_german_language_support']['buddy'])) {
    $PHORUM['mod_german_language_support']['sticky'] = 1; // Freund/Freundin
}
if (!isset($PHORUM['mod_german_language_support']['sticky'])) {
    $PHORUM['mod_german_language_support']['sticky'] = 3; // Wichtig
}
if (!isset($PHORUM['mod_german_language_support']['email_format'])) {
    $PHORUM['mod_german_language_support']['email_format'] = 1; // Extended
}

// We build the settings form by using the PhorumInputForm object.
include_once './include/admin/PhorumInputForm.php';
$frm =& new PhorumInputForm('', 'post', 'Save');
$frm->hidden('module', 'modsettings');
$frm->hidden('mod', 'german_language_support');

// Here we display an error in case one was set by saving
// the settings before.
if (!empty($error)){
    phorum_admin_error($error);
}

$frm->addbreak('Edit settings for the German Language Support Module');
// Buddy
$row = $frm->addrow
    ( 'Select translation for &quot;Buddy&quot;',
       $frm->select_tag
           ( 'buddy',
             array('Buddy', 'Freund/Freundin', 'Kollege/Kollegin', 'Kumpel/Bekannte'),
             $PHORUM['mod_german_language_support']['buddy'] ) );
$frm->addhelp
    ( $row,
      'Select translation for &quot;Buddy&quot;',
      'Select one of the offered translations for &quot;Buddy&quot;.' );
// Sticky
$row = $frm->addrow
    ( 'Select translation for &quot;Sticky&quot;',
       $frm->select_tag
           ( 'sticky',
             array('Festgepinnt', 'Pickert [österr.][ugs.]', 'Sticky', 'Wichtig'),
             $PHORUM['mod_german_language_support']['sticky'] ) );
$frm->addhelp
    ( $row,
      'Select translation for &quot;Sticky&quot;',
      'Select one of the offered translations for &quot;Sticky&quot;.' );
// Email format
$row = $frm->addrow
    ( 'Format of notification emails',
       $frm->select_tag
           ( 'email_format',
             array('Standard (WITHOUT topic content)', 'Extended (WITH topic content)'),
             $PHORUM['mod_german_language_support']['email_format'] ) );
$frm->addhelp
    ( $row,
      'Format of notification emails',
      'Select a format for the notification emails send to users. Standard format provides only a link to the new topic. Extended format includes additional the topic text.' );
// Show settings form
$frm->show();

?>
