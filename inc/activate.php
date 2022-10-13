<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

class activate{

	public function activate_plugin(){
		imok_read_and_create_pages(); //create default pages

		$imok_r1 = $_SERVER['HTTP_HOST'];
		$imok_r2 = $_SERVER['SERVER_NAME'];
		$imok_from_email = 'imok9@' . $_SERVER['HTTP_HOST']; //assume a send from email address
		update_option('imok_admin_settings' , array( 'imok_from_email_field'=>$imok_from_email ) );

		flush_rewrite_rules();
	}

}

register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'activate' , 'activate_plugin') );

?>
