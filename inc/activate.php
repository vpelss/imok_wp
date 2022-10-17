<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'activate' , 'activate_plugin') ); // ( string $file, callable $callback ) //activate class
register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , 'imok_activate_plugin' ); // ( string $file, callable $callback ) //activate class

/*
class activate{
	public function activate_plugin(){
		imok_read_and_create_pages(); //create default pages
		$imok_r1 = $_SERVER['HTTP_HOST'];
		$imok_r2 = $_SERVER['SERVER_NAME'];
		$imok_from_email = 'imok@' . $_SERVER['HTTP_HOST']; //assume a send from email address
		update_option('imok_admin_settings' , array( 'imok_from_email_field'=>$imok_from_email ) );
		flush_rewrite_rules();
	}
}
*/

function imok_activate_plugin(){
		imok_read_and_create_pages(); //create default pages

		//set from email
		$imok_r1 = $_SERVER['HTTP_HOST'];
		$imok_r2 = $_SERVER['SERVER_NAME'];
		$imok_from_email = 'imok@' . $_SERVER['HTTP_HOST']; //assume a send from email address
		update_option('imok_admin_settings' , array( 'imok_from_email_field'=>$imok_from_email ) );

		//set new home page
		$home = get_page_by_title( 'IMOK Logged In' );
		update_option( 'page_on_front', $home->ID );
		update_option( 'show_on_front', 'page' );

		flush_rewrite_rules();
	}

?>
