<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($status='ABSPATH not defn'); } //exit if directly accessed

//admin options.
//if (is_page('wp-admin')) {
//Adds an imok link to the Dashboard Settings menu. Also creates the imok setting page 
add_action( 'admin_menu', ['Emogic_IMOK_Admin' , 'imok_add_admin_menu' ] );
//admin_init is triggered before any other hook when a user accesses the admin area.
add_action( 'admin_init', ['Emogic_IMOK_Admin' , 'imok_settings_init'] );
//add custom settings link next to plugin deactivate link
add_filter( "plugin_action_links_" . IMOK_PLUGIN_NAME , ['Emogic_IMOK_Admin' , 'imok_settings_link'] );
//}


class Emogic_IMOK_Admin{

	public static function imok_settings_init(  ) {

		//we are saving all settings in an array (imok_admin_options in sql wp_options contains array)
		//register_setting( string $option_group, string $option_name, array $args = array() )
		//option _name created in update_option in activate.php
		register_setting( 'imok_option_group', 'imok_admin_options' ); //string $option_group, string $option_name 
		
		//from_email

		//add_settings_section( string $id, string $title, callable $callback, string $page, array $args = array() )
		add_settings_section(
			'imok_admin_page_section', //$id name of new section
			'imok settings', //$title
			['Emogic_IMOK_Admin' , 'imok_from_email_section_callback'], //$callback
			'imok_admin_page' //$page we are naming page here
		);
		
		//add_settings_field( string $id, string $title, callable $callback, string $page, string $section = ‘default’, array $args = array() )
		add_settings_field(
			'imok_from_email_field', //$id
			'From Email (must exist on your domain to actually send from this email address)', //$title
			['Emogic_IMOK_Admin' , 'imok_from_email_field_render'], //$callback
			'imok_admin_page', //$page points to page in add_settings_section
			'imok_admin_page_section' //$section  points to section $id
		);

		// imok_cron_schedule_field
		add_settings_field(
			'imok_cron_schedule_field',
			'IMOK cron schedule (in seconds)',
			['Emogic_IMOK_Admin' , 'imok_cron_schedule_field_render'],
			'imok_admin_page',
			'imok_admin_page_section'
		);

	}

	public static function imok_from_email_section_callback(  ) {
		//echo 'Section text';
	}
	
	public static function imok_from_email_field_render(  ) {
		$options = get_option( 'imok_admin_options' );
		$option1 = $options['imok_from_email_field'];
		echo "<input type='text' name='imok_admin_options[imok_from_email_field]' value='{$option1}'>";
	}	

	public static function imok_cron_schedule_field_render(  ) {
		$options = get_option( 'imok_admin_options' );
		$option1 = $options['imok_cron_schedule_field'];
		echo "<input type='text' name='imok_admin_options[imok_cron_schedule_field]' value='{$option1}'>";
	}
	
	public static function imok_add_admin_menu(  ) {
		add_options_page( 'imok settings', 'imok', 'manage_options', IMOK_ADMIN_MENU_SLUG , ['Emogic_IMOK_Admin' , 'imok_options_page'] );
		//add_options_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $callback = '', int $position = null ):
	}

	public static function imok_options_page(  ) {
		 //ob_start();//allow return with same code
		echo"<form action='options.php' method='post'>";
		settings_fields( 'imok_option_group' ); // settings_fields( string $option_group ) Outputs nonce, action, and option_page fields for a settings page.
		do_settings_sections( 'imok_admin_page' ); //do_settings_sections( string $page ) Prints out all settings sections added to a particular settings page.
		submit_button();
		echo"</form>";
		//return ob_get_clean(); //allow return with same code
	}

	public static function imok_settings_link($links){
			//$settings_link = "<a href='admin.php?page=" . IMOK_ADMIN_MENU_SLUG . "'>Settings</a>";
			$settings_link = "<a href='" .menu_page_url( IMOK_ADMIN_MENU_SLUG ) . "'>Settings</a>";
			array_push($links , $settings_link);
			return $links;
		}
	
}
