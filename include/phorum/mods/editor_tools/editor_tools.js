///////////////////////////////////////////////////////////////////////////////
//                                                                           //
// Copyright (C) 2007  Phorum Development Team                               //
// http://www.phorum.org                                                     //
//                                                                           //
// This program is free software. You can redistribute it and/or modify      //
// it under the terms of either the current Phorum License (viewable at      //
// phorum.org) or the Phorum License that was distributed with this file     //
//                                                                           //
// This program is distributed in the hope that it will be useful,           //
// but WITHOUT ANY WARRANTY, without even the implied warranty of            //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                      //
//                                                                           //
// You should have received a copy of the Phorum License                     //
// along with this program.                                                  //
///////////////////////////////////////////////////////////////////////////////

// Javascript code for the Phorum editor_tools module.

// Valid object ids for textarea objects to handle. The first object
// that can be matched will be use as the object to work with.
// This is done to arrange for backward compatibility between
// Phorum versions.
var editor_tools_textarea_ids = new Array(
    'phorum_textarea',  // Phorum 5.1
    'body',             // Phorum 5.2
    'message'           // PM interface
);

// Valid object ids for subject text field objects to handle.
var editor_tools_subject_ids = new Array(
    'phorum_subject',   // Phorum 5.1
    'subject'           // Phorum 5.2
);

// Storage for language translation strings from the Phorum language system.
var editor_tools_lang = new Array();

// Some variables for storing objects that we need globally.
var editor_tools_textarea_obj = null;
var editor_tools_subject_obj = null;
var editor_tools_size_picker_obj = null;
var editor_tools_smiley_picker_obj = null;
var editor_tools_subjectsmiley_picker_obj = null;
var editor_tools_help_picker_obj = null;

// A variable for storing the current selection range of the 
// textarea. Needed for working around an MSIE problem.
var editor_tools_textarea_range = null;

// A variable for storing all popup objects that we have, so we
// can hide them all at once.
var editor_tools_popup_objects = new Array();

// Storage for the tools that have to be added to the editor tools panel.
// The array value contains the following fields:
//
// 1) the id for the tool (must be unique)
// 2) a description to use as the tooltip title for the button
// 3) the icon image to display as a button.
// 4) the javascript action to run when the user clicks the button
// 5) optional: the width of the icon image
// 6) optional: the height of the icon image (presumed 20px by default)
//
// This array will be filled from PHP-generated javascript.
var editor_tools = new Array();

// Storage for help chapters that must be put under the editor tools
// help button. The array value contains the following fields:
//
// 1) a description that will be used as the clickable link text.
// 2) the url for the help page (absolute or relative to the Phorum dir).
//
// This array will be filled from PHP-generated javascript.
var editor_tools_help_chapters = new Array();

// Valid sizes to select from for the size picker. If you add or change sizes,
// remember to change the module language file to supply some display strings.
var editor_tools_size_picker_sizes = new Array(
    'x-large',
    'large',
    'medium',
    'small',
    'x-small'
);

// Smileys for the smiley picker.
// *_s = search strings (smileys)
// *_r = replace strings (image urls)
// These will be filled from PHP-generated javascript.
var editor_tools_smileys = new Array();
var editor_tools_smileys_r = new Array();
var editor_tools_smileys_a = new Array();
var editor_tools_subjectsmileys = new Array();
var editor_tools_subjectsmileys_r = new Array();
var editor_tools_subjectsmileys_a = new Array();

// The width and offset to the left of the smiley picker popup menus.
// These will be filled from PHP-generated javascript.
var editor_tools_smileys_popupwidth;
var editor_tools_smileys_popupoffset;
var editor_tools_subjectsmileys_popupwidth;
var editor_tools_subjectsmileys_popupoffset;

// The dimensions of the help window.
var editor_tools_help_width = '400px';
var editor_tools_help_height = '400px';

// The default height for our icons.
// This one is filled from PHP-generated javascript.
var editor_tools_default_iconheight;

