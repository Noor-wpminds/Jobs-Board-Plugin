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
 //require_once 'single-ams_jobs.php';
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
     plugins_url( 'Jobs-Board-Plugin/css/ams-style.css' ),
     array(),
     filemtime( dirname( __FILE__ ) . '/css/ams-style.css' ) );
  wp_enqueue_style( 'ams-plugin' );
   //wp_enqueue_script( 'jquery');
}
// Register style sheet.
add_action( 'wp_enqueue_scripts', 'ams_register_plugin_styles' );
add_action( 'admin_enqueue_scripts', 'my_script_enqueuer' );
function my_script_enqueuer() {
   wp_register_script( "ams_plugin_script", WP_PLUGIN_URL.'/Jobs-Board-Plugin/js/settings-script.js', array('jquery'),'1.0.54' );
   wp_enqueue_script( 'jquery' );
   wp_enqueue_script( 'ams_plugin_script' );
   wp_localize_script('ams_plugin_script', 'myAjax', array(
      'ajaxurl' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('ajax-nonce')
   ));     
}
//Override single template
add_filter( 'single_template', 'ams_single_template' );
function ams_single_template( $single_template ){
  global $post;
  If ( is_single() && get_post_type( $post ) == 'ams_jobs'){
     $file = dirname(__FILE__) .'/single-'. $post->post_type .'.php';
     if( file_exists( $file ) ) $single_template = $file;
     return $single_template; 
  }
}
// Ajax verification reCaptcha
function amb_recaptcha_ajax_auth_init(){ 
   wp_enqueue_script( 'amb_google-reCaptcha', 'https://www.google.com/recaptcha/api.js','','','in_footer' ); 
   wp_register_script('amb_recaptcha_ajax-script', WP_PLUGIN_URL.'/Jobs-Board-Plugin/js/ajax-recaptcha-script.js', array('jquery'),'1.0.54','in_footer' ); 
   wp_enqueue_script('amb_recaptcha_ajax-script');
   wp_localize_script( 'amb_recaptcha_ajax-script', 'ajax_auth_object', array( 
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
 ));
    // Enable the user with privileges and no privileges to run  in AJAX
   add_action( 'wp_ajax_nopriv_amb_recaptcha_ajax', 'amb_recaptcha_ajax_fun' );
   add_action( 'wp_ajax_amb_recaptcha_ajax', 'amb_recaptcha_ajax_fun' );
}
add_action('init', 'amb_recaptcha_ajax_auth_init');
// Ajax call
function amb_recaptcha_ajax_fun(){
    // First check the nonce, if it fails the function will break
  wp_verify_nonce( $_POST['security'],  'ajax-recaptcha-nonce');
   // Check if reCaptcha is valid
  $recaptcha=$_POST['recaptcha'];
  if(!empty($recaptcha))
  {
   $options = get_option( 'ams_general_settings_options' );
   $secret_key = $options['secret_key'];
   $google_url = "https://www.google.com/recaptcha/api/siteverify";
      $secret = $secret_key; // Replace your Google Secret Key here
      $ip = $_SERVER['REMOTE_ADDR'];
      $url = $google_url."?secret=".$secret."&response=".$recaptcha."&remoteip=".$ip;
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_TIMEOUT, 10);
      curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
      $results = curl_exec($curl);
      curl_close($curl);
      $res= json_decode($results, true);
      if(!$res['success'])
      {
         echo json_encode(array('status'=>__('failure'),'message'=>__('reCAPTCHA invalid')));
         die();
      } else{
         echo json_encode(array('status'=>__('success'),'message'=>__('reCAPTCHA valid')));
         die();
      }
   }
   else
   {
      echo json_encode(array('message'=>__('Please enter reCAPTCHA')));
      die();
   }
}