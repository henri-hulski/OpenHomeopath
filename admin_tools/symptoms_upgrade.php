<?php
chdir("..");
include_once ("include/classes/login/session.php");

function retrieve_symptoms() {
	global $db;
	$src_ar = array('old' => array('kent', 'kent_de', 'bogboen', 'boen', 'publicum09'), 'new' => array('kent.en', 'kent.de', 'boenn_bogner', 'boenn_allen', 'openrep_pub'));
	$query = "SELECT symptom_id_old FROM symptoms_upgrade WHERE sym_id_new = 0";
	$result = $db->send_query($query);
	while (list($symptom_id) = $db->db_fetch_row($result)) {
		unset($sym_id);
		foreach ($src_ar['old'] as $key => $src_old) {
			$query = "SELECT i.symptom_id FROM `import_" . $src_ar['new'][$key] . "__sym` i, `openrep_" . $src_old . "_sym` o WHERE i.sym_id = o.sym_id AND o.symptom_id = $symptom_id";
			$db->send_query($query);
			list($sym_id) = $db->db_fetch_row();
			$db->free_result();
			if (!empty($sym_id)) {
				break;
			}
		}
		if (empty($sym_id)) {
			$sym_id = 0;
		}
		$query = "UPDATE `symptoms_upgrade` SET `sym_id_new` = $sym_id WHERE `symptom_id_old` = $symptom_id";
		$db->send_query($query);
	}
	$db->free_result($result);
}

function get_symptoms() {
	global $db;
	$query = "SELECT su.`symptom_id_old`, s.symptom_name, s.rubrik_id, s.sprache_id, s.native FROM `symptoms_upgrade` su LEFT JOIN `symptome` s ON s.symptom_id = su.`symptom_id_old` WHERE su.`sym_id_new` = 0";
	$result = $db->send_query($query);
	while (list($symptom_id, $sym_name, $rubric_id, $lang_id, $native) = $db->db_fetch_row($result)) {
		$escaped_sym_name = $db->escape_string($sym_name);
		$sym_name_alt = $db->escape_string(preg_replace('/( \\\\> |, )/u', '( \> |, )', preg_quote($sym_name)));
		$symptom_where = "(s1.symptom LIKE '$escaped_sym_name' OR s1.symptom REGEXP '^$sym_name_alt$')";
		if ($native == 0) {
			$query = "SELECT s1.sym_id FROM symptom_translations s1, symptoms s2 WHERE s1.sym_id = s2.sym_id AND s1.lang_id = '$lang_id' AND s2.rubric_id = $rubric_id AND $symptom_where LIMIT 1";
		} else {
			$query = "SELECT sym_id FROM symptoms s1 WHERE rubric_id = $rubric_id AND lang_id = '$lang_id' AND $symptom_where LIMIT 1";
		}
		$db->send_query($query);
		list($sym_id) = $db->db_fetch_row();
		$db->free_result();
		if (!isset($sym_id) && $native != 0) {
			$query = "SELECT sym_id FROM symptoms s1 WHERE rubric_id = $rubric_id AND $symptom_where LIMIT 1";
			$db->send_query($query);
			list($sym_id) = $db->db_fetch_row();
			$db->free_result();
		}
		if (!isset($sym_id)) {
			$sym_id = 0;
		}
		$query = "UPDATE `symptoms_upgrade` SET `sym_id_new` = $sym_id WHERE `symptom_id_old` = $symptom_id";
		$db->send_query($query);
	}
	$db->free_result($result);
}


if(!$session->isAdmin()) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php?url=admin%symptoms_upgrade.php";
	header("Content-Type: text/html;charset=utf-8");
	header("Location: http://$host$uri/$extra");
	die();
} else {
	$skin = $session->skin;
	include("./skins/$skin/header.php");
	if (empty($_POST['get_symptoms'])) {
?>
<h1>
   <?php echo _("Archive the old symptom-ids during symptom-upgrade"); ?>
</h1>
<div style="text-align: center;">
   <form method="POST" action="symptoms_upgrade.php" accept-charset="utf-8">
      <input type='hidden' name='get_symptoms' value='1'>
      <input type='submit' value=' <?php echo _("Archive symptoms"); ?> '>
   </form>
</div>
<br>
<?php
	} else {
		retrieve_symptoms();
		get_symptoms();
		echo "<p>" . _("<strong>Congratulations!</strong> The symptoms are archived.") . "</p>\n";
	}
?>

<?php
	include("./skins/$skin/footer.php");
}
?>
