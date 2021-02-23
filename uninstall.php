<?php


if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// delete custom post type posts
$ams_cpt_args = array('post_type' => 'ams_jobs', 'posts_per_page' => -1);
$ams_cpt_posts = get_posts($ams_cpt_args);
foreach ($ams_cpt_posts as $post) {
	// delete post meta (custom taxonomy)
	delete_post_meta($post->ID, 'ams_post_meta');
	wp_delete_post($post->ID, false);
}