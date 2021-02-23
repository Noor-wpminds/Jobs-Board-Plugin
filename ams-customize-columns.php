<?php


add_filter( 'manage_ams_job_application_posts_columns', 'ams_filter_posts_columns' );
function ams_filter_posts_columns( $columns ) {
  $columns['position'] = __( 'Position', 'ams-joabs-board' );
  $columns['status'] = __( 'Status', 'ams-jobs-board' );
  
  $columns = array(
      'cb' => $columns['cb'],
      'title' => __( 'Title' ),
      'position' => __( 'Position', 'ams-joabs-board' ),
      'status' => __( 'Status', 'ams-jobs-board' ),
	  'date' => __( 'Date' ),
    );
  
  return $columns;
}


add_action( 'manage_ams_job_application_posts_custom_column', 'ams_job_application_column', 10, 2);
function ams_job_application_column( $column, $post_id ) {
  // Position applied for column
  if ( 'position' === $column ) {
    $position = get_post_meta( $post_id, 'position', true );

    if ( ! $position ) {
      _e( 'n/a' );  
    } else {
      echo '<a href="' . get_edit_post_link($position) . '" title="' . get_the_title($position) . '">' . get_the_title($position) . '</a>';
    }
  }
  
   // Application Status column
  if ( 'status' === $column ) {
    $area = get_post_meta( $post_id, 'area', true );
	$terms = get_the_terms( $post_id, 'ams_aplication_status' );

    if ( $terms && ! is_wp_error( $terms ) ) { 
		foreach ( $terms as $term ) {
        echo $term->name;
		} }
  }
}
  