<?php

//respond to form submissions and redirect giving feedback to user
add_action('admin_post_settings_action_hook', 'process_form');
function process_form($user_id) {
	$user = wp_get_current_user();
	//$aa = $_POST;

  update_user_meta( $user->ID , 'imok_contact_email_1' , is_email( $_POST['imok_contact_email_1'] ) ); //$_POST['imok_contact_email_X']
  update_user_meta( $user->ID , 'imok_contact_email_2' , is_email( $_POST['imok_contact_email_2'] ) ); //$_POST['imok_contact_email_X']
  update_user_meta( $user->ID , 'imok_contact_email_3' , is_email( $_POST['imok_contact_email_3'] ) ); //$_POST['imok_contact_email_X']

	update_user_meta( $user->ID , 'imok_email_form' , $_POST['imok_email_form'] ) ;

  update_user_meta( $user->ID , 'imok_start_date' , $_POST['imok_start_date'] ) ;
  update_user_meta( $user->ID , 'imok_start_time' , $_POST['imok_start_time'] );

	update_user_meta( $user->ID , 'imok_timeout' , $_POST['imok_timeout'] );
	update_user_meta( $user->ID , 'imok_pre_warn_time' , $_POST['imok_pre_warn_time'] );

	$admin_notice = "success";
	wp_redirect( home_url() . "/settings"  );

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

	add_shortcode( 'imok_start_date', 'imok_start_date_func' );
function imok_start_date_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_start_date', true ) );
	}

add_shortcode( 'imok_email_form', 'imok_email_form_func' );
function imok_email_form_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_email_form', true ) );
	}

add_shortcode( 'imok_start_time', 'imok_start_time_func' );
function imok_start_time_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_start_time', true ) );
	}

add_shortcode( 'imok_timeout', 'imok_timeout_func' );
function imok_timeout_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_timeout', true ) );
	}

add_shortcode( 'imok_pre_warn_time', 'imok_pre_warn_time_func' );
function imok_pre_warn_time_func(){
		$user = wp_get_current_user();
		return esc_attr( get_user_meta( $user->ID, 'imok_pre_warn_time', true ) );
	}


?>
