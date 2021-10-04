<?php

function ams_setup() {
    add_shortcode( 'ams_job_listing', 'ams_search_page' );
}
add_action( 'init', 'ams_setup' );


function ams_search_page( $args ){
	
$output = ams_search_form();

$ams_query_args = ams_set_query_args();

$output .= '<h1 class="entry-title" itemprop="headline">Available Jobs</h1>
<div class = "ams-jobs-archive">';
	
$ams_jobs = new WP_Query($ams_query_args);

// The Loop
if ( $ams_jobs->have_posts() ) {
    while ( $ams_jobs->have_posts() ) {
        $ams_jobs->the_post();
        $city_archive = get_post_meta( get_the_ID(), 'location', true );
        $terms = get_the_terms( get_the_ID(), 'ams_job_category' );
		//$jobcategory = array();
 
		$feat_image = get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
		$output .= '<div class = "ams-archive-post">
		<div class = "ams-feat-image">';
		$output .= $feat_image;
		$output .= '</div>' . "\n";
		$output .= '<div class="jobs-summary">'."\n";
		$output .= '<br><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a><br>';
		$output .= '<span class = "ams-post-meta">-' . $city_archive . '</span>';
		if ( $terms && ! is_wp_error( $terms ) ) { 
		foreach ( $terms as $term ) {
        $output .= '<span class = "ams-post-meta">-' . $term->name . '</span>';
		} }
		//$output .= $category;
		$output .= '<br></div></div>' . "\n";
    }
}
 else{
       $output .= 'No Jobs matching your criteria!';
}

$output .= '</div>'; 

return $output;


}

function ams_set_query_args() {
$location = "";
$job_category = "";
$salary = "";

$location = isset($_GET["location"]) ? esc_textarea($_GET["location"]) : '';
$job_category = isset($_GET["job_category"]) ? esc_textarea($_GET["job_category"]) : '';
$salary = isset($_GET["salary"]) ? esc_textarea($_GET["salary"]) : '';
	
	$jobs_args = array( 
		'post_type' => 'ams_jobs',
		'posts_per_page' => '10'
	);
	if(!empty($location)){
		$jobs_args['meta_query'] = array(
			array(
				'key' => 'location',
				'value' => $location,
				'compare' => 'LIKE'
			)
		);
	}
	
	if(!empty($salary)){
		$jobs_args['meta_query'][] = array(
				'key' => 'salary',
				'value' => $salary,
				'compare' => '<='
			);
	}
	if(!empty($salary) && !empty($location) ){
		$jobs_args['meta_query']['relation'] = 'AND';
	}
	
	if(!empty($job_category)) {
		$jobs_args['tax_query'] = array(
			array(
			'taxonomy' => 'ams_job_category',
            'field'    => 'slug',
            'terms'    => $job_category
			)
		);
	}
	
	return $jobs_args;
	
	//print_r($jobs_args); 
}

function ams_search_form() {
	$ams_query = new WP_Query( array( 'post_type' => 'ams_jobs', 'posts_per_page' => '-1') );

// The Loop
if ( $ams_query->have_posts() ) {
    $cities = array();
    while ( $ams_query->have_posts() ) {
        $ams_query->the_post();
        $city = get_post_meta( get_the_ID(), 'location', true );

        // populate an array of all occurrences (non duplicated)
        if( !in_array( $city, $cities ) ){
            $cities[] = $city;    
        }
    }
}
 else{
       $print_form = 'No Jobs yet!';
}

$args = array( 'hide_empty' => false );
$job_categories = get_terms( 'ams_job_category', $args );

$print_form = 
'<div class="ams-search-form">
<form action="" method="GET" role="search">
<div class="amsselectbox">
<select name="location" style="width: 100%">
<option value="" selected="selected">Select City</option>';
foreach ($cities as $city ) {
    $print_form .= '<option value="' . $city . '">' . $city . '</option>';
}
$print_form .= '</select></div>' . "\n";

reset($cities);

if( is_array( $job_categories ) ){
    $print_form .= '<div class="amsselectbox">
	<select name="job_category" style="width: 100%">
	<option value="" selected="selected">Select Category</option>';
    foreach ( $job_categories as $term ) {
        $print_form .= '<option value="' . $term->slug . '">' . $term->name . '</option>';
    }
    $print_form .= '</select></div>' . "\n";
}

$print_form .= '<div class="amssalaryrange">
<br><label for="salary"><b>Select Salary Range</b></label>
<input type="range" id="salary" name="salary" min="5000" max="500000" value="500000" class="widefat">
</div>' . "\n";
$print_form .= '<p><input type="submit" value="Go!" class="button" /></p></form><br><br>
</div>' . "\n";

return $print_form;

}