<?php

/**
* Plugin Name: Emogic IMOK
* Plugin URI: https://github.com/vpelss/imok_wp#readme
* Description: Emogic IMOK 
* Version: 0.5.0
* License: GPLv3
* License URI:  https://github.com/vpelss/imok_wp?tab=GPL-3.0-1-ov-file#readme
* Author: Vince Pelss
* Author URI: https://www.emogic.com/
**/

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

// define variable for path to this plugin file.
define( 'IMOK_PLUGIN_PATH_AND_FILENAME' , __file__ ); // c:\*********\imok_pulgin_folder\imok.php
define( 'IMOK_PLUGIN_PATH', dirname( __FILE__ ) ); // c:\************\imok_pulgin_folder\
define( 'IMOK_PLUGIN_LOCATION_URL', plugins_url( '', __FILE__ ) ); // http://wp_url/wp-content/plugins/imok_pulgin_folder
define( 'IMOK_PLUGIN_NAME' , plugin_basename( __FILE__ ) ); // imok_wp (or other if renamed)
define( 'IMOK_ROOT_URL' , home_url() ); // http://wp_url/

register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , ['Emogic_IMOK_Activate' , 'activate'] ); 

class EMOGIC_IMOK {
	
	public static function run() {
		require_once plugin_dir_path(__file__) . 'inc/activate.php' ; //set up pages
		require_once plugin_dir_path(__file__) . 'inc/deactivate.php' ; //remove created pages
		
		require_once plugin_dir_path(__file__) . 'inc/admin.php' ;//add admin page (?empty) , settings links , MOVE TO imok/settings add meta type , user fields , user field write
		//require_once plugin_dir_path(__file__) . 'inc/settings.php' ; //settings page functions
		//require_once plugin_dir_path(__file__) . 'inc/redirector.php' ; //main page redirects to page based on status
		//require_once plugin_dir_path(__file__) . 'inc/login_logout.php' ; //logging in logging out page functions
		//require_once plugin_dir_path(__file__) . 'inc/cron.php' ; //cron page functions
		//require_once plugin_dir_path(__file__) . 'inc/commands.php' ; //functions for IMOK Logged In page
		//require_once plugin_dir_path(__file__) . 'inc/email.php' ; //email
		//require_once plugin_dir_path(__file__) . 'inc/menu.php';
	}
}

if( class_exists('EMOGIC_IMOK') ){
	$EMOGIC_IMOK = new EMOGIC_IMOK();
	$EMOGIC_IMOK->run();
	}

//fixes
	//security benefit to commands going through : /wp-admin/admin-post.php
	//<a href="<?php echo esc_url( $user_url ); >"><?php echo esc_html( $user_name ); ></a>


//audio alarm. bypass dom interaction?
//no audio alarm option in settings?

	//no comments
	//no posts
	//no media

//to do
	//messaging option
	//pay system?

?>
