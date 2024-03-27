<!-- Dashboard Titlte -->
<?php
  $dashboard_group = get_field('blog_reaction_content','option');

  $most_reacted_post_group = (!empty($dashboard_group)) ? $dashboard_group['most_reacted_post_grp'] : '';
  $most_shared_reaction_group = (!empty($dashboard_group)) ? $dashboard_group['most_shared_reaction_grp'] : '';
  $dashboard_label = (!empty($dashboard_group)) ? $dashboard_group['tmj_blog_dashboard_label'] : 'TMJ BLOG REACTION DASHBOARD';
  ?>
  <h1 class="reacted-post-title"><?=$dashboard_label?></h1>

<?php
//Function for total reacts each reactions
$blog_reaction_repeater = get_field('reaction_image_and_label_rptr', 'option');
function total_reactions($reaction_id_list){
  $reaction_post_type_settings = get_field('post_config', 'option');
  $reaction_post_type = (!empty($reaction_post_type_settings['post_type_slct'])) ? $reaction_post_type_settings['post_type_slct'] : 'post';
  $reaction_argument = array(
    'post_type'      => $reaction_post_type,
    'post_status'    => 'publish',
    'posts_per_page' => '-1',
  );
  $reaction_query = new WP_Query( $reaction_argument );
  if ( $reaction_query->have_posts() ) :
    $total_reacted_count = 0;
    while ( $reaction_query->have_posts() ) : $reaction_query->the_post();
    if (isset(get_post_meta(get_the_ID(),'blog_reaction_'.$reaction_id_list)[0])) {
      $total_reacted_count+= get_post_meta(get_the_ID(),'blog_reaction_'.$reaction_id_list)[0];
    }
  endwhile;
 endif;

 return $total_reacted_count;
}
?>

<?php
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
?>

<?php
//Creating data of array and get only the top 5
$reaction_post_type_settings = get_field('post_config', 'option');
$reaction_post_type = (!empty($reaction_post_type_settings['post_type_slct'])) ? $reaction_post_type_settings['post_type_slct'] : 'post';
$reaction_argument = array(
'post_type'      => $reaction_post_type,
'post_status'    => 'publish',
'posts_per_page' => '-1',
);
$reaction_query = new WP_Query( $reaction_argument );
if ( $reaction_query->have_posts() ) :
  while ( $reaction_query->have_posts() ) : $reaction_query->the_post();
  $data[most_reacted_reaction(get_the_ID())."-".get_the_ID()] = array(
    'id'     => get_the_ID(),
    'title'  => get_the_title(),
    'link'   => get_the_permalink(),
    'vote'   => most_reacted_reaction(get_the_ID()),
  );
  endwhile;
 wp_reset_postdata();
endif;
//Sorting and Display only 20 in array
krsort($data, SORT_NUMERIC);
$reactions = array_slice($data,0,5);


$graph_colors = get_field('graph_color', 'option');
$most_reacted_graph = (!empty($graph_colors)) ? $graph_colors['most_reaction_post'] : '' ;

if (is_array($most_reacted_graph) && is_array($blog_reaction_repeater) && count($most_reacted_graph) >= count($blog_reaction_repeater)) {
  foreach ($most_reacted_graph as $colors) {
    $color_list[] = $color_list_graph[] = $colors['colors'];
  }
} else {
  $color_list = $color_list_graph = '';
}
 ?>

<!-- Most Reacted Post Output -->
<div class="most-react-dashboard row">
  <div class="most-reacted-post column">
    <?php
      $dashboard_reacted_title = !empty($most_reacted_post_group['title_txt']) ? $most_reacted_post_group['title_txt'] : 'Most voted reaction posts ( Ranked in the top 5 )' ;
      $dashboard_reacted_description = !empty($most_reacted_post_group['description_txt']) ? $most_reacted_post_group['description_txt'] : 'This ranking shows the articles with the most votes out of the total number of reactions.' ;
     ?>
    <?php if ($dashboard_reacted_title): ?>
      <h2><?=$dashboard_reacted_title;?></h2>
    <?php endif; ?>
    <?php if ($dashboard_reacted_description): ?>
      <p><?=$dashboard_reacted_description;?></p>
    <?php endif; ?>
    <?php if (is_array($reactions)) { ?>
      <div class="most-reacted-post-graph">
        <?php
        $react_rank = 0;
        foreach ($reactions as $reaction) {
          $react_rank++;
          $reactions_title = $reaction['title'];
          $reactions_link = $reaction['link'];
          $reactions_vote = $reaction['vote'];
          $reaction_id = $reaction['id'];
          if ($reactions_vote != 0) { ?>
            <!-- Most reacted post ranking (Post title and link)-->
            <div class="reacted-data">
              <a href="<?=$reactions_link;?>">
                <b><?=$react_rank;?>.</b>
                <span><?=$reactions_title;?></span>
              </a>
            </div>
            <!-- Reactions vote graph -->
            <div class="reactions-graph">
              <div data-stepped-bar = '{
                "catagories": [
                <?php $color_count = 0;
                foreach ($blog_reaction_repeater as $most_reactions) {
                  $reactions_label = $most_reactions['label_txt'];
                  $reaction_id_list = $most_reactions['reaction_id'];
                  //List of color for the graph
                  if (is_array($color_list)) {
                    $graph_color = $color_list;
                  }else {
                    $graph_color = array('#3d5a80', '#e07a5f', '#3d405b', '#81b29a', '#f2cc8f', '#ee6c4d' );
                  }

                  if (isset(get_post_meta($reaction_id,'blog_reaction_'.$reaction_id_list)[0])) {
                    $reaction_vote = get_post_meta($reaction_id,'blog_reaction_'.$reaction_id_list)[0];
                  } else {
                    $reaction_vote = 0;
                  }
                  $data_comma = ($color_count == count($blog_reaction_repeater)-1) ? '' : ',';
                  ?>
                  { "name": "<?=$reactions_label;?>", "value": <?=$reaction_vote;?>, "color": "<?=$graph_color[$color_count];?>" } <?=$data_comma;?>
                  <?php
                  $color_count++;
                }
                ?>
                ]
              }'>
            </div>
          </div>
        <?php } else {
          if (array_values($reactions)[0]['vote'] == '0') {
            //Empty Post
            ?>
            <h3>We can't find any post!</h3>
            <?php
            break;
          }
        }
      }?>
    </div>
    <a href="<?=home_url('mfsxi/wp-admin/admin.php?page=most-clicked-stat-menu');?>" class="button button-primary">See more</a>
  <?php } ?>
