<?php

//namespace inc\admin;

class admin{

//set an admin page and put it in wp admin left menu
	static function add_admin_pages(){
		//add_menu_page( 'browser tab text' , 'link text' , 'manage_options' , 'url ? page name' , 'path to html inc\admin\admin::admin_index' , 'dashicons-store' , 110 );
		add_menu_page( 'imok Plugin' , 'IMOK Admin' , 'manage_options' , 'imok_plugin' , 'admin::admin_index' , 'dashicons-store' , 110 );
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

//disable admin bar for users
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
		}
	}

add_action( 'admin_menu' , array('admin' ,'add_admin_pages') ); //set an admin page and put it in wp admin left menu
add_filter( "plugin_action_links_" . IMOK_PLUGIN_NAME , 'admin::settings_link' ); 	//set up link under plugin on plugin page

//the imok fields to be added to user profile page
//these require echo to feed to user page at correct time. return goes to a bit bucket so it does not work here
add_action( 'show_user_profile', 'imok_settings_form_echo' ); // Add the imok fields to user's own profile editing screen
add_action( 'edit_user_profile', 'imok_settings_form_echo' ); // Add the imok fields to user profile editing screen for admins
function imok_settings_form_echo( $user ){
	$html = imok_settings_form($user);
	$html = "<h2 id='settings_top'>IMOK Data Settings Below:</h2><hr>" . $html;
	echo $html;
	}

add_action( 'personal_options_update', 'imok_process_form' ); // allows user to update IMOK settings in their account page
add_action( 'edit_user_profile_update', 'imok_process_form' ); // allows admin to update IMOK settings
/*
function wp_usermeta_form_fields_imok_update( $user_id ){ //processing and saving of imok fields data submitted from user profile form
    if ( ! current_user_can( 'edit_user', $user_id ) ) { return false; }// check that the current user have the capability to edit the $user_id
		imok_process_form(); //from settings.php
}
*/

function my_error_notice() {
					?>
					<div class="error notice">
							<p><?php _e( 'There has been an error. Bummer!', 'my_plugin_textdomain' ); ?></p>
					</div>
					<?php
			}

//check form data submissions for errors
add_action( 'posts_selection', 'check_ipp' );
function check_ipp(){
					if( isset($_POST['imok_contact_email_1']) && (! is_email( $_POST['imok_contact_email_1'] )) )
						{
						//WP_Error::add( 'bad' , 'email wrong' , $_POST['imok_contact_email_1'] );

							add_action( 'admin_notices', 'my_error_notice' );

						}
			//add_action( 'admin_notices', 'my_error_notice' );
			}

//!!!!!!!!!!!!!!!!!!!remove later
/*
function _wp_usermeta_form_fields_imok( $user )
{
    ?>

	<h2 id="settings_top">IMOK Data</h2>
    <h3>What email(s) would you like to be notified if you are not responsive?</h3>

		<input type="email"
                       class="regular-text ltr form-required"
                       id="imok_contact_email_1"
                       name="imok_contact_email_1"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'imok_contact_email_1', true ) ) ?>"
                       title="Please enter a valid email address."
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                       required>

		<input type="email"
                       class="regular-text ltr form-required"
                       id="imok_contact_email_1"
                       name="imok_contact_email_1"
                       value="<?= esc_attr( get_user_meta( $user->ID, 'imok_contact_email_1', true ) ) ?>"
                       title="Please enter a valid email address."
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                       required>


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
*/

?>
