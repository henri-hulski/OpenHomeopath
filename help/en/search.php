<?php

/**
 * help/en/search.php
 *
 * The English manual for the symptom search.
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
$head_title = "Symptom search :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Symptom search";
if (empty($_GET['popup'])) {
	$skin = $session->skin;
	include("help/layout/$skin/header.php");
}
?>

<h1>How to search for symptoms</h1>

<p>You can use the following search capabilities:</p>

<dl>
<dt><strong>Simple search</strong>:</dt>
<dd>

<p>Insert one or several search items into the search field.<br>
The search is not case-sensitive.</p>

<p>You can choose between</p>
<ul>
  <li><strong>"and"-search:</strong> the symptom must contain all search items.</li>
  <li><strong>"or"-search:</strong> the symptom must contain at least one search item.</li>
</ul>
<p>and also between</p>
<ul>
  <li>Searching for <strong>whole words</strong></li>
  <li>Searching for <strong>parts of words</strong></li>
</ul>

<p>You can select the search mode using the radio buttons.</p>

<p>If you want, that the symptom doesn't contain a certain word use the <span class="blue">"<strong>-</strong>"</span> sign before the item (without space).</p>
<p>With the whole word search you can use the <span class="blue">" <strong>*</strong> "</span> sign as a wildcard for any number of chars (also none).<br>
In the whole word search words with less than 4 chars will be left out.</p>
<p>Advanced: With the search for parts of words you can use <a href='http://dev.mysql.com/doc/refman/5.1/en/regexp.html' target='_blank'>regular expressions</a>.</p>

</dd>

<dt><strong>Phrase Search</strong>:</dt>
<dd>

<p>Enclose multi-word phrases in quotation marks, e.g. <span class="blue">"deep sleep"</span>.</p>

<p>Punctuation marks will be ignored.</p>

</dd>
</dl>

<?php
if (empty($_GET['popup'])) {
	include("help/layout/$skin/footer.php");
}
?>
