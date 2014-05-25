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
	"insert"    => "Wstaw nowy rekord",
	"search/update/delete" => "Szukaj/zmien/usun rekordy",
	"insert_short"    => "Wstaw",
	"search_short" => "Szukaj",
	"new_mailing" => "Nowa poczta",
	"check_existing_mailing" => "Sprawdz utworzona poczte",
	"send_mailing" => "Wyslij utworzona poczte",
	"insert_anyway"    => "Wstaw mimo wszystko",
	"search"    => "Szukaj rekordu",
	"update"    => "Zapisz",
	"ext_update"    => "Zmien swoj profil",
	"yes"    => "Tak",
	"no"    => "Nie",
	"go_back" => "Wroc",
	"edit" => "Popraw",
	"delete" => "Usun",
	"details" => "Szczegoly",
	"remove_from_mailing" => "Usun z poczty",
	"remove_all_from_mailing" => "Usun wszystko z poczty",
	"send" => "Wyslij",
	"add_to_mailing" => "Dodaj do poczty",
	"print_labels" => "Drukuj etykiety",
	"create_this_mailing" => "                 Utworz poczty                 ",
	"change_table" => "Zmien tablice"
);

// normal messages
$normal_messages_ar = array (
	"insert_item" => "Insert item", // to change
	"show_all_records" => "Pokaz wszystkie rekordy",
	"logout" => "Wyloguj",
	"top" => "Top", // to change
	"last_search_results" => "Last search results", // to change
	"show_all" => "Pokaz wszystko",
	"home" => "Strona domowa",
	"select_operator" => "Wybierz operator:",
	"all_conditions_required" => "Wszystkie warunki spelnione",
	"any_conditions_required" => "Dowolny warunek spelniony",
	"all_contacts" => "Wszystkie kontakty",
	"removed" => "usuniety",
	"please" => "Prosze",
	"and_check_form" => "i sprawdz formularz.",
	"and_try_again" => "i sprobuj ponownie.",
	"none" => "zaden",
	"are_you_sure" => "Jestes pewien?",
	"delete_all" => "Usun wszystko",
	"really?" => "Naprawde?",
	"delete_are_you_sure" => "Zamierzasz skasowac ponizszy rekord, jestes pewien?",
	"required_fields_missed" => "Nie wypelniles niektorych wymaganych pol.",
	"alphabetic_not_valid" => "Wpisales cyfry w polu literowym.",
	"numeric_not_valid" => "Wpisales niedozwolone znaki w polu cyfrowym.",
	"email_not_valid" => "Wpisany adres e-mail jest nieprawidlowy.",
	"url_not_valid" => "Wpisany URL jest nieprawidlowy.",
	"phone_not_valid" => "Wpisany numer telefonu jest nieprawidlowy.<br>Prosze uzyc formatu \"+(kod kraju)(kierunkowy)(numer)\" np +48711234567.",
	"date_not_valid" => "Wpisales jedna lub wiecej nieprawidlowych dat.",
	"similar_records" => "Rekordy ponizej wydaja sie podobne do tego, ktory wpisales.<br>Co chcesz zrobic?",
	"no_records_found" => "Nie znaleziono rekodow.",
	"records_found" => "rekordow znaleziono",
	"number_records" => "Ilosc rekordow: ",
	"details_of_record" => "Szczegoly rekordu",
	"edit_record" => "Popraw rekord",
	"back_to_the_main_table" => "Back to the main table", // to change
	"previous" => "Previous", // to change
	"next" => "Next", // to change
	"edit_profile" => "Popraw swoj profil",
	"i_think_that" => "Mysle ze ",
	"is_similar_to" => " jest podobny do ",
	"page" => "Strona ",
	"of" => " z ",
	"records_per_page" => "records per page", // to change
	"day" => "Dzien",
	"month" => "Miesiac",
	"year" => "Rok",
	"administration" => "Administracja",
	"create_update_internal_table" => "Utworz lub zmien tablice wewnetrzna",
	"other...." => "inny....",
	"insert_record" => "Wstaw nowy rekord",
	"search_records" => "Szukaj rekordow",
	"exactly" => "dokladnie",
	"like" => "podobne do",
	"required_fields_red" => "Pola wymagane sa na czerwono.",
	"insert_result" => "Wynik wstawiania:",
	"record_inserted" => "Rekord wstawiony poprawnie.",
	"update_result" => "Wynik poprawiania:",
	"record_updated" => "Rekord zmieniony poprawnie.",
	"profile_updated" => "Zmiana profilu zakonczona poprawnie.",
	"delete_result" => "Wynik kasowania:",
	"record_deleted" => "Rekord skasowany poprawnie.",
	"duplication_possible" => "Powtarzanie mozliwe",
	"mail_to_records" => "wyslij do wszystkich znalezionych rekordow",
	"number_mails" => "Liczba wiadomosci, ktore zamierzasz wyslac: ",
	"specify_subject_body" => "Prosze okreslic temat i tresc wiadomosci.",
	"create_new_mailing" => "Utworz nowa poczte",
	"mailing_name" => "Nazwa poczty: ",
	"mailing_type" => "Typ poczty: ",
	"specify_mailing_name" => "(okresl nazwe dla tej poczty, np. workshop_2002, nie uzywaj cudzyslowow!!!!!!)",
	"email_specific_fields" => "Pola na niebiesko sa zwiazane z poczta, zignoruj, jezeli wybrales zwykla poczte",
	"from" => "Od: ",
	"subject" => "Temat: ",
	"body" => "Tresc: ",
	"dear" => "Drogi",
	"john_smith" => "p. Jan Kowalski",
	"start_sending" => "Wysyla......",
	"mailing_sent" => "......Wyslane",
	"mail_to" => "Poczta do",
	"sent" => "wyslana",
	"send" => "Wyslij",
	"e-mail" => "e-mail",
	"normal_mail" => "zwykla poczta",
	"attachment" => "Zalacznik: ",
	"additional_notes_mailing" => "Uwagi dodatkowe<br>(np. powod wyslania)",
	"please_specify_mailing_name" => "Prosze okreslic nazwe dla poczty.",
	"please_specify_mailing_type" => "Prosze okreslic typ poczty.",
	"please_specify_mailing_subject" => "Prosze okreslic temat poczty.",
	"please_specify_mailing_body" => "Prosze okreslic tresc poczty.",
	"mailing_name_already_used" => "Wybrana nazwa jest juz uzywana, prosze wybrac inna.",
	"filename_already_used" => "Wybrany zalacznik juz jest uzywany, prosze wybrac inny.",
	"mailing" => "Poczta",
	"created" => "utworzona",
	"all_records_found" => "wszystkie rekordy znaleziono",
	"add_contacts_to" => "Dodaj kontakty do",
	"check_mailing" => "Sprawdz poczte",
	"the_mailing" => "Poczta",
	"mailing_already_sent" => "Ta poczta zostala juz wyslana!!",
	"was_composed_by" => "zostala utworzona przez",
	"contacts" => "kontakty",
	"you_have_added" => "Dodales",
	"of_which_duplicated" => "z nich jest powtorzonych",
	"of_which_with_no_info" => "z nich nie ma wystarczajacych informacji",
	"is_composed_by" => "jest w tej chwili tworzona przez",
	"go_back_to_home_send_or_add" => "Mozesz wrocic do strony domowej i wyslac poczte, lub wyszukac i dodac kontakty do tej poczty.",
	"fields_max_length" => "Wpisales zbyt duzo informacji w jedno lub wiecej pol.",
	"you_are_going_unsubscribe" => "za chwile bedziesz usuniety z listy. Kontynuowac?",
	"you_have_unsubscribed" => "zostales usuniety z naszej listy.",
	"change_profile_url" => "Aby zmienic swoj profil przejdz do nastepujacej strony: ",
	"removed_mailing_list_url" => "Aby zrezygnowac z listy odwiedz ta strone: ",
	"city" => "Miejscowosc",
	"province" => "Wojewodztwo",
	"zip_code" => "Kod pocztowy",
	"prefix" => "Kierunkowy",
	"no_city_found" => "Nie znaleziono miejscowosci.",
	"print_warning" => "Ustaw marginesy drukowania na (0,0,0,0) (gora, dol, lewo, prawo) w swojej przegladarce w celu uzyskania poprawnego wydruku etykiet.",
	"current_upload" => "Biezacy plik",
	"delete" => "Usun",
	"total_records" => "Wszystkich rekordow",
	"confirm_delete?" => "Potwierdz usuniece?",
	"is_equal" => "jest rowny",
	"contains" => "zawiera",
	"starts_with" => "zaczyna sie od",
	"ends_with" => "konczy sie na",
	"greater_than" => "jest wieksze niz",
	"less_then" => "jest mniejsze niz",
	"export_to_csv" => "Wyslij do CSV",
	"new_insert_executed" => "New insert executed", // to change
	"new_update_executed" => "New update executed", // to change
	"null" => "Null", // to change
	"is_null" => "is null", // to change
	"is_empty" => "is empty" // to change
	);

