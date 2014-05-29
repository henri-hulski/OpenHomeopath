<?php
function view_lang_menu($page)
{
    global $lng;
    $get_string="?";
    foreach($_GET as $key=>$val){
        if($key != "lang"){
            $get_string .="$key=$val";
            $get_string .="&";
        }
    }
    if($lng == "en"){
        $lang_menu = "<a href='$page.php" . $get_string . "lang=de'>deutsch</a>";
    }else{
        $lang_menu = "<a href='$page.php" . $get_string . "lang=en'>english</a>";
    }
    return $lang_menu;
}

function get_page_nav($rem_short,$s_count,$limit,$start)
{
global $grade, $rubric_id, $lng;
$pages = round(($s_count+$limit), -2)/$limit;
    $nav = "<span>";
    for ($i=1;$i<=$pages;$i++)
    {
        if ((($i-1)*$limit)==$start){
            //echo " ".$i." ";
            $nav .= " ".$i." ";
        }else{
            //echo "<a href=\"materia-medica.php?rem=".$rem_short."&start=".(($i-1)*$limit)."#Symptome\"> ".$i." </a>";
            $nav .= "<a href=\"materia-medica.php?rem=".$rem_short."&start=".(($i-1)*$limit)."&grade=".$grade."&rubric=".$rubric_id."&lang=".$lng."#Symptome\"> ".$i." </a>";
        }
    }
    $nav .= "</span><br/>";
    return $nav;

}
function check_letter($letter)
{
    global $letters;
    $letter = strtoupper(substr(trim($letter),0,1));
    if (in_array($letter, $letters)) {
        return $letter;
    }else{
        return FALSE;
    }
}

function get_letters_menu($letter, $target="letter")
{
    global $letters, $lng;
    $i = 0;
    $letters_menu = "";
    foreach ($letters as $val) {
        if ($letter == $val) {
            $letters_menu .= "<span style='font-size:1.2em;'>$val</span>";
        } else {
            $letters_menu .= "<a href='materia-medica.php?".$target."=$val&lang=$lng'>$val</a>";
        }
        if (count($letters) > ($i+1)) {
            $letters_menu .= " | ";
        }
        $i++;
    }
    return $letters_menu;
}
function get_rem_by_rem_id($remedies_q_ar)
{
    global $db;
    if(is_array($remedies_q_ar)){
        foreach($remedies_q_ar as $rem_id=>$remedy){
            $query = "SELECT remedies.rem_short, remedies.rem_name, remedies.rem_id
                            FROM remedies
                            WHERE rem_id = '$rem_id'";
            $db->send_query($query);
            while($remedy = $db->db_fetch_row()) {
                $remedies_ar[$remedy[2]]['rem_name'] = $remedy[1];
                $remedies_ar[$remedy[2]]['rem_short'] = $remedy[0];
                $remedies_ar[$remedy[2]]['rem_id'] = $remedy[2];
            }
            $db->free_result();  
        }
    }else{
        $rem_id = $remedies_q_ar;
            if (!empty($rem_id)) { // die Aliasabkuerzung wurde gefunden
                $query = "SELECT remedies.rem_short, remedies.rem_name, remedies.rem_id
                            FROM remedies
                            WHERE rem_id = '$rem_id'";
            $db->send_query($query);
            while($remedy = $db->db_fetch_row()) {
                $remedies_ar[$remedy[2]]['rem_name'] = $remedy[1];
                $remedies_ar[$remedy[2]]['rem_short'] = $remedy[0];
                $remedies_ar[$remedy[2]]['rem_id'] = $remedy[2];
            }
            $db->free_result();
        }
    }
    if($remedies_ar){
        $remedies_ar = get_rem_alias($remedies_ar);
        return $remedies_ar; 
    }else{
        return FALSE;
    }
}
function get_rem_by_rem_short($rem_short)
{
    global $db;
    if ($rem_short{strlen($rem_short)-1} == ".") { // ein . am Ende wird entfernt
        $rem_short_ohne_punkt = substr_replace($rem_short, "", -1, 1);
    } else {
        $rem_short_ohne_punkt = $rem_short;
    }
    $query = "SELECT remedies.rem_name, remedies.rem_id, remedies.rem_short FROM remedies WHERE remedies.rem_short  = '$rem_short_ohne_punkt.' OR rem_short = '$rem_short' ORDER BY rem_short";
    $db->send_query($query);
    while($remedy = $db->db_fetch_row()) {
	   $remedies_ar[$remedy[1]]['rem_name'] = $remedy[0];
	   $remedies_ar[$remedy[1]]['rem_short'] = $remedy[2];
    }
    $db->free_result();
    // mittel nicht gefunden
    if (empty($rem_id)) { // die Mittelabkuerzung wurde nicht gefunden
        $query = "SELECT rem_id
                        FROM rem_alias
                        WHERE alias_short = '$rem_short_ohne_punkt.' OR alias_short = '$rem_short'";
        $db->send_query($query);
        $rem_id = $db->db_fetch_row();
        $db->free_result();
        $rem_id = $rem_id[0];
        if (!empty($rem_id)) { // die Aliasabkuerzung wurde gefunden
            $remedies_ar = get_rem_by_rem_id($rem_id);
            
        }
        if($remedies_ar){
            $remedies_ar = get_rem_alias($remedies_ar);
            return $remedies_ar; 
        }else{
            return FALSE;
        }
    }

}

