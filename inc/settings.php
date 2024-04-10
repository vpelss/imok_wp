<?php

//if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//respond to form submissions and redirect giving feedback to user
//verify that nonce is valid
add_action('admin_post_imok_process_form', ['Emogic_IMOK_Settings' , 'imok_process_form_nonce']);

//disable admin bar for users
add_action('after_setup_theme', ['Emogic_IMOK_Settings' , 'remove_admin_bar']);
//the imok fields to be added to user profile page
//these require echo to feed to user page at correct time. return goes to a bit bucket so it does not work here
add_action( 'show_user_profile', ['Emogic_IMOK_Settings' , 'imok_settings_form_echo'] ); // Add the imok fields to user's own profile editing screen
add_action( 'edit_user_profile', ['Emogic_IMOK_Settings' , 'imok_settings_form_echo'] ); // Add the imok fields to user profile editing screen for admins
add_shortcode( 'imok_get_setting_fields', ['Emogic_IMOK_Settings' , 'imok_get_settings_fields_func'] );

add_action( 'personal_options_update', ['Emogic_IMOK_Settings' , 'imok_process_form'] ); // user to process IMOK setting changes on their account page. imok_process_form() is in settings.php
add_action( 'edit_user_profile_update', ['Emogic_IMOK_Settings' , 'imok_process_form'] ); // admin to process user's IMOK setting.  imok_process_form() is in settings.php

//add_shortcode( 'imok_settings',  ['Emogic_IMOK_Settings' , 'imok_create_form_nonce'] );//Create nonce fields and then add the user's form fields for the imok-settings page
add_shortcode( 'imok_nonce',  ['Emogic_IMOK_Settings' , 'imok_create_form_nonce'] );//Create nonce fields and then add the user's form fields for the imok-settings page
add_shortcode( 'imok_stay_on_settings_page_checked', ['Emogic_IMOK_Settings' , 'imok_stay_on_settings_page_checkbox_function'] );

//these [shortcodes] were used on the templates/Old_Setting_Form.html and can still be used if useful
add_shortcode( 'imok_root_url', ['Emogic_IMOK_Settings' , 'imok_root_url_func'] );

add_shortcode( 'imok_contact_email_1', ['Emogic_IMOK_Settings' , 'imok_contact_email_1_func'] );
add_shortcode( 'imok_contact_email_2', ['Emogic_IMOK_Settings' , 'imok_contact_email_2_func'] );
add_shortcode( 'imok_contact_email_3', ['Emogic_IMOK_Settings' , 'imok_contact_email_3_func'] );
add_shortcode( 'imok_email_form', ['Emogic_IMOK_Settings' , 'imok_email_form_func'] );
add_shortcode( 'imok_alert_date', ['Emogic_IMOK_Settings' , 'imok_alert_date_func'] );
add_shortcode( 'imok_alert_time', ['Emogic_IMOK_Settings' , 'imok_alert_time_func'] );
add_shortcode( 'imok_alert_interval', ['Emogic_IMOK_Settings' , 'imok_alert_interval_func'] );
add_shortcode( 'imok_pre_warn_time', ['Emogic_IMOK_Settings' , 'imok_pre_warn_time_func'] );
add_shortcode( 'imok_timezone', ['Emogic_IMOK_Settings' , 'imok_timezone_func'] );
add_shortcode( 'EMOGIC_IMOK_CURRENT_USER_EMAIL', ['Emogic_IMOK_Settings' , 'EMOGIC_IMOK_CURRENT_USER_EMAIL_func'] );

class Emogic_IMOK_Settings{
	
	static public $useris;
	
	public static function imok_timezone_func(){
		$user = wp_get_current_user();
		return 	get_user_meta( $user->ID, 'imok_timezone', true );
	}

	public static function remove_admin_bar() {
		if (!current_user_can('administrator') && !is_admin()) {
			show_admin_bar(false);
			}
		}
			
	public static function imok_settings_form_echo( $user ){
		$html = "<h2 id='settings_top'>IMOK Data Settings Below:</h2><hr>" . self::imok_settings_form($user);
		echo $html;
		}	
	
	public static function imok_settings_form( $user ){ //so we can use same code for edit_user_profile (requires echo o/p) and [shortcode] in settings.php (requires return o/p)
		if( is_admin() ){//if we are on admin page we are likely looking up a client's account
			self::$useris = $user;
			}
		else{
			self::$useris = null;
		}
		$posts = get_posts(
		array( 'post_type'              => 'page',
			'title'                  => 'IMOK Settings Fields',
			'post_status'            => 'all', ) );
		$page_got_by_title = null;		 
		if ( ! empty( $posts ) ) {
			$page_got_by_title = $posts[0];
		}
		
		$textme = $page_got_by_title->post_content;
		$textme = do_shortcode( $textme );
		return $textme;
		}
		
		//DUPLICATE????
		public static function imok_get_settings_fields_func(){
			$posts = get_posts(
			array( 'post_type'              => 'page',
				'title'                  => 'IMOK Settings Fields',
				'post_status'            => 'all', ) );
			$page_got_by_title = null;
			if ( ! empty( $posts ) ) {
				$page_got_by_title = $posts[0];
			}
			$textme = $page_got_by_title->post_content;
			$textme = do_shortcode( $textme );
			return $textme;
		}
	
