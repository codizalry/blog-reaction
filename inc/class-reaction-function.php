<?php
class Blog_Reaction {
  //Plugin hooks
  function __construct() {
    add_action( 'wp_ajax_blog_reaction_react', array($this,'react'));
    add_action( 'wp_ajax_nopriv_blog_reaction_react', array($this,'react' ));
    add_action( 'wp_ajax_blog_reaction_get_reactions', array($this,'get_reactions'));
    add_action( 'wp_ajax_nopriv_blog_reaction_get_reactions', array($this,'get_reactions' ));
    add_action( 'wp_ajax_blog_reaction_get_html', array($this,'get_plugin_html'));
    add_action( 'wp_ajax_nopriv_blog_reaction_get_html', array($this,'get_plugin_html' ));
    add_action('wp_enqueue_scripts', array($this,'add_styles_and_scripts'));
    add_shortcode( 'blog_reaction_reactions', array($this, 'short_code') );
  }
  //Pass vote value to DB
  function get_amount($reaction, $id) {
    $meta_key = "blog_reaction_".$reaction;
    $amount = get_post_meta($id, $meta_key, true) ? intval(get_post_meta($id, $meta_key, true)) : 0;
    return $amount;
  }
  //Shortcode for reaction function
  function short_code() {
    $post_id = get_the_ID();
    $post_object = $this->get_post_object($post_id);
    return $this->render_plugin($post_object);
  }

  //Create separate DB for the list of reactions and Deletion of data in DB
  function reactions_database(){
    $blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
    if (is_array($blog_reaction_repeater)) {
      foreach ($blog_reaction_repeater as $reaction) {
        $reaction_id_list[] = $reaction['reaction_id'];
      }
      $reaction_db_data = get_post_meta(99999,'reaction_db');
        foreach ($reaction_db_data[0] as $reaction_data) {
          if (in_array($reaction_data, $reaction_id_list) == false) {
            delete_post_meta_by_key('blog_reaction_'.$reaction_data);
          }
        }
      update_post_meta(99999,'reaction_db', $reaction_id_list);
    } else {
      update_post_meta(99999,'reaction_db', 0);
    }
  }
  //Fetching the Post Object of current page
  function get_post_object($post_id) {
    $post_url = get_permalink($post_id);
    $title = strip_tags(get_the_title($post_id));
    $tagObjects = get_the_tags($post_id);
    $single = is_single();
    $tags = "";
    if (!is_wp_error($tagObjects) && !empty($tagObjects)) {
     $tags .= $tagObjects[0]->name;
     for ($i = 1; $i < count($tagObjects); $i++) {
       $tags .=  ",".$tagObjects[$i]->name;
     }
    }
    $category = get_the_category($post_id);
    $categories = "";
    if (!is_wp_error($category) && !empty($category)) {
     $categories .= $category[0]->name;
     for ($i=1; $i<count($category); $i++) {
       $categories .= ",".$category[$i]->name;
     }
    }
    $author = get_the_author();
    $date = get_the_date('U', $post_id) * 1000;
    $comments = get_comments_number($post_id);
    $post_object = array(
      'id' 				 => $post_id,
      'url' 			 => $post_url,
      'title' 	   => $title,
      'tags' 			 => $tags,
      'categories' => $categories,
      'comments' 	 => $comments,
      'date' 			 => $date,
      'author' 		 => $author,
      'single' 		 => $single,
      'img' 			 => get_the_post_thumbnail_url($post_id)
    );
    return $post_object;
  }

   //Output of the Reactions in UI page
   function get_plugin_html() {
     $this->reactions_database();?>
     <div class="react-icons">
       <?php $blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
       if (is_array($blog_reaction_repeater)) :
         $count=0;
       foreach ($blog_reaction_repeater as $reaction) :
         //Condition for image display with fallback image
         if (!empty($reaction['icon_img'])) {
            $image = $reaction['icon_img'];
         } else {
            $image = '🚫';
         }
         //Condition for label display with fallback label
         if (!empty($reaction['label_txt'])) {
              $data_reaction = $reaction['label_txt'];
         }  else {
              $data_reaction = "Please insert label";
         }
         $reaction_id = $reaction['reaction_id'];?>
          <div class="ri-inner-items" data-reaction="<?=$reaction_id;?>">
            <label for="<?=$count;?>">
              <span class="ri-badge">0</span>
              <input class="ri-radio-btn" type="radio" name="feedback" id="<?=$count;?>" value="<?=$count;?> ">
              <span class="ri-emoji"><?=$image;?></span>
              <!--span class="ri-txt"><#$data_reaction;?></span-->
            </label>
          </div>

       <?php $count++;
        endforeach;
       endif;
       ?>
     </div>
     <?php die();
   }

