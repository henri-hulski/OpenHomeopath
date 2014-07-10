<?php
chdir("..");
include_once ("include/classes/login/session.php");
if (!$session->isAdmin()) {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = "../login.php?admin%buildtree.php";
	header("Content-Type: text/html;charset=utf-8"); 
	header("Location: http://$host$uri/$extra");
	die();
} else {
	$skin = $session->skin;
	include("skins/$skin/header.php");
?>
<h1>
   <?php echo _("Build symptomtree"); ?>
</h1>
<?php
	$rep = "";
	if (!empty($_REQUEST['rep'])) {
		$rep = $_REQUEST['rep'];
	}
	if (empty($_POST['buildtree'])) {
?>
<form method="POST" action="buildtree.php">
  <input type='hidden' name='buildtree' value='1'>
  <input type='hidden' name='rep' value='<?php echo $rep; ?>'>
  <div style="text-align: center;">
    <input type='submit' value=' <?php echo _("Build symptomtree"); ?> '>
  </div>
</form>
<?php
	} else {
		include ("include/functions/import.php");
		build_symptom_tree();
		get_sym_ids();
	}
	include("skins/$skin/footer.php");
}
?>