function get_rem_alias($remedies_ar)
{
	global $db;
	foreach($remedies_ar as $rem_id=>$remedy){
		$query = "SELECT alias_short FROM rem_alias WHERE rem_id = $rem_id";
		$db->send_query($query);
		$alias_str ="";
		while($alias_arrr = $db->db_fetch_row()) {
		$alias_str = $alias_str.", ".$alias_arrr[0];
		}
		$db->free_result();
		$remedies_ar[$rem_id]['rem_alias'] = $alias_str;
	}
	return $remedies_ar;
}

function get_rem_by_letter($letter)
{
    global $db;
    $query_rem = "SELECT remedies.rem_short, remedies.rem_name, remedies.rem_id FROM remedies  WHERE remedies.rem_name LIKE '$letter%' ORDER BY rem_name";
    $db->send_query($query_rem);
    while($remedy = $db->db_fetch_row()) {
        $remedies_ar[$remedy[2]]['rem_name'] = $remedy[1];
        $remedies_ar[$remedy[2]]['rem_short'] = $remedy[0];
    }
    $db->free_result();
    if($remedies_ar){
        $remedies_ar = get_rem_alias($remedies_ar); 
    }
    return $remedies_ar;
}
function get_rem_info($remedies_ar)
{
    global $db;
    global $materia_table;
    if(is_array($remedies_ar)){
        foreach($remedies_ar as $rem_id=>$remedy){
            $query = "SELECT  $materia_table.rem_related, $materia_table.rem_incomp, $materia_table.rem_antidot, $materia_table.rem_note, $materia_table.rem_description, $materia_table.src_id, sources.src_title FROM $materia_table, sources WHERE $materia_table.rem_id = $rem_id AND $materia_table.src_id = sources.src_id AND ($materia_table.rem_related != '' || $materia_table.rem_incomp != '' || $materia_table.rem_antidot != '' || $materia_table.rem_note != '' || $materia_table.rem_description != '') ORDER BY sources.src_title";
            $db->send_query($query);
            $remedy_info = $db->db_fetch_assoc();
            $db->free_result();
            if($remedy_info){
                foreach($remedy_info as $key=>$val){
                    $remedies_ar[$rem_id][$key] = $val;
                }
            }
        }
    }
    return $remedies_ar;
}
// zählt symptome
function get_rem_repertory_count($remedies_ar, $where_query)
{
    global $db;
    global $sym_rem;
    if(is_array($remedies_ar)){
        foreach($remedies_ar as $rem_id=>$remedy){
            $query = "SELECT COUNT(*) FROM $sym_rem, symptoms, main_rubrics WHERE $sym_rem.rem_id = $rem_id AND $sym_rem.sym_id = symptoms.sym_id AND symptoms.rubric_id = main_rubrics.rubric_id ";
            $query = $query.$where_query;
            $db->send_query($query);
            while ($count = $db->db_fetch_row()) {
                $remedies_ar[$rem_id]['repertory']['s_count'] = $count[0];
            }
            $db->free_result();
        }
    }
    return $remedies_ar;
}
function get_rem_repertory_symptoms($remedies_ar, $where_query, $start, $limit)
{
    global $db;
    global $sym_rem;
    global $lng;
    if(is_array($remedies_ar)){
        foreach($remedies_ar as $rem_id=>$remedy){
            $select = "SELECT $sym_rem.rel_id, main_rubrics.rubric_de, main_rubrics.rubric_en,symptoms.symptom, $sym_rem.grade, symptoms.sym_id, symptoms.lang_id,$sym_rem.src_id, main_rubrics.rubric_id FROM $sym_rem, symptoms, main_rubrics WHERE $sym_rem.rem_id = $rem_id AND $sym_rem.sym_id = symptoms.sym_id AND symptoms.rubric_id = main_rubrics.rubric_id ";
            $query = $select.$where_query;
            $query = $query . "ORDER BY main_rubrics.rubric_$lng, symptoms.symptom";
            $query = $query . " LIMIT $start , $limit ";
            $result = $db->send_query($query);
            while ($rubric_info = $db->db_fetch_assoc($result)){
                foreach($rubric_info as $key=>$val){
                    //$remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']][$key] = $val;
                    if($key == "src_id"){
                        $remedies_ar[$rem_id]['repertory']['sources'][$val]['src_id'] = $val;
                        $remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']]['sources'][$rubric_info['rel_id']]['src_id'] = $val;
                        $remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']]['sources'][$rubric_info['rel_id']]['src_references'] = get_rem_repertory_src_reference($rubric_info['rel_id']);

                    }
                    elseif($key == "grade"){
                        $remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']]['sources'][$rubric_info['rel_id']][$key] = $val;
                        if(!isset($remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']][$key]) || $val > $remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']]['grade']) {
                            $remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']]['grade'] = $val;
                        }

                    }else{
                     $remedies_ar[$rem_id]['repertory']['symptoms'][$rubric_info['sym_id']][$key] = $val;
                    }
                }
            }
            $db->free_result($result);
        }
    }
    return $remedies_ar;
}
function get_rem_repertory_rubrics($remedies_ar, $where_query)
{
    global $db;
    global $sym_rem;
    global $lng;
    if(is_array($remedies_ar)){
        foreach($remedies_ar as $rem_id=>$remedy){
            // find main rubrics
            $select = "SELECT $sym_rem.rel_id, main_rubrics.rubric_de, main_rubrics.rubric_en,symptoms.symptom, $sym_rem.grade, symptoms.sym_id, symptoms.lang_id,$sym_rem.src_id, main_rubrics.rubric_id FROM $sym_rem, symptoms, main_rubrics WHERE $sym_rem.rem_id = $rem_id AND $sym_rem.sym_id = symptoms.sym_id AND symptoms.rubric_id = main_rubrics.rubric_id ";
            $query_main_rubrics = $select.$where_query . " GROUP BY main_rubrics.rubric_$lng ORDER BY main_rubrics.rubric_$lng";
            $db->send_query($query_main_rubrics);
            while ($main_rubrics = $db->db_fetch_row()) {
                $remedies_ar[$rem_id]['repertory']['rubrics'][$main_rubrics[8]]['rubric_de']=$main_rubrics[1];
                $remedies_ar[$rem_id]['repertory']['rubrics'][$main_rubrics[8]]['rubric_en']=$main_rubrics[2];
            }
            $db->free_result();
        }
    }
    return $remedies_ar;
}
function get_rem_repertory_src_reference($rel_id)
{
    global $db;
    $refs_arr = NULL;
    $query = "SELECT src_id FROM sym_rem_refs  WHERE rel_id = $rel_id ";
    $db->send_query($query);
    while ($refs = $db->db_fetch_row()) {
        $refs_arr[]=$refs[0];
    }
    $db->free_result();
    return $refs_arr;
}
function get_rem_repertory_sources($remedies_ar)
{
    global $db;
    global $sym_rem;
    global $lng;
    if(is_array($remedies_ar)){
        foreach($remedies_ar as $rem_id=>$remedy){
            if(isset($remedies_ar[$rem_id]['repertory']['sources'])){
                foreach($remedies_ar[$rem_id]['repertory']['sources'] as $src_id=>$source){
                    $query_sources = "SELECT src_title, lang_id, src_type, src_author, src_year, src_edition_version FROM sources WHERE  src_id = '$src_id' ";
                    $db->send_query($query_sources);
                    while ($sources_info = $db->db_fetch_assoc()){
                        foreach($sources_info as $key=>$val){
                            $remedies_ar[$rem_id]['repertory']['sources'][$src_id][$key] = $val;
                        }
                    }
                    $db->free_result();
                }
            }
        }
    }
    return $remedies_ar;
}
function get_select_rubric($remedy)
{
    global $translations, $lng;
    $rubric_name = "rubric_".$lng;
    $rubric_select = '<select name="rubric" style="font-size:12px;font-weigt:normal;margin:2px;" onchange="javascript:document.repform.submit()" class="drop-down2"><option value="">'.$translations['Repertory_all_rubrics'].'</option>';
    if(isset($remedy['repertory']['rubrics'])){
        foreach ($remedy['repertory']['rubrics'] as $rubric_id => $rubric) {
            $rubric_select = $rubric_select.'<option value="'.$rubric_id.'"';
            if (isset($_REQUEST['rubric'])) {
                $get_rubric_id = $_REQUEST['rubric'];
                    if($rubric_id == $get_rubric_id){
                        $rubric_select = $rubric_select.'selected="true"';
                    }
            }
            $rubric_select = $rubric_select.'>'.$rubric[$rubric_name].'</option>';
	   }
	}
	$rubric_select = $rubric_select."</select>";
	return $rubric_select;
}
function get_radio_grade()
{
    global $grade, $translations;
    $grade_arr= array("1"=>$translations['Repertory_all'], "2"=>"&ge;2", "3"=>"&ge;3");
    $grade_radio = "";
    foreach ($grade_arr as $key => $value) {
        $grade_radio .= '<input type="radio" class="button" name="grade" id="'.$value.'" value="'.$key.'"';
        if($grade == $key){
            $grade_radio .= ' checked="checked"';
        }
        $grade_radio .= ' onchange="javascript:document.repform.submit()"> <span class="grade'.$key.'">'.$value.'</span>&nbsp;';
	}
	return $grade_radio;
}
function get_rem_searchform($rem_short)
{
    $form = "<form name='searchform' action='materia-medica.php'><div style='position:relative;top:0;left:0;'><input id=\"query\" type=\"text\"  onkeyup=\"autosuggest('auto_all_remedies')\" /><input name=\"rem\" id=\"rem\" type=\"hidden\" value=\"".$rem_short."\"  ><div id=\"results\"></div><div id=\"search_icon\"><img src=\"./skins/original/img/search.png\" width=\"24\" height=\"24\"></div></div></form>";
	return $form;
}

