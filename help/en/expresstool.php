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
  Expresstool Manual
</h1>
<ol>
  <li>Wähle die <strong>Quelle</strong> aus. Wenn es die Quelle noch nicht gibt, musst du sie erst in die <a href="../../datadmin.php?function=show_insert_form&amp;table_name=sources">Datenbank einfügen</a>.</li>
  <li>Wähle die <strong>Hautrubrik</strong> aus, zu der die Symptome gehören.
  Wenn es die Hauptrubrik noch nicht gibt, kannst du sie gegebenenfalls in die <a href="../../datadmin.php?function=show_insert_form&amp;table_name=rubriken">Datenbank einzufügen</a>.</li>
  <li>Gib im <strong>Textfeld</strong> die Symptom-Mittel-Datensätze folgendermaßen ein:
  <ul>
    <li>Jeder <strong>Symptom-Mittel-Datensatz</strong> kommt in eine eigene Zeile. Innerhalb eines Datensatzes darf es keinen Zeilenumbruch geben.</li>
    <li>Erst wird das <strong>Symptom</strong> inklusiv Unterrubriken angegeben. Die einzelnen Unterrubriken bitte mit einem "<strong>></strong>" voneinander trennen.</li>
    <li>Um nicht alle Unterrubriken immer neu eingeben zu müssen kannst du eine <strong>Kurzform</strong> verwenden:
    " <strong>></strong> " am Anfang einer Symptoms hängt das Symptom an die vorhergehende Rubrik an. Mit " <strong>>></strong> " am Anfang springt es eine Rubrik zurück, mit " <strong>>>></strong> " 2 Rubriken usw..</li>
    <li>Bei Büchern kannst du die <strong>Seitenzahl</strong> mit " <strong>s.</strong> " + Seitenzahl (z.B. "<strong>s.123</strong>") angeben.</li>
    <li>Ein <strong>Künzli-Punkt</strong> für das Symptom wird mit <strong>@</strong> hinzugefügt.</li>
    <li>Text in runden Klammern () wird getrennt vom Symptom als Zusatzinformation in der Symptome-Quellen-Tabelle abgespeichert.</li>
    <li>Danach kommt ein Doppelpunkt ("<strong>:</strong>") und dann die <strong>Mittelliste</strong>.</li>
    <li>Die einzelnen <strong>Mittel-Abkürzungen</strong> werden mit Kommas ("<strong>,</strong>") getrennt. Strichpunkte ("<strong>;</strong>") oder Leerzeichen (" ") sind auch als Trennzeichen möglich. Punkte ("<strong>.</strong>") am Ende der Abkürzung werden nicht berücksichtigt, können also weggelassen werden. Groß-/Kleinschreibung wird nicht berücksichtigt.</li>
    <li>Nach jedem Mittel wird mit Bindestrich ("<strong>-</strong>") die <strong>Wertigkeit</strong> von <span class='nobr'><strong>1 - 5
    </strong></span> angehängt. Also <span class='nobr'>"<strong>-1</strong>"</span>, <span class='nobr'>"<strong>-2</strong>"</span>, <span class='nobr'>"<strong>-3</strong>"</span>, <span class='nobr'>"<strong>-4</strong>"</span> oder <span class='nobr'>"<strong>-5</strong>"</span>. Wenn keine Wertigkeit angegeben wird, wird von einer <strong>einfachen Wertigkeit</strong> ausgegangen (entspricht  <span class='nobr'>"<strong>-1</strong>"</span>).</li>
    <li>Du kannst hinter jedem Mittel den Status der Mittelprüfung mit Hilfe eines Symbols angeben.<br>
	In der folgenden Liste findest du erst das Symbol und dahinter die Erklärung des Status:
    <ul style='list-style-type:none'>
<?php
$query = "SELECT status_symbol, status_de FROM sym_status WHERE status_id != 0";  // Status ermitteln
$db->send_query($query);
while($status = $db->db_fetch_row()) {
	$statussymbol = $status[0];
	$statusname = $status[1];
	echo "      <li>\" <strong>$statussymbol</strong> \" : &nbsp;$statusname</li>\n";
}
$db->free_result();

