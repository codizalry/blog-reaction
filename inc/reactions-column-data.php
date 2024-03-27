<?php
//function for the computation of the total number reactions.
function total_reacts($reaction_id_list){
  $reaction_argument = array(
    'post_type'      => 'article',
    'post_status'    => 'publish',
    'posts_per_page' => '-1',
  );
  $reaction_query = new WP_Query( $reaction_argument );
  if ( $reaction_query->have_posts() ) :
    $total_reacted_count = 0;
    while ( $reaction_query->have_posts() ) : $reaction_query->the_post();
    if (isset(get_post_meta(get_the_ID(), $reaction_id_list)[0])) {
      $total_reacted_count+= get_post_meta(get_the_ID(), $reaction_id_list)[0];
    }
  endwhile;
endif;

return $total_reacted_count;
}

//Function for Creating column in article list of the Total number reactions.
add_filter('manage_edit-article_columns', 'article_column');
function article_column($columns) {
  $columns['total_reacts'] =__('全反応 <b>('.total_reacts('reaction_total_db').')</b> ');
  return $columns;
}

add_action( 'manage_article_posts_custom_column', 'article_post_data_column', 10, 2 );
function article_post_data_column( $column_name, $post_id ) {
  if ( 'total_reacts' != $column_name )
  return;
  echo intval(get_post_meta($post_id, 'reaction_total_db', true));
}

add_filter( 'manage_edit-article_sortable_columns', 'article_post_sortable_column' );
function article_post_sortable_column( $columns ) {
  $columns['total_reacts'] = 'total_views';
  return $columns;
}

add_action( 'pre_get_posts', 'article_orderby' );
function article_orderby( $query ) {
  if( ! is_admin() )
  return;

  $orderby = $query->get( 'orderby');
  if( 'total_views' == $orderby ) {
    $query->set('meta_key','reaction_total_db');
    $query->set('orderby','meta_value_num');
  }
}

//Function for Creating column in article list of the Total number per reactions.
function reaction_list(){
  $blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
  if (is_array($blog_reaction_repeater)) {
    $count=0;
    foreach ($blog_reaction_repeater as $reaction) {
      $count++;
      //variables for ID and label
      $reaction_id_list = $reaction['reaction_id'];
      $reaction_label_list = $reaction['label_txt'];
      //Dynamic function for the creating column
      $reaction_label_list = function() use ($reaction_id_list, $reaction_label_list, $count){
        $reaction_label_list = function($columns) use ($reaction_id_list, $reaction_label_list, $count){
          $columns['reaction_'.$count] = $reaction_label_list.' <b>('.total_reacts('blog_reaction_'.$reaction_id_list).')</b> ';
          return $columns;
        };
        add_filter('manage_edit-article_columns', $reaction_label_list);

        $reaction_label_list = function($column, $post_id) use ($reaction_label_list, $reaction_id_list, $count){
          if ( $column === 'reaction_'.$count) {
            echo intval(get_post_meta($post_id, 'blog_reaction_'.$reaction_id_list, true));
          }
        };
        add_action( 'manage_article_posts_custom_column', $reaction_label_list, 10, 2 );

        $reaction_label_list = function($columns) use ($count){
          $columns['reaction_'.$count] = 'reaction_'.$count;
          return $columns;
        };
        add_filter( 'manage_edit-article_sortable_columns', $reaction_label_list );

        $reaction_label_list = function($query) use ($reaction_id_list,$count){
          if( ! is_admin() )
          return;

          $orderby = $query->get( 'orderby');
          if( 'reaction_'.$count == $orderby ) {
            $query->set('meta_key','blog_reaction_'.$reaction_id_list);
            $query->set('orderby','meta_value_num');
          }
        };
        add_action( 'pre_get_posts', $reaction_label_list );
      };
      $reaction_label_list($reaction_id_list, $reaction_label_list);
    }
  }
};
add_action('init', 'reaction_list');
?>