function view_rem_repertory($remedies_ar,$limit,$start)
{
    global $grade, $lng;
    $html = "";
    foreach($remedies_ar as $rem_id=>$remedy){
        $html .= "<div class='mm-info-box'>\n";
        $html .= view_rem_repertory_head($remedy);
        
        if(!empty($remedy['repertory']['symptoms'])){
            if($remedy['repertory']['s_count'] > $limit){
                $html .= "<div class='mm-info-box-part-title'>".get_page_nav($remedy['rem_short'],$remedy['repertory']['s_count'],$limit,$start)."</div>\n";
            }
            $html .= view_rem_repertory_symptoms($remedy,$limit,$start);
            if($remedy['repertory']['s_count'] > $limit){
                $html .= "<div class='mm-info-box-part-title'>".get_page_nav($remedy['rem_short'],$remedy['repertory']['s_count'],$limit,$start)."</div>\n";
            }
        }
        $html .= "</div>\n";
    }
    return $html;
}

function view_rem_repertory_head($remedy)
{
    global $grade, $translations, $lng;
    $html = "   <div class='mm-info-box-repertory'>\n";
    $html .= "<form name='repform' action='materia-medica.php'>";
    $html .= "      <span class='mm-info-box-source-title'>$translations[General_repertory] <span style='font-size:0.7em'><b>".$remedy['rem_name']."</b> (".$remedy['rem_short'].")</span></span>\n";
    $html .= "<input name=\"rem\" id=\"rem\" type=\"hidden\" value=\"".$remedy['rem_short']."\"  >".get_select_rubric($remedy).get_radio_grade()."</form>";
    $html .= "      <b>".$remedy['repertory']['s_count']."</b> $translations[entries_with_grade] <b>&ge;".$grade."</b>\n";
    $html .= "  </div>\n";
    return $html;
}

