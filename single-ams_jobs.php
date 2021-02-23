<?php
/* Remove post meta
------------------------------------------------------------ */

add_action( 'genesis_before_comments', 'ams_jobs_single' );


function ams_jobs_single() {
	if ( is_single() && get_post_type( $post ) == 'ams_jobs' && !isset($_POST['submit']) ) {
		//* Remove the entry meta in the entry header (requires HTML5 theme support)
		remove_action( 'genesis_entry_header', 'genesis_post_info', 5 );
	echo '<h1 class="entry-title" itemprop="headline">Apply Now:</h1>';
	echo ams_application_form();
}

if (isset($_POST['submit'])) {
	
	ams_create_application();
	//ams_save_resume();
	ams_email_admin();
}

}

function ams_application_form () {
		
	$application_form = '<div class="ams-application-form">
	<form action="" method="POST" enctype="multipart/form-data">
	<label class="ams-form-label" for="name"><b>Full Name</b></label><br>
	<input class="ams-form-input-name1" type="text" name="fname" placeholder="First Name" required>
	<input class="ams-form-input-name" type="text" name="lname" placeholder="Last Name" required><br>
	<label class="ams-form-label" for="bday"><b>Birth Date</b></label><br>
	<input class="ams-form-input" type="date" name="bday" required>
	<br><label class="ams-form-label" for="email"><b>Email Address</b></label><br>
	<input class="ams-form-input" type="email" name="email" placeholder="ex: name@email.com" required>
	<br><label class="ams-form-label" for="phnumber"><b>Phone Number</b></label><br>
	<input class="ams-form-input" type="tel" name="phnumber" placeholder="123-45-678" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required><br>
	<small>Format: 123-45-678</small>
	<input class="ams-form-input" type="hidden" name="position" value="'. get_the_ID() .'">
	<br><label class="ams-form-label" for="address"><b>Current Address</b></label><br>
	<input class="ams-form-input" type="text" name="caddress" placeholder="Enter Address">
	<label class="ams-form-label" for="myfile"><b>Upload Resume:</b></label>
	<input class="ams-form-input" type="file" id="myfile" name="resume" required>
	<br><br><input type="submit" name="submit" value="APPLY!" class="button" />
	</form>
	</div>';
	
	return $application_form;
}

function ams_email_admin() {
	
	$fname = wp_strip_all_tags($_POST["fname"]);
    $lname = wp_strip_all_tags($_POST["lname"]);
	$bday = wp_strip_all_tags($_POST["bday"]);
	$email = wp_strip_all_tags($_POST["email"]);
	$phnumber = wp_strip_all_tags($_POST["phnumber"]);
	$caddress = wp_strip_all_tags($_POST["caddress"]);
	$position = wp_strip_all_tags($_POST["position"]);
	
	
	$to_admin_email = get_option( 'admin_email' );
	$subject = 'NEW JOB APPLICATION FOR ' . get_the_title($position);
	$body = '<ul>
	<li>Name: ' .$fname.' '.$lname.'</li>
	<li>Email: '. $email.'</li>
	<li>Phone Number: '. $phnumber .'</li>
	</ul>';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	wp_mail( $to_admin_email, $subject, $body, $headers );
}

function ams_email_applicant( $post_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ) {
 
    // If this is just a revision, don't send the email.
    if ( wp_is_post_revision( $post_id ) ) {
        return;
        }
	if (get_post_type( $post_id ) == 'ams_job_application') {	
	$show_position = get_post_meta( $post_id, 'position', true );
	$terms = get_the_terms( get_the_ID(), 'ams_aplication_status' );

    if ( $terms && ! is_wp_error( $terms ) ) { 
		foreach ( $terms as $term ) {
		$body = 'Your application for the position of '.get_the_title($show_position).' is '.$term->name;
		} 
		
	$to_applicant_email = get_post_meta( $post_id, 'email', true );
	$subject = 'NEW EMAIL FROM ' . get_option( 'blogname' );
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	wp_mail( $to_applicant_email, $subject, $body, $headers );
	} }
}

add_action( 'set_object_terms', 'ams_email_applicant',10,6 );

function ams_create_application() {
	$target_dir = plugin_dir_path( __FILE__ )."resumes/";
	$target_file = $target_dir . basename($_FILES["resume"]["name"]);
	$resume_file = plugin_dir_url( __FILE__ )."resumes/".basename($_FILES["resume"]["name"]);
	$uploadOk = 1;
	$resumeFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file is selected
if (($_FILES['resume']['name'] == "")){
	echo "Please select a file first.";
	$uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["resume"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($resumeFileType != "pdf" && $resumeFileType != "doc" && $resumeFileType != "docx"
&& $resumeFileType != "txt" ) {
  echo "Sorry, only PDF, DOC, DOCX & TXT files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["resume"]["name"])). " has been uploaded.";
		$my_post = array(
  'post_title'    => wp_strip_all_tags($_POST["fname"]) . " " . wp_strip_all_tags($_POST["lname"]),
  'post_status'   => 'publish',
  'post_type' => 'ams_job_application',
  'meta_input' => array(
    'fname' => wp_strip_all_tags($_POST["fname"]),
    'lname' => wp_strip_all_tags($_POST["lname"]),
	'bday' => wp_strip_all_tags($_POST["bday"]),
	'email' => wp_strip_all_tags($_POST["email"]),
	'phnumber' => wp_strip_all_tags($_POST["phnumber"]),
	'caddress' => wp_strip_all_tags($_POST["caddress"]),
	'position' => wp_strip_all_tags($_POST["position"]),
	'resume' => $resume_file
)
  );
 
// Insert the post into the database
$post_id = wp_insert_post( $my_post );

if( $post_id ){
    echo "Application submitted successfully!!";
} else {
    echo "Something went wrong, try again.";
}
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
}