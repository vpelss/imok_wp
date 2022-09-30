<?php

//respond to form submissions and redirect giving feedback to user
add_action('admin_post_settings_action_hook', 'process_form');
function process_form($user_id) {
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

	$admin_notice = "success";
	$tmp = IMOK_ROOT_URL . "/settings/";
	$page = get_page_by_title("IMOK Redirector");
	$homeURL = get_permalink($page->ID);
	wp_redirect( $homeURL );

	exit;
}

//[shortcodes]

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
