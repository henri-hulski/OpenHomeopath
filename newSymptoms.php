<?php

/**
 * newSymptoms.php
 *
 * Shows the last 100 inserted symptoms
 *
 * PHP version 5
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Homeopathy
 * @package   NewSymptoms
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

header("Content-Type: text/html;charset=utf-8"); 

include_once ("include/classes/login/session.php");
$skin = $session->skin;
include("skins/$skin/header.php");
?>
<h1>
  Die 100 letzten neu eingetragenen Symptom-Rubriken
</h1>
<p>Wie du Symptom-Rubriken eintragen kannst, ist in der Anleitung:<br><a href='help/<?php echo $lang; ?>/expresstool_tut.php'>Rubriken aus Büchern mit dem Expresstool eingeben</a> beschrieben</p><br><br>
<?php
$lang = DEFAULT_LANGUAGE;
$query = "SELECT DISTINCT main_rubrics.rubric_de, symptoms.sym_id, symptoms.symptom, symptoms.username, UNIX_TIMESTAMP(symptoms.timestamp) FROM symptoms, main_rubrics WHERE main_rubrics.rubric_id = symptoms.rubric_id ORDER BY symptoms.timestamp DESC LIMIT 0,100";
	$db->send_query($query);


// syms from here
while($symptom = $db->db_fetch_row()) {
    $symsubarr['rubric'] = $symptom[0];
    $symsubarr['name'] = $symptom[2];
    $symsubarr['id'] = $symptom[1];
    $symsubarr['user'] = $symptom[3];
    $symsubarr['date'] = date(" d.m.y H:i", $symptom[4]);
    $symsubarr['type'] = "main";
    echo "<span style=\"font-size:13px;\">".$symsubarr['date']."    <a href=\"symptom-details.php?sym=".$symsubarr['id']."\" title=\"Symptom Info\"><img src=\"skins/kraque/img/info.png\" border=\"0\" height=\"14px\" alt=\"Symptom Info\" /></a>  <b>".$symsubarr['rubric']." > ".$symsubarr['name']."</b></span><br>";

}

$db->free_result();
include("skins/$skin/footer.php");
?>