?>
    </ul></li>
    <li>Ein <strong>Künzli-Punkt</strong> für das Mittel wird mit <strong>@</strong> hinzugefügt.</li>
    <li><strong>Referenzquellen</strong> können vom Mittel mit " <strong>#</strong> " getrennt angegeben werden. Mehrere Referenzquellen werden auch mit " <strong>#</strong> " voneinander getrennt.</li>
    <li>Mittel die aus <strong>nicht klassischen Prüfungen</strong> (z.B. Traumprüfungen) werden in " <strong>{ }</strong> " eingeschlossen an die Mittelliste angehängt.</li>
    <li><strong>Beispiel</strong> mit 2 Symptomen:<br>Manie > anfallsweise: carc,cic,diosm,kali-i,nat-m-2,nat-s,stram,tarent-2,tub<br>Farben > schwarz > Flecken > Kopfschmerz > während: glon-2,meli-3,psil</li>
  </ul></li>
  <li>Wenn eine angegebene <strong>Mittel-Abkürzung</strong> in der Datenbank <strong>nicht gefunden</strong> wurde, wird ein Fehler ausgegeben und der entspechende Datensatz erscheint <strong>zur Korrektur</strong> im Textfeld. überprüfe, ob kein <strong>Rechtschreibfehler</strong> vorliegt, ansonsten überprüfe mit Hilfe der <a href='../../datadmin.php?function=show_search_form&amp;table_name=remedies'>Datenpflege-Suchfunktion</a>, ob in der Mittel-Tabelle eine <strong>andere Abkürzung</strong> verwendet wird. In diesem Fall kannst du die alternative Abkürzung in der <a href='../../datadmin.php?function=show_insert_form&amp;table_name=rem_alias'>Alias-Tabelle hinzufügen</a>. Es wird dann automatisch die richtige Abkürzung in die Datenbank eingetragen. Die Mittel-Datenbank enthält mit über <strong>3600 Einträgen</strong> so gut wie alle homöopathischen Mittel (Fehler in der Mittel-Tabelle bitte im <a href="../../homeophorum.php?list,7">Supportforum</a> melden!). Wenn du wirklich ein neues Mittel hast, kannst du das Mittel in die <a href='../../datadmin.php?function=show_insert_form&amp;table_name=remedies'>Mittel-Tabelle einfügen</a>. Bitte das Mittel gegebenenfalls im Textfeld <strong>korrigieren</strong> und Formular <strong>nochmal abschicken</strong>!</li>
  <li>Wenn die angegebene <strong>Wertigkeit, Status oder Künzli-Punkt</strong> nicht mit der Angabe  der entsprechenden Symptom-Mittel-Beziehung <strong>in der Datenbank übereinstimmt</strong> und der Datenbankeintrag von dir stammt, wird er mit dem angegebenen Wert aktualisiert. Ansonsten bleibt die Original-Eintrag erhalten. Um eine Änderung rückgängig zu machen, kannst du entweder den Datensatz mit der korrekten Anabe nochmal eingeben oder du korrigierst den Datensatz in der <a href="../../datadmin.php?table_name=sym_rem">Datenpflege</a>.</li>
  <li><strong>Mittelaliase</strong> können auch direkt über das Expresstool in die Datenbank eingefügt werden:
  <ul>
    <li>Der Alias ist eine Mittel-Abkürzung, die alternativ zur offiziellen Abkürzung verwendet werden kann.</li>
    <li>Jede Aliaszuweisung kommt in eine eigene Zeile. Einem Mittel können bei einer Zuweisung auch mehrere kommagetrennte Aliase zugewiesen werden.</li>
    <li>Der Syntax einer Aliaszuweisung lautet: <em>"alias: Mittel-Abkürzung = Alias-Abkürzung [,Alias-Abkürzung,...]"</em></li>
    <li>Bei den Alias-Abkürzungen ist die Schreibweise wichtig, da sie so in die Datenbank übernommen werden. In den meisten Fällen sollten die Abkürzungen mit einem Punkt enden. Der erste Buchstabe wird automatisch in einen Großbuchstaben umgewandelt.</li>
    <li>Beispiel: <em>"alias: Iod. = Jod."</em></li>
  </ul></li>
  <li><strong>Quellen</strong> können auch direkt über das Expresstool in die Datenbank eingefügt werden:
  <ul>
    <li>Jede Quellenangabe kommt in eine eigene Zeile.</li>
    <li>Der Syntax einer Quelldefinition lautet: <em>"Schlüsselwort: Quelle-ID#Autor#Titel#Jahr#Sprache#Maximale Wertigkeit[#Art der Quelle]"</em></li>
    <li>Das Schlüsselwort ist entweder '<strong>source</strong>' für Hauptquellen die in die Datenbank übertragen werden sollen oder '<strong>ref</strong>' für Referenzquellen, auf die in den Hauptquellen verwiesen wird.</li>
    <li><strong>Quelle-ID</strong> ist der Identifikator. Er kann aus Buchstaben und Zahlen bestehen und maximal 12 Zeichen umfassen.</li>
	<li><strong>Autor:</strong> Den Autor der Quelle angeben.</li>
	<li><strong>Titel:</strong> Den Titel der Quelle angeben.</li>
	<li><strong>Jahr:</strong> Das Erscheinungsjahr in der Form '<strong>1902</strong>', '<strong>1783-1785</strong>' oder '<strong>1988-</strong>' angeben</li>
	<li><strong>Sprache:</strong> Das Kürzel der Sprache, das in der Sprachen-Tabelle in der Datenbank eingetragen ist (z.B. '<strong>de</strong>' für deutsch, '<strong>en</strong>' für englisch, etc.). Wenn es die Sprache in der Sprachen-Tabelle noch nicht gibt, muss sie erst in die <a href="../../datadmin.php?function=show_insert_form&amp;table_name=sprachen">Datenbank eingetragen werden</a>.</li>
	<li><strong>Maximale Wertigkeit:</strong> Die maximale Wertigkeit, die in der Quelle benutzt wird. Eine Zahl von <strong>1-5</strong>.</li>
	<li><strong>Art der Quelle:</strong> Folgende Angaben sind möglich:
    <ul>
	  <li><strong>Repertorium</strong></li>
	  <li><strong>Materia Medica</strong></li>
	  <li><strong>Publikation</strong></li>
	  <li><strong>Arzneimittelprüfung</strong></li>
	  <li><strong>Klinischer Fall</strong></li>
	  <li><strong>Autor</strong></li>
	  <li><strong>Sonstiges</strong></li>
	</ul>
	Wenn keine Angabe erfolgt wird '<strong>Repertorium</strong>' in die Datenbank eingetragen.</li>
  </ul></li>
</ol>
<?php
if (empty($_GET['popup'])) {
	include("help/layout/$skin/footer.php");
}
?>