// A simple browser check. We need to know the browser version, because
// the color picker won't work on at least MacOS MSIE 5.
var OLD_MSIE =
    navigator.userAgent.indexOf('MSIE')>=0 &&
    navigator.appVersion.replace(/.*MSIE (\d\.\d).*/g,'$1')/1 < 6;

// ----------------------------------------------------------------------
// Uitilty functions
// ----------------------------------------------------------------------

// Find the Phorum textarea object and return it. In case of
// problems, null will be returned.
function editor_tools_get_textarea()
{
    if (editor_tools_textarea_obj != null) {
        return editor_tools_textarea_obj;
    }

    for (var i=0; editor_tools_textarea_ids[i]; i++) {
        editor_tools_textarea_obj =
            document.getElementById(editor_tools_textarea_ids[i]);
        if (editor_tools_textarea_obj) break;
    }

    if (! editor_tools_textarea_obj) {
        alert("editor_tools.js library reports: " +
              "no textarea found on the current page.");
        return null;
    }

    return editor_tools_textarea_obj;
}

// Find the Phorum subject field object and return it. In case of
// problems, null will be returned.
function editor_tools_get_subjectfield()
{
    if (editor_tools_subject_obj != null) {
        return editor_tools_subject_obj;
    }

    for (var i=0; editor_tools_subject_ids[i]; i++) {
        editor_tools_subject_obj =
            document.getElementById(editor_tools_subject_ids[i]);
        if (editor_tools_subject_obj) break;
    }

    if (! editor_tools_subject_obj) {
        return null;
    }

    return editor_tools_subject_obj;
}

// Return a translated string, based on the Phorum language system.
function editor_tools_translate(str)
{
    if (editor_tools_lang[str]) {
        return editor_tools_lang[str];
    } else {
        return str;
    }
}

// Strip whitespace from the start and end of a string.
function editor_tools_strip_whitespace(str, return_stripped)
{
    var strip_pre = '';
    var strip_post = '';

    // Strip whitespace from end of string.
    for (;;) {
        var lastchar = str.substring(str.length-1, str.length);
        if (lastchar == ' ') {
            strip_post += ' ';
            str = str.substring(0, str.length-1);
        } else {
            break;
        }
    }

    // Strip whitespace from start of string.
    for (;;) {
        var firstchar = str.substring(0,1);
        if (firstchar == ' ') {
            strip_pre += ' ';
            str = str.substring(1);
        } else {
            break;
        }
    }

    if (return_stripped) {
        return new Array(str, strip_pre, strip_post);
    } else {
        return str;
    }
} 

// Close all popup windows and move the focus to the textarea.
function editor_tools_focus_textarea()
{
    var textarea_obj = editor_tools_get_textarea();
    if (textarea_obj == null) return;
    editor_tools_hide_all_popups();
    textarea_obj.focus();
}

// Close all popup windows and move the focus to the subject field.
function editor_tools_focus_subjectfield()
{
    var subjectfield_obj = editor_tools_get_subjectfield();
    if (subjectfield_obj == null) return;
    editor_tools_hide_all_popups();
    subjectfield_obj.focus();
}

// ----------------------------------------------------------------------
// Construction of the editor tools
// ----------------------------------------------------------------------