  //Add the post object in data target value
  function render_plugin($post_object) {
    $plugin = '<div class="d-reactions"';
    $plugin .= ' data-post-id="'.$post_object['id'].'"';
    $plugin .= ' data-post-url="'.$post_object['url'].'"';
    $plugin .= ' data-post-title="'.$post_object['title'].'"';
    $plugin .= ' data-post-img="'.$post_object['img'].'"';
    return $plugin;
  }

  //Update the react value ( toggle )
  function react() {
    if (isset($_POST["postid"])) {
      $post_id = intval($_POST["postid"]);
      $reaction = $_POST["reaction"];
      $unreact = $_POST["unreact"];
    }
    $amount = $this->get_amount($reaction, $post_id);
    if (isset($unreact) && $unreact === "true") {
      $amount = (int) $amount - 1;
      if ($amount >= 0) {
        update_post_meta($post_id, "blog_reaction_".$reaction, $amount);
        update_post_meta($post_id, "reaction_total_db", $amount);
      }
    }
    else {
      $amount = (int) $amount + 1;
      if ($amount >= 0) {
        update_post_meta($post_id, "blog_reaction_".$reaction, $amount);
        update_post_meta($post_id, "reaction_total_db", $amount);
      }
    }
    //Creating DB for total reactions
    $blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
    if (is_array($blog_reaction_repeater)) {
      foreach ($blog_reaction_repeater as $reaction) {
        $reaction_id_list[] = $reaction['reaction_id'];
      }
    }
    $reactions_result = 0;
    if (is_array($blog_reaction_repeater)) {
      foreach ($reaction_id_list as $id_list) {
        if(isset(get_post_meta($post_id, 'blog_reaction_'.$id_list)[0])) {
          $reactions_result+= get_post_meta($post_id, 'blog_reaction_'.$id_list)[0];
          if (get_post_meta($post_id, 'blog_reaction_'.$id_list)[0] == 0) {
            delete_post_meta($post_id, 'blog_reaction_'.$id_list);
          }
        }
      }
    }
    update_post_meta($post_id,'reaction_total_db', $reactions_result);
    if (get_post_meta($post_id, 'reaction_total_db')[0] == 0) {
      delete_post_meta($post_id, 'reaction_total_db');
    }
    wp_send_json(array( 'amount' => $amount)); // return;
  }

  //Display the voted value
  function get_reactions() {
    $response = array();
    $blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
    if (is_array($blog_reaction_repeater)) {
      foreach ($blog_reaction_repeater as $reaction) {
        $data_reaction[] = $reaction['reaction_id'];
      }
      if (!empty($_POST["posts"])) {
        foreach($_POST["posts"] as $id) {
          $id = intval($id);
          $meta = get_post_meta($id);
          $post = array();
          $reactions = $data_reaction;
          foreach($reactions as $reaction) {
            $post[$reaction] = isset($meta["blog_reaction_".$reaction]) ? intval($meta["blog_reaction_".$reaction][0]) : 0;
          }
          $response[$id] = $post;
        }
      }
      wp_send_json($response);
    }
  }

  function add_styles_and_scripts() {
    wp_enqueue_style( 'blog-reaction-font', 'https://fonts.googleapis.com/css?family=Open+Sans' );
    wp_enqueue_style( 'blog-reaction-style', str_replace("/inc","",plugins_url( 'assets/css/site-custom.css' , __FILE__ )), array(), "3.3" );
    wp_enqueue_script( 'idle-js', str_replace("/inc","",plugins_url( 'assets/js/idle.min.js' , __FILE__ )), array(), "0.0.2" );
    wp_enqueue_script( 'js-cookie', str_replace("/inc","",plugins_url( 'assets/js/js.cookie.min.js' , __FILE__ )), array(), "3.3" );
    wp_enqueue_script( 'blog-reaction-script', str_replace("/inc","",plugins_url( 'assets/js/site-custom.js' , __FILE__ )), array( 'jquery', 'js-cookie', 'idle-js' ), "3.3" );
    $localize = array(
      'ajax_url' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script( 'blog-reaction-script', 'blog_reaction_data', $localize );
  }
}

function tmj_blog_reactions() {
  $blog_reaction = new Blog_Reaction();
  $post_id = get_the_ID();
  $post_object = $blog_reaction->get_post_object($post_id);
  echo $blog_reaction->render_plugin($post_object);
  echo '>';
  echo '</div>';
}
new Blog_Reaction();
?>
