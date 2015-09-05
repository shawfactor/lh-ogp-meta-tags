<?php
/**
 * Plugin Name: LH OGP Meta Tags
 * Plugin URI: http://lhero.org/plugins/lh-ogp-meta/
 * Description: Customise your OGP meta tags the LocalHero way.
 * Version: 1.0
 * Author: Peter Shaw
 * Author URI: http://shawfactor.com/
 * Tags: OGP, Open Graph, facebook, Meta, html, head
 * License: GPL

=====================================================================================
Copyright (C) 2014 Peter Shaw

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WordPress; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
=====================================================================================
*/

define ( 'LH_OGP_META_PLUGIN_URL', plugin_dir_url(__FILE__)); // with forward slash (/).


class LH_ogp_meta_tags_plugin {

var $opt_name = "lh_ogp_meta-options";
var $hidden_field_name = 'lh_ogp_meta-submit_hidden';
var $ogp_image_name = 'lh_ogp_meta-image_name';
var $ogp_thumbnail_name = 'lh_ogp_meta-thumbnail_name';
var $fb_publisher_name = 'lh_ogp_meta-fb_publisher_name';
var $fb_article_author_name = 'lh_ogp_meta-fb_article_author_name';
var $fb_userids_field_name = 'lh_ogp_meta-fb_userids_field_name';
var $fb_page_app_field_name = 'lh_ogp_meta-fb_page_app_field_name';
var $options;
var $filename;

private function truncate_string($string,$min) {
$string = strip_shortcodes($string);
    $text = trim(strip_tags($string));
    if(strlen($text)>$min) {
        $blank = strpos($text,' ');
        if($blank) {
            # limit plus last word
            $extra = strpos(substr($text,$min),' ');
            $max = $min+$extra;
            $r = substr($text,0,$max);
            if(strlen($text)>=$max) $r=trim($r,'.').'...';
        } else {
            # if there are no spaces
            $r = substr($text,0,$min).'...';
        }
    } else {
        # if original length is lower than limit
        $r = $text;
    }

$r = trim(preg_replace('/\s\s+/', ' ', $r));
    return $r;
}

public function add_ogp_meta() {


echo "\n<!-- begin LH OGP meta output -->\n";

if (is_singular()){

$var = get_post(get_the_ID());

?>
<meta property="og:url" content="<?php the_permalink() ?>"/>   
<meta property="og:title" content="<?php single_post_title(''); ?>" />   
<meta property="og:description" content="<?php

if ($var->post_excerpt){ echo $var->post_excerpt;  } else {  echo $this->truncate_string($var->post_content,120); } 

?>" />   
<meta property="og:type" content="article" />
<meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
<?php 

if ($var->post_author){


if (get_the_author_meta($this->fb_article_author_name, $var->post_author )){
?>
<meta property="article:author" content="<?php echo get_the_author_meta($this->fb_article_author_name, $var->post_author ); ?>"/>
<meta name="author" content="<?php echo get_the_author_meta('display_name', $var->post_author ); ?>"/>
<?php 

}

}



if ($this->options[$this->fb_publisher_name]){


?>
<meta property="article:publisher" content="<?php echo $this->options[$this->fb_publisher_name]; ?>"/>
<?php 

}

if (get_post_meta($var->ID, $var->post_type."_".$this->ogp_thumbnail_name."_thumbnail_id", true)){


?>
<meta property="og:image" content="<?php $image = wp_get_attachment_image_src(get_post_meta($var->ID, $var->post_type."_".$this->ogp_thumbnail_name."_thumbnail_id", true), 'lh-ogp-meta-thumbnail'); echo $image[0]; ?>"/><?php 


} elseif (wp_get_attachment_thumb_url(get_post_thumbnail_id($var->ID))){

?>
<meta property="og:image" content="<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($var->ID), 'lh-ogp-meta-thumbnail'); echo $image[0]; ?>"/>
<?php 

} else {


$image = wp_get_attachment_image_src(get_option('lh_ogp_meta-ogp_image'), 'lh-ogp-meta-thumbnail');

echo "<meta property=\"og:image\" content=\"".$image[0]."\"/>\n";

}
} else {

$image = wp_get_attachment_image_src($this->options[$this->ogp_image_name], 'lh-ogp-meta-thumbnail');

echo "<meta property=\"og:title\" content=\"";
bloginfo('name');
echo "\"/>\n";

echo "<meta property=\"og:type\" content=\"website\"/>\n";
echo "<meta property=\"og:url\" content=\"";
bloginfo('url');
echo "\"/>\n";

echo "<meta property=\"og:image\" content=\"".$image[0]."\"/>\n";
echo "<meta property=\"og:site_name\" content=\"";
bloginfo('name');
echo "\"/>\n";

echo "<meta property=\"og:description\" content=\"";
bloginfo('description');
echo "\"/>\n";

}

if ($this->options[$this->fb_userids_field_name]){
echo "<meta property=\"fb:admins\" content=\"".$this->options[$this->fb_userids_field_name]."\" />\n";
}

if ($this->options[$this->fb_page_app_field_name]){
echo "<meta property=\"fb:app_id\" content=\"".$this->options[$this->fb_page_app_field_name]."\" />\n";
}
echo "<!-- end LH OGP meta output -->\n\n";



}


public function plugin_menu(){
	add_options_page('LH OGP Meta settings', 'LH OGP Meta', 'manage_options', $this->filename, array($this,"plugin_options"));
}


public function plugin_options(){

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}


