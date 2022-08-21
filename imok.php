<?php
/**
* _Plugin Name: no imok
* Plugin URI: http://home/wordpress/imok
* Description: This is the very first plugin I ever created.
* Version: 1.0
* Author: Vinman
* Author URI: https://www.emogic.com/
**/

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
$r = __FILE__;
define( 'imok_LOCATION', dirname( __FILE__ ) );
define( 'imok_LOCATION_URL', plugins_url( '', __FILE__ ) );

class imok {
	public $plugin_name;

	function __construct() {
		require_once plugin_dir_path(__file__) . 'inc/imok_plugin_activate.php' ;
		require_once plugin_dir_path(__file__) . 'inc/imok_plugin_activate.php' ;
		//register_activation_hook( __file__ , array( $this , 'activate') );
		//register_deactivation_hook( __file__ , array( $this , 'deactivate') );
		register_activation_hook( __file__ , array( 'imokPluginActivate' , 'activate') );
		register_deactivation_hook( __file__ , array( 'imokPluginDeactivate' , 'deactivate') );

		//https://www.php.net/manual/en/language.types.callable.php
		//register_activation_hook( __file__ , array( $this , 'activate') );
		//register_deactivation_hook( __file__ , array( $this , 'deactivate') );

		add_action( 'init' , array( $this , 'custom_post_type') );
		add_action( 'admin_menu' , array( $this , 'add_admin_pages') );
		add_filter( 'plugin_action_link_NAME-OF-MY-PLUGIN' , array($this , 'settings_link') );
		$this->enqueue();
	}

	function settings_link($links){
		//add custom settings link


	}

	function add_admin_pages(){
		add_menu_page( 'imok Plugin' , 'imok' , 'manage_options' , 'imok_plugin' , array($this,'admin_index') , 'dashicons-store' , 110 );

	}

	function admin_index(){
		//require template
		require_once plugin_dir_path(__file__) . 'templates/admin.php' ;
	}

	/*
	function activate(){
		$this->custom_post_type(); //failsafe precaution only
		flush_rewrite_rules();
	}

	function deactivate(){
		flush_rewrite_rules();
	}
	*/

	function custom_post_type(){
		register_post_type( 'imok' , ['public' => true , 'label' => 'IMOK'] );

	}

	function enqueue(){
		wp_enqueue_script('imokscript' , plugins_url('/assets/imok.js' , __FILE__));
	}

}

if( class_exists('imok') ){
	$imok = new imok();
	}