function view_rem_repertory_symptoms($remedy,$limit,$start){
    global $lng, $translations;
    $rubric_name = "rubric_".$lng;
    $html = "<div class='mm-info-box-rubric'>";
    $i=0;
    foreach($remedy['repertory']['symptoms'] as $sym_id=>$symptom){
        if($remedy['repertory']['symptoms'][$sym_id]['sources']){
            $sources = "$translations[Repertory_sources]: ";
            $ii = 0;
            $row = "";
            foreach($symptom['sources'] as $rel_id=>$val2){
                $sources .= "<a href=\"javascript:popup_url('source.php?src=$val2[src_id]',540,380)\" class='source' style=\"text-decoration:underline;color:#666666\" title='$translations[source_info]'>$val2[src_id]</a> <span class='grade$val2[grade]'>$val2[grade]</span>";
                if($val2['src_references']){
                    $references = " (Ref: ";
                    $iii = 0;
                    foreach($val2['src_references'] as $key=>$referenz){
                        $references .= "<a href=\"javascript:popup_url('source.php?src=$referenz',540,380)\" class='source' style=\"text-decoration:underline;color:#666666\" title='$translations[source_info]'>$referenz</a>";
                        if(count($val2['src_references'])> ($iii+1)){
                            $references = $references.", ";
                        }
                        $iii++;
                    }
                    $references .= ")";
                    $sources .= $references;
                }
                if(count($symptom['sources'])> ($ii+1)){
                    $sources .= " | ";
                }
                $ii++;
            }
        }
        if(!isset($main_rubric) || $main_rubric != $symptom[$rubric_name]){
            $row .= "<span class='mm-info-box-main-rubric'>".$symptom[$rubric_name]."</span><br/>";
        }
        $main_rubric = $symptom[$rubric_name];
        $row .= "<a href='./symptom-details.php?sym=".$sym_id."&lang=$lng' title='Symptom Info'>  <span class='grade".$symptom['grade']."' >".$symptom[$rubric_name]."&nbsp;>&nbsp;".$symptom['symptom']."</span></a>  &ndash;  ".$symptom['grade']."" . _("-gr.") . " &nbsp;";
        $row .= "<span style='font-size:0.8em;'>".$sources."</span>";
        if(count($remedy['repertory']['symptoms']) > ($i+1)){
            $row .= " <br/> ";
        }
        $ii++;
        $html .= $row;
    }
    $html .= "</div>";
    return $html;
}
function view_rem_rel_tab($remedy)
{
    global $translations, $lng;
    $rel_tab =("<br/><table><tbody>");
    if (!empty($remedy['rem_related'])) {
        $rel_rem_ar = explode(";",$remedy['rem_related']);
        $rel_rem_str = "";
        foreach($rel_rem_ar as $val){
            $rel_rem_str .= " <a href=\"materia-medica.php?rem=".trim($val)."&lang=$lng\">".trim($val)."</a>";
        }
        $rel_tab .= ("<tr><td class='rem-info-tab'><strong>$translations[related]:</strong> </td><td class='rem-info-tab'>$rel_rem_str</td></tr>");
    }
    if (!empty($remedy['rem_incomp'])) {
        $incomp_rem_arr = explode(";",$remedy['rem_incomp']);
        $incomp_rem_str = "";
        foreach($incomp_rem_arr as $val){
            $incomp_rem_str .= " <a href=\"materia-medica.php?rem=".trim($val)."&lang=$lng\">".trim($val)."</a>";
        }
        $rel_tab .= ("<tr><td class='rem-info-tab'><strong>$translations[incompatible]:</strong> </td><td class='rem-info-tab'>$incomp_rem_str</td></tr>");
    }
    if (!empty($remedy['rem_antidot'])) {
        $anti_rem_arr = explode(";",$remedy['rem_antidot']);
        $anti_rem_str = "";
        foreach($anti_rem_arr as $val){
            $anti_rem_str .= " <a href=\"materia-medica.php?rem=".trim($val)."&lang=$lng\">".trim($val)."</a>";
        }
        $rel_tab .= ("<tr><td class='rem-info-tab'><strong>$translations[antidote]:</strong> </td><td class='rem-info-tab'>$anti_rem_str</td></tr>");
    }
    if (!empty($remedy['rem_note'])) {
        $note = str_replace("\r\n", "<br />", $remedy['rem_note']);
        $note = str_replace("\r", "<br />", $note);
        $note = str_replace("\n", "<br />", $note);
        $rel_tab .= ("<tr><td class='rem-info-tab'><strong>$translations[Materia_comment]:</strong> </td><td class='rem-info-tab'>$note</td></tr>");
    }
    if (!empty($remedy_info['rem_description'])) {
        $description = str_replace("\r\n", "<br />", $remedy_info[4]);
        $description = str_replace("\r", "<br />", $description);
        $description = str_replace("\n", "<br />", $description);
        $description = $remedy_info[4];
        $rel_tab .= ("<tr><td><strong>".$translations['Materia_description'].":</strong> </td><td>$anti_rem_str</td></tr>");
    }
    $rel_tab .= ("</tbody></table>");
    return $rel_tab;
}

