<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

class deactivate{

	public static function deactivate_plugin(){

		//remove created pages
		$pages = array("IMOK Log In" , "IMOK Logged In" , "IMOK Redirector" ,  "IMOK Settings");
		foreach ($pages as $page_name) {
			$page = get_page_by_title($page_name);
			wp_delete_post($page->ID , 1);
			}

		//remove all user metadata starting with imok
		$users = get_users();
/*
		foreach ( $users as $user ) {
			delete_user_meta($user->ID , 'imok_timezone');
			delete_user_meta($user->ID , 'imok_contact_email_1');
			delete_user_meta($user->ID , 'imok_contact_email_2');
			delete_user_meta($user->ID , 'imok_contact_email_3');
			delete_user_meta($user->ID , 'imok_email_form');
			delete_user_meta($user->ID , 'imok_alert_date');
			delete_user_meta($user->ID , 'imok_alert_time');
			delete_user_meta($user->ID , 'imok_alert_interval');
			delete_user_meta($user->ID , 'imok_pre_warn_time');
			}
*/
		flush_rewrite_rules();
	}

}

register_deactivation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'deactivate' , 'deactivate_plugin') );
