<?php
function get_replace_query($table) {
	$old_rubric_id = $_POST['old_rubric'];
	$new_rubric_id = $_POST['new_rubric'];
	return "UPDATE `$table` SET rubric_id = $new_rubric_id WHERE rubric_id = $old_rubric_id";
}

chdir("..");
include_once ("include/classes/login/session.php");
if(!$session->isAdmin()) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "../login.php?url=admin%replace_mainrubric.php";
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$extra");
	die();
} else {
	$skin = $session->skin;
	include("skins/$skin/header.php");
	if (!empty($_POST['update_db'])) {
		set_time_limit(0);
		ignore_user_abort(true);
		$tables_contain_rubric_id_ar = array('symptoms');
		$query = "SELECT table_name FROM information_schema.tables WHERE ((table_name LIKE 'sym\_\_%' AND table_name NOT LIKE '%\_only') OR table_name LIKE 'import\_%\_\_sym') AND table_schema = 'OpenHomeopath';";
		$db->send_query($query);
		while (list($table) = $db->db_fetch_row()) {
			$tables_contain_rubric_id_ar[] = $table;
		}
		$db->free_result();
		foreach($tables_contain_rubric_id_ar as $table) {
			$db->send_query(get_replace_query($table));
		}
	echo "<p><strong>Done!</strong></p>";
	}
?>
<h1>
   Eine Hauptrubrik in der gesamten Datenbank durch eine andere ersetzen
</h1>
<p>
  Hier kannst du eine Hauptrubrik durch eine andere ersetzen. Die Änderung beinhaltet alle Tabellen, die diese Hauptrubrik referenzieren außer der Tabelle 'main_rubrics' selbst.
</p>
  <form method="POST" action="replace_mainrubric.php" accept-charset="utf-8">
    <table style="border:0; text-align:left;">
      <tr>
        <td>
          <label for="old_rubric"> <span class="label"><?php echo _("Main rubric be replaced"); ?></span> </label>
        </td>
        <td>
          <label for="new_rubric"> <span class="label"><?php echo _("Main rubric to insert"); ?></span> </label>
        </td>
        <td></td>
      </tr>
      <tr>
        <td>
          <select class="drop-down" name="old_rubric" id="old_rubric" size="1">
<?php
$query = "SELECT rubric_id, rubric_de, rubric_en FROM main_rubrics";
$db->send_query($query);
while(list($old_rubric_id, $old_rubric_de, $old_rubric_en) = $db->db_fetch_row()) {
	echo ("          <option value='$old_rubric_id'>$old_rubric_de - $old_rubric_en</option>\n");
}
$db->free_result();
?>
          </select>
        </td>
        <td>
          <select class="drop-down" name="new_rubric" id="new_rubric" size="1">
<?php
$query = "SELECT rubric_id, rubric_de, rubric_en FROM main_rubrics";
$db->send_query($query);
while(list($new_rubric_id, $new_rubric_de, $new_rubric_en) = $db->db_fetch_row()) {
	echo ("          <option value='$new_rubric_id'>$new_rubric_de - $new_rubric_en</option>\n");
}
$db->free_result();
?>
          </select>
        </td>
        <td>
          <input class='submit' type='submit' value=' Hauptrubrik ersetzen '>
        </td>
      </tr>
    </table>
	<div class="clear"></div>
    <input type='hidden' name='update_db' value='1'>
    <br>
  </form>

<?php
	include("skins/$skin/footer.php");
}
?>
