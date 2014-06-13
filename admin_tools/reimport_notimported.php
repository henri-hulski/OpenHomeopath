<?php
chdir("..");
include_once ("include/classes/login/session.php");
if (!$session->isAdmin()) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "login.php?url=admin%reimport_notimported.php";
	header("Content-Type: text/html;charset=utf-8");
	header("Location: http://$host$uri/$extra");
	die();
} else {
	$skin = $session->skin;
	include("skins/$skin/header.php");
?>
<h1>
   <?php echo _("Reimporting of not imported repertories"); ?>
</h1>
<?php
	if (empty($_REQUEST['export']) && empty($_REQUEST['import'])) {
?>
<p>
   <?php echo _("Here you can export manually added rubrics from the OpenHomeopath-database for reimporting them in the new tables."); ?>
</p>
<h2>
   <?php echo _("Export of manually added rubrics"); ?>
</h2>
<p>
   <?php echo _("Manually added rubrics will be exported in import-tables."); ?>
</p>
<form method="POST" action="reimport_notimported.php">
  <input type='hidden' name='export' value='1'>
  <div style="text-align: center;">
    <input type='submit' value=' <?php echo _("Export now"); ?> '>
  </div>
</form>
<?php
	} elseif (!empty($_REQUEST['export'])) {
		include ("include/functions/import_custom.php");
		create_import_tables();
		import_custom_rubrics();
		get_sym_ids();
		echo "<p>" . _("The import of MySQL-import-tables is finalized.") . "<br>\n";
		echo _("Please check the tables:") . " <strong>import_custom__*</strong></p>\n";
		echo "<p>" . _("Now you can insert the new repertory in the OpenHomeopath-database.") . "</p>\n";
?>
<h2>
   <?php echo _("Import in the new OpenHomeopath-tables"); ?>
</h2>
<p>
   <?php echo _("The rubrics will be reimported from the import-tables (import_custom__*) in the OpenHomeopath-tables."); ?>
</p>
<form method="POST" action="reimport_notimported.php">
  <input type='hidden' name='import' value='1'>
  <div style="text-align: center;">
    <input type='submit' value=' <?php echo _("Reimport in the new tables"); ?> '>
  </div>
</form>
<?php
	} elseif (!empty($_REQUEST['import'])) {
		include ("include/functions/import_custom.php");
		insert_sym();
		insert_remsym();
		echo "<p>" . _("<strong>Congratulations!</strong> Reimporting of manually added rubrics in OpenHomeopath is finalized.") . "</p>\n";
	}
	include("skins/$skin/footer.php");
}
?>
