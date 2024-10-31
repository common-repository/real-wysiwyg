<?php
/*
Plugin Name: Real WYSIWYG
Plugin URI: http://windyroad.org/software/wordpress/real-wysiwyg-plugin/
Description: Turn the TinyMCE Visual Editor in to a real WYSIWYG editor.
Version: 0.0.2
Author: Windy Road
Author URI: http://windyroad.org

Copyright (C)2007 Windy Road

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.This work is licensed under a Creative Commons Attribution 2.5 Australia License http://creativecommons.org/licenses/by/2.5/au/

*/ 

function real_wysiwyg_get_theme_styles() {
	$styles = array();
	$styles[] = get_stylesheet_uri();
	$styles = array_merge($styles, real_wysiwyg_extra_styles() );
	return apply_filters( 'real_wysiwyg_style_sheets', $styles );
}

function real_wysiwyg_get_theme_styles_ie() {
	$styles = array();
	$styles = array_merge($styles, real_wysiwyg_extra_styles_ie() );
	return apply_filters( 'real_wysiwyg_style_sheets_ie', $styles );
}


function real_wysiwyg_init_options() {
	$css = real_wysiwyg_extra_css();
	$style_sheets = real_wysiwyg_get_theme_styles();
	$style_sheets_ie = real_wysiwyg_get_theme_styles_ie();
?>
var before_real_wysiwyg = null;
if( initArray.init_instance_callback == undefined ) {
	initArray.init_instance_callback = "on_mce_init_real_wysiwyg";
}
else {
	before_real_wysiwyg = initArray.init_instance_callback;
	initArray.init_instance_callback = "on_mce_init_real_wysiwyg";
}

function on_mce_init_real_wysiwyg(inst) {
	if( before_real_wysiwyg != null ) {
		tinyMCE.settings[ 'init_instance_callback' ] = before_real_wysiwyg;
		tinyMCE.dispatchCallback(inst, 'init_instance_callback', 'initInstance', inst);
		tinyMCE.settings[ 'init_instance_callback' ] = "on_mce_init_real_wysiwyg";
	}
		<?php
		foreach( $style_sheets as $style ) {
			$style = trim( $style );
			if( !empty( $style ) ) {
		?>
		tinyMCE.importCSS(inst.getDoc(),
						  '<?php echo $style ?>');
			
		<?php
			}
		} ?>
		if( navigator.userAgent.indexOf('MSIE 6') != -1  ) {
			<?php foreach( $style_sheets_ie as $style ) {
			$style = trim( $style );
			if( !empty( $style ) ) {
		?>
			tinyMCE.importCSS(inst.getDoc(),
							  '<?php echo $style ?>');
		<?php 
			}
		} ?>
	}
	tinyMCE.importCSS(inst.getDoc(),
					  '../wp-content/plugins/real-wysiwyg/extra-style.css.php?style=<?php echo urlencode( $css ) ?>');
	
}

<?php
}

if ( !function_exists('wp_nonce_field') ) {
	define('REAL_WYSIWYG_NONCE', -1);
    function real_wysiwyg_nonce_field() { return; }        
} 
else {
	define('REAL_WYSIWYG_NONCE', 'real-wysiwyg-update-key');
    function real_wysiwyg_nonce_field() { return wp_nonce_field(REAL_WYSIWYG_NONCE); }
}


add_action('tinymce_before_init', 'real_wysiwyg_init_options');

function real_wysiwyg_save_options( $curr_options ) {
	// create array
	$mfg_options = $curr_options;
	$mfg_options[ get_stylesheet() ] = array();
	$mfg_options[ get_stylesheet() ]['extra_css'] = stripslashes( $_POST['real_wysiwyg_extra_css'] );
	$mfg_options[ get_stylesheet() ]['extra_styles'] = explode( "\n", stripslashes( $_POST['real_wysiwyg_extra_styles'] ) );
	$mfg_options[ get_stylesheet() ]['extra_styles_ie'] = explode( "\n", stripslashes( $_POST['real_wysiwyg_extra_styles_ie'] ) );
	if( $curr_options != $mfg_options )
		update_option('real_wysiwyg_options', $mfg_options);
	return $mfg_options;
}
function  real_wysiwyg_extra_css() {
	$real_wysiwyg = get_option('real_wysiwyg_options');
	$extra_css = "";
	if( isset( $real_wysiwyg[ get_stylesheet() ][ "extra_css" ] )
		&& !empty( $real_wysiwyg[ get_stylesheet() ][ "extra_css" ] ) ) {
		$extra_css = $real_wysiwyg[ get_stylesheet() ][ "extra_css" ];
	}
	else if( get_stylesheet() == 'default' ) {
		$extra_css = <<<REAL_WYSIWYG_DATA
.mceContentBody {
	line-height:1.4em;
	text-align:justify;
	font-size:75%;
	margin:2.5ex auto;
	padding: 0pt;
	width:450px;
	background:transparent url('../../themes/default/images/kubrickbg-ltr.jpg') repeat-y scroll center top;
	background-color:#e7e7e7;
}

.mceContentBody p {
	font-size:1.05em;
	line-height:1.34em;
}

.mceContentBody ul li:before {
	content: "\\00BB \\0020";
}

html>body.mceContentBody ul {
	list-style-image:none;
	list-style-position:outside;
	list-style-type:none;
	margin-left:0px;
	padding:0pt 0pt 0pt 10px;
	text-indent:-10px;
}

html>body.mceContentBody li {
	margin:7px 0pt 8px 10px;
}



.mceContentBody ol {
	padding: 0 0 0 35px;
	margin: 0;
}

.mceContentBody ol li {
	margin: 0;
	padding: 0;
}

.mceContentBody pre {
	font-family:'Courier New',Courier,Fixed;
	font-size:85%;
	line-height:1.65em;
}

REAL_WYSIWYG_DATA;
	}
	else if( get_stylesheet() == 'classic' ) {
			$extra_css = <<<REAL_WYSIWYG_DATA
.mceContentBody {
	border: none;
}
REAL_WYSIWYG_DATA;
	}
	return apply_filters( 'real_wysiwyg_extra_css', $extra_css );
}

