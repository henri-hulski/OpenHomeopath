<?php

/**
 * help/en/booksearch.php
 *
 * The English manual for the booksearch.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   BookSearch
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/openhomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$head_title = "Booksearch :: Help :: OpenHomeopath";
$meta_description = "OpenHomeopath Manual, Booksearch";
if (empty($_GET['popup'])) {
	$skin = $session->skin;
	include("help/layout/$skin/header.php");
}
?>

<h1>How to search for books</h1>

<p>Folgende Suchmöglichkeiten stehen zur Verfügung:</p>

<dl>
<dt><strong>Einfache Suche</strong>:</dt>
<dd>

<p>Gib einfach <strong>einen oder mehrere Suchbegriffe</strong> in das Suchfeld ein.<br /><br />
Möchtest Du irgendeinen der Suchbegriffe finden, schreibe <span class="blue"><strong>OR</strong></span> zwischen die Begriffe<br />
Bsp: <span class="blue">abies OR nigra</span><br />
findet alle Vorkommen von "abies" und alle von "nigra". <a href="books.php?searchTerm=abies+OR+nigra&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666">Test!</a>

</p>


<p>Außerdem kannst Du bestimmen, dass Bücher, in denen ein bestimmter oder bestimmte Begriffe vorkommen, nicht angezeigt werden. Für diese <strong>"ohne"-Suche</strong> gib ein <span class="blue">"<strong>-</strong>"</span> (Minus) vor dem Begriff ein. Innerhalb von Phrasen (siehe unten) ist eine "ohne"-Suche nicht möglich.<br /><br />
The following example searches for the second set of 10 books whose metadata or text matches the query term <b>abies</b> but doesn't match the query term <b>nigra</b> <div class="blue">abies -nigra</div>

</p>

</dd>

<dt><strong>Phrasensuche</strong>:</dt>
<dd>

<p>Für die Phrasensuche gib zwei oder mehr Wörter innerhalb von <i>Gänsefüßchen</i> ein.<br>
Bsp: <span class="blue">"tiefer Schlaf"</span><br>
findet Bücher mit der Phrase "tiefer Schlaf" <a href="books.php?searchTerm=&#34;tiefer Schlaf&#34;&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666">Test!</a>


</dd>

<dt><strong>Autoren Suche</strong>:</dt>
<dd>

<p>Bsp: <span class="blue">inauthor:"Constantine&nbsp;Hering"</span><br />
findet Bücher von "Constantine Hering"   
<a href="books.php?searchTerm=inauthor%3A&#34;Constantine+Hering&#34;&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666" >Test!</a><br /><br />

Bsp: <span class="blue">Constantine&nbsp;Hering&nbsp;-inauthor:"Constantine&nbsp;Hering"</span><br>
 findet "Constantine Hering" in Büchern die nicht von "Constantine Hering" sind. 
<a href="books.php?searchTerm=Constantine+Hering+-inauthor%3A&#34;Constantine+Hering&#34;&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666">Test!</a><br /><br />

Bsp: <span class="blue">belladonna&nbsp;inauthor:"Ernst&nbsp;Stapf"</span><br>
 findet "belladonna" in Büchern von "Erns&nbsp;Stapf". 
<a href="books.php?searchTerm=belladonna+inauthor%3A&#34;Ernst+Stapf&#34;&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666">Test!</a><br /><br />
Bsp: <span class="blue">"fever&nbsp;sweat"&nbsp;inauthor:phatak</span><br>
 findet "fever&nbsp;sweat" in Büchern von "phatak". 
<a href="books.php?searchTerm=&#34;fever+sweat&#34;+inauthor%3Aphatak&queryType=all&category=all&maxResults=10&oder=1" style="text-decoration:underline;color:#666666">Test!</a>


</p>
</dd>

<dt><strong>Titel Suche</strong>:</dt>
<dd>

<p>Bsp: <span class="blue">intitle:organon</span><br />
findet Bücher mit dem Titel "organon"   
<a href="books.php?searchTerm=intitle%3Aorganon&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666" >Test!</a><br /><br />

Bsp: <span class="blue">apis&nbsp; intitle:"materia&nbsp;medica"</span><br>
 findet "apis" in Büchern mit der Phrase "materia&nbsp;medica" im Titel. 
<a href="books.php?searchTerm=apis+intitle%3A&#34;materia+medica&#34;&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666">Test!</a><br /><br />

Bsp: <span class="blue">belladonna&nbsp;-inauthor:"hahnemann"&nbsp;intitle:"Reine&nbsp;Arzneimittellehre"</span><br>
 findet "belladonna" in Büchern mit der Phrase "Reine Arzneimittellehre" im Titel die nicht von "hahnemann" stammen. 
<a href="books.php?searchTerm=belladonna+-inauthor%3A&#34;hahnemann&#34;+intitle%3A&#34;Reine+Arzneimittellehre&#34;&queryType=all&category=all&maxResults=10" style="text-decoration:underline;color:#666666">Test!</a>


</p>
</dd>




</dl>

<?php
if (empty($_GET['popup'])) {
	include("help/layout/$skin/footer.php");
}
?>
