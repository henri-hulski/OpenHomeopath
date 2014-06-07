<?php
function create_import_tables() {
	global $db;
	$rep = $_REQUEST['rep'];
	$query = "DROP  TABLE IF EXISTS `import_".$rep."__sym`, `import_".$rep."__mainsym`, `import_".$rep."__ref`, `import_".$rep."__rem`, `import_".$rep."__src`, `import_".$rep."__remsym`, `import_".$rep."__remsymsrc`";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_".$rep."__sym` (
		symptom_id mediumint(8) unsigned NOT NULL DEFAULT 0,
		sym_id mediumint(8) unsigned NOT NULL,
		sym_name text NOT NULL,
		parent_id mediumint(8) NOT NULL DEFAULT -1,
		ref_id smallint(5) unsigned NOT NULL DEFAULT 0,
		rubric_id tinyint(3) unsigned NOT NULL,
		correct tinyint(1) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY(sym_id),
		KEY symptom_id (symptom_id),
		KEY parent_id (parent_id),
		KEY ref_id (ref_id),
		KEY rubric_id (rubric_id),
		FULLTEXT KEY sym_name (sym_name)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_".$rep."__mainsym` (
		rubric_id tinyint(3) unsigned NOT NULL,
		mainsym_name varchar(255) NOT NULL,
		KEY rubric_id (rubric_id),
		KEY mainsym_name (mainsym_name)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_".$rep."__rem` (
		remedy_id smallint(5) unsigned NOT NULL DEFAULT 0,
		rem_id smallint(5) unsigned NOT NULL,
		rem_short varchar(255) NOT NULL,
		rem_long varchar(255) NOT NULL,
		PRIMARY KEY(rem_id),
		KEY remedy_id (remedy_id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_".$rep."__remsym` (
		remsym_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
		sym_id mediumint(8) unsigned NOT NULL,
		rem_id smallint(5) unsigned NOT NULL,
		rem_grade tinyint(1) unsigned NOT NULL,
		PRIMARY KEY(remsym_id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_".$rep."__remsymsrc` (
		remsym_id mediumint(8) unsigned NOT NULL,
		src_id smallint(5) unsigned NOT NULL,
		PRIMARY KEY (remsym_id,src_id),
		KEY remsym_id (remsym_id),
		KEY src_id (src_id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_".$rep."__src` (
		source_id varchar(12) NOT NULL DEFAULT '',
		src_id smallint(5) unsigned NOT NULL,
		src_title varchar(200) NOT NULL,
		src_author tinytext NOT NULL,
		PRIMARY KEY(src_id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
	$query = "CREATE  TABLE `import_".$rep."__ref` (
		ref_id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
		xref_id mediumint(8) unsigned NOT NULL,
		PRIMARY KEY(ref_id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	$db->send_query($query);
}

function openrep_to_mysql() {
	global $db;
	$rep = $_REQUEST['rep'];
	$uploaddir = UPLOAD_DIR;
	$version = 1;
	if (!empty ($_REQUEST['version'])) {
		$version = $_REQUEST['version'];
	}
	
	if (is_readable($uploaddir . $_REQUEST['sym'])) {
		$sym_file = fopen($uploaddir . $_REQUEST['sym'], "rb");
		while (!feof($sym_file)) {
			$sym = trim(fgets($sym_file));
			if (!empty($sym)) {
				list($sym_id, $sym_name) = explode (':', $sym, 2);
				$sym_name = $db->escape_string(trim($sym_name, '" '));
				$query = "INSERT INTO `import_".$rep."__sym` (sym_id, sym_name) VALUES ($sym_id, '$sym_name')";
				$db->send_query($query);
			}
		}
		fclose($sym_file);
	}
	if (is_readable($uploaddir . $_REQUEST['rem'])) {
		$rem_file = fopen($uploaddir . $_REQUEST['rem'], "rb");
		while (!feof($rem_file)) {
			$rem = trim(fgets($rem_file));
			if (!empty($rem)) {
				list($rem_id, $rem_str) = explode (':', $rem, 2);
				list($rem_name, $rem_short) = explode ('#', $rem_str);
				$rem_name = $db->escape_string(trim($rem_name, '" '));
				$rem_short = $db->escape_string(trim($rem_short, '" '));
				$query = "INSERT INTO `import_".$rep."__rem` (rem_id, rem_short, rem_long) VALUES ($rem_id, '$rem_short', '$rem_name')";
				$db->send_query($query);
			}
		}
		fclose($rem_file);
	}
	if (is_readable($uploaddir . $_REQUEST['remsym'])) {
		$remsym_file = fopen($uploaddir . $_REQUEST['remsym'], "rb");
		while (!feof($remsym_file)) {
			$remsym = trim(fgets($remsym_file));
			if (!empty($remsym)) {
				list($sym_id, $rems_str) = explode (':', $remsym, 2);
			}
			if (isset($sym_id) && !empty($rems_str)) {
				if ($rems_str{strlen($rems_str)-1} == ";") {
					$rems_str = substr_replace($rems_str, "", -1, 1);
				}    // ein ; am Ende der Mittelliste wird entfernt
				$rem_ar = explode (';', $rems_str);
				foreach ($rem_ar as $rem_str) {
					$rem_str_ar = explode ('#', $rem_str, 3);
					if (count($rem_str_ar) > 2) {
						list($rem_id, $rem_grade, $src_str) = $rem_str_ar;
					} else {
						list($rem_id, $rem_grade) = $rem_str_ar;
					}
					$query = "INSERT INTO `import_".$rep."__remsym` (sym_id, rem_id, rem_grade) VALUES ($sym_id, $rem_id, $rem_grade)";
					$db->send_query($query);
					$remsym_id = $db->db_insert_id();
					if (!empty($src_str)) {
						$src_ar = explode ('#', $src_str);
						foreach ($src_ar as $src_id) {
							$query = "REPLACE INTO `import_".$rep."__remsymsrc` (remsym_id, src_id) VALUES ($remsym_id, $src_id)";
							$db->send_query($query);
						}
					}
				}
				
			}
		}
		fclose($remsym_file);
	}
	$mainsym_imported = 0;
	if (is_readable($uploaddir . $_REQUEST['tree'])) {
		$tree_file = fopen($uploaddir . $_REQUEST['tree'], "rb");
		while (!feof($tree_file)) {
			$tree = trim(fgets($tree_file));
			if (!empty($tree)) {
				list($sym_id, $tree_str) = explode (':', $tree, 2);
				list($parent_id, $tail) = explode ('#', $tree_str);
				$query = "UPDATE `import_".$rep."__sym` SET parent_id='$parent_id' WHERE sym_id='$sym_id'";
				$db->send_query($query);
			}
		}
		fclose($tree_file);
	} elseif (is_readable($uploaddir . $_REQUEST['mainsym'])) {
		$mainsym_file = fopen($uploaddir . $_REQUEST['mainsym'], "rb");
		while (!feof($mainsym_file)) {
			$mainsym = $db->escape_string(trim(fgets($mainsym_file)));
			if (!empty($mainsym)) {
				list($mainsym) = explode (', ', $mainsym, 2);
				$query = "INSERT INTO `import_".$rep."__mainsym` (mainsym_name) VALUES ('$mainsym')";
				$db->send_query($query);
			}
		}
		fclose($mainsym_file);
		$mainsym_imported = 1;
	}
	if (is_readable($uploaddir . $_REQUEST['src'])) {
		$src_file = fopen($uploaddir . $_REQUEST['src'], "rb");
		while (!feof($src_file)) {
			$src = trim(fgets($src_file));
			if (!empty($src)) {
				list($src_id, $src_str) = explode (':', $src, 2);
				list($src_author, $src_title) = explode ('#', $src_str);
				$src_title = $db->escape_string(trim($src_title, '" '));
				$src_author = $db->escape_string(trim($src_author, '" '));
				$query = "INSERT INTO `import_".$rep."__src` (src_id, src_title, src_author) VALUES ($src_id, '$src_title', '$src_author')";
				$db->send_query($query);
			}
		}
		fclose($src_file);
	}
	if (is_readable($uploaddir . $_REQUEST['ref'])) {
		$ref_file = fopen($uploaddir . $_REQUEST['ref'], "rb");
		while (!feof($ref_file)) {
			$ref = trim(fgets($ref_file));
			if (!empty($ref)) {
				$ref = str_replace(':', ';', $ref);
				$refs_ar = explode (';', $ref);
				sort($refs_ar, SORT_NUMERIC);
				foreach ($refs_ar as $sym_id) {
					$query = "SELECT ref_id FROM `import_".$rep."__sym` WHERE sym_id='$sym_id'";
					$db->send_query($query);
					list($ref_id) = $db->db_fetch_row();
					$db->free_result();
					if ($ref_id != 0) {
						break;
					}
				}
				if ($ref_id == 0) {
					$query = "INSERT INTO `import_".$rep."__ref` () VALUES ()";
					$db->send_query($query);
					$ref_id = $db->db_insert_id();
				}
				foreach ($refs_ar as $symptom) {
					$query = "UPDATE `import_".$rep."__sym` SET ref_id='$ref_id' WHERE sym_id='$symptom'";
					$db->send_query($query);
				}
			}
		}
		fclose($ref_file);
	}
	return $mainsym_imported;
}

function extract_mainsym($mainsym_imported) {
	global $db;
	$rep = $_REQUEST['rep'];
	$query = "SELECT lang_id FROM sources WHERE src_id LIKE '$rep'";
	$db->send_query($query);
	list($lang_id) = $db->db_fetch_row();
	$db->free_result();
	$mainsym_missed = 0;
	if ($mainsym_imported != 1) {
		$query = "SELECT sym_id, sym_name FROM `import_".$rep."__sym` WHERE parent_id=-1";
		$result = $db->send_query($query);
		while (list($mainsym_id, $mainsym_name) = $db->db_fetch_row($result)) {
			$mainsym_name_ar = explode (', ', $mainsym_name, 2);
			if (count($mainsym_name_ar) > 1) {
				$mainsym_name = $db->escape_string($mainsym_name_ar[0]);
				$query = "SELECT sym_id FROM `import_".$rep."__sym` WHERE sym_name = '$mainsym_name' LIMIT 1";
				$db->send_query($query);
				list($mainsym_id) = $db->db_fetch_row();
				$mainsym_id = (isset($mainsym_id)) ? $mainsym_id : -1;
			}
			if ($mainsym_id != -1) {
				$query = "UPDATE `import_".$rep."__sym` SET parent_id=-2 WHERE sym_id = $mainsym_id";
				$db->send_query($query);
				$query = "UPDATE `import_".$rep."__sym` SET parent_id=-1 WHERE parent_id = $mainsym_id";
				$db->send_query($query);
			}
			$query = "SELECT rubric_id FROM main_rubrics WHERE rubric_$lang_id = '$mainsym_name' LIMIT 1";
			$db->send_query($query);
			list($rubric_id) = $db->db_fetch_row();
			$db->free_result();
			if (!isset($rubric_id)) {
				$rubric_id = 0;
				$mainsym_missed++;
			}
			$query = "INSERT INTO `import_".$rep."__mainsym` (rubric_id, mainsym_name) VALUES ($rubric_id, '$mainsym_name')";
			$db->send_query($query);
		}
	} else {
		$query = "SELECT mainsym_name FROM `import_".$rep."__mainsym`";
		$result = $db->send_query($query);
		while (list($mainsym_name) = $db->db_fetch_row($result)) {
			$query = "SELECT sym_id FROM `import_".$rep."__sym` WHERE sym_name = '$mainsym_name' LIMIT 1";
			$db->send_query($query);
			list($mainsym_id) = $db->db_fetch_row();
			if (isset($mainsym_id)) {
				$query = "UPDATE `import_".$rep."__sym` SET parent_id=-2 WHERE sym_id = $mainsym_id";
				$db->send_query($query);
			}
			$query = "SELECT rubric_id FROM main_rubrics WHERE rubric_$lang_id = '$mainsym_name' LIMIT 1";
			$db->send_query($query);
			list($rubric_id) = $db->db_fetch_row();
			$db->free_result();
			if (!isset($rubric_id)) {
				$rubric_id = 0;
				$mainsym_missed++;
			}
			$query = "UPDATE `import_".$rep."__mainsym` SET rubric_id = $rubric_id WHERE mainsym.mainsym_name LIKE '$mainsym_name'";
			$db->send_query($query);
		}
	}
	$db->free_result($result);
	return $mainsym_missed;
}

function import_mainsym() {
	global $db;
	$rep = $_REQUEST['rep'];
	$query = "SELECT sym_id, sym_name FROM `import_".$rep."__sym`";
	$result = $db->send_query($query);
	while (list($sym_id, $sym_name_str) = $db->db_fetch_row($result)) {
		$sym_name_ar = explode (', ', $sym_name_str, 2);
		$mainsym_name = $db->escape_string($sym_name_ar[0]);
		if (count($sym_name_ar) > 1) {
			$sym_name = $db->escape_string($sym_name_ar[1]);
		} else {
			$sym_name = $mainsym_name;
		}
		$query = "SELECT rubric_id FROM `import_".$rep."__mainsym` WHERE mainsym_name = '$mainsym_name' LIMIT 1";
		$db->send_query($query);
		list($rubric_id) = $db->db_fetch_row();
		$db->free_result();
		$query = "UPDATE `import_".$rep."__sym` SET sym_name = '$sym_name', rubric_id = $rubric_id WHERE sym_id = $sym_id";
		$db->send_query($query);
	}
	$db->free_result($result);
}

function build_symptom_tree() {
	global $db;
	$rep = $_REQUEST['rep'];
	$query = "SELECT sym_id, sym_name FROM `import_".$rep."__sym` WHERE parent_id=-1";
	$result = $db->send_query($query);
	while (list($parent_id, $parent_name) = $db->db_fetch_row($result)) {
		update_sym_name($parent_id, $parent_name, $parent_name);
	}
	$db->free_result($result);
}

function update_sym_name($parent_id, $parent_name, $tree_parent_name) {
	global $db;
	$rep = $_REQUEST['rep'];
	$query = "SELECT sym_id, sym_name FROM `import_".$rep."__sym` WHERE parent_id=$parent_id";
	$result = $db->send_query($query);
	while (list($sym_id, $sym_name) = $db->db_fetch_row($result)) {
		$tree_sym_name = str_replace($parent_name.", ", $tree_parent_name." > ", $sym_name);
		$escaped_tree_sym_name = $db->escape_string($tree_sym_name);
		$query = "UPDATE `import_".$rep."__sym` SET sym_name = '$escaped_tree_sym_name' WHERE sym_id = $sym_id";
		$db->send_query($query);
		update_sym_name($sym_id, $sym_name, $tree_sym_name);
	}
	$db->free_result($result);
}

function get_rem_ids() {
	global $db;
	$rep = $_REQUEST['rep'];
	$rem_missed = 0;
	$query = "SELECT rem_id, rem_short, rem_long FROM `import_".$rep."__rem`";
	$result = $db->send_query($query);
	while (list($rem_id, $rem_short, $rem_name) = $db->db_fetch_row($result)) {
		$rem_name = $db->escape_string($rem_name);
		$rem_short = $db->escape_string(str_replace(".", "", $rem_short));   // Punkte (.) werden entfernt
		$query = "SELECT rem_id FROM remedies WHERE rem_short = '$rem_short.' OR rem_short = '$rem_short' LIMIT 1";
		$db->send_query($query);
		list($remedy_id) = $db->db_fetch_row();
		$db->free_result();
		if (!isset($remedy_id)) {
			$query = "SELECT rem_id FROM rem_alias WHERE alias_short LIKE '$rem_short.' OR alias_short LIKE '$rem_short' LIMIT 1";
			$db->send_query($query);
			list($remedy_id) = $db->db_fetch_row();
			$db->free_result();
		}
		if (!isset($remedy_id)) {
			$query = "SELECT rem_id FROM remedies WHERE rem_name LIKE '$rem_name' LIMIT 1";
			$db->send_query($query);
			list($remedy_id) = $db->db_fetch_row();
			$db->free_result();
		}
		if (!isset($remedy_id)) {
			$remedy_id = 0;
			$rem_missed++;
		}
		$query = "UPDATE `import_".$rep."__rem` SET remedy_id = $remedy_id WHERE rem_id = $rem_id";
		$db->send_query($query);
	}
	$db->free_result($result);
	return $rem_missed;
}

function get_src_ids() {
	global $db;
	$rep = $_REQUEST['rep'];
	$src_missed = 0;
	$query = "SELECT src_id, src_title, src_author FROM `import_".$rep."__src`";
	$result = $db->send_query($query);
	while (list($src_id, $src_title, $src_author) = $db->db_fetch_row($result)) {
		$src_title = $db->escape_string($src_title);
		$src_author = $db->escape_string($src_author);
		$query = "SELECT src_id FROM sources WHERE src_title LIKE '$src_title' AND src_author LIKE '$src_author' LIMIT 1";
		$db->send_query($query);
		list($source_id) = $db->db_fetch_row();
		$db->free_result();
		if (empty($source_id)) {
			$source_id = 0;
			$src_missed++;
		}
		$query = "UPDATE `import_".$rep."__src` SET source_id = '$source_id' WHERE src_id = $src_id";
		$db->send_query($query);
	}
	$db->free_result($result);
	return $src_missed;
}

function get_sym_ids() {
	global $db;
	$rep = $_REQUEST['rep'];
	$query = "SELECT lang_id FROM sources WHERE src_id = '$rep'";
	$db->send_query($query);
	list($lang_id) = $db->db_fetch_row();
	$db->free_result();
	$query = "SELECT sym_id, sym_name, rubric_id FROM `import_".$rep."__sym`";
	$result = $db->send_query($query);
	while (list($sym_id, $sym_name, $rubric_id) = $db->db_fetch_row($result)) {
		$correct = 0;
		$escaped_sym_name = $db->escape_string($sym_name);
		$sym_name_alt = $db->escape_string(preg_replace('/( \\\\> |, )/u', '( \> |, )', preg_quote($sym_name)));
		$symptom_where = "MATCH (symptom) AGAINST ('$escaped_sym_name') AND (symptom LIKE '$escaped_sym_name' OR symptom REGEXP '^$sym_name_alt$')";
		// TODO: Set [mysqld] ft_min_word_len=3 and ft_stopword_file=''
		$query = "SELECT sym_id FROM symptoms WHERE rubric_id = $rubric_id AND lang_id = '$lang_id' AND $symptom_where LIMIT 1";
		$db->send_query($query);
		list($symptom_id) = $db->db_fetch_row();
		$db->free_result();
		if (!isset($symptom_id)) {
			$symptom_id = 0;
		} else {
			$correct = 1;
		}
		$query = "UPDATE `import_".$rep."__sym` SET symptom_id = $symptom_id, correct = $correct WHERE sym_id = $sym_id";
		$db->send_query($query);
	}
	$db->free_result($result);
}

function update_src() {
	global $db;
	$rep = $_REQUEST['rep'];
	$i = 1;
	while ($i <= $_REQUEST['src_missed']) {
		unset($src_id);
		$source_id = "";
		$src_id = $_REQUEST["src_$i"];
		$source_id = $db->escape_string(urldecode($_REQUEST["src_$i"]));
		if (isset($src_id) && !empty($source_id)) {
			$query = "UPDATE `import_".$rep."__src` SET source_id = '$source_id' WHERE src_id = $src_id";
			$db->send_query($query);
		}
		$i++;
	}
}

function update_rem() {
	global $db;
	$rep = $_REQUEST['rep'];
	$rem_missed = $_REQUEST['rem_missed'];
	$i = 1;
	while ($i <= $rem_missed) {
		$rem_id = $_REQUEST["rem_$i"];
		$remedy_id = $_REQUEST["remedy_$i"];
		if (isset($rem_id) && isset($remedy_id)) {
			$query = "UPDATE `import_".$rep."__rem` SET remedy_id = $remedy_id WHERE rem_id = $rem_id";
			$db->send_query($query);
		}
		$i++;
	}
}

function update_mainsym() {
	global $db;
	$rep = $_REQUEST['rep'];
	$mainsym_missed = $_REQUEST['mainsym_missed'];
	$i = 1;
	while ($i <= $mainsym_missed) {
		$mainsym_name = $db->escape_string($_REQUEST["mainsym_$i"]);
		$rubric_id = $_REQUEST["rubric_$i"];
		if (isset($mainsym_name) && isset($rubric_id)) {
			$query = "UPDATE `import_".$rep."__mainsym` SET rubric_id = $rubric_id WHERE mainsym_name LIKE '$mainsym_name'";
			$db->send_query($query);
		}
		$i++;
	}
}

function insert_ref() {
	global $db;
	$rep = $_REQUEST['rep'];
	$query = "SELECT ref_id FROM `import_".$rep."__ref`";
	$result = $db->send_query($query);
	while (list($ref_id) = $db->db_fetch_row($result)) {
		$query = "SELECT symptoms.xref_id FROM `import_".$rep."__sym`, symptoms WHERE `import_".$rep."__sym`.ref_id = $ref_id AND symptoms.sym_id = `import_".$rep."__sym`.symptom_id AND symptoms.xref_id != 0 LIMIT 1";
		$db->send_query($query);
		list($xref_id) = $db->db_fetch_row();
		$db->free_result();
		if (empty($xref_id)) {
			$query = "INSERT INTO `symptom_crossreferences` () VALUES ()";
			$db->send_query($query);
			$xref_id = $db->db_insert_id();
		}
		$query = "UPDATE `import_".$rep."__ref` SET xref_id = $xref_id WHERE ref_id = $ref_id";
		$db->send_query($query);
	}
	$db->free_result($result);
}

function insert_sym() {
	global $db, $session;
	$rep = $_REQUEST['rep'];
	$query = "SELECT lang_id FROM sources WHERE src_id = '$rep'";
	$db->send_query($query);
	list($lang_id) = $db->db_fetch_row();
	$db->free_result();
	$current_user = $session->username;
	$query = "SELECT symptom_id, sym_id, sym_name, ref_id, rubric_id, correct FROM `import_".$rep."__sym`";
	$result = $db->send_query($query);
	while (list($symptom_id, $sym_id, $sym_name, $ref_id, $rubric_id, $correct) = $db->db_fetch_row($result)) {
		$xref_id = 0;
		$escaped_sym_name = $db->escape_string($sym_name);
		if ($ref_id != 0) {
			$query = "SELECT xref_id FROM `import_".$rep."__ref` WHERE ref_id = $ref_id";
			$db->send_query($query);
			list($xref_id) = $db->db_fetch_row();
			$db->free_result();
		}
		if ($symptom_id == 0) {
			$sym_name_alt = $db->escape_string(preg_replace('/( \\\\> |, )/u', '( \> |, )', preg_quote($sym_name)));
			$symptom_where = "MATCH (sym_name) AGAINST ('$escaped_sym_name') AND (sym_name LIKE '$escaped_sym_name' OR sym_name REGEXP '^$sym_name_alt$')";
			// TODO: Set [mysqld] ft_min_word_len=3 and ft_stopword_file=''
			$query = "SELECT symptom_id FROM `import_".$rep."__sym` WHERE sym_id != $sym_id AND symptom_id != 0 AND rubric_id = $rubric_id AND $symptom_where LIMIT 1";
			$db->send_query($query);
			list($symptom_id) = $db->db_fetch_row();
			$db->free_result();
			if (empty($symptom_id)) {
				$query = "INSERT INTO symptoms (symptom, rubric_id, lang_id, xref_id, username) VALUES ('$escaped_sym_name', $rubric_id, '$lang_id', $xref_id, '$current_user')";
				$db->send_query($query);
				$symptom_id = $db->db_insert_id();
			}
			$query = "UPDATE `import_".$rep."__sym` SET symptom_id = $symptom_id WHERE sym_id = $sym_id";
			$db->send_query($query);
		} elseif ($xref_id != 0 || $correct == 1) {
			$set = "";
			if ($xref_id != 0) {
				$set .= "xref_id = $xref_id";
			}
			if ($correct == 1) {
				if ($xref_id != 0) {
					$set .= ", ";
				}
				$set .= "symptom = '$escaped_sym_name'";
			}
			$query = "UPDATE symptoms SET $set WHERE sym_id = $symptom_id";
			$db->send_query($query);
		}
	}
	$db->free_result($result);
}

function insert_remsym() {
	global $db, $session;
	$rep = $_REQUEST['rep'];
	$current_user = $session->username;
	$query = "SELECT remsym.remsym_id, sym.symptom_id, rem.remedy_id, remsym.rem_grade FROM `import_".$rep."__remsym` remsym, `import_".$rep."__sym` sym, `import_".$rep."__rem` rem WHERE sym.sym_id = remsym.sym_id AND rem.rem_id = remsym.rem_id";
	$result = $db->send_query($query);
	while (list($remsym_id, $sym_id, $rem_id, $rem_grade) = $db->db_fetch_row($result)) {
		$query = "REPLACE INTO sym_rem (sym_id, rem_id, grade, src_id, username) VALUES ($sym_id, $rem_id, $rem_grade, '$rep', '$current_user')";
		$db->send_query($query);
		$rel_id = $db->db_insert_id();
		$new_remsym = 1;
		if (empty($rel_id)) {
			$query = "SELECT rel_id FROM sym_rem WHERE sym_id = $sym_id AND rem_id = $rem_id AND src_id = '$rep'";
			$db->send_query($query);
			list($rel_id) = $db->db_fetch_row();
			$db->free_result();
			$new_remsym = 0;
		}
		$query = "SELECT `import_".$rep."__src`.source_id FROM `import_".$rep."__remsymsrc`, `import_".$rep."__src` WHERE `import_".$rep."__remsymsrc`.remsym_id = $remsym_id AND `import_".$rep."__src`.src_id = `import_".$rep."__remsymsrc`.src_id";
		$result_2 = $db->send_query($query);
		while (list($source_id) = $db->db_fetch_row($result_2)) {
			$sym_rem_ref_num = 0;
			if ($new_remsym == 0) {
				$query = "SELECT rel_id FROM sym_rem_refs WHERE rel_id = $rel_id AND src_id = '$source_id' LIMIT 1";
				$db->send_query($query);
				$sym_rem_ref_num = $db->db_num_rows();
				$db->free_result();
			}
			if ($sym_rem_ref_num == 0) {
				$query = "INSERT INTO sym_rem_refs (rel_id, src_id, username) VALUES ($rel_id, '$source_id', '$current_user')";
				$db->send_query($query);
			}
		}
		$db->free_result($result_2);
	}
	$db->free_result($result);
}

function get_imported_reps() {
	global $db;
	$imported_reps_ar = array();
	$query = "SELECT table_name FROM information_schema.tables WHERE table_name LIKE 'import_%__rem' AND table_schema = 'OpenHomeopath';";
	$db->send_query($query);
	while (list($import_table) = $db->db_fetch_row()) {
		preg_match('/import_(.*)__rem/', $import_table, $imported_rep);
		if ($imported_rep[1] !== 'kent.de') {
			$imported_reps_ar[] = $imported_rep[1];
		}
	}
	$db->free_result();
	return $imported_reps_ar;
}
