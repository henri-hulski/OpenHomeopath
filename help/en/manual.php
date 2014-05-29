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
  It is recommended to use <strong>OpenHomeopath</strong> with a <strong>screen resolution</strong> of at least <strong>1280x1024</strong>.<br>
  <strong>Javascript and Cookies (at least from the same site)</strong> have to be enabled in the browser.
  You can change the layout of OpenHomeopath by selecting a skin. At the moment we've 2 skins:  <strong><em>"original"</em></strong> from Henri Schumacher and <strong><em>"kraque"</em></strong> without sidebar from Thomas Bochmann.
  At the moment OpenHomeopath is translated in English and German.
</p>
<a name="overview" id="overview"><br></a>
<h3>
  Overview
</h3>
<p>
  <p>The most important parts of OpenHomeopath are <strong>well organized in Tabs</strong> on the homepage.</p>
  You can work independently on each tab without changing the content of the other tabs.<br>
  If you start OpenHomeopath you see 2 tabs, <strong><em>Repertorization</em></strong> and <span class='nobr'><strong><em>Materia Medica</em></strong></span>. After logging in the tab <span class='nobr'><strong><em>My Account</em></strong></span> appear. There are 2 more tabs that show up on request, the <strong><em>Repertorization result</em></strong> and the <strong><em>Symptom-Info</em></strong>.<br>
  In the tabs <strong><em>Repertorization result</em></strong>, <span class='nobr'><strong><em>Materia Medica</em></strong></span> and <strong><em>Symptom-Info</em></strong> can go back and forward to previous views</strong>.
</p>
<p>
  In the Original skin you see on the left side the <strong>navigation menue</strong>, from where you can reach each part of the program.
</p>
<div class="content">
  <h2>
    Contents
  </h2>
  <ul>
    <li><a href="#repertorization">Repertorization</a></li>
    <li><a href="#rep_result">Repertorization result</a></li>
    <li><a href="#materia">Materia Medica</a></li>
    <li><a href="#symptominfo">Symptom information</a></li>
    <li><a href="#data">Data maintenance</a></li>
    <li><a href="#help">Help</a></li>
    <li><a href="#info">Info</a></li>
  </ul>
</div>
<a name="repertorization" id="repertorization"><br></a>
<h3>
  Repertorization
</h3>
<p>
  This is the most important part of the program, where we make the main <strong>repertorization work</strong>.
</p>
<p>
  If you start a new repertorization, on the left side you can choose a main rubric or all rubrics. In the middle you find the <a href='search.php'>symptom search form</a> where you can provide the search items and choose the search mode.<br>
  If a symptom exists in different translations your <strong>preferred symptom-language</strong> will be considered, which can be set in your program settings.
</p>
<p>
  After pressing the <strong><em>"Show symptoms"-button</em></strong> two selection forms will show up.<br>
  In the upper one you see a tree view of the symptoms you requested. If you're selecting a symptom for repertorization by clicking on it, the symptom shows up in the second window.<br>
  To switch to the <a href="#symptominfo">Symptom-Info</a> you can click the <img src='../../skins/original/img/info.gif' width='12' height='12'> icon beside the symptom.<br>
  By clicking on the <img src='../../skins/original/img/del.png' width='12' height='12'> icon you unselect the symptom.
</p>
<p>
  You can weight each symptom separately by choosing between the following degrees:
  <ul>
    <li><strong>0:</strong> The symptom is deactivated but shows up in the repertory result.</li>
    <li><strong>1:</strong> Default, the symptom exists.</li>
    <li><strong>2:</strong> Shows some frequency <strong>or</strong> intensity of the symptom.</li>
    <li><strong>3:</strong> Shows both a frequent <strong>and</strong> an intense symptom.</li>
    <li><strong>4:</strong> Should be given rarely and only if the symptom is really impressive.</li>
  </ul>
  Try to include the full range of degrees in a repertorization.
<br>
</p>
<p>
  You can change the selection of symptoms in the upper window by changing the filters and search patterns and pressing again the <strong><em>"Show symptoms"-button</em></strong>. The symptom selection in the lower window will remain untouched.<br>
  If you've finished the symptom selection press the <strong><em>"Repertorize"-button</em></strong>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="rep_result" id="rep_result"><br></a>
<h3>
  Repertorization result
</h3>
<p>
  After repertorizing a new tab shows up with the <strong>Repertorization result</strong>.<br>
  You can save - <img src='../../img/pdf_down.png' width='32' height='32'>&nbsp;  or view - <img src='../../img/pdf_print.png' width='32' height='32'>&nbsp; the repertorization result <strong>as PDF</strong>. The PDF-file contains the 20 most important remedies and is prepared for printing in A4-format.<br>
  If you are logged in, you can <strong>save the repertory results</strong> to the database.
</p>
<div>
  In the upper part of the page you see 4 fields:
  <ul>
    <li><strong>Patient</strong> - The patient ID. By choosing it take care of privacy issues.</li>
    <li><strong>Rep.-Date</strong> - Here you see the current date. If you want you can change it.</li>
    <li><strong>Prescription</strong> - The prescripted remedies.</li>
    <li><strong>Case taking</strong> - The anamnesis of the case.</li>
  </ul>
  After filling in the fields you can save the repertorization by pressing <img src='../../img/save.png' width='32' height='32'>.
  You receive the <strong>Repertorization No.</strong> under which the result was saved.<br>
  The saved repertorizations can be managed from your user account. You can review them, continue repertorizing, publish them to other users or delete them.
