<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

require_once IMOK_PLUGIN_PATH . 'inc/menu.php';

//create menu on WP init
add_action('init', ['Emogic_IMOK_Menu' , 'imok_nav_creation_primary'] ); 

class Emogic_IMOK_Activate{

	public static function activate(){
	
		self::create_pages(); //create default pages

		//set a default from email
		$imok_r1 = $_SERVER['HTTP_HOST'];
		$imok_r2 = $_SERVER['SERVER_NAME'];
 		//assume a send from email address imok@last.two
		$domain_array =  explode("." , $_SERVER['HTTP_HOST']);
		$domain_array = array_reverse($domain_array);
		$imok_from_email = 'imok@' . $domain_array[1] . '.' . $domain_array[0];
		//$imok_from_email = 'imok@' . $_SERVER['HTTP_HOST']; //assume a send from email address
		update_option('imok_admin_settings' , array( 'imok_from_email_field'=>$imok_from_email ) );
		flush_rewrite_rules();
	}
	
	public static function create_pages(){	
		$folders = ['draft' , 'publish'];
		foreach($folders as $folder){
			$dir = IMOK_PLUGIN_PATH . "/pages/{$folder}/";
			$files = scandir($dir);
			foreach ($files as $file) {
				if($file == "."){continue;}
				if($file == ".."){continue;}
				if( isset(get_posts( ['post_type' => 'page' , 'title' => $file] )[0]) ){
					continue;
					} //skip if this file page already exists
				$file_string = file_get_contents($dir . $file , true);		
				$post_id = self::post_page($file , $file_string , $folder);
				if($file == 'IMOK'){ //set main landing page
					update_option('show_on_front' , 'page');
					update_option('page_on_front' , $post_id );
				}
			}
		}
	}

	public static function post_page($file , $file_string , $status){
			$wordpress_page = array(
				'post_title'    => $file,
				'post_content'  => $file_string,
				'post_status'   => $status,
				'post_author'   => 1,
				'post_type' => 'page'
				);
			return wp_insert_post( $wordpress_page );	
	}
		
}

//this should only run on register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , ['Emogic_IMOK' , 'activate'] ) from imok.php
Emogic_IMOK_Activate::activate();

?>
