<?php

add_action( 'init', 'ams_create_taxonomies', 0 );

//create taxonomy for job categories
function ams_create_taxonomies() 
{
  // Add new taxonomy
  $labels = array(
    'name' => _x( 'Job Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Job Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Category' ),
    'popular_items' => __( 'Popular Categories' ),
    'all_items' => __( 'All Categories' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Category' ), 
    'update_item' => __( 'Update Category' ),
    'add_new_item' => __( 'Add New Category' ),
    'new_item_name' => __( 'New Category Name' ),
    'separate_items_with_commas' => __( 'Separate categories with commas' ),
    'add_or_remove_items' => __( 'Add or remove categories' ),
    'choose_from_most_used' => __( 'Choose from the most used categories' ),
    'menu_name' => __( 'Job Category' ),
  ); 
  
  $labels2 = array(
    'name' => _x( 'Application Status', 'taxonomy general name' ),
    'singular_name' => _x( 'Application Status', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Status' ),
    'popular_items' => __( 'Popular Statuses' ),
    'all_items' => __( 'All Statuses' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Status' ), 
    'update_item' => __( 'Update Status' ),
    'add_new_item' => __( 'Add New Status' ),
    'new_item_name' => __( 'New Status Name' ),
    'separate_items_with_commas' => __( 'Separate Statuses with commas' ),
    'add_or_remove_items' => __( 'Add or remove Statuses' ),
    'choose_from_most_used' => __( 'Choose from the most used Statuses' ),
    'menu_name' => __( 'Application Status' ),
  ); 

  register_taxonomy('ams_job_category','ams_jobs',array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'job_category' ),
	'show_in_rest' => true,
  ));

  register_taxonomy('ams_aplication_status','ams_job_application',array(
    'hierarchical' => true,
    'labels' => $labels2,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'ams_aplication_status' ),
	'show_in_rest' => true,
  ));
} 