    if( isset($_POST[$this->hidden_field_name]) && $_POST[ $this->hidden_field_name ] == 'Y' ) {
        // Read their posted value

if ($_POST[ $this->ogp_image_name."-url" ] != ""){
$options[$this->ogp_image_name] = $_POST[ $this->ogp_image_name ];
}

if (($_POST[ $this->fb_publisher_name ]) and ($_POST[ $this->fb_publisher_name ] != "")){
$options[$this->fb_publisher_name] = $_POST[ $this->fb_publisher_name ];
}

if (($_POST[$this->fb_page_app_field_name]) and ($_POST[ $this->fb_page_app_field_name ] != "")){
$options[$this->fb_page_app_field_name] = $_POST[ $this->fb_page_app_field_name ];
}

if (($_POST[$this->fb_userids_field_name]) and ($_POST[ $this->fb_userids_field_name ] != "")){
$options[$this->fb_userids_field_name] = $_POST[ $this->fb_userids_field_name ];
}



if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);

        // Put an settings updated message on the screen


?>
<div class="updated"><p><strong><?php _e('LH OGP Meta Settings Updated', 'menu-test' ); ?></strong></p></div>
<?php

} 

}
?>
	<div class="wrap">

<h2>LH OGP Meta Settings</h2>



<form method="post" action="" >	

<input type="hidden" name="<?php echo $this->hidden_field_name; ?>" value="Y" />

<div class="live-preview-image">
<label for="<?php echo $this->ogp_image_name; ?>">OGP Image</label>
<img alt="Tile Icon" id="tile-img-preview" src="<?php echo wp_get_attachment_url($this->options[$this->ogp_image_name]); ?>" /><br/>

<input type="hidden" id="<?php echo $this->ogp_image_name; ?>" name="<?php echo $this->ogp_image_name; ?>" value="<?php echo $this->options[$this->ogp_image_name]; ?>"  />

<input type="url" id="<?php echo $this->ogp_image_name; ?>-url" name="<?php echo $this->ogp_image_name; ?>-url" value="<?php echo wp_get_attachment_url($this->options[$this->ogp_image_name]); ?>" size="65" />
<input type="button" class="button" name="img_upload_button" id="img_upload_button" value="Upload/Select Image" />
</div>

