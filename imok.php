<?php

/**
* Plugin Name: Emogic IMOK
* Plugin URI: https://github.com/vpelss/imok_wp#readme
* Description: Emogic IMOK 
* Version: 0.7.0
* License: GPLv3
* License URI:  https://github.com/vpelss/imok_wp?tab=GPL-3.0-1-ov-file#readme
* Author: Vince Pelss
* Author URI: https://www.emogic.com/
**/

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

// define variable for path to this plugin file.
define( 'IMOK_PLUGIN_PATH_AND_FILENAME' , __file__ ); // c:\*********\imok_pulgin_folder\imok.php
#define( 'IMOK_PLUGIN_PATH', dirname( __file__ ) ); // sans trailing slash c:\************\imok_pulgin_folder
define( 'IMOK_PLUGIN_PATH', plugin_dir_path( __file__ ) ); // c:\************\imok_pulgin_folder\
define( 'IMOK_PLUGIN_LOCATION_URL', plugins_url( '', __FILE__ ) ); // http://wp_url/wp-content/plugins/imok_pulgin_folder
define( 'IMOK_PLUGIN_NAME' , plugin_basename( __FILE__ ) ); // Emogic IMOK (or other if renamed)
define( 'IMOK_ROOT_URL' , home_url() ); // http://wp_url/
define( 'IMOK_MAIN_PAGE' , 'IMOK' ); // set to main page name : curently IMOK
define( 'IMOK_MENU_NAME' , 'imok_menu' ); // set menu name

register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , ['Emogic_IMOK' , 'activate'] ); 
register_deactivation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , ['Emogic_IMOK' , 'deactivate'] );
add_action("wp_enqueue_scripts", ["EMOGIC_IMOK" , "enqueue_js"] , 1); //set priority early = 1
add_action("wp_enqueue_scripts", ["EMOGIC_IMOK" , "enqueue_css"] );

class EMOGIC_IMOK {
	
	//called as required
	public static function admin_requires() {
		require_once IMOK_PLUGIN_PATH . 'inc/admin.php' ;//add admin page (?empty) , settings links , MOVE TO imok/settings add meta type , user fields , user field write
		require_once IMOK_PLUGIN_PATH . 'inc/cron.php' ; //cron page functions
		require_once IMOK_PLUGIN_PATH . 'inc/settings.php' ; //settings page function
	}

	public static function non_admin_requires() {
		require_once IMOK_PLUGIN_PATH . 'inc/redirector.php' ; //main page redirects to page based on status
		require_once IMOK_PLUGIN_PATH . 'inc/login_logout.php' ; //logging in logging out page functions
		require_once IMOK_PLUGIN_PATH . 'inc/commands.php' ; //functions for IMOK Logged In page
		require_once IMOK_PLUGIN_PATH . 'inc/menu.php';
		require_once IMOK_PLUGIN_PATH . 'inc/settings.php' ; //settings page functions
		require_once IMOK_PLUGIN_PATH . 'inc/cron.php' ; //cron page functions
	}

	public static function activate() {
		require_once IMOK_PLUGIN_PATH . 'inc/activate.php' ; //set up pages
	}
	
	public static function deactivate() {
		require_once IMOK_PLUGIN_PATH . 'inc/deactivate.php' ; //deactivate pages
	}

	public static function enqueue_js(){
		wp_enqueue_script('IMOK__wp-js', IMOK_PLUGIN_LOCATION_URL . '/js/imok.js');
	}
	
	public static function enqueue_css(){
		wp_enqueue_style('IMOK__wp-css', IMOK_PLUGIN_LOCATION_URL . '/css/imok.css');
	}
	
}

//we really don't need to separate these as user has all requires anyway, and that is the one we wanted to try to speed up.
if( is_admin() ){
	EMOGIC_IMOK::admin_requires();
}
else{
	EMOGIC_IMOK::non_admin_requires();
}

?>
