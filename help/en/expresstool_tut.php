<?php

/**
 * help/en/expresstool_tut.php
 *
 * The English tutorial for the Expresstool.
 *
 * LICENSE: Permission is granted to copy, distribute and/or modify this document
 * under the terms of the GNU Free Documentation License, Version 1.3
 * or any later version published by the Free Software Foundation;
 * with no Invariant Sections, no Front-Cover Texts, and no Back-Cover Texts.
 * A copy of the license is included in doc/en/fdl_1.3.php.
 *
 * @category  Manual
 * @package   ExpresstoolTutorial
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/fdl.html GNU Free Documentation License v1.3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

chdir("../..");
include_once ("include/classes/login/session.php");
$head_title = "Expresstool :: Tutorial :: OpenHomeopath";
$meta_description = "How to insert rubrics from books with the Expresstool";
$meta_keywords = "Express Tool";
$skin = $session->skin;
include("help/layout/$skin/header.php");
?>

		<style type="text/css" media="all"><!--
			div.image {}
			div.story {font-family:arial,sans-serif;}
			.synth {
			font-family:arial,sans-serif;
			background-color: #fffbdb;
			border: 10px solid #e0ccd6;
			padding: 10px;
			}
			span.synth {
			border: 1px solid #660033;
			padding: 1px 6px 1px 6px;
			color: #660033;
			}
			.ohp {
			font-family:arial,sans-serif;
			background-color: #fffdee;
			border: 10px solid #cce6d4;
			padding: 10px;
			}
			span.ohp {
			border: 1px solid #008029;
			padding: 1px 6px 1px 6px;
			}
			li {
			border: 0px solid #808080;
			//padding: 1px 6px 1px 6px;
			margin: 10px;
			}
			img.ohp {
			font-family:arial,sans-serif;
			background-color: #fffdee;
			border: 10px solid #cce6d4;
			padding: 0px;
			}
			h2{text-align:left;}
		--></style>
<h1>How to insert rubrics from books with the Expresstool</h1>
				<p>Um Rubriken und ihre Mittel aus gedruckten Repertorien schnell eingeben zu können, gibt es bei OpenHomeopath das Expresstool mit einem einfachen Eingabeschema.</p>
				<h2>Ich will an einem kleinen Beispiel kurz erklären, wie das gemacht wird.</h2>
		<div id="express-tut" style="line-height:1.4em;background-color: #fff;padding: 20px;border: 2px solid #BDC9A3;">
			<div class="story">
				
				
				<h3 style ="font-family: Times, serif;font-size:22px;">&raquo;Nehmen wir an, deine Patientin bekommt Schnupfen durch Blumen.&laquo;</h3>
				<p>In deinem Synthesis 9.1 findest du im Kapitel Nase auf Seite 571 die passende Rubrik:</p>
				<div class="synth">
				<span style="color: #660033;" ><b>Schnupfen:</b></span> ...<br />
				&bull; <span style="color: #b27f99;" >Blumen,</span> durch (Gerüche - Rosen): <b>ALL-C</b><span style="font-size:0.8em">k</span><span style="color: #660033;" ><b>&bull;</b></span> sabad* sang*
				</div>
				<h3>Im Expresstool machst du folgendes:</h3>
				<p><b>1. Quelle auswählen = Synthesis 9.1</b></p>
				<img class="ohp" src="http://www.openhomeo.org/content/img/quelle-waehlen.gif" alt="Quelle waehlen" />
				<p><b>2. Hauptrubrik auswählen = Nase</b></p>
				<img class="ohp" src="http://www.openhomeo.org/content/img/hauptrubrik-waehlen.gif" alt="Hauptubrik waehlen" />
				<p><b>3. Die Symptom-Rubrik nach folgendem Schema eingeben</b></p>
				<p class="ohp">s. [Seitentzahl] [Symptom Rubrik]:[Mittel-Kürzel]-[Mittel-Wertigkeit][Mittel-Status]#[Mittel-Referenz], [Mittelkürzel-2] ...</p>
				<p>das sieht dann im Express-Tool so aus:</p>
				<div class="ohp">
				<p>s. 571 Schnupfen &gt; Blumen, durch (Gerüche - Rosen): all-c-4@#k,sabad-1*,sang-1*</p></div>
				<h3>Erläuterung</h3>
				Als Erstes wird die Symptom-Rubrik beschrieben<br />
				<b>Zur Symptom-Rubrik gibt es 5 Details:</b>
				<ol style="list-style-type:decimal">
				    <li><b>Seite</b> (die Seite deines Buches, auf der die Rubrik steht)<br />
				    Schreibe: <span class="ohp">s.</span> für Seite, dann die Seitenzahl: <span class="ohp">571</span> </li>
				    <li><b>Symptom-Rubrik</b><br />
				        <i>wobei du vor jede Unterrubrik eine "schließende spitze Klammer" <span class="ohp">&gt;</span> als Trennung schreibst.</i><br />
				        im Synthesis werden Unterubriken 1-7 durch folgende Zeichen Abgegrenzt:<br />
				        <span class="synth"><b>&bull; , - , -- , &gt; , &raquo; , - , -- </b></span>      schreibe dafür jeweils: <span class="ohp">&gt;</span><br />
				        der durchbrochene Strich <span class="synth">&brvbar;</span>  ist im Synthesis ebenfalls ein Rubrik-Trenner, er wird verwendet, wenn die Elternrubrik keine Mittel enthält. Für dieses Zeichen schreibe auch: <span class="ohp">&gt;</span><br />
                        In unserem Beispiel kommt die spitze Klammer vor <b>Blumen</b>,<br />
				        das sieht dann so aus: <span class="ohp">Schnupfen &gt; Blumen, durch</span></li>
				        <li><b>Künzlipunkt</b> für die Symptom-Rubrik<br />
				        im Synthesis <span class="synth"><b>&bull;</b></span><br />
				        wenn angegeben, schreibe: <span class="ohp">@</span></li>
				    <li><b>Querverweise</b><br />
				        Ist ein Querverweis angegeben, schreibe die Rubriken in Klammern hinter die Symptom-Rubrik: <span class="ohp">(Gerüche - Rosen)</span></li>
				    <li><b>zum Abschluss</b> der Symptom-Rubrik<br />
				        schreibe einen Doppelpunkt: <span class="ohp">:</span></li>
				</ol>
				Darauf folgen alle Mittel-Angaben getrennt durch Kommas.
				<p><b>Zu jeder Mittel-Angabe gibt es 4 Details.</b></p>
				<ol style="list-style-type:decimal">
				    <li><b>Mittel-Kürzel</b><br /> du schreibst den Kurznamen des Mittels, wie er in deinem Buch steht <span class="ohp">all-c</span></li>
				<li><b>Mittel-Wertigkeit</b><br /> du schreibst ein Minus als Trenner <span class="ohp">-</span> und dann die Wertigkeit als Zahl <span class="ohp">4</span></li>
				<li><b>Mittel-Status</b><br /> es gibt 7 Statuszeichen
				    <ol>
				        <li><b>**</b> = mehrfach bestätigt</li>
				        <li><b>*</b> = bestätigt (im Synthesis = <span class="synth"><b>*</b></span> )</li>
				        <li><b>%</b> = neuere Prüfung (im Synthesis = <span class="synth"><b>&deg;</b></span> )</li>
				        <li><b>@</b> = Künzlipunkt (im Synthesis = <span class="synth"><b>&bull;</b></span> )</li>
				        <li><b>^</b> = aus Unterubrik (im Synthesis = <span class="synth"><b>^</b></span> )</li>
				        <li><b> __</b> = nicht bestätigt</li>
				        <li><b> _</b> = ungenügend bestätigt</li>
				        </ol>
				    Für unser Beispiel schreibe <span class="ohp">@</span> für den Künzli-Punkt welcher im Synthesis so dargestellt ist: <span class="synth">&bull;</span></li>
				<li><b>Die Mittel-Referenz</b><br /> ist eine Referenz angegeben, schreibe erst eine Raute als Trenner <span class="ohp">#</span> und dann das Kürzel der Quelle bzw des Autors, in unserem Fall <span class="ohp">k</span> was im Synthesis für Kent James Tyler steht.</li>
				</ol>
				<p>Dem Beispiel folgend hast du das 1. Mittel nun vollständig beschrieben: <span class="ohp">all-c-4@#k</span></p>
				<p>
				da noch zwei weitere Mittel in der Rubrik sind, fügst du die jetzt noch hinzu, wobei vor jedem weiteren Mittel ein Komma als Trenner zu schreiben ist.<br />Du schreibst also noch:<span class="ohp">,sabad-1*,sang-1*</span></p>
				<p>Jetzt drückst du den Button <span class="ohp">Abschicken</span></p>
				<p>danach werden dir zum Vergleich Dublikationsmöglichkeiten mit vorhandenen Rubriken angezeigt, zum Beispiel so:<br />
				<img class="ohp" src="http://www.openhomeo.org/content/img/duplizierung.gif" alt="Duplizierung" /><br /><br />
				Wenn du sicher bist, das die neue Rubrik noch nicht vorhanden ist, und du keinen Schreibfehler gemacht hast, drücke den Button <span class="ohp" >Trotzdem einfügen</span></p>
				<h3>Schon bist du FERTIG</h3>
				und kannst nun die Symptom-Rubrik in der Repertorisierung mit auswerten.
			</div>
			
		</div>
Have a look at <a href="../../newSymptoms.php">the last 100 inserted symptom rubrics</a>.
<?php
include("help/layout/$skin/footer.php");
?>