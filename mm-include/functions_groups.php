<?php

/**
 * mm-include/functions_group.php
 *
 * Group functions that are be used by the materia-medica.php script from Thomas Bochmann
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
 * @package   GroupFunctionsMm
 * @author    Thomas Bochmann <thomas.bochmann@gmail.com>
 * @copyright 2009 Thomas Bochmann
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

}

function get_rem_groups($title) {

    global $db;
    $query ="SELECT *  FROM `rem_groups` WHERE `title` = '$title'";
    $db->send_query($query);
    $i=0;
    $rem_group = $db->db_fetch_assoc();
    if($rem_group){
        return $rem_group;
    }else{
        return FALSE;
    }
}
function get_rem_groups_by_letter($letter)
{
    
    global $db;
    $query ="SELECT *  FROM `rem_groups` WHERE `title` LIKE '$letter%' ORDER BY title";
    $db->send_query($query);
    $i=0;
    while($group = $db->db_fetch_assoc()) {
        $group_arr[$group['id']] = $group;
    }
    if($group_arr){
        return $group_arr; 
    }else{
        return FALSE;
    }
}
function get_rem_groups_by_id($id)
{

    global $db;
    $query ="SELECT *  FROM `rem_groups` WHERE `id` = '$id'";
    $db->send_query($query);
    $i=0;
    $rem_group = $db->db_fetch_assoc();
    if($rem_group){
        return $rem_group;
    }else{
        return FALSE;
    }
}

function get_kraque_table2table($src_table, $relation_type_id, $target_table, $src_id=NULL, $target_id=NULL)
{
    global $db;
    $query ="SELECT * FROM `kraque__relations` WHERE `src_table` = '$src_table' AND `target_table` = '$target_table' AND `relation_type_id` = $relation_type_id";
    if($target_id != NULL){
        $query .=" AND `target_id` = $target_id";
    }
    if($src_id != NULL){
        $query .=" AND `src_id` = $src_id";
    }
    $db->send_query($query);
    $i=0;
    while ($relations = $db->db_fetch_assoc()) {
        $relations_arr[$i] = $relations;
        $i++;
    }
    if(isset($relations_arr)){
        return $relations_arr;
    }else{
        return FALSE;
    }
}
function get_rem_groups_searchform($group_id = "")
{
    $form = "<form name='searchform' action='materia-medica.php'><div style='position:relative;top:0;left:0;'><input id=\"query\" type=\"text\"  onkeyup=\"autosuggest('auto_all_groups')\" /><input name=\"group_id\" id=\"rem\" type=\"hidden\" value=\"".$group_id."\"  ><div id=\"results\"></div><div id=\"search_icon\"><img src=\"./skins/original/img/search.png\" width=\"24\" height=\"24\"></div></div></form>";
	return $form;
}

function get_group_repertory_symptoms($group,$remedy_ar, $where_query, $start, $limit)
{
	global $db;
	global $sym_rem;
	global $lng, $translations;
	global $min_in;
	$rubric_name = "rubric_".$lng;
	if(is_array($remedy_ar)){
		$i = 1;
		$where_remedy = "";
		foreach($remedy_ar as $rem_id=>$remedy){
			$where_remedy .= " $rem_id";
			if($i < count($remedy_ar)){
				$where_remedy .= ", ";
			}
			$i++;
		}
	}
	$select = "SELECT $sym_rem.rel_id, main_rubrics.rubric_de, main_rubrics.rubric_en,symptoms.symptom, $sym_rem.grade, $sym_rem.rem_id, symptoms.sym_id,  main_rubrics.rubric_id, sym_count_rem.count_rem FROM $sym_rem, symptoms, main_rubrics, sym_count_rem WHERE $sym_rem.rem_id IN ($where_remedy) AND $sym_rem.sym_id = symptoms.sym_id AND symptoms.rubric_id = main_rubrics.rubric_id AND sym_count_rem.sym_id = $sym_rem.sym_id ";
	$query = $select . $where_query;
	$query .= " group by $sym_rem.sym_id,$sym_rem.rem_id  ORDER BY main_rubrics.rubric_$lng, symptoms.symptom";
	$query .= " LIMIT $start , $limit ";
	$db->send_query($query);
	$ii=1;
	while ($rubric_info = $db->db_fetch_assoc()){
		$symptoms_arr[$rubric_info['sym_id']]['symptom'] = $rubric_info['symptom'];
		$symptoms_arr[$rubric_info['sym_id']]['sym_id'] = $rubric_info['sym_id'];
		$symptoms_arr[$rubric_info['sym_id']]['count_rem'] = $rubric_info['count_rem'];
		$symptoms_arr[$rubric_info['sym_id']]['rubric_de'] = $rubric_info['rubric_de'];
		$symptoms_arr[$rubric_info['sym_id']]['rubric_en'] = $rubric_info['rubric_en'];
		$symptoms_arr[$rubric_info['sym_id']]['remedy'][$rubric_info['rem_id']]['rem_id'] = $rubric_info['rem_id'];
		$symptoms_arr[$rubric_info['sym_id']]['remedy'][$rubric_info['rem_id']]['rem_short'] = $remedy_ar[$rubric_info['rem_id']]['rem_short'];
		$symptoms_arr[$rubric_info['sym_id']]['remedy'][$rubric_info['rem_id']]['grade'] = $rubric_info['grade'];
		$group['repertory']['rubrics'][$rubric_info['rubric_id']]['rubric_de']=$rubric_info['rubric_de'];
		$group['repertory']['rubrics'][$rubric_info['rubric_id']]['rubric_en']=$rubric_info['rubric_en'];
	}
	$db->free_result();
	$symptoms_arr = array_msort($symptoms_arr, array('count_rem'=>SORT_ASC, 'rubric_de'=>SORT_ASC, 'symptom'=>SORT_ASC));
	$html = "<div class='mm-info-box'>\n";
	$html .= view_group_repertory_head($group);
	$html .= "<div class='mm-info-box-rubric'>";
	foreach($symptoms_arr as $sym_id=>$symptom){
		$symptom['symptom'] = $symptom['symptom'];
		$count_rem = count($symptom['remedy']);
		if($count_rem >=$min_in){
			$html .= "<a href='./symptom-details.php?sym=".$sym_id."&lang=$lng' title='Symptom Info'><b><span class='grade1' >".$symptom[$rubric_name]."&nbsp;>&nbsp;".$symptom['symptom']."</span></b></a> <span style='font-size:0.7em;'>".$count_rem."/".$symptom['count_rem']."</span> ";
			foreach($symptom['remedy'] as $rem_id2=>$remedy2){
				$html .= "<span class=\"grade".$remedy2['grade']."\" >".$remedy_ar[$rem_id2]['rem_short']."</span> ";
			}
			$html .= "<br/>";
		}
	}
	$html = $html."</div>\n";
	$html = $html."</div>\n";
	return $html;
}

function view_group_repertory($group, $remedy_ar, $where_query, $start, $limit)
{
    global $grade;

        $html = $html."<div class='mm-info-box'>\n";
        $html = $html.view_group_repertory_head($group);
        $html = $html.get_group_repertory_symptoms($remedy_ar, $where_query, $start, $limit);
        $html = $html."</div>\n";
    return $html;
}

function view_group_repertory_head($group)
{
    global $grade, $translations;
    $html = "   <div class='mm-info-box-repertory'>\n";
    $html .= "<form name='repform' action='materia-medica.php'>";
    $html .= "      <span class='mm-info-box-source-title'>$translations[General_repertory] <span style='font-size:0.7em'><b>".$group['title']." $translations[General_group]</b></span></span>\n";
    $html .= "<input name=\"show\" id=\"show\" type=\"hidden\" value=\"repertory\"  ><input name=\"group_id\" id=\"group_id\" type=\"hidden\" value=\"".$_GET['group_id']."\"  >".get_select_rubric($group).get_radio_grade().get_radio_min_in()."</form>";
    $html .= "      $translations[show_small_rubrics_at_first]\n";
    $html .= "  </div>\n";
    return $html;
}
function get_radio_min_in()
{
    global $min_in, $translations;
    $min_in_arr= array("2"=>"2", "3"=>"3", "4"=>"4" , "5"=>"5");
    $min_in_radio = "<br/>$translations[how_many_remedies_shoud_be_min_in_rubric] ";
    foreach ($min_in_arr as $key => $value) {
        $min_in_radio = $min_in_radio.'<input type="radio" class="button" name="min_in" id='.$value.'" value="'.$key.'"';
        if($min_in == $key){
            $min_in_radio = $min_in_radio.' checked="checked"';
        }
        $min_in_radio = $min_in_radio.' onchange="javascript:document.repform.submit()"> <span class="grade1">'.$value.'</span>&nbsp;';
	}
	return $min_in_radio;
}

function view_group_list($group_arr)
{
    global $translations, $lng;
    if($group_arr){
        $html = "<div class=\"mm-info-box-head\">";
        $group_info_list = "<table width='95%' style='background-color: #fff;'>
            <tbody>
                <tr>
                    <td class='rem-info-tab-head' width='40px'>Id.</td>
                    <td class='rem-info-tab-head'>$translations[General_group]</td>
                    <td class='rem-info-tab-head'>Links</td>
                </tr>
                <tr><td colspan='3' style='border-bottom:1px solid black;'></td></tr>";
        foreach($group_arr as $group_id=>$group){
            $group_info_list .= "<tr class=\"tr_results_2\" onclick=\"if (this.className == 'tr_highlighted_onclick'){ this.className='tr_results_2';}else{ this.className='tr_highlighted_onclick';}\" onmouseout=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_results_2'}\" onmouseover=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_highlighted_onmouseover'}\">
                    <td class='rem-info-tab'>".$group_id."</td>
                    <td class='rem-info-tab'><strong><a href=\"materia-medica.php?group_id=".$group_id."&lang=$lng\" title='$translations[Gerneral_group_details]'>".$group['title']."</a> </strong></td>
                    <td class='rem-info-tab'><strong></strong></td>
                </tr><tr><td colspan='3' style='border-bottom:1px solid black;'></td></tr>";
        }
        $html .= $group_info_list."</tbody></table></div>";

        return $html;
    }else{
        return FALSE;
    }
}

?>