</div>

<?php
  $most_shared_reaction_graph = (!empty($graph_colors['most_shared_reaction_grp'])) ? $graph_colors['most_shared_reaction_grp'] : '' ;
  $graph_type = (!empty($most_shared_reaction_graph['graph_type_slct'])) ? $most_shared_reaction_graph['graph_type_slct'] : '' ;
  if ($graph_type == 'bar' || $graph_type == 'line' || $graph_type == 'radar') {
    if (!empty($most_shared_reaction_graph['most_shared_reaction_clr'])) {
      $graph_color = hex2rgb($most_shared_reaction_graph['most_shared_reaction_clr']);
      $background_color =  '"rgb('.$graph_color['0'].", ".$graph_color['1'].", ".$graph_color['2'].')"';
    } else {
      $background_color =  '"rgb(76, 175, 147)"';
    }
  } else {
    $background_color = "[";
    $bg_count = 0;
    if (is_array($color_list_graph)) {
    foreach ($color_list_graph as $color_graph) {
      $graph_color_list = hex2rgb($color_graph);
      $data_comma = ($bg_count == count($color_list_graph)-1) ? '' : ',';
     $background_color.= '"rgb('.$graph_color_list['0'].", ".$graph_color_list['1'].", ".$graph_color_list['2'].')"'.$data_comma.'';
     $bg_count++;
    }
    $background_color.= "]";
  } else {
    $background_color =  "[
      'rgb(61, 90, 128)',
      'rgb(224, 122, 95)',
      'rgb(61, 64, 91)',
      'rgb(129, 178, 154)',
      'rgb(242, 204, 143)',
      'rgb(238, 108, 77)'
      ]";
    }
  }
?>
<!-- Most Share Reactions output -->
<div class="most-share-reactions column">
  <?php
  $dashboard_shared_title = (!empty($most_shared_reaction_group['title_txt'])) ? $most_shared_reaction_group['title_txt'] : 'Total number of votes for each reaction' ;
  $dashboard_shared_description = (!empty($most_shared_reaction_group['description_txt'])) ? $most_shared_reaction_group['description_txt'] : 'This ranking shows the most used reactions on all pages.' ;
  if ($dashboard_shared_title): ?>
    <h2><?=$dashboard_shared_title;?></h2>
  <?php endif; ?>
  <?php if ($dashboard_shared_description): ?>
    <p><?=$dashboard_shared_description;?></p>
  <?php endif; ?>
  <!-- For the displaying of graph -->
  <canvas id="reaction-chart"></canvas>
  <a href="<?=home_url('mfsxi/wp-admin/admin.php?page=button-clicked-stat-menu');?>" class="button button-primary">See more</a>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
  jQuery(document).ready( function() {
    var ctx = document.getElementById('reaction-chart').getContext('2d');
    var chart = new Chart(ctx, {
      type: '<?=$graph_type;?>',
      data: {
        labels: [
        <?php
        if (is_array($blog_reaction_repeater)) {
          foreach ($blog_reaction_repeater as $reactionss) {
            $reaction_labels = $reactionss['label_txt'];
            echo "'".$reaction_labels."',";
          }
        } else {
          echo "'TBA',";
        }
        ?>
        ],
        datasets: [{
          label: 'Reaction(s)',
          backgroundColor: <?=$background_color;?>,
          data: [
          <?php
          if (is_array($blog_reaction_repeater)) {
            foreach ($blog_reaction_repeater as $reaction) {
              $reaction_id_list = $reaction['reaction_id'];
              echo total_reactions($reaction_id_list).',';
            }
          } else {
            echo "0".',';
          }
          ?>
          ]
        }]
      }
    })
  });
</script>
