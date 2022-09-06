<?php

//login form with shortcode
add_shortcode( 'wp_login_form', 'wp_login_form_func' );
function wp_login_form_func(){
	$site_url =	get_site_url();
	return wp_login_form(
		['echo' => false,	//'redirect' => $site_url,
        'form_id' => 'loginform-custom',
        'label_username' => __( 'Login Email' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'remember' => true]																							);
	};

//wp url after login - redirect WP default to our redirect page after login. This will overide 'redirect' => $site_url, in my custom form
add_filter( 'login_redirect', 'login_redirect', 10, 3 );
function login_redirect( $redirect_to, $request, $user ){
    return home_url(  );
}

//wp logout url : wp_logout_url( string $redirect = '' )
function wp_logout_url_func(){
		return wp_logout_url( get_home_url() );
	}
add_shortcode( 'wp_logout_url', 'wp_logout_url_func' );

?>
