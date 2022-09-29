<?php

//add_action('init', 'imok_create_wordpress_page_programmatically'); //if we want to continuall check for and add missing pages....

//load default pages and create them if they do not already exist.
function imok_create_wordpress_page_programmatically(){

$dir = IMOK_PLUGIN_PATH . "/pages/";
$files = scandir($dir);

foreach ($files as $file) {
	if($file == "."){continue;}
	if($file == ".."){continue;}
	if( get_page_by_title($file) ){continue;} //skip if this file page already exists
	$file_string = file_get_contents($dir . $file , true);

	$wordpress_page = array(
		'post_title'    => $file,
		'post_content'  => $file_string,
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_type' => 'page'
		);
	wp_insert_post( $wordpress_page );
	}
}

?>
