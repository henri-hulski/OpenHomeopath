<?php

/**
 * help/en/expresstool.php
 *
 * The English manual for the Expresstool.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   Expresstool
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$head_title = "Expresstool :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Expresstool, Expresstool Manual";
if (empty($_GET['popup'])) {
	$skin = $session->skin;
	include("help/layout/$skin/header.php");
}
?>
<h1>
  Express-Tool Manual
</h1>
<ol>
  <li>Select the corresponding <strong>source</strong>. If the source doesn't exists you first have to <a href="../../datadmin.php?function=show_insert_form&amp;table_name=sources">add it to the source table</a>.</li>
  <li>Select the corresponding <strong>main rubric</strong>. If the main rubric doesn't exists you may first <a href="../../datadmin.php?function=show_insert_form&amp;table_name=main_rubrics">add it to the main rubrics table</a>.</li>
  <li>In the text area insert the <strong>symptom-remedy records</strong> according to the following rules:
  <ul>
    <li>The symptom-remedy records are seperated by line breaks.</li>
    <li>First insert the <strong>symptom</strong> including parent rubrics. The subrubrics are seperated by a '<strong>></strong>'.</li>
    <li>For not rewriting the parent rubrics each time you can use a <strong>short form</strong>:<br>
    '<strong>></strong>' at the beginning replaces the previous parent rubric.<br>
    With '<strong>>></strong>' at the beginning you jump back one parent rubric, with '<strong>>>></strong>' 2 parent rubrics etc..</li>
    <li>For books you can specify the <strong>page number</strong> by '<strong>s.</strong>' + page number (e.g. '<strong>s.123</strong>').</li>
    <li>You can add a <strong>Künzli-dot</strong> for the symptom with <strong>@</strong>.</li>
    <li>Text in parentheses ('<strong>()</strong>')  will be saved seperatly as <strong>extra information</strong>.</li>
    <li>After this insert a colon ('<strong>:</strong>') and the <strong>remedy list</strong>.</li>
    <li>The <strong>remedy abbreviations</strong> are separated by commas ('<strong>,</strong>'). Semicolons ('<strong>;</strong>') or spaces (' ') are also allowed. Dots ('<strong>.</strong>') at the end of the abbreviations can be leaved out. The abbreviations are not case-sensitive.</li>
    <li>After each remedy the grade from <span class='nobr'><strong>1 - 5
    </strong></span> is appended with a hyphen ('<strong>-</strong>'). The default grade is <strong>1</strong> and can be leaved out.</li>
    <li>Behind each remedy you can add the status of proving by one of the following signs:
    <ul style='list-style-type:none'>
<?php
$query = "SELECT status_symbol, status_en FROM sym_status WHERE status_id != 0";  // show status symbols
$db->send_query($query);
while (list($statussymbol, $statusname) = $db->db_fetch_row()) {
	echo "      <li>' <strong>$statussymbol</strong> ' : &nbsp;$statusname</li>\n";
}
$db->free_result();

?>
    </ul></li>
    <li>You can add a <strong>Künzli-dot</strong> to the remedy with the <strong>@</strong> sign.</li>
    <li>You can add <strong>reference sources</strong> seperated from the remedy and from each other by a hash ('<strong>#</strong>').</li>
    <li>Remedies from nonclassical provings (e.g. dream provings) must be enclosed in curly brackets ('<strong>{ }</strong>') and appended at the end of the remedy list.</li>
    <li><strong>Example</strong> with 2 symptoms:<br>
      conditions > bath > cold, amel.: alum-4,ang-2,apis-2,bell-2,caps-2<br>
      phenomena > air > through him, seems to go: calc-2,coloc-2
    </li>
  </ul></li>
  <li>If a <strong>remedy abbreviation could not be found</strong> in the database, an error is thrown and the record reappear in the text area <strong>for correction</strong>.<br>
  Check if you have <strong>no spelling error</strong>, otherwise check with the <a href='../../datadmin.php?function=show_search_form&amp;table_name=remedies'>datadmin search function</a> if the remedy table uses <strong>another abbreviation</strong>.<br>
  In this case you can add your alternative abbreviation <a href='../../datadmin.php?function=show_insert_form&amp;table_name=rem_alias'>to the alias table</a>.<br>
  The remedy database contains with more then <strong>3600 entries</strong> nearly all possible homeopathic remedies. If you really have a new remedy you can <a href='../../datadmin.php?function=show_insert_form&amp;table_name=remedies'>add it to the remedy table</a>.<br>
  After <strong>correcting the record</strong> in the text area resend the form.</li>
  <li>If the specified <strong>grade, status or Künzli-dot</strong> are not consistent with the corresponding symptom-remedy-relation in the databasen and the record originates from you, it will be updated with the specified values. Otherwise the original record remains unchanged.<br>
  To reverse a change you can either resend the record with the correct value or you <a href="../../datadmin.php?table_name=sym_rem">correct the record in Datadmin</a>.</li>

  <li><strong>Remedy aliases</strong> can be inserted directly with the Expresstool:
  <ul>
    <li>The alias is a remedy abbreviation, which can be use alternatively to the common abbreviation.</li>
    <li>The alias declarations are seperated by line break. You can assign several comma seperated aliases to one remedy.</li>
    <li>The Syntax of a alias declaration: <em>"alias: remedy abbreviation = alias abbreviation [,alias abbreviation,...]"</em></li>
    <li>Take care of the spelling of the alias abbreviation. Generally it should end with a dot.</li>
    <li>Example: <em>"alias: Iod. = Jod."</em></li>
  </ul></li>

  <li><strong>Sources</strong> can also be inserted directly with the Expresstool:
  <ul>
    <li>Each source is seperated by line break.</li>
    <li>The syntax of a source specification: <em>"Keyword: Source-ID#Author#Title#Year#Language#Maximum grade[#Source type]"</em></li>
    <li>The Keyword is either '<strong>source</strong>' for a main source or '<strong>ref</strong>' for a reference source.</li>
    <li><strong>Source-ID</strong> is the source identificator, which is alphanumeric and contain maximum 12 characters.</li>
    <li><strong>Author:</strong> The source author.</li>
    <li><strong>Title:</strong> The source title.</li>
    <li><strong>Year:</strong> Specify the publication year in the form '<strong>1902</strong>', '<strong>1783-1785</strong>' or '<strong>1988-</strong>'.</li>
    <li><strong>Language:</strong> The language code from the languages table (e.g. '<strong>en</strong>' for English, '<strong>de</strong>' for German, etc.). If the language doesn't exists you first have to <a href="../../datadmin.php?function=show_insert_form&amp;table_name=languages">add it to the languages table</a></a>.</li>
    <li><strong>Maximum grade:</strong> The maximum grade of the source. A number from <strong>1-5</strong>.</li>
    <li><strong>Source type:</strong> Allowed values are (write both, German and English):
    <ul>
      <li><strong>"Repertorium / Repertory"</strong></li>
      <li><strong>"Materia Medica"</strong></li>
      <li><strong>"Publikation / Publication"</strong></li>
      <li><strong>"Arzneimittelprüfung / Proving"</strong></li>
      <li><strong>"Klinischer Fall / Clinical"</strong></li>
      <li><strong>"Autor / Author"</strong></li>
      <li><strong>"Sonstiges / Others"</strong></li>
    </ul>
    If you don't specify the type <strong>"Repertorium / Repertory"</strong> will be used.</li>
  </ul></li>
</ol>
<?php
if (empty($_GET['popup'])) {
	include("help/layout/$skin/footer.php");
}
?>
