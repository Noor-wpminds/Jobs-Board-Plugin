<?php

// Register our uninstall function
register_uninstall_hook( __FILE__, 'ams_plugin_uninstall' );

// Deregister our settings group and delete all options
function ams_plugin_uninstall() {

// Clean de-registration of registered setting
unregister_setting( 'ams_plugin_options', 'ams_plugin_options' );

// Remove saved options from the database
delete_option( 'ams_plugin_options' );

}

// Add a menu for our option page
add_action( 'admin_menu', 'ams_plugin_add_settings_menu' );

function ams_plugin_add_settings_menu() {

add_submenu_page( 'edit.php?post_type=ams_jobs', 'AMS Settings', 'AMS Settings', 
'manage_options', 'ams-plugin', 'ams_plugin_option_page' );

}
      
// Create the option page
function ams_plugin_option_page() {

if ( ! current_user_can( 'manage_options' ) ) {
  return;
}

  ?>
        
        <div class="wrap">
        
            <!-- Create a header in the default WordPress 'wrap' container -->
            <div id="icon-themes" class="icon32"></div>
            <h2>AMS Settings</h2>
        
            <?php settings_errors(); ?>
        
            <?php
            if ( isset( $_GET ) ) {
                $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'export_file';
            } 
            ?>
        
            <h2 class="nav-tab-wrapper" id="tabs-container">
              <ul class="tabs-menu">
                <li><a href="?page=ams-plugin&tab=general_settings&post_type=ams_jobs" id= "general_settings" class="nav-tab <?php echo $active_tab == 'general_settings' ? 'nav-tab-active' : ''; ?>">General</a></li>
                <li><a href="?page=ams-plugin&tab=export_file&post_type=ams_jobs" id="export_file" class="nav-tab <?php echo $active_tab == 'export_file' ? 'nav-tab-active' : ''; ?>">Import/Export</a></li>
              </ul>
            </h2>
        
        
            <form method="post" action="options.php">
        
            <?php
            if ( $active_tab == 'general_settings' ) {
                // settings options group name passed
                settings_fields( 'ams_general_settings_options_group' );
                // settings options name passed
                do_settings_sections( 'ams_general_settings_options' );
                submit_button(); 
            } elseif ( $active_tab == 'export_file' ) {
                // settings options group name passed
                settings_fields( 'ams_export_file_options_group' );
                // settings options name passed
                do_settings_sections( 'ams_export_file_options' );
            }
            
            ?>
            </form>
        </div><!-- /.wrap -->
    
        <?php
}

// Register and define the settings
add_action('admin_init', 'ams_plugin_general_init');
// Register and define the settings
add_action('admin_init', 'ams_plugin_export_init');

function ams_plugin_general_init(){

// Define the setting args
$args = array(
    'type'        => 'string', 
    'sanitize_callback' => 'ams_plugin_validate_options',
    'default'       => NULL
);

  // Register our settings
  register_setting( 'ams_general_settings_options_group', 'ams_general_settings_options', $args );
  
  // Add a settings section
  add_settings_section( 
    'ams_plugin_main', 
    'AMS Plugin Settings',
      'ams_plugin_section_text', 
      'ams_general_settings_options' 
  );
  
  // Create our settings field for name
  add_settings_field( 
    'ams_plugin_name', 
    'Your Name',
      'ams_plugin_setting_name', 
      'ams_general_settings_options', 
      'ams_plugin_main' 
  );

  
  // Create our settings field for favorite holiday
  add_settings_field( 
    'ams_plugin_fav_holiday', 
    'Favorite Holiday',
      'ams_plugin_setting_fav_holiday', 
      'ams_general_settings_options', 
      'ams_plugin_main' 
  );

  // Create our settings field for beast mode
  add_settings_field( 
    'ams_plugin_beast_mode', 
    'Enable Beast Mode?',
      'ams_plugin_setting_beast_mode', 
      'ams_general_settings_options', 
      'ams_plugin_main' 
  );

}

function ams_plugin_export_init(){

  // Define the setting args
  $args = array(
      'type'              => 'string', 
      'sanitize_callback' => 'ams_plugin_validate_options',
      'default'           => NULL
  );

  // Register our settings
  register_setting( 'ams_export_file_options_group', 'ams_export_file_options', $args );
  
  // Add a settings section
  add_settings_section( 
      'ams_plugin_csv', 
      'Download CSV File',
      'ams_plugin_section_desc', 
      'ams_export_file_options' 
  );

  // Add a settings section
  add_settings_section( 
      'ams_plugin_csv2', 
      'Import Data from CSV',
      'ams_plugin_section_desc', 
      'ams_export_file_options' 
  );
  
  // Create our settings field for File Name
  add_settings_field( 
      'ams_plugin_pickdate', 
      'Please choose time duration for required data:',
      'ams_plugin_setting_pickdate', 
      'ams_export_file_options', 
      'ams_plugin_csv' 
  ); 
  
  // Create our settings field for Export button
  add_settings_field( 
      'ams_plugin_export_csv', 
      'Export CSV File:',
      'ams_plugin_exportcsv', 
      'ams_export_file_options', 
      'ams_plugin_csv' 
  );
  
  // Create our settings field for Downloading the file
  add_settings_field( 
      'ams_plugin_file_download', 
      'Download your CSV file:',
      'ams_plugin_setting_filedl', 
      'ams_export_file_options', 
      'ams_plugin_csv' 
  );

  // Create our settings field for File Name
  add_settings_field( 
      'ams_plugin_choosefile', 
      'Please choose File to import:',
      'ams_plugin_setting_chooseFile', 
      'ams_export_file_options', 
      'ams_plugin_csv2' 
  ); 
  

}

