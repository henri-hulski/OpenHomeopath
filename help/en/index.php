<?php

/**
 * help/en/index.php
 *
 * The index file of the English manuals for OpenHomeopath.
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
$head_title = "Contents :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Contents";
include("help/layout/$skin/header.php");
?>
<h1>
  OpenHomeopath Manual
</h1>
<nav class="content">
  <h1>
    Contents
  </h1>
  <ul>
    <li>
      <a href="preamble.php">Preambel</a>
    </li>
    <li>
      <a href="introduction.php">Introduction</a>
    </li>
    <li>
      <a href="manual.php">Manual</a>
      <ul class="subcontent">
        <li><a href="manual.php#overview">Overview</a></li>
        <li><a href="manual.php#repertorization">Repertorization</a></li>
        <li><a href="search.php">How to search for symptoms</a></li>
        <li><a href="manual.php#rep_result">Repertorization result</a></li>
        <li><a href="manual.php#materia">Materia Medica</a></li>
        <li><a href="manual.php#symptominfo">Symptom information</a></li>
        <li><a href="manual.php#data">Data maintenance</a></li>
        <li><a href="manual.php#help">Help</a></li>
        <li><a href="manual.php#info">Info</a></li>
      </ul>
    </li>
    <li>
      <a href="user.php">User account</a>
      <ul class="subcontent">
        <li><a href="user.php#account">My account</a></li>
        <li><a href="user.php#settings">Settings</a></li>
        <li><a href="user.php#logout">Log out</a></li>
      </ul>
    </li>
    <li>
      <a href="datadmin.php">Data maintenance</a>
      <ul class="subcontent">
        <li><a href="expresstool.php">Expresstool Manual</a></li>
        <!--<li><a href="expresstool_tut.php">Expresstool Tutorial</a></li>-->
        <li><a href="datadmin.php#medica">Materia Medica table</a></li>
        <li><a href="datadmin.php#symptoms">Symptoms table</a></li>
        <li><a href="datadmin.php#sources">Sources table</a></li>
        <li><a href="datadmin.php#remedies">Remedies table</a></li>
        <li><a href="datadmin.php#mainrubrics">Main rubrics table</a></li>
        <li><a href="datadmin.php#overview">Data maintenance - Start page</a></li>
        <li><a href="datadmin.php#edit">Edit and insert records</a></li>
      </ul>
    </li>
  </ul>
</nav>
<?php
include("help/layout/$skin/footer.php");
?>
