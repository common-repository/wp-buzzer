<?php
/*
Plugin Name: WP Buzzer
Version: 1.1.1
Plugin URI: http://artiko.net.pl/projekty/wordpress/google-buzz/
Description: Google Buzz plugin allows you to add the blog/page or post to Google Buzz 
Author: Artur Kosztyła
Author URI: http://artiko.net.pl/
*/

function wp_get_buzzer($before_title="",$after_title="") {
	$wp_buzzer_options = get_option('wp_buzzer_options');
	global $post;
	
	if (get_bloginfo('wpurl') == get_bloginfo('url')) {
		$buzz_link = "\"http://www.google.com/reader/link?url=". get_permalink( $post->ID ) ."&title=". get_the_title($post->ID) ."&srcTitle=".get_bloginfo('name')."&srcURL=". get_bloginfo('url') ."\" target=\"_blank\" rel=\"nofollow external\"";
	} else {
		$buzz_link = "\"http://www.google.com/reader/link?url=http://". $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ."&title=". get_bloginfo('name') ."&srcTitle=".get_bloginfo('name')."&srcURL=". get_bloginfo('url') ."\" target=\"_blank\" rel=\"nofollow external\"";
	}
	
	$out = "<table style='border:none;'>
  <tr>
    <td style='border:none;'><a href=". $buzz_link ."><img style='height:". $wp_buzzer_options['widget_height'] ."px;' src=\"". get_bloginfo('wpurl') ."/wp-content/plugins/wp-buzzer/googlebuzz.png\" alt=\"\" /></a></td>
    <td style='border:none; width:110px;'><a href=". $buzz_link .">Buzz this!</a><br />
	<a href=\"http://artiko.net.pl/projekty/wordpress/wp-buzzer/\" style='font-size:8px;'>WP Buzzer</a></td>
  </tr>
</table>";
	
	return $out;
}

function wp_buzzer(){
	
	$output = wp_get_buzz() ;

	echo $output;
}



add_action('plugins_loaded', 'widget_sidebar_wp_buzzer');
function widget_sidebar_wp_buzzer() {
	function widget_wp_buzzer($args) {
	    extract($args);
		
		echo $before_widget;
		
		$output = wp_get_buzzer();
		echo $output;
		echo $after_widget;
	}
	register_sidebar_widget('WP Buzzer', 'widget_wp_buzzer');
}


function activate_wp_buzzer() {
	//global $wp_buzzer;
	$wp_buzzer = array('rel'=> 'nofollow', 
	                           'location'=>'after',
							   'enable_after'=>'true',
							   'after_height'=>'35',
							   'widget_height'=>'50');
	add_option('wp_buzzer_options',$wp_buzzer);
}	

global $wp_buzzer;
$wp_buzzer = get_option('wp_buzzer_options');
register_activation_hook( __FILE__, 'activate_wp_buzzer' );


function add_wp_buzzer($content){ 
	$wp_buzzer_options = get_option('wp_buzzer_options');
	global $post;
	$buzz_link = "\"http://www.google.com/reader/link?url=".get_permalink( $post->ID )."&title=".get_the_title($post->ID)."&srcTitle=".get_bloginfo('name')."&srcURL=". get_bloginfo('url') ."\" target=\"_blank\" rel=\"nofollow external\"";
	$out = "<table style='border:none;'>
  <tr>
    <td style='border:none;'><a href=". $buzz_link ."><img style='height:". $wp_buzzer_options['after_height'] ."px;' src=\"". get_bloginfo('wpurl') ."/wp-content/plugins/wp-buzzer/googlebuzz.png\" alt=\"\" /></a></td>
    <td style='width:110px; border:none;'><a href=". $buzz_link .">Buzz this!</a>
  </tr>
</table>";

	if($wp_buzzer_options['enable_after'] == 'true' ){
    	$content = $content.$out;
  	}
  	return $content;
}

add_filter('the_content','add_wp_buzzer');
add_filter('the_excerpt', 'add_wp_buzzer');

function wp_buzzer_settings() {
    // Add a new submenu under Options:
    add_options_page('WP Buzzer', 'WP Buzzer', 9, basename(__FILE__), 'wp_buzzer_settings_page');
}

function wp_buzzer_settings_page() {
$wp_buzzer = get_option('wp_buzzer_options');
?>
<div class="wrap">
<h2>WP Buzzer</h2>

<form  method="post" action="options.php">
<div id="poststuff" class="metabox-holder has-right-sidebar"> 

<div style="float:left;width:60%;">
<?php
settings_fields('wp-buzzer-group');
?>
<h2>Settings</h2> 

<div class="postbox">
<h3 style="cursor:pointer;"><span>WP Buzzer Options</span></h3>
<div>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="wp_buzzer_options[enable_after]">Show after post:</label></th>
<td><select name="wp_buzzer_options[enable_after]">
<option value="true" <?php if ($wp_buzzer['enable_after'] == "true"){ echo "selected";}?> >True</option>
<option value="false" <?php if ($wp_buzzer['enable_after'] == "false"){ echo "selected";}?> >False</option>
</select></td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_buzzer_options[after_height]">Height of the Google Buzz button showed after post:</label></th> 
<td><input type="text" name="wp_buzzer_options[after_height]" class="small-text" value="<?php echo $wp_buzzer['after_height']; ?>" />&nbsp;px</td>
</tr>

<tr valign="top">
<th scope="row"><label for="wp_buzzer_options[widget_height]">Height of the Google Buzz button in the widget:</label></th> 
<td><input type="text" name="wp_buzzer_options[widget_height]" class="small-text" value="<?php echo $wp_buzzer['widget_height']; ?>" />&nbsp;px</td>
</tr>
</table>
</div>
</div>


<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>

</div>
</form>

   <div id="side-info-column" class="inner-sidebar"> 
			<div class="postbox"> 
			  <h3 class="hndle"><span>WP Buzzer</span></h3>
			  <div class="inside">
                <ul>
                <li><a href="http://artiko.net.pl/projekty/wordpress/wp-buzzer" title="WP Buzzer plugin page" target="_blank">Plugin Homepage</a></li>                
                </ul> 
              </div> 
			</div> 
     </div>
     

</div> 


</div> <!--end wrap -->
<?php	
}

// adding admin menus
if ( is_admin() ){ // admin actions
  add_action('admin_menu', 'wp_buzzer_settings');
  add_action( 'admin_init', 'register_wp_buzzer_settings' ); 
} 
function register_wp_buzzer_settings() { // whitelist options
  register_setting( 'wp-buzzer-group', 'wp_buzzer_options' );
}

?>