<p>
<label for="<?php echo $this->fb_publisher_name; ?>">FB Publisher ID</label>
<input type="url" id="<?php echo $this->fb_publisher_name; ?>" name="<?php echo $this->fb_publisher_name; ?>" value="<?php echo $this->options[$this->fb_publisher_name]; ?>" size="40" />
</p>

<p>
<label for="<?php echo $this->fb_page_app_field_name; ?>"><?php _e("facebook page app:", 'menu-test' ); ?></label> 
<input type="number" id="<?php echo $this->fb_page_app_field_name; ?>" name="<?php echo $this->fb_page_app_field_name; ?>" value="<?php echo $this->options[$this->fb_page_app_field_name]; ?>" size="20">
</p>

<p>
<label for="<?php echo $this->fb_userids_field_name; ?>"><?php _e("facebook page userids:", 'menu-test' ); ?></label> 
<input type="number" id="<?php echo $this->fb_userids_field_name; ?>" name="<?php echo $this->fb_userids_field_name; ?>" value="<?php echo $this->options[$this->fb_userids_field_name]; ?>" size="20" />
</p>

				

<p class="submit">
<input type="submit" class="button-primary" value="Save Changes" />
</p>

</form>
<?php	
} //options page


// Prepare the media uploader
public function admin_scripts(){
	// must be running 3.5+ to use color pickers and image upload
	wp_enqueue_media();
        wp_register_script('lh-ogp-meta-admin', LH_OGP_META_PLUGIN_URL.'scripts/uploader.js', array('jquery','media-upload','thickbox'),'1.1');
	wp_enqueue_script('lh-ogp-meta-admin');
}

function add_new_image_sizes_to_wp() {

if ( function_exists( 'add_image_size' ) ) { 

add_image_size( 'lh-ogp-meta-thumbnail', 1500, 1500 ); 

}

}


public function add_schema($attr) {

$attr .= "\n xmlns:og=\"http://ogp.me/ns#\"";
return $attr;

}


public function extra_user_profile_field( $user ) {



?>

<table class="form-table">
<tr>
<th><label for="<?php echo $this->fb_article_author_name; ?>">Facebook user url</label></th>
<td><input type="text" name="<?php echo $this->fb_article_author_name; ?>" id="<?php echo $this->fb_article_author_name; ?>" value="<?php echo esc_attr( get_the_author_meta($this->fb_article_author_name, $user->ID ) ); ?>" class="regular-text" /></td>
</tr>
</table>

<?php

}


public function save_extra_user_profile_field( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
    update_user_meta( $user_id, $this->fb_article_author_name, $_POST[$this->fb_article_author_name]);

    $saved = true;
  }
  return true;
}



public function __construct() {


$this->options = get_site_option($this->opt_name);
$this->filename = plugin_basename( __FILE__ );

add_action('admin_menu', array($this,"plugin_menu"));
add_action( 'init', array($this,"add_new_image_sizes_to_wp"));
add_filter('language_attributes', array($this,"add_schema"));
add_action('wp_head', array($this,"add_ogp_meta"));
add_action( 'show_user_profile', array($this,"extra_user_profile_field"),10,1);
add_action( 'edit_user_profile', array($this,"extra_user_profile_field"),10,1);
add_action( 'personal_options_update', array($this,"save_extra_user_profile_field"));
add_action( 'edit_user_profile_update', array($this,"save_extra_user_profile_field"));
add_action( 'user_register', array($this,"save_extra_user_profile_field"));

if (isset($_GET['page']) && $_GET['page'] == $this->filename) {
	add_action('admin_enqueue_scripts', array($this,"admin_scripts"));
}

if (!class_exists('MultiPostThumbnails')) {

include_once('includes/multi-post-thumbnails.php');

}

  if (class_exists('MultiPostThumbnails')) {
            $types = array('post', 'page');
            foreach($types as $type) {
                new MultiPostThumbnails(array(
                    'label' => 'OGP Image',
                    'id' => $this->ogp_thumbnail_name,
                    'post_type' => $type
                    )
                );
            }
        }




}


}