// error messages
$error_messages_ar = array (
	"int_db_empty" => "Blad, wewnetrzna baza jest pusta.",
	"get" => "Blad przy czytaniu zmiennych.",
	"no_unique_update_id" => "Link do aktualizacji jest nieprawidlowy.<br>Skontaktuj sie z webmasterem <a href=\"mailto:webm.mine@pc.unicatt.it\">webm.mine@pc.unicatt.it</a> aby uzyskac nowy link.<br>Przepraszamy za trudnosci.",
	"no_unique_unsubscribe_id" => "Twoj link do rezygnacji z listy jest nieprawidlowy.<br>Skontaktuj sie z webmasterem <a href=\"mailto:webm.mine@pc.unicatt.it\">webm.mine@pc.unicatt.it</a> aby uzyskac odpowiedzi na swoje pytania.<br>Przepraszamy za trudnosci.",
	"no_functions" => " Blad, nie wybrano fukncji.<br>Prosze wrocic do strony domowej.",
	"no_unique_key" => "Blad, w wybranej tablicy nie ma klucza glownego.",	
	"upload_error" => "Blad podczas ladowania pliku.",
	"no_authorization_update_delete" => "Nie masz uprawnien aby zmieniac/poprawiac rekord.",
	"no_authorization_view" => "Nie masz uprawnien, aby ogladac ten rekord.",
	"deleted_only_authorizated_records" => "Zostaly skasowane tylko te rekordy, do ktorych miales uprawnienie.",
	"record_from_which_you_come_no_longer_exists" => "The record from which you come no longer exists.", // to change
	"this_record_is_the_last_one" => "This record is the last one.", // to change
	"this_record_is_the_first_one" => "This record is the first one." // to change
	);



//login messages
$login_messages_ar = array(
	"username" => "Login",
	"password" => "Haslo",
	"please_authenticate" => "Musisz podac login i haslo aby przejsc dalej",
	"login" => "login", // to change
	"username_password_are_required" => "Username and password are required", // to change
	"pwd_gen_link" => "create password", // to change
	"incorrect_login" => "Login lub haslo nieprawidlowe",
	"pwd_explain_text" =>"Insert a word to be used as password and press <b>Crypt it!</b>.<br> Press <b>Register</b> to write it in the below form", // to change
	"pwd_suggest_email_sending"=>"You may want to send yourself a mail to remeber the password", // to change
	"pwd_send_link_text" =>"send mail!", // to change
	"pwd_encrypt_button_text" => "Cript it!", // to change
	"pwd_register_button_text" => "Register password and exit" // to change
)
?>