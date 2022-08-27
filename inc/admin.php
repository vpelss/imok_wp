<?php

//namespace inc\admin;

class admin{

//set an admin page and put it in wp admin left menu
	static function add_admin_pages(){
		//add_menu_page( 'imok Plugin' , 'imok' , 'manage_options' , 'imok_plugin' , 'inc\admin\admin::admin_index' , 'dashicons-store' , 110 );
		add_menu_page( 'imok Plugin' , 'imok admin side' , 'manage_options' , 'imok_plugin' , 'admin::admin_index' , 'dashicons-store' , 110 );
	}

	static function admin_index(){//generates html output
		require_once IMOK_PLUGIN_PATH . '/templates/admin.php'; //
	}

	//set up link under plugin on plugin page
	static function settings_link($links){
		//add custom settings link
		$settings_link = '<a href="admin.php?page=imok_plugin">Settings</a>';
		array_push($links , $settings_link);
		return $links;
	}

} //end of admin class

add_action( 'admin_menu' , array('admin' ,'add_admin_pages') ); //set an admin page and put it in wp admin left menu
add_filter( "plugin_action_links_" . IMOK_PLUGIN_NAME , 'admin::settings_link' ); 	//set up link under plugin on plugin page

//the imok fields to be added to user profile page
function wp_usermeta_form_fields_imok( $user )
{
    ?>
	<h2 id="settings_top">IMOK Data</h2>
    <h3>What email(s) would you like to be notified if you are not responsive?</h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="imok_contact_email_1">Contact Email # 1</label>
            </th>
            <td>
                <input type="email"
                       class="regular-text ltr form-required"
                       id="imok_contact_email_1"
                       name="imok_contact_email_1"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'imok_contact_email_1', true ) ) ?>"
                       title="Please enter a valid email address."
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                       required>
            </td>
        </tr>
    </table>
  <table class="form-table">
        <tr>
            <th>
                <label for="imok_contact_email_2">Contact Email # 2</label>
            </th>
            <td>
                <input type="email"
                       class="regular-text ltr"
                       id="imok_contact_email_2"
                       name="imok_contact_email_2"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'imok_contact_email_2', true ) ) ?>"
                       title="Please enter a valid email address."
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                       >
            </td>
        </tr>
    </table>
  <table class="form-table">
        <tr>
            <th>
                <label for="imok_contact_email_3">Contact Email # 3</label>
            </th>
            <td>
                <input type="email"
                       class="regular-text ltr "
                       id="imok_contact_email_3"
                       name="imok_contact_email_3"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'imok_contact_email_3', true ) ) ?>"
                       title="Please enter a valid email address."
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                       >
            </td>
        </tr>
    </table>

	<script type="text/javascript">
		//wp has this turned on???? and there seems no way to show failed sanitized data on server side...
		//maybe try profile_update!
    //var commentForm = document.getElementById('your-profile');
    //commentForm.removeAttribute('novalidate');
</script>

    <?php
}

// Add the imok fields to user's own profile editing screen
add_action( 'show_user_profile', 'wp_usermeta_form_fields_imok' );

// Add the imok fields to user profile editing screen for admins
add_action( 'edit_user_profile', 'wp_usermeta_form_fields_imok' );

//processing and saving of imok fields data submitted from user profile form
function wp_usermeta_form_fields_imok_update( $user_id )
{
    // check that the current user have the capability to edit the $user_id
    if ( ! current_user_can( 'edit_user', $user_id ) ) { return false; }

    // create/update user meta for the $user_id. do not use return as it will stop all further processing! save return as variable for error checking
    update_user_meta( $user_id, 'imok_contact_email_1', is_email( $_POST['imok_contact_email_1'] ) ); //$_POST['imok_contact_email_X']
    update_user_meta( $user_id, 'imok_contact_email_2', is_email( $_POST['imok_contact_email_2'] ) ); //$_POST['imok_contact_email_X']
    update_user_meta( $user_id, 'imok_contact_email_3', is_email( $_POST['imok_contact_email_3'] ) ); //$_POST['imok_contact_email_X']

}

// allows user to update IMOK settings
add_action( 'personal_options_update', 'wp_usermeta_form_fields_imok_update' );

// allows admin to update IMOK settings
add_action( 'edit_user_profile_update', 'wp_usermeta_form_fields_imok_update' );

function my_error_notice() {
					?>
					<div class="error notice">
							<p><?php _e( 'There has been an error. Bummer!', 'my_plugin_textdomain' ); ?></p>
					</div>
					<?php
			}

//check form data submissions for errors
function check_ipp(){
					if( isset($_POST['imok_contact_email_1']) && (! is_email( $_POST['imok_contact_email_1'] )) )
						{
						//WP_Error::add( 'bad' , 'email wrong' , $_POST['imok_contact_email_1'] );

							add_action( 'admin_notices', 'my_error_notice' );

						}
			//add_action( 'admin_notices', 'my_error_notice' );
			}

			add_action( 'posts_selection', 'check_ipp' );

//redirect WP default to our redirect page after login. This will overide 'redirect' => $site_url, in my custom form
function login_redirect( $redirect_to, $request, $user ){
    return home_url(  );
}
add_filter( 'login_redirect', 'login_redirect', 10, 3 );

//disable admin bar
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

//respond to form submissions and redirect giving feedback to user
add_action('admin_post_custom_action_hook', 'process_form');
function process_form() {
//if (!current_user_can('administrator') && !is_admin()) {
  //show_admin_bar(false);
	//}
	$admin_notice = "success";
	wp_redirect( home_url() . "/hjkgjhgjhg"  );
	//wp_redirect( home_url() , 302 , 'ass' );
	exit;
}

//form response to user
add_action( 'admin_post_nds_form_response', 'the_form_response');
function the_form_response() {
	if( isset( $_POST['nds_add_user_meta_nonce'] ) && wp_verify_nonce( $_POST['nds_add_user_meta_nonce'], 'nds_add_user_meta_form_nonce') ) {

		// sanitize the input
		$nds_user_meta_key = sanitize_key( $_POST['nds']['user_meta_key'] );
		$nds_user_meta_value = sanitize_text_field( $_POST['nds']['user_meta_value'] );
		$nds_user =  get_user_by( 'login',  $_POST['nds']['user_select'] );
		$nds_user_id = absint( $nds_user->ID ) ;

		// do the processing

		// add the admin notice
		$admin_notice = "success";

		// redirect the user to the appropriate page
		//$this->custom_redirect( $admin_notice, $_POST );
		//exit;
	}
	else {
		wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
					'response' 	=> 403,
					'back_link' => 'admin.php?page=' . $this->plugin_name,

			) );
	}
}

/* SHORTCODES */

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
			$user = wp_get_current_user();
			if( get_user_meta( $user->ID, 'imok_contact_email_1', true ) == true ){ //we have set up our settings already
				return( "<script>window.location.replace('./logged_in');</script>" );
			}
			else{ //we need to set up our settings. 1st login?
				return( "<script>window.location.replace('./wp-admin/profile.php/#settings_top');</script>" );
			}
		}
		else{
			return( "<script>window.location.replace('./log_in');</script>" );
		}
	}
add_shortcode( 'redirector', 'redirector' );
