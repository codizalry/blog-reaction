<?php
//Main Repeater field
$blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
//Function for creating array data
$reaction_labels = array();
if (is_array($blog_reaction_repeater)) {
  foreach ($blog_reaction_repeater as $reaction) {
    $reaction_labels[] = $reaction['label_txt'];
  }
}

//function for fetching the data of reacted post
function most_reacted_post($post_ids) {
  $blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
  if (is_array($blog_reaction_repeater)) {
    foreach ($blog_reaction_repeater as $reaction) {
      $reaction_id_list[] = $reaction['reaction_id'];
      $reaction_label[] = $reaction['label_txt'];
    }

  $reactions_url = $_SERVER['REQUEST_URI'];
  $result = preg_split('/reactions=/',$reactions_url);
  if( count($result) > 1 ) {
    $result_split = explode(' ',$result[1]);
  }

  $menu_panel = $reactions_result = 0;
  if (isset($result_split[0])) {
    $menu_panel = $result_split[0];
  }
  if(isset(get_post_meta($post_ids, 'blog_reaction_'.$reaction_id_list[$menu_panel])[0])) {
    $reactions_result = get_post_meta($post_ids, 'blog_reaction_'.$reaction_id_list[$menu_panel])[0];
  }else {
    $reactions_result = '';
  }
} else {
  $reactions_result = '';
}

  return $reactions_result;
}

//Fetching of data in post
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
  //Creation of array for storing of data
    $data[most_reacted_post(get_the_ID())."-".get_the_ID()] = array(
      'id'         => get_the_ID(),
      'title'      => get_the_title(),
      'category'   => $reaction_category,
      'date'       => get_the_date(),
      'author'     => get_the_author(),
      'vote'       => most_reacted_post(get_the_ID()),
    );
  endwhile;
 wp_reset_postdata();
endif;
//function for sorting and slicing the array
if ($data) {
  krsort($data, SORT_NUMERIC);
  $reactions = array_slice($data,0,10);
}

// <!-- Displaying in table -->
$reactions_url = $_SERVER['REQUEST_URI'];
$result = preg_split('/reactions=/',$reactions_url);
if( count($result) > 1 ) {
  $result_split = explode(' ',$result[1]);
}

if (isset($result_split[0])) {
  $reaction_result = $reaction_labels[$result_split[0]];
}else {
  $reaction_result = !empty($reaction_labels) ? $reaction_labels[0] : 'TBA';
}

$most_reactions_menu = get_field('most_reaction_menu', 'option');
$most_shared_reaction_menu = !empty($most_reactions_menu['most_shared_reactions_group']) ? $most_reactions_menu['most_shared_reactions_group'] : '' ;

$total_votes = 0;
 foreach ($reactions as $reaction) {
   if (is_numeric($reaction['vote'])) {
     $total_votes+= $reaction['vote'];
   }
 }
 ?>

 <div class="total-reactions pc">
   <div class="reaction-title">
     <?php
     // Title and description
     $shared_title = (!empty($most_shared_reaction_menu['title_txt'])) ? $most_shared_reaction_menu['title_txt'] : 'TOP #10 MOST NUMBER OF "SHARED REACTIONS"' ;
     $shared_description = (!empty($most_shared_reaction_menu['description'])) ? $most_shared_reaction_menu['description'] : 'The table displays the number of votes for each reaction and automatically ranks them by the total number of votes for each reaction Click on the article title to view the page.' ;
     if ($shared_title): ?>
     <h1 class="reacted-post-title"><?=$shared_title." ( ".$reaction_result." )"?></h1>
   <?php endif;
   if ($shared_description): ?>
   <p class="reacted-post-description"><?=$shared_description;?></p>
 <?php endif; ?>
  </div>
  <div class="total-number">
    <h1><?=$total_votes;?></h1>
  </div>
</div>

 <?php

//Creating panel tab
if (is_array($blog_reaction_repeater)) { ?>
  <div class="reaction-tab">
  <?php $blog_count = 0;
  foreach ($blog_reaction_repeater as $reaction) {

    $reaction_label = $reaction['label_txt'];
    $reaction_labels[] = $reaction['label_txt'];
    reset($reaction_labels);
    if ($reaction_label == $reaction_labels[0]) {
      $class_reaction = 'id="defaultOpen"';
    }else {
      $class_reaction = '';
    }

    if ($reaction_result == $reaction_label) {
      $menu_class = "nav-tab-active";
    } else {
      $menu_class = "";
    }
    $reaction_link = add_query_arg('reactions', $blog_count); ?>
      <a href="<?=$reaction_link?>" class="nav-tab <?=$menu_class;?>" >
        <p><?=$reaction_label;?></p>
      </a>
    <?php $blog_count++;
    } ?>
  </div>
<?php } ?>
 <!-- Displaying the data in table -->
 <div class="total-reactions sp">
   <div class="total-number">
     <h1><?=$total_votes;?></h1>
   </div>
 </div>
 <?php
 if (is_array($blog_reaction_repeater)) {
 foreach ($blog_reaction_repeater as $reaction) {
   $reaction_label = $reaction['label_txt'];
   $reaction_labels[] = $reaction['label_txt'];
   if ($reaction_label == $reaction_labels[0]) {
     $react_style = 'style="display:block; overflow-x : auto;"';
   }else {
     $react_style = '';
   } ?>
   <!-- Most Reacts Table output -->
   <div id="<?=$reaction_label;?>" class="reaction-content" <?=$react_style;?>>
     <table class="reactions-table">
       <tbody>
         <tr>
           <th>Post Title</th>
           <th>Author Name</th>
           <th>Category</th>
           <th>Date</th>
           <th>Total number of shared reactions</th>
         </tr>
         <tr>
           <?php if (is_array($reactions)): ?>
             <?php foreach ($reactions as $reaction) {
               $reactions_id = $reaction['id'];
               $reactions_title = $reaction['title'];
               $reactions_category = $reaction['category'];
               $reactions_date = $reaction['date'];
               $reactions_author = $reaction['author'];
               $reactions_vote = $reaction['vote'];
               if (!empty($reactions_vote)): ?>
               <!-- Output of the table -->
               <tr>
                 <td><?=edit_post_link( $reactions_title, '', '', $reactions_id );?></td>
                 <td><?=$reactions_author;?></td>
                 <td><?=$reactions_category;?></td>
                 <td><?=$reactions_date;?></td>
                 <td><?=$reactions_vote;?></td>
               </tr>
             <?php endif;
             //Result if the reaction still empty
             if (array_values($reactions)[0]['vote'] == '' || array_values($reactions)[0]['vote'] == '0') { ?>
               <tr>
                 <td class="empty-reaction" colspan="5">We can't find any post!</td>
               </tr>
               <?php
               break;
             }
           }
         endif; ?>
       </tr>
     </tbody>
   </table>
 </div>
<?php }
} else {
  ?>
  <div class="reaction-tab">
    <a href="#" class="nav-tab nav-tab-active" >
      <p>TBA</p>
    </a>
  </div>
  <div id="defaultOpen" class="reaction-content" style="display:block; overflow-x : auto;">
    <table class="reactions-table">
      <tbody>
        <tr>
          <th>Post Title</th>
          <th>Author Name</th>
          <th>Category</th>
          <th>Date</th>
          <th>Total number of shared reactions</th>
        </tr>
        <tr>
          <tr>
            <td class="empty-reaction" colspan="5">We can't find any post!</td>
          </tr>
      </tr>
    </tbody>
  </table>
</div>
<?php } ?>
