<?php

//set login and logout pages, stiings, and redirects

//login form for shortcode
add_shortcode( 'wp_login_form', 'wp_login_form_func' );
function wp_login_form_func(){
	//$site_url =	get_site_url();
	$page = get_page_by_title("IMOK Redirector");
	$homeURL = get_permalink($page->ID);
	//$homeURL = home_url();
	return wp_login_form(
		['echo' => false,	//'redirect' => $site_url,
		'redirect' => $homeURL,
        'form_id' => 'imok-loginform',
        'label_username' => __( 'Login Email' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'remember' => true,
		'value_remember' => true
		]);
	};

//wp url after login - redirect WP default to our redirect page after login.
// ??? This will overide 'redirect' => $site_url, in my custom form
//add_filter( 'login_redirect', 'login_redirect', 10, 3 );
//function login_redirect( $redirect_to, $request, $user ){
  //  return home_url(  );
//}

//create a wp logout url and send to shortcode : wp_logout_url( string $redirect = '' ) : redirect to main page on log out
add_shortcode( 'wp_logout_url', 'wp_logout_url_func' );
function wp_logout_url_func(){
		$page = get_page_by_title("IMOK Redirector");
		$homeURL = get_permalink($page->ID);
		return wp_logout_url( $homeURL );
		//return wp_logout_url( get_home_url() );
	}

?>
