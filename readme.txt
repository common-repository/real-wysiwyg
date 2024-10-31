=== Real WYSIWYG ===
Contributors: tompahoward
Donate link: http://windyroad.org/software/wordpress/real-wysiwyg-plugin/#donate
Tags: Formatting, post, admin, editor, wysiwyg, html, Windy Road
Requires at least: 2.2
Tested up to: 2.2
Stable tag: 0.0.2

The Real WYSIWYG plugin turns the TinyMCE Visual Editor in to a real WYSIWYG editor.

== Description ==

The Real WYSIWYG plugin turns the TinyMCE Visual Editor in to a real WYSIWYG editor.
TinyMCE allows you to import style sheets into the editor window.  The Real
WYSIWYG plugin uses this ability to import your theme's style sheet, allowing it to display
the post as it will look when published.

== Installation ==

1. copy the 'real-wysiwyg' directory to your 'wp-contents/plugins' directory.
1. Activate the Real WYSIWYG plugin in your plugins administration page.
1. If your theme supports Real WYSIWYG then the editor window should now look
the same as your posts.  Otherwise, you may need to configure Real WYSIWYG via the options
menu to include any extra stylesheets your theme may have and to include any extra css
so that the editor body will be correctly styled.
For instance, for the default them, the following CSS is some of what is added to the Extra CSS field:
	.mceContentBody {
		line-height:1.31em;
		text-align:justify;
		font-size:0.8em;
		margin:2.5ex auto;
		padding: 0pt;
		width:450px;
		background:transparent url('../../themes/default/images/kubrickbg-ltr.jpg') repeat-y scroll center top;
		background-color:#e7e7e7;
	}

== Screenshots ==

1. Editing a post (in the [Vistered Little Theme](http://windyroad.org/software/wordpress/vistered-little-theme/ )) using TinyMCE and the Real WYSIWYG plugin
2. The same post as published.
3. Screenshots 1 and 2 combined at 50%. Pixel Perfect.

== Frequently Asked Questions ==

= How do I make my theme support Real WYSIWYG =

If your theme uses more than onw style sheet, then you should add a filter
on `real_wysiwyg_style_sheets` and add the URI for you stylesheets to the
array that is passed in.
If you have stylesheets that are only for IE 6,
then you can create a filter on `real_wysiwyg_style_sheets_ie` and add your
IE 6 stylesheets to it's array.
If you have inline styles that need to be applied, then create a filter on
`real_wysiwyg_extra_css` and add your required CSS to the string.

You will also need to update you stylesheets so that the CSS that applies
to a `post` also applies to an element with a class of `mceContentBody`. If
you don't have it already, Firebug for Firefox is excellent for determining
what styles are applied on an element.  You can use this to see what is
applied to the `post` and create extra rules for the `mceContentBody` for
anything it missed.  Finally, I use screenshots of the post in the editor
and as published to compare the styles and tweek the `mceContentBody` style
as needed.

== Release Notes ==
* 0.0.2
    * Fixed bug that prevented automatic support for the Default Theme.
    * Added some more CSS for the Default theme.
* 0.0.1
	* Added filter for inserting extra CSS.
	* Tweeked the escaping of the generated extra CSS output.
* 0.0.0 
	* Initial Release