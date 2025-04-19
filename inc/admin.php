<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//admin options.
//if (is_page('wp-admin')) {
//admin_init is triggered before any other hook when a user accesses the admin area.
add_action( 'admin_init', ['Emogic_IMOK_Admin' , 'imok_settings_init'] );
//Adds an imok link to the Dashboard Settings menu. Also creates the imok setting page 
add_action( 'admin_menu', ['Emogic_IMOK_Admin' , 'imok_add_admin_menu' ] );
//add custom settings link next to plugin deactivate link
add_filter( "plugin_action_links_" . IMOK_PLUGIN_NAME , ['Emogic_IMOK_Admin' , 'imok_settings_link'] );
//}

class Emogic_IMOK_Admin{

	public static function imok_settings_init(  ) {
		register_setting( 'imok_admin_page', 'imok_admin_settings' ); //string $option_group, string $option_name : we are saving all settings in an array (imok_admin_settings in wp_options contains array)
		add_settings_section(
			'imok_pluginPage_section',
			'imok settings',
			['Emogic_IMOK_Admin' , 'imok_settings_section_callback'],
			'imok_admin_page'
		);
		add_settings_field(
			'imok_from_email_field',
			'From Email',
			['Emogic_IMOK_Admin' , 'imok_from_email_field_render'],
			'imok_admin_page',
			'imok_pluginPage_section'
		);
	}

	public static function imok_settings_section_callback(  ) {
		echo 'Important: This email must exist on your domain to actually send from this email address.';
	}
	
	public static function imok_from_email_field_render(  ) {
		$options = get_option( 'imok_admin_settings' );
		$option1 = $options['imok_from_email_field'];
		echo "<input type='text' name='imok_admin_settings[imok_from_email_field]' value='{$options['imok_from_email_field']}'>";
	}	

	public static function imok_add_admin_menu(  ) {
		add_options_page( 'imok settings', 'imok', 'manage_options', 'imok_settings', ['Emogic_IMOK_Admin' , 'imok_options_page'] );
		//add_options_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $callback = '', int $position = null ):
	}

	public static function imok_options_page(  ) {
		 //ob_start();//allow return with same code
		echo"<form action='options.php' method='post'>";
		settings_fields( 'imok_admin_page' );
		do_settings_sections( 'imok_admin_page' );
		submit_button();
		echo"</form>";
		//return ob_get_clean(); //allow return with same code
	}

	public static function imok_settings_link($links){
			$settings_link = '<a href="admin.php?page=imok_settings">Settings</a>';
			array_push($links , $settings_link);
			return $links;
		}
	
}

?>
