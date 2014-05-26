<?php

/**
 * help/en/manual.php
 *
 * The main English manual for OpenHomeopath.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   Manual
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = "Manual :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Manual";
include("help/layout/$skin/header.php");
?>
<h1>
  OpenHomeopath Manual
</h1>
<h2>
  Manual
</h2>
<p>
  Es wird empfohlen <strong>OpenHomeopath</strong> bei einer <strong>Bildschirmauflösung</strong> von mindestens <strong>1280x1024</strong> zu benutzen. <strong>Javascript</strong> und <strong>Cookies</strong> zumindest von der gleichen Site müssen im Browser aktiviert sein.
  Bei den Auswahllisten kannst du mit <strong>Doppelklick auf einen Eintrag</strong> die jeweils wichtigste Aktion ausführen.
  Mit der Skin-Auswahl kannst du das Aussehen von OpenHomeopath verändern. Im Moment gibt es 2 Skins: <strong><em>"original"</em></strong> von Henri Schumacher und <strong><em>"kraque"</em></strong> von Thomas Bochmann ohne Seitenleiste, so das sie besonders für kleinere Displays geeignet ist.
  OpenHomeopath ist zur Zeit in deutsch und englisch übersetzt.
</p>
<a name="overview" id="overview"><br></a>
<h3>
  Übersicht
</h3>
<p>
  Die wichtigsten Funktionen von OpenHomeopath sind <strong>übersichtlich in Tabs</strong> organisiert.<br>
  Dabei kannst du innerhalb der einzelnen Tabs unabhängig voneinander arbeiten, ohne das der aktuelle Stand der anderen Tabs sich ändert.<br>
  Wenn du OpenHomeopath aufrufst siehst du 2 Tabs, <strong><em>Repertorisierung</em></strong> und <span class='nobr'><strong><em>Materia Medica</em></strong></span>. Sobald du dich anmeldest kommt noch der Tab <span class='nobr'><strong><em>Mein Bereich</em></strong></span> hinzu. Außerdem gibt es je nach Anforderung noch die Tabs <strong><em>Repertorisierungsergebnis</em></strong> und <strong><em>Symptom-Info</em></strong>.<br>
  In den Tabs <strong><em>Repertorisierungsergebnis</em></strong>, <span class='nobr'><strong><em>Materia Medica</em></strong></span> und <strong><em>Symptom-Info</em></strong> kannst du zu vorherigen Ansichten <strong>zurück und wieder vor springen</strong>.
</p>
<p>
  Auf der linken Seite findest du in allen Programmfenstern das <strong>Navigationsmenü</strong> (in der Original-Skin). Von dort hast du Zugang zu allen wichtigen Teilen des Programms.
</p>
<p>
  <strong>Es folgt eine Beschreibung der einzelnen Programmfunktionen:</strong>
</p>
<br>
<div class="content">
  <h2>
    Contents
  </h2>
  <ul>
    <li><a href="#repertorization">Repertorization</a></li>
    <li><a href="#rep_result">Repertorization result</a></li>
    <li><a href="#materia">Materia Medica</a></li>
    <li><a href="#symptominfo">Symptom information</a></li>
    <li><a href="#daten">Data maintenance</a></li>
    <li><a href="#hilfe">Help</a></li>
    <li><a href="#info">Info</a></li>
  </ul>
</div>
<a name="repertorization" id="repertorization"><br></a>
<h3>
  Repertorization
</h3>
<p>
  Dies ist der <strong>wichtigste Teil</strong> des Programms. Hier findet die eigentliche <strong>Repertorisierung</strong> statt.
</p>
<p>
  Wenn du eine neue Repertorisierung beginnst, siehst du links eine Auswahlmenü, wo die <strong>Hauptrubrik</strong> ausgewählt werden kann. Bei <strong><em>"Alle Rubriken"</em></strong> werden Symptome aus allen Rubriken angezeigt.<br>
  In der Mitte befindet sich die <strong>Suchfunktion</strong>. Hier kannst du nach einem oder mehreren Begriffen suchen. Hierbei kann entweder nach <strong>Wortteilen</strong> oder <strong>ganzen Wörtern</strong> gesucht werden. Bei mehreren Begriffen ist sowohl eine <strong>UND-</strong> als auch eine <strong>ODER-Suche</strong> möglich. Eine genaue Beschreibung der Suchfunktion findest du in der <a href="search.php"><strong>Hilfe zur Formulierung von Symptom-Suchanfragen</strong></a>.<br>
  Falls ein Symptom in <strong>verschiedenen Übersetzungen</strong> vorliegt wird die <strong><em>bevorzugte Symptom-Sprache</em></strong> berücksichtigt, die angemeldete Benutzer in den <a href='user.php#settings'><strong>Benutzereistellungen</strong></a> festlegen können.<br>
  Nachdem du die Rubrik ausgewählt und eventuell Suchbegriffe eingegeben hast, drückst du rechts auf <strong><em>"Symptome anzeigen"</em></strong>.
</p>
<p>
  Jetzt öffnen sich unterhalb zwei Auswahlfenster. Im oberen siehst du die Symptome als Baumansicht, die deinen Kriterien entsprechen. Das untere ist erstmal leer. Dort werden die Symptome eingetragen, die du auswählst.<br>
  Ein Symptom wird durch anklicken ausgewählt. Rubriken mit Unterrubriken lassen sich ausklappen. Die ausgewählten Symptome erscheinen im unteren Auswahlfenster.<br>
  Wenn du dir alle Mittel anzeigen lassen willst, die einem <strong>Symptom</strong> zugeordnet sind, klicke entweder im oberen oder im unteren Fenster auf <img src='../../skins/original/img/info.gif' width='12' height='12'> neben dem entsprechenden Symptom. Genaueres siehe <a href="#symptominfo">Symptom-Information</a>.<br>
  Durch klicken auf <img src='../../skins/original/img/del.png' width='12' height='12'> im unteren Fenster wird das entsprechende <strong>Symptom</strong> abgewählt.<br>
</p>
<p>
  Du kannst jedes ausgewählte Symptom individuell gewichten.
  Dabei kannst du den Symptomen folgende Gewichtungen zuordnen:
  <ul>
    <li><strong>0:</strong> Das Symptom ist deaktiviert. Es wird im Repertorisierungergebnis zwar angezeigt, aber in der Gesamtbewertung nicht berücksichtigt.</li>
    <li><strong>1:</strong> Standardeinstellung, das Symptom ist vorhanden.</li>
    <li><strong>2:</strong> Das Symptom kommt häufig vor <strong>oder</strong> ist besonders intensiv.</li>
    <li><strong>3:</strong> Das Symptom kommt häufig vor <strong>und</strong> ist besonders intensiv.</li>
    <li><strong>4:</strong> Das Symptom ist <strong>besonders wichtig bzw. auffällig</strong>. Sollte selten benutzt werden.</li>
  </ul>
  Es ist sinnvoll den vollen Umfang an Gewichtungen bei einer Repertorisierung mit einzubeziehen.<br>
</p>
<p>
  Während der Symptomauswahl kannst du jederzeit die angezeigten Symptome ändern, indem du eine neue Hauptrubrik und/oder neue Suchbegriffe eingibst und nochmals auf <strong><em>"Symptome anzeigen"</em></strong> drückst. Die bereits ausgewählten Symptome bleiben dabei erhalten.<br>
  Wenn die Symptomauswahl abgeschlossen ist, drückst du auf <strong><em>"Repertorisieren"</em></strong>.
</p>
<br><span class="rightFlow"><a href="#oben" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="rep_result" id="rep_result"><br></a>
<h3>
  Repertorization result
</h3>
<p>
  Nach dem Repertorisieren erscheint ein neues Tab mit dem <strong>Repertorisierungsergebnis</strong>.<br>
  Das Repertorisierungsergebnis kannst du <strong>als PDF anzeigen oder herunterladen</strong>. Die PDF-Datei enthält die 20 wichtigsten Mittel und ist zum <strong>Ausdruck</strong> im A4-Querformat vorbereitet.<br>
  Wenn du dich angemeldet hast, besteht die Möglichkeit, das Repertorisierungsergebnis zu <strong>speichern</strong>.
</p>
<div>
  Im oberen Bereich siehst du 4 Felder:
  <ul>
    <li><strong>Patient</strong> - Hier gibst du einen Patientenkode ein. Dabei wird aus Datenschutzgründen empfohlen, einen Kode zu wählen, der keine Rückschlüsse auf die Identität des Patienten erlaubt.</li>
    <li><strong>Rep.-Datum</strong> - Hier ist das aktuelle Datum voreingetragen. Es kann aber gegebenenfalls angepasst werden.</li>
    <li><strong>Verordnung</strong> - Hier kannst du das/die verordnete(n) Mittel eintragen.</li>
    <li><strong>Fallaufnahme</strong> - Hier kann die Anamnese hinterlegt werden.</li>
  </ul>
  Nachdem du alle Angaben gemacht hast kann das Ergebnis über <strong><em>"Ergebnis speichern"</em></strong> gespeichert werden.
  Es erscheint eine Meldung, unter welcher <strong>Repertorisierungsnummer</strong> das Ergebnis gespeichert wurde. Gespeicherte Repertorisierungen lassen sich im <strong>Benutzerbereich</strong> wieder aufrufen.
</div>
<p>
  <strong>Das Repertorisierungsergebnis wird übersichtlich in der <em>interaktiven Ergebnistabelle</em> dargestellt:</strong>
  <ul>
    <li>Rechts befinden sich die Symptomgewichtung und die <strong>Symptome nach Gewichtung sortiert</strong>. Wenn du auf ein Symptom klickst wird die entsprechende <a href="#symptominfo"><strong>Symptom-Info</strong></a> angezeigt.</li>
    <li>Im Tabellenkopf findest du die <strong>Kurznamen der Mittel nach Relevanz sortiert</strong>. Beim überfahren mit der Maus erscheint der vollständige Mittelname. Bei Klick kommst du in die entsprechende <a href="#materia"><strong>Materia Medica</strong></a>.</li>
    <li>In der darunterliegenden Tabellenzeile werden die <strong>Gesamtwertigkeiten und Treffer</strong> für das entsprechende Mittel angezeigt.</li>
    <li>In der Tabelle findest du die <strong>Wertigkeit des Mittels für das entsprechende Symptom</strong>. Wenn du mit der Maus über die Wertigkeit fährst erscheinen <strong>Angaben zu den gefundenen Quellen</strong>. Bei Klick öffnet sich ein <strong>Popup-Fenster mit den Symptom-Mittel-Details</strong>.</li>
  </ul>
</p>
<p>
  Wenn du ausgehend von den ausgewählten Symptomen weiter repertorisieren willst drückst du unten auf <strong><em>"Weiter repertorisieren"</em></strong>. Es erscheint das Repertorisierungstab mit den gewählten Symptomen.
</p>
<br><span class="rightFlow"><a href="#oben" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="materia" id="materia"><br></a>
<h3>
  Materia Medica
</h3>
<div>
  Hier kannst du dir zu einem <strong>Mittel</strong> die ihm <strong>zugeordneten Symptome</strong> zusammenstellen lassen. Außerdem werden weitere Informationen zu dem Mittel angezeigt, sofern sie in der Datenbank hinterlegt sind.
  Um sich die <strong>Materia Medica</strong> zu einem Mittel zusammenzustellen, gebe den <strong>Wortanfang des Mittelnamens oder der Mittelabkürzung</strong> in das Suchfeld ein und <strong>wähle aus den vorgeschlagenen Mitteln das entsprechende aus</strong>.
</div>
<p>
  Im oberen Bereich der Mittelbeschreibung werden <strong>spezifische Angaben</strong> zum Mittel angezeigt. Dies sind im einzelnen:
  <ul>
    <li><strong>Mittelname</strong> - der offizielle Mittelname</li>
    <li><strong>Abkürzung</strong> - die gebräuchliche Kurzform</li>
    <li><strong>Verwandte Mittel, unverträgliche Mittel und Antidote</strong></li>
    <li><strong>Herstellung, Herkunft und Synonyme des Mittels</strong></li>
    <li><strong>Allgemeine Beschreibung</strong> des Mittels</li>
    <li><strong>Leitsymptome</strong> in den Unterkategorien Allgemein, Gemüt und Körper</li>
    <li><strong>Links</strong> zu verschiedenen weiterführenden Informationen zu diesem Mittel.</li>
  </ul>
</p>
<p>
  Darunter befindet sich eine <strong>Baumansicht mit allen Symptomen, die diesem Mittel zugeordnet sind</strong>.<br>
  Die <strong>Wertigkeit der Symptome bezüglich des angezeigten Mittels</strong> werden durch die Schriftart und Schriftfarbe ausgedrückt:
  <ul>
<?php
for ($i = 5; $i > 0; $i--) {
	echo "    <li class='grade_$i' style='list-style: none; padding: 2px;'>$i" . _("-grade") . "</li>\n";
}
?>
  </ul>
  Um sich die <strong>Mittel</strong> anzeigen zu lassen, die einem <strong>bestimmten Symptom</strong> zugeordnet sind, klicke auf <img src='../../skins/original/img/info.gif' width='12' height='12'> neben dem entsprechenden Symptom. Genaueres siehe <a href="#symptominfo">Symptom-Information</a>.
</p>
<p>
  Wenn du mit der Maus über ein Symptom fährst erscheint die <strong>Wertigkeit und Angaben zu den Quellen</strong>.<br>
  Wenn du darauf klickst werden dir alle <strong>Details zu dieser Rubrik</strong> angezeigt.
</p>
<p>
  Du kannst die Symptomauswahl <strong>nach Hauptrubrik sowie nach minimaler Wertigkeit filtern</strong>.<br>
  Nachdem du die gewünschte Hauptrubrik und Wertigkeit ausgewählt hast drücke auf <strong><em>"Zusammenstellen"</em></strong>.
</p>
<br><span class="rightFlow"><a href="#oben" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="symptominfo" id="symptominfo"><br></a>
<h3>
  Symptom information
</h3>
<p>
  Zur Symptom-Information gelangst du entweder über das <strong>Repertorisierungsfenster</strong> oder über die <strong>Materia Medica</strong>.
</p>
<p>
  In der Legende des Rahmens findest du den <strong>Pfad zu dem Symptom</strong>.<br>
  Hier kannst du zu der Symptom-Info der <strong>Elternrubriken</strong> springen.
</p>
<p>
  Im oberen Bereich der Symptom-Info werden <strong>spezifische Angaben</strong> zum Symptom angezeigt. Dies sind im einzelnen:
  <ul>
    <li><strong>Symptom</strong> - Beschreibung des Symptoms.</li>
    <li><strong>Symptom-Nr.</strong> - die Nummer unter dem das Symptom in OpenHomeopath registriert ist.</li>
    <li><strong>Hauptrubrik</strong> - die Hauptrubrik unter der das Symptom eingeordnet ist.</li>
    <li><strong>Sprache</strong> - die Sprache des Symptoms mit einem Hinweis, ob es sich um die Originalsprache handelt.</li>
    <li><strong>Übersetzungen</strong> - gegebenenfalls werden hier Übersetzungen aufgeführt.</li>
    <li><strong>Weitere Details</strong> - Link zu mehr Details zu diesem Symptom in OpenHomeo.org.</li>
  </ul>
</p>
  Darunter findest du eine <strong>Baumansicht</strong> mit den Unterrubriken dieser Rubrik.<br>
  Wenn du auf <img src='../../skins/original/img/info.gif' width='12' height='12'> klickst kommst du in die Symptom-Info der entsprechenden Unterrubrik.
<p>
  Unten werden <strong>alle Mittel, die dem Symptom zugeordnet sind</strong> angezeigt.<br>
  Die <strong>Wertigkeit der Mittel bezüglich des angezeigten Symptoms</strong> werden durch die Schriftart und Schriftfarbe ausgedrückt:
  <ul>
<?php
for ($i = 5; $i > 0; $i--) {
	echo "    <li class='grade_$i' style='list-style: none; padding: 2px;'>$i" . _("-grade") . "</li>\n";
}
?>
  </ul>
  Wenn du dir zu einem Mittel die <strong>Materia Medica</strong> zusammen stellen willst klicke auf <img src='../../skins/original/img/materia.png' width='12' height='12'> vor dem jeweiligen Mittel. Näheres im Kapitel <a href="#materia">Materia Medica</a>.
</p>
<p>
  Wenn du mit der Maus über ein Mittel fährst erscheint die <strong>Wertigkeit und Angaben zu den Quellen</strong>.<br>
  Wenn du darauf klickst werden dir alle <strong>Details zu dieser Rubrik</strong> angezeigt.
</p>
</p>
  Die Mittel lassen sich <strong>nach Wertigkeit, Mittelname oder Mittel-Abkürzung sortieren</strong>, indem du die entsprechende Sortierung in der Dropdown-Liste auswählst.<br>
  Bei der Sortierung nach Mittel-Abkürzung werden die Mittel <strong>wesentlich kompakter präsentiert</strong>, was vor allem bei großen Rubriken übersichtlicher wirkt.
<p>
<p>
  Ausserdem kannst die Mittel <strong>nach minimaler Wertigkeit filtern</strong> indem du die entsprechende Wertigkeit in der Dropdown-Liste auswählst.
</p>
<br><span class="rightFlow"><a href="#oben" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="daten" id="daten"><br></a>
<h3>
  Data maintenance
</h3>
<p>
  Hier kann die <strong>Datenbank</strong> erweitert und verändert werden. Näheres im Kapitel <a href="datadmin.php"><strong>Datenpflege</strong></a>.
</p>
<br><span class="rightFlow"><a href="#oben" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="hilfe" id="hilfe"><br></a>
<h3>
  Help
</h3>
<p>
  Here you find this <strong>Help</strong>.
</p>
<br><span class="rightFlow"><a href="#oben" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="info" id="info"><br></a>
<h3>
  Info
</h3>
<p>
  Hier findest du Informationen über die Programmversion, Lizenz und Copyright, Dank an die, die durch Inspiration und nützliche Skripte bzw. Programmkode zum Programm beigetragen haben, Client- und Server-Anforderungen und Installation und Konfiguration für MySQL.
</p>
<?php
include("help/layout/$skin/footer.php");
?>
