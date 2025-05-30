<?php
/*

 [redirector] is only found on IMOK & IMOK Settings

 redirect from wrong page to correct page. 
 JS redirect code is returned from our shortcode, and also also shows busy flash icon
 see if user is logged in and chose which page to redirect to based on that

-If we are logged in
	IMOK Setting page
		do nothing
	IMOK Logged In page
		if user has no settings -> IMOK Settings page
		if user has settings -> stay
-If we are not logged in
	IMOK Setting page
		-> IMOK Log In page
	IMOK Logged In page
		-> IMOK Log In page

*/

if (! defined('ABSPATH')) {
	exit($staus = 'ABSPATH not defn');
} //exit if directly accessed

add_shortcode('EMOGIC_IMOK_REDIRECTOR', ['Emogic_IMOK_Redireector', 'imok_redirector_func']);

class Emogic_IMOK_Redireector
{

	public static function imok_redirector_func()
	{
		$currentURL = get_permalink();
		$newURL = $currentURL; //assume we are already on the correct page. test this assumption below
		//$imokSettingsURL to IMOK Settings page
		$page = get_posts(['post_type' => 'page', 'title' => 'IMOK Settings'])[0];
		$imokSettingsURL = get_permalink($page->ID);
		if (is_user_logged_in()) {
			if ($currentURL != $imokSettingsURL) { //if not on IMOK-Settings see if we should be
				$user = wp_get_current_user();

				//check all fields for values
				$all_fields_have_values = 1;
				$fields_array = Emogic_IMOK_Settings::$fields_array;
				foreach ($fields_array as $field_name) {
					if (! get_user_meta($user->ID, $field_name, true) ) {
						$all_fields_have_values = 0;
					}
				}

				//if( get_user_meta( $user->ID, 'imok_contact_email_1', true ) == true ) { //we have set up our settings already
				if ($all_fields_have_values) { //we have set up our settings already
					$page = get_posts(['post_type' => 'page', 'title' => IMOK_MAIN_PAGE])[0];
					$newURL = get_permalink($page->ID);
				} else { //we need to set up our settings. 1st login?
					$page = get_posts(['post_type' => 'page', 'title' => 'IMOK Settings'])[0];
					$newURL = get_permalink($page->ID);
				}
			}
		} else {
			$page = get_posts(['post_type' => 'page', 'title' => 'IMOK Log In'])[0];
			$newURL = get_permalink($page->ID);
		}
		if ($currentURL != $newURL) { //only redirect if we are changing pages. compare current URL with redirected one so we don't loop		
			$string = "<center>We are not logged in we will redirect you to the login page.</center>
			<script>
			imok_menu_spinner();
			window.location.replace('$newURL');
			</script>
			";
			return ($string);
		}
	}
}
