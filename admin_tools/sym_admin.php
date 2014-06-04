<?php
chdir("..");
include_once ("include/classes/login/session.php");

if(!$session->isAdmin()) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php?url=admin%sym_admin.php";
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$extra");
	die();
} else {
	$head_title = _("Administration of the symptom tables") . " :: OpenHomeopath";
	$skin = $session->skin;
	include("./skins/$skin/header.php");
	if (empty($_POST['add_missing_parents']) && empty($_POST['restruct']) && empty($_POST['update_lang_symptom_tables'])) {
?>
<h1>
   <?php echo _("Administration of the symptom tables"); ?>
</h1>
<h3>
   <?php echo _("Complete parent-rubrics"); ?>
</h3>
<p>
   <?php echo _("If several rubrics exists, which starts with the same rubric before a '>' but the parent-rubric doesn't exists, it will be generated. If the rubric before '>' exists only once, the '>' will be converted in a comma (',')."); ?>
</p>
<p>
   <?php echo _("This needs some time. Please be patient."); ?>
</p>
<div style="text-align: center;">
   <form method="POST" action="sym_admin.php" accept-charset="utf-8">
      <input type='hidden' name='add_missing_parents' value='1'>
      <input type='submit' value=' <?php echo _("complete parent-rubrics"); ?> '>
   </form>
</div>
<br>
<h3>
   <?php echo _("Update the tree-structure"); ?>
</h3>
<p>
   <?php echo _("The symptom table will be restructured."); ?><br>
   <?php echo _("First all symptoms that have no '>' in the symptom name will be parsed. Then we check if the symptom name occur again in the table followed by '>'.  In this case these symptoms get the \"parent_id\" of the superior symptom. Next all symptoms with one '>' will be parsed and so on."); ?>
</p>
<p>
   <?php echo _("The restructuring needs some time. Please be patient."); ?>
</p>
<div style="text-align: center;">
   <form method="POST" action="sym_admin.php" accept-charset="utf-8">
      <input type='hidden' name='restruct' value='1'>
      <input type='submit' value=' <?php echo _("Restructure the symptom table"); ?> '>
   </form>
</div>
<br>
<h3>
   <?php echo _("Update of the language-symptom-tables"); ?>
</h3>
<p>
   <?php echo _("The language-symptom-tables (\"sym__de\", \"sym__en\", etc.) will be updated on the base of the main symptom-table (\"symptoms\") . This is necessary, when new symptoms were added."); ?>
</p>
<p>
   <?php echo _("The update needs some time. Please be patient."); ?>
</p>
<div style="text-align: center;">
   <form method="POST" action="sym_admin.php" accept-charset="utf-8">
      <input type='hidden' name='update_lang_symptom_tables' value='1'>
      <input type='submit' value=' <?php echo _("Update the language-symptom-tables"); ?> '>
   </form>
</div>
<br>
<?php
	} elseif (!empty($_POST['add_missing_parents'])) {
		$log = $db->add_missing_parents();
		echo $log;
	} elseif (!empty($_POST['restruct'])) {
		$log = $db->update_symptom_tree();
		echo $log;
	} elseif (!empty($_POST['update_lang_symptom_tables'])) {
		$update_tree = true;
		$log = $db->update_symptom_tables($update_tree);
		$lang = $session->lang;
		$query = "SELECT lang_$lang FROM languages WHERE sym_lang != 0";
		$db->send_query($query);
		while (list($lang_name) = $db->db_fetch_row()) {
			$sym_lang_ar[] = $lang_name;
		}
		$db->free_result();
		$sym_lang = implode(", ", $sym_lang_ar);
		echo "<p>" . _("The following language-symptom-tables were parsed:") . " <strong>" . $sym_lang . "</strong></p>";
		echo $log;
	}
?>

<?php
	include("./skins/$skin/footer.php");
}
?>
