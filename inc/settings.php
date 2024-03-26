<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//disable admin bar for users
add_action('after_setup_theme', ['Emogic_IMOK_Settings' , 'remove_admin_bar']);
//the imok fields to be added to user profile page
//these require echo to feed to user page at correct time. return goes to a bit bucket so it does not work here
add_action( 'show_user_profile', ['Emogic_IMOK_Settings' , 'imok_settings_form_echo'] ); // Add the imok fields to user's own profile editing screen
add_action( 'edit_user_profile', ['Emogic_IMOK_Settings' , 'imok_settings_form_echo'] ); // Add the imok fields to user profile editing screen for admins

add_action( 'personal_options_update', ['Emogic_IMOK_Settings' , 'imok_process_form'] ); // user to process IMOK setting changes on their account page. imok_process_form() is in settings.php
add_action( 'edit_user_profile_update', ['Emogic_IMOK_Settings' , 'imok_process_form'] ); // admin to process user's IMOK setting.  imok_process_form() is in settings.php

add_shortcode( 'imok_settings',  ['Emogic_IMOK_Settings' , 'imok_settings_form_nonce'] );//Create nonce fields and then add the user's form fields for the imok-settings page
add_shortcode( 'imok_stay_on_settings_page_checkbox', ['Emogic_IMOK_Settings' , 'imok_stay_on_settings_page_checkbox_function'] );

//respond to form submissions and redirect giving feedback to user
//verify that nonce is valid
add_action('admin_post_imok_process_form', ['Emogic_IMOK_Settings' , 'imok_process_form_nonce']);

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

class Emogic_IMOK_Settings{

	public static function remove_admin_bar() {
		if (!current_user_can('administrator') && !is_admin()) {
			show_admin_bar(false);
			}
		}
			
	public static function imok_settings_form_echo( $user ){
		$html = "<h2 id='settings_top'>IMOK Data Settings Below:</h2><hr>" . self::imok_settings_form($user);
		echo $html;
		}	

