<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) http://www.dadabik.org/
Copyright (C) 2001-2007  Eugenio Tacchini

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

If you want to contact me by e-mail, this is my address: eugenio.tacchini@unicatt.it
***********************************************************************************
*/
?>
<?php
// submit buttons
$submit_buttons_ar = array (
	"insert"    => "Neuen Satz einfügen",
	"search/update/delete" => "Sätze suchen/aktualisieren/löschen",
	"insert_short"    => "Einfügen",
	"search_short" => "Suchen",
	"new_mailing" => "Neues Mailing",
	"check_existing_mailing" => "Überprüfe vorhandes Mailing",
	"send_mailing" => "Vorhandenes Mailing versenden",
	"insert_anyway"    => "trotzdem einfügen",
	"search"    => "Suche nach Satz",
	"update"    => "Speichern",
	"ext_update"  => "Ihr Profil aktualisieren",
	"yes"    => "Ja",
	"no"    => "Nein",
	"go_back" => "zurück",
	"edit" => "Bearbeiten",
	"delete" => "Löschen",
	"details" => "Details",
	"remove_from_mailing" => "Aus Mailing entfernen",
	"remove_all_from_mailing" => "Alle aus Mailing entfernen",
	"send" => "Senden",
	"add_to_mailing" => "Zum Mailing hinzufügen",
	"print_labels" => "Label drucken",
	"create_this_mailing" => "                 Dieses Mailing erstellen             	  ",
	"change_table" => "Tabelle wechseln"
);