// Add the editor tools panel to the page.
function editor_tools_construct()
{
    var textarea_obj;
    var div_obj;
    var parent_obj;
    var a_obj;
    var img_obj;

    // If the browser does not support document.getElementById,
    // then the javascript code won't run. Do not display the
    // editor tools at all in that case.
    if (! document.getElementById) return;

    // No editor tools selected to display? Then we're done.
    if (editor_tools.length == 0) return;

    // Find the textarea and subject field object.
    textarea_obj = editor_tools_get_textarea();
    if (textarea_obj == null) return; // we consider this fatal.
    var subjectfield_obj = editor_tools_get_subjectfield();

    // Insert a <div> for containing the buttons, just before the textarea,
    // unless there is already an object with id "editor-tools". In that
    // case, the existing object is used instead.
    div_obj = document.getElementById('editor-tools');
    if (! div_obj) {
        parent_obj = textarea_obj.parentNode;
        div_obj = document.createElement('div');
        div_obj.id = 'editor-tools';
        parent_obj.insertBefore(div_obj, textarea_obj);
    }

    // Add the buttons to the new <div> for the editor tools.
    for (var i = 0; i < editor_tools.length; i++)
    {
        var toolinfo    = editor_tools[i];
        var tool        = toolinfo[0];
        var description = toolinfo[1];
        var icon        = toolinfo[2];
        var jsaction    = toolinfo[3];
        var iwidth      = toolinfo[4];
        var iheight     = toolinfo[5];

        // Do not use the color picker on MSIE 5. I tested this on a
        // Macintosh OS9 system and the color picker about hung MSIE.
        if (tool == 'color' && OLD_MSIE) continue;

        a_obj = document.createElement('a');
        a_obj.id = "editor-tools-a-" + tool;
        a_obj.href = "javascript:" + jsaction;

        img_obj = document.createElement('img');
        img_obj.id = "editor-tools-img-" + tool;
        img_obj.className = "editor-tools-button";
        img_obj.src = icon;
        img_obj.width = iwidth;
        img_obj.height = iheight;
        img_obj.style.padding = '2px';
        img_obj.alt = description;
	img_obj.title = description;

        // If an icon is added that is less high than our default icon
        // height, we try to make the button the same height as the
        // others by adding some dynamic padding to it.
        if (iheight < editor_tools_default_iconheight) {
            var fill = editor_tools_default_iconheight - iheight;
            var addbottom = Math.round(fill / 2);
            var addtop = fill - addbottom;
            img_obj.style.paddingTop = (addtop + 2) + 'px';
            img_obj.style.paddingBottom = (addbottom + 2) + 'px';
        }
        a_obj.appendChild(img_obj);

        // A special tool: subjectsmiley. This one is added to the
        // subject field instead of the textarea.
        // Find the subject text field. If we can't find one,
        // simply continue with the rest of the code.
        if (tool == 'subjectsmiley' && subjectfield_obj) {
            img_obj.style.verticalAlign = 'top';
            var parent = subjectfield_obj.parentNode;
            var sibling = subjectfield_obj.nextSibling;
            parent.insertBefore(a_obj, sibling);
        } else {
            div_obj.appendChild(a_obj);
        }
    }

    // Hide any open popup when the user clicks the textarea or subject field.
    textarea_obj.onclick = function() {
        editor_tools_hide_all_popups();
    };
    if (subjectfield_obj) {
        subjectfield_obj.onclick = function() {
            editor_tools_hide_all_popups();
        }
    }
}

// ----------------------------------------------------------------------
// Popup window utilities
// ----------------------------------------------------------------------

// Create a popup window.
function editor_tools_construct_popup(create_id, anchor)
{
    // Create the outer div for the popup window.
    var popup_obj = document.createElement('div');
    popup_obj.id = create_id;
    popup_obj.className = 'editor-tools-popup';
    popup_obj.style.display = 'none';
    document.body.appendChild(popup_obj);

    popup_obj._anchor = anchor;

    // Create the inner content div.
    var content_obj = document.createElement('div');
    content_obj.id = create_id + '-content';
    popup_obj.appendChild(content_obj);

    return new Array(popup_obj, content_obj);
}

