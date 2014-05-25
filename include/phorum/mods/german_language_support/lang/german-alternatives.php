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
        $PHORUM['DATA']['LANG']['Buddies']          = 'Buddy-Liste';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Buddy';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmer meiner Buddy-Liste hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Der Teilnehmer konnte nicht Ihrer Buddy-Liste hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Der Teilnehmer wurde Ihrer Buddy-Liste hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Ihre Buddy-Liste ist leer.<br />Um Teilnehmer hinzuzufügen, öffnen Sie deren Profil und klicken Sie auf &quot;Teilnehmer meiner Buddy-Liste hinzufügen&quot;.';
        break;
    case 1:
        // Freund
        $PHORUM['DATA']['LANG']['Buddies']          = 'Liste der Freunde';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Freund';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmer meiner Liste der Freunde hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Der Teilnehmer konnte nicht Ihrer Liste der Freunde hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Der Teilnehmer wurde Ihrer Liste der Freunde hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Ihre Liste der Freunde ist leer.<br />Um Teilnehmer hinzuzufügen, öffnen Sie deren Profil und klicken Sie auf &quot;Teilnehmer meiner Liste der Freunde hinzufügen&quot;.';
        break;
    case 2:
        // Kollege
        $PHORUM['DATA']['LANG']['Buddies']          = 'Liste der Kollegen';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Kollege';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmer meiner Liste der Kollegen hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Der Teilnehmer konnte nicht Ihrer Liste der Kollegen hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Der Teilnehmer wurde Ihrer Liste der Kollegen hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Ihre Liste der Kollegen ist leer.<br />Um Teilnehmer hinzuzufügen, öffnen Sie deren Profil und klicken Sie auf &quot;Teilnehmer meiner Liste der Kollegen hinzufügen&quot;.';
        break;
    case 3:
        // Kumpel
        $PHORUM['DATA']['LANG']['Buddies']          = 'Liste der Kumpel';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Kumpel';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmer meiner Liste der Kumpel hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Der Teilnehmer konnte nicht Ihrer Liste der Kumpel hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Der Teilnehmer wurde Ihrer Liste der Kumpel hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Ihre Liste der Kumpel ist leer.<br />Um Teilnehmer hinzuzufügen, öffnen Sie deren Profil und klicken Sie auf &quot;Teilnehmer meiner Liste der Kumpel hinzufügen&quot;.';
        break;
    default:
        // Freund
        $PHORUM['DATA']['LANG']['Buddies']          = 'Liste der Freunde';
        $PHORUM['DATA']['LANG']['Buddy']            = 'Freund';
        $PHORUM['DATA']['LANG']['BuddyAdd']         = 'Teilnehmer meiner Liste der Freunde hinzufügen';
        $PHORUM['DATA']['LANG']['BuddyAddFail']     = 'Der Teilnehmer konnte nicht Ihrer Liste der Freunde hinzugefügt werden.';
        $PHORUM['DATA']['LANG']['BuddyAddSuccess']  = 'Der Teilnehmer wurde Ihrer Liste der Freunde hinzugefügt.';
        $PHORUM['DATA']['LANG']['BuddyListIsEmpty'] = 'Ihre Liste der Freunde ist leer.<br />Um Teilnehmer hinzuzufügen, öffnen Sie deren Profil und klicken Sie auf &quot;Teilnehmer meiner Liste der Freunde hinzufügen&quot;.';
        break;
    }

    // Sticky
    switch ($PHORUM['mod_german_language_support']['sticky']) {
    case 0:
        // Festgepinnt
        $PHORUM['DATA']['LANG']['MakeSticky'] = 'Als &quot;Festgepinnt&quot; kennzeichnen';
        $PHORUM['DATA']['LANG']['Sticky']     = 'Festgepinnt';
        break;
    case 1:
        // Pickert
        $PHORUM['DATA']['LANG']['MakeSticky'] = 'Als &quot;Pickert&quot; kennzeichnen';
        $PHORUM['DATA']['LANG']['Sticky']     = 'Pickert';
        break;
    case 2:
        // Sticky
        $PHORUM['DATA']['LANG']['MakeSticky'] = 'Als &quot;Sticky&quot; kennzeichnen';
        $PHORUM['DATA']['LANG']['Sticky']     = 'Sticky';
        break;
    case 3:
        // Wichtig
        $PHORUM['DATA']['LANG']['MakeSticky'] = 'Als &quot;Wichtig&quot; kennzeichnen';
        $PHORUM['DATA']['LANG']['Sticky']     = 'Wichtig';
        break;
    default:
        // Wichtig
        $PHORUM['DATA']['LANG']['MakeSticky'] = 'Als &quot;Wichtig&quot; kennzeichnen';
        $PHORUM['DATA']['LANG']['Sticky']     = 'Wichtig';
        break;
    }

    // Email format
    if ($PHORUM['mod_german_language_support']['email_format'] == 0) {
			// Restore standard email format (extended format is included in /include/lang/german.php)
      $PHORUM['DATA']['LANG']['NewModeratedMessage']   = "Ein neuer Beitrag wurde in einem von Ihnen moderierten Forum gemacht.\nDer Beitrag hat das Thema %subject%\nund kann unter\n%approve_url%\nbearbeitet werden.\n";

      $PHORUM['DATA']['LANG']['NewReplyMessage']       = "Es hat einen neuen Beitrag in einem von Ihnen beobachteten Thema gegeben.\nDer Beitrag hat den Betreff %subject%\nund kann unter\n%read_url%\neingesehen werden.\n";

      $PHORUM['DATA']['LANG']['NewUnModeratedMessage'] = "Es existiert ein neuer Beitrag in einem von Ihnen moderierten Forum.\nDieser Beitrag wurde von %author% mit dem Betreff %subject%\ngeschrieben und kann unter\n%read_url%\ngelesen werden.\n";

      $PHORUM['DATA']['LANG']['PMNotifyMessage']       = "Sie haben eine neue Privatnachricht erhalten.\n\nAutor: %author%\nBetreff: %subject%\n\nSie können die Nachricht unter\n%read_url%\neinsehen.\n";
		}

?>