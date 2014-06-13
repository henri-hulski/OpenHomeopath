<?php

/**
 * help/de/index.php
 *
 * The index file of the German manuals for OpenHomeopath.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   Index
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = "Inhalt :: Hilfe :: OpenHomeopath";
$meta_description = "Handbuch für OpenHomeopath, Inhaltsverzeichnis";
include("help/layout/$skin/header.php");
?>
<h1>
  Hilfe zu OpenHomeopath
</h1>
<nav class="content">
  <h2>
    Inhalt
  </h2>
  <ul>
    <li>
      <a href="preamble.php">Präambel</a>
    </li>
    <li>
      <a href="introduction.php">Einführung</a>
    </li>
    <li>
      <a href="manual.php">Bedienung von OpenHomeopath</a>
      <ul class="subcontent">
        <li><a href="manual.php#overview">Übersicht</a></li>
        <li><a href="manual.php#repertorization">Repertorisierung</a></li>
        <li><a href="search.php">So formulierst du Symptom-Suchanfragen</a></li>
        <li><a href="manual.php#rep_result">Repertorisierungsergebnis</a></li>
        <li><a href="manual.php#materia">Materia Medica</a></li>
        <li><a href="manual.php#symptominfo">Symptom-Information</a></li>
        <li><a href="manual.php#data">Datenpflege</a></li>
        <li><a href="manual.php#help">Hilfe</a></li>
        <li><a href="manual.php#info">Info</a></li>
      </ul>
    </li>
    <li>
      <a href="user.php">Benutzerverwaltung</a>
      <ul class="subcontent">
        <li><a href="user.php#account">Mein Bereich</a></li>
        <li><a href="user.php#settings">Einstellungen</a></li>
        <li><a href="user.php#logout">Abmelden</a></li>
      </ul>
    </li>
    <li>
      <a href="datadmin.php">Datenpflege</a>
      <ul class="subcontent">
        <li><a href="expresstool.php">Express-Tool</a></li>
        <li><a href="expresstool_tut.php">Express-Tool Tutorium</a></li>
        <li><a href="datadmin.php#medica">Tabelle Materia Medica</a></li>
        <li><a href="datadmin.php#symptoms">Tabelle Symptome</a></li>
        <li><a href="datadmin.php#sources">Tabelle Quellen</a></li>
        <li><a href="datadmin.php#remedies">Tabelle Mittel</a></li>
        <li><a href="datadmin.php#mainrubrics">Tabelle Hauptrubriken</a></li>
        <li><a href="datadmin.php#overview">Aufbau de Datenpflege-Startseite</a></li>
        <li><a href="datadmin.php#edit">Bearbeiten und Einfügen von Sätzen</a></li>
      </ul>
    </li>
  </ul>
</nav>
<?php
include("help/layout/$skin/footer.php");
?>
