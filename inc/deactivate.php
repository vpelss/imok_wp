<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

require_once IMOK_PLUGIN_PATH . 'inc/menu.php';

add_action('shutdown', ['Emogic_IMOK_Menu' , 'shutdown_funct'] );

class Emogic_IMOK_Deactivate{
	
	public static function deactivate(){
			
		//add_action('shutdown', ['Emogic_IMOK_Menu' , 'shutdown_funct'] );
		//Emogic_IMOK_Menu::shutdown_funct(); //remove menu

		$folders = ['draft' , 'publish'];
		foreach($folders as $folder){
			$dir = IMOK_PLUGIN_PATH . "/pages/{$folder}/";
			$files = scandir($dir);
			foreach ($files as $file) {
				if($file == "."){continue;}
				if($file == ".."){continue;}
				if(isset(get_posts( ['post_type' => 'page' , 'title' => $files , 'post_status' => $folders] )[0])){
				$page = get_posts( ['post_type' => 'page' , 'title' => $files , 'post_status' => $folders ] )[0]; 
				wp_delete_post($page->ID , 1);
				}
			}
		}
		delete_option('show_on_front');
		delete_option('page_on_front');
			
		
		//remove all user metadata starting with imok
		$users = get_users();
/*
		foreach ( $users as $user ) {
			delete_user_meta($user->ID , 'imok_timezone');
			delete_user_meta($user->ID , 'imok_contact_email_1');
			delete_user_meta($user->ID , 'imok_contact_email_2');
			delete_user_meta($user->ID , 'imok_contact_email_3');
			delete_user_meta($user->ID , 'imok_email_message');
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

Emogic_IMOK_Deactivate::deactivate();

?>
