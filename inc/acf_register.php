<?php
if( function_exists('acf_add_local_field_group') ):

  acf_add_local_field_group(array(
    'key'     => 'group_61a6d4b014364',
    'title'   => 'Reaction images and labels',
    'fields'  => array(
      array(
      'key' => 'field_620de2f444badasdqweadawe',
      'label' => 'Post configuration',
      'name' => 'post_config',
      'type' => 'group',
      'instructions' => 'This setting must be applied first in order for the data to appear on the admin site. <br/> <span style="font-size:17px;">&#9888; </span> <i style="color:red;"> Note: To get the data correct, select the post where you placed the shortcode.</i>',
      'required' => 1,
      'conditional_logic' => 0,
      'wrapper' => array(
        'width' => '',
        'class' => '',
        'id' => '',
      ),
      'layout' => 'table',
      'sub_fields' => array(
        array(
          'key' => 'field_6540a07136a7f',
          'label' => 'Post Type',
          'name' => 'post_type_slct',
          'type' => 'select',
          'instructions' => 'Select the post type to be recorded in the graph.',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'choices' => array(
            'post' => 'Default Post',
          ),
          'default_value' => 'post',
          'allow_null' => 0,
          'multiple' => 0,
          'ui' => 0,
          'return_format' => 'value',
          'ajax' => 0,
          'placeholder' => '',
        ),
        array(
        'key'               => 'field_6215d4cac10d7',
        'label'             => 'Taxonomy',
        'name'              => 'tax_txt',
        'type'              => 'text',
        'instructions'      => 'Manually insert the slug of the taxonomy.',
        'required'          => 1,
        'conditional_logic' => 0,
        'wrapper'           => array(
          'width' => '',
          'class' => '',
          'id'    => '',
          ),
        'default_value' => 'uncategorized',
        'placeholder'   => '',
        'prepend'       => '',
        'append'        => '',
        'maxlength'     => '',
        ),
      ),
    ),
      array(
        'key'               => 'field_61a6d4b5be697',
        'label'             => '',
        'name'              => 'reaction_image_and_label_rptr',
        'type'              => 'repeater',
        'instructions'      => '<span style="font-size:17px;">&#9888; </span><i style="color:red;"> Note: Deleting a reaction erases the data in the database and cannot be restored.</i>',
        'required'          => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id'    => '',
        ),
        'collapsed'     => '',
        'min'           => 1,
        'max'           => 6,
        'layout'        => 'table',
        'button_label'  => '',
        'sub_fields'    => array(
          array(
            'key'               => 'field_61a6d505be69a',
            'label'             => 'ID',
            'name'              => 'reaction_id',
            'type'              => 'unique_id',
            'instructions'      => 'Reaction ID',
            'required'          => 0,
            'conditional_logic' => 0,
            'wrapper'           => array(
              'width' => '',
              'class' => '',
              'id'    => '',
            ),
          ),
          array(
          'key'               => 'field_61a6d4ccbe698',
          'label'             => 'Icon(s)',
          'name'              => 'icon_img',
          'type'              => 'text',
          'instructions'      => 'Insert reactions label <br/> <span style="color:red;">FYI </span> : Get the Emoji here <a href="https://www.piliapp.com/facebook-symbols/" target="_blank">Link</a> and 1 EMOJI / Reaction per row. <br/> <span style="color:red;">How to copy the emoji ? </span> <br/> - Click on the EMOJI / Reaction of your choice, then click Copy next to the text field at the top.',
          'required'          => 1,
          'conditional_logic' => 0,
          'wrapper'           => array(
            'width' => '',
            'class' => '',
            'id'    => '',
            ),
          'default_value' => '',
          'placeholder'   => '',
          'prepend'       => '',
          'append'        => '',
          'maxlength'     => '',
          ),

            array(
            'key'               => 'field_61a6d4e8be699',
            'label'             => 'Label',
            'name'              => 'label_txt',
            'type'              => 'text',
            'instructions'      => 'Insert reactions label',
            'required'          => 1,
            'conditional_logic' => 0,
            'wrapper'           => array(
            'width' => '',
            'class' => '',
            'id'    => '',
            ),
          'default_value' => '',
          'placeholder'   => '',
          'prepend'       => '',
          'append'        => '',
          'maxlength'     => '',
          ),
        ),
      ),
      array(
			'key' => 'field_620de2f444bad',
			'label' => 'Dashboard graph color',
			'name' => 'graph_color',
			'type' => 'group',
			'instructions' => '<span style="font-size:17px;">&#9888; </span><i style="color:red;"> Note: The number of colors should be the same as the number of reactions. </i>',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'table',
			'sub_fields' => array(
				array(
					'key' => 'field_620de28644bab',
					'label' => 'Most voted reaction posts',
					'name' => 'most_reaction_post',
					'type' => 'repeater',
					'instructions' => 'Select a color for the graph',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 1,
					'max' => 6,
					'layout' => 'table',
					'button_label' => '',
					'sub_fields' => array(
						array(
							'key' => 'field_620de2c844bac',
							'label' => 'Color',
							'name' => 'colors',
							'type' => 'color_picker',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'enable_opacity' => 0,
							'return_format' => 'string',
						),
					),
				),
				array(
					'key' => 'field_620de31444bae',
					'label' => 'Total number of votes for each reaction',
					'name' => 'most_shared_reaction_grp',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_6215ccadfec64',
							'label' => 'Graph Type',
							'name' => 'graph_type_slct',
							'type' => 'select',
							'instructions' => 'Select a graph type',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'choices' => array(
								'bar' => 'Bar',
								'doughnut' => 'Doughnut',
								'line' => 'line',
                'polarArea' => 'Polar Area',
								'pie' => 'Pie',
								'radar' => 'Radar',
							),
							'default_value' => false,
							'allow_null' => 0,
							'multiple' => 0,
							'ui' => 0,
							'return_format' => 'value',
							'ajax' => 0,
							'placeholder' => '',
						),
						array(
							'key' => 'field_6215cd64fec65',
							'label' => '',
							'name' => 'most_shared_reaction_clr',
							'type' => 'color_picker',
							'instructions' => 'Select a color for the graph',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'field_6215ccadfec64',
										'operator' => '==',
										'value' => 'bar',
									),
								),
								array(
									array(
										'field' => 'field_6215ccadfec64',
										'operator' => '==',
										'value' => 'line',
									),
								),
								array(
									array(
										'field' => 'field_6215ccadfec64',
										'operator' => '==',
										'value' => 'radar',
									),
								),
							),
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'enable_opacity' => 0,
							'return_format' => 'string',
						),
						array(
							'key' => 'field_6215d4cac10d6',
							'label' => 'For your reference',
							'name' => '',
							'type' => 'message',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => array(
								array(
									array(
										'field' => 'field_6215ccadfec64',
										'operator' => '==',
										'value' => 'doughnut',
									),
								),
								array(
									array(
										'field' => 'field_6215ccadfec64',
										'operator' => '==',
										'value' => 'polarArea',
									),
								),
								array(
									array(
										'field' => 'field_6215ccadfec64',
										'operator' => '==',
										'value' => 'pie',
									),
								),
							),
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'message' => 'The color of this graph will be the same as the color of the "most voted reactions."',
							'new_lines' => 'wpautop',
							'esc_html' => 0,
						),
					),
				),
			),
		),
     array(
			'key' => 'field_620ddf19809a6',
			'label' => 'TMJ Blog Reaction Dashboard',
			'name' => 'blog_reaction_content',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'table',
			'sub_fields' => array(
				array(
					'key' => 'field_620ddf49809a7',
					'label' => 'TMJ Blog Dashboard Label',
					'name' => 'tmj_blog_dashboard_label',
					'type' => 'text',
					'instructions' => 'Insert a label for the dashboard',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => 'TMJ Blog Reaction Dashboard',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_62158aae54082',
					'label' => 'Most Voted Reaction Articles',
					'name' => 'most_reacted_post_grp',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_62158ae054083',
							'label' => 'Title',
							'name' => 'title_txt',
							'type' => 'text',
							'instructions' => 'Insert the title of the most voted reaction article',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'Most voted reaction posts ( Ranked in the top 5 )',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_62158b0a54084',
							'label' => 'Description',
							'name' => 'description_txt',
							'type' => 'text',
							'instructions' => 'Insert description of most voted reaction article.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'This ranking shows the articles with the most votes out of the total number of reactions.',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
					),
				),
				array(
					'key' => 'field_62158b2554085',
					'label' => 'Most Shared reactions',
					'name' => 'most_shared_reaction_grp',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_62158b3c54086',
							'label' => 'Title',
							'name' => 'title_txt',
							'type' => 'text',
							'instructions' => 'Insert the title of the most "shared response".',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'Total number of votes for each reaction',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_62158b4254087',
							'label' => 'Description',
							'name' => 'description_txt',
							'type' => 'text',
							'instructions' => 'Insert the most shared reaction description',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'This ranking shows the most used reactions on all pages.',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
					),
				),
			),
		),
    array(
			'key' => 'field_62159689f00e3',
			'label' => 'Most Shared Reactions and Reacted to Post Menu',
			'name' => 'most_reaction_menu',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'table',
			'sub_fields' => array(
				array(
					'key' => 'field_62159731f00e4',
					'label' => 'Most Shared Reaction Menu',
					'name' => 'most_shared_reactions_group',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_62159794f00e5',
							'label' => 'Title',
							'name' => 'title_txt',
							'type' => 'text',
							'instructions' => 'Insert the title of the most "shared response".',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'TOP #10 Most number of "Shared Reactions"',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_621597a2f00e6',
							'label' => 'Description',
							'name' => 'description',
							'type' => 'text',
							'instructions' => 'Insert the most "share reaction" description',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'The table displays the number of votes for each reaction and automatically ranks them by the total number of votes for each reaction Click on the article title to view the page.',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
					),
				),
				array(
					'key' => 'field_621597aef00e7',
					'label' => 'Most Voted Reaction Articles Group',
					'name' => 'most_reacted_post_group',
					'type' => 'group',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_621597aef00e8',
							'label' => 'Title',
							'name' => 'title_txt',
							'type' => 'text',
							'instructions' => 'Insert the title of the most voted reaction article.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'Top 20 most voted reaction articles.',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
						array(
							'key' => 'field_621597aef00e9',
							'label' => '説明',
							'name' => 'description',
							'type' => 'text',
							'instructions' => 'Insert description of most voted reaction article.',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => 'It represents the total number of reactions to each page and automatically ranks them. Clicking on an article title will display that page.',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
						),
					),
				),
			),
		),
    ),
    'location' => array(
      array(
        array(
          'param'    => 'options_page',
          'operator' => '==',
          'value'    => 'react-icon-and-label',
        ),
      ),
    ),
    'menu_order'            => 0,
    'position'              => 'normal',
    'style'                 => 'default',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen'        => '',
    'active'                => true,
    'description'           => '',
  ));
endif;
?>
