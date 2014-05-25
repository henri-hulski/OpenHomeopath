<?php
chdir("..");
include_once ("include/classes/login/session.php");
include_once ("include/phorum/mods/embed_phorum/syncuser.php");
$query = "SELECT id_user, username, email FROM users u WHERE NOT EXISTS (SELECT 1 FROM `homeophorum__users` hu WHERE u.`username` = hu.`username`)";
$db->send_query($query);
while(list($id_user, $username, $email) = $db->db_fetch_row()) {
	/* insert users into the Phorum user table */
	$user_ar = array("user_id" => $id_user, "username" => $username, "email" => $email);
	embed_phorum_syncuser($user_ar);
}
$db->free_result();
?>
