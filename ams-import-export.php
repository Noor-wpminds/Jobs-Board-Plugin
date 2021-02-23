<?php

function get_ajax_posts(){

$pickdate = $_POST['date'];
$pickedate = $_POST['dateEnd'];

	if (isset($pickdate) && isset($pickedate)) {
$date = getdate((strtotime($pickdate)));
$dateEnd = getdate((strtotime($pickedate)));
$args = array(
'post_type' => 'ams_job_application',
'posts_per_page' => '-1',
'date_query' => array(
array(
    'after'    => array(
	'year'  => $date['year'],
    'month' => $date['mon'],
    'day'   => $date['mday'],
    ),
    'before'    => array(
	'year'  => $dateEnd['year'],
    'month' => $dateEnd['mon'],
    'day'   => $dateEnd['mday'],
    ),
    'inclusive' => true,
),
),
);
}

$ajaxposts = new WP_Query( $args );

$path = wp_upload_dir();
$file = fopen($path['path']."/infooo.csv", "w");  // the file name you choose
// The Loop
if ( $ajaxposts->have_posts() ) {
$posts = $ajaxposts->posts;
$header = $posts[0];
$header = (array)$header;
$file_header = array_keys($header);
fputcsv($file, $file_header);


foreach($posts as $post) {
	$post = (array)$post;
	fputcsv($file, $post);
}
$fileUrl = '<a id="fileURL" href="'.$path['url'].'/infooo.csv">Download</a>';		
fclose($file);
} else {
echo 'no posts found';
} 

echo json_encode( $fileUrl );
die(); // exit ajax call(or it will return useless information to the response)
}

add_action('wp_ajax_get_ajax_posts', 'get_ajax_posts');
add_action('wp_ajax_nopriv_get_ajax_posts', 'get_ajax_posts');

function upload_file_callback(){

	$target_dir = wp_upload_dir();
	$target_file = $target_dir['url'] . '/'.basename($_FILES["fileUpload"]["name"]);
	$uploadOk = 1;
	$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
		//$uploadOk = 0;
	        die ( 'Busted!');
	    }

	    if ( ! wp_doing_ajax() ) {
	    	die();
	    }

// Check if file is selected
if (($_FILES['fileUpload']['name'] == "")){
	$response = "Please select a file first.";
	$uploadOk = 0;
}

// Check file size
if ($_FILES["fileUpload"]["size"] > 500000) {
  $response = "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if( $FileType != "csv" ) {
  $response = "Sorry, only CSV files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  $response = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {

  if(isset($_FILES['fileUpload'])) {
  	$upload = wp_upload_bits($_FILES["fileUpload"]["name"], null, file_get_contents($_FILES["fileUpload"]["tmp_name"]));
    $response = "The file ". htmlspecialchars( basename( $_FILES["fileUpload"]["name"])). " has been uploaded.";
  } else {
    $response = "Sorry, there was an error uploading your file.";
  }
}

$response = importFile($upload);

echo json_encode( $response );
die(); 
		
}

function importFile($upload) {
	if($upload['error'] == false){
		$file = fopen($upload['url'], "r");
		$file_contents = fgetcsv($file);
		if ( $file_contents[0] == 'post_title' && $file_contents[1] == 'job_category' && $file_contents[2] == 'location' 
		&& $file_contents[3] == 'salary' && $file_contents[4] == 'timings' && $file_contents[5] == 'benefits'  ) {
		while(! feof($file))
		  {
		  $file_contents = fgetcsv($file);
		  		$my_post = array(
		    'post_title'    => $file_contents[0],
		    'post_status'   => 'publish',
		    'post_type' => 'ams_jobs',
		    'meta_input' => array(
		      'location' => $file_contents[2],
		      'salary' => $file_contents[3],
		  	'timings' => $file_contents[4],
		  	'benefits' => $file_contents[5]
		  ),
		    'tax_input' => array( 
		      'ams_job_category' => $file_contents[1] 
		    )
		    );
		   
		  // Insert the post into the database
		  $post_id = wp_insert_post( $my_post );
		  if( $post_id ){
		      $response = "File Imported successfully!!";
		  } else {
		      $response = "Something went wrong, try again.";
		  }
		}

		  }

		  else {
		  	$response = 'File format is not correct';
		  }

		fclose($file); 
		$file_Url = $upload['url'];
		deleteFile($file_Url);
	}

	return $response;
}

function deleteFile($file_Url) {
	$path = parse_url($file_Url, PHP_URL_PATH); // Remove "http://localhost"
	$fullPath = get_home_path() . $path;
	unlink($fullPath);
}

add_action('wp_ajax_nopriv_upload_file_callback', 'upload_file_callback');
add_action( 'wp_ajax_upload_file_callback', 'upload_file_callback' );