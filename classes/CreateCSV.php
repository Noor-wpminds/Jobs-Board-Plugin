<?php

class CreateCSV {
	public $query;
	public $file_name;
	public $path;
	
	function __construct($query, $file_name) {
		$this->query = $query;
    $this->file_name = $file_name;
	$this->path = wp_upload_dir();
		global $wpdb;
	$file = fopen($this->path['path']."/'. $file_name .'.csv", "w");  // the file name you choose
	//$query = "SELECT * FROM {$wpdb->prefix}posts WHERE post_status = 'publish' AND post_type='ams_job_application'";
	$results = $wpdb->get_results($query, ARRAY_A);
	$header = $results[0];
	//print_r($results);
	$file_header = array_keys($header);
	//print_r($file_header);
	fputcsv($file, $file_header);
		fclose($file); 
	}
	
	function update_file() {
		$file = fopen($this->path['path'].'/'. $this->file_name .'.csv', "a");
		$results = $wpdb->get_results($this->query, ARRAY_A);
		foreach ($results as $result) {
		fputcsv($file, $result);
		}
	fclose($file); 
	}
	
	function get_download_link(){
		echo '<a href="'.$this->path['url'].'/'. $this->file_name .'.csv">Download</a>';  //make a link to the file so the user can download.
	}
}