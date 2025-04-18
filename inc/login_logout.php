<?php
//set login form shortcode and logout URL shortcodes with our redirects

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//login form shortcode
add_shortcode( 'EMOGIC_IMOK_LOGIN', ['Emogic_IMOK_Login_Logout' , 'login_shortcode'] );

//need this for registration login, which likely comes from wp-login.php not our custom form
add_filter( 'login_redirect', ['Emogic_IMOK_Login_Logout' , 'login_redirect'] );

//create a wp logout url and send to shortcode : wp_logout_url( string $redirect = '' ) : redirect to main page on log out
add_shortcode( 'EMOGIC_IMOK_LOGOUT_URL', ['Emogic_IMOK_Login_Logout' , 'log_out_url'] );

add_action( 'login_enqueue_scripts', ['Emogic_IMOK_Login_Logout' , 'my_login_logo'] );

class Emogic_IMOK_Login_Logout{

	public static function login_shortcode(){	
		$page = get_posts( ['post_type' => 'page' , 'title'=> IMOK_MAIN_PAGE] )[0]; 
		
		$homeURL = get_permalink($page->ID);
		$wp_login_form_html = wp_login_form(
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
	
			$imok_root_url = IMOK_ROOT_URL;
			$wp_login_form_html =  $wp_login_form_html . "<p id='nav'>
					<a href='$imok_root_url/wp-login.php?action=register'>Register</a> |	<a href='$imok_root_url/wp-login.php?action=lostpassword'>Lost your password?</a>
				</p>";
	
		return $wp_login_form_html;
	}

	public static function login_redirect() {
		$page = get_posts( ['post_type' => 'page' , 'title'=> IMOK_MAIN_PAGE] )[0]; 
		$homeURL = get_permalink($page->ID);
		return $homeURL ;
	}	

	public static function log_out_url(){
		$page = get_posts( ['post_type' => 'page' , 'title'=> 'IMOK Log In'] )[0]; 
		$homeURL = get_permalink($page->ID);
		return wp_logout_url( $homeURL );
		}

	public static function my_login_logo() {
		echo "
			<style type='text/css'>
			#login h1 a, .login h1 a {
				background-image: url( IMOK_PLUGIN_LOCATION_URL . '/images/imok-logo.svg');
			height:65px;
			width:320px;
			background-size: 320px 65px;
			background-repeat: no-repeat;
				padding-bottom: 30px;
			}
		</style>
		";
		}
		
}

?>
