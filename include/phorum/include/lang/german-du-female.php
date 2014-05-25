<?php
    // This file ist part of the German Language Files Package
    // Get the complete package here:
    // http://www.phorum.org/phorum5/read.php?17,116038

    // DON'T TOUCH THIS FILE IF YOU WANT BE ABLE TO UPDATE THE GERMAN LANGUAGE
    // FILES PACKAGE IN THE FUTURE. USE INSTEAD THE FILES IN
    // mods/german_language_support/lang/

    // Diese Datei ist Teil des Deutschen Sprachpakets
    // Das komplette Paket gibt es hier:
    // http://www.phorum.org/phorum5/read.php?17,116038

    if(!defined('PHORUM')) return;

    include(dirname(__FILE__).'/german-du-male.php');

    $language = 'Deutsch (Du) weiblich';

    $PHORUM['DATA']['LANG']['Admin']                       = 'Administratorin';
    $PHORUM['DATA']['LANG']['AddToGroup']                  = 'Neue Teilnehmerin zu Gruppe hinzufügen:';
    $PHORUM['DATA']['LANG']['AllowSeeActivity']            = 'Andere Teilnehmerinnen sollen sehen, dass ich online bin';
    $PHORUM['DATA']['LANG']['AllowSeeEmail']               = 'Meine E-Mail-Adresse anderen Teilnehmerinnen sichtbar machen';
    $PHORUM['DATA']['LANG']['AnonymousUser']               = 'Anonyme Teilnehmerin';
    $PHORUM['DATA']['LANG']['Author']                      = 'Autorin';
    $PHORUM['DATA']['LANG']['Buddies']                     = 'Liste der Freundinnen';
    $PHORUM['DATA']['LANG']['Buddy']                       = 'Freundin';
    $PHORUM['DATA']['LANG']['BuddyAdd']                    = 'Teilnehmerin meiner Liste der Freundinnen hinzufügen';
    $PHORUM['DATA']['LANG']['BuddyAddFail']                = 'Die Teilnehmerin konnte nicht Deiner Liste der Freundinnen hinzugefügt werden.';
    $PHORUM['DATA']['LANG']['BuddyAddSuccess']             = 'Die Teilnehmerin wurde Deiner Liste der Freundinnen hinzugefügt.';
    $PHORUM['DATA']['LANG']['BuddyListIsEmpty']            = 'Deine Liste der Freundinnen ist leer.<br />Um Teilnehmerinnen hinzuzufügen, öffne deren Profil und klicke auf &quot;Teilnehmerin meiner Liste der Freundinnen hinzufügen&quot;.';
    $PHORUM['DATA']['LANG']['DeleteAnnouncementForbidden'] = 'Leider dürfen nur Administratorinnen Ankündigungen löschen.';
    $PHORUM['DATA']['LANG']['EditPostForbidden']           = 'Du hast leider keine Berechtigung, diesen Beitrag zu bearbeiten. Vielleicht liegt dies aber auch daran, dass die Administratorin ein Zeitlimit für das Bearbeiten der Beiträge gesetzt hat.';
    $PHORUM['DATA']['LANG']['ErrAuthor']                   = 'Bitte Autorin angeben.';
    $PHORUM['DATA']['LANG']['ErrBannedContent']            = 'Ein Wort, das Du in Deiner Nachricht verwendest, wurde von uns gesperrt. Verwende bitte ein anderes Wort oder setze Dich mit den Administratorinnen des Forums in Verbindung.';
    $PHORUM['DATA']['LANG']['ErrBannedEmail']              = 'Deine E-Mail-Adresse wurde von der Benutzung ausgeschlossen. Bitte benutze eine andere E-Mail-Adresse oder setze Dich mit den Administratorinnen des Forums in Verbindung.';
    $PHORUM['DATA']['LANG']['ErrBannedIP']                 = 'Deine IP-Adresse, Domain oder Dein Internetprovider wurde gesperrt. Bitte setze Dich mit den Administratorinnen des Forums in Verbindung';
    $PHORUM['DATA']['LANG']['ErrBannedName']               = 'Dein Name oder Teilnehmername wurde von der Verwendung ausgeschlossen. Bitte wähle einen anderen Namen oder setze Dich mit den Administratorinnen des Forums in Verbindung.';
    $PHORUM['DATA']['LANG']['ErrBannedUser']               = 'Die Teilnehmerin &quot;%name%&quot; wurde gesperrt.';
    $PHORUM['DATA']['LANG']['ErrEmailExists']              = 'Die eingegebene E-Mail-Adresse ist bereits einer anderen Teilnehmerin zugeordnet.';
    $PHORUM['DATA']['LANG']['ErrRegisterdEmail']           = 'Die von Dir angegebene E-Mail-Adresse wird bereits von einer anderen Teilnehmerin verwendet. Wenn Du diese Teilnehmerin bist, logge Dich bitte ein. Ansonsten nutze bitte eine andere E-Mail-Adresse.';
    $PHORUM['DATA']['LANG']['ErrRegisterdName']            = 'Dieser Name wird bereits von einer anderen Teilnehmerin verwendet. Wenn Du diejenige bist, logge Dich bitte ein. Ansonsten nutze bitte einen anderen Namen.';
    $PHORUM['DATA']['LANG']['ErrUserAddUpdate']            = 'Teilnehmerin nicht hinzugefügt/geändert. Unbekannter Fehler.';
    $PHORUM['DATA']['LANG']['ErrUsername']                 = 'Bitte Name der Teilnehmerin angeben.';
    $PHORUM['DATA']['LANG']['GroupJoinFail']               = 'Du konntest nicht als Teilnehmerin hinzugefügt werden.';
    $PHORUM['DATA']['LANG']['GroupJoinSuccess']            = 'Du wurdest erfolgreich Teilnehmerin der Gruppe.';
    $PHORUM['DATA']['LANG']['GroupJoinSuccessModerated']   = 'Dein Gesuch wurde registriert. Weil es sich um eine moderierte Gruppe handelt, muss Deine Zugehörigkeit von einer Moderatorin bestätigt werden.';
    $PHORUM['DATA']['LANG']['GroupMemberList']             = 'Liste aller Gruppenteilnehmerinnen';
    $PHORUM['DATA']['LANG']['HideEmail']                   = 'Meine E-Mail-Adresse vor anderen Teilnehmerinnen verstecken';
    $PHORUM['DATA']['LANG']['InvalidLogin']                = 'Der Name der Teilnehmerin wurde nicht gefunden bzw. ist inaktiv. Versuche es bitte noch einmal.';
    $PHORUM['DATA']['LANG']['JoinGroupDescription']        = 'Um einer Gruppe beizutreten, wähle diese aus der Liste aus. Die mit einem * markierten Gruppen sind moderiert, d.h. Deine Zugehörigkeit muss von einer Moderatorin akzeptiert werden.';
    $PHORUM['DATA']['LANG']['Member']                      = 'Teilnehmerin';
    $PHORUM['DATA']['LANG']['ModeratedForum']              = 'Dies ist ein moderiertes Forum. Dein Beitrag bleibt solange unsichtbar, bis er von einer Moderatorin genehmigt wurde.';
    $PHORUM['DATA']['LANG']['NewModeratedMessage']         = "Forum: %forumname%\nBetreff: %subject%\nAutorin: %author%\nLink zum Beitrag: %read_url%\n\n%plain_body%\n\n--\nBeitrag freigeben: %approve_url%\n";
    $PHORUM['DATA']['LANG']['NewReplyMessage']             = "Forum: %forumname%\nBetreff: %subject%\nAutorin: %author%\nLink zum Beitrag: %read_url%\n\n%plain_body%\n\n--\nAbo für dieses Thema aufheben: %remove_url%\nKeine weitere E-Mail, aber das Thema weiter beobachten: %noemail_url%\nAbonnierte Themen anzeigen: %followed_threads_url%\n";
    $PHORUM['DATA']['LANG']['NewUnModeratedMessage']       = "Forum: %forumname%\nBetreff: %subject%\nAutorin: %author%\nLink zum Beitrag: %read_url%\n\n%plain_body%\n";
    $PHORUM['DATA']['LANG']['NoUnapprovedUsers']           = 'Zur Zeit keine neuen Teilnehmerinnen vorhanden';
    $PHORUM['DATA']['LANG']['PermAdministrator']           = 'Du bist Administratorin.';
    $PHORUM['DATA']['LANG']['PermModerator']               = 'Moderatorin';
    $PHORUM['DATA']['LANG']['PleaseLoginPost']             = 'In diesem Forum dürfen leider nur registrierte Teilnehmerinnen schreiben.';
    $PHORUM['DATA']['LANG']['PleaseLoginRead']             = 'Entschuldige, nur registrierte Teilnehmerinnen düfen dieses Forum lesen.';
    $PHORUM['DATA']['LANG']['PMNoRecipients']              = 'Du hast keine Empfängerinnen für Deine Nachricht ausgewählt';
    $PHORUM['DATA']['LANG']['PMNotifyMessage']             = 'Privatnachricht bei '.$PHORUM['title']."\n\nBetreff: %subject%\nAutorin: %author%\nLink zur Nachricht: %read_url%\n\n%plain_body%\n";
    $PHORUM['DATA']['LANG']['PMSelectARecipient']          = 'Empfängerin auswählen&nbsp;...';
    $PHORUM['DATA']['LANG']['PMToMailboxFull']             = 'Die Nachricht konnte nicht verschickt werden.<br />Das Postfach der Teilnehmerin &quot;%recipient%&quot; ist voll.';
    $PHORUM['DATA']['LANG']['ReceiveModerationMails']      = 'Ich möchte Benachrichtigungen für Moderatorinnen empfangen.';
    $PHORUM['DATA']['LANG']['Recipients']                  = 'Empfängerinnen';
    $PHORUM['DATA']['LANG']['RegVerifyMod']                = 'Danke für Deine Registrierung. Die Zustimmung einer Moderatorin steht noch aus. Du erhältst eine E-Mail, sobald man über Deine Zugehörigkeit entschieden hat.';
    $PHORUM['DATA']['LANG']['ReportPostEmailBody']         = '"%reportedby% hat aus folgendem Grund einen Beitrag gemeldet:\n%explanation%\n\nBeitrag : %url%\n\nForum:    %forumname%\nBetreff:  %subject%\nAutorin:  %author%\nIP:       %ip%\nDatum:    %date%\n\n%body%\n\n\nBeitrag löschen:\n%delete_url%\n\nBeitrag verbergen:\n%hide_url%\n\nBeitrag bearbeiten:\n%edit_url%\n\nProfil von %reportedby% anzeigen:\n%reporter_url%"';
    $PHORUM['DATA']['LANG']['ReportPostExplanation']       = 'Gib bitte eine Erläuterung, warum Du diesen Beitrag melden möchtest. Dies erleichtert es den Moderatorinnen, Deine Meldung zu verstehen.';
    $PHORUM['DATA']['LANG']['ReportPostSuccess']           = 'Dieser Beitrag wurde den Moderatorinnen des Forums gemeldet.';
    $PHORUM['DATA']['LANG']['SearchAllPostsHelp']          = 'Liste der Beiträge der Teilnehmerin "%user%".';
    $PHORUM['DATA']['LANG']['SearchAuthors']               = 'Autorinnen durchsuchen';
    $PHORUM['DATA']['LANG']['TotalRecipients']             = 'Anzahl Empfängerinnen';
    $PHORUM['DATA']['LANG']['UnapprovedGroupMembers']      = 'Es gibt Gruppenteilnehmerinnen, die noch nicht akzeptiert wurden';
    $PHORUM['DATA']['LANG']['UnapprovedUsers']             = '(Noch) nicht akzeptierte Teilnehmerinnen';
    $PHORUM['DATA']['LANG']['UnapprovedUsersLong']         = 'Es gibt Teilnehmerinnen, die noch nicht akzeptiert wurden';
    $PHORUM['DATA']['LANG']['UnknownUser']                 = 'Diese Teilnehmerin existiert nicht oder wurde deaktiviert.';
    $PHORUM['DATA']['LANG']['UserAddedToGroup']            = 'Teilnehmerin wurde der Gruppe hinzugefügt.';
    $PHORUM['DATA']['LANG']['Username']                    = 'Name der Teilnehmerin';
    $PHORUM['DATA']['LANG']['UserNotFound']                = 'Die Teilnehmerin, an die Deine Nachricht adressiert war, konnte nicht gefunden werden. Bitte überprüfe den Namen und versuchen es noch einmal.';
    $PHORUM['DATA']['LANG']['UserPermissions']             = 'Rechte der Teilnehmerin';
    $PHORUM['DATA']['LANG']['UserProfile']                 = 'Profil der Teilnehmerin';
    // ForumStats Module
    $PHORUM['DATA']['LANG']['Users']                       = 'Teilnehmerinnen';
?>