// Toggle a popup window.
function editor_tools_toggle_popup(popup_obj, button_obj, width, leftoffset)
{
    // Determine where to show the popup on screen.
    var work_obj = button_obj;
    var top = work_obj.offsetTop + work_obj.offsetHeight + 2;
    var left = work_obj.offsetLeft;

    while (work_obj.offsetParent != null) {
        work_obj = work_obj.offsetParent;
        left += work_obj.offsetLeft;
        top += work_obj.offsetTop;
    }

    if (leftoffset) left -= leftoffset;
    if (width) popup_obj.style.width = width;

    // Move the popup window to the right place.
    if (popup_obj._anchor == 'r')
    {
        // Determine the screen width.
        if (document.documentElement.clientWidth) {
            // Firefox screen width.
            scrwidth = document.documentElement.clientWidth;
        } else {
            var scrwidth = document.body.clientWidth;
            // -16 for scrollbar that is counted in in some browsers.
            if (document.getElementById && !document.all) {
                scrwidth -= 16;
            }
        }

        var right = scrwidth - left - button_obj.offsetWidth;

        popup_obj.style.right = right + 'px';
        popup_obj.style.top = top + 'px';
    } else {
        popup_obj.style.left = left + 'px';
        popup_obj.style.top = top + 'px';
    }

    // Toggle the popup window's visibility.
    if (popup_obj.style.display == 'none') {
        editor_tools_hide_all_popups();
        popup_obj.style.display = 'block';
    } else {
        popup_obj.style.display = 'none';
        editor_tools_focus_textarea();
    }
}

// Register an object as a popup, so editor_tools_hide_all_popups() 
// can hide it.
function editor_tools_register_popup_object(object)
{
    if (! object) return;
    editor_tools_popup_objects[editor_tools_popup_objects.length] = object;
}

// Hide all objects that were registered as a popup.
function editor_tools_hide_all_popups()
{
    for (var i = 0; i < editor_tools_popup_objects.length; i++) {
        var object = editor_tools_popup_objects[i];
        object.style.display = 'none';
    }
}

// Save the selection range of the textarea. This is needed because
// sometimes clicking in a popup can clear the selection in MSIE.
function editor_tools_store_range()
{
    var ta = editor_tools_get_textarea();
    if (ta == null || ta.setSelectionRange || ! document.selection) return;
    ta.focus();
    editor_tools_textarea_range = document.selection.createRange();
}

// Restored a saved textarea selection range.
function editor_tools_restore_range()
{
    if (editor_tools_textarea_range != null)
    {
        editor_tools_textarea_range.select();
        editor_tools_textarea_range = null;
    }
}

// ----------------------------------------------------------------------
// Textarea manipulation
// ----------------------------------------------------------------------

// Add tags to the textarea. If some text is selected, then place the
// tags around the selected text. If no text is selected and a prompt_str
// is provided, then prompt the user for the data to place inside
// the tags.
function editor_tools_add_tags(pre, post, target, prompt_str)
{
    var text;
    var pretext;
    var posttext;
    var range;
    var ta = target ? target : editor_tools_get_textarea();
    if (ta == null) return;

    if(ta.setSelectionRange)
    {
        // Add pre and post to the text.
        pretext = ta.value.substring(0, ta.selectionStart);
        text = ta.value.substring(ta.selectionStart, ta.selectionEnd);
        posttext = ta.value.substring(ta.selectionEnd, ta.value.length);

        if (text == '' && prompt_str) {
            text = prompt(prompt_str, '');
            if (text == null) return;
        }

        // Strip whitespace from text selection and move it to the
        // pre- and post.
        var res = editor_tools_strip_whitespace(text, true);
        text = res[0];
        pre = res[1] + pre;
        post = post + res[2];

        ta.value = pretext + pre + text + post + posttext;

        // Set the cursor to a logical position.
        var cursorpos = pretext.length + pre.length;
        if (text.length != 0) cursorpos += text.length + post.length;
        ta.setSelectionRange(cursorpos, cursorpos);
        ta.focus();
    }
    else if (document.selection) /* MSIE support */
    {
        // Add pre and post to the text.
        ta.focus();
        range = document.selection.createRange();
        text = range.text;

        if (text == '' && prompt_str) {
            text = prompt(prompt_str, '');
            if (text == null) return;
        }

        if (text.length <= 0) {
            // Add pre and post to the text.
            range.text = pre + post;

            // Set the cursor to a logical position.
            range.moveStart("character", -(post.length));
            range.moveEnd("character", -(post.length));
            range.select();
        } else {
            // Strip whitespace from text selection and move it to the
            // pre- and post.
            var res = editor_tools_strip_whitespace(text, true);
            text = res[0];
            pre = res[1] + pre;
            post = post + res[2];

            // Add pre and post to the text.
            range.text = pre + text + post;

            // Set the cursor to a logical position.
            range.select();
        }
    } else { /* Support for really limited browsers, e.g. MSIE5 on MacOS */
        ta.value = ta.value + pre + post;
    }
}

