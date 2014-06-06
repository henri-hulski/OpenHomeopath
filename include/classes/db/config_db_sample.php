<?php
/**
 * config.db.php contains the database configuration of OpenHomeopath.
 * Please edit this file following the hints.
 *
 * config.db.php enthält die Datenbankkonfiguration für OpenHomeopath.
 * Bitte editiere die Datei gemäß der untenstehenden Anweisungen.
 */

// The server locale for English. It must support utf-8.
// Die 'locale' Variable deines Servers für englisch. Sie muß utf-8 unterstützen.
    define("LOCALE_EN",   "en_US.utf8");
// The server locale for Germen. It must support utf-8.
// Die 'locale' Variable deines Servers für deutsch. Sie muß utf-8 unterstützen.
    define("LOCALE_DE",   "de_DE.utf8");

// Database connection:
// Datenbankverbindung:

// Database driver - preferred mysqli, or mysql
// Datenbanktyp
    define("DB_TYPE",   "mysqli");

// Database name
// Datenbankname
    define("DB_NAME",   "OpenHomeopath");

// MySQL-Server-Host
    define("DB_SERVER", "localhost");

// Database user
// Datenbankbenutzer
    define("DB_USER",   "OpenHomeopath");

// Password
// Datenbankbenutzerpasswort
    define("DB_PASS",   "homeo");

// true | false
// true, if the user should receive a password per email
// on registration for verifying the email-address.
// false, if the user can choose the password by himself
// on the registration form.
// Wenn dem Benutzer bei der Registrierung
// zur Verifizierung der email das Passwort
// per email zugeschickt werden soll: true.
// Wenn der Benutzer das Passwort im
// Registrierungsformular selbst angeben 
// kann: false.
    define("REGISTER_VERIFY_EMAIL", true);

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$side_uri = "http://$host$uri/";

// URL of OpenHomeopath
// If you leave $side_uri it will be computed.
// Die vollständige Webadresse (URL) von OpenHomeopath (z.B. http://www.meineseite.de/pfad/openhomeopath/).
// Mit $side_uri wird sie automatisch ermittelt.
    define("URL",    $side_uri);

// Upload directory for imports.
// Hochlade Verzeichnis für Importe.
    define("UPLOAD_DIR",    "/tmp/");

// 2 email-addresses for notification of database changes
// hie kannst du zwei e-Mail Adressen angeben, an die Benachichtigungen 
// über Einfügungen und Änderungen der Datenbank geschickt werden
    define("EMAIL_NOTICE_1",   "henri.hulski@gazeta.pl");
    define("EMAIL_NOTICE_2",   "root@localhost");

/*
  Beschreibung der einzelnen Konfigurationskonstanten
  ---------------------------------------------------

* DB_TYPE:

  Der Datenbanktyp. Standard ist  "mysqli".
  Wenn dein Server es nicht unterstützt kannst du auch "mysql" verwenden.

* DB_NAME:

  Der Name der Datenbank.

* DB_SERVER:

  Der Hostname oder die IP-Adresse Ihre Datenbankservers. Normalerweise "localhost".
  ändere dies nur, wenn der Datenbankserver auf einem anderen System liegt wie der Webserver.

* DB_USER:

  Der Benutzername für den Datenbankserver.
  Der Benutzer muss alle Rechte für die Datenbank besitzen, um die benötigten Tabellen 
  erstellen und pflegen zu können.

* DB_PASS:

  Das Passwort des Datenbank-Benutzers.

* URL:

  Die Webadresse inklusiv des openhomeopath-Ordners und dem Schluss-"/".
  Den Ordner openhomeopath bitte nicht umbenennen oder die Variable "$site_path"
  in der Datei "include/datadmin/config.php" entsprechend anpassen.

*/
