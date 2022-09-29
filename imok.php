<?php
/**
* Plugin Name: imok
* Plugin URI: https://github.com/vpelss/imok_wp
* Description: IMOK for WP.
* Version: 1.0
* Author: The Vinman
* Author URI: https://www.emogic.com/
**/

//XDEBUG_SESSION_START=1

// Enable WP_DEBUG mode
//define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
//define( 'WP_DEBUG_LOG', true );

/* exit if directly accessed */
if ( ! defined( 'ABSPATH' ) ) {
	exit($staus='ABSPATH not defn');
	//die('ABSPATH not defn');
}

// define variable for path to this plugin file.
define( 'IMOK_PLUGIN_PATH_AND_FILENAME' , __file__ ); // c:\*********\imok2\imok.php
define( 'IMOK_PLUGIN_PATH', dirname( __FILE__ ) ); // c:\************\imok2\
define( 'IMOK_PLUGIN_LOCATION_URL', plugins_url( '', __FILE__ ) ); // http://home/wordpress/wp-content/plugins/imok2
define( 'IMOK_PLUGIN_NAME' , plugin_basename( __FILE__ ) ); // imok2
define( 'IMOK_ROOT_URL' , home_url() ); // https://emogic.com/imok

class imok {

	function __construct() {

		require_once plugin_dir_path(__file__) . 'inc/activate.php' ; //flush , custom post (temp)
		require_once plugin_dir_path(__file__) . 'inc/deactivate.php' ; //flush , custom post (temp)
		require_once plugin_dir_path(__file__) . 'inc/enqueue.php' ;//add js and styles : none
		require_once plugin_dir_path(__file__) . 'inc/admin.php' ;//add admin page (?empty) , settings links , MOVE TO imok/settings add meta type , user fields , user field write

		require_once plugin_dir_path(__file__) . 'inc/redirector.php' ; //logging in logging out page functions
		require_once plugin_dir_path(__file__) . 'inc/login_logout.php' ; //logging in logging out page functions
		require_once plugin_dir_path(__file__) . 'inc/settings.php' ; //settings page functions
		require_once plugin_dir_path(__file__) . 'inc/cron.php' ; //cron page functions
		require_once plugin_dir_path(__file__) . 'inc/commands.php' ; //imok pushed functions
		require_once plugin_dir_path(__file__) . 'inc/get_set_pages.php' ; //imok auto setup pages

		//uninstall erase db, remove pages,
		//will not rebuild page if in trash...
		//no back button. not likely

	}

}

if( class_exists('imok') ){
	$imok = new imok();
	}

?>
