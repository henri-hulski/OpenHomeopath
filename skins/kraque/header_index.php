<?php
if (empty($lang)) {
	$lang = $session->lang;
}

header("Expires: Mon, 1 Dec 2006 01:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-Type: text/html;charset=utf-8"); 
?>
<!DOCTYPE html>
<html lang="<?php echo($lang); ?>">
  <head>
    <title>
<?php
if(!empty($head_title)) {
	echo "      $head_title\n";
} else {
	echo "      OpenHomeo.org: OpenHomeopath\n";
}
?>
    </title>
    <meta charset="utf-8">
    <meta name="author" content="Henri Schumacher">
<?php
if(!empty($meta_description)) {
	echo "      <meta name='description' content='$meta_description'>\n";
} else {
	echo "      <meta name='description' content='Online Repertorium, Materia medica, Bibliothek, Hom&ouml;opathische Wissenssammlung'>\n";
}
if(!empty($meta_keywords)) {
	echo "      <meta name='keywords' content='$meta_keywords Heilmittel, Remedy, Hom&ouml;opathie, Homeopathy, Homoeopathie, Homoeopathy, Repertorium, Online Repertorium,Repertory, Online Repertory, Materia Medica, Kent, Arzneimittellehre, Samuel Hahnemann, B&uuml;cher, Books, Book, Buch, Hom&ouml;opathische Online Bibliothek, Bibliothek, Library'>\n";
} else {
	echo "      <meta name='keywords' content='Hom&ouml;opathie, Homeopathy, Homoeopathie, Homoeopathy, Repertorium, Online Repertorium,Repertory, Online Repertory, Materia Medica, Kent, Arzneimittellehre, Samuel Hahnemann, B&uuml;cher, Books, Book, Buch, Hom&ouml;opathische Online Bibliothek, Bibliothek, Library'>\n";
}
?>
    <meta name="robots" content="all">
    <meta name="robots" content="index,follow">
    <meta name="revisit-after" content="7 days">
    
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<!--[if lt IE 9]>
	  <script src="javascript/html5shiv.min.js"></script>
	<![endif]-->
<?php
include("javascript/locale.php");
?>
    <script src="openhomeopath/javascript/openhomeopath.js"></script>
    <!-- thb -->
    <script src="scriptaculous-js-1.8.2/lib/prototype.js"></script>
    <script src="scriptaculous-js-1.8.2/src/scriptaculous.js"></script>
    <script src="scriptaculous-js-1.8.2/menu.js"></script>
    <link href="menu.css" rel="stylesheet" />
  </head>
  <body>
<div id="wrapper">

<?php
include("skins/kraque/frame_index.php")
?>