function get_itis_child($tsn)
{
     global $db;
    $query = "SELECT itis__taxonomic_units.tsn, itis__taxonomic_units.parent_tsn, itis__taxonomic_units.rank_id, itis__taxonomic_units.name_usage, itis__taxon_unit_types.rank_name, itis__longnames.completename, itis__kingdoms.kingdom_name 
                    FROM itis__taxonomic_units, itis__longnames, itis__kingdoms, itis__taxon_unit_types 
                    WHERE itis__taxonomic_units.parent_tsn = $tsn 
                    AND itis__longnames.tsn = itis__taxonomic_units.tsn 
                    AND itis__kingdoms.kingdom_id = itis__taxonomic_units.kingdom_id 
                    AND itis__taxon_unit_types.kingdom_id = itis__taxonomic_units.kingdom_id 
                    AND itis__taxon_unit_types.rank_id = itis__taxonomic_units.rank_id ";
    $db->send_query($query);
    $i=0;
    while ($itis_child = $db->db_fetch_assoc()) {
        $itis_child_arr[$i] = $itis_child;
        $i++;
    }
    $db->free_result();
    return $itis_child_arr;
}

function get_itis_parent($parent_tsn)
{
   global $db;
    $query = "SELECT itis__taxonomic_units.tsn, itis__taxonomic_units.parent_tsn, itis__taxonomic_units.rank_id, itis__taxon_unit_types.rank_name, itis__longnames.completename, itis__kingdoms.kingdom_name FROM itis__taxonomic_units, itis__longnames, itis__kingdoms, itis__taxon_unit_types WHERE itis__taxonomic_units.tsn = $parent_tsn AND itis__longnames.tsn = $parent_tsn AND itis__kingdoms.kingdom_id = itis__taxonomic_units.kingdom_id AND itis__taxon_unit_types.kingdom_id = itis__taxonomic_units.kingdom_id AND itis__taxon_unit_types.rank_id = itis__taxonomic_units.rank_id ";
    $db->send_query($query);
    $itis_parent = $db->db_fetch_assoc();
    $db->free_result();
    return $itis_parent;
}

function get_itis_parents($parent_tsn)
{

    $i =0;
    for (; ; ) {
        if($i == 0){
        $i++;
        $itis_parent_arr[$i] = get_itis_parent($parent_tsn);
        }
        if ($itis_parent_arr[$i]['parent_tsn'] == 0) {
            break;
        }
        $i++;
        $itis_parent_arr[$i] = get_itis_parent($itis_parent_arr[($i-1)]['parent_tsn']);
    }
    krsort($itis_parent_arr);
    return $itis_parent_arr;
}

