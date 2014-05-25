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

    include(dirname(__FILE__).'/german.php');

    $language = 'Deutsch (Du) männlich';

    $PHORUM['DATA']['LANG']['AdminOnlyMessage']          = 'Das Forum ist zurzeit nicht verfügbar. Bitte versuche es später noch einmal.';
    $PHORUM['DATA']['LANG']['AreYouSure']                = 'Bist Du sicher?';
    $PHORUM['DATA']['LANG']['AttachCancel']              = 'Dein Beitrag wurde abgebrochen.';
    $PHORUM['DATA']['LANG']['AttachFull']                = 'Du hast die maximal erlaubte Anzahl an Dateianhängen erreicht.';
    $PHORUM['DATA']['LANG']['AttachInfo']                = 'Dein Beitrag wird jetzt auf dem Server gespeichert. Du hast die Möglichkeit ihn erneut zu bearbeiten bevor er im Forum erscheint.';
    $PHORUM['DATA']['LANG']['AttachInstructions']        = 'Wenn Du die Dateien angefügt hast, klicke auf "Beitrag"';
    $PHORUM['DATA']['LANG']['AttachmentsMissing']        = 'Das Anhängen der Dateien ist fehlgeschlagen, versuche es bitte erneut.';
    $PHORUM['DATA']['LANG']['AttachNotAllowed']          = 'Es ist Dir leider nicht erlaubt Dateien anzuhängen.';
    $PHORUM['DATA']['LANG']['BookmarkedThread']          = 'Du verfolgst dieses Thema in Deinem Kontrollcenter';
    $PHORUM['DATA']['LANG']['Buddies']                   = 'Liste der Kumpel';
    $PHORUM['DATA']['LANG']['Buddy']                     = 'Kumpel';
    $PHORUM['DATA']['LANG']['BuddyAdd']                  = 'Teilnehmer meiner Liste der Kumpel hinzufügen';
    $PHORUM['DATA']['LANG']['BuddyAddFail']              = 'Der Teilnehmer konnte nicht Deiner Liste der Kumpel hinzugefügt werden.';
    $PHORUM['DATA']['LANG']['BuddyAddSuccess']           = 'Der Teilnehmer wurde Deiner Liste der Kumpel hinzugefügt.';
    $PHORUM['DATA']['LANG']['BuddyListIsEmpty']          = 'Deine Liste der Kumpel ist leer.<br />Um Teilnehmer hinzuzufügen, öffne dessen Profil und klicke auf &quot;Teilnehmer meiner Liste der Kumpel hinzufügen&quot;.';
    $PHORUM['DATA']['LANG']['CancelConfirm']             = 'Bist Du sicher, dass Du abbrechen willst?';
    $PHORUM['DATA']['LANG']['ClickHereToLogin']          = 'Klicke hier, um Dich einzuloggen';
    $PHORUM['DATA']['LANG']['ConfirmDeleteMessage']      = 'Willst Du diesen Beitrag wirklich löschen?';
    $PHORUM['DATA']['LANG']['ConfirmDeleteThread']       = 'Willst Du dieses Thema wirklich löschen?';
    $PHORUM['DATA']['LANG']['ConfirmReportMessage']      = 'Willst Du diesen Beitrag wirklich melden?';
    $PHORUM['DATA']['LANG']['EditPostForbidden']         = 'Du hast leider keine Berechtigung, diesen Beitrag zu bearbeiten. Vielleicht liegt dies aber auch daran, dass der Administrator ein Zeitlimit für das Bearbeiten der Beiträge gesetzt hat.';
    $PHORUM['DATA']['LANG']['EmailVerifyBody']           = "Hallo %uname%,\n\nDiese E-Mail erhältst, weil Du im Profil eine Änderung Deiner E-Mail-Adresse angegeben hast. Um zu bestätigen, dass diese Adresse gültig ist, enthält diese Nachricht einen Bestätigungscode. Falls Du nicht %uname% bist, ignoriere diese Nachricht.\n\nDie neue E-Mail-Adresse ist: %newmail%\nDer Überprüfungs-Code lautet: %mailcode%\n\nGebe diesen Code zur Bestätigung der Änderung in Deinem Profil ein:\n\n<%cc_url%>\n\nDanke, ".$PHORUM['title'];
    $PHORUM['DATA']['LANG']['EmailVerifyEnterCode']      = 'Bitte E-Mail-Überprüfungs-Code eingeben, der Dir zugeschickt wurde';
    $PHORUM['DATA']['LANG']['EmailVerifySubject']        = 'Überprüfung Deiner neuen E-Mail-Adresse';
    $PHORUM['DATA']['LANG']['ErrBannedContent']          = 'Ein Wort, das Du in Deiner Nachricht verwendest, wurde von uns gesperrt. Verwende bitte ein anderes Wort oder setze Dich mit den Administratoren des Forums in Verbindung.';
    $PHORUM['DATA']['LANG']['ErrBannedEmail']            = 'Deine E-Mail-Adresse wurde von der Benutzung ausgeschlossen. Bitte benutze eine andere E-Mail-Adresse oder setze Dich mit den Administratoren des Forums in Verbindung.';
    $PHORUM['DATA']['LANG']['ErrBannedIP']               = 'Deine IP-Adresse, Domain oder Dein Internetprovider wurde gesperrt. Bitte setze Dich mit den Administratoren des Forums in Verbindung';
    $PHORUM['DATA']['LANG']['ErrBannedName']             = 'Dein Name oder Teilnehmername wurde von der Verwendung ausgeschlossen. Bitte wähle einen anderen Namen oder setze Dich mit den Administratoren des Forums in Verbindung.';
    $PHORUM['DATA']['LANG']['ErrEmail']                  = 'Die E-Mail-Adresse scheint nicht gültig zu sein. Bitte überprüfe das noch einmal.';
    $PHORUM['DATA']['LANG']['ErrPassword']               = 'Entweder ist das Passwort falsch oder es wurde gar nicht eingegeben. Versuche es bitte nochmal.';
    $PHORUM['DATA']['LANG']['ErrRegisterdEmail']         = 'Die von Dir angegebene E-Mail-Adresse wird bereits von einem anderen Teilnehmer verwendet. Wenn Du dieser Teilnehmer bist, logge Dich bitte ein. Ansonsten nutze bitte eine andere E-Mail-Adresse.';
    $PHORUM['DATA']['LANG']['ErrRegisterdName']          = 'Dieser Name wird bereits von einem anderen Teilnehmer verwendet. Wenn Du derjenige bist, logge Dich bitte ein. Ansonsten nutze bitte einen anderen Namen.';
    $PHORUM['DATA']['LANG']['ErrWrongMailcode']          = 'Du hast einen falschen Code eingegeben. Bitte versuche es noch einmal.';
    $PHORUM['DATA']['LANG']['FileOverQuota']             = 'Die Datei konnte nicht hochgeladen werden. Die Größe der Datei würde dazu führen, dass Dein Speicherlimit überschritten würde. Es stehen Dir '.$PHORUM['file_space_quota'].'&nbsp;kB auf dem Server zur Verfügung.';
    $PHORUM['DATA']['LANG']['FileQuotaLimits']           = 'Insgesamt darfst Du auf dem Server ablegen: ';
    $PHORUM['DATA']['LANG']['FileSizeLimits']            = 'Bitte versuche nicht Dateien hochzuladen, die größer sind als';
    $PHORUM['DATA']['LANG']['FileTooLarge']              = 'Die Datei, die Du hochladen willst, überschreitet die erlaubte Größe. Bitte versuche nicht, Dateien hochzuladen, die größer als '.$PHORUM['max_file_size'].'&nbsp;kB sind.';
    $PHORUM['DATA']['LANG']['FileWrongType']             = 'Dateien dieses Typs sind hier nicht für den Upload freigegeben. Folgende Dateitypen kannst Du hochladen: '.str_replace(';', ', ', $PHORUM['file_types']).'.';
    $PHORUM['DATA']['LANG']['FollowExplanation']         = 'Die folgenden Themen sind in Deinem Kontrollcenter gelistet.<br />Wenn Du die E-Mail-Benachrichtigung auswählst, wirst Du informiert, sobald es neue Beiträge zu diesen Themen gibt.';
    $PHORUM['DATA']['LANG']['FollowWithEmail']           = 'Willst Du per E-Mail benachrichtigt werden, wenn zu diesem Thema ein neuer Beitrag geschrieben wird?';
    $PHORUM['DATA']['LANG']['GroupJoinFail']             = 'Du konntest nicht als Teilnehmer hinzugefügt werden.';
    $PHORUM['DATA']['LANG']['GroupJoinSuccess']          = 'Du wurdest erfolgreich Teilnehmer der Gruppe.';
    $PHORUM['DATA']['LANG']['GroupJoinSuccessModerated'] = 'Dein Gesuch wurde registriert. Weil es sich um eine moderierte Gruppe handelt, muss Deine Zugehörigkeit von einem Moderator bestätigt werden.';
    $PHORUM['DATA']['LANG']['HowToFollowThreads']        = 'Du kannst das Thema mit einem Klick auf &quot;Diese Diskussion verfolgen&quot; beim Lesen des Beitrags abonnieren. Wenn Du zusätzlich &quot;Antworten auf dieses Thema per E-Mail zusenden&quot; beim Verfassen eines Beitrags anklickst, wird der Beitrag der Liste der Diskussionen hinzugefügt, die Du verfolgst.';
    $PHORUM['DATA']['LANG']['InvalidLogin']              = 'Teilnehmername wurde nicht gefunden bzw. ist inaktiv. Versuche es bitte noch einmal.';
    $PHORUM['DATA']['LANG']['JoinGroupDescription']      = 'Um einer Gruppe beizutreten, wähle diese aus der Liste aus. Die mit einem * markierten Gruppen sind moderiert, d.h. Deine Zugehörigkeit muss von einem Moderator akzeptiert werden.';
    $PHORUM['DATA']['LANG']['LoginTitle']                = 'Bitte gebe für die Anmeldung Deinen Teilnehmernamen und das Passwort ein.';
    $PHORUM['DATA']['LANG']['LostPassEmailBody1']        = 'Du hast ein neues Passwort für '.$PHORUM['title']." angefordert. Wenn Du selbst gar kein neues Passwort angefordert hast, kannst Du getrost diese E-Mail ignorieren und wie gewohnt Deine bisherigen Zugangsdaten verwenden.\n\nAnsonsten ist dies Dein neues Passwort:";
    $PHORUM['DATA']['LANG']['LostPassEmailBody2']        = 'Du kannst Dich nun bei '.$PHORUM['title'].' auf '.phorum_get_url(PHORUM_LOGIN_URL)." einloggen.\n\nDanke, ".$PHORUM['title'];
    $PHORUM['DATA']['LANG']['LostPassEmailSubject']      = 'Deine Zugangsdaten für '.$PHORUM['title'];
    $PHORUM['DATA']['LANG']['LostPassInfo']              = 'Wenn Du hier Deine E-Mail-Adresse eingibst, schicken wir Dir ein neues Passwort.';
    $PHORUM['DATA']['LANG']['LostPassSent']              = 'Ein neues Passwort wurde Dir per E-Mail zugeschickt.';
    $PHORUM['DATA']['LANG']['LostPassword']              = 'Hast Du Dein Passwort vergessen?';
    $PHORUM['DATA']['LANG']['MergeThreadAction2']        = 'Möchtest Du die Verschmelzung fortsetzen?';
    $PHORUM['DATA']['LANG']['MergeThreadInfo']           = 'Gehe jetzt zum Thema, das mit dem gewählten Thema verschmolzen werden soll und wähle erneut &quot;Thema verschmelzen&quot;.';
    $PHORUM['DATA']['LANG']['MergeThreadInfo1']          = 'Du möchtest das folgende Thema mit einem anderen Thema verschmelzen.';
    $PHORUM['DATA']['LANG']['MergeThreadInfo2']          = 'Gehe jetzt zum Thema, das mit dem gewählten Thema verschmolzen werden soll und wähle erneut &quot;Thema verschmelzen&quot;.';
    $PHORUM['DATA']['LANG']['ModeratedForum']            = 'Dies ist ein moderiertes Forum. Dein Beitrag bleibt solange unsichtbar, bis er von einem Moderator genehmigt wurde.';
    $PHORUM['DATA']['LANG']['MovedMessage']              = 'Dieses Thema wurde verschoben. Du wirst zur neuen Position weitergeleitet.';
    $PHORUM['DATA']['LANG']['MsgRedirect']               = 'Du wirst umgeleitet &ndash; klicke bitte hier, wenn dies nicht automatisch geschehen sollte.';
    $PHORUM['DATA']['LANG']['NewPrivateMessages']        = 'Du hast neue Privatnachrichten';
    $PHORUM['DATA']['LANG']['NoGroupMembership']         = 'Du gehörst zu keiner Gruppe.';
    $PHORUM['DATA']['LANG']['NoMoreEmails']              = 'Du erhältst keine weiteren E-Mails, wenn es neue Beiträge zum Thema gibt.';
    $PHORUM['DATA']['LANG']['NoPost']                    = 'Du hast nicht die erforderliche Berechtigung, um in diesem Forum zu schreiben.';
    $PHORUM['DATA']['LANG']['NoPrivateMessages']         = 'Du hast keine privaten Nachrichten.';
    $PHORUM['DATA']['LANG']['NoRead']                    = 'Du hast nicht die erforderliche Berechtigung, um dieses Forum einzusehen.';
    $PHORUM['DATA']['LANG']['NotRegistered']             = 'Nicht registriert? &ndash; Klicke hier, um Dich zu registrieren.';
    $PHORUM['DATA']['LANG']['PeriodicLogin']             = 'Zu Deiner Sicherheit ist es notwendig, Deine Anmelde-Informationen zu bestätigen, sobald Du diese Seite verlassen hast.';
    $PHORUM['DATA']['LANG']['PermAdministrator']         = 'Du bist Administrator.';
    $PHORUM['DATA']['LANG']['PleaseLoginRead']           = 'Entschuldige, nur registrierte Teilnehmer düfen dieses Forum lesen.';
    $PHORUM['DATA']['LANG']['PMFolderDeleteConfirm']     = 'Bist Du sicher, dass Du den Ordner und alle enthaltenen Nachrichten löschen willst?';
    $PHORUM['DATA']['LANG']['PMFolderDeleteExplain']     = '<b>Warnung:</b> Wenn Du einen Ordner löschst, werden auch alle enthaltenen Nachrichten gelöscht! Wenn diese einmal gelöscht sind, können sie nicht wieder hergestellt werden. Wenn Du die Nachrichten behalten willst, verschiebe diese bitte vorher in einen anderen Ordner.';
    $PHORUM['DATA']['LANG']['PMFromMailboxFull']         = 'Du kannst keine Kopie dieser Nachricht speichern.<br />Dein Postfach ist voll.';
    $PHORUM['DATA']['LANG']['PMNoRecipients']            = 'Du hast keine Empfänger für Deine Nachricht ausgewählt';
    $PHORUM['DATA']['LANG']['PMNotSent']                 = 'Deine private Nachricht wurde nicht verschickt. Es gab einen Fehler.';
    $PHORUM['DATA']['LANG']['PMSent']                    = 'Deine private Nachricht wurde erfolgreich verschickt';
    $PHORUM['DATA']['LANG']['PMSpaceFull']               = 'Dein Postfach für private Nachrichten ist voll.';
    $PHORUM['DATA']['LANG']['PMSpaceLeft']               = 'Du kannst noch %pm_space_left% weitere private Nachricht(en) speichern.';
    $PHORUM['DATA']['LANG']['PostErrorDuplicate']        = 'Es ist bereits ein exakt gleicher Beitrag im Forum enthalten. Duplikate sind nicht erlaubt; daher konnte Dein Beitrag nicht gespeichert werden.';
    $PHORUM['DATA']['LANG']['PreviewExplain']            = 'So wird Dein Beitrag im Forum aussehen.';
    $PHORUM['DATA']['LANG']['ReadOnlyMessage']           = 'In diesem Forum können zur Zeit keine Beiträge verfasst werden. Bitte versuche es später noch einmal.';
    $PHORUM['DATA']['LANG']['RegAcctActive']             = 'Du bist nun Teilnehmer dieser Gruppe.';
    $PHORUM['DATA']['LANG']['RegApprovedEmailBody']      = 'Du wurdest im Forum: '.$PHORUM['title'].' aufgenommen. Du kannst Dich in das Forum: '.$PHORUM['title'].' unter '.phorum_get_url(PHORUM_LOGIN_URL)." einloggen.\n\nDanke, ".$PHORUM['title'];
    $PHORUM['DATA']['LANG']['RegApprovedSubject']        = 'Du wurdest aufgenommen.';
    $PHORUM['DATA']['LANG']['RegVerifyEmail']            = 'Danke für die Registrierung. Du wirst in Kürze Details zur Aktivierung per E-Mail bekommen.';
    $PHORUM['DATA']['LANG']['RegVerifyFailed']           = 'Entschuldige, Deine Daten konnten nicht überprüft werden. Bitte stelle sicher, dass Du die komplette Web-Adresse verwendest, die Du per E-Mail erhalten hast.';
    $PHORUM['DATA']['LANG']['RegVerifyMod']              = 'Danke für Deine Registrierung. Die Zustimmung eines Moderators steht noch aus. Du erhältst eine E-Mail, sobald man über Deine Zugehörigkeit entschieden hat.';
    $PHORUM['DATA']['LANG']['RemoveFollowed']            = 'Du verfolgst diese Diskussion nicht.';
    $PHORUM['DATA']['LANG']['ReportPostExplanation']     = 'Gib bitte eine Erläuterung, warum Du diesen Beitrag melden möchtest. Dies erleichtert es den Moderatoren, Deine Meldung zu verstehen.';
    $PHORUM['DATA']['LANG']['ReportPostNotAllowed']      = 'Um einen Beitrag zu melden, mußt Du registriert sein.';
    $PHORUM['DATA']['LANG']['RequireCookies']            = 'Bedaure! Du musst in Deinem Browser Cookies erlauben, damit Du Dich in diesem Forum erfolgreich anmelden kannst.';
    $PHORUM['DATA']['LANG']['ScriptUsage']               = 'Syntax : php script.php [--module=<module_name> | --scheduled] [options]--module=<module_name>   Führt das entsprechende Modul aus. --scheduled   Führt alle Module aus, die keine Einträge brauchen.  [options] Während des Ausführens werden diese Optionen an das Modul weitergegeben. Bitte konsultiere die Dokumentation des entsprechenden Moduls. Mit --scheduled werden diese ignoriert.';
    $PHORUM['DATA']['LANG']['SearchRunning']             = 'Deine Suche läuft. Bitte ein wenig Geduld.';
    $PHORUM['DATA']['LANG']['ThreadAnnouncement']        = 'Du kannst auf Ankündigungen nicht antworten.';
    $PHORUM['DATA']['LANG']['UnsubscribeError']          = 'Dein Abo für dieses Thema konnte nicht aufgehoben werden.';
    $PHORUM['DATA']['LANG']['UnsubscribeOk']             = 'Dein Abo dieses Themas wurde aufgehoben.';
    $PHORUM['DATA']['LANG']['UploadNotAllowed']          = 'Du darfst leider keine Dateien auf diesen Server hochladen.';
    $PHORUM['DATA']['LANG']['UserNotFound']              = 'Der Teilnehmer, an den Deine Nachricht adressiert war, konnte nicht gefunden werden. Bitte überprüfe den Namen und versuchen es noch einmal.';
    $PHORUM['DATA']['LANG']['VerifyRegEmailBody1']       = 'Um Deine Zugehörigkeit bei '.$PHORUM['title'].' zu aktivieren, klicke bitte hier:';
    $PHORUM['DATA']['LANG']['VerifyRegEmailBody2']       = 'Nach der Aktivierung kannst Du Dich bei '.$PHORUM['title'].' auf '.phorum_get_url(PHORUM_LOGIN_URL)." einloggen.\n\nWichtiger Hinweis für Thunderbird-Benutzer unter Linux: Kopiere obigen Link bitte manuell in Deinen Brwoser. Thunderbird schneidet aufgrund eines Bugs einen Teil des Links ab und verhindert damit eine Aktivierung!\n\nDanke, ".$PHORUM['title'];
    $PHORUM['DATA']['LANG']['VerifyRegEmailSubject']     = 'Bitte überprüfe Deine Angaben';
    $PHORUM['DATA']['LANG']['YourEmail']                 = 'Deine E-Mail-Adresse';
    $PHORUM['DATA']['LANG']['YourName']                  = 'Dein vollständiger Name';
    $PHORUM['DATA']['LANG']['YouWantToFollow']           = 'Du hast angegeben, dass Du dieser Diskussion folgen möchtest.';
?>