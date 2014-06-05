<?php
if (!empty($_REQUEST['ajax'])) {
	chdir("..");
	include_once ("include/classes/login/session.php");
	$username = $session->username;
}
// Analyse the form
if (isset($_REQUEST['custom_materia_submit'])) {
	unset($src_materia);
	unset($custom_materia);
	$src_materia = $_REQUEST['src'];
	$query = "UPDATE users SET src_materia='" . $src_materia . "' WHERE username='$username'";
	$db->send_query($query);
	$query = "DELETE FROM custom_materia WHERE username='$username'";
	$db->send_query($query);
	if ($src_materia == 'custom') {
		$src_no_ar = explode('_', $_REQUEST['src_sel']);
		$custom_materia_ar = $db->get_source_id($src_no_ar);
		foreach ($custom_materia_ar as $src_id) {
			$query = "INSERT INTO custom_materia (username, src_id) VALUES ('$username', '$src_id')";
			$db->send_query($query);
		}
	}
	$db->create_custom_table("materia");
}
$query = "SELECT src_materia FROM users WHERE username='$username'";
$db->send_query($query);
list($src_materia) = $db->db_fetch_row();
$db->free_result();
if ($src_materia == 'custom') {
	$custom_materia_ar = $db->get_custom_src($username, 'custom_materia');
	foreach ($custom_materia_ar as $src_id) {
		$query = "SELECT src_title FROM sources WHERE src_id='$src_id'";
		$db->send_query($query);
		list($src_title) = $db->db_fetch_row();
		$materia_src_ar[] = "$src_title ('$src_id')";
		$db->free_result();
	}
}
if ($src_materia == 'all') {
	echo ("<span class='alert_box'>" . _("You're using at the moment the full <strong> Materia Medica </strong>.") . "</span>\n");
} else {
	echo ("<span class='alert_box'>" . _("You're using at the moment a <strong> personalized Materia Medica </strong>") . " " . ngettext("with the source", "with the sources", count($materia_src_ar)) . " <strong><em>" . implode(", ", $materia_src_ar) . "</em></strong>.</span>\n");
}
?>