function get_itis_synonym($tsn)
{
	global $db;
	$query = "SELECT tsn
		FROM itis__synonym_links
		WHERE tsn_accepted = $tsn";
	$db->send_query($query);
	$i=0;
	while ($tsn = $db->db_fetch_row()) {
		$itis_synonym_tsn_arr[$i] = $tsn[0];
		$i++;
	}
	$db->free_result();
	if(isset($itis_synonym_tsn_arr)) {
		foreach($itis_synonym_tsn_arr as $key => $tsn){
			$query = "SELECT itis__taxonomic_units.tsn, itis__taxonomic_units.parent_tsn, itis__taxonomic_units.rank_id, itis__taxon_unit_types.rank_name, itis__longnames.completename, itis__kingdoms.kingdom_name
				FROM itis__taxonomic_units, itis__longnames, itis__kingdoms, itis__taxon_unit_types
				WHERE itis__taxonomic_units.tsn = $tsn
				AND itis__longnames.tsn = $tsn
				AND itis__kingdoms.kingdom_id = itis__taxonomic_units.kingdom_id
				AND itis__taxon_unit_types.kingdom_id = itis__taxonomic_units.kingdom_id
				AND itis__taxon_unit_types.rank_id = itis__taxonomic_units.rank_id ";
			$db->send_query($query);
			$itis_synonym_tsn_arr[$key] = $db->db_fetch_assoc();
			$db->free_result();
		}
	} else {
		$itis_synonym_tsn_arr = NULL;
	}
	return $itis_synonym_tsn_arr;
	
}
function get_itis_vernaculars($tsn)
{
    global $db;
    $itis_vernaculars_arr = NULL;
    $query = "SELECT vernacular_name, language, vern_id
                    FROM itis__vernaculars
                    WHERE tsn =$tsn";
    $db->send_query($query);
    $i=0;
    while ($itis_vernaculars = $db->db_fetch_assoc()) {
        $itis_vernaculars_arr[$i] = $itis_vernaculars;
        $i++;
    }
    $db->free_result();
    return $itis_vernaculars_arr;
}
function get_rem_itis($rem_id)
{
    global $db;
    $query = "SELECT rem_itis.tsn, itis__taxonomic_units.parent_tsn, itis__taxonomic_units.rank_id, itis__taxon_unit_types.rank_name, itis__longnames.completename, itis__kingdoms.kingdom_name FROM rem_itis, itis__taxonomic_units, itis__longnames, itis__kingdoms, itis__taxon_unit_types WHERE rem_itis.rem_id = $rem_id AND itis__taxonomic_units.tsn = rem_itis.tsn AND itis__longnames.tsn = rem_itis.tsn AND itis__kingdoms.kingdom_id = itis__taxonomic_units.kingdom_id AND itis__taxon_unit_types.kingdom_id = itis__taxonomic_units.kingdom_id AND itis__taxon_unit_types.rank_id = itis__taxonomic_units.rank_id ";
    $db->send_query($query);
    $itis = $db->db_fetch_assoc();
    $db->free_result();
    if(!empty($itis)){
        $itis['hierarchy'] = get_itis_parents($itis['parent_tsn']);
        $itis['synonyms'] = get_itis_synonym($itis['tsn']);
        $itis['vernaculars'] = get_itis_vernaculars($itis['tsn']);
    }
    return $itis;
}
function get_rem_left($remedy, $remedy_itis, $check_url=1)
{
    global $lang, $db;
    $rem_left = "";
    if($check_url == 1){
        //system sat
        $url_systemsat = "http://system-sat.de/" . str_replace(" ", "_", strtolower($remedy['rem_name'])) . ".html";
        if (!url_exists($url_systemsat)) {
            $url_systemsat = "http://system-sat.de/" . str_replace(" ", "_", strtolower($remedy['rem_name'])) . ".htm";
        }
        if (url_exists($url_systemsat)) {
            $systemsat_link = ("<a href='$url_systemsat' target='_blank'>system-sat.de</a> Die homöopathischen Fäden der Ariadne");
	$links_arr[] = $systemsat_link;
        }
    
        //proving.info
        if($lang == "de"){
            $url_provings = "http://www.provings.info/substanz/" . str_replace(".", "", strtolower($remedy['rem_short']));
            $link_text ="Systematik und Prüfungen";
        }else{
            $url_provings = "http://www.provings.info/en/substanz/" . str_replace(".", "", strtolower($remedy['rem_short']));
            $link_text ="systematics and provings";
        }
        if(url_exists($url_provings)) {
            $provings_link = ("<a href='$url_provings' target='_blank'>provings.info</a> $link_text");
            $links_arr[] = $provings_link;
        }else{
            if($remedy['rem_alias']){
                $alias_arr = explode(",",$remedy['rem_alias']);
                foreach($alias_arr as $val){
                    //echo $val."  ";
                    if($val){
                    $val = str_replace(".", "", strtolower($val));
                    $val = trim($val);
                    $url_provings = "http://www.provings.info/substanz/".$val;
                    if(url_exists($url_provings)) {
                        $provings_link = ("<a href='$url_provings' target='_blank'>provings.info</a> Systematik und Prüfungen");
                        $links_arr[] = $provings_link;
                        
                    }
                    }
                }
            }
        }
	//homoeowiki.org
	$url_homoeowiki = "http://www.homoeowiki.org/index.php/" . str_replace(" ", "_", strtoupper($remedy['rem_name']));
            $homoeowiki_link = ("<a href='$url_homoeowiki' target='_blank'>homoeowiki.org</a>");
	$links_arr[] = $homoeowiki_link;
	
    }

	
    
    //species.wikimedia.org/wiki/
    if($remedy_itis['completename']){
        $url_species_wikimedia = "http://species.wikimedia.org/wiki/" . str_replace(" ", "_", strtolower($remedy_itis['completename']));
        $species_wikimedia_link = ("<a href='$url_species_wikimedia' target='_blank'>Wikispecies</a> free directory of species");
        $links_arr[] = $species_wikimedia_link;
    }

    //dr duke
    if($remedy_itis['completename'] AND $remedy_itis['kingdom_name'] == "Plantae"){
        $url_dr_duke = "http://www.ars-grin.gov/cgi-bin/duke/ethnobot.pl?" . str_replace(" ", "%20", strtolower($remedy_itis['completename']));
        $dr_duke_link = ("<a href='$url_dr_duke' target='_blank'>Dr. Duke s Phytochemical and Ethnobotanical Databases</a>");
        $links_arr[] = $dr_duke_link;
    }
    
    //NCBI taxonomie
    if($remedy_itis['completename']){
        $url_ncbi = "http://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?name=" . str_replace(" ", "+", strtolower($remedy_itis['completename']));
        $ncbi_link = ("<a href='$url_ncbi' target='_blank'>NCBI Taxonomy Browser</a>");
        $links_arr[] = $ncbi_link;
    }
	//calPhoto $remedy_itis['completename']
    if($remedy_itis['completename']){
        $url_cal_photo = "http://calphotos.berkeley.edu/cgi/img_query?special=browse&where-taxon=" . str_replace(" ", "+", strtolower($remedy_itis['completename']));
        $cal_photo_link = ("<a href='$url_cal_photo' target='_blank'>CalPhotos</a> University of California, Berkeley");
        $links_arr[] = $cal_photo_link;
    }
    $query = "SELECT rem_id FROM remedies WHERE rem_short = '$remedy[rem_short]'";
    $db->send_query($query);
    list($rem_id) = $db->db_fetch_row();
    $db->free_result();
    $links_arr[] = ("<a href='materia.php?rem=$rem_id&lang=$lang' target='_blank'>OpenHomeopath</a>");
    //mittel links
    if(!empty($links_arr)){
        $ii=1;
        foreach($links_arr as $key =>$link){
            $rem_left .= $link;
            if($ii < count($links_arr)){
                $rem_left .= "<br/>";
            }
            $ii++;
        }
    }
    return $rem_left;
}
function view_rem_info_tab($remedy,$rem_id, $remedy_itis)
{
    global $translations, $lng;
    $vernaculars_html = "";
    if(!empty($remedy_itis['vernaculars'])){
        $vernaculars_html .= "<span class='vernaculars'>";
        $i=1;
        foreach($remedy_itis['vernaculars'] as $key =>$vernaculars){
            $vernaculars_html .= $vernaculars['vernacular_name'];
            if($i < count($remedy_itis['vernaculars'])){
                $vernaculars_html .= "<br/>";
            } 
            $i++;
        }
        $vernaculars_html .= "</span>";
    }
    // synonyme
    $synonyms_html = "";
    if(!empty($remedy_itis['synonyms'])){
        $synonyms_html .= "<br/><span class='synonym'>";
        $i=1;
        foreach($remedy_itis['synonyms'] as $key =>$synonyms){
            $synonyms_html .= "<span class='".strtolower($synonyms['rank_name'])."' title='".$synonyms['rank_name']."'>".$synonyms['completename']."</span>";
            if($i < count($remedy_itis['synonyms'])){
                $synonyms_html .= "<br/>";
            } 
            $i++;
        }
        $synonyms_html .= "</span>";
    }
    // taxon
    $taxon = "";
    if(!empty($remedy_itis['completename'])){
        if($remedy_itis['completename'] != $remedy['rem_name']){
            $taxon ="<br/>".$remedy_itis['completename'];
        }
    }
    // family
        $itis_family = "";
        if(!empty($remedy_itis['hierarchy'])){
            foreach($remedy_itis['hierarchy'] as $key =>$hierarchy){
                if($hierarchy['rank_name'] == "Family"){
                    $itis_family = $hierarchy['completename']."<br/>";
                }
            }
        }
        // gruppen
        $gruppen_html = "";
        if(!empty($remedy['gruppen'])){
            $gruppen_html .= "<span class='group'>";
            $i=1;
            foreach($remedy['gruppen'] as $key =>$gruppen){
                $gruppen_html .= "<a href='materia-medica.php?group_id=".$gruppen['id']."&lang=$lng'>".$gruppen['title']."</a>";
                if($i < count($remedy['gruppen'])){
                    $gruppen_html .= "<br/>";
                } 
                $i++;
            }
            $gruppen_html .= "</span>";
        }
	//mittel info tab
    $remedy_info_tab = "<table width='95%'>
            <tbody>
                <tr>
                    <td class='rem-info-tab-head' width='40px'>Id.</td>
                    <td class='rem-info-tab-head'>$translations[General_remedy]</td>
                    <td class='rem-info-tab-head'>$translations[General_abbreviation]</td>
                    <td class='rem-info-tab-head'>$translations[General_groups]</td>
                    <td class='rem-info-tab-head'>$translations[General_common_name]</td>
                    <td class='rem-info-tab-head'>Links</td>
                </tr>

                <tr>
                    <td class='rem-info-tab'>".$rem_id."</td>
                    <td class='rem-info-tab'><strong>".$remedy['rem_name']."</strong>$taxon $synonyms_html</td>
                    <td class='rem-info-tab'><strong>".$remedy['rem_short']."</strong>".$remedy['rem_alias']."</td>
                    <td class='rem-info-tab'><strong>".$itis_family.$gruppen_html."</td>
	            <td class='rem-info-tab'>".$vernaculars_html."</td>
                    <td class='rem-info-tab'>".get_rem_left($remedy, $remedy_itis)."</td>
                </tr>
            </tbody>
        </table>";
        return $remedy_info_tab;
}
function view_rem_info($remedies_ar)
{
    global $lng;
    $synonyms_html = "";
    $html = "";
    $hierarchy_html = "";
    foreach($remedies_ar as $rem_id=>$remedy){
        $remedy_itis = get_rem_itis($rem_id);
        if(!empty($remedy_itis['synonyms'])){
            $synonyms_html .= "<span class='synonym'>";
             foreach($remedy_itis['synonyms'] as $key =>$synonyms){
                $synonyms_html .= " = <span class='".strtolower($synonyms['rank_name'])."' title='".$synonyms['rank_name']."'>".$synonyms['completename']."</span>";
            }
            $synonyms_html .= "</span>";
        }
        if(!empty($remedy_itis['hierarchy'])){
            foreach($remedy_itis['hierarchy'] as $key =>$hierarchy){
                $hierarchy_html .= "<span class='".strtolower($hierarchy['rank_name'])."' title='".$hierarchy['rank_name']."'>".$hierarchy['completename']."</span> - ";
            }
            $hierarchy_html .= "<span class='".strtolower($remedy_itis['rank_name'])."' title='".$remedy_itis['rank_name']."'><b>".$remedy_itis['completename']."</b></span>\n";
        }
        $html .= "<div class=\"mm-info-box-head\">";
        $html .= $hierarchy_html;
        $html .= "<h2>$remedy[rem_name] <span style='font-weight:normal;'>($remedy[rem_short])</span></h2><hr/>";
        $html .= view_rem_info_tab($remedy, $rem_id, $remedy_itis);
        $html .= view_rem_rel_tab($remedy);
        $html .= "</div>";
    }
    return $html;
}
function view_rem_list($remedies_ar)
{
    global $translations, $lng;
    $html = "<div class=\"mm-info-box-head\">";
    $remedy_info_list = "<table width='95%' style='background-color: #fff;'>
            <tbody>
                <tr>
                    <td class='rem-info-tab-head' width='40px'>ID</td>
                    <td class='rem-info-tab-head'>$translations[General_remedy]</td>
                    <td class='rem-info-tab-head'>$translations[General_abbreviation]</td>
                    <td class='rem-info-tab-head'>$translations[General_groups]</td>
                    <td class='rem-info-tab-head'>$translations[General_common_name]</td>
                    <td class='rem-info-tab-head'>Links</td>
                </tr>
                <tr><td colspan='6' style='border-bottom:1px solid black;'></td></tr>";
    foreach($remedies_ar as $rem_id=>$remedy){
        $remedy_itis = get_rem_itis($rem_id);
        // vernaculars
        $vernaculars_html ="";
        if(!empty($remedy_itis['vernaculars'])){
            $vernaculars_html .= "<span class='vernaculars'>";
            $i=1;
            foreach($remedy_itis['vernaculars'] as $key =>$vernaculars){
                $vernaculars_html .= $vernaculars['vernacular_name'];
                if($i < count($remedy_itis['vernaculars'])){
                    $vernaculars_html .= "<br/>";
                } 
                $i++;
            }
            $vernaculars_html .= "</span>";
        }
        // synonyme
        $synonyms_html ="";
        if(!empty($remedy_itis['synonyms'])){
            $synonyms_html .= "<br/><span class='synonym'>";
            $i=1;
            foreach($remedy_itis['synonyms'] as $key =>$synonyms){
                $synonyms_html .= "<span class='".strtolower($synonyms['rank_name'])."' title='".$synonyms['rank_name']."'>".$synonyms['completename']."</span>";
                if($i < count($remedy_itis['synonyms'])){
                    $synonyms_html .= "<br/>";
                } 
                $i++;
            }
            $synonyms_html .= "</span>";
        }
        // taxon
        $taxon ="";
        if(!empty($remedy_itis['completename'])){
            if($remedy_itis['completename'] != $remedy['rem_name']){
                $taxon ="<br/>".$remedy_itis['completename'];
            }
        }
        // family
        $itis_family = "";
        if(!empty($remedy_itis['hierarchy'])){
            foreach($remedy_itis['hierarchy'] as $key =>$hierarchy){
                if($hierarchy['rank_name'] == "Family"){
                    $itis_family = $hierarchy['completename']."<br/>";
                }
            }
        }
        // gruppen
        $gruppen_html = "";
        if(!empty($remedy['gruppen'])){
            $gruppen_html .= "<span class='group'>";
            $i=1;
            foreach($remedy['gruppen'] as $key =>$gruppen){
                $gruppen_html .= "<a href='materia-medica.php?group_id=".$gruppen['id']."&lang=$lng'>".$gruppen['title']."</a>";
                if($i < count($remedy['gruppen'])){
                    $gruppen_html .= "<br/>";
                } 
                $i++;
            }
            $gruppen_html .= "</span>";
        }
        $remedy_info_list .= "<tr class=\"tr_results_2\" onclick=\"if (this.className == 'tr_highlighted_onclick'){ this.className='tr_results_2';}else{ this.className='tr_highlighted_onclick';}\" onmouseout=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_results_2'}\" onmouseover=\"if (this.className!='tr_highlighted_onclick'){this.className='tr_highlighted_onmouseover'}\">
                    <td class='rem-info-tab'>".$rem_id."</td>
                    <td class='rem-info-tab'><strong><a href=\"materia-medica.php?rem=".$remedy['rem_short']."&lang=$lng\" title='Mittel Details'>".$remedy['rem_name']."</a> </strong>".$remedy['repertory']['s_count']."$taxon $synonyms_html</td>
                    <td class='rem-info-tab'><strong>".$remedy['rem_short']."</strong>".$remedy['rem_alias']."</td>
                    <td class='rem-info-tab'><strong>".$itis_family.$gruppen_html."</td>
                    <td class='rem-info-tab'>".$vernaculars_html."</td>
                    <td class='rem-info-tab'>".get_rem_left($remedy, $remedy_itis,0)."</td>
                </tr><tr><td colspan='6' style='border-bottom:1px solid black;'></td></tr>";
        }
        $html .= $remedy_info_list."</tbody></table></div>";
    //}
    return $html;
}
?>