// ----------------------------------------------------------------------
// Tool: [hr] (horizontal line)
// ----------------------------------------------------------------------

function editor_tools_handle_hr() {
    editor_tools_add_tags('\n[hr]\n', ''); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [b]...[/b] (bold)
// ----------------------------------------------------------------------

function editor_tools_handle_bold() {
    editor_tools_add_tags('[b]', '[/b]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [s]...[/s] (strike through)
// ----------------------------------------------------------------------

function editor_tools_handle_strike() {
    editor_tools_add_tags('[s]', '[/s]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [u]...[/u] (underline)
// ----------------------------------------------------------------------

function editor_tools_handle_underline() {
    editor_tools_add_tags('[u]', '[/u]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [i]...[/i] (italic)
// ----------------------------------------------------------------------

function editor_tools_handle_italic() {
    editor_tools_add_tags('[i]', '[/i]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [center]...[/center] (center text)
// ----------------------------------------------------------------------

function editor_tools_handle_center() {
    editor_tools_add_tags('[center]', '[/center]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [sub]...[/sub] (subscript)
// ----------------------------------------------------------------------

function editor_tools_handle_subscript() {
    editor_tools_add_tags('[sub]', '[/sub]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [sup]...[/sup] (superscript)
// ----------------------------------------------------------------------

function editor_tools_handle_superscript() {
    editor_tools_add_tags('[sup]', '[/sup]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [code]...[/code] (formatted code)
// ----------------------------------------------------------------------

function editor_tools_handle_code() {
    editor_tools_add_tags('[code]\n', '\n[/code]\n'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [email]...[/email] (email address link)
// ----------------------------------------------------------------------

function editor_tools_handle_email() {
    editor_tools_add_tags('[email]', '[/email]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [url=...]...[/url] (URL link)
// ----------------------------------------------------------------------

function editor_tools_handle_url()
{
    var url = 'http://';

    for (;;)
    {
        // Read input.
        url = prompt(editor_tools_translate("enter url"), url);
        if (url == null) return; // Cancel clicked.
        url = editor_tools_strip_whitespace(url);
        
        // Check the URL scheme (http, https, ftp and mailto are allowed).
        copy = url.toLowerCase();
        if (copy == 'http://' || (
            copy.substring(0,7) != 'http://' &&
            copy.substring(0,8) != 'https://' &&
            copy.substring(0,6) != 'ftp://' &&
            copy.substring(0,7) != 'mailto:')) {
            alert(editor_tools_translate("invalid url"));
            continue;
        }

        break;
    }

    editor_tools_add_tags('[url=' + url + ']', '[/url]', null, editor_tools_translate("enter url description")); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [color=...]...[/color] (text color)
// ----------------------------------------------------------------------

function editor_tools_handle_color()
{
    editor_tools_store_range();

    // Display the color picker.
    var img_obj = document.getElementById('editor-tools-img-color');
    showColorPicker(img_obj);
    return;
}

// Called by the color picker library.
function editor_tools_handle_color_select(color)
{
    editor_tools_restore_range();

    editor_tools_add_tags('[color=' + color + ']', '[/color]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [size=...]...[/size] (text size)
// ----------------------------------------------------------------------

function editor_tools_handle_size()
{
    editor_tools_store_range();

    // Create the size picker on first access.
    if (!editor_tools_size_picker_obj)
    {
        // Create a new popup.
        var popup = editor_tools_construct_popup('editor-tools-size-picker','l');
        editor_tools_size_picker_obj = popup[0];
        var content_obj = popup[1];

        // Populate the new popup.
        for (var i = 0; i < editor_tools_size_picker_sizes.length; i++)
        {
            var size = editor_tools_size_picker_sizes[i];
            var a_obj = document.createElement('a');
            a_obj.href = 'javascript:editor_tools_handle_size_select("' + size + '")';
            a_obj.style.fontSize = size;
            a_obj.innerHTML = editor_tools_translate(size);
            content_obj.appendChild(a_obj);

            var br_obj = document.createElement('br');
            content_obj.appendChild(br_obj);
        }

        // Register the popup with the editor tools.
        editor_tools_register_popup_object(editor_tools_size_picker_obj);
    }

    // Display the popup.
    var button_obj = document.getElementById('editor-tools-img-size');
    editor_tools_toggle_popup(editor_tools_size_picker_obj, button_obj);
}

function editor_tools_handle_size_select(size)
{
    editor_tools_hide_all_popups();
    editor_tools_restore_range();
    size = editor_tools_strip_whitespace(size);
    editor_tools_add_tags('[size=' + size + ']', '[/size]'); 
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: [img]...[/img] (Image URL)
// ----------------------------------------------------------------------

function editor_tools_handle_image()
{
    var url = 'http://';

    for (;;)
    {
        // Read input.
        url = prompt(editor_tools_translate("enter image url"), url);
        if (url == null) return; // Cancel clicked.
        url = editor_tools_strip_whitespace(url);
        
        // Check the URL scheme (http, https, ftp and mailto are allowed).
        var copy = url.toLowerCase();
        if (copy == 'http://' || (
            copy.substring(0,7) != 'http://' &&
            copy.substring(0,8) != 'https://' &&
            copy.substring(0,6) != 'ftp://')) {
            alert(editor_tools_translate("invalid image url"));
            continue;
        }

        break;
    }

    editor_tools_add_tags('[img]' + url + '[/img]', '');
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: Smileys
// ----------------------------------------------------------------------

function editor_tools_handle_smiley()
{
    // Create the smiley picker on first access.
    if (!editor_tools_smiley_picker_obj)
    {
        // Create a new popup.
        var popup = editor_tools_construct_popup('editor-tools-smiley-picker','l');
        editor_tools_smiley_picker_obj = popup[0];
        var content_obj = popup[1];

        editor_tools_smiley_picker_obj.style.width = editor_tools_smileys_popupwidth;

        // Populate the new popup.
        for (var i = 0; i < editor_tools_smileys.length; i++) 
        {
	    var s = editor_tools_smileys[i];
	    var r = editor_tools_smileys_r[i];
	    var a = editor_tools_smileys_a[i];
            var a_obj = document.createElement('a');
            a_obj.href = 'javascript:editor_tools_handle_smiley_select("'+s+'")';
            var img_obj = document.createElement('img');
            img_obj.src = r;
	    img_obj.title = a;
	    img_obj.alt = a;
            a_obj.appendChild(img_obj);

            content_obj.appendChild(a_obj);
        }

        // Register the popup with the editor tools.
        editor_tools_register_popup_object(editor_tools_smiley_picker_obj);
    }

    // Display the popup.
    var button_obj = document.getElementById('editor-tools-img-smiley');
    editor_tools_toggle_popup(
        editor_tools_smiley_picker_obj,
        button_obj,
        editor_tools_smileys_popupwidth,
        editor_tools_smileys_popupoffset
    );
}

// Called by the smiley picker.
function editor_tools_handle_smiley_select(smiley)
{
    smiley = editor_tools_strip_whitespace(smiley);
    editor_tools_add_tags(smiley, '');
    editor_tools_focus_textarea();
}

function editor_tools_handle_subjectsmiley()
{
    // Create the smiley picker on first access.
    if (!editor_tools_subjectsmiley_picker_obj)
    {
        // Create a new popup.
        var popup = editor_tools_construct_popup('editor-tools-subjectsmiley-picker','l');
        editor_tools_subjectsmiley_picker_obj = popup[0];
        var content_obj = popup[1];

        // Populate the new popup.
        for (var i = 0; i < editor_tools_subjectsmileys.length; i++) 
        {
	    var s = editor_tools_subjectsmileys[i];
	    var r = editor_tools_subjectsmileys_r[i];
	    var a = editor_tools_subjectsmileys_a[i];

            var a_obj = document.createElement('a');
            a_obj.href = 'javascript:editor_tools_handle_subjectsmiley_select("'+s+'")';
            var img_obj = document.createElement('img');
            img_obj.src = r;
            img_obj.alt = a;
	    img_obj.title = a;
            a_obj.appendChild(img_obj);
            content_obj.appendChild(a_obj);
        }

        // Register the popup with the editor tools.
        editor_tools_register_popup_object(editor_tools_subjectsmiley_picker_obj);
    }

    // Display the popup.
    var button_obj = document.getElementById('editor-tools-img-subjectsmiley');
    editor_tools_toggle_popup(
        editor_tools_subjectsmiley_picker_obj,
        button_obj,
        editor_tools_subjectsmileys_popupwidth,
        editor_tools_subjectsmileys_popupoffset
    );
}

// Called by the subject smiley picker.
function editor_tools_handle_subjectsmiley_select(smiley)
{
    smiley = editor_tools_strip_whitespace(smiley);
    editor_tools_add_tags(smiley, '', editor_tools_subject_obj);
    editor_tools_focus_subjectfield();
}

// ----------------------------------------------------------------------
// Tool: [quote]...[/quote] (add a quote)
// ----------------------------------------------------------------------

function editor_tools_handle_quote()
{
    // Read input.
    var who = prompt(editor_tools_translate("enter who you quote"), '');
    if (who == null) return;

    who = editor_tools_strip_whitespace(who);
    if (who == '') {
        editor_tools_add_tags('[quote]', '[/quote]'); 
    } else {
        editor_tools_add_tags('[quote=' + who + ']', '[/quote]'); 
    }
    
    editor_tools_focus_textarea();
}

// ----------------------------------------------------------------------
// Tool: Help
// ----------------------------------------------------------------------

function editor_tools_handle_help()
{
    var c = editor_tools_help_chapters;

    // Shouldn't happen.
    if (c.length == 0) {
        alert('No help chapters available');
        return;
    }

    // Exactly one help chapter available. Immediately open the chapter.
    if (c.length == 1) {
        editor_tools_handle_help_select(c[0][1]);
        return;
    }

    // Multiple chapters available. Show a help picker menu with some
    // choices. Create the help picker on first access.
    if (!editor_tools_help_picker_obj)
    {
        // Create a new popup.
        var popup = editor_tools_construct_popup('editor-tools-help-picker','r');
        editor_tools_help_picker_obj = popup[0];
        var content_obj = popup[1];

        // Populate the new popup.
        for (var i = 0; i < editor_tools_help_chapters.length; i++) 
        {
            var helpinfo = editor_tools_help_chapters[i];
            var a_obj = document.createElement('a');
            a_obj.href = 'javascript:editor_tools_handle_help_select("' + helpinfo[1] + '")';
            a_obj.innerHTML = helpinfo[0];
            content_obj.appendChild(a_obj);
            content_obj.appendChild(document.createElement('br'));
        }

        // Register the popup with the editor tools.
        editor_tools_register_popup_object(editor_tools_help_picker_obj);
    }

    // Display the popup.
    var button_obj = document.getElementById('editor-tools-img-help');
    editor_tools_toggle_popup(editor_tools_help_picker_obj, button_obj);
}

function editor_tools_handle_help_select(url)
{
    var help_window = window.open(
        url,
        'editor_tools_help',
        'resizable=yes,' +
        'menubar=no,' +
        'directories=no,' +
        'scrollbars=yes,' +
        'toolbar=no,' +
        'status=no,' +
        'width=' + editor_tools_help_width + ',' +
        'height=' + editor_tools_help_height
    );

    editor_tools_focus_textarea();
    help_window.focus();
}

