<?php

/**
 * help/de/search.php
 *
 * The German manual for the symptom search.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  SymptomSearch
 * @package   Manual
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$head_title = "Symptomsuche :: Hilfe :: OpenHomeopath";
$meta_description = "Handbuch für OpenHomeopath, Symptomsuche";
if (empty($_GET['popup'])) {
	$skin = $session->skin;
	include("help/layout/$skin/header.php");
}
?>

<h1>So formulierst du Symptom-Suchanfragen</h1>

<p>Folgende Suchmöglichkeiten stehen zur Verfügung:</p>

<dl>
<dt><strong>Einfache Suche</strong>:</dt>
<dd>

<p>Gib einfach <strong>einen oder mehrere Suchbegriffe</strong> in das Suchfeld ein.<br>
Gefunden werden alle Symptome, in denen die Suchbegriffe vorkommen. Zwischen Groß- und Kleinschreibung wird nicht unterschieden.<br>
Bei der Suche nach Wörtern mit <span class="blue">"ß"</span> wird auch nach <span class="blue">"ss"</span> gesucht und umgekehrt.</p>

<p>Du kannst wählen zwischen</p>
<ul>
  <li><strong>"und"-Suche:</strong> alle angegebenen Begriffe müssen vorkommen.</li>
  <li><strong>"oder"-Suche:</strong> mindestens einer der Begriffe muss vorkommen.</li>
</ul>
<p>sowie zwischen</p>
<ul>
  <li>Suche nach <strong>ganzen Wörtern</strong></li>
  <li>Suche nach <strong>Wortteilen</strong></li>
</ul>

<p>Die Auswahl erfolgt mittels der Schaltflächen und wird automatisch auf alle Suchbegriffe angewandt.</p>

<p>Außerdem kannst Du bestimmen, dass Symptome, in denen ein bestimmter oder bestimmte Begriffe vorkommen, nicht angezeigt werden. Für diese <strong>"ohne"-Suche</strong> gib ein <span class="blue">"<strong>-</strong>"</span> (Minus) vor dem Begriff ein. Innerhalb von Phrasen (siehe unten) ist eine "ohne"-Suche nicht möglich.</p>
<p>Bei der Suche nach ganzen Wörtern kann <span class="blue">" <strong>*</strong> "</span> als Joker für beliebig viele (auch kein) Zeichen verwendet werden.
<br>Außerdem werden bei der Suche nach ganzen Wörtern nur Wörter mit mehr als drei Buchstaben berücksichtigt.</p>
<p>Für Fortgeschrittene: Bei der Suche nach Wortteilen könnt ihr <a href='http://dev.mysql.com/doc/refman/5.1/de/regexp.html' target='_blank'>reguläre Ausdrücke</a> verwenden.</p>

</dd>

<dt><strong>Phrasensuche</strong>:</dt>
<dd>

<p>Für die Phrasensuche gib zwei oder mehr Wörter innerhalb von <em>Gänsefüßchen</em> ein.<br>
Bsp: <span class="blue">"tiefer Schlaf"</span><br>
Gefunden werden Symptome, in denen die angegebenen Wörter in angegebener Reihenfolge vorkommen.</p>

<p>Wenn diese Art der Suche mit der Suche nach <strong>Wortteilen</strong> kombiniert wird, wird die ganze Phrase als Wortteil gesehen. Es kann also beim ersten Wort der Wortanfang und beim letzten Wort das Wortende fehlen.</p>

<p>Satzzeichen wie Punkt oder Komma werden automatisch ignoriert.</p>

</dd>
</dl>

<?php
if (empty($_GET['popup'])) {
	include("help/layout/$skin/footer.php");
}
?>
