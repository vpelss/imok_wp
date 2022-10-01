<?php
//set login form shortcode and logout URL shortcodes with our redirects

//login form shortcode
add_shortcode( 'wp_login_form', 'imok_login_form_func' );
function imok_login_form_func(){
	$page = get_page_by_title("IMOK Redirector");
	$homeURL = get_permalink($page->ID);
	return wp_login_form(
		['echo' => false,	//'redirect' => $site_url,
		'redirect' => $homeURL,
        'form_id' => 'loginform',
        'label_username' => __( 'Login Email' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'remember' => true,
		'value_remember' => true
		]);
	};

/*
//create a wp logout url and send to shortcode : wp_logout_url( string $redirect = '' ) : redirect to main page on log out
add_shortcode( 'wp_logout_url', 'imok_logout_url_func' );
function imok_logout_url_func(){
		$page = get_page_by_title("IMOK Redirector");
		$homeURL = get_permalink($page->ID);
		return wp_logout_url( $homeURL );
	}
*/

?>
