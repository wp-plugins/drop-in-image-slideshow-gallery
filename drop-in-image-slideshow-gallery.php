<?php

/*
Plugin Name: drop in image slideshow gallery
Plugin URI: http://www.gopiplus.com/work/2010/07/18/drop-in-image-slideshow-gallery/
Description:  This drop in image slideshow gallery is your regular image slideshow, except each image is dropped into view. this effect that works in all major browsers. The slideshow stops dropping when the mouse is over it.
Author: Gopi.R
Version: 6.0
Author URI: http://www.gopiplus.com/work/2010/07/18/drop-in-image-slideshow-gallery/
Donate link: http://www.gopiplus.com/work/2010/07/18/drop-in-image-slideshow-gallery/
DIISG = drop in image slideshow gallery
*/

/**
 *     Vertical scroll recent registered user
 *     Copyright (C) 2012  www.gopiplus.com
 *     http://www.gopiplus.com/work/2010/07/18/drop-in-image-slideshow-gallery/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function DIISG_slideshow() 
{
	echo "<div style='padding:3px;'></div>";
	?>
	<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/drop-in-image-slideshow-gallery/drop-in-image-slideshow-gallery.js"></script>
	<script type="text/javascript">
	var DIISG_images=new Array()
    <?php
	$DIISG_width = get_option('DIISG_width');
	$DIISG_height = get_option('DIISG_height');
	$DIISG_delay = get_option('DIISG_delay');
	$DIISG_dir = get_option('DIISG_dir');
	$DIISG_link = get_option('DIISG_link');
	$siteurl_link = get_option('siteurl') . "/";

	if($DIISG_link <> ""){
		$DIISG_str_link = $DIISG_link;
	}
	else{
		$DIISG_str_link = "";
	}

	$DIISG_dirHandle = opendir($DIISG_dir);
	$DIISG_count = 0;
	$DIISG_returnstr = "";
	while ($DIISG_file = readdir($DIISG_dirHandle)) 
	{
	  if(!is_dir($DIISG_file) && (strpos($DIISG_file, '.jpg')>0 or strpos($DIISG_file, '.gif')>0)) 
	  {
		 
		 if($DIISG_link == "" )
		 {
		 	$DIISG_str =  '["' . $siteurl_link . $DIISG_dir . $DIISG_file . '", "", ""]';
		 }
		 else
		 {
		 	$DIISG_str =  '["' . $siteurl_link . $DIISG_dir . $DIISG_file . '", "'. $DIISG_link .'", "_new"]';
		 }
		 
		 $DIISG_returnstr = $DIISG_returnstr . "DIISG_images[$DIISG_count]=$DIISG_str; ";
	  	 $DIISG_count++;
	  }
	} 
	
    echo $DIISG_returnstr;
	closedir($DIISG_dirHandle)
	
	?>
    new DIISG(DIISG_images, <?php echo $DIISG_width; ?>, <?php echo $DIISG_height; ?>, <?php echo $DIISG_delay; ?>)
    </script>
    <?php
	echo "<div style='padding:3px;'></div>";
}

function DIISG_install() 
{
	add_option('DIISG_title', "Drop In Slide Show");
	add_option('DIISG_width', "200");
	add_option('DIISG_height', "150");
	add_option('DIISG_delay', "3000");
	add_option('DIISG_dir', "wp-content/plugins/drop-in-image-slideshow-gallery/gallery/");
	add_option('DIISG_link', "");
}

function DIISG_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('DIISG_title');
	echo $after_title;
	DIISG_slideshow();
	echo $after_widget;
}

function DIISG_control() 
{
	$DIISG_title = get_option('DIISG_title');
	$DIISG_width = get_option('DIISG_width');
	$DIISG_height = get_option('DIISG_height');
	$DIISG_delay = get_option('DIISG_delay');
	$DIISG_dir = get_option('DIISG_dir');
	$DIISG_link = get_option('DIISG_link');
	
	if (@$_POST['DIISG_submit']) 
	{
		$DIISG_title = stripslashes($_POST['DIISG_title']);
		$DIISG_width = stripslashes($_POST['DIISG_width']);
		$DIISG_height = stripslashes($_POST['DIISG_height']);
		$DIISG_delay = stripslashes($_POST['DIISG_delay']);
		$DIISG_dir = stripslashes($_POST['DIISG_dir']);
		$DIISG_link = stripslashes($_POST['DIISG_link']);
		
		update_option('DIISG_title', $DIISG_title );
		update_option('DIISG_width', $DIISG_width );
		update_option('DIISG_height', $DIISG_height );
		update_option('DIISG_delay', $DIISG_delay );
		update_option('DIISG_dir', $DIISG_dir );
		update_option('DIISG_link', $DIISG_link );
	}
	
	echo '<p">Title:<br><input  style="width: 450px;" maxlength="100" type="text" value="';
	echo $DIISG_title . '" name="DIISG_title" id="DIISG_title" /></p>';
	echo '<table width="490" border="0" cellspacing="0" cellpadding="2">';
	echo '<tr><td colspan="3" style="color:#999900;">';
	echo '<p>For best view, arrange the width & height perfectly to match with your site side bar. Set the height to the height of the LARGEST image in your slideshow!</p>';
	echo '</td></tr>';
	echo '<tr>';
	echo '<td>Width:</td>';
	echo '<td>Height:</td>';
	echo '<td>Delay:</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td><input  style="width: 75px;" maxlength="3" type="text" value="' . $DIISG_width . '" name="DIISG_width" id="DIISG_width" />px</td>';
	echo '<td><input  style="width: 75px;" maxlength="3" type="text" value="' . $DIISG_height . '" name="DIISG_height" id="DIISG_height" />px</td>';
	echo '<td><input  style="width: 75px;" maxlength="5" type="text" value="' . $DIISG_delay . '" name="DIISG_delay" id="DIISG_delay" />milliseconds</td>';
	echo '</tr>';
	echo '</table>';
	echo '<p></p>';
	echo '<p>Image directory:(Your entire image should be placed within below mentioned path)<br><input  style="width: 450px;" type="text" value="';
	echo $DIISG_dir . '" name="DIISG_dir" id="DIISG_dir" /><br>Default : wp-content/plugins/drop-in-image-slideshow-gallery/gallery/</p>';
	echo '(Note: Dont upload your original images into this default folder, instead you change this default path to original path.)</p>';
	echo '<p><p>All image link:<br><input  style="width: 450px;" type="text" value="';
	echo $DIISG_link . '" name="DIISG_link" id="DIISG_link" /></p>';
	echo '<input type="hidden" id="DIISG_submit" name="DIISG_submit" value="1" />';
	
	echo 'Check official website for more info <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/drop-in-image-slideshow-gallery/">click here</a>';

}

function DIISG_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget('drop-in-image-slideshow', 'Drop In Slide Show', 'DIISG_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control('drop-in-image-slideshow', array('Drop In Slide Show', 'widgets'), 'DIISG_control', 'width=500');
	} 
}

function DIISG_deactivation() 
{
	delete_option('DIISG_title');
	delete_option('DIISG_width');
	delete_option('DIISG_height');
	delete_option('DIISG_delay');
	delete_option('DIISG_dir');
	delete_option('DIISG_link');
}

add_action("plugins_loaded", "DIISG_widget_init");
register_activation_hook(__FILE__, 'DIISG_install');
register_deactivation_hook(__FILE__, 'DIISG_deactivation');
add_action('init', 'DIISG_widget_init');
?>