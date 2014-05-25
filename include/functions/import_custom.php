<?php
function create_import_tables() {
	global $db;
	$query = "DROP  TABLE IF EXISTS `import_custom__sym`, `import_custom__remsym`, `import_custom__sym_rem_refs`";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_custom__sym` (
		sym_id mediumint(8) unsigned NOT NULL DEFAULT 0,
		symptom_id mediumint(8) unsigned NOT NULL,
		sym_name text NOT NULL,
		rubric_id tinyint(3) unsigned NOT NULL,
		lang_id varchar(6) NOT NULL,
		username varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		`timestamp` timestamp NOT NULL DEFAULT '0',
		PRIMARY KEY(symptom_id),
		KEY sym_id (sym_id),
		FULLTEXT KEY sym_name (sym_name)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_custom__remsym` (
		bez_id mediumint(8) unsigned NOT NULL,
		symptom_id mediumint(8) unsigned NOT NULL,
		rem_id smallint(5) unsigned NOT NULL,
		grade tinyint(1) unsigned NOT NULL,
		src_id varchar(12) NOT NULL,
		status_id tinyint(1) unsigned NOT NULL DEFAULT '0',
		kuenzli tinyint(1) NOT NULL DEFAULT '0',
		username varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		`timestamp` timestamp NOT NULL DEFAULT '0',
		PRIMARY KEY(symptom_id, rem_id, src_id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_custom__sym_rem_refs` (
		bez_id mediumint(8) unsigned NOT NULL,
		src_id varchar(12) NOT NULL,
		nonclassic tinyint(1) NOT NULL DEFAULT '0',
		username varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
		`timestamp` timestamp NOT NULL,
		KEY bez_id (bez_id),
		KEY src_id (src_id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
}

function import_custom_rubrics() {
	global $db;
	$imported_src_id_ar = array('BZH', 'kent.en', 'boenn_bogner', 'boenn_allen', 'openrep_pub');
	$not_src_query = implode("' AND symptome_mittel.quelle_id != '", $imported_src_id_ar);
	$where = "(symptome_mittel.quelle_id != '" . $not_src_query . "')";
	$query = "INSERT INTO `import_custom__sym` (symptom_id, sym_name, rubric_id, lang_id, username, `timestamp`) SELECT DISTINCT s.symptom_id, s.symptom_name, s.rubrik_id, s.sprache_id, s.username, FROM_UNIXTIME(s.`timestamp`) FROM symptome s, symptome_mittel WHERE s.symptom_id = symptome_mittel.symptom_id AND $where ORDER BY s.symptom_id";
	$db->send_query($query);
	$query = "INSERT INTO `import_custom__remsym` (bez_id, symptom_id, rem_id, grade, src_id, status_id, kuenzli, username, `timestamp`) SELECT `beziehungs_id`, `symptom_id`, `mittel_id`, `wertigkeit`, `quelle_id`, `status_id`, `kuenzli_punkt`, `username`, FROM_UNIXTIME(s.`timestamp`) FROM `symptome_mittel` WHERE $where ORDER BY `beziehungs_id`";
	$db->send_query($query);
	$query = "INSERT INTO `import_custom__sym_rem_refs` (bez_id, src_id, nonclassic, username, `timestamp`) SELECT smr.`beziehungs_id`, smr.`quelle_id`, smr.`nicht_klassisch`, smr.`username`, FROM_UNIXTIME(s.`timestamp`) FROM `symptome_mittel_referenzen` smr, symptome_mittel WHERE (smr.`quelle_id` != '' OR smr.`nicht_klassisch` = 1) AND smr.`beziehungs_id` = symptome_mittel.`beziehungs_id` AND $where ORDER BY smr.`beziehungs_id`";
	$db->send_query($query);
}

function get_sym_ids() {
	global $db;
	$query = "SELECT symptom_id, sym_name, rubric_id, lang_id FROM `import_custom__sym`";
	$result = $db->send_query($query);
	while (list($symptom_id, $sym_name, $rubric_id, $lang_id) = $db->db_fetch_row($result)) {
		$escaped_sym_name = $db->escape_string($sym_name);
		$sym_name_alt = $db->escape_string(preg_replace('/( \\\\> |, )/u', '( \> |, )', preg_quote($sym_name)));
		$symptom_where = "MATCH (symptom) AGAINST ('$escaped_sym_name') AND (symptom LIKE '$escaped_sym_name' OR symptom REGEXP '^$sym_name_alt$')";
		// TODO: Set [mysqld] ft_min_word_len=3 and ft_stopword_file=''
		$query = "SELECT sym_id FROM symptoms WHERE rubric_id = $rubric_id AND lang_id = '$lang_id' AND $symptom_where LIMIT 1";
		$db->send_query($query);
		list($sym_id) = $db->db_fetch_row();
		$db->free_result();
		if (!isset($sym_id)) {
			$sym_id = 0;
		}
		$query = "UPDATE `import_custom__sym` SET sym_id = $sym_id WHERE symptom_id = $symptom_id";
		$db->send_query($query);
	}
	$db->free_result($result);
}

function insert_sym() {
	global $db;
	$query = "SELECT sym_id, symptom_id, sym_name, rubric_id, lang_id, username, `timestamp` FROM `import_custom__sym`";
	$result = $db->send_query($query);
	while (list($sym_id, $symptom_id, $sym_name, $rubric_id, $lang_id, $username, $timestamp) = $db->db_fetch_row($result)) {
		if ($sym_id == 0) {
			$escaped_sym_name = $db->escape_string($sym_name);
			$sym_name_alt = $db->escape_string(preg_replace('/( \\\\> |, )/u', '( \> |, )', preg_quote($sym_name)));
			$symptom_where = "MATCH (sym_name) AGAINST ('$escaped_sym_name') AND (sym_name LIKE '$escaped_sym_name' OR sym_name REGEXP '^$sym_name_alt$')";
			$query = "SELECT sym_id FROM `import_custom__sym` WHERE symptom_id != $symptom_id AND sym_id != 0 AND rubric_id = $rubric_id AND lang_id = '$lang_id' AND $symptom_where LIMIT 1";
			$db->send_query($query);
			list($sym_id) = $db->db_fetch_row();
			$db->free_result();
			if (empty($sym_id)) {
				$query = "INSERT INTO symptoms (`symptom`, `rubric_id`, `lang_id`, `username`, `timestamp`) VALUES ('$escaped_sym_name', $rubric_id, '$lang_id', '$username', $timestamp)";
				$db->send_query($query);
				$sym_id = $db->db_insert_id();
				$query = "UPDATE `import_custom__sym` SET sym_id = $sym_id WHERE symptom_id = $symptom_id";
				$db->send_query($query);
			}
		}
		$query = "UPDATE `symptoms_upgrade` SET `sym_id_new` = $sym_id WHERE `symptom_id_old` = $symptom_id";
		$db->send_query($query);
	}
	$db->free_result($result);
}

function insert_remsym() {
	global $db;
	$query = "SELECT rs.bez_id, s.sym_id, rs.rem_id, rs.grade, rs.src_id, rs.status_id, rs.kuenzli, rs.username, rs.`timestamp` FROM `import_custom__remsym` rs, `import_custom__sym` s WHERE s.symptom_id = rs.symptom_id";
	$result = $db->send_query($query);
	while (list($bez_id, $sym_id, $rem_id, $grade, $src_id, $status_id, $kuenzli, $username, $timestamp) = $db->db_fetch_row($result)) {
		$query = "SELECT rel_id FROM sym_rem WHERE sym_id = $sym_id AND rem_id = $rem_id AND src_id = '$src_id'";
		$db->send_query($query);
		list($rel_id) = $db->db_fetch_row();
		$sym_rem_num = $db->db_num_rows();
		$db->free_result();
		$new_remsym = 0;
		if ($sym_rem_num == 0) {
			$query = "INSERT INTO sym_rem (sym_id, rem_id, grade, src_id, username, timestamp) VALUES ($sym_id, $rem_id, $grade, '$src_id', '$username', $timestamp)";
			$db->send_query($query);
			$rel_id = $db->db_insert_id();
			$new_remsym = 1;
		}
		$query = "SELECT src_id, nonclassic, username, `timestamp` FROM `import_custom__sym_rem_refs` WHERE bez_id = $bez_id";
		$result_2 = $db->send_query($query);
		while (list($ref_src_id, $nonclassic, $ref_username, $ref_timestamp) = $db->db_fetch_row($result_2)) {
			$sym_rem_ref_num = 0;
			if ($new_remsym == 0) {
				$query = "SELECT rel_id FROM sym_rem_refs WHERE rel_id = $rel_id AND src_id = '$ref_src_id' LIMIT 1";
				$db->send_query($query);
				$sym_rem_ref_num = $db->db_num_rows();
				$db->free_result();
			}
			if ($sym_rem_ref_num == 0) {
				$query = "INSERT INTO sym_rem_refs (`rel_id`, `src_id`, `nonclassic`, `username`, `timestamp`) VALUES ($rel_id, '$ref_src_id', $nonclassic, '$ref_username', $ref_timestamp)";
				$db->send_query($query);
			}
		}
		$db->free_result($result_2);
	}
	$db->free_result($result);
}
?>
