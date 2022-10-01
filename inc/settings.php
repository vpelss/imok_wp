<?php

//this is newer one shared on imok-settings page and (not the shorcode part) in user profile
add_shortcode( 'imok_settings', 'imok_settings_form' );

function imok_settings_form(){ //so we can use same code for edit_user_profile (requires echo o/p) and [shortcode] in settings.php (requires return o/p)
	$user = wp_get_current_user();
	$imok_contact_email_1 = get_user_meta( $user->ID, 'imok_contact_email_1', true );
	$imok_contact_email_2 = get_user_meta( $user->ID, 'imok_contact_email_2', true );
	$imok_contact_email_3 = get_user_meta( $user->ID, 'imok_contact_email_3', true );
	$imok_email_form = get_user_meta( $user->ID, 'imok_email_form', true );
	$imok_alert_date = get_user_meta( $user->ID, 'imok_alert_date', true );
	$imok_alert_time = get_user_meta( $user->ID, 'imok_alert_time', true );
	$imok_alert_interval = get_user_meta( $user->ID, 'imok_alert_interval', true );
	$imok_pre_warn_time = get_user_meta( $user->ID, 'imok_pre_warn_time', true );

	$html = "
		<input type='hidden' name='imok_timezone' id='imok_timezone'>

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
			required>
<p>
	  <label for='imok_contact_email_3'>What email(s) would you like to be notified if you are not responsive?</label>
		<input type='email'
			class='regular-text ltr form-required'
			id='imok_contact_email_3'
			name='imok_contact_email_3'
			value='$imok_contact_email_3'
			title='Please enter a valid email address.'
			pattern='[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$'
			required>
<p>
		<label for='imok_email_form'>
		What email message would you like to send if you are not responsive? (Edit the suggested one to suit)
		</label><br>
		<textarea id='imok_email_form' name='imok_email_form' rows='4' cols='40'>$imok_email_form</textarea>
<p>
		<div>On what date &amp; time should your first alert be sent if you are not responsive?</div>
		<label for='imok_alert_date'>Date:</label> <input type='date' name='imok_alert_date' id='imok_alert_date' value='$imok_alert_date'>
		<label for='imok_alert_time'>Time:</label> <input type='time' id='imok_alert_time' name='imok_alert_time' value='$imok_alert_time'>
<p>
<label for='imok_alert_interval'>How many days after you push the IMOK button would you like to set your next alert date?</label>
<select name='imok_alert_interval' id='imok_alert_interval' value='$imok_alert_interval'>
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
<label for='imok_pre_warn_time'>A reminder email will be sent to your email address before the alert is sent. How many hours before the alert should this email be sent?</label>
<select name='imok_pre_warn_time' id='imok_pre_warn_time' value='$imok_pre_warn_time'>
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

	";

	return $html;
	}

//respond to form submissions and redirect giving feedback to user
add_action('admin_post_settings_action_hook', 'imok_process_form');
function imok_process_form() {
	$user = wp_get_current_user();

	update_user_meta( $user->ID , 'imok_timezone' ,  $_POST['imok_timezone'] ); //$_POST['imok_timezone'] in minutes

  update_user_meta( $user->ID , 'imok_contact_email_1' , is_email( $_POST['imok_contact_email_1'] ) ); //$_POST['imok_contact_email_X']
  update_user_meta( $user->ID , 'imok_contact_email_2' , is_email( $_POST['imok_contact_email_2'] ) ); //$_POST['imok_contact_email_X']
  update_user_meta( $user->ID , 'imok_contact_email_3' , is_email( $_POST['imok_contact_email_3'] ) ); //$_POST['imok_contact_email_X']

	update_user_meta( $user->ID , 'imok_email_form' , $_POST['imok_email_form'] ) ;

  update_user_meta( $user->ID , 'imok_alert_date' , $_POST['imok_alert_date'] ) ;
  update_user_meta( $user->ID , 'imok_alert_time' , $_POST['imok_alert_time'] );

	update_user_meta( $user->ID , 'imok_alert_interval' , $_POST['imok_alert_interval'] );
	update_user_meta( $user->ID , 'imok_pre_warn_time' , $_POST['imok_pre_warn_time'] );

	$email_from = 'From: imok <imok@emogic.com>';
	$email_to = $user->user_email;
	$subject = "Your IMOK settings were changed";
	$message = "Your IMOK settings were changed";
	$headers = $email_from;
	$result = wp_mail( $email_to , $subject , $message , $headers  );

	$admin_notice = "success"; //???
	$tmp = IMOK_ROOT_URL . "/settings/";
	$page = get_page_by_title("IMOK Redirector");
	$homeURL = get_permalink($page->ID);
	wp_redirect( $homeURL );

	return(1);
	//exit;
}

//[shortcodes]

//these were used on the templates/Old_Setting_Form.html and can still be used if useful
add_shortcode( 'imok_contact_email_1', 'imok_contact_email_1_func' );
function imok_contact_email_1_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_contact_email_1', true ) );
	}
add_shortcode( 'imok_contact_email_2', 'imok_contact_email_2_func' );
function imok_contact_email_2_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_contact_email_2', true ) );
	}
add_shortcode( 'imok_contact_email_3', 'imok_contact_email_3_func' );
function imok_contact_email_3_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_contact_email_3', true ) );
	}
add_shortcode( 'imok_email_form', 'imok_email_form_func' );
function imok_email_form_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_email_form', true ) );
	}
add_shortcode( 'imok_alert_date', 'imok_alert_date_func' );
function imok_alert_date_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_alert_date', true ) );
	}
add_shortcode( 'imok_alert_time', 'imok_alert_time_func' );
function imok_alert_time_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_alert_time', true ) );
	}
add_shortcode( 'imok_alert_interval', 'imok_alert_interval_func' );
function imok_alert_interval_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_alert_interval', true ) );
	}
add_shortcode( 'imok_pre_warn_time', 'imok_pre_warn_time_func' );
function imok_pre_warn_time_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_pre_warn_time', true ) );
	}

?>
