<?php


/**
 * Plugin Name: AMS Jobs Board
 * Description: Custom Post Type for building a job board
 * Author: AMS
 * Text Domain: ams-jobs-board
 */
 
 require_once 'ams-cpt.php';
 require_once 'ams-taxonomies.php';
 require_once 'ams-metaboxes.php';
 require_once 'ams-job-search.php';
 require_once 'single-ams_jobs.php';
 require_once 'ams-customize-columns.php';
 //require_once 'classes/CreateCSV.php';
 require_once 'ams-import-export.php';
 require_once 'ams-settings-page.php';
 require_once 'ams-nonce-example.php';

 
 register_activation_hook( __FILE__, 'ams_activate' );
 register_deactivation_hook( __FILE__, 'ams_deactivate' );
 
 /**
 * Registers a stylesheet.
 */
function ams_register_plugin_styles() {
    wp_register_style( 'ams-plugin', 
	plugins_url( 'ams-jobs-board/css/ams-style.css' ),
	array(),
	filemtime( dirname( __FILE__ ) . '/css/ams-style.css' ) );
    wp_enqueue_style( 'ams-plugin' );
}
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'ams_register_plugin_styles' );

add_action( 'admin_enqueue_scripts', 'my_script_enqueuer' );

function my_script_enqueuer() {
   wp_register_script( "ams_plugin_script", WP_PLUGIN_URL.'/ams-jobs-board/js/settings-script.js', array('jquery'),'1.0.54' );

   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'ams_plugin_script' );
      wp_localize_script('ams_plugin_script', 'myAjax', array(
               'ajaxurl' => admin_url('admin-ajax.php'),
               'nonce' => wp_create_nonce('ajax-nonce')
           ));     


}