</div>
<p>
  <strong>The repertory result will be presented as a nicely formatted interactive result table:</strong>
  <ul>
    <li>On the right side you find the symptom degree and the symptoms sorted by degree. If you click on a symptom, you switch to the <a href="#symptominfo"><strong>Symptom-Info</strong></a>.</li>
    <li>In the table header you find the <strong>remedy abbreviations</strong> sorted by relevance. When you move the mouse over it you get the full remedy name. By clicking you switch to the corresponding <a href="#materia"><strong>Materia Medica</strong></a>.</li>
    <li>In the table row beneath you find <strong>the total grades and hits for each remedy</strong>.</li>
    <li>In the table body you find the grade of a symptom-remedy relation. When you move the mouse over you get information about the <strong>sources</strong>. When clicking you get a popup window with the <strong><em>Symptom-remedy-details</em></strong>.</li>
  </ul>
</p>
<p>
  For continue repertorizing this case press <strong><em>"Add more symptoms"</em></strong>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="materia" id="materia"><br></a>
<h3>
  Materia Medica
</h3>
<div>
  In the Materia Medica you find details about a remedy and all symptoms related to this remedy.
  For retrieving a <strong>Materia Medica</strong> type the beginning of the remedy name or abbreviation in the search form and select it from the suggested remedies.
</div>
<p>
  In the upper section you find some details about the remedy:
  <ul>
    <li><strong>Remedy name</strong> - the full remedy name</li>
    <li><strong>Remedy-No.</strong> - the remedy-id in OpenHomeopath</li>
    <li><strong>Abbreviation</strong> - the common abbreviation</li>
    <li><strong>Related remedies, incompatible remedies and antidotes</strong></li>
    <li><strong>Preparation, origin, synonyms</strong></li>
    <li><strong>General description</strong>  of the remedy</li>
    <li><strong>Leading symptoms</strong> in the categoties General, Mind and Body</li>
    <li><strong>Links</strong> to further information about this remedy.</li>
  </ul>
</p>
<p>
  Below you find the treeview of the <strong>symptoms related to this remedy</strong>.<br>
  The <strong>grade of symptom</strong> is visualized by color and font type:
  <ul>
<?php
for ($i = 5; $i > 0; $i--) {
	echo "    <li class='grade_$i' style='list-style: none; padding: 2px;'>$i" . _("-grade") . "</li>\n";
}
?>
  </ul>
  By clicking on <img src='../../skins/original/img/info.gif' width='12' height='12'> beside the symptom you switch to the <a href="#symptominfo">Symptom-Info</a>.
</p>
<p>
  Move the mouse over a symptom to see <strong>the grade and the sources</strong>.<br>
  When clicking you get a popup window with the <strong><em>Symptom-remedy-details</em></strong>.
</p>
<p>
  You can filter the symptom selection by <strong>main rubric and minimal grade</strong>.<br>
  After selecting main rubric and grade press <strong><em>"Send request"</em></strong>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="symptominfo" id="symptominfo"><br></a>
<h3>
  Symptom information
</h3>
<p>
  To the Symptom-Info you get either from <strong>Repertorization</strong> or <strong>Materia Medica</strong>.
</p>
<p>
  In the frame-legend you find the <strong>rubric path</strong> of the symptom from where you can-m jump to the Symptom-Info of the parent rubrics.
</p>
<p>
  In the upper part of the Symptom-Info you find details about the symptom:
  <ul>
    <li><strong>Symptom</strong> - Description of the symptom.</li>
    <li><strong>Symptom-No.</strong> - the symptom-id in OpenHomeopath.</li>
    <li><strong>Main rubric</strong> - the main rubric of the symptom.</li>
    <li><strong>Native language</strong> - the native language of the symptom.</li>
    <li><strong>Translations</strong> - here you find translations if any.</li>
    <li><strong>More details</strong> - Link to more details in OpenHomeo.org.</li>
  </ul>
</p>
<p>
  Next you see a list with crossreferences if any. On click you jump to the requested Symptom-Info.
</p>
<p>
  Beneath you find a <strong>treeview</strong> with the subrubrics of this rubric.
  To switch to the <a href="#symptominfo">Symptom-Info</a> you can click the <img src='../../skins/original/img/info.gif' width='12' height='12'> icon beside the subrubric.
</p>
<p>
  Below you find a list of <strong>remedies related to this symptom</strong>.<br>
  The <strong>grade of the remedy</strong> is visualized by color and font type:
  <ul>
<?php
for ($i = 5; $i > 0; $i--) {
	echo "    <li class='grade_$i' style='list-style: none; padding: 2px;'>$i" . _("-grade") . "</li>\n";
}
?>
  </ul>
  By clicking on <img src='../../skins/original/img/materia.png' width='12' height='12'> beside the remedy you switch to the <a href="#materia">Materia Medica</a>.
</p>
<p>
  Move the mouse over a remedy to see <strong>the grade and the sources</strong>.<br>
  When clicking you get a popup window with the <strong><em>Symptom-remedy-details</em></strong>.
</p>
</p>
  You can sort the remedies with a drop-down list by <strong>grade, name or abbreviation</strong>.<br>
  When sorting by abbreviation the remedy list is <strong>much more compact</strong>, which looks nice in big rubrics.
<p>
<p>
  You can also filter the remedy selection by <strong>minimal grade</strong> from a drop-down list.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="data" id="data"><br></a>
<h3>
  Data maintenance
</h3>
<p>
  Here you can <strong>edit and extend the database</strong>. Details in the manual of <a href="datadmin.php"><strong>Data maintenance</strong></a>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="help" id="help"><br></a>
<h3>
  Help
</h3>
<p>
  Here you find this <strong>Help</strong>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="info" id="info"><br></a>
<h3>
  Info
</h3>
<p>
  Here you find information about program version, license, copyright and credits.<br>
  You get also the requirements of the client and the server and introductions for installation and configuration of OpenHomeopath.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span><br>
<?php
include("help/layout/$skin/footer.php");
?>
