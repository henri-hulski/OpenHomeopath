<?php

/**
 * help/en/datadmin.php
 *
 * The English manual for Datadmin - the database administration tool we use in OpenHomeopath.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   Datadmin
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = "Datadmin :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Data maintenance, Datadmin";
include("help/layout/$skin/header.php");
?>
<h1>
  OpenHomeopath Manual
</h1>
<h2>
  Data maintenance
</h2>
<div>
  With Datadmin, our data maintenance tool you can <strong>edit and extend the database of OpenHomeopath</strong>.<br>
  For using Datadmin you've first to <a href="user.php">log in</a>.<br>
  You can choose the <strong>database table for editing</strong> in a drop-down menue.<br>
  You can view and edit the <strong>following tables</strong>:
  <ul>
    <li><strong><em>Materia Medica</em></strong> &ndash; contains the remedy descriptions of the <strong>Materia Medica</strong> with <strong>source references</strong>.</li>
    <li><strong><em>Symptoms</em></strong> &ndash; contains the <strong>symptoms</strong> and their relations among each other.</li>
    <li><strong><em>Sources</em></strong> &ndash; contains the  <strong>sources</strong> with a <strong>detailed description</strong>.</li>
    <li><strong><em>Remedies</em></strong> &ndash; contains the <strong>remedies</strong>.</li>
    <li><strong><em>Main rubrics</em></strong> &ndash; contains the <strong>main rubrics</strong>.</li>
  </ul>
</div>
<p>
  I will give you a <strong>detailed description</strong> of <strong>each table</strong>:
</p>
<br>
<div class="content">
  <h2>
    Inhalt
  </h2>
  <ul>
    <li><a href="#medica">Table Materia Medica</a></li>
    <li><a href="#symptoms">Table Symptoms</a></li>
    <li><a href="#sources">Table Sources</a></li>
    <li><a href="#remedies">Table Remedies</a></li>
    <li><a href="#mainrubrics">Table Main Rubrics</a></li>
    <li><a href="#overview">Layout of Datadmin</a></li>
    <li><a href="#edit">Edit and insert of records</a></li>
  </ul>
</div>
<a name="medica" id="medica"><br></a>
<h3>
  Table Materia Medica
</h3>
<div>
  In the table <strong><em>Materia Medica</em></strong> you find the following fields:
  <ul>
    <li><strong><em>Remedy</em></strong> &ndash; you can choose the remedy from a drop-down list related to the Remedies table.</li>
    <li><strong><em>Source</em></strong> &ndash; you can choose the source of the materia medica from a drop-down list related to the Sources table.</li>
    <li><strong><em>Details</em></strong> about preparation, origin and synonyms of the remedy.</li>
    <li><strong><em>Description</em></strong> &ndash; for a detailed description of the remedy in general.</li>
    <li><strong><em>Related remedies</em></strong>, <strong><em>Incompatible remedies</em></strong> and <strong><em>Antidotes</em></strong> &ndash; as abbreviations separated by semicolon (';').</li>
    <li><strong><em>Leading symptoms</em></strong> in the categories <strong><em>Genarel</em></strong>, <strong><em>Mind</em></strong> and <strong><em>Body</em></strong> &ndash; for a detailed description of the leading symptoms.</li>
  </ul>
  The fields <strong>Remedy</strong> and <strong>Source</strong> are mandatory. <strong>The other fields</strong> only show up in the <strong>Materia Medica</strong>, when they're filled in.
</div>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="symptoms" id="symptoms"><br></a>
<h3>
  Table Symptoms
</h3>
<div>
  The table <strong><em>Symptoms</em></strong> contains 3 mandatory fields:
  <ul>
    <li><strong><em>Symptom</em></strong> &ndash; the symptom description,</li>
    <li><strong><em>Main rubric</em></strong> &ndash; here you can choose the main rubric from a drop-down list,</li>
    <li><strong><em>Language</em></strong> &ndash; here you can choose the language from a drop-down list.</li>
  </ul>
</div>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="sources" id="sources"><br></a>
<h3>
  Table Sources
</h3>
<div>
  The table <strong><em>Sources</em></strong> contains 6 mandatory fields:
  <ul>
    <li><strong><em>Source ID</em></strong> &ndash; a <strong>short name</strong> for the source. Up to 12 alphanumeric characters are allowed.</li>
    <li><strong><em>Title</em></strong> &ndash; the title of the source.</li>
    <li><strong><em>Language</em></strong> &ndash; choose from a drop-down list,</li>
    <li><strong><em>Source type</em></strong> &ndash; choose from a drop-down list,</li>
    <li><strong><em>Maximum grade</em></strong> &ndash; choose the maximum grade that is used in the source,</li>
    <li><strong><em>Primary source</em></strong> &ndash; 1: Primary source, 0: Reference source.</li>
  </ul>
  The next <strong>10 fields</strong> are self explaining and will only show up if they're completed:
  <ul>
    <li><strong><em>Author</em></strong></li>
    <li><strong><em>Year</em></strong></li>
    <li><strong><em>Edition/Version</em></strong></li>
    <li><strong><em>Copyright</em></strong></li>
    <li><strong><em>License</em></strong></li>
    <li><strong><em>URL</em></strong></li>
    <li><strong><em>ISBN</em></strong></li>
    <li><strong><em>Note</em></strong></li>
    <li><strong><em>Contact address</em></strong></li>
    <li><strong><em>Remedy proving</em></strong> &ndash; if the source is a remedy proving provide details.</li>
  </ul>
</div>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="remedies" id="remedies"><br></a>
<h3>
  Table Remedies
</h3>
<div>
  The table <strong><em>Remedies</em></strong> contains <strong>2 mandatory fields</strong>:
  <ul>
    <li><strong><em>Abbreviation</em></strong> &ndash; the common remedy abbreviation,</li>
    <li><strong><em>Remedy name</em></strong> &ndash; the full remedy name.</li>
  </ul>
</div>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="mainrubrics" id="mainrubrics"><br></a>
<h3>
  Table Main Rubrics
</h3>
<div>
  In the table <strong><em>Main Rubrics</em></strong> you find the <strong>following fields</strong>:
  <ul>
    <li><strong><em>Main Rubrik (German)</em></strong>,</li>
    <li><strong><em>Main Rubrik (English)</em></strong>,</li>
    <li><strong><em>Synonym</em></strong> &ndash; an existing synonymous main rubric.</li>
  </ul>
</div>
<p>
  <span class="boldtext red">Warning!</span> &nbsp; You should <strong>change the main rubric</strong> only after consulting the forum or an administrator, because the main rubrics are creating the basic structure of the rubrics database.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="overview" id="overview"><br></a>
<h3>
  Layout of Datadmin
</h3>
<p>
  On the <strong>main page of each table</strong> you find a list of <strong>existing records</strong>. You can choose how many records per page will be shown.
</p>
<div>
  At the beginning of each table row you find <strong>3 clickable icons</strong>:
  <ul>
      <li><img alt=""  src="../../<?php echo(EDIT_ICON);?>" width="16" height="16"> &ndash; for <strong>editing the record</strong>. If you aren't administrator you can only edit your own records. For details see  <a href="#edit">Edit and insert of records<strong></strong></a>.</li>
      <li><img alt=""  src="../../<?php echo(DELETE_ICON);?>" width="16" height="16"> &ndash; for <strong>deleting the record</strong>. If you aren't administrator you can only delete your own records.</li>
      <li><img alt=""  src="../../<?php echo(DETAILS_ICON);?>" width="16" height="16"> &ndash; for the <strong>details view</strong>.</li>
  </ul>
</div>
<div>
  In the <strong>menue bars</strong> above and below the table you find the <strong>following items</strong>:
  <ul>
    <li><strong><em>"Home"</em></strong> &ndash; return to the last view of the table main page.</li>
    <li><strong><em>"Insert"</em></strong> &ndash; insert new records in the table. For details see <a href="#edit">Edit and insert of records</a>.</li>
    <li><strong><em>"Search"</em></strong> &ndash; searching the table with search filters for each field.</li>
    <li><strong><em>"Last search results"</em></strong> &ndash; return to the last search results.</li>
    <li><strong><em>"Show all"</em></strong> &ndash; show all records without filters.</li>
    <li><strong><em>"Archive"</em></strong> &ndash; where changed and deleted records are archived and can be restored.</li>
    <li><strong><em>"Express-Tool"</em></strong> &ndash; switch to the <a href="expresstool.php">Express-Tool</a>.</li>
  </ul>
  In the table <strong><em>Remedies</em></strong> you've also the possibility to select the records by the <strong>first letter of the remedy abbreviation</strong>.<br>
  In the table <strong><em>Symptoms</em></strong> you can select records by <strong>main rubric</strong>.
</div>
<p>
  With <strong><em>"Export to CSV"</em></strong> you can export the current record selection to a semicolon seperated <strong>CSV-file</strong>.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span>
<a name="edit" id="edit"><br></a>
<h3>
  Edit and insert of records
</h3>
<p>
  You can reach the <strong>edition form</strong> from the <img alt=""  src="../../<?php echo(EDIT_ICON);?>" width="16" height="16"><strong>-Symbol</strong> in front of each table row and the insert form from <strong><em>"Insert"</em></strong> in the <strong>menue bar</strong>.<br>
  The difference between the two form is, that the insert form is a blank form and in the edit form you find the data of the current record with the possibility to jump with <strong><em>"<< Previous"</em></strong> to the previous and with <strong><em>"Next >>"</em></strong> to the next record.
</p>
<p>
  <strong>Mandatory fields</strong> are signed by an <strong>asterisk (*)</strong> before the field name.<br>
  There're 3 types of fields:
  <ul>
    <li><strong><em>single-line textfields</em></strong>,</li>
    <li><strong><em>multi-line textareas</em></strong> &ndash; line feeds are generally preserved,</li>
    <li><strong><em>drop-down lists</em></strong>.</li>
  </ul>
  You find <strong>hints to fill out the fields</strong> behind the fields.<br>
  <strong>Details for each table</strong> you find in the corresponding table chapter.
</p>
<br><span class="rightFlow"><a href="#up" title="To the top of the page"><img src="../../<?php echo(ARROW_UP_ICON);?>" alt="To the top of the page" border="0"></a></span><br>
<?php
include("help/layout/$skin/footer.php");
?>
