<?php

/**
 * doc/de/info.php
 *
 * Information details about OpenHomeopath in German.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Documentation
 * @package   Info
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = _("Info") . " :: OpenHomeopath";
$meta_description = "OpenHomeopath Info";
include("help/layout/$skin/header.php");
?>
<h1>
  <?php echo _("Info"); ?>
</h1>
<div class="content">
  <h2>
    <?php echo _("Contents"); ?>
  </h2>
  <ul>
    <li><a href="#version">Programmversion</a></li>
    <li><a href="#changelog">Changelog</a></li>
    <li><a href="#license">Lizenz und Copyright</a></li>
    <li><a href="#credits">Dank geht an</a></li>
    <li><a href="#client">Client-Anforderung</a></li>
    <li><a href="#server">Server-Anforderung</a></li>
    <li><a href="#install">Installation und Konfiguration</a></li>
    <li><a href="#download">Download</a></li>
  </ul>
</div>
<a name="version" id="version"><br></a>
<h2>
  Programmversion
</h2>
<p>
  Dies ist <strong>OpenHomeopath Version 1.0.1_ext</strong> veröffentlicht am 07.06.2014.<br>
  Nach einer <strong>grundlegenden Überarbeitung des gesamten Codes und der Datenbankstruktur</strong> veröffentliche ich hiermit nach über 7 Jahren Arbeit die erste stabile Version von OpenHomeopath.<br>
  Wenn noch Bugs oder Programmfehler auftauchen und bei weiteren Fragen meldet euch <a title="Kontakt zum Autor" href="mailto:henri.hulski@gazeta.pl?subject=OpenHomeopath">bei mir</a>.
</p>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="changelog" id="changelog"><br></a>
<h2>
  Changelog
</h2>
  <ul>
    <li><strong>Version 1.0 veröffentlicht am 05.06.2014:</strong> Erstes stabiles Release nach über 7 Jahren Entwicklung und einer grundlegenden Überarbeitung des gesamten Quellcodes und der Datenbankstruktur.<br>
    <strong>Die wichtigsten aktuellen Änderungen:</strong><br>
    <ul>
      <li>Grundlegende Modernisierung des Layouts.</li>
      <li>Möglichkeit, das Repertorisierungsergebnis als PDF anzuzeigen oder herunterzuladen.</li>
      <li>Die Rubriken können jetzt bei der Repertorisierung gewichtet werden.</li>
      <li>Die Symptome in der Materia Medica werden wie bei der Repertorisierung als Rubrikbaum dargestellt.</li>
      <li>In der Materia Medica können die angezeigten Rubriken nach Hauptrubrik und minimaler Wertigkeit gefiltert werden.</li>
      <li>In der Symptominfo lassen sich die zugeordeneten Mittel nach minimaler Wertigkeit filtern und nach Wertigkeit, Mittelname oder Kurzname sortieren. Bei der Sortierung nach Kurznamen wird eine kompaktere Darstellung gewählt.</li>
      <li>Reimport der Repertorien aus OpenRep: Kent, Bönninghausen, Boger und das Repertorium Publicum. Weiterhin habe ich den deutschen Kent von der Bergischen Akademie nach grundlegender Durchsicht und Fehlerbereinigung reimportiert.</li>
      <li>Beim Import wurden auch Querverweise zwischen den Rubriken berücksichtigt, die jetzt in der Symptominfo angezeigt werden.</li>
      <li>Aktualisierung und Reorganisierung des OpenHomeopath-Handbuchs und die vollständige Übersetzung des Handbuchs ins Englische.</li>
      <li>Weitgehende Verwendung der objektorientierten Programmierung (OOP) und Reorganisation des PHP-Quellcodes in Klassen.</li>
      <li>Übersetzung aller Tabellen-, Spalten-, Klassen-, Funktions- und Variablennamen sowie aller Kommentare in englisch, so daß sich auch internationale Entwickler an dem Projekt beteiligen können.</li>
      <li>Hinzufügen einer <a href='http://openhomeo.org/openhomeopath/doc/en/apigen' target='_blank'>ausführlichen englischen Quellcode-Dokumentation</a> nach dem PHPDoc-Schema.</li>
    </ul></li>
    <li><strong>Version 1.0.1 veröffentlicht am 07.06.2014:</strong> Ein Bugfix-Release, bei dem Fehler bereinigt und dieses Changelog hinzugeführt wurde.</li>
  </ul>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="license" id="license"><br></a>
<h2>
 Lizenz und Copyright
</h2>
<p>
  Copyright &copy; 2007-2014 by Henri Schumacher.
</p>
<p>
   OpenHomeopath ist freie Software. Du kannst es unter den Bedingungen der GNU Affero General Public License, wie von der Free Software Foundation veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß Version 3 der Lizenz oder (nach deiner Option) jeder späteren Version.
</p>
<p>
  Die Veröffentlichung von OpenHomeopath erfolgt in der Hoffnung, daß es dir von Nutzen sein wird, aber OHNE IRGENDEINE GARANTIE, sogar ohne die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FÜR EINEN BESTIMMTEN ZWECK. Details finden Sie in der GNU Affero General Public License.
</p>
<p>
  Ein vollständiges Exemplar der original GNU Affero General Public License Version 3 (AGPLv3) auf englisch findest du <a href="../en/agpl3.php">hier</a>.
</p>
<p>
  Die Hilfe und Dokumentation von OpenHomeopath wurden unter der <a href='../en/fdl_1.3.php'>GNU Free Documentation License Version 1.3 (FDLv1.3)</a> veröffentlicht.
</p>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="credits" id="credits"><br></a>
<h2>
Dank geht an
</h2>
<ul>
  <li><strong>Thomas Bochmann</strong>: Thomas gründete und betreut das Homöopathie-Portal <a href='http://openhomeo.org'>OpenHomeo.org</a> auf dem OpenHomeopath läuft.<br>
  Ausserdem hat er durch Anregungen, Vorschläge und auch konstruktiver Kritik viel zum heutigen Aussehen von OpenHomeopath beigetragen.<br>
  Thomas stellt auf OpenHomeo.org auch einige Skripte zur Verfügung, die OpenHomeopath ergänzen.
  </li>
  <li><strong>Bernd Zille</strong>: Sein Programm <a href="http://www.zille-software.de">BZ-Homöopathie für Windows</a> hat mich zum Schreiben von OpenHomeopath inspiriert.<br>
  Besonderen Dank für die <strong>Erteilung der Lizenz</strong> für die Benutzung seines Repertoriums mit OpenHomeopath zu nicht kommerziellen Zwecken. Näheres findet ihr <a href="../../source.php?src=BZH">hier</a>.</li>
  <li><strong>Eugenio Tacchini</strong>: Sein DataBase Interfaces Kreator <a href="http://www.dadabik.org">DaDaBIK</a> Version 4.2 bildet die Grundlage für die Datenbankpflege.<br>
  Copyright &copy; 2001-2007 by Eugenio Tacchini. Das Programm steht unter der <a href="../en/gpl3.php">GNU General Public License</a>.
  </li>
  <li><strong>Alexey Ozerov</strong>: Die Datenbankinstallation hab ich von seinem Programm <a href="http://www.ozerov.de/bigdump.php">BigDump ver. 0.34b</a> vom 04.09.2011 abgeleitet.<br>
  Copyright &copy; 2003-2011 by Alexey Ozerov. Unter <a href="../en/gpl3.php">GPL-Lizenz</a> veröffentlicht.
  </li>
  <li><strong>Jpmaster77</strong>: Das Loginsystem baut auf seinem freien Skript <a href="http://www.evolt.org/PHP-Login-System-with-Admin-Features">Login System v.2.0</a> vom 26. August 2004 auf.
  </li>
  <li><strong>Phorum Development Team</strong>: Das Homeophorum enthält die Software <a href="http://phorum.org/">Phorum</a> Version 5.1.25 vom 14.03.2007 entwickelt vom Phorum Development Team unter der <a href='../../include/phorum/docs/license.txt'>Phorum License 2.0</a>.
  </li>
  <li><strong>Patrick Fitzgerald</strong>: Die Tabs sind auf dem Javascript <a href="http://www.barelyfitz.com/projects/tabber/">Tabber</a> von Patrick Fitzgerald aufgebaut.<br>
  Copyright &copy; 2006 by Patrick Fitzgerald.<br>
  Veröffentlicht unter der <a href='http://www.opensource.org/licenses/mit-license.php'>MIT-Lizenz</a>, auch X11-Lizenz genannt.
  </li>
</ul>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="client" id="client"><br></a>
<h2>
  Client-Anforderung
</h2>
<p>Zur Benutzung von <strong>OpenHomeopath</strong> müssen <strong>Javascript</strong> und <strong>Cookies</strong> (zumindest von der gleichen Site) im Browser aktiviert sein.</p>
<strong>OpenHomeopath</strong> ist optimiert für:
<ul>
<li><strong>Bildschirmauflösung</strong> 1280x1024 und mehr</li>
<li><strong>Browser</strong> Chromium bzw. Chrome, Opera und Firefox</li>
<li><strong>Betriebssystem</strong> getestet unter Linux, aber funktioniert auch auf anderen Systemen</li>
</ul>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="server" id="server"><br></a>
<h2>
  Server-Anforderung
</h2>
<ul>
  <li><strong>MySQL</strong> ab Version 5.1</li>
  <li><strong>PHP</strong> ab PHP 5.3</li>
  <li><strong>UTF-8</strong> Unterstützung.</li>
</ul>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="install" id="install"><br></a>
<h2>
  Installation und Konfiguration
</h2>
<ol>
  <li>Erstelle eine MySQL-Datenbank und einen Benutzer, der alle Rechte für diese Datenbank besitzt.</li>
  <li>Nenne die Datei "openhomeopath/include/classes/db/config_db_sample.php" in "config_db.php" um und trage den Namen der MySQL-Datenbank, den Benutzernamen und dessen Passwort ein. Außerdem muss der Datenbanktreiber angeben werden: "mysqli" oder "mysql".<br>
  Schütze die Datei "config_db.php" später auf dem Server vor unbefugtem Lese-/Schreibzugriff (z.B. mit chmod 600), da das Passwort im Klartext gespeichert wird. Achte dabei darauf, das der Server weiterhin Lesezugriff auf "config_db.php" hat! Bei lokaler Installation nicht nötig.</li>
  <li>Lade den gesamten Order "openhomeopath" auf deinen Webserver. Der kann auch lokal auf deinem Computer oder Laptop laufen.</li>
  <li>Öffne in deinem Browser "<em>http://deine.webadresse.de/</em>openhomeopath/install/<strong>install_db.php</strong>" und importiere die Daten in die Datenbank.<br>
  Wenn alles klappt kannst du die Datei "sql/OpenHomeopath.sql.gz" auf deinem Server löschen.</li>
  <li>Es gibt einen <strong>vorgegebenen Benutzer</strong> mit Administratorrechten: <strong><em>"admin"</em></strong> mit dem <strong>Passwort</strong>: <strong><em>"admin"</em></strong>. Logge dich als <strong>"admin"</strong> unter "<em>http://deine.webadresse.de/</em>openhomeopath/<strong>login.php</strong>" ein.<br>
  Das kann etwas dauern, da OpenHomeopath noch weitere Tabellen generieren muss.</li>
  <li>Und schon kannst du loslegen. Ich empfehle, das admin-Passwort zu ändern.</li>
</ol>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span>
<a name="download" id="download"><br></a>
<h2>
  Download
</h2>
<div class="rightFlow">
  <div class='center'>
    <strong>Denk daran zu spenden!</strong><br><br>
    <a href='../../donations.php' target='_blank'><img src='../../img/donate_de.png' width='110' height='33' alt='Spende' title='Wenn dir unsere Arbeit gefällt bedank dich mit einer großzügigen Spende.'></a>
  </div>
</div>
<div>
  Hier könnt ihr <strong>OpenHomeopath</strong> herunterladen:
  <ul>
    <li><a href="../../download/OpenHomeopath_1.0.tar.gz">OpenHomeopath_1.0.tar.gz</a> gepackt als tar.gz-Datei für die lokale Installation.</li>
    <li>Du kannst auch das <a href='https://github.com/henri-hulski/OpenHomeopath' target='_blank'>OpenHomeopath Git-Repository</a> von GitHub klonen.</li>
    <li>Oder den <a href='https://github.com/henri-hulski/OpenHomeopath/archive/master.zip'>OpenHomeopath Masterbranch</a> von GitHub als gepackte Zip-Datei herunterladen.</li>
  </ul>
</div>
<p>Ihr könnt euch auch gerne die <a href='../en/apigen' target='_blank'>OpenHomeopath PHP Quellcode Dokumentation</a> anschauen.</p>
<br><span class="rightFlow"><a href="#up" title="Zum Seitenanfang"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="Zum Seitenanfang" border="0"></a></span><br>
<?php
include("help/layout/$skin/footer.php");
?>
