<?php
if (!empty($_REQUEST['ajax'])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$username = $session->username;
}
if (isset($_REQUEST['custom_rep_submit'])) { // Die Form wird ausgewertet
	unset($src_rep);
	unset($custom_rep);
	$src_rep = $_REQUEST['src'];
	$query = "UPDATE users SET src_rep='" . $src_rep . "' WHERE username='$username'";
	$db->send_query($query);
	$query = "DELETE FROM custom_rep WHERE username='$username'";
	$db->send_query($query);
	if ($src_rep == 'custom') {
		$src_no_ar = explode('_', $_REQUEST['src_sel']);
		$custom_rep_ar = $db->get_source_id($src_no_ar);
		foreach ($custom_rep_ar as $src_id) {
			$query = "INSERT INTO custom_rep (username, src_id) VALUES ('$username', '$src_id')";
			$db->send_query($query);
		}
	}
	$db->create_custom_table("sym_rem");
	$db->create_custom_symptom_table();
}
$query = "SELECT src_rep FROM users WHERE username='$username'";
$db->send_query($query);
list($src_rep) = $db->db_fetch_row();
$db->free_result();
if ($src_rep == 'custom') {
	$custom_rep_ar = $db->get_custom_src($username, 'custom_rep');
	foreach ($custom_rep_ar as $src_id) {
		$query = "SELECT src_title FROM sources WHERE src_id='$src_id'";
		$db->send_query($query);
		list($src_title) = $db->db_fetch_row();
		$rep_src_ar[] = "$src_title ('$src_id')";
		$db->free_result();
	}
}
if ($src_rep == 'all') {
	echo ("<span class='alert_box'>" . _("You're using at the moment, the <strong>full repertory</strong>.") . "</span>\n");
} elseif ($sympt_lang = $db->get_lang_only_symptom_table()) {
	printf("<span class='alert_box'>" . _("You're using at the moment all <strong>symptoms in %s</strong>.") . "</span>\n", $sympt_lang['name']);
} else {
	echo ("<span class='alert_box'>" . _(" You're using at the moment a <strong>personalized repertory</strong>") . " " . ngettext("with the source", "with the sources", count($rep_src_ar)) . " <strong><em>" . implode(", ", $rep_src_ar) . "</em></strong>.</span>\n");
}
?>
