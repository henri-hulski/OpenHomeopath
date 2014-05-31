<?php
include ("include/functions/layout.php");
if (TABBED) {
	$rep_url = "openhomeopath/index.php?tab=0";
	$materia_url = "openhomeopath/index.php?tab=2";
	$user_url = "openhomeopath/index.php?tab=4";
} else {
	$rep_url = "openhomeopath/repertori.php";
	$materia_url = "openhomeopath/materia.php";
	$user_url = "openhomeopath/userinfo.php?user=" . $session->username;
}
$lang = $session->lang;
?>
<a name="up" id="up" title="<?php echo _("Top of the page"); ?>"></a>
<?php
if($session->logged_in){
?>
<div id="menu">
		<span style="float: left;">
            <a style="color: #336633; font-size: 14px;" href="../index.php">
                <img height="17" border="0" alt="OpenHomeo.org" src="openhomeopath/skins/kraque/img/punkte_d9b7ea.gif"/>
            </a>
        </span>
		<ul class="level1" id="root">
		<li>
		    <a href="../index.php" style="color: #336633; font-size: 13px;"><b>OpenHomeo.org</b></a>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="<?php echo($rep_url);?>"><?php echo _("Repertorize"); ?></a>
		    <ul class="level2">
		      <li><a href="<?php echo($rep_url);?>"><?php echo _("New"); ?></a>
		      </li>
		      <?php
	           echo ("<li><a href='$user_url#reps'>" . _("Open") . "</a></li>");
		      echo ("<li><a href='$user_url#rep_custom'>" . _("Select sources") . "</a></li>");
		      ?>
		      </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="<?php echo($materia_url);?>"><?php echo _("Materia Medica"); ?></a>
		  <ul class="level2">
		  <?php
		      echo ("<li><a href='openhomeopath/materia-medica.php'>" . _("Remedy") . ": A-Z</a></li>");
	           echo ("<li><a href='$user_url#materia_custom'>" . _("Select sources") . "</a></li>");
		  ?>
		  </ul>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="openhomeopath/datadmin.php"><?php echo _("Data maintenance"); ?></a>
		    <ul class="level2">
		    <li><a href="openhomeopath/express.php"><?php echo _("Expresstool"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=remedies"><?php echo _("Remedy"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=rem_alias"><?php echo _("Remedy aliases"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=sources"><?php echo _("Sources"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=symptoms"><?php echo _("Symptoms"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="openhomeopath/support.php"><?php echo _("Support"); ?></a></li>
		  <li class="sep">|</li>
		  <li><a href="openhomeopath/homeophorum.php"><?php echo _("Forum"); ?></a></li>
		  <li class="sep">|</li>
		  <li>
		    <a href="openhomeopath/help/<?php echo $lang; ?>/index.php"><?php echo _("Help"); ?></a>
		    <ul class="level2">
		    <li><a href="openhomeopath/help/<?php echo $lang; ?>/manual.php#repertorization"><?php echo _("Repertorization"); ?></a></li>
		      <li><a href="openhomeopath/help/<?php echo $lang; ?>/user.php"><?php echo _("User administration"); ?></a></li>
		      <li><a href="openhomeopath/help/<?php echo $lang; ?>/datadmin.php"><?php echo _("Data maintenance"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="openhomeopath/doc/<?php echo $lang; ?>/info.php"><?php echo _("Info"); ?></a></li>
		  <li class="sep">|</li>
		  <?php
	           echo ("<li><a href='$user_url'><img src='openhomeopath/skins/kraque/img/user.gif' width='13' height='12' border='0' alt='Benutzer'>" . _("My account") . "</a>");
	           echo ("<ul class='level2'>");
	               echo ("<li><a href='$user_url#rep_custom'>" . _("Settings") . "</a></li>");
	               echo ("<li><a href='$user_url#reps'>" . _("Repertorizations") . "</a></li>");
		      ?>
		  <li><a href="openhomeopath/useredit.php"><?php echo _("Personal particulars"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="openhomeopath/include/classes/login/process.php"><?php echo _("Logout"); ?></a>
		  </li>
		  <li class="sep">|</li>
			<li>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="5VCBZJRKXM9EC">
					<input type="hidden" name="image_url" value="http://www.openhomeo.org/openhomeopath/img/openhomeopath.gif">
					<input type="image" src="http://www.openhomeo.org/openhomeopath/img/spenden_<?php echo $lang; ?>.gif" border="0" name="submit" alt="Jede Spende ist uns sehr willkommen und hilft der Entwicklung von OpenHomeopath.">
					<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
				</form>
			</li>
			<li class="sep">|</li>
			<li><span style="font-size:0.7em;"><?php echo view_lang_menu('index'); ?></span></li>
		  <li class='sep'>|</li>
		  <li>
		    <a href='openhomeopath/support.php'><strong><?php echo _("Received donations"); ?></strong></a>
		    <ul class='level2 donations'>
<?php
	echo $magic_hat->print_received_donations();
?>
		    </ul>
		  </li>
		</ul>
		</div>
<?php
}else{
?>
<div id="menu">
		<span style="float: left;">
            <a href="../index.php">
                <img height="17" border="0" alt="OpenHomeo.org" src="openhomeopath/skins/kraque/img/punkte_d9b7ea.gif"/>
            </a>
        </span>
		<ul class="level1" id="root">
		<li>
		    <a href="../index.php" style="color: #336633; font-size: 13px;"><b>OpenHomeo.org</b></a>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="<?php echo($rep_url);?>"><?php echo _("Repertorize"); ?></a>
		    <ul class="level2">
		      <li><a href="<?php echo($rep_url);?>"><?php echo _("New"); ?></a></li>
		  </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="<?php echo($materia_url);?>"><?php echo _("Materia Medica"); ?></a>
		  <ul class="level2">
		  <?php
		      echo ("<li><a href='openhomeopath/materia-medica.php'>" . _("Remedy") . ": A-Z</a></li>");
		  ?>
		  </ul>
		  </li>
		  <li class="sep">|</li>
		  <li>
		    <a href="openhomeopath/datadmin.php"><?php echo _("Data maintenance"); ?></a>
		    <ul class="level2">
		    <li><a href="openhomeopath/express.php"><?php echo _("Expresstool"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=remedies"><?php echo _("Remedy"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=rem_alias"><?php echo _("Remedy aliases"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=sources"><?php echo _("Sources"); ?></a></li>
		      <li><a href="openhomeopath/datadmin.php?table_name=symptoms"><?php echo _("Symptoms"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="openhomeopath/support.php"><?php echo _("Support"); ?></a></li>
		  <li class="sep">|</li>
		  <li><a href="openhomeopath/homeophorum.php"><?php echo _("Forum"); ?></a></li>
		  <li class="sep">|</li>
		  <li>
		    <a href="openhomeopath/help/<?php echo $lang; ?>/index.php"><?php echo _("Help"); ?></a>
		    <ul class="level2">
		    <li><a href="openhomeopath/help/<?php echo $lang; ?>/manual.php#repertorization"><?php echo _("Repertorization"); ?></a></li>
		      <li><a href="openhomeopath/help/<?php echo $lang; ?>/user.php"><?php echo _("User administration"); ?></a></li>
		      <li><a href="openhomeopath/help/<?php echo $lang; ?>/datadmin.php"><?php echo _("Data maintenance"); ?></a></li>
		    </ul>
		  </li>
		  <li class="sep">|</li>
		  <li><a href="openhomeopath/doc/<?php echo $lang; ?>/info.php"><?php echo _("Info"); ?></a></li>
		  <li class="sep">|</li>
		  <li><a href="openhomeopath/login.php"><img width="13" height="12" border="0" alt="Benutzer" src="openhomeopath/skins/kraque/img/user.gif" border="0" /> <?php echo _("Log in"); ?></a>
		  <li class="sep">|</li>
		  <li>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="4053996">
				<input type="hidden" name="image_url" value="http://openhomeo.org/openhomeopath/img/openhomeopath.gif">
				<input type="image" src="http://openhomeo.org/openhomeopath/img/spenden_<?php echo $lang; ?>.gif" border="0" name="submit" alt="Jede Spende ist uns sehr willkommen und hilft der Entwicklung von OpenHomeopath.">
				<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
			</form>
		</li>
		<li class="sep">|</li>
		<li><span style="font-size:0.7em;"><?php echo view_lang_menu('index'); ?></span></li>
		  <li class='sep'>|</li>
		  <li>
		    <a href='openhomeopath/support.php'><strong><?php echo _("Received donations"); ?></strong></a>
		    <ul class='level2 donations'>
<?php
	echo $magic_hat->print_received_donations();
?>
		    </ul>
		  </li>
		</ul>
		</div>
<?php
}
?>

<div class="clear"></div>
			<!-- End Navigation -->
