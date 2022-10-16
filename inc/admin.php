<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//disable admin bar for users
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
		}
	}

/*
//add_action( 'admin_menu' , array('admin' ,'imok_add_admin_pages') ); //set an admin page and put it in wp admin left menu
add_action( 'admin_menu' , 'imok_add_admin_pages' ); //set an admin page and put it in wp admin left menu
function imok_add_admin_pages(){
		//add_menu_page( 'browser tab text' , 'link text' , 'manage_options' , 'url ? page name' , 'path to html inc\admin\admin::admin_index' , 'dashicons-store' , 110 );
		add_menu_page( 'imok Plugin' , 'IMOK Admin' , 'manage_options' , 'imok_admin_page' , 'imok_admin_index' , 'dashicons-store' , 110 );
	}
function imok_admin_index(){//generates html output
		require_once IMOK_PLUGIN_PATH . '/templates/admin.php'; //
	}

add_filter( "plugin_action_links_" . IMOK_PLUGIN_NAME , 'imok_settings_link' ); 	//set up link under plugin on plugin page
function imok_settings_link($links){
		//add custom settings link
		$settings_link = '<a href="admin.php?page=imok_admin_page">Settings</a>';
		array_push($links , $settings_link);
		return $links;
	}
*/

//the imok fields to be added to user profile page
//these require echo to feed to user page at correct time. return goes to a bit bucket so it does not work here
add_action( 'show_user_profile', 'imok_settings_form_echo' ); // Add the imok fields to user's own profile editing screen
add_action( 'edit_user_profile', 'imok_settings_form_echo' ); // Add the imok fields to user profile editing screen for admins
function imok_settings_form_echo( $user ){
	$html = "<h2 id='settings_top'>IMOK Data Settings Below:</h2><hr>" . imok_settings_form($user);
	echo $html;
	}

add_action( 'personal_options_update', 'imok_process_form' ); // user to process IMOK setting changes on their account page. imok_process_form() is in settings.php
add_action( 'edit_user_profile_update', 'imok_process_form' ); // admin to process user's IMOK setting.  imok_process_form() is in settings.php

//--------------------------------

//admin options.

add_action( 'admin_init', 'imok_settings_init' ); //admin_init is triggered before any other hook when a user accesses the admin area.
function imok_settings_init(  ) {
	register_setting( 'imok_admin_page', 'imok_admin_settings' ); //string $option_group, string $option_name : we are saving all settings in an array (imok_admin_settings in wp_options contains array)
	add_settings_section(
		'imok_pluginPage_section',
		__( 'imok settings', 'emogic.com' ),
		'imok_settings_section_callback',
		'imok_admin_page'
	);
	add_settings_field(
		'imok_from_email_field',
		__( 'From Email', 'emogic.com' ),
		'imok_from_email_field_render',
		'imok_admin_page',
		'imok_pluginPage_section'
	);
}

function imok_settings_section_callback(  ) {
	//echo __( 'This section description', 'emogic.com' );
}

function imok_from_email_field_render(  ) {
	$options = get_option( 'imok_admin_settings' );
	$option1 = $options['imok_from_email_field'];
	echo "<input type='text' name='imok_admin_settings[imok_from_email_field]' value='{$options['imok_from_email_field']}'>";
}

//Adds an imok link to the Dashboard Settings menu. Also creates the imok setting page 
add_action( 'admin_menu', 'imok_add_admin_menu' );
function imok_add_admin_menu(  ) {
	add_options_page( 'imok settings', 'imok', 'manage_options', 'imok_settings', 'imok_options_page' );
	//add_options_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $callback = '', int $position = null ):
}

function imok_options_page(  ) {
	 //ob_start();//allow return with same code
	echo"<form action='options.php' method='post'>";
	settings_fields( 'imok_admin_page' );
	do_settings_sections( 'imok_admin_page' );
	submit_button();
	echo"</form>";
	//return ob_get_clean(); //allow return with same code
}

//add custom settings link next to plugin deactivate link
add_filter( "plugin_action_links_" . IMOK_PLUGIN_NAME , 'imok_settings_link' );
function imok_settings_link($links){
		$settings_link = '<a href="admin.php?page=imok_settings">Settings</a>';
		array_push($links , $settings_link);
		return $links;
	}

?>
