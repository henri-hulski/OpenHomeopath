
/**
 * openhomeopath.js
 *
 * The central javascript file of OpenHomeopath.
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Homeopathy
 * @package   JavaScript
 * @author    Henri Schumacher <henri.hulski@gazeta.pl>
 * @copyright 2007-2014 Henri Schumacher
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License v3
 * @version   1.0
 * @link      http://openhomeo.org/openhomeopath/download/OpenHomeopath_1.0.tar.gz
 */

var baseUrl = 'http://openhomeo.org/openhomeopath/';

function reloadTabs(tabbed_url) {
	var s = window.location.search;
	var q = s.indexOf("?") == 0 ? s.slice(1) : s;
	tabbed_url = q.length > 0 ? tabbed_url + '&' + q : tabbed_url;
	window.location.href = tabbed_url;
}

var isClick;
var elementValue;
function doubleClick(command, e) {
	if (isClick == 1 && e.value == elementValue) {
		eval(command);
		isClick = 0;
	} else {
		isClick = 1;
		elementValue = e.value;
		window.setTimeout("isClick = 0", 500);
	}
}

function changeSkin(skin) {
	window.location.hash = "";
	var url = window.location.href;
	url = url.replace(/#/, "");
	url = url.replace(/[?&]skin=[a-z_.-]+/, "");
	window.location.href = url.indexOf("?") == -1 ? url + "?skin=" + skin : url + "&skin=" + skin;
}

function getWindowWidth() {
// Fensterbreite ermitteln (verschiedene Metoden fuer verschiedene Browser)
	var windowWidth;
	if (self.innerWidth) {
		windowWidth = self.innerWidth;
	} else if (document.documentElement && document.documentElement.clientWidth) {
		windowWidth = document.documentElement.clientWidth;
	} else if (document.body) {
		windowWidth = document.body.clientWidth;
	}
	return windowWidth;
}

function getWindowHeight() {
// Fensterhoehe ermitteln (verschiedene Metoden fuer verschiedene Browser)
	var windowHeight;
	if (self.innerHeight) {
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) {
		windowHeight = document.body.clientHeight;
	}
	return windowHeight;
}

function resizeResultTable() {  // passt die Ergebnistabellenbreite an die Fensterbreite an
	if (document.getElementById("result_table")) {
		var tableWidth = 93; // Tabellenbreite im Verhaeltnis zur Elternelementbreite in %
		var windowWidth = getWindowWidth();
		document.getElementById("result_fieldset").style.width = Math.floor(windowWidth * tableWidth / 100 - sideFrameWidth) + "px";
		document.getElementById("result_table").style.width = Math.floor(windowWidth * tableWidth / 100 - sideFrameWidth - 33) + "px";
	}
}


/* Popup-Windows */
var maxWidth = 0;
var maxHeight = 0;
var inputWidth = 0;
var inputHeight = 0;

function popupOpen(popupWidth, popupHeight) {
	popupClose();
	if (document.getElementById("popup").style.left == "" || popupWidth != inputWidth || popupHeight != inputHeight) {
		inputWidth = popupWidth;
		inputHeight = popupHeight;
		maxWidth = popupWidth;
		maxHeight = popupHeight;
		popupResize();
		popupCenter();
	}
	document.getElementById("popup").style.display = "block";
	window.onresize = new Function("popupResize(); popupCenter();");
	document.onmousedown=selectmouse;
	document.onmouseup=new Function("isdrag=false; isresize=false;");
}

function popupResize() {

	var maxPopupSize = 95; // Maximale Popupgroesse im Verhaeltnis zur Fenstergroesse in %
	var minPopupSize = 200; // Minimale Popupbreite und -hoehe in Pixel
	var popupWidth = maxWidth;
	var popupHeight = maxHeight;
	var windowWidth = getWindowWidth();
	var windowHeight = getWindowHeight();

// Die Popup-Breite auf maxPopupSize-% der Fensterbreite begrenzen
	if (popupWidth == 0 || popupWidth > (windowWidth * maxPopupSize / 100)) {
		popupWidth = Math.floor(windowWidth * maxPopupSize / 100);
		if (maxWidth == 0) {
			maxWidth = popupWidth;
		}
	}

// Die Popup-Hoehe auf maxPopupSize-% der Fensterhoehe begrenzen
	if (popupHeight == 0 || popupHeight > (windowHeight * maxPopupSize / 100)) {
		popupHeight = Math.floor(windowHeight * maxPopupSize / 100);
		if (maxHeight == 0) {
			maxHeight = popupHeight;
		}
	}

// Mindesthoehe: minPopupSize-px
	if (popupHeight < minPopupSize) {
		popupHeight = minPopupSize;
		if (maxHeight < minPopupSize) {
			maxHeight = minPopupSize;
		}
	}

// Mindestbreite: minPopupSize-px
	if (popupWidth < minPopupSize) {
		popupWidth = minPopupSize;
		if (maxWidth < minPopupSize) {
			maxWidth = minPopupSize;
		}
	}

	document.getElementById("popup").style.width = popupWidth + "px";
	document.getElementById("popup-title").style.width = (popupWidth - 60) + "px";
	document.getElementById("popup-u").style.width = (popupWidth - 10) + "px";
	document.getElementById("popup-m").style.width = (popupWidth - 4) + "px";
	document.getElementById("popup-close").style.left = (popupWidth - 30) + "px";
	document.getElementById("popup-ru").style.left = (popupWidth - 16) + "px";
	document.getElementById("popup-r").style.left = (popupWidth - 2) + "px";
	document.getElementById("popup").style.height = popupHeight + "px";
	document.getElementById("popup-l").style.height = (popupHeight - 31) + "px";
	document.getElementById("popup-r").style.height = (popupHeight - 31) + "px";
	document.getElementById("popup-m").style.height = (popupHeight - 31) + "px";
	document.getElementById("popup-lu").style.top = (popupHeight - 6) + "px";
	document.getElementById("popup-u").style.top = (popupHeight - 6) + "px";
	document.getElementById("popup-ru").style.top = (popupHeight - 16) + "px";
}

function popupCenter() {

	var popupWidth = parseInt(document.getElementById("popup").style.width+0);
	var popupHeight = parseInt(document.getElementById("popup").style.height+0);
	var windowWidth = getWindowWidth();
	var windowHeight = getWindowHeight();


// Popup-Fenster zentrieren
	var leftOffset = Math.round((windowWidth - popupWidth) / 2);
	var topOffset = Math.round((windowHeight - popupHeight) / 2);
	document.getElementById("popup").style.top = topOffset + "px";
	document.getElementById("popup").style.left = leftOffset + "px";
}

var nn6 = document.getElementById && !document.all;
var isdrag = false;
var isresize = false;
var x, y;

function dragmouse(e) {
	if (isdrag) {
		document.getElementById("popup").style.left = nn6 ? tx + Math.round(e.clientX - x) + "px" : tx + Math.round(event.clientX - x) + "px";
		document.getElementById("popup").style.top  = nn6 ? ty + Math.round(e.clientY - y) + "px" : ty + Math.round(event.clientY - y) + "px";
		return false;
	}
}

function resizemouse(e) {
	if (isresize) {
		maxWidth = nn6 ? Math.round(e.clientX) - parseInt(document.getElementById("popup").style.left+0) : Math.round(event.clientX) - parseInt(document.getElementById("popup").style.left+0);
		maxHeight  = nn6 ? Math.round(e.clientY) - parseInt(document.getElementById("popup").style.top+0) : Math.round(event.clientY) - parseInt(document.getElementById("popup").style.top+0);
		popupResize();
		return false;
	}
}


var tx;
var ty;
function selectmouse(e) {
	var fobj       = nn6 ? e.target : event.srcElement;
	var topelement = nn6 ? "HTML" : "BODY";
	while (fobj.tagName != topelement && fobj.className != "dragme" && fobj.className != "resize") {
		fobj = nn6 ? fobj.parentNode : fobj.parentElement;
	}
	if (fobj.className=="dragme") {
		isdrag = true;
		tx = parseInt(document.getElementById("popup").style.left+0);
		ty = parseInt(document.getElementById("popup").style.top+0);
		x = nn6 ? e.clientX : event.clientX;
		y = nn6 ? e.clientY : event.clientY;
		document.onmousemove=dragmouse;
		return false;
	} else if (fobj.className=="resize") {
		isresize = true;
		document.onmousemove=resizemouse;
		return false;
	}
}

function popupClose() {
	document.getElementById("popup").style.display = "none";
}

/* Ajax */
var historyBackPopupAr = new Array();
var historyForwardPopupAr = new Array();
var maxHistory = 99; // maximale Groesse des History-Arrays

function popup_url(url, popupWidth, popupHeight) {
	popupHistory(url);
	var popupurl = setPopupurl(url);
	if (loadurl(popupurl, "GET", "popup-body")) {
		popupOpen(popupWidth, popupHeight);
		if (typeof _paq !== 'undefined') {
			_paq.push(['trackPageView', url]);
		}
	} else {
		window.location.href = url;
	}
//	return false;
}

function popupHistory(url){
	if (document.getElementById("history_back")) {
		var historyBackLength = historyBackPopupAr.unshift(url);
		while (historyBackLength > maxHistory) {
			historyBackPopupAr.pop();
		}
		if (historyForwardPopupAr.length != 0) {
			historyForwardPopupAr.length = 0;
			document.getElementById("arrow_right").src = "img/arrow_right_inactive.gif";
			document.getElementById("arrow_right").removeAttribute("title");
			document.getElementById("history_forward").removeAttribute("href");
		}
	}
	if (historyBackLength > 1) {
		if (document.getElementById("arrow_left").src.indexOf("_inaktiv.gif") > -1) {
			document.getElementById("arrow_left").src = "img/arrow_left.gif";
			document.getElementById("arrow_left").title = lang[1];
			document.getElementById("history_back").href = "javascript:historyBackPopup();";
		}
	}
}

function setPopupurl(url) {
	// Info an Server, dass es sich um eine Popup-Anfrage handelt
	var popupurl = url.indexOf("?") == -1 ? url + "?popup=1" : url + "&popup=1";
	return popupurl;
}

function historyBackPopup() {
	var url = historyBackPopupAr.shift();
	if (historyBackPopupAr.length <= 1) {
		document.getElementById("arrow_left").src = "img/arrow_left_inactive.gif";
		document.getElementById("arrow_left").removeAttribute("title");
		document.getElementById("history_back").removeAttribute("href");
	}
	historyForwardPopupAr.unshift(url);
	if (document.getElementById("arrow_right").src.indexOf("_inaktiv.gif") > -1) {
		document.getElementById("arrow_right").src = "img/arrow_right.gif";
		document.getElementById("arrow_right").title = lang[2];
		document.getElementById("history_forward").href = "javascript:historyForwardPopup();";
	}
	var popupurl = setPopupurl(historyBackPopupAr[0]);
	loadurl(popupurl, "GET", "popup-body");
}

function historyForwardPopup() {
	var url = historyForwardPopupAr.shift();
	if (historyForwardPopupAr.length == 0) {
		document.getElementById("arrow_right").src = "img/arrow_right_inactive.gif";
		document.getElementById("arrow_right").removeAttribute("title");
		document.getElementById("history_forward").removeAttribute("href");
	}
	historyBackPopupAr.unshift(url);
	if (document.getElementById("arrow_left").src.indexOf("_inaktiv.gif") > -1) {
		document.getElementById("arrow_left").src = "img/arrow_left.gif";
		document.getElementById("arrow_left").title = lang[1];
		document.getElementById("history_back").href = "javascript:historyBackPopup();";
	}
	var popupurl = setPopupurl(url);
	loadurl(popupurl, "GET", "popup-body");
}

function loadurl(url, Method, outputId, notFound, treeview, rubric, fromHistory, id) {
	document.getElementById("onwork").style.display = "block";
	document.getElementById("container").style.cursor = "wait";
	var xmlhttp;
	try {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	}
	catch (e) {
		try {
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
		catch (e2) {
			try {
				xmlhttp = new XMLHttpRequest();
			}
			catch (e3) {
				return false;
			}
		}
	}
	
	xmlhttp.onreadystatechange = function() {
		if(xmlhttp.readyState  == 4) {
			if(xmlhttp.status  == 200) {
				if (notFound == 1 && xmlhttp.responseText == "<ul></ul>") {
					document.getElementById(outputId).innerHTML = "<ul><li>&nbsp;&nbsp;" + lang[3] + "</li></ul>";
				} else if (treeview > 0 && treeview < 3) {
					var response = eval('('+xmlhttp.responseText+')');
					if (treeview == 1) {
						generateChild(outputId, response);
					} else if (treeview == 2) {
						symtomSelect(response);
					}
				} else {
					document.getElementById(outputId).innerHTML = xmlhttp.responseText;
					if (treeview == 3) {
						loadChild('tree1_0', rubric, 0);
					}
					if (outputId == 'tab_1') {
						resizeResultTable();
					}
					if (outputId.indexOf("tab_") > -1 || outputId == "materia_medica") {
						var tabId = outputId.substr(4, 1);
						if (outputId == "materia_medica") {
							tabId = 2;
						}
						if (tabId >=1 && tabId <= 3 && fromHistory != -1 && fromHistory != 1) {
							if (id == -1) {
								if (Method == "POST") {
									id = url + "?" + query;
								} else {
									id = url;
								}
							}
							tabHistory (id, tabId);
						} else if (fromHistory == -1) {
							historyBackArrors(tabId);
						} else if (fromHistory == 1) {
							historyForwardArrors(tabId);
						}
					}
				}
				document.getElementById("container").style.cursor = "auto";
				document.getElementById("onwork").style.display = "none";
			} else {
				document.getElementById(outputId).innerHTML = "Error code " + xmlhttp.status;
			}
		}
	};
	if (Method == "POST") {
		var urlAr = url.split("?", 2);
		url = urlAr[0];
		var query = urlAr[1];
	}
	xmlhttp.open(Method, url,  true);
	if (Method == "GET") {
		xmlhttp.send(null);
	} else if (Method == "POST") {
		xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xmlhttp.send(query);
	}
	return true;
}

function saveRep(task) {
	var patient = document.getElementById("patient").value;
	var date = document.getElementById("date").value;
	var prescription = document.getElementById("prescription").value;
	var note = document.getElementById("note").value;
	if (task != 'save_rep') {
		note = note.replace(/\r\n/g, '%br');
		note = note.replace(/[\r\n]/g, '%br');
	}
	var user = document.getElementById("user").value;
	var symSelect = document.getElementById("sym_select").value;
	var url = "forms/save_rep.php?task=" + task + "&patient=" + patient + "&date=" + date + "&prescription=" + prescription + "&note=" + note + "&user=" + user + "&symsel=" + symSelect;
	if (document.getElementById("rep")) {
		var rep = document.getElementById("rep").value;
		url += "&rep=" + rep;
	}
	if (typeof _paq !== 'undefined') {
		_paq.push(['trackLink', baseUrl + url, 'download']);
	}
	if (task == 'save_rep') {
		url += "&ajax=1";
		if (document.getElementById("tabber")) {
			url += "&tab=1";
		}
		loadurl(url, "POST", "save_rep");
		var savedRepsUrl = "forms/saved_reps.php?ajax=1";
		loadurl(savedRepsUrl, "POST", "saved_reps");
	} else if (task == 'print_PDF') {
		window.open(url, '_blank');
	} else {
		window.location.href = url;
	}
}

function searchSymptoms() {
	var rubricId = document.getElementById("rubrics").options[document.getElementById("rubrics").selectedIndex].value;
	var search = document.getElementById("search").value;
	var andOr = document.getElementById("and").checked ? "AND" : "OR";
	var wholeWord = document.getElementById("whole_word").checked ? "true" : "false";
	var url = "forms/select_symptoms.php?rubric=" + rubricId  + "&ajax=1";
	if (search != "") {
		url += "&search=" + search + "&and_or=" + andOr + "&whole_word=" + wholeWord;
	}
	if (rubricId != -1 && search == "") {
		loadurl(url, "POST", "select_symptoms", 0, 3 , rubricId);
	} else {
		loadurl(url, "POST", "select_symptoms");
	}
	document.getElementById("selected_symptoms").style.display = "block";
	var recentRubric = document.getElementById("rubrics").options[document.getElementById("rubrics").selectedIndex].text;
	if (rubricId == -1) {
		document.getElementById("main_rubric").innerHTML = "<strong>" + recentRubric + "</strong>";
	} else {
		document.getElementById("main_rubric").innerHTML = lang[4] + " <br><strong>" + recentRubric + "</strong>";
	}
	if (search != "") {
		document.getElementById("search_item").innerHTML = lang[5] + " <strong>" + search.replace(/\\\\/, "") + "</strong>";
	} else {
		document.getElementById("search_item").innerHTML = "- " + lang[6] + " -";
		search = "no keywords";
	}
	if (typeof _paq !== 'undefined') {
		_paq.push(['trackSiteSearch', search.replace(/\\\\/, ""), recentRubric, false]);
	}
	return false;
}

function repDelete() {
	var savedRepButtons = document.getElementById("saved_reps_form").saved_rep;
	var repAr;
	if(savedRepButtons.checked) {
		repAr = document.getElementById("radio_1").value.split("%", 6);
	} else {
		for (var i=0; i < savedRepButtons.length; i++) {
			if (savedRepButtons[i].checked) {
				repAr = savedRepButtons[i].value.split("%", 2);
			}
		}
	}
	if (repAr) {
		var repId = repAr[0];
		var url = "forms/saved_reps.php?rep=" + repId + "&loesch=1&ajax=1";
		loadurl(url, "POST", "saved_reps");
	} else {
		alert (lang[7]);
		return false;
	}
}

function repPublic() {
	var savedRepButtons = document.getElementById("saved_reps_form").saved_rep;
	var repAr;
	if(savedRepButtons.checked) {
		repAr = document.getElementById("radio_1").value.split("%", 6);
	} else {
		for (var i=0; i < savedRepButtons.length; i++) {
			if (savedRepButtons[i].checked) {
				repAr = savedRepButtons[i].value.split("%", 6);
			}
		}
	}
	if (repAr) {
		var repId = repAr[0];
		var repPublic = repAr[5];
		var url = "forms/saved_reps.php?rep=" + repId + "&rep_public=" + repPublic + "&public=1&ajax=1";
		loadurl(url, "POST", "saved_reps");
	} else {
		alert (lang[7]);
		return false;
	}
}

function reloadSavedRepsTable(orderBy, orderType) {
	var url = "forms/saved_reps.php?order_by=" + orderBy + "&order_type=" + orderType + "&ajax=1";
	loadurl(url, "POST", "saved_reps");
	return false;
}

function customTable(table) {
	var Sources = "";
	var personalForm = eval("document.personal_" + table + "_form");
	for (var i=0; i < personalForm.src.length; i++) {
		if (personalForm.src[i].checked) {
			var source = personalForm.src[i].value;
		}
	}
	if (source == "custom") {
		if (document.getElementById("src_" + table).selectedIndex == -1) { // if no source is selected through an error
			alert (lang[8]);
			return false;
		}
		var sourcesAr = new Array;
		var i;
		for (i = 0; i < document.getElementById("src_" + table).length; i++) {
			if (document.getElementById("src_" + table).options[i].selected) {
				sourcesAr[sourcesAr.length] = document.getElementById("src_" + table).options[i].value;
			}
		}
		Sources = "&src_sel=" + sourcesAr.join("_");
	}
	var url = "forms/personal_" + table + ".php?src=" + source + Sources + "&custom_" + table + "_submit=1&ajax=1";
	loadurl(url, "POST", "personal_" + table);
	
	if (source != "custom") {
		document.getElementById("src_" + table).selectedIndex = -1;
	}
	
	var wholeTable = false;
	if (source == "all") {
		wholeTable = true;
	}
	if (document.getElementById("all_" + table)) {
		document.getElementById("all_" + table).style.display = wholeTable ? "block" : "none";
	}
	if (document.getElementById("personalized_" + table)) {
		document.getElementById("personalized_" + table).style.display = wholeTable ? "none" : "block";
	}
	if (document.getElementById("personalized_" + table + "_1")) {
		document.getElementById("personalized_" + table + "_1").style.display = wholeTable ? "none" : "block";
	}
	if (document.getElementById("personalized_" + table + "_2")) {
		document.getElementById("personalized_" + table + "_2").style.display = wholeTable ? "none" : "block";
	}
	if (document.getElementById("lang_" + table)) {
		document.getElementById("lang_" + table).style.display = "none";
	}
}

function symptomEdit(tab, inputId) {
	var symptomId = document.getElementById(inputId).options[document.getElementById(inputId).selectedIndex].value;
	tabOpen("symptomeditor.php?sym=", symptomId, "GET", tab);
}

/* Symptome auswählen */
var symptNo = 0;
function symtomSelect(response) {
	var div = document.getElementById("symSelect");
	/* doppelte erkennen */
	for (var i = 0; i < div.getElementsByTagName('input').length; i++) {
		if (response.id == div.getElementsByTagName('input')[i].value) {
			return false;
		}
	}
	symptNo++;
	var divChild = document.createElement("div");
	var str = "";
//	str += "<input id='check_" + symptNo + "' type='checkbox' value='" + response.id + "'>&nbsp;";
	str += "<input type='hidden' value='" + response.id + "'>";
	str += "<select size='1' title='" + lang[0] + "'><option value='0'>0</option><option selected='selected' value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option></select>";
	str += '&nbsp;<a href="javascript:symptomData(' + response.id + ');" title="' + lang[9] + '"><img src="skins/original/img/info.gif" width="12" height="12"></a>';
	str += '&nbsp;<a href="javascript:symDeselect(\'sympt_' + symptNo + '\');" title="' + lang[11] + '"><img src="skins/original/img/del.png" width="12" height="12"></a>';
//	str += "&nbsp;&nbsp;<label for='check_" + symptNo + "' title='" + response.name + "'>" + response.name + "</label>";
	str += "&nbsp;&nbsp;" + response.name;
	divChild.id = "sympt_" + symptNo;
	divChild.innerHTML = str;
	div.appendChild(divChild);
}

/* deselect symptoms */
function symDeselect(divId) {
	var symDiv = document.getElementById(divId);
	symDiv.parentNode.removeChild(symDiv);
}

function rep(tab) {
	var symSelectAr = new Array;
	var div = document.getElementById("symSelect");
	for (var i = 0; i < div.getElementsByTagName('input').length; i++) {
		symSelectAr[symSelectAr.length] = div.getElementsByTagName('input')[i].value + "-" + div.getElementsByTagName('select')[i].options[div.getElementsByTagName('select')[i].selectedIndex].value;
	}
	if (symSelectAr.length == 0) {
		alert(lang[10]);
		return false;
	} else {
		var symSelect = symSelectAr.join("_");
		loadRep(tab, symSelect, "rep_result")
	}
}

function addSymptoms(tab) {
	var symSelect = document.getElementById("sym_select").value;
	loadRep(tab, symSelect, "repertori")
}

function loadRep(tab, symSelect, target) {
	var url = target + ".php?symsel=" + symSelect;
	if (document.getElementById("patient") && document.getElementById("patient").value != "") {
		var patient = document.getElementById("patient").value;
		url += "&patient=" + patient;
	}
	if (document.getElementById("prescription") && document.getElementById("prescription").value != "") {
		var prescription = document.getElementById("prescription").value;
		url += "&prescription=" + prescription;
	}
	if (document.getElementById("note") && document.getElementById("note").value != "") {
		var note = document.getElementById("note").value;
		url += "&note=" + note;
	}
	tabOpen(url, -1, "POST", tab);
}

function repCall(tab) {
	var savedRepButtons = document.getElementById("saved_reps_form").saved_rep;
	if(savedRepButtons.checked) {
		var repAr = document.getElementById("radio_1").value.split("%", 6);
	} else {
		for (var i=0; i < savedRepButtons.length; i++) {
			if (savedRepButtons[i].checked) {
				var repAr = savedRepButtons[i].value.split("%", 6);
			}
		}
	}
	if (repAr) {
		var repId = repAr[0];
		var patient = encodeURI(repAr[1]);
		var prescription = encodeURI(repAr[2]);
		var note = encodeURI(repAr[3]);
		var timestamp = repAr[4];
		var url = "rep_result.php?rep=" + repId;
		if (patient != "") {
			url += "&patient=" + patient;
		}
		if (prescription != "") {
			url += "&prescription=" + prescription;
		}
		if (note != "") {
			url += "&note=" + note;
		}
		if (timestamp != "") {
			url += "&timestamp=" + timestamp;
		}
		tabOpen(url, -1, "POST", tab);
	} else {
		alert (lang[7]);
		return false;
	}
}

function repContinue(tab) {
	var savedRepButtons = document.getElementById("saved_reps_form").saved_rep;
	if(savedRepButtons.checked) {
		var repAr = document.getElementById("radio_1").value.split("%", 6);
	} else {
		for (var i=0; i < savedRepButtons.length; i++) {
			if (savedRepButtons[i].checked) {
				var repAr = savedRepButtons[i].value.split("%", 6);
			}
		}
	}
	if (repAr) {
		var repId = repAr[0];
		var patient = encodeURI(repAr[1]);
		var prescription = encodeURI(repAr[2]);
		var note = encodeURI(repAr[3]);
		var url = "repertori.php?rep=" + repId;
		if (patient != "") {
			url += "&patient=" + patient;
		}
		if (prescription != "") {
			url += "&prescription=" + prescription;
		}
		if (note != "") {
			url += "&note=" + note;
		}
		tabOpen(url, -1, "POST", tab);
	} else {
		alert (lang[7]);
		return false;
	}
}

// Tab:
var historyBackArTab1 = new Array();
var historyForwardArTab1 = new Array();
var historyBackArTab2 = new Array();
var historyForwardArTab2 = new Array();
var historyBackArTab3 = new Array();
var historyForwardArTab3 = new Array();

function tabOpen(url, id, Method, tabId, fromHistory) {
	if (id != -1) {
		url = url + id;
	}
	if (tabId != -1 && document.getElementById("tabber")) {
		if (typeof _paq !== 'undefined') {
			_paq.push(['trackPageView', url]);
		}
		document.getElementById("tabber").tabber.tabs[tabId].li.firstChild.className = '';
		if (url.indexOf("tab=") == -1) {
			url = url.indexOf("?") == -1 ? url + "?tab=" + tabId : url + "&tab=" + tabId;
		}
		loadurl(url, Method, "tab_" + tabId, 0, 0, 0, fromHistory, id);
		document.getElementById("tabber").tabber.tabShow(tabId);
	} else {
		window.location.href = url;
	}
}

function userTabOpen(id) {
	if (typeof _paq !== 'undefined') {
		_paq.push(['trackPageView', 'userinfo.php']);
	}
	document.getElementById("tabber").tabber.tabShow(4);
	document.getElementById(id).scrollIntoView(true);
}

function tabHistory (id, tab) {
	var historyBackLength;
	if (eval("historyBackArTab" + tab + "[0]") != id) {
		historyBackLength = eval("historyBackArTab" + tab).unshift(id);
		while (historyBackLength > maxHistory) {
			eval("historyBackArTab" + tab + ".pop()");
		}
		if (eval("historyForwardArTab" + tab).length != 0) {
			eval("historyForwardArTab" + tab).length = 0;
			document.getElementById("arrow_right_tab_" + tab).src = "img/arrow_right_inactive.gif";
			document.getElementById("arrow_right_tab_" + tab).removeAttribute("title");
			document.getElementById("history_forward_tab_" + tab).removeAttribute("href");
		}
	} else {
		historyBackLength = eval("historyBackArTab" + tab).length;
		var historyForwardLength = eval("historyForwardArTab" + tab).length;
		if (historyForwardLength >= 1) {
			if (document.getElementById("arrow_right").src.indexOf("_inaktiv.gif") > -1) {
				document.getElementById("arrow_right_tab_" + tab).src = "img/arrow_right.gif";
				document.getElementById("arrow_right_tab_" + tab).title = lang[1];
				document.getElementById("history_forward_tab_" + tab).href = "javascript:historyForwardTab(" + tab + ");";
			}
		}
	}
	if (historyBackLength > 1) {
		if (document.getElementById("arrow_left").src.indexOf("_inaktiv.gif") > -1) {
			document.getElementById("arrow_left_tab_" + tab).src = "img/arrow_left.gif";
			document.getElementById("arrow_left_tab_" + tab).title = lang[1];
			document.getElementById("history_back_tab_" + tab).href = "javascript:historyBackTab(" + tab + ");";
		}
	}
}

function sendTabUrl(id, tab, fromHistory) {
	switch (tab) {
		case 1:
			tabOpen(id, -1, "POST", 1, fromHistory);
			break;
		case 2:
			tabOpen("materia.php?rem=", id, "GET", 2, fromHistory);
			break;
		case 3:
			tabOpen("symptominfo.php?sym=", id, "GET", 3, fromHistory);
			break;
	}
}

function historyBackTab(tab) {
	var id = eval("historyBackArTab" + tab).shift();
	sendTabUrl(eval("historyBackArTab" + tab + "[0]"), tab, -1);
	eval("historyForwardArTab" + tab).unshift(id);
}

function historyBackArrors(tab) {
	if (eval("historyBackArTab" + tab).length <= 1) {
		document.getElementById("arrow_left_tab_" + tab).src = "img/arrow_left_inactive.gif";
		document.getElementById("arrow_left_tab_" + tab).removeAttribute("title");
		document.getElementById("history_back_tab_" + tab).removeAttribute("href");
	} else if (document.getElementById("arrow_left_tab_" + tab).src.indexOf("_inaktiv.gif") > -1) {
		document.getElementById("arrow_left_tab_" + tab).src = "img/arrow_left.gif";
		document.getElementById("arrow_left_tab_" + tab).title = lang[1];
		document.getElementById("history_back_tab_" + tab).href = "javascript:historyBackTab(" + tab + ");";
	}
	if (document.getElementById("arrow_right_tab_" + tab).src.indexOf("_inaktiv.gif") > -1) {
		document.getElementById("arrow_right_tab_" + tab).src = "img/arrow_right.gif";
		document.getElementById("arrow_right_tab_" + tab).title = lang[2];
		document.getElementById("history_forward_tab_" + tab).href = "javascript:historyForwardTab(" + tab + ");";
	}
}

function historyForwardTab(tab) {
	var id = eval("historyForwardArTab" + tab).shift();
	sendTabUrl(id, tab, 1);
	eval("historyBackArTab" + tab).unshift(id);
}

function historyForwardArrors(tab) {
	if (eval("historyForwardArTab" + tab).length == 0) {
		document.getElementById("arrow_right_tab_" + tab).src = "img/arrow_right_inactive.gif";
		document.getElementById("arrow_right_tab_" + tab).removeAttribute("title");
		document.getElementById("history_forward_tab_" + tab).removeAttribute("href");
	} else if (document.getElementById("arrow_right_tab_" + tab).src.indexOf("_inaktiv.gif") > -1) {
		document.getElementById("arrow_right_tab_" + tab).src = "img/arrow_right.gif";
		document.getElementById("arrow_right_tab_" + tab).title = lang[2];
		document.getElementById("history_forward_tab_" + tab).href = "javascript:historyForwardTab(" + tab + ");";
	}
	if (document.getElementById("arrow_left_tab_" + tab).src.indexOf("_inaktiv.gif") > -1) {
		document.getElementById("arrow_left_tab_" + tab).src = "img/arrow_left.gif";
		document.getElementById("arrow_left_tab_" + tab).title = lang[1];
		document.getElementById("history_back_tab_" + tab).href = "javascript:historyBackTab(" + tab + ");";
	}
}

// autosuggest:
var keyPressed = 0;
var t;
function autosuggest(formular) {
	if (keyPressed == 0) {
		keyPressed = 1;
	} else {
		window.clearTimeout(t);
	}
	t = window.setTimeout("getForm('" + formular + "')", 700);
}

function getForm(formular) {
	keyPressed = 0;
	var q = document.getElementById("query").value;
	if (typeof _paq !== 'undefined') {
		_paq.push(['trackSiteSearch', q, false, false]);
	}
	var url = "forms/" + formular + ".php?q=" + q + "&ajax=1";
	loadurl(url, "GET", "results", 1);
	document.getElementById("results").style.display = "block";
}

function setRem(remId, e) {
	if (typeof e.textContent !== 'undefined') {
		document.getElementById("query").value = e.textContent;
	} else {
		document.getElementById("query").value = e.innerText;
	}
	document.getElementById("remId").value = remId;
	document.getElementById("results").style.display = "none";
	getMateria(remId);
}

// thb
function setRemShort(rem, e) {
	document.getElementById("query").value = rem;
	document.getElementById("rem").value = rem;
	document.getElementById("results").style.display = "none";
}
// ende thb

function cleanRem() {
	document.getElementById("query").value = "";
	document.getElementById("remId").value = "";
	document.getElementById("results").innerHTML = "";
}

function getMateria(remId) {
	if (!remId || remId == -1) {
		remId = document.getElementById("remId").value;
		if (remId == "") {
			return false;
		}
	}
	var url = "forms/materia_medica.php?rem=" + remId;
	if (typeof _paq !== 'undefined') {
		_paq.push(['trackPageView', url]);
	}
	url += "&ajax=1";
	if (document.getElementById("tabber")) {
		url += "&tab=2";
	}
	loadurl(url, "POST", "materia_medica", 0, 0, 0, 0, remId);
}

function getRemSymptoms(name) {
	var remId = document.getElementById("remId").value;
	var rubricId = document.getElementById("rem_rubrics").options[document.getElementById("rem_rubrics").selectedIndex].value;
	var grade = "1";
	if (document.getElementById(name + "2").checked) {
		grade = "2";
	} else if (document.getElementById(name + "3").checked) {
		grade = "3";
	}
	var url = "forms/reversed_rep.php?rem=" + remId + "&rem_rubric=" + rubricId + "&grade=" + grade;
	if (typeof _paq !== 'undefined') {
		_paq.push(['trackPageView', url]);
	}
	url += "&getRemSymptoms=1";
	loadurl(url, "POST", "reversed_rep");
}

function getSymRems(name) {
	var symId = document.getElementById("symId").value;
	var sort = document.getElementById("sort").options[document.getElementById("sort").selectedIndex].value;
	var grade = "1";
	if (document.getElementById(name + "2").checked) {
		grade = "2";
	} else if (document.getElementById(name + "3").checked) {
		grade = "3";
	}
	var url = "forms/sym_rems.php?sym=" + symId + "&sort=" + sort + "&grade=" + grade;
	if (typeof _paq !== 'undefined') {
		_paq.push(['trackPageView', url]);
	}
	url += "&getSymRems=1";
	loadurl(url, "POST", "sym_rems");
}

// treeview:

var UI = new Object();
UI.expand = "<img src='skins/original/img/folder_arrow.png' width='12' height='12'> <img src='skins/original/img/folder_aeskulap.png' width='12' height='12'> ";
UI.collapse = "<img src='skins/original/img/folder_open_arrow.png' width='12' height='12'> <img src='skins/original/img/folder_open_aeskulap.png' width='12' height='12'> ";
UI.expand_not_used = "<img src='skins/original/img/folder_arrow.png' width='12' height='12'> <img src='skins/original/img/folder.png' width='12' height='12'> ";
UI.collapse_not_used = "<img src='skins/original/img/folder_open_arrow.png' width='12' height='12'> <img src='skins/original/img/folder_open.png' width='12' height='12'> ";
UI.expand_main = "<img src='skins/original/img/main_folder_arrow.png' width='14' height='14'> <img src='skins/original/img/main_folder.png' width='14' height='14'> ";
UI.collapse_main = "<img src='skins/original/img/main_folder_open_arrow.png' width='14' height='14'> <img src='skins/original/img/main_folder_open.png' width='14' height='14'> ";
UI.single = "<span style='visibility:hidden'><img src='skins/original/img/folder_arrow.png' width='12' height='12'> </span><img src='skins/original/img/aeskulap.png' width='12' height='12'> ";

function collapse(outputId, rubric, value, mainRubric, inUse) {
	document.getElementById(outputId).style.display = "none";
	var str = 'symbol_'+outputId.replace(/_/g,'');
	if (document.getElementById(str)) {
		var mainId = outputId.replace(/_/g,'');
		var symbolhref = '<span id="symbol_'+mainId+'"><a href="javascript:expand(\''+outputId+'\','+rubric+','+value+','+mainRubric+','+inUse+');" ';
		if (mainRubric == 1) {
			symbolhref += 'class="nodecls_main">'+UI.expand_main+'</a></span>';
		} else if (inUse == 1) {
			symbolhref += 'class="nodecls">'+UI.expand+'</a></span>';
		} else {
			symbolhref += 'class="nodecls">'+UI.expand_not_used+'</a></span>';
		}
		document.getElementById(str).innerHTML = symbolhref;
	}
}

function expand(outputId, rubric, value, mainRubric, inUse) {
	loadChild(outputId, rubric, value);
	document.getElementById(outputId).style.display = "block";
	var str = 'symbol_'+outputId.replace(/_/g,'');
	if (document.getElementById(str)) {
		var mainId = outputId.replace(/_/g,'');
		var symbolhref = '<span id="symbol_'+mainId+'"><a href="javascript:collapse(\''+outputId+'\','+rubric+','+value+','+mainRubric+','+inUse+');" ';
		if (mainRubric == 1) {
			symbolhref += 'class="nodecls_main">'+UI.collapse_main+'</a></span>';
		} else if (inUse == 1) {
			symbolhref += 'class="nodecls">'+UI.collapse+'</a></span>';
		} else {
			symbolhref += 'class="nodecls">'+UI.collapse_not_used+'</a></span>';
		}
		document.getElementById(str).innerHTML = symbolhref;
	}
}

function collapse_static(outputId, mainRubric, inUse) {
	document.getElementById(outputId).style.display = "none";
	var str = 'symbol_'+outputId.replace(/_/g,'');
	if (document.getElementById(str)) {
		var mainId = outputId.replace(/_/g,'');
		var symbolhref = '<span id="symbol_'+mainId+'"><a href="javascript:expand_static(\''+outputId+'\','+mainRubric+','+inUse+');" ';
		if (mainRubric == 1) {
			symbolhref += 'class="nodecls_main">'+UI.expand_main+'</a></span>';
		} else if (inUse == 1) {
			symbolhref += 'class="nodecls">'+UI.expand+'</a></span>';
		} else {
			symbolhref += 'class="nodecls">'+UI.expand_not_used+'</a></span>';
		}
		document.getElementById(str).innerHTML = symbolhref;
	}
}

function expand_static(outputId, mainRubric, inUse) {
	document.getElementById(outputId).style.display = "block";
	var str = 'symbol_'+outputId.replace(/_/g,'');
	if (document.getElementById(str)) {
		var mainId = outputId.replace(/_/g,'');
		var symbolhref = '<span id="symbol_'+mainId+'"><a href="javascript:collapse_static(\''+outputId+'\','+mainRubric+','+inUse+');" ';
		if (mainRubric == 1) {
			symbolhref += 'class="nodecls_main">'+UI.collapse_main+'</a></span>';
		} else if (inUse == 1) {
			symbolhref += 'class="nodecls">'+UI.collapse+'</a></span>';
		} else {
			symbolhref += 'class="nodecls">'+UI.collapse_not_used+'</a></span>';
		}
		document.getElementById(str).innerHTML = symbolhref;
	}
}

function loadChild(outputId, rubric, value) {
	var url = "forms/treeview.php?rubric=" + rubric + "&pid=" + value;
	loadurl(url, "POST", outputId, 0, 1);
}

function generateChild(outputId, response) {
	var str = '';
	var i = 0;
	if (response.data.length == 0) {
		document.getElementById(outputId).style.display = "none";
	}
	var mainId = outputId.replace(/_/g,'');
	for(i = 0;i < response.data.length;i++) {
		str += '<div id="'+mainId+''+i+'" style="padding-left:20px;">';
		if (response.data[i].folder > 0) {
			if (response.data[i].in_use > 0) {
				str += '<span id="symbol_'+mainId+''+i+'"><a href="javascript:expand(\''+outputId+'_'+i+'\','+response.rubric+','+response.data[i].id+',0'+',1'+');" class="nodecls">'+UI.expand+'</a></span>';
				str += '<a href="javascript:selectSymptom('+response.data[i].id+');" class="nodecls">'+response.data[i].name+' </a>';
				str += '<a href="javascript:symptomData('+response.data[i].id+');" class="nodecls" title="' + lang[9] + '"><img src="skins/original/img/info.gif" width="12" height="12"></a></div>';
			} else {
				str += '<span id="symbol_'+mainId+''+i+'"><a href="javascript:expand(\''+outputId+'_'+i+'\','+response.rubric+','+response.data[i].id+',0'+',0'+');" class="nodecls">'+UI.expand_not_used+'</a></span>';
				str += '<span class="nodecls">'+response.data[i].name+'</span></div>';
			}
			str += '<div id="'+outputId+'_'+i+'" style="padding-left:20px;display:none"></div>';
		} else {
			str += '<span class="nodecls">'+UI.single+'</span>';
			str += '<a href="javascript:selectSymptom('+response.data[i].id+');" class="nodecls">'+response.data[i].name+' </a>';
			str += '<a href="javascript:symptomData('+response.data[i].id+');" class="nodecls" title="' + lang[9] + '"><img src="skins/original/img/info.gif" width="12" height="12"></a></div>';
		}
	}
	document.getElementById(outputId).innerHTML = str;
}

function selectSymptom(symptomId) {
	var url = "forms/treeview.php?id=" + symptomId;
	loadurl(url, "POST", 0, 0, 2);
}

function symptomData(symptomId) {
	tabOpen("symptominfo.php?sym=", symptomId, "GET", 3);
}

// end treeview


function colorizeRadioRow(idCheckCommon, idRowCommon, idForm) {
	var myform = document.getElementById(idForm);
	var inputTags = myform.elements;
	var radioCount = 0;
	for (var i=0, length = inputTags.length; i<length; i++) {
		if (inputTags[i].type == 'radio') {
			radioCount++;
		}
	}
	var j = 1;
	for (var i = 1; i <= radioCount; i++)
	{
		var idRow = idRowCommon + "_" + i;
		var idCheck = idCheckCommon + "_" + i;
		document.getElementById(idRow).className = (document.getElementById(idCheck).checked) ? "checked" : "unchecked_"+j;
		j = (j==1) ? 2 : 1;
	}
}
