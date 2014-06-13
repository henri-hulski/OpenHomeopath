<?php
    // This file ist part of the German Language Files Package
    // Get the complete package here:
    // http://www.phorum.org/phorum5/read.php?17,116038

    // Diese Datei ist Teil des Deutschen Sprachpakets
    // Das komplette Paket gibt es hier:
    // http://www.phorum.org/phorum5/read.php?17,116038

    if(!defined('PHORUM')) return;

    // Do not change anything in this file!

    // Buddy
    switch ($PHORUM['mod_german_language_support']['buddy']) {
    case 0:
        // Buddy
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmerin meiner Buddy-Liste hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Die Teilnehmerin konnte nicht Deiner Buddy-Liste hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Die Teilnehmerin wurde Deiner Buddy-Liste hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Deine Buddy-Liste ist leer.<br>Um Teilnehmerinnen hinzuzufügen, öffne deren Profil und klicke auf &quot;Teilnehmerin meiner Buddy-Liste hinzufügen&quot;.';
        break;
    case 1:
        // Freundin
        $PHORUM['DATA']['LANG']['Buddies']          = 'Liste der Freundinnen';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Freundin';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmerin meiner Liste der Freundinnen hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Die Teilnehmerin konnte nicht Deiner Liste der Freundinnen hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Die Teilnehmerin wurde Deiner Liste der Freundinnen hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Deine Liste der Freundinnen ist leer.<br>Um Teilnehmerinnen hinzuzufügen, öffne deren Profil und klicke auf &quot;Teilnehmerin meiner Liste der Freundinnen hinzufügen&quot;.';
        break;
    case 2:
        // Kollegin
        $PHORUM['DATA']['LANG']['Buddies']          = 'Liste der Kolleginnen';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Kollegin';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmerin meiner Liste der Kolleginnen hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Die Teilnehmerin konnte nicht Deiner Liste der Kolleginnen hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Die Teilnehmerin wurde Deiner Liste der Kolleginnen hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Deine Liste der Kolleginnen ist leer.<br>Um Teilnehmerinnen hinzuzufügen, öffne deren Profil und klicke auf &quot;Teilnehmerin meiner Liste der Kolleginnen hinzufügen&quot;.';
        break;
    case 3:
        // Bekannte
        $PHORUM['DATA']['LANG']['Buddies']          = 'Liste der Bekannten';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Bekannte';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmerin meiner Liste der Bekannten hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Die Teilnehmerin konnte nicht Deiner Liste der Bekannten hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Die Teilnehmerin wurde Deiner Liste der Bekannten hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Deine Liste der Bekannten ist leer.<br>Um Teilnehmer hinzuzufügen, öffne deren Profil und klicke auf &quot;Teilnehmerin meiner Liste der Bekannten hinzufügen&quot;.';
        break;
    default:
        // Freundin
        break;
    }

?>