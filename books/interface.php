<!---
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Demos
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 *
 */
-->

  <link href="books/books_browser.css" type="text/css" rel="stylesheet"/>
  <script type="text/javascript" src="http://www.google.com/jsapi">
  </script>
  <script type="text/javascript">
    function load_viewport(identifier, viewport_div_id) {
      var viewport_div = document.getElementById(viewport_div_id);
      var rightpane_div = viewport_div.parentNode;
      rightpane_div.style.display = 'table-cell';
      viewport_div.innerHTML = 'Loading...';

      var viewer = new google.books.DefaultViewer(viewport_div);
      <?php

        $searchTerm =str_replace  ("\"","&quot;",$_GET['searchTerm']);
        echo 'viewer.highlight(\''. $searchTerm . '\');';
    ?>
      viewer.load(identifier, handle_not_found);
    }

    function on_load() {
    }

    function handle_not_found() {
      var viewport_div = document.getElementById(viewport_div_id);
      viewport_div.parentNode.style.display = 'none';
    } 

    google.load('books', '0');
    google.setOnLoadCallback(on_load);
  </script>


<span clear="all" />
<div id="mainSearchBox">
  <h2>Bücher durchsuchen:</h2>
  <form id="mainSearchForm" action="books.php">
  <?php

        $searchTerm = empty($_GET['searchTerm']) ? '' : str_replace  ("\"","&quot;",$_GET['searchTerm']);
        echo '<input name="searchTerm" type="text" size="50" style="font-size:16px;margin:2px;" value="' . $searchTerm . '">';
    ?>&nbsp;<input type="submit" value="Suchen" style="font-size:16px;font-weigt:bold;margin:2px;">&nbsp;&nbsp;<a href="javascript:popup_url('help/<?php echo $lang; ?>/booksearch.php',600,500)">Hilfe</a>
    <br /><b>Anzeigen:</b>
    <select name="queryType">
    <?php
    $views= array("Alle Bücher"=>"all", "Eingeschränkte Vorschau und vollständige Ansicht"=>"partial_view", "Nur vollständige Ansicht"=>"full_view");
    foreach($views as $key => $value) {
        echo '<option value="'.$value.'"';
        if (isset($_GET['queryType'])) {
            $view = $_GET['queryType'];
                if($view == $value){
                    echo 'selected="true"';
                }
        }
        echo '>'.$key.'</option>';
    }
    ?>
    </select>
    <select name="category">
    <?php
    $categorys= array("Alle Sprachen"=>"all", "Nur Deutsch"=>"de", "Nur Englisch"=>"en", "Nur Französisch"=>"fr", "Nur Italiänisch"=>"it", "Nur Spanisch"=>"es");
    foreach($categorys as $key => $value) {
        echo '<option value="'.$value.'"';
        if (isset($_GET['category'])) {
            $category = $_GET['category'];
                if($category == $value){
                    echo 'selected="true"';
                }
        }
        echo '>'.$key.'</option>';
    }
    ?>
    </select>
    <input name="maxResults" type="hidden" value="10">
  </form>
</div>
<div id='popup' name='popup' style='position: fixed; display:none; z-index:2;'>
  <div class='dragme'>
    <div id='popup-icon' style='position: absolute; top: 0; left: 0; width: 30px; height: 25px;'><img height='25' width='30' src='./img/popup-icon.gif' border='0'></div>
    <div id='popup-title' style='position: absolute; top: 0; left: 30px; height: 25px; background: url(./img/popup-title-bg.gif) repeat-x; text-align: center;'><img height='25' width='140' src='./img/popup-title.gif'></div>
  </div>
  <div id='popup-close' style='position: absolute; top: 0; width: 30px; height: 25px;'><a style='padding: 0px;' href='javascript:popupClose();'><img height='25' width='30' src='./img/popup-close.gif' border='0'></a></div>
  <div id='popup-lu' style='position: absolute; left: 0; width: 5px; height: 6px; background-color: transparent;'><img height='6' width='5' src='./img/popup-lu.gif' border='0'></div>
  <div id='popup-u' class='popup-background' style='position: absolute; left: 5px; height: 6px; background-image: url(./img/popup-u.gif); background-repeat: repeat-x;'></div>
  <div class='resize' id='popup-ru' style='position: absolute; width: 16px; height: 16px; background-color:transparent; z-index:1;'><img height='16' width='16' src='./img/popup-resize.gif' border='0'></div>

  <div id='popup-l' style='position: absolute; top: 25px; left: 0px; width: 2px; background: url(./img/popup-l.gif) repeat-y;'></div>
  <div id='popup-r' style='position: absolute; top: 25px; width: 2px; background: url(./img/popup-r.gif) repeat-y;'></div>
  <div id='popup-m' class='popup-background' style='position: absolute; top: 25px; left: 2px; overflow:auto;'>
    <div id='popup-body'>
    </div>
  </div>
</div>

<span clear="all" />


