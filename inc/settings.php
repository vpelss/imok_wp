<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//disable admin bar for users
add_action('after_setup_theme', ['Emogic_IMOK_Settings', 'remove_admin_bar']);

//Create nonce fields and then add the user's form fields for the imok-settings page
add_shortcode('EMOGIC_IMOK_NONCE',  ['Emogic_IMOK_Settings', 'create_form_nonce_shortcode']); 
//add the imok fields to be added to user profile page
//these require echo to feed to user page at correct time. return goes to a bit bucket so it does not work here
add_action('show_user_profile', ['Emogic_IMOK_Settings', 'imok_settings_form_echo']); // Add the imok fields to user's own profile editing screen
add_action('edit_user_profile', ['Emogic_IMOK_Settings', 'imok_settings_form_echo']); // Add the imok fields to user profile editing screen for admins
add_shortcode('EMOGIC_IMOK_GET_SETTINGS_FIELDS', ['Emogic_IMOK_Settings', 'get_settings_fields_shortcode']);

//process IMOK Settings form submissions
//also verify that nonce is valid
//tied to <input type="hidden" name="action" value="imok_process_form">
add_action('admin_post_imok_process_form', ['Emogic_IMOK_Settings', 'imok_process_form_nonce']);
//process admin/user form submissions
add_action('personal_options_update', ['Emogic_IMOK_Settings', 'imok_process_form']); // user to process IMOK setting changes on their personal account page
add_action('edit_user_profile_update', ['Emogic_IMOK_Settings', 'imok_process_form']); // admin to process user's IMOK setting

//generic field shorcode. uses [EMOGIC_IMOK_FIELD name='imok_contact_email_3']
add_shortcode('EMOGIC_IMOK_FIELD', ['Emogic_IMOK_Settings', 'imok_field_shortcode']);
add_shortcode('EMOGIC_IMOK_ROOT_URL', ['Emogic_IMOK_Settings', 'root_url_shortcode']); //used in form action

class Emogic_IMOK_Settings
{
	static public $useris;
	static public $fields_array = ['imok_contact_email_1', 'imok_contact_email_2', 'imok_contact_email_3', 'imok_email_message', 'imok_alert_date', 'imok_alert_time', 'imok_alert_interval', 'imok_pre_warn_time', 'imok_timezone', 'imok_stay_on_settings_page'];
	static public $fields_array_type = ["imok_contact_email_1" => "email", "imok_contact_email_2" => "email", "imok_contact_email_3" => "email", "imok_email_message" => "textarea", "imok_alert_date" => "text", "imok_alert_time" => "text", "imok_alert_interval" => "text", "imok_pre_warn_time" => "text", "imok_timezone" => "text", "imok_stay_on_settings_page" => "text"];


	public static function remove_admin_bar()
	{
		if (!current_user_can('administrator') && !is_admin()) {
			show_admin_bar(false);
		}
	}

	public static function create_form_nonce_shortcode()
	{
		$user = wp_get_current_user();
		return wp_nonce_field('imok_process_settings' . $user->ID); // value creates a unique nonce hash?
	}

	public static function imok_settings_form_echo($user) //user comes from add_action
	{
		$html = "<h2 id='settings_top'>IMOK Data Settings Below:</h2><hr>" . self::imok_settings_form($user);
		echo $html;
	}

	public static function imok_settings_form($user)
	{ //so we can use same code for edit_user_profile (requires echo o/p) and [shortcode] in settings.php (requires return o/p)
		if (is_admin()) { //if we are on admin page we are likely looking up a client's account
			self::$useris = $user;
		} else {
			self::$useris = null;
		}
		$posts = get_posts(
			array(
				'post_type'              => 'page',
				'title'                  => 'IMOK Settings Fields',
				'post_status'            => 'all',
			)
		);
		$page_got_by_title = null;
		if (! empty($posts)) {
			$page_got_by_title = $posts[0];
		}

		$textme = $page_got_by_title->post_content;
		$textme = do_shortcode($textme);
		return $textme;
	}