$lh_ogp_meta_tags_instance = new LH_ogp_meta_tags_plugin();





function lh_ogp_meta_return_ogp_object_type_array() {


$return = array(
'article' => 'This object represents an article on a website. It is the preferred type for blog posts and news stories',
'book' => 'This object type represents a book or publication. This is an appropriate type for ebooks, as well as traditional paperback or hardback ',
'books.author' => 'This object type represents a single author of a book',
'books.book' => 'This object type represents a book or publication. This is an appropriate type for ebooks, as well as traditional paperback or hardback books',
'books.genre' => 'This object type represents the genre of a book or publication',
'business.business' => 'This object type represents a place of business that has a location, operating hours and contact information',
'fitness.course' => 'This object type represents the users activity contributing to a particular run, walk, or bike course',
'fitness.unit' => 'This object type represents a custom unit of measurement',
'game' => 'This is an unsupported object type',
'game.achievement' => 'This object type represents a specific achievement in a game. An app must be in the Games category in App Dashboard to be able to use this object type',
'game.stat_type' => '',	
'games.match' => '',	
'games.victory' => '',
'music.album' => 'This object type represents a music album; in other words, an ordered collection of songs from an artist or a collection of artists. An album can comprise multiple discs',
'music.musician' => 'This object type represents a musician or band. It is a subclass of the profile object type',
'music.playlist' => 'This object type represents a music playlist, an ordered collection of songs from a collection of artists',
'music.radio_station' => 'This object type represents a radio station of a stream of audio. The audio properties should be used to identify the location of the stream itself',
'music.song' => 'This object type represents a single song',

'object' => 'This type represents a generic object, and can be used for objects that are not of a common type, nor of a defined custom type',
'place' => 'This object type represents a place - such as a venue, a business, a landmark, or any other location which can be identified by longitude and latitude',
'product' => 'This object type represents a product. This includes both virtual and physical products, but it typically represents items that are available in an online store',
'product.group' => 'This object type represents a group of product items',
'product.item' => 'This object type represents a product item',
'profile' => 'This object type represents a person. While appropriate for celebrities, artists, or musicians, this object type can be used for the profile of any individual. The fb:profile_id field associates the object with a Facebook user',
'quickelectionelection' => 'This object type represents an election',
'restaurant.menu' => 'This object type represents a restaurants menu. A restaurant can have multiple menus, and each menu has multiple sections',
'restaurant.menu_item' => 'This object type represents a single item on a restaurants menu. Every item belongs within a menu section',
'restaurant.menu_section' => 'This object type represents a section in a restaurants menu. A section contains multiple menu items',
'restaurant.restaurant' => 'This object type represents a restaurant at a specific location',
'video.episode' => 'This object type represents an episode of a TV show and contains references to the actors and other professionals involved in its production. An episode is defined by us as a full-length episode that is part of a series. This type must reference the series this it is part of',
'video.movie' => 'This object type represents a movie, and contains references to the actors and other professionals involved in its production. A movie is defined by us as a full-length feature or short film. Do not use this type to represent movie trailers, movie clips, user-generated video content, etc',
'video.other' => 'This object type represents a generic video, and contains references to the actors and other professionals involved in its production. For specific types of video content, use the video.movie or video.tv_show object types. This type is for any other type of video content not represented elsewhere (eg. trailers, music videos, clips, news segments etc.)',
'video.tv_show' => 'This object type represents a TV show, and contains references to the actors and other professionals involved in its production. For individual episodes of a series, use the `video.episode` object type. A TV show is defined by us as a series or set of episodes that are produced under the same title (eg. a television or online series)',
'website' => 'This object type represents a website. It is a simple object type and uses only common Open Graph properties. For specific pages within a website, the article object type should be used'


);




}






?>