// Draw the section header
function ams_plugin_section_text() {

  echo '<p>Enter your settings here.</p>';

}
      
// Display and fill the Name text form field
function ams_plugin_setting_name() {

  // Get option 'text_string' value from the database
  $options = get_option( 'ams_plugin_options' );
  $name = $options['name'];

  // Echo the field
  echo "<input id='name' name='ams_plugin_options[name]'
      type='text' value='" . esc_attr( $name ) . "' />";

}

// Display and select the favorite holiday select field
function ams_plugin_setting_fav_holiday() {

  // Get option 'fav_holiday' value from the database
  // Set to 'Halloween' as a default if the option does not exist
$options = get_option('ams_plugin_options', [ 'fav_holiday' => 'Halloween' ] );
$fav_holiday = $options['fav_holiday'];

// Define the select option values for favorite holiday
$items = array( 'Halloween', 'Christmas', 'New Years');

echo "<select id='fav_holiday' name='ams_plugin_options[fav_holiday]'>";

foreach( $items as $item ) {

  // Loop through the option values
  // If saved option matches the option value, select it
  echo "<option value='" . esc_attr( $item ) . "' ".selected( $fav_holiday, $item, false ).">" . esc_html( $item ) . "</option>";

}

echo "</select>";

}

//Display and set the Beast Mode radion button field
function ams_plugin_setting_beast_mode() {

// Get option 'beast_mode' value from the database
  // Set to 'disabled' as a default if the option does not exist
$options = get_option( 'ams_plugin_options', [ 'beast_mode' => 'disabled' ] );
$beast_mode = $options['beast_mode'];

// Define the radio button options
$items = array( 'enabled', 'disabled' );

foreach( $items as $item ) {

  // Loop the two radio button options and select if set in the option value
  echo "<label><input " . checked( $beast_mode, $item, false ) . " value='" . esc_attr( $item ) . "' name='ams_plugin_options[beast_mode]' type='radio' />" . esc_html( $item ) . "</label><br />";

}

}

function ams_plugin_section_desc() {

  echo '';

}

// Display the Export File Button
function ams_plugin_exportcsv() {

  // Get option 'text_string' value from the database
  $options = get_option( 'ams_plugin_options' );
$pickdate = $options['pickdate'];

  // Echo the field
 echo "<input id='exportButton' name='exportButton'
      type='button' value='Export CSV File' /><div id='exButton'></div>";

}

function ams_plugin_setting_pickdate() {

  // Get option 'text_string' value from the database
  $options = get_option( 'ams_plugin_options' );
  $pickdate = $options['pickdate'];
  $pickedate = $options['pickedate'];

  // Echo the field
  echo "<label for='sdate'>Start Date:</label>
  <input id='pickdate' name='ams_plugin_options[pickdate]' class='button button-secondary'
      type='date' value='" . esc_attr( $pickdate ) . "' />
      <label for='edate'>End Date:</label>
         <input id='pickedate' name='ams_plugin_options[pickedate]' class='button button-secondary'
             type='date' value='" . esc_attr( $pickedate ) . "' />";


}

function ams_plugin_setting_filedl() {

$path = wp_upload_dir();
echo '<button id="fileDownload" type="button" disabled>Download</button>';  //make a link to the file so the user can download. 
}

function ams_plugin_setting_chooseFile() {

  // Echo the field
 echo '<form method="POST" action="" enctype="multipart/form-data" id="myform">
            <input type="file" id="fileUpload" name="fileUpload" />
            <input type="button" class="button" value="Upload and Import!" id="but_upload">';
            echo wp_nonce_field( 'my_nonce' );
   echo '</form>';

}

// Validate user input for all three options
function ams_plugin_validate_options( $input ) {

// Only allow letters and spaces for the name
  $valid['name'] = preg_replace(
      '/[^a-zA-Z\s]/',
      '',
      $input['name'] );
      
  if( $valid['name'] !== $input['name'] ) {

      add_settings_error(
          'ams_plugin_name',
          'ams_plugin_texterror',
          'Incorrect value entered! Please only input letters and spaces.',
          'error'
      );

  }
      
  // Sanitize the data we are receiving 
  $valid['fav_holiday'] = sanitize_text_field( $input['fav_holiday'] );
  $valid['beast_mode'] = sanitize_text_field( $input['beast_mode'] );
  $valid['pickdate'] = sanitize_text_field( $input['pickdate'] );

  $valid['pickedate'] = sanitize_text_field( $input['pickedate'] );
  return $valid;
}
?>