<?
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
Zend_Loader::loadClass('Zend_Json');

/**
 * xml2array() will convert the given XML text to an array in the XML structure.
 * Link: http://www.bin-co.com/php/scripts/xml2array/
 * Arguments : $contents - The XML text
 *                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
 *                $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
 * Return: The parsed XML in an array form. Use print_r() to see the resulting array structure.
 * Examples: $array =  xml2array(file_get_contents('feed.xml'));
 *              $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute'));
 */
function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
}  

//sortieren
//http://de3.php.net/manual/de/function.array-multisort.php
function array_sort_func($a,$b=NULL) {
   static $keys;
   if($b===NULL) return $keys=$a;
   foreach($keys as $k) {
      if(@$k[0]=='!') {
         $k=substr($k,1);
         if(@$a[$k]!==@$b[$k]) {
            return strcmp(@$b[$k],@$a[$k]);
         }
      }
      elseif(@$a[$k]!==@$b[$k]) {
         return strcmp(@$a[$k],@$b[$k]);
      }
   }
   return 0;
}

function array_sort(&$array) {
   if(!$array) return $keys;
   $keys=func_get_args();
   array_shift($keys);
   array_sort_func($keys);
   usort($array,"array_sort_func");       
}

function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

header("Content-Type: text/html;charset=utf-8"); 
include_once ("include/classes/login/session.php");
$skin = $session->skin;
$lng = $session->lang;
$head_title = "Homöopathische Onlinebibliothek - OpenHomeo.org";
include("./skins/$skin/header.php");

?>

		<style type="text/css" media="all"><!--
			div.image {}
			div.story {font-family:arial,sans-serif;font-size:10pt;}
			.synth {
			font-family:arial,sans-serif;
			background-color: #fffbdb;
			border: 10px solid #e0ccd6;
			padding: 10px;
			}
			span.synth {
			border: 1px solid #660033;
			padding: 1px 6px 1px 6px;
			color: #660033;
			}
			.ohp {
			font-family:arial,sans-serif;
			background-color: #fffdee;
			border: 10px solid #cce6d4;
			padding: 10px;
			}
			span.ohp {
			border: 1px solid #008029;
			padding: 1px 6px 1px 6px;
			}
			li {
			border: 0px solid #808080;
			/*padding: 1px 6px 1px 6px;*/
			margin: 10px;
			}
			img.ohp {
			font-family:arial,sans-serif;
			background-color: #fffdee;
			border: 10px solid #cce6d4;
			padding: 0px;
			}
			h1{text-align:center;margin:2px;}

			h2{text-align:left;}
			h3{text-align:center;margin:2px;}
			h4{text-align:left;font-size:11pt;}
			#mainSearchBox {
  background-color: #D9E7BA;
  border: 1px solid silver;
  width: 800;
  text-align:center;
  padding-top: 5px;
  padding-bottom: 10px;
  padding-left: 10px;
  padding-right: 10px;
}

		--></style>
		
<h1>Homöopathische Onlinebibliothek</h1>
	<div id="mainSearchBox">
  <h3>Bücher durchsuchen:</h3>
  <form id="mainSearchForm" action="books.php">

  <input name="searchTerm" type="text" style="font-size:16px;margin:2px;" value=""><input type="submit" value="Suchen" style="font-size:16px;font-weigt:bold;">&nbsp;&nbsp;<a href="javascript:popup_url('help/<?php echo $lng; ?>/booksearch.php',600,500)">Hilfe</a>
    <br /><b>Anzeigen:</b>
    <select name="queryType">
    <option value="all" selected="true">Alle Bücher</option><option value="partial_view">Eingeschränkte Vorschau und vollständige Ansicht</option><option value="full_view">Nur vollständige Ansicht</option>
    </select>
    <select name="category">

    <option value="all" selected="true">Alle Sprachen</option><option value="de">Nur Deutsch</option><option value="en">Nur Englisch</option><option value="fr">Nur Französisch</option><option value="it">Nur Italiänisch</option><option value="es">Nur Spanisch</option>      
    </select>
    <input name="maxResults" type="hidden" value="10">

  </form>
</div>
<div id='popup' name='popup' style='position: fixed; display:none; z-index:2;'>
  <div class='dragme'>
    <div id='popup-icon' style='position: absolute; top: 0; left: 0; width: 30px; height: 25px;'><img height='25' width='30' src='img/popup-icon.gif' border='0'></div>
    <div id='popup-title' style='position: absolute; top: 0; left: 30px; height: 25px; background: url(img/popup-title-bg.gif) repeat-x; text-align: center;'><img height='25' width='140' src='img/popup-title.gif'></div>
  </div>
  <div id='popup-close' style='position: absolute; top: 0; width: 30px; height: 25px;'><a style='padding: 0px;' href='javascript:popupClose();'><img height='25' width='30' src='img/popup-close.gif' border='0'></a></div>
  <div id='popup-lu' style='position: absolute; left: 0; width: 5px; height: 6px; background-color: transparent;'><img height='6' width='5' src='img/popup-lu.gif' border='0'></div>
  <div id='popup-u' class='popup-background' style='position: absolute; left: 5px; height: 6px; background-image: url(img/popup-u.gif); background-repeat: repeat-x;'></div>
  <div class='resize' id='popup-ru' style='position: absolute; width: 16px; height: 16px; background-color:transparent; z-index:1;'><img height='16' width='16' src='img/popup-resize.gif' border='0'></div>

  <div id='popup-l' style='position: absolute; top: 25px; left: 0px; width: 2px; background: url(img/popup-l.gif) repeat-y;'></div>
  <div id='popup-r' style='position: absolute; top: 25px; width: 2px; background: url(img/popup-r.gif) repeat-y;'></div>
  <div id='popup-m' class='popup-background' style='position: absolute; top: 25px; left: 2px; overflow:auto;'>
    <div id='popup-body'>
    </div>
  </div>