// normal messages
$normal_messages_ar = array (
	"insert_item" => "Satz einfügen", // to change
	"show_all_records" => "Alle Sätze zeigen",
	"logout" => "Abmelden",
	"top" => "Top",
	"last_search_results" => "Letzte Suchergebnisse",

	"show_all" => "Alles zeigen",
	"home" => "Startseite",
	"select_operator" => "Verknüpfung wählen:",
	"all_conditions_required" => "Alle Bedingungen müssen zutreffen",
	"any_conditions_required" => "Eine Bedingung muss zutreffen",
	"all_contacts" => "Alle Kontakte",
	"removed" => "entfernt",
	"please" => "Bitte",
	"and_check_form" => "Überprüfen Sie das Formular.",
	"and_try_again" => "und versuchen Sie es erneut.", 
	"none" => "keine",
	"are_you_sure" => "Sind Sie sicher?",
	"delete_all" => "Alle löschen", 
	"really?" => "Wirklich ?", 
	"delete_are_you_sure" => "Sie wollen den nachstehenden Satz löschen. Sind Sie sicher?",
	"required_fields_missed" => "Sie haben einige erforderliche Felder nicht ausgefüllt.<br>Bitte füllen Sie alle erforderlichen Felder aus.",
	"alphabetic_not_valid" => "Sie haben numerische Werte in ein Alfa-Feld eingegeben.",
	"numeric_not_valid" => "Sie haben nichtnumerische Zeichen in ein numerisches Feld eingetragen.",
	"email_not_valid" => "Die eingegebene/n E-Mail-Adresse/n ist/sind nicht gültig.",
	"url_not_valid" => "Die eingegebene/n URL/s ist/sind nicht gültig.",
	"phone_not_valid" => "Die eingegebene/n Telefon-Nummer/n ist/sind nicht gültig.",
	"date_not_valid" => "Sie haben ungültige Datumsangaben eingetragen.", 
	"similar_records" => "Die untenstehenden Sätze ähneln dem Satz, den Sie einfügen möchten.<br>Was möchten Sie tun?",
	"no_records_found" => "Keine Sätze gefunden.",
	"records_found" => "Sätze gefunden",
	"number_records" => "Anzahl Sätze: ",
	"details_of_record" => "Details des Satzes:",
	"edit_record" => "Satz bearbeiten",
	"back_to_the_main_table" => "Zurück zur Haupttabelle",
	"previous" => "Vorheriger",

		"next" => "Nächster",

	"edit_profile" => "Ihre Profil-Information aktualisieren",
	"i_think_that" => "Ich denke ",
	"is_similar_to" => " ähnelt folgendem: ",
	"page" => "Seite ",
	"of" => " von ",
	"records_per_page" => "Sätze pro Seite",

	"day" => "Tag",
	"month" => "Monat",
	"year" => "Jahr",
	"administration" => "Administration",
	"create_update_internal_table" => "Interne Tabelle erstellen/aktualisieren",
	"other...." => "andere....",
	"insert_record" => "Neuen Satz einfügen",
	"search_records" => "Suche nach Sätzen",
	"exactly" => "exakt",
	"like"    => "ähnlich",
	"required_fields_red" => "Erforderliche Felder sind rot markiert.",
	"insert_result" => "Einfüge-Ergebnis:",
	"record_inserted" => "Satz korrekt eingefügt.",
	"update_result" => "Aktualisierungs-Ergebnis:",
	"record_updated" => "Satz korrekt aktualisiert.",
	"profile_updated" => "Ihr Profil wurde korrekt aktualisiert.",
	"delete_result" => "Lösch-Ergebnis:",
	"record_deleted" => "Satz korrekt gelöscht.",
	"duplication_possible" => "Duplizierung möglich",
	"mail_to_records" => "E-Mail an alle gefundenen Sätze",
	"number_mails" => "Anzahl Nachrichten, die Sie verschicken wollen: ",
	"specify_subject_body" => "Bitte geben Sie Subjekt und Text der Nachricht an",
	"create_new_mailing" => "Neues Mailing erstellen",
	"mailing_name" => "Mailing-Name: ",
	"mailing_type" => "Mailing-Typ: ",
	"specify_mailing_name" => "(Geben Sie einen Namen für dieses Mailing an, z. B. Arbeitskreis_2002, bitte benutzen Sie keine Anführungszeichen!!!!!!)",
	"email_specific_fields" => "Blaue Felder beziehen sich auf E-Mail-Versand, ignorieren Sie diese bei normaler Post",
	"from" => "Von: ",
	"subject" => "Subjekt: ",
	"body" => "Text: ",
	"dear" => "Lieber",
	"john_smith" => "Hr. Otto Normalverbraucherin",
	"start_sending" => "Starte Senden......",
	"mailing_sent" => "......Mailing gesendet", 
	"mail_to" => "Mail an",
	"sent" => "gesendet",
	"send" => "Senden",
	"e-mail" => "E-Mail",
	"normal_mail" => "normale Post",
	"attachment" => "Anhang",
	"additional_notes_mailing" => "Zusätzliche Notizen<br>(z.B. Anlass für dieses Mailing)",
	"please_specify_mailing_name" => "Bitte geben Sie einen Namen für dieses Mailing an.",
	"please_specify_mailing_type" => "Bitte geben Sie einen Typ für dieses Mailing an.",
	"please_specify_mailing_subject" => "Bitte geben Sie einen Betreff für dieses Mailing an.",
	"please_specify_mailing_body" => "Bitte geben Sie einen Inhalt für dieses Mailing an.",
	"mailing_name_already_used" => "Der von Ihnen gewählte Name wird bereits benutzt, bitte ändern Sie ihn.",
	"filename_already_used" => "Der Dateiname des Anhangs ist bereits benutzt, bitte ändern Sie den Namen.",
	"mailing" => "Mailing",
	"created" => "erstellt",
	"all_records_found" => "Alle Sätze gefunden",
	"add_contacts_to" => "Kontakte hinzufügen",
	"check_mailing" => "Mailing überprüfen",
	"the_mailing" => "Das Mailing",
	"mailing_already_sent" => "Dieses Mailing wurde bereits versandt!!",
	"was_composed_by" => "wurde erstellt von",
	"contacts" => "Kontakte",
	"you_have_added" => "Hinzugefügt wurden",
	"of_which_duplicated" => "von denen Duplikate sind",
	"of_which_with_no_info" => "die nicht genug Informationen enthalten",
	"is_composed_by" => "ist nun erstellt von",
	"go_back_to_home_send_or_add" => "Sie können zur Startseite zurückgehen und das Mailing senden oder weitere Kontakte suchen und zu diesem Mailing hinzufügen.",
	"fields_max_length" => "Sie haben zuviel Text in ein oder mehrere Feld/er eingegeben.",
	"you_are_going_unsubscribe" => "Sie werden von unserer Mailingliste gelöscht. Weitermachen?",
	"you_have_unsubscribed" => "Sie wurden erfolgreich aus dieser Mailing-Liste ausgetragen.",
	"change_profile_url"  =>  "Zur Änderung Ihrer Profil-Information besuchen Sie bitte diese Seite",
	"removed_mailing_list_url" => "Um von der Mailingliste entfernt zu werden, besuchen Sie bitte diese Seite: ",
	"city" => "Stadt",
	"province" => "Land",
	"zip_code" => "Postleitzahl",
	"prefix" => "Präfix",
	"no_city_found" => "keine Stadt gefunden",
	"print_warning" => "Bitte setzen Sie den Druckbereich auf (0,0,0,0) (oben, unten, links, rechts) in Ihrem Browser, um die Label korrekt drucken zu könenn.",
	"current_upload" => "aktuelle Datei ",
	"delete" => "Löschen",
	"total_records" => "Gesamtanzahl Sätze",
	"confirm_delete?" => "Löschung bestätigen ?",
	"is_equal" => "entspricht",
	"contains" => "enthält",
	"starts_with" => "beginnt mit",
	"ends_with" => "endet mit",
	"greater_than" => ">",
	"less_then" => "<",
	"export_to_csv" => "Als CSV-Datei ausgeben",
	"new_insert_executed" => "Neu-Einfügung",
	"new_update_executed" => "Aktualisierung",
	"null" => "Null",
	"is_null" => "ist null",
	"is_empty" => "ist leer"

	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Fehler, die interne Datenbank ist leer.",
	"get" => "Fehler, beim Variablen-Abruf.",
	"no_unique_update_id" => "Ihr Aktualisierungs-Link ist abgelaufen.",
	"no_unique_unsubscribe_id" => "Ihr Austragungs-Link ist nicht korrekt.",
	"no_functions" => "Fehler, keine Funktionen gewählt.<br>Bitte gehen Sie zurück zur Startseite.",
	"no_unique_key" => "Fehler, Sie haben keinen Primärschlüssel in Ihrer Tabelle.",	
	"upload_error" => "Während der Datei-Übertragung trat ein Fehler auf." ,
	"no_authorization_update_delete" => "Sie haben keine Berechtigung zum Ändern/Löschen des Datensatzes.",
	"no_authorization_restore" => "Sie haben keine Berechtigung zum Wiederherstellung des Datensatzes.",
	"no_authorization_view" => "Sie haben keine Berechtigung zum Ansehen des Datensatzes.",
	"deleted_only_authorizated_records" => "Es wurden nur die Sätze gelöscht, für die Sie eine Berechtigung haben.",
	"record_from_which_you_come_no_longer_exists" => "Der zuletzt angezeigte Datensatz existiert nicht mehr.",

	"this_record_is_the_last_one" => "Dieser Datensatz ist der letzte.",

	"this_record_is_the_first_one" => "Dieser Datensatz ist der erste."

	);

//login messages
$login_messages_ar = array(
	"username" => "benutzername",
	"password" => "passwort",
	"please_authenticate" => "Zur Fortsetzung müssen Sie identifiziert sein",
	"login" => "Anmeldung",
	"username_password_are_required" => "Benutzername/Passwort sind erforderlich",
	"pwd_gen_link" => "passwort erstellen", 
	"incorrect_login" => "Benutzername/Passwort ist falsch",
	"pwd_explain_text" =>"Geben Sie ein Wort als Passwort ein und drücken Sie <b>Verschlüsseln!</b>.<br> Klicken Sie <b>Registrieren</b> um es in das Formular einzutragen", 
	"pwd_suggest_email_sending"=>"Sie können sich selbst eine Email mit dem Passwort als Erinnerung schicken", 
	"pwd_send_link_text" =>"Email senden!", 
	"pwd_encrypt_button_text" => "Verschlüsseln!", 
	"pwd_register_button_text" => "Passwort registrieren und beenden"
)
?>