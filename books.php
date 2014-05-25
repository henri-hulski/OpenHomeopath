<?php
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
 */
ini_set('include_path',ini_get('include_path').':../include/ZendGdata-1.8.1/library:');
/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @see Zend_Gdata_Books
 */
Zend_Loader::loadClass('Zend_Gdata_Books');
/**
 * @see Zend_Gdata_ClientLogin
 */
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');

/**
 * @see Zend_Gdata_App_AuthException
 */
Zend_Loader::loadClass('Zend_Gdata_App_AuthException');
Zend_Loader::loadClass('Zend_Gdata_AuthSub');
/**
 * Return a comma separated string representing the elements of an array
 *
 * @param Array $elements The array of elements
 * @return string Comma separated string
 */
function printArray($elements) {
    $result = '';
    foreach ($elements as $element) {
      if (!empty($result)) $result = $result.', ';
      $result = $result.$element;
    }
    return $result;
}
function printAuthorArray($elements) {
    $result = '';
    foreach ($elements as $element) {
      if (!empty($result)) $result = $result.', ';
      //$result = $result.$element;
      $result = $result."<a href=\"books.php?searchTerm=inauthor%3A&#34;".str_replace(" ","+",$element)."&#34;&queryType=all&category=all&maxResults=10&oder=1\" style=\"text-decoration:underline;color:#666666\" title=\"Bücher des Autors finden!\">".$element."</a>";
      //books.php?searchTerm=bell&queryType=all&category=all&maxResults=10
    }
    return $result;
}

/**
 * Echo the list of books in the specified feed.
 *
 * @param Zend_Gdata_Books_BookFeed $feed The book feed
 * @return void
 */

function echoBookList($feed)
{
    print <<<HTML
    <table><tr><td id="resultcell">
    <div id="searchResults">
        <table class="volumeList"><tbody width="100%">
HTML;
    $flipflop = false;
    $totalResults = $feed->getTotalResults()->text;
            $page = $_REQUEST['page'];
if ($page=="") {
$page=0;
}

if (isset($_GET['maxResults'])) {
        $perpage = $_GET['maxResults'];
    }else{
    $perpage=10;
    }
$pages = ceil($totalResults/$perpage);
echo "ca. ".$totalResults." Ergebnisse <br />";
if (isset($_GET['startIndex'])) {
            $startIndex = $_GET['startIndex'];
        }else{
            $startIndex = 1;
        }
for ($i=1;$i<=$totalResults;)
{
     if (isset($_GET['startIndex'])) {
     $startIndex =$_GET['startIndex'];
     }else{
     $startIndex = 1;
     }
    if ($startIndex==$i){
            echo " ".(($i-1+10)/10)." ";
    }else{
        $pageLink =  '<a href="books.php?';
        if (isset($_GET['queryType'])) {
            $pageLink .=  'queryType='.$_GET['queryType'];
        }else{
            $pageLink .=  'queryType=all';
        }
        $pageLink .='&maxResults=';
        $pageLink .=$perpage;
        if (isset($_GET['searchTerm'])) {
            $pageLink .= '&searchTerm=';
            $pageLink .=str_replace  ("\"","&quot;",$_GET['searchTerm']);
        }else{
            $pageLink .= '&searchTerm=';
        }
        $pageLink .= '&startIndex='.$i;
        if (isset($_GET['category'])) {
            $pageLink .= '&category=';
            $pageLink .=$_GET['category'];
        }else{
            $pageLink .= '&category=all';
        }
        $pageLink .='">'.(($i-1)/10+1).'</a>  ';
        echo $pageLink;
    }
    $i = $i+$perpage;
}

    $i=0;
    foreach ($feed as $entry) {
    $i++;
        $title = printArray($entry->getTitles());
        $volumeId = $entry->getVolumeId();
        if ($thumbnailLink = $entry->getThumbnailLink()) {
            $thumbnail = $thumbnailLink->href;
        } else {
            $thumbnail = null;
        }
        $preview = $entry->getPreviewLink()->href;
        $embeddability = $entry->getEmbeddability()->getValue();
        $viewability = $entry->getViewability()->getValue();
        $labels = $entry->getCategory();
        unset($archivorgId);
        unset( $archivLink);
        $iii = 0;
        while ($iii <= 10) {
            $iii++; 
            if($labels[$iii]){
                $pos = strpos($labels[$iii]->getTerm(),"archivorg-id");
                if ($pos !== false) {
                    $archivorgId=str_replace("archivorg-id:","",$labels[$iii]->getTerm());
                    $archivLink= " <a href=\"http://openlibrary.org/details/".$archivorgId."\"target=\"_blank\"><img src=\"books/img/read_book_openlibrary_button.gif\" border=\"0\" alt=\"Buch lesen bei OpenLibrary\" /></a>";
                    $archivLink= " <a href=\"http://www.archive.org/stream/".$archivorgId."\"target=\"_blank\"><img src=\"books/img/read_book_openlibrary_button.gif\" border=\"0\" alt=\"Buch lesen bei Archiv.org\" /></a>";
                }
            }
        }

        $creators = printAuthorArray($entry->getCreators());
        if (!empty($creators)) $creators = "von " . $creators;
        $descriptions = printArray($entry->getDescriptions());
        $dates= printArray($entry->getDates());
        
        $rating = $entry->getRating();
        
        $formats= printArray($entry->getFormats());
                if ($embeddability ==
            "http://schemas.google.com/books/2008#embeddable") {
            $preview_link = '<a href="javascript:load_viewport(\''.
                $preview.'\',\'viewport\');">'.
                '<img class="previewbutton" src="http://code.google.com/' .
                'apis/books/images/gbs_preview_button1.png" />' .
                '</a><br>';
        } else {
            $preview_link = '';
        }
        //http://schemas.google.com/books/2008#view_partial
        //http://schemas.google.com/books/2008#view_no_pages
        //http://schemas.google.com/books/2008#view_all_pages
        
        $thumbnail_img = (!$thumbnail) ? '' : '<a href="'.$preview.
            '"><img src="'.$thumbnail.'"/></a>';

        print <<<HTML
        <tr>
        <td><b>$i</b> $olLink<div class="thumbnail">
            $thumbnail_img
        </div></td>
        <td width="100%">
            <a href="${preview}" target="_blanc">$title</a><br>
            $creators<br />
            $dates / $formats <br />
            $descriptions<br />
            $preview_link $archivLink
        </td></tr>
HTML;
    }
print <<<HTML
    </table></div></td>
        <td width=70% id="previewcell"><div id="viewport"></div>&nbsp;
    </td></tr></table><br>
HTML;
}

