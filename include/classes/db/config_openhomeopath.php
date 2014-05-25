<?php
/*
config_openhomeopath.php contains the configuration for OpenHomeopath.
Please edit the file following the hints.

config_openhomeopath.php enthält die Programmkonfiguration für OpenHomeopath.
Bitte editiere die Datei gemäß der untenstehenden Anweisungen.
*/

// default language: de | en
// Sprache-Voreinstellung: de | en
	define("DEFAULT_LANGUAGE", "de");
// default skin: original | kraque
// Skin-Voreinstellung: original | kraque
	define("DEFAULT_SKIN", "original");

// Symptom-Tabelle und Symptome-Mittel-Tabelle, die benutzt wird, wenn Nutzer nicht eingeloggt bzw. kein Spender und Spendenziel nicht erreicht

	// German userinterface
	// deutsche Benutzeroberfläche
	define("DEFAULT_SYMPTOM_TABLE_DE",   'sym__1');
	define("DEFAULT_SYMPTOM_REMEDY_TABLE_DE",   'sym_rem__1');

	// English userinterface
	// englische Benutzeroberfläche
	define("DEFAULT_SYMPTOM_TABLE_EN",   'sym__2_en');
	define("DEFAULT_SYMPTOM_REMEDY_TABLE_EN",   'sym_rem__2');

// expected monthly donatation goal in €/$
// angepeilte monatliche Spendenerwartung in Euro bzw. US-Dollar
	define("DONATION_GOAL_MONTHLY",   200);
?>
