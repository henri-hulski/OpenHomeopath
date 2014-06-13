<html>
  <head>
    <title>BBcode help</title>
    <link rel="stylesheet" type="text/css" href="<?php print $GLOBALS["PHORUM"]["http_path"] ?>/mods/editor_tools/help/help.css"/>
  </head>
  <body>
    <h2>BBcode help information</h2>

    BBcode is short for Bulletin Board code. It is a markup language
    that can be used by forum users to format their messages. This help
    page describes what BBcode can be used on this forum.

    <h3>Bold text: [b]...[/b]<br/>
        Underlined text: [u]...[/u]<br/>
        Italic text: [i]...[/i]<br/>
        Striked through text: [s]...[/s]<br/>
        </h3>
    By using these tags, you can apply styles to pieces of text.
    Examples:<br/><br/>
    <tt>
    [b]This text is bold[/b]<br/>
    [u]This text is underlined[/u]<br/>
    [i]This text is italic[/i]<br/>
    [s]This text is striked through[/s]<br/>
    [b][i]This text[/i] is [s]mixet[/s] mixed[/b]
    </tt><br/><br/>
    These will be displayed as:<br/><br/>
    <b>This text is bold</b><br/>
    <i>This text is italic</i><br/>
    <u>This text is underlined</u><br/>
    <strike>This text is striked through</strike><br/>
    <b><i>This text</i> is <strike>mixet</strike> mixed</b>


    <h3>Superscript: [sup]...[/sup]<br/>Subscript: [sub]...[/sub] </h3>
    By using these tags, you can print a piece of text using subscript
    or superscript. This is for example useful for things like 
    "2<sup>4</sup> = 16" or "H<sub>2</sub>O". Example:<br/><br/>
    <tt>
    [sup]superscript[/sup] normal [sub]subscript[/sub]
    </tt><br/><br/>
    This will be displayed as:<br/><br/>
    <sup>superscript</sup> normal <sub>subscript</sub>

    <h3>Font color: [color=...]...[/color]</h3>
    This tag can be used for applying a color to a piece of text.
    The color has to be a valid HTML color code (e.g. "blue", "red",
    "#ff0000", "#888", etc.). Example:<br/><br/>
    <tt>
    Who is afraid of
    <span class="nobr">[color=red]red[/color],</span>
    <span class="nobr">[color=#eeaa00]yellow[/color]</span> and 
    <span class="nobr">[color=#30f]blue[/color]?</span>
    </tt><br/><br/>
    This will be displayed as:<br/><br/>
    Who is afraid of
    <span style="color: red">red</span>,
    <span style="color: #eeaa00">yellow</span> and
    <span style="color: #30F">blue</span>?

    <h3>Font size: [size=...]...[/size]</h3>
    This tag can be used for resizing a piece of text.
    The size has to be a valid HTML size indication (e.g. "12px",
    "small", "large", etc.). Example:<br/><br/>
    <tt>
    <span class="nobr">[size=x-small]It[/size]</span>
    <span class="nobr">[size=small]looks[/size]</span>
    <span class="nobr">[size=medium]like[/size]</span>
    <span class="nobr">[size=large]I'm[/size]</span>
    <span class="nobr">[size=x-large]growing![/size]</span>
    </tt><br/><br/>
    This will be displayed as:<br/><br/>
    <span style="font-size: x-small">It</span>
    <span style="font-size: small">looks</span>
    <span style="font-size: medium">like</span>
    <span style="font-size: large">I'm</span>
    <span style="font-size: x-large">growing!</span> 

    <h3>Center text: [center]...[/center]</h3>
    You can use this for centering a piece of text on the 
    center of the screen. Example:<br/><br/>
    <tt>
    [center]I'm right in the middle of it all[/center]
    </tt><br/><br/>
    This will be displayed as:<br/><br/>
    <center>I'm right in the middle of it all</center>

    <h3>Link an image from the web: [img]...[/img]<br/>
        Link to a website: [url]...[/url] or [url=...]...[/url]<br/>
        Link to an email address [email]...[/email]</h3>
    These are all tags for linking web resources. Here are
    some examples:<br/><br/>
    <tt>
    [img]http://www.somesite.com/cool/thumbsup.gif[/img]<br/>
    [url]http://www.phorum.org[/url]<br/>
    [url=http://www.phorum.org]Visit Phorum.org![/url]<br/>
    [email]someuser@somesite.com[/email]
    </tt><br/><br/>
    These will be displayed as:<br/><br/> 
    <img alt=""  src="<?php print $GLOBALS["PHORUM"]["http_path"] ?>/mods/editor_tools/help/thumbsup.gif" border="0"/><br/>
    [<a href="http://www.phorum.org">www.phorum.org</a>]<br/>
    <a rel="nofollow" href="http://www.phorum.org">Visit Phorum.org!</a><br/>
    <a href="mailto:someuser@somesite.com">someuser@somesite.com</a>

    <h3>Monospaced, formatted code: [code]...[/code]</h3>
    Sometimes, you might have things like ASCII art, programming
    code, guitar TABs, etc., which you want to put in your message.
    For those cases, you can use the [code] tag. Example:
<pre>
[code]
 _____  _                                
|  __ \| |                               
| |__) | |__   ___  _ __ _   _ _ __ ___  
|  ___/| '_ \ / _ \| '__| | | | '_ ` _ \ 
| |    | | | | (_) | |  | |_| | | | | | |
|_|    |_| |_|\___/|_|   \__,_|_| |_| |_|
[/code]
</pre>

Without the [code] around it, this would look totally scrambled, like:
<br/><br/>
  _____  _                                <br/>
 |  __ \| |                               <br/>
 | |__) | |__   ___  _ __ _   _ _ __ ___  <br/>
 |  ___/| '_ \ / _ \| '__| | | | '_ ` _ \ <br/>
 | |    | | | | (_) | |  | |_| | | | | | |<br/>
 |_|    |_| |_|\___/|_|   \__,_|_| |_| |_|<br/>
<br/>
But with the [code] around it, it looks like:
<pre style="border: 1px solid #dde; background-color: #ffe; padding: 0px 0px 0px 10px">
  _____  _                                
 |  __ \| |                               
 | |__) | |__   ___  _ __ _   _ _ __ ___  
 |  ___/| '_ \ / _ \| '__| | | | '_ ` _ \  
 | |    | | | | (_) | |  | |_| | | | | | |
 |_|    |_| |_|\___/|_|   \__,_|_| |_| |_|