function real_wysiwyg_extra_styles() {
	$real_wysiwyg = get_option('real_wysiwyg_options');
	$extra_styles = array();
	if( isset( $real_wysiwyg[ get_stylesheet() ][ "extra_styles" ] ) ) {
		$extra_styles = $real_wysiwyg[ get_stylesheet() ][ "extra_styles" ];
	}
	return $extra_styles;
}

function real_wysiwyg_extra_styles_ie() {
	$real_wysiwyg = get_option('real_wysiwyg_options');
	$extra_styles_ie = array();
	if( isset( $real_wysiwyg[ get_stylesheet() ][ "extra_styles_ie" ] ) ) {
		$extra_styles_ie = $real_wysiwyg[ get_stylesheet() ][ "extra_styles_ie" ];
	}
	return $extra_styles_ie;
	
}

function real_wysiwyg_options_page() { 
	$options = get_option('real_wysiwyg_options');
	if( isset( $_GET[ 'activated' ] ) && $_GET[ 'activated' ] == "true" ){
		?><div class="updated"><p><strong>Options saved.</strong></p></div><?php
 	}
    ?><div class="wrap" id="real_wysiwyg-options"><?php
		?><h2>Real WYSIWYG Options</h2><?php
		?><form method="post" action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']; ?>"><?php
			?><fieldset><?php
				?><input type="hidden" name="action" value="real_wysiwyg_save_options" /><?php
								
				?><p><label for="real_wysiwyg_extra_css" style="font-weight: bold;"><?php _e( 'Extra CSS:', REAL_WYSIWYG_DOMAIN ); ?></label><br/><?php
				?><textarea name="real_wysiwyg_extra_css" rows='15' style="width: 100%"/><?php echo real_wysiwyg_extra_css(); ?></textarea><br/><?php
				?>Add any css here for your current them, so it will display propery within a body element with a class of <code>.mceContentBody</code>.</p><?php

				?><p><label for="real_wysiwyg_extra_styles" style="font-weight: bold;"><?php _e( 'Extra StyleSheets:', REAL_WYSIWYG_DOMAIN ); ?></label><br/><?php
				?><textarea name="real_wysiwyg_extra_styles" rows='15' style="width: 100%"/><?php echo implode("\n", real_wysiwyg_extra_styles() ); ?></textarea><br/><?php
				?>If the theme you are using has more than one stylesheet, then you may need to add it here, seperated by a new line.</p><?php

				?><p><label for="real_wysiwyg_extra_styles_ie" style="font-weight: bold;"><?php _e( 'Extra StyleSheets for IE 6:', REAL_WYSIWYG_DOMAIN ); ?></label><br/><?php
				?><textarea name="real_wysiwyg_extra_styles_ie" rows='15' style="width: 100%"/><?php echo implode("\n", real_wysiwyg_extra_styles_ie() ); ?></textarea><br/><?php
				?>If the theme you are using has stylesheets just for IE 6, then add them here, seperated by a new line.</p><?php

				real_wysiwyg_nonce_field();
			?></fieldset><?php
			?><p class="submit"><?php
				?><input type="submit" name="submit" value="Update Options &raquo;" /><?php
			?></p><?php
		?></form><?php
	?></div><?php
}


function real_wysiwyg_add_admin() {
	// Add a new menu under Options:
	add_options_page('Real WYSIWYG', 'Real WYSIWYG', 8, basename(__FILE__), 'real_wysiwyg_options_page');
}

add_action('admin_menu', 'real_wysiwyg_add_admin'); 		// Insert the Admin panel.

function real_wysiwyg_process_options() {
	$curr_options = get_option('real_wysiwyg_options');
	if ( isset($_POST['submit']) 
		&& isset($_POST['action']) 
		&& $_POST['action'] == 'real_wysiwyg_save_options' ) {

	    if ( function_exists('current_user_can') && !current_user_can('manage_options') )
	      die(__('Cheatin’ uh?'));
	
	    check_admin_referer(REAL_WYSIWYG_NONCE);
	
		real_wysiwyg_save_options( $curr_options );
		$url = add_query_arg( 'activated', 'true', $_SERVER[ 'HTTP_REFERER' ] );
		wp_redirect( $url );
	}	
}

add_action('init', 'real_wysiwyg_process_options'); //Process the post options for the admin page.

function real_wysiwyg_mce_css( $text ) {
	$css = real_wysiwyg_extra_css();
	$style_sheets = real_wysiwyg_get_theme_styles();
	foreach( $style_sheets as $style ) {
		$text .= ',' . $style;		
	}
	$text .= ',' . '../wp-content/plugins/real-wysiwyg/extra-style.css.php?style=' . urlencode( $css );

	return $text;
}

add_filter('mce_css', 'real_wysiwyg_mce_css');

function real_wysisyg_tiny_mce_before_init( $options ) {
	$options[ 'body_class' ] = 'entry custom';
	return $options;
}

add_filter('tiny_mce_before_init', 'real_wysisyg_tiny_mce_before_init');

?>