	public static function get_settings_fields_shortcode()
	{
		$posts = get_posts(
			array(
				'post_type'              => 'page',
				'title'                  => 'IMOK Settings Fields',
				'post_status'            => 'all',
			)
		);
		$page_got_by_title = null;
		if (! empty($posts)) {
			$page_got_by_title = $posts[0];
		}
		$textme = $page_got_by_title->post_content;
		$textme = do_shortcode($textme);
		return $textme;
	}

	public static function imok_process_form_nonce()
	{
		$user = wp_get_current_user();
		if (! check_admin_referer('imok_process_settings' . $user->ID)) {
				return;
		}
		self::imok_process_form($user->ID);
	}

	public static function imok_process_form($user_id)
	{
		if (!current_user_can('edit_user', $user_id)) {
			return;
		}

		//commands here?


		//get all field values and store in user_meta
		foreach (self::$fields_array as $field_name) {
			if (isset($_POST[$field_name])) {
				$field_type = self::$fields_array_type[$field_name];
				$value = $_POST[$field_name];
				$result = self::set_and_sanitize_field($user_id, $field_name, $field_type, $value);
			}
		}

		$user = get_userdata($user_id); 
		//email user that settings have changed
		require_once IMOK_PLUGIN_PATH . 'inc/email.php';
		$template_page_name = 'IMOK Email Settings Changed';
		$email_to_str = $user->user_email;
		$result = Emogic_IMOK_Email::template_mail($email_to_str, $template_page_name);

		if (isset($_POST['imok_stay_on_settings_page']) and ($_POST['imok_stay_on_settings_page'] == 'checked')) {
			$homeURL = $_POST['imok_current_url']; //curent full URL 
		} else { // imok_stay_on_settings_page is not set. set it
			$page = get_posts(['post_type' => 'page', 'title' => IMOK_MAIN_PAGE])[0];
			$homeURL = get_permalink($page->ID);
			//set imok_stay_on_settings_page to anything but 'checked' or false value
			$field = 'imok_stay_on_settings_page';
			$result = self::set_and_sanitize_field($user_id, $field, self::$fields_array_type[$field], 'unchecked');
		}
		wp_redirect($homeURL);
		return 1; // MUST be return, not exit, after redirect or all the user data changed on admin scren will not be processed
	}

	public static function imok_field_shortcode($atts = [], $content = null, $tag = '')
	{
		$user = self::which_user();
		$field_name = $atts['name'];
		$field_type = self::$fields_array_type[$field_name];
		$value = self::get_and_sanitize_field($user->ID, $field_name, $field_type);
		return $value;
	}

	public static function root_url_shortcode()
	{
		$imok_root_url = IMOK_ROOT_URL;
		return $imok_root_url;
	}

	public static function which_user()
	{
		$user = wp_get_current_user();
		if (isset(self::$useris)) { //we are admin and looking up client
			$user = self::$useris;
		}
		return $user;
	}

	public static function set_and_sanitize_field($user_id, $field, $type, $value)
	{
		$user = self::which_user();
		if (isset($value)) {
			if ($type == 'email')
				$result = update_user_meta($user_id, $field, is_email($value));
			elseif ($type == 'text')
				$result = update_user_meta($user_id, $field, sanitize_text_field($value));
			elseif ($type == 'textarea') {
				$var = sanitize_textarea_field($value);
				$result = update_user_meta($user_id, $field, sanitize_textarea_field($value));
			}
		} else {
			$result = false;
		}
		return $result;
	}

	public static function get_and_sanitize_field($user_id, $field, $type)
	{
		$user = self::which_user();
		if ($type == 'email')
			$result = sanitize_email(get_user_meta($user_id, $field, true));
		elseif ($type == 'text')
			$result = sanitize_text_field(get_user_meta($user_id, $field, true));
		elseif ($type == 'textarea') {
			$val = get_user_meta($user_id, $field, true);
			$val2 = sanitize_textarea_field($val);
			$result = sanitize_textarea_field(get_user_meta($user_id, $field, true));
		}
		return $result;
	}

		/*
	public static function cleanup_shortcode($val)
	{
		//was a kludge for wp ticket #60948
		// https://core.trac.wordpress.org/ticket/60948
		//if shortcode reurns nothing in tag value attibute : mayhem
		if ($val == "") { 
			$val = '""';
			
		}
		return  $val;
	}
	*/
}

