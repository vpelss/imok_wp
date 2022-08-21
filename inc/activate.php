<?php

class activate{

	public function activate_plugin(){
		flush_rewrite_rules();
	}

	static function custom_post_type(){
		register_post_type( 'imok_posts' , ['public' => true , 'label' => 'IMOK_POSTS'] );
	}

}

register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'activate' , 'activate_plugin') );

add_action( 'init' , 'activate::custom_post_type' );

//login mods
// custom login for theme
function childtheme_custom_login() {
    echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/customlogin.css" />';
}

add_action('login_head', 'childtheme_custom_login');

/**
 * Custom login image URL
 */
function my_custom_login_url( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_custom_login_url', 10, 1 );

//redirect WP default redirect after login. This will overide 'redirect' => $site_url, in my custom form
function login_redirect( $redirect_to, $request, $user ){
    return home_url(  );
}
add_filter( 'login_redirect', 'login_redirect', 10, 3 );


/* SHORTCODES */

//disable admin bar
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

//for our 100% login form
function wp_login_form_funct(){

				$site_url =	get_site_url();

				return wp_login_form(
								['echo' => false,
								//'redirect' => $site_url,
        'form_id' => 'loginform-custom',
        'label_username' => __( 'Username custom text' ),
        'label_password' => __( 'Password custom text' ),
        'label_remember' => __( 'Remember Me custom text' ),
        'label_log_in' => __( 'Log In custom text' ),
        'remember' => true]
																									);
	};
	add_shortcode( 'wp_login_form', 'wp_login_form_funct' );

//wp logout url : wp_logout_url( string $redirect = '' )
function wp_logout_url_funct(){
		return wp_logout_url( get_home_url() );
	}
add_shortcode( 'wp_logout_url', 'wp_logout_url_funct' );

//root page redirector
function redirector(){
		if( is_user_logged_in() ){
			return( "<script>window.location.replace('./logged_in');</script>" );
		}
		else{
			return( "<script>window.location.replace('./log_in');</script>" );
		}
	}
add_shortcode( 'redirector', 'redirector' );
