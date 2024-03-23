<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//register_deactivation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'deactivate' , 'deactivate_plugin') );
register_deactivation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , ['Emogic_IMOK_Deactivate' , 'deactivate'] );


class Emogic_IMOK_Deactivate{
	
	public static function deactivate(){

		//remove created pages
		$dir = IMOK_PLUGIN_PATH . "/pages/";
		//$pages = scandir($dir);
		$pages = array_diff(scandir($dir), array('..', '.'));
		foreach ($pages as $page_name) {
			if(isset(get_posts( ['post_type' => 'page' , 'title' => $page_name] )[0])){
				$page = get_posts( ['post_type' => 'page' , 'title' => $page_name] )[0]; 
				wp_delete_post($page->ID , 1);
				}
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

		delete_option( 'imok_admin_settings' );
		wp_clear_scheduled_hook( 'imok_cron_hook' );
		 //wp_schedule_event( $time , 'fifteen_minutes', 'imok_cron_hook' );
		 //wp_schedule_event( int $timestamp, string $recurrence, string $hook, array $args = array(), bool $wp_error = false )
		flush_rewrite_rules();
	
	}
	
}

?>
