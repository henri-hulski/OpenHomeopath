<?php

class HomeophorumConnector extends PhorumConnectorBase {
	public $name = "Homeophorum";

	function get_template() {
		global $db;
		$session = new Session;
		$current_skin = $session->skin;
		$query = "SELECT phorum_template FROM skins WHERE skin_name = '$current_skin'";
		$db->send_query($query);
		$template = $db->db_fetch_row();
		$db->free_result();
		if (!empty($template[0])) {
			$template = $template[0];
		} else {
			$template = "embed_homeophorum";
		}
		return $template;
	}

	// Return the user_id for the logged in user.
	function get_user_id() {
		$PHORUM_DIR = getcwd();
		if (! chdir("../..")) {
			die("embed_phorum error: Cannot change directory to main directory.");
		}
		require_once("include/classes/login/session.php");
		chdir($PHORUM_DIR);
		if(isset($session->id_user)){
			$user_id = $session->id_user;
		} else {
			$user_id = NULL;
		}
		return $user_id;
	}

	// Setup Phorum's page elements in the master templating system.
	function process_page_elements($elements) {
		global $db;
		$session = new Session;
		$magic_hat = new MagicHat;
		$skin = $session->skin;
		$PHORUM_DIR = getcwd();
		if (! chdir("../..")) {
			die("embed_phorum error: Cannot change directory to main directory.");
		}
		header("Expires: Mon, 1 Dec 2006 01:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Content-Type: text/html;charset=utf-8");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"
>
<html>
  <head>
    <title><?php print $elements["title"] ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Content-Language" content="de,en">
    <meta name="author" content="Henri Schumacher">
    <meta name="robots" content="all">
    <meta name="robots" content="index,follow">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <script src="./javascript/openhomeopath.js" type="text/javascript"></script>
<?php
		if ($skin == 'kraque') {
?>
    <script src="../scriptaculous-js-1.8.2/lib/prototype.js" type="text/javascript"></script>
    <script src="../scriptaculous-js-1.8.2/src/scriptaculous.js" type="text/javascript"></script>
    <script src="../scriptaculous-js-1.8.2/menu.js" type="text/javascript"></script>
<?php
		}
		print $elements["redirect_meta"];
		print $elements["style"];
		print $elements["head_data"];
?>
  </head>
  <body id="default" onload="<?php print $elements["body_onload"] ?>">
    <div id="onwork"><span class='onwork'><?php echo _("I'm on work ...."); ?></span></div>
<?php
		include("./skins/$skin/frame.php");
?>
                <h1>
                  <?php echo _("Homeophorum"); ?>
                </h1>
        <hr/>
        <?php print $elements["body_data"] ?>
        <hr/>
<?php
include("./skins/$skin/footer.php");
chdir($PHORUM_DIR);
?>
<?php
	}
}
?>