	public static function imok_process_form($user_id) {
			$user = get_userdata($user_id);
		
			update_user_meta( $user->ID , 'imok_timezone' ,  $_POST['imok_timezone'] ); //$_POST['imok_timezone'] in minutes
		
			update_user_meta( $user->ID , 'imok_contact_email_1' , is_email( $_POST['imok_contact_email_1'] ) ); //$_POST['imok_contact_email_X']
			update_user_meta( $user->ID , 'imok_contact_email_2' , is_email( $_POST['imok_contact_email_2'] ) ); //$_POST['imok_contact_email_X']
			update_user_meta( $user->ID , 'imok_contact_email_3' , is_email( $_POST['imok_contact_email_3'] ) ); //$_POST['imok_contact_email_X']
		
			update_user_meta( $user->ID , 'imok_email_form' , $_POST['imok_email_form'] ) ;
		
			update_user_meta( $user->ID , 'imok_alert_date' , $_POST['imok_alert_date'] ) ;
			update_user_meta( $user->ID , 'imok_alert_time' , $_POST['imok_alert_time'] );
		
			update_user_meta( $user->ID , 'imok_alert_interval' , $_POST['imok_alert_interval'] );
			update_user_meta( $user->ID , 'imok_pre_warn_time' , $_POST['imok_pre_warn_time'] );
			//$r = $_POST['imok_stay_on_settings_page'] ;
			if(! isset($_POST['imok_stay_on_settings_page'])){
				$_POST['imok_stay_on_settings_page'] = 0;
			}
			update_user_meta( $user->ID , 'imok_stay_on_settings_page' , $_POST['imok_stay_on_settings_page'] );
		
			//email user that settings have changed
			$template_page_name = 'IMOK Email Settings Changed';
			$email_to_str = $user->user_email;
			$result = Emogic_IMOK_Email::template_mail($email_to_str , $template_page_name);
		
			//$admin_notice = "success"; //???
			if( $_POST['imok_stay_on_settings_page'] ) {
				$page = get_posts( ['post_type' => 'page' , 'title'=> 'IMOK Settings'] )[0];
				} 
			else {
				$page = get_posts( ['post_type' => 'page' , 'title'=> 'IMOK Logged In'] )[0];
				} 
			$homeURL = get_permalink($page->ID);
			wp_redirect( $homeURL );
			return(1);
	}

	public static function imok_create_form_nonce(){
		$user = wp_get_current_user();
		return wp_nonce_field( 'imok_process_settings' . $user->ID ); // . self::imok_settings_form($user);
		//return wp_nonce_field( 'imok_process_settings' . $user->ID ) . self::imok_settings_form($user);
	}

	public static function imok_process_form_nonce(){
		$user = wp_get_current_user();
		if ( ! check_admin_referer( 'imok_process_settings' . $user->ID ) ) {	return;	}
		self::imok_process_form( $user->ID);
	}
	
	public static function imok_root_url_func(){
		$imok_root_url = IMOK_ROOT_URL;
		return $imok_root_url;
		}
	
	public static function cleanup_shortcode($val){
		if( $val == "" ){ //kludge for wp ticket #60948
			$val = '""';		
		}
		return  $val ;
	}

	public static function which_user(){
		$user = wp_get_current_user();	
		if( isset(self::$useris) ){ //we are admin and looking up client
				$user = self::$useris;
			}
		return $user;
	}
	
	public static function imok_contact_email_1_func(){
		$user = self::which_user();	
		return self::cleanup_shortcode( sanitize_email( get_user_meta( $user->ID, 'imok_contact_email_1', true ) ) );
		}
		
	public static function imok_contact_email_2_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_email( get_user_meta( $user->ID, 'imok_contact_email_2', true ) ) );
		}
		
	public static function imok_contact_email_3_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_email( get_user_meta( $user->ID, 'imok_contact_email_3', true ) ) );
		}
		
	public static function imok_email_form_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_text_field( get_user_meta( $user->ID, 'imok_email_form', true ) ) );
		}
		
	public static function imok_alert_date_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_text_field( get_user_meta( $user->ID, 'imok_alert_date', true ) ) );
		}
		
	public static function imok_alert_time_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_text_field( get_user_meta( $user->ID, 'imok_alert_time', true ) ) );
		}
		
	public static function imok_alert_interval_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_text_field( get_user_meta( $user->ID, 'imok_alert_interval', true ) ) );
		}
		
	public static function imok_pre_warn_time_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_text_field( get_user_meta( $user->ID, 'imok_pre_warn_time', true ) ) );
		}
		
	public static function EMOGIC_IMOK_CURRENT_USER_EMAIL_func(){
		$user = self::which_user();
		return self::cleanup_shortcode( sanitize_email( $user->user_email ) );
		}


	public static function imok_stay_on_settings_page_checkbox_function($user){
		$user = self::which_user();
		$imok_stay_on_settings_page = get_user_meta( $user->ID, 'imok_stay_on_settings_page', true );
		if($imok_stay_on_settings_page == 1){
			return "checked";
		}
		else{
			return "";
		}
		return ;
	}
	
	
	
}
	
?>