	public static function imok_settings_form_nonce(){
		$user = wp_get_current_user();
		return wp_nonce_field( 'imok_process_settings' . $user->ID ) . self::imok_settings_form($user);
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
		
			//$options = get_option( 'imok_admin_settings' );
			//$from_email = $options['imok_from_email_field'];
		
			//$email_from = "From: imok <$from_email>";
			$email_to = $user->user_email;
			$subject = "Your IMOK settings were changed";
			$message = "Your IMOK settings were changed";
			//$headers = $email_from;
			$result = Emogic_IMOK_Email::imok_mail( $email_to , $subject , $message );
		
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

	public static function imok_stay_on_settings_page_checkbox_function(){
			$user = wp_get_current_user();
			$imok_stay_on_settings_page = get_user_meta( $user->ID, 'imok_stay_on_settings_page', true );
			if($imok_stay_on_settings_page == 1){
				return "<input type='checkbox' id='imok_stay_on_settings_page' name='imok_stay_on_settings_page' value='1' checked>
					<label for='imok_stay_on_settings_page'> Stay on this page</label><br>
					</p>";
			}
			else{
				return "<input type='checkbox' id='imok_stay_on_settings_page' name='imok_stay_on_settings_page' value='1'>
					<label for='imok_stay_on_settings_page'> Stay on this page</label><br>
					</p>";
			}
			return ;
	}

	/*
	public static function imok_add_stay_on_settings_page_checkbox(){
		$user = wp_get_current_user();
		$imok_stay_on_settings_page = get_user_meta( $user->ID, 'imok_stay_on_settings_page', true );
		if($imok_stay_on_settings_page){
	
		}
		else{
	
		}
	}
	*/

	//this is newer one shared on imok-settings page via shortcode and in user profile via echo
	public static function imok_settings_form( $user ){ //so we can use same code for edit_user_profile (requires echo o/p) and [shortcode] in settings.php (requires return o/p)
		//$user = wp_get_current_user();
		$imok_contact_email_1 = get_user_meta( $user->ID, 'imok_contact_email_1', true );
		$imok_contact_email_2 = get_user_meta( $user->ID, 'imok_contact_email_2', true );
		$imok_contact_email_3 = get_user_meta( $user->ID, 'imok_contact_email_3', true );
		$imok_email_form = get_user_meta( $user->ID, 'imok_email_form', true );
		$imok_alert_date = get_user_meta( $user->ID, 'imok_alert_date', true );
		$imok_alert_time = get_user_meta( $user->ID, 'imok_alert_time', true );
		$imok_alert_interval = get_user_meta( $user->ID, 'imok_alert_interval', true );
		$imok_pre_warn_time = get_user_meta( $user->ID, 'imok_pre_warn_time', true );
		$imok_timezone =	get_user_meta( $user->ID, 'imok_timezone', true );
		$imok_stay_on_settings_page = get_user_meta( $user->ID, 'imok_stay_on_settings_page', true );
	
		if($imok_email_form == ""){
			$imok_email_form = " This is an example. Please edit it.
	
		Your Name has not reported in to the IMOK service by the scheduled time.
		Please consider checking on them.
		They have pets.
		Phone: xxx-xxx-xxxx
		Email: change_to_your_address@gmail.com
		Contact list:
		- Contact 1
		- Contact 2
		- Contact 3
				";
			}
		
			$html = "
			  <label for='imok_contact_email_1'>What email(s) would you like to be notified if you are not responsive?</label>
				<input type='email'
					class='regular-text ltr form-required'
					id='imok_contact_email_1'
					name='imok_contact_email_1'
					value='$imok_contact_email_1'
					title='Please enter a valid email address.'
					pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$'
					required>
		<p>
			  <label for='imok_contact_email_2'>What email(s) would you like to be notified if you are not responsive?</label>
				<input type='email'
					class='regular-text ltr form-required'
					id='imok_contact_email_2'
					name='imok_contact_email_2'
					value='$imok_contact_email_2'
					title='Please enter a valid email address.'
					pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$'
					>
		<p>
			  <label for='imok_contact_email_3'>What email(s) would you like to be notified if you are not responsive?</label>
				<input type='email'
					class='regular-text ltr form-required'
					id='imok_contact_email_3'
					name='imok_contact_email_3'
					value='$imok_contact_email_3'
					title='Please enter a valid email address.'
					pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$'
					>
		<p>
				<label for='imok_email_form'>
				What email message would you like to send if you are not responsive? (Edit the suggested one to suit)
				</label><br>
				<textarea id='imok_email_form' name='imok_email_form' rows='4' cols='40' required>$imok_email_form</textarea>
		<p>
				<div>On what date &amp; time should your first alert be sent if you are not responsive?</div>
				<label for='imok_alert_date'>Date:</label> <input type='date' name='imok_alert_date' id='imok_alert_date' value='$imok_alert_date' required>
				<label for='imok_alert_time'>Time:</label> <input type='time' id='imok_alert_time' name='imok_alert_time' value='$imok_alert_time' required>
		<p>
		<label for='imok_alert_interval'>How many days after you push the IMOK button would you like to set your next alert date?</label>
		<select name='imok_alert_interval' id='imok_alert_interval' value='$imok_alert_interval' required>
		  <option value='.5'>.5</option>
		  <option value='1' selected>1</option>
		  <option value='2'>2</option>
		  <option value='3'>3</option>
		  <option value='4'>4</option>
		  <option value='5'>5</option>
		  <option value='6'>6</option>
		  <option value='7'>7</option>
		</select>
		<script>document.getElementById('imok_alert_interval').value = '$imok_alert_interval';</script>
		<p>
		<label for='imok_pre_warn_time'>A reminder email will be sent to {$user->user_email} before the alert is sent. How many hours before the alert should this email be sent?</label>
		<select name='imok_pre_warn_time' id='imok_pre_warn_time' value='$imok_pre_warn_time' required>
		  <option value='.5'>.5</option>
		  <option value='1' selected>1</option>
		  <option value='2'>2</option>
		  <option value='3'>3</option>
		  <option value='4'>4</option>
		  <option value='5'>5</option>
		  <option value='6'>6</option>
		  <option value='7'>7</option>
		</select>
		<script>document.getElementById('imok_pre_warn_time').value = '$imok_pre_warn_time';</script>
		<p>
		<label for='imok_timezone'>Timezone in minutes. Do not alter.</label>
		<input type='text' name='imok_timezone' id='imok_timezone' value='$imok_timezone' required>
			";
	
		return $html;
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
	
	public static function imok_contact_email_1_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_contact_email_1', true ) );
		}
		
	public static function imok_contact_email_2_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_contact_email_2', true ) );
		}
		
	public static function imok_contact_email_3_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_contact_email_3', true ) );
		}
		
	public static function imok_email_form_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_email_form', true ) );
		}
		
	public static function imok_alert_date_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_alert_date', true ) );
		}
		
	public static function imok_alert_time_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_alert_time', true ) );
		}
		
	public static function imok_alert_interval_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_alert_interval', true ) );
		}
		
	function imok_pre_warn_time_func(){
			$user = wp_get_current_user();
			return esc_attr( get_user_meta( $user->ID, 'imok_pre_warn_time', true ) );
		}

}
	
?>