</pre>

    <h3>Quoted text: [quote]...[/quote] or [quote=...]...[/quote]</h3>
    If you want to add some quote to your message, you can use
    this tag. You can choose whether you want to include the name of
    the person that you quote or not. Examples:<br/><br/>
    <tt>
    [quote]Phorum is the best![/quote]<br/>
    [quote=From Hamlet, by William Shakespeare]<br/>
    To be or not to be, --that is the question:--<br/>
    Whether 'tis nobler in the mind to suffer<br/>
    The slings and arrows of outrageous fortune<br/>
    Or to take arms against a sea of troubles,<br/>
    And by opposing end them?<br/>
    [/quote]
    </tt><br/><br/>
    These will be displayed as:<br/><br/> 
    <blockquote class="bbcode">Quote:<div>Phorum is the best!</div></blockquote>
    <blockquote class="bbcode">Quote:<div><strong>From Hamlet, by William Shakespeare</strong><br>
    To be or not to be, --that is the question:--
    <br>
    Whether 'tis nobler in the mind to suffer
    <br>
    The slings and arrows of outrageous fortune
    <br>
    Or to take arms against a sea of troubles,
    <br>
    And by opposing end them?
    <br></div></blockquote>

    <h3>Add a horizontal separator line: [hr]</h3>
    To add a separator line to your message, you can use [hr].
    This will look like:
    <hr>
    This is mainly useful for adding structure to very long messages.

    <br/><br/><br/><br/>
  </body>
</html>
