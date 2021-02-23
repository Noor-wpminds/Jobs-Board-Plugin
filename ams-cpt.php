<?php

 // Our custom post type function
function ams_create_posttype() {
 
    register_post_type( 'ams_jobs',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Jobs' ),
                'singular_name' => __( 'Job' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'ams_jobs'),
            'show_in_rest' => true,
			'taxonomies'  => array( 'ams_job_category' ),
			'supports' => array( 'thumbnail', 'title', 'editor', 'comments', 'revisions', 'trackbacks', 'author', 'excerpt', 'page-attributes' ),
			'register_meta_box_cb' => 'ams_add_job_metaboxes',
			'menu_icon' => 'dashicons-businessman',

 
        )
    );
	
	register_post_type( 'ams_job_application',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Applications' ),
                'singular_name' => __( 'Application' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'ams_job_application'),
            'show_in_rest' => true,
			'taxonomies'  => array( 'ams_aplication_status' ),
			'supports' => array( 'thumbnail', 'title', 'author', 'page-attributes' ),
			'register_meta_box_cb' => 'ams_add_application_metaboxes',
			'menu_icon' => 'dashicons-media-document',

 
        )
    );
}

// Hooking up our function to theme setup
add_action( 'init', 'ams_create_posttype' );

/**
 * Activate the plugin.
 */
 function ams_activate() { 
    // Trigger our function that registers the custom post type plugin.
    ams_create_posttype(); 
    // Clear the permalinks after the post type has been registered.
    flush_rewrite_rules(); 
}

/**
 * Deactivation hook.
 */
function ams_deactivate() {
    // Unregister the post type, so the rules are no longer in memory.
    unregister_post_type( 'ams_jobs' );
    unregister_post_type( 'ams_job_application' );
    // Clear the permalinks to remove our post type's rules from the database.
    flush_rewrite_rules();
}