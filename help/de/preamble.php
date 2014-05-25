<?php

/**
 * help/de/preamble.php
 *
 * The preamble to OpenHomeopath in German.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   Preamble
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = "Präambel :: Hilfe :: OpenHomeopath";
$meta_description = "Handbuch für OpenHomeopath, Präambel";
include("help/layout/$skin/header.php");
?>
<h1>
  Hilfe zu OpenHomeopath
</h1>
<h2>
  Präambel
</h2>
  <p>Die wichtigsten Funktionen von OpenHomeopath sind <strong>übersichtlich in Tabs</strong> organisiert.</p>
  <p><strong>OpenHomeopath</strong> dient in erster Linie dem Finden des passenden homöopathischen Mittels anhand einer Auswahl von Symptomen. Diesen Vorgang nennt man <a href="<?php echo($rep_url);?>"><strong>Repertorisierung</strong></a>.<br>
  OpenHomeopath enthält zur Zeit das Repertorium von Kent und das Akutrepertorium von BZ-Homöopathie auf deutsch sowie die Repertorien von Kent, Bogner, Boenninghausen und das Repertorium publicum von Vladimir Polony auf englisch.<br>
  <strong>Die Reportorisierungsergebnisse kannst du einer Patientenkennung zuordnen und zusammen mit Verordnung, Bemerkung und Datum abspeichern.</strong> Dazu musst du allerdings <a href="../../register.php">registriert</a> und <a href="../../login.php">angemeldet</a> sein. Die gespeicherten Ergebnisse können später im Benutzerbereich abgerufen und auf ihrer Grundlage weiterrepertorisiert werden. Einzelne Ergebnisse können anderen Benutzern zur Verfügung gestellt werden.<br>
  <strong>Die Repertorisierungsergebnisse können als PDF herunterladen oder angezeigt werden.</strong> Die PDF-Datei enthält die 20 wichtigsten Mittel und ist zum <strong>Ausdruck</strong> im A4-Querformat vorbereitet.</p>
  <p>Weiterhin lassen sich in der <a href="<?php echo($materia_url);?>"><strong>Materia Medica</strong></a> zu einem gegebenen Mittel alle ihm entsprechenden Symptome mit ihren Wertigkeiten anzeigen. Hier können auch genauere Angaben zum Mittel wie verwandte Mittel, unverträgliche Mittel, Antidote, Herstellung/Herkunft/Synonyme, allgemeine Beschreibung des Mittels und Leitsymptome vermerkt werden.</p>
  <p>Das Repertorium und die Materia Medica kann der angemeldete Benutzer im <strong>Benutzerbereich</strong> seinen eigenen Bedürfnissen anpassen.</p>
  <p>Die Datenbank die Mittel, Symptome und Symptom-Mittel-Beziehungen enthält, ist durch die Benutzer <a href="datadmin.php">jederzeit erweiterbar.</a><br>
  Um Rubriken und ihre Mittel aus gedruckten Repertorien schnell in die Datenbank übertragen zu können, gibt es bei OpenHomeopath das <a href='expresstool.php'><strong>Expresstool</strong></a> mit einem einfachen Eingabeschema.</p>
  <p><a href="../../doc/<?php echo $lang; ?>/info.php"><strong>OpenHomeopath ist Opensource</strong></a> und unter den Bedingungen der <a href="../../doc/en/agpl3.php">AGPLv3 </a>lizensiert.</p>
  <p>Das <strong>Finanzierungskonzept von OpenHomeopath</strong> beruht darauf, ein <strong>monatliches Spendenziel von <?php echo(DONATION_GOAL_MONTHLY);?> €/$</strong> durch gemeinsame Anstrengung aller Nutzer zu erreichen. Solange das monatliche Spendenziel nicht erreicht ist, sind die Funktionen von OpenHomeopath für Nicht-Spender deutlich eingeschränkt. Sobald das monatliche Spendenziel erreicht wird, <strong>ist OpenHomeopath wieder für alle bis zum 10. bzw. sobald die Hälfte des Spendenziels erreicht wird bis zum 20. des nächsten Monats voll nutzbar</strong>.</p>
  <p>Ich hoffe das Programm gefällt euch und hilft zu heilen.</p>
  Für <a href="mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath">Anregungen und Verbesserungen</a> bin ich jederzeit offen.
<?php
include("help/layout/$skin/footer.php");
?>
