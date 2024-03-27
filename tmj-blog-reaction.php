<?php
  /*
   * Plugin Name: TMJ Blog Reaction
   * Description: Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam eget ante non diam pretium lacinia.
   * Author: TMJP DSO - Web and Multimedia Design ( Ryan Codizal )
   * Version: 1.0.0.0
   */

//WordPress security
defined( 'ABSPATH') or die ( 'Hey!!! what are you doing here kiddo ? ' );

//Function for fetching css and js
function css_and_script(){
  if (isset($_GET["page"])) {
  	$_GET['pages'] = $_GET["page"];
  } else {
  	$_GET['pages'] = '';
  }
  if (get_post_type() == 'article' || $_GET['pages'] == "tmj-blog-reaction" || $_GET['pages'] == "button-clicked-stat-menu" || $_GET['pages'] == "most-clicked-stat-menu" || $_GET['pages'] == "react-icon-and-label") {
    wp_enqueue_style('blog-reaction-admin-styles',  plugins_url( 'assets/css/admin-custom.css' , __FILE__ ));
    wp_enqueue_script('blog-reaction-admin-script', plugins_url( 'assets/js/admin-custom.js' , __FILE__ ), array('jquery'));
  }
}
add_action('admin_enqueue_scripts','css_and_script');

//function for creating admin panel ( TMJ Blog Reaction )
function tmjp_reaction_menu(){
  if (!current_user_can('hrs')) {
    add_menu_page(
      'TMJ Blog Reaction',
      'TMJ Blog Reaction',
      'read',
      'tmj-blog-reaction',
      'tmj_blog_reaction_dashboard',
      'dashicons-chart-area',
      56
    );
    add_submenu_page(
      'tmj-blog-reaction',
      'Most Shared Reactions',
      'Most Shared Reactions',
      'read',
      'button-clicked-stat-menu',
      'blog_button_clicked_reaction_statistics'
    );
    add_submenu_page(
      'tmj-blog-reaction',
      'Most Reacted Post',
      'Most Reacted Post',
      'read',
      'most-clicked-stat-menu',
      'blog_most_clicked_reaction_statistics'
    );
  }
}
add_action('admin_menu','tmjp_reaction_menu');

//Function for the sub menu of Blog Post Reaction Menu ( ACF Option Page )
if(function_exists('acf_add_options_page')) {
  acf_add_options_page(array(
    'page_title' 	=> 'Reactions Configuration',
    'menu_title' 	=> 'Configuration',
    'capability'  => 'administrator',
    'menu_slug' 	=> 'react-icon-and-label',
    'parent_slug'	=> 'tmj-blog-reaction',
    'position'    => false,
    'redirect'	  => false,
  ));
}

//ACF - Converting HEX to RGB
function hex2rgb($hex) {
  $hex = str_replace("#", "", $hex);
  if(strlen($hex) == 3) {
	  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
  } else {
	  $r = hexdec(substr($hex,0,2));
	  $g = hexdec(substr($hex,2,2));
	  $b = hexdec(substr($hex,4,2));
  }
  $rgb = array($r, $g, $b);
  return $rgb; // returns an array with the rgb values
}

//Fetch the Layout for most clicked statistics
function tmj_blog_reaction_dashboard(){
  include plugin_dir_path( __FILE__ ) . 'components/reaction-dashboard.php';
}

//Fetch the Layout for button statistics
function blog_button_clicked_reaction_statistics(){
  include plugin_dir_path( __FILE__ ) . 'components/most-shared-reactions.php';
}

//Fetch the Layout for most reacted post
function blog_most_clicked_reaction_statistics(){
  include plugin_dir_path( __FILE__ ) . 'components/most-reacted-post.php';
}

//Fetch the ACF Fields and Run the function in core
function reaction_functions() {
  include plugin_dir_path( __FILE__ ) . 'inc/acf_register.php';
}
add_action( 'init', 'reaction_functions' );

//Reaction function class
include plugin_dir_path( __FILE__ ) . 'inc/class-reaction-function.php';

//Reaction results in article post menu
include plugin_dir_path( __FILE__ ) . 'inc/reactions-column-data.php';

//Reaction results in article post menu
//Function for ACF Addons : Unique ID
// 1. set text domain
load_plugin_textdomain( 'acf-unique_id', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );

// 2. Include field type for ACF5
function include_field_types_unique_id( $version ) {
	include_once(plugin_dir_path( __FILE__ ) . 'inc/acf-unique_id-v5.php');
}
add_action('acf/include_field_types', 'include_field_types_unique_id');

//Add custom data to ACF Select via code.
add_filter('acf/load_field/name=post_type_slct', 'acf_load_post_types');

function acf_load_post_types($field) {
  $choices = get_post_types(
    array( 'show_in_nav_menus' => true ),
    'objects',
  );

  if (is_array($choices)) {
    foreach ( $choices as $post_type ) {
      if ($post_type->name != 'post' && $post_type->name != 'page') {
        $field['choices'][$post_type->name] = $post_type->label;
      }
    }
  }
  return $field;
}