include_once ("include/classes/login/session.php");
$skin = $session->skin;
$head_title = str_replace  ("\"","&quot;",stripslashes($_GET['searchTerm']))." - Homöopathische Onlinebibliothek - OpenHomeo.org";
header("Content-Type: text/html;charset=utf-8"); 
include("skins/$skin/header.php");
//
echo "<h1 style=\"text-align:center;\">Homöopathische Onlinebibliothek</h1>";
/*
 * The main controller logic of the Books volume browser demonstration app.
 */
$queryType = isset($_GET['queryType']) ? $_GET['queryType'] : null;

include 'books/interface.php';

if ($queryType === null) {
    /* display the entire interface */
} else {
    $username = 'thomas.bochmann@gmail.com';
    $password = 'affenbrot1!';
    $service = 'print';
    $httpClient = Zend_Gdata_ClientLogin::getHttpClient($username, $password, $service);
    $books = new Zend_Gdata_Books($httpClient);
    $query = $books->newVolumeQuery('http://www.google.com/books/feeds/users/' .
    '11221605598272230624/collections/library/volumes');
    $startIndex=1;
    $query->setStartIndex($startIndex);
    /* display a list of volumes */
    if (isset($_GET['searchTerm'])) {
        $searchTerm = $_GET['searchTerm'];
        $query->setQuery($searchTerm);
    }
    
    if (isset($_GET['startIndex'])) {
        $startIndex = $_GET['startIndex'];
        $query->setStartIndex($startIndex);
    }
    if (isset($_GET['maxResults'])) {
        $maxResults = $_GET['maxResults'];
        $query->setMaxResults($maxResults);
    }
    if (isset($_GET['minViewability'])) {
        $minViewability = $_GET['minViewability'];
        $query->setMinViewability($minViewability);
    }
    //thb
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
            if($category !== "all"){
                $query->setCategory($category);
            }
    }
    
    
    /* check for one of the restricted feeds, or list from 'all' videos */
    switch ($queryType) {
    case 'full_view':
    case 'partial_view':
        $query->setMinViewability($queryType);
        echo '<div style="font-size:16px;font-weight:bold;width:800;text-align:center;margin:2px;">Deine Suche: ' . ($query->getQuery()) . '</div>';
        // http://www.openhomeo.org/openhomeopath/books.php?queryType=partial_view&maxResults=10&searchTerm=a
        $nextPageLink =  '<a href="books.php?queryType=';
        $nextPageLink .=$query->getMinViewability();
        $nextPageLink .='_view&maxResults=';
        $nextPageLink .=$query->getMaxResults();
        $nextPageLink .='&searchTerm=';
        //$nextPageLink .=($query->getQuery());
        $nextPageLink .=str_replace  ("\"","&quot;",$_GET['searchTerm']);
        $nextPageLink .='&startIndex=';
        $nextPageLink .=$query->getStartIndex()+10;
        if($query->getCategory()){
            $nextPageLink .='&category=';
            $nextPageLink .=$query->getCategory();
        }
        $nextPageLink .='">nächste Seite</a><br /> ';
        
        $prevPageLink =  '<a href="books.php?queryType=';
        $prevPageLink .=$query->getMinViewability();
        $prevPageLink .='_view&maxResults=';
        $prevPageLink .=$query->getMaxResults();
        $prevPageLink .='&searchTerm=';
        $prevPageLink .=str_replace  ("\"","&quot;",$_GET['searchTerm']);
        $prevPageLink .='&startIndex=';
        $prevPageLink .=$query->getStartIndex()-10;
        if($query->getCategory()){
            $prevPageLink .='&category=';
            $prevPageLink .=$query->getCategory();
        }
        $prevPageLink .='">vorherige Seite</a>   ';
        
        $needle = 'start-index='.$query->getStartIndex();
        $replace = "start-index=";
        $replace .= $query->getStartIndex()+10;
        //http://www.openhomeo.org/openhomeopath/books.php?queryType=all&maxResults=10&searchTerm=d
        $nextPage = str_replace  ($needle,$replace,$query->getQueryUrl());
        $feed = $books->getVolumeFeed($query);
        $ii=0;
        // buchzähler
        foreach ($feed as $entry) {
            $ii++;
        }
        if($query->getStartIndex() > 1){
            echo $prevPageLink;
        }
        if($ii==10){
            echo $nextPageLink;
        }
        
        break;
    case 'all':
    echo '<div style="font-size:16px;font-weight:bold;width:800;text-align:center;">Deine Suche: ' . ($query->getQuery()) . '</div>';
        
        $nextPageLink =  '<a href="books.php?queryType=all';
        $nextPageLink .='&maxResults=';
        $nextPageLink .=$query->getMaxResults();
        $nextPageLink .='&searchTerm=';
        $nextPageLink .=str_replace  ("\"","&quot;",$_GET['searchTerm']);
        $nextPageLink .='&startIndex=';
        $nextPageLink .=$query->getStartIndex()+10;
        if($query->getCategory()){
            $nextPageLink .='&category=';
            $nextPageLink .=$query->getCategory();
        }
        $nextPageLink .='">nächste Seite</a><br /> ';
        
        $prevPageLink =  '<a href="books.php?queryType=all';
        $prevPageLink .='&maxResults=';
        $prevPageLink .=$query->getMaxResults();
        $prevPageLink .='&searchTerm=';
        //$prevPageLink .=$query->getQuery();
        $prevPageLink .=str_replace  ("\"","&quot;",$_GET['searchTerm']);
        $prevPageLink .='&startIndex=';
        $prevPageLink .=$query->getStartIndex()-10;
        if($query->getCategory()){
            $prevPageLink .='&category=';
            $prevPageLink .=$query->getCategory();
        }
        $prevPageLink .='">vorherige Seite</a>   ';
        
        $needle = 'start-index='.$query->getStartIndex();
        $replace = "start-index=";
        $replace .= $query->getStartIndex()+10;
        //http://www.openhomeo.org/openhomeopath/books.php?queryType=all&maxResults=10&searchTerm=d
        $nextPage = str_replace  ($needle,$replace,$query->getQueryUrl());
        $feed = $books->getVolumeFeed($query);
        
        $ii=0;
        // buchzähler
        foreach ($feed as $entry) {
            $ii++;
        }
        
        if($query->getStartIndex() > 1){
            echo $prevPageLink;
        }
        if($ii==10){
            echo $nextPageLink;
        }
        
        break;
    default:
        echo 'ERROR - unknown queryType - "' . $queryType . '"';
        break;
    }
    echoBookList($feed);
    if($query->getStartIndex() > 1){
            echo $prevPageLink;
        }
        if($ii==10){
            echo $nextPageLink;
        }
}

include("skins/$skin/footer.php");