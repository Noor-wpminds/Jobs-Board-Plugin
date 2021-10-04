<?php

/**
 * Adds a metabox below the post content
 */
 function ams_add_job_metaboxes() {
	add_meta_box(
		'ams_job_details',
		'Job Details',
		'ams_job_details',
		'ams_jobs',
		'normal',
		'high'
	);
}

function ams_add_application_metaboxes() {
	add_meta_box(
		'ams_applicant_details',
		'Applicant Details',
		'ams_applicant_details',
		'ams_job_application',
		'normal',
		'high'
	);
}

/**
 * Output the HTML for the job details metabox.
 */
function ams_job_details() {
	global $post;

	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'job_fields' );

	// Get the location data if it's already been entered
	$location = get_post_meta( $post->ID, 'location', true );
	
	//HTML for the location field
	$lahore = "";
	$karachi = "";
	$islamabad = "";
	
	switch ($location) {
  case "lahore":
    $lahore = "selected";
    break;
  case "karachi":
    $karachi = "selected";
    break;
  case "islamabad":
    $islamabad = "selected";
    break;
  default:
    $location = "";
}

	// Output the location field
	echo '<label for="location"><b>Location</b></label>
	<select name="location">
  <option value="">Please select location</option>
   <option value="lahore" '.$lahore.'>Lahore</option>
  <option value="karachi" '.$karachi.'>Karachi</option>
  <option value="islamabad" '.$islamabad.'>Islamabad</option>
</select><br>';

	// Get the salary data if it's already been entered
	$salary = get_post_meta( $post->ID, 'salary', true );

	// Output the salary field
	echo '  <br><label for="salary"><b>Salary Range</b></label>
	<input type="range" id="salary" name="salary" min="5000" max="500000" value="' . esc_textarea( $salary )  . '" class="widefat"><br>';
	
	// Get the timings data if it's already been entered
	$timings = get_post_meta( $post->ID, 'timings', true );

	// Output the timings field
	echo '<br><label for="timings"><b>Timings</b></label>
	<input type="text" name="timings" value="' . esc_textarea( $timings )  . '" class="widefat"><br>';
	
	// Get the benefits data if it's already been entered
	$benefits = get_post_meta( $post->ID, 'benefits', true );

	// Output the benefits field
		echo '<br><label for="benefits"><b>Benefits</b></label>
		<input type="text" name="benefits" value="' . esc_textarea( $benefits )  . '" class="widefat"><br><br>';
}


/**
 * Save the metabox data
 */
function ams_save_jobs_meta( $post_id, $post ) {

	// Return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	if ( ! isset( $_POST['location'] ) || ! wp_verify_nonce( $_POST['job_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}

	// Now that we're authenticated, time to save the data.
	// This sanitizes the data from the field and saves it into an array $jobs_meta.
	$jobs_meta1['location'] = esc_textarea( $_POST['location'] );
	$jobs_meta2['salary'] = esc_textarea( $_POST['salary'] );
	$jobs_meta3['timings'] = esc_textarea( $_POST['timings'] );
	$jobs_meta4['benefits'] = esc_textarea( $_POST['benefits'] );

	// Cycle through the $jobs_meta array.
	foreach ( $jobs_meta1 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
	foreach ( $jobs_meta2 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
	foreach ( $jobs_meta3 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
		foreach ( $jobs_meta4 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'ams_save_jobs_meta', 1, 2 );


function ams_applicant_details() {
	global $post;

	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'application_fields' );

	// Get the location data if it's already been entered
	$fname = get_post_meta( $post->ID, 'fname', true );
	$lname = get_post_meta( $post->ID, 'lname', true );
	$bday = get_post_meta( $post->ID, 'bday', true );
	$email = get_post_meta( $post->ID, 'email', true );
	$phnumber = get_post_meta( $post->ID, 'phnumber', true );
	$caddress = get_post_meta( $post->ID, 'caddress', true );
	$position = get_post_meta( $post->ID, 'position', true );
	$show_position = get_post_meta( $post->ID, 'position', true );
	$resume = get_post_meta( $post->ID, 'resume', true );
	$show_resume = get_post_meta( $post->ID, 'resume', true );
	
	echo '<label class="ams-form-label" for="name"><b>Full Name</b></label><br>
	<input class="ams-form-input" type="text" name="fname" placeholder="First Name" value="' . esc_textarea( $fname )  . '" class="widefat">
	<input class="ams-form-input" type="text" name="lname" placeholder="Last Name" value="' . esc_textarea( $lname )  . '" class="widefat"><br>
	<label class="ams-form-label" for="bday"><b>Birth Date</b></label><br>
	<input class="ams-form-input" type="date" name="bday" value="' . esc_textarea( $bday )  . '" class="widefat">
	<br><label class="ams-form-label" for="email"><b>Email Address</b></label><br>
	<input class="ams-form-input" type="email" name="email" placeholder="ex: name@email.com" value="' . esc_textarea( $email )  . '" class="widefat">
	<br><label class="ams-form-label" for="phnumber"><b>Phone Number</b></label><br>
	<input class="ams-form-input" type="tel" name="phnumber" value="' . esc_textarea( $phnumber )  . '" class="widefat">
	<br><label class="ams-form-label" for="address"><b>Current Address</b></label><br>
	<input class="ams-form-input" type="text" name="caddress" value="' . esc_textarea( $caddress )  . '" class="widefat"><br>
	<input class="ams-form-input" type="hidden" name="position" value="' . esc_textarea( $position )  . '" class="widefat"><br>
	<label class="ams-form-label" for="position"><b>Position Applied For:</b></label>
	<a href="' . get_edit_post_link($show_position) . '" title="' . get_the_title($show_position) . '">' . get_the_title($show_position) . '</a>
	<input class="ams-form-input" type="hidden" name="resume" value="' . esc_textarea( $resume )  . '" class="widefat"><br>
	<br><a href="'. $show_resume .'" download="'. get_the_title($show_position) .'">Download Resume</a>';
}

function ams_save_applications_meta( $post_id, $post ) {

	// Return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	// if ( ! isset( $_POST['fname'] ) || ! wp_verify_nonce( $_POST['application_fields'], basename(__FILE__) ) ) {
	// 	return $post_id;
	// }

	// Now that we're authenticated, time to save the data.
	// This sanitizes the data from the field and saves it into an array $jobs_meta.
	$jobs_meta1['fname'] = esc_textarea( $_POST['fname'] );
	$jobs_meta2['lname'] = esc_textarea( $_POST['lname'] );
	$jobs_meta3['bday'] = esc_textarea( $_POST['bday'] );
	$jobs_meta4['email'] = esc_textarea( $_POST['email'] );
	$jobs_meta5['phnumber'] = esc_textarea( $_POST['phnumber'] );
	$jobs_meta6['caddress'] = esc_textarea( $_POST['caddress'] );
	$jobs_meta7['position'] = esc_textarea( $_POST['position'] );

	// Cycle through the $jobs_meta array.
	foreach ( $jobs_meta1 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
	foreach ( $jobs_meta2 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
	foreach ( $jobs_meta3 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
		foreach ( $jobs_meta4 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
	foreach ( $jobs_meta5 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
	foreach ( $jobs_meta6 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;
	
	foreach ( $jobs_meta7 as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'ams_save_applications_meta', 1, 2 );