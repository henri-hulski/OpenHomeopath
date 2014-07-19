<?php
chdir("..");
include_once("include/classes/login/session.php");
include ("include/datadmin/config.php");
echo "hallo";
$query = "SELECT src_id FROM sources WHERE src_id LIKE '%.%'";
$query_select_all = "SELECT `sym_id`, `symptom` FROM `symptoms` WHERE `rubric_id` = '$rubric_id'";
	$db->send_query($query);
	while ($symptom_row = $db->db_fetch_row()){ // for each symptom in the table
		$result = $db->db_fetch_row();
		list($ref)=$result;
		$ref_all[]=$ref;
	} // end while loop for each symptom
	$db->free_result();
$count_ref_not_found=1;
foreach ($ref_all as $key => $value) {
	if($value != "") {
	$without_point = substr($value,0,strpos($value,".")+0);
	$query = "SELECT COUNT(*) FROM sources WHERE src_id = '$without_point'";
	$db->send_query($query);
	$ref_count = $db->db_fetch_row();
	$db->free_result();
	if ($ref_count[0] == 0) {
		$titel ="=".$value."=";
		$src_id = $without_point;
		echo "<br>".$count_ref_not_found." src_id: ".$src_id." src_title: ".$titel."<br>";
		$author = $value["author"];
					
					$year = "";
					$lang = "";
					$max_grade = 0;
					$src_type = "alias";
					$main_source = 0;
					$ref_not_found_ar[] = $value;
					$count_ref_not_found++;
		}else{
		$ref_found_ar[] = $value;
		echo "<br>Kein Alias noetig,  es wurde ".$value." und ".$without_point." gefunden<br>";
		}
	}
}