</div>

<span clear="all" />

<?
$contents = file_get_contents('books/xml/googlebookslib.xml');  // Or however you want it
$result = xml2array($contents ,0);
$books_ar = array();
foreach($result['library']['books']['book'] as $key => $row) {
	//Array ( [0] => 1879 [1] => homeopathie [2] => en [3] => olid [4] => /b/OL20619739M )
	if (!empty($row['labels']['label']) && is_array($row['labels']['label'])){
		$str ="";
		if (in_array("homeopathie", $row['labels']['label'])) {
			if (in_array("en", $row['labels']['label'])){
				$lang="englisch";
				$books_ar[$key]['lang']="englisch";
			}elseif(in_array("es", $row['labels']['label'])){
				$lang="spanisch";
				$books_ar[$key]['lang']="spanisch";
			}elseif(in_array("fr", $row['labels']['label'])){
				$lang="französisch";
				$books_ar[$key]['lang']="französisch";
			}elseif(in_array("it", $row['labels']['label'])){
				$lang="italienisch";
				$books_ar[$key]['lang']="italienisch";
			}else{
				$lang="? oder deutsch";
				$books_ar[$key]['lang']="deutsch";
			}
			if (in_array("olid", $row['labels']['label'])){
				$olidKey = recursive_array_search("olid", $row['labels']['label']);
				$books_ar[$key]['olid'] = $row['labels']['label'][($olidKey+1)];
			}
			if (in_array("archivorg", $row['labels']['label'])){
				$archivorgKey = recursive_array_search("archivorg", $row['labels']['label']);
				$books_ar[$key]['archivorg'] = $row['labels']['label'][($archivorgKey+1)];
			}
			$books_ar[$key]['title']=$row['title'];
			$books_ar[$key]['contributor'] = empty($row['contributor']) ? "" : $row['contributor'];
		
			foreach($row['labels']['label'] as $key2 => $labelss) {
				$str = $labelss;
				if (preg_match("([0-9]+)", $str, $matches)){
					$zahl = $matches[0];
					$books_ar[$key]['year']=$zahl;
				}
				if ( $labelss == "Materia medica"){
					$books_ar[$key]['art']="Materia medica";
				}
				if ( $labelss == "Repertorium"){
					$books_ar[$key]['art']="Repertorium";
				}
				$str ="";
			}
			$books_ar[$key]['url']=$row['url'];
			$books_ar[$key]['id']=$row['id'];
		}
	}
}
$read="&printsec=frontcover";
array_sort($books_ar,'lang','title','contributor','!year');//sortieren
echo "<script type=\"text/javascript\" src=\"http://books.google.com/books/previewlib.js\"></script>";
echo count($books_ar)." Titel";
$page = empty($_REQUEST['page']) ? 0 : $_REQUEST['page'];
$perpage=10;
$pages = round(count($books_ar)/$perpage);
echo " auf ".$pages." Seiten<br />";
for ($i=1;$i<=$pages;$i++)
{
    if ($i==$page+1){
        echo " ".$i." ";
    }else{
        echo "<a href=\"googlebooks.php?page=".($i-1)."\"> ".$i." </a>";
    }
}
$start=$page*$perpage;

$end=$start+$perpage;
for ($i=$start;$i<=$end;$i++)
{
    if (isset($books_ar[$i]['title'])){
        echo "<div class=\"story\">";
        echo "<h4>".$books_ar[$i]['title']."</h4>";
        if(isset($books_ar[$i]['contributor'])){
            echo $books_ar[$i]['contributor']."<br />";
        }
        if(isset($books_ar[$i]['year'])){ echo $books_ar[$i]['year']." | ";}
        if(isset($books_ar[$i]['lang'])){ echo $books_ar[$i]['lang']." | ";}
        if(isset($books_ar[$i]['art'])){ echo $books_ar[$i]['art']." | ";}
        echo "<a href=\"".$books_ar[$i]['url']."\"target=\"_blank\">&Uuml;ber dieses Buch</a>";
        echo "<script type=\"text/javascript\">GBS_setLanguage('de');GBS_insertPreviewButtonPopup('".$books_ar[$i]['id']."');</script>";
        if(isset($books_ar[$i]['olid'])){
            $client = new Zend_Http_Client('http://openlibrary.org'.$books_ar[$i]['olid'].'.json');
        $client->setConfig(array(
        'keepalive' => 'true',
        'timeout'      => 60));
        $response = $client->request();
        $olBookArr=Zend_Json::decode($response->getBody());
        echo "<br /><a href=\"http://openlibrary.org/details/".$olBookArr['ocaid']."\"target=\"_blank\"><img src=\"./books/img/read_book_openlibrary_button.gif\" border=\"0\" alt=\"Buch lesen bei OpenLibrary\" /></a>";
        }
        if(isset($books_ar[$i]['archivorg'])){
        
        $archivorgId=str_replace("archivorg-id:","",$books_ar[$i]['archivorg']);
                echo " <a href=\"http://openlibrary.org/details/".$archivorgId."\"target=\"_blank\"><img src=\"./books/img/read_book_openlibrary_button.gif\" border=\"0\" alt=\"Buch lesen bei OpenLibrary\" /></a>";
        }
        echo "<hr /></div>";
        
    }
}
for ($i=1;$i<=$pages;$i++)
{
    if ($i==$page+1){
        echo " ".$i." ";
    }else{
        echo "<a href=\"googlebooks.php?page=".($i-1)."\"> ".$i." </a>";
    }
}
include("./skins/$skin/footer.php");

?>