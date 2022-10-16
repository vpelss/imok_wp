<?php
/**
* Plugin Name: imok
* Plugin URI: https://github.com/vpelss/imok_wp
* Description: IMOK for WP.
* Version: 1.0
* Author: Vince Pelss
* Author URI: https://www.emogic.com/
**/

// Enable WP_DEBUG mode
//define( 'WP_DEBUG', true );

// Enable Debug logging to the /wp-content/debug.log file
//define( 'WP_DEBUG_LOG', true );

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

// define variable for path to this plugin file.
define( 'IMOK_PLUGIN_PATH_AND_FILENAME' , __file__ ); // c:\*********\imok_pulgin_folder\imok.php
define( 'IMOK_PLUGIN_PATH', dirname( __FILE__ ) ); // c:\************\imok_pulgin_folder\
define( 'IMOK_PLUGIN_LOCATION_URL', plugins_url( '', __FILE__ ) ); // http://wp_url/wp-content/plugins/imok_pulgin_folder
define( 'IMOK_PLUGIN_NAME' , plugin_basename( __FILE__ ) ); // imok_wp (or other if renamed)
define( 'IMOK_ROOT_URL' , home_url() ); // http://wp_url/

class imok {
	function __construct() {
		require_once plugin_dir_path(__file__) . 'inc/activate.php' ; //set up pages
		require_once plugin_dir_path(__file__) . 'inc/deactivate.php' ; //remove created pages
		require_once plugin_dir_path(__file__) . 'inc/enqueue.php' ;//add js and styles : none
		require_once plugin_dir_path(__file__) . 'inc/admin.php' ;//add admin page (?empty) , settings links , MOVE TO imok/settings add meta type , user fields , user field write
		require_once plugin_dir_path(__file__) . 'inc/settings.php' ; //settings page functions
		require_once plugin_dir_path(__file__) . 'inc/redirector.php' ; //main page redirects to page based on status
		require_once plugin_dir_path(__file__) . 'inc/login_logout.php' ; //logging in logging out page functions
		require_once plugin_dir_path(__file__) . 'inc/cron.php' ; //cron page functions
		require_once plugin_dir_path(__file__) . 'inc/commands.php' ; //functions for IMOK Logged In page
		require_once plugin_dir_path(__file__) . 'inc/pages.php' ; //auto setup pages
		require_once plugin_dir_path(__file__) . 'inc/email.php' ; //email
	}
}

if( class_exists('imok') ){
	$imok = new imok();
	}

//fixes
	//security benefit to commands going through : /wp-admin/admin-post.php
	//<a href="<?php echo esc_url( $user_url ); >"><?php echo esc_html( $user_name ); ></a>

	//set main page?

	//no comments
	//no posts
	//no media

//to do
	//messaging option
	//pay system?

?>
