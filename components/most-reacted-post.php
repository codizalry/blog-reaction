<?php
 $most_reactions_menu = get_field('most_reaction_menu', 'option');
 $most_reacted_post_menu = (!empty($most_reactions_menu['most_reacted_post_group'])) ? $most_reactions_menu['most_reacted_post_group'] : '';

 //Function for Displaying the total reacted post
 function most_reacted_reaction($post_ids) {
   $blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
   if (is_array($blog_reaction_repeater)) {
     foreach ($blog_reaction_repeater as $reaction) {
       $reaction_id_list[] = $reaction['reaction_id'];
     }
   }
   $reactions_result = 0;
   if (is_array($blog_reaction_repeater)) {
     foreach ($reaction_id_list as $id_list) {
       if(isset(get_post_meta($post_ids, 'blog_reaction_'.$id_list)[0])) {
         $reactions_result+= get_post_meta($post_ids, 'blog_reaction_'.$id_list)[0];
       }
     }
   }
   return $reactions_result;
 }

 $reaction_post_type_settings = get_field('post_config', 'option');
 $reaction_post_type = (!empty($reaction_post_type_settings['post_type_slct'])) ? $reaction_post_type_settings['post_type_slct'] : 'post';
 $reaction_taxonomy = (!empty($reaction_post_type_settings['tax_txt'])) ? $reaction_post_type_settings['tax_txt'] : 'uncategorized';
 $reaction_argument = array(
   'post_type'      => $reaction_post_type,
   'post_status'    => 'publish',
   'posts_per_page' => '-1',
 );
 $reaction_query = new WP_Query( $reaction_argument );
 if ( $reaction_query->have_posts() ) :
   while ( $reaction_query->have_posts() ) : $reaction_query->the_post();

   $article_category = wp_get_post_terms(get_the_ID(), array($reaction_taxonomy));

   if ($reaction_taxonomy != 'uncategorized' && is_array($article_category) && !empty($article_category[0]->name)) {
     $reaction_category = $article_category[0]->name;
   } else {
     $reaction_category = 'Uncategorized';
   }
   $data[most_reacted_reaction(get_the_ID())."-".get_the_ID()] = array(
     'id'         => get_the_ID(),
     'title'      => get_the_title(),
     'category'   => $reaction_category,
     'date'       => get_the_date(),
     'author'     => get_the_author(),
     'vote'       => most_reacted_reaction(get_the_ID()),
   );
   endwhile;
  wp_reset_postdata();
 endif;
 //Sorting and Display only 20 in array
 if ($data) {
   krsort($data, SORT_NUMERIC);
   $reactions = array_slice($data,0,20);
 } else {
   $reactions = '';
 }

   $total_votes = 0;
 foreach ($reactions as $reaction) {
   $total_votes+= $reaction['vote'];
 }
 ?>
<!-- Title and description -->
<div class="total-reactions">
  <div class="reaction-title">
  <?php
  $reacted_title = (!empty($most_reacted_post_menu['title_txt'])) ? $most_reacted_post_menu['title_txt'] : 'TOP 20 MOST VOTED REACTION ARTICLES.' ;
  $reacted_description = (!empty($most_reacted_post_menu['description'])) ? $most_reacted_post_menu['description'] : 'It represents the total number of reactions to each page and automatically ranks them. Clicking on an article title will display that page.' ;
   if ($reacted_title): ?>
     <h1 class="reacted-post-title"><?=$reacted_title;?></h1>
   <?php endif;
   if ($reacted_description): ?>
     <p class="reacted-post-description"><?=$reacted_description;?></p>
   <?php endif;
  ?>
  </div>
  <div class="total-number">
    <h1><?=$total_votes;?></h1>
  </div>
</div>

<!-- Most Reacted Table output -->
<div class="most-reacted-table">
  <table class="reactions-table">
    <tbody>
      <tr>
        <th>Post Title</th>
        <th>Author Name</th>
        <th>Category</th>
        <th>Date</th>
        <th>Total number of votes for reactions</th>
      </tr>
      <?php
      if (is_array($reactions)) {
        foreach ($reactions as $reaction) {
          $reactions_id = $reaction['id'];
          $reactions_title = $reaction['title'];
          $reactions_category = $reaction['category'];
          $reactions_date = $reaction['date'];
          $reactions_author = $reaction['author'];
          $reactions_vote = $reaction['vote'];
          ?>
          <?php if ($reactions_vote != 0): ?>
            <!-- Output of the table -->
            <tr>
              <td><?=edit_post_link( $reactions_title, '', '', $reactions_id );?></td>
              <td><p><?=$reactions_author;?></p></td>
              <td><p><?=$reactions_category;?></p></td>
              <td><p><?=$reactions_date;?></p></td>
              <td><p><?=$reactions_vote;?></p></td>
            </tr>
          <?php endif;
          //Result if the reaction still empty
          if (array_values($reactions)[0]['vote'] == '0'): ?>
          <tr>
            <td class="empty-reaction" colspan="5" style="padding: 15px 10px;">We can't find any post!</td>
          </tr>
          <?php break;
        endif;
        }
      } ?>
    </tbody>
  </table>
</div>
