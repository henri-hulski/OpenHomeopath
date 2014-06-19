<?php

/**
 * mm-include/functions_symptoms.php
 *
 * Symptom functions that are be used by the symptom-details.php script from Thomas Bochmann
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
 * @package   SymptomFunctionsMm
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

function get_select_all_rubric()
{
	global $translations, $lng, $db;
	$rubric_select = '<select name="rubric" style="font-size:12px;font-weight:normal;margin:2px;" onchange="document.repform.submit()" class="drop-down2">';
	$rubric_id = 0;
	if (!empty($_REQUEST['rubric'])) {
		$rubric_id = $_REQUEST['rubric'];
		if ($rubric_id == -1) {
			$aktuelle_rubric = $translations['Repertory_all_rubrics'];
		} else {
			$query = "SELECT rubric_$lng FROM main_rubrics WHERE rubric_id = $rubric_id";
			$db->send_query($query);
			list ($aktuelle_rubric) = $db->db_fetch_row();
			$db->free_result();
		}
	}
	if (!empty($rubric_id)) {
		$rubric_select .= "          <option value='$rubric_id' selected='selected'>$aktuelle_rubric</option>\n";
	}
	$rubric_select .= "          <option value='-1' style='font-weight: bold'>$translations[Repertory_all_rubrics]</option>\n";
	$query = "SELECT DISTINCT main_rubrics.rubric_id, main_rubrics.rubric_$lng FROM main_rubrics, symptoms WHERE main_rubrics.rubric_id = symptoms.rubric_id ORDER BY main_rubrics.rubric_$lng";
	$db->send_query($query);
	while($rubric = $db->db_fetch_row()) {
		$rubric_select .="          <option value='$rubric[0]'>$rubric[1]</option>\n";
	}
	$db->free_result();
	$rubric_select .= "</select>";
	return $rubric_select;
}

function get_symptoms_by_letter_page_nav($letter,$s_count,$limit,$start)
{
global $rubric_id, $lng;
$pages = round(($s_count+$limit), -2)/$limit;
    $nav = "<span>";
    for ($i=1;$i<=$pages;$i++)
    {
        if ((($i-1)*$limit)==$start){
            $nav = $nav." ".$i." ";
        }else{
            $nav = $nav."<a href=\"symptom-details.php?letter=".$letter."&start=".(($i-1)*$limit)."&rubric=".$rubric_id."&lang=".$lng."#Symptome\"> ".$i." </a>";
        }
    }
    $nav = $nav. "</span><br>";
    return $nav;

}

function get_symptoms($letter, $where_query, $start, $limit)
{
    global $db;
    global $lng;
    $select = "SELECT main_rubrics.rubric_$lng, symptoms.symptom, symptoms.sym_id, symptoms.lang_id, main_rubrics.rubric_id FROM symptoms, main_rubrics WHERE symptoms.rubric_id = main_rubrics.rubric_id ";
    $query = $select.$where_query;
    $query = $query . "ORDER BY main_rubrics.rubric_$lng, symptoms.symptom";
    $query = $query . " LIMIT $start , $limit ";
    $db->send_query($query);
    while ($rubric_info = $db->db_fetch_assoc()){
        $sym_arr['rubric'][$rubric_info['rubric_id']][]=$rubric_info;
    }
    $db->free_result();
    $select = "SELECT COUNT(*) FROM symptoms, main_rubrics WHERE symptoms.symptom LIKE '$letter%' AND symptoms.rubric_id = main_rubrics.rubric_id ";
    $query = $select.$where_query;
    $db->send_query($query);
     while ($count = $db->db_fetch_row()) {
        $sym_arr['s_count'] = $count[0];
    }
    $db->free_result();

    return $sym_arr;
}

function view_repertory($symptom_arr, $letter,$limit,$start)
{
        $html = "<div class='mm-info-box'>\n";
        $html .= view_repertory_head($letter, $symptom_arr);
        if(!empty($symptom_arr)){
            if($symptom_arr['s_count'] > $limit){
                $html .= "<div class='mm-info-box-part-title'>".get_symptoms_by_letter_page_nav($letter,$symptom_arr['s_count'],$limit,$start)."</div>\n";
            }
            $html .= view_repertory_symptoms_tree($symptom_arr);
            if($symptom_arr['s_count'] > $limit){
                $html .= "<div class='mm-info-box-part-title'>".get_symptoms_by_letter_page_nav($letter,$symptom_arr['s_count'],$limit,$start)."</div>\n";
            }
        }
        $html .= "</div>\n";

    return $html;
}

function view_repertory_head($letter, $symptom_arr)
{
    global $grade, $translations;
    $html = "  <div class='mm-info-box-repertory'>\n";
    $html .= "    <form name='repform' action='symptom-details.php'>\n";
    $html .= "      <span class='mm-info-box-source-title'>$translations[General_repertory] <span style='font-size:0.7em'><b>".$letter."</b></span></span>\n";
    $html .= "      <input name=\"letter\" id=\"letter\" type=\"hidden\" value=\"".$letter."\"  >".get_select_all_rubric()."\n";
    $html .= "    </form>\n";
    $html .= "    <b>".$symptom_arr['s_count']."</b> $translations[entries_with_grade] <b>&ge;".$grade."</b>\n";
    $html .= "  </div>\n";
    return $html;
}

function view_repertory_symptoms_tree($symptoms_arr)
{
    global $lng;
    $html = "<div class='mm-info-box-rubric'>";
    $parts_count = 0;
    foreach($symptoms_arr['rubric'] as $rubric_id=>$symptoms){
    foreach($symptoms as $skey=>$symptom){
        $parts_arr= explode(" > ", $symptom['symptom']);
        if(count($parts_arr) > $parts_count){
            $parts_count = count($parts_arr);
        }
        foreach($parts_arr as $key=>$part){
            $symptoms_arr['rubric'][$rubric_id][$skey]['parts'][$key] = $part;
            $tree_arr[$symptom['rubric_id']][$key][$part][$symptom['sym_id']]=$skey;
        }
    }
    }
    foreach($symptoms_arr['rubric'] as $rubric_id=>$symptoms){
        foreach($symptoms as $skey=>$symptom){
            $i = $parts_count-1;
            for (; ; ) {
                if ($i ==0) {
                if(empty($symptoms_arr['rubric'][$rubric_id][$skey]['parts'][$i+1])){
                    $symptoms_arr2['rubric'][$rubric_id][$skey]['sym_id']=$symptom['sym_id'];
                    $tree[$symptoms_arr['rubric'][$rubric_id][$skey]['parts'][0]] = $skey;
                    $symptoms_arr2['rubric'][$rubric_id][$skey]['symptom'] =$symptom['symptom'];
                }
                    break;
                }
                if(isset($symptoms_arr['rubric'][$rubric_id][$skey]['parts'][$i]) &&$symptoms_arr['rubric'][$rubric_id][$skey]['parts'][$i-1] == $symptoms_arr['rubric'][$rubric_id][$skey-1]['parts'][$i-1] ){
                    $symptoms_arr2['rubric'][$rubric_id][$skey-1]['parts'][$i]['children'][$symptom['sym_id']]['symptom'] = $symptoms_arr['rubric'][$rubric_id][$skey]['parts'][$i];
            echo "<br><br>";
                }
                $i=$i-1;
            }
        }
    }
    echo "<br><br>";
    $html = $html."</div>";
    return $html;
}
