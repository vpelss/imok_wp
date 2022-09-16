<?php

//root page redirector shortcode

//compare current URL with redirected one so we don't loop
//-logged in
//	-main -> no settings -> settings
//	-main -> settings -> /logged_in
//	-settings -> settings
//-not logged in
//	-main -> login
//	-settings -> login
//	-logged_in -> login

add_shortcode( 'redirector', 'redirector_func' );
function redirector_func(){
	$currentURL = get_permalink();
	$newURL  = get_permalink(); //in cases where we need no action
	$homeURL = IMOK_ROOT_URL . "/";
	if( is_user_logged_in() ){
			$user = wp_get_current_user();
			if( $currentURL == $homeURL ){ //we are on main page
				if( get_user_meta( $user->ID, 'imok_contact_email_1', true ) == true ) { //we have set up our settings already
					$newURL = $homeURL . 'logged_in/';
				}
				else{ //we need to set up our settings. 1st login?
					$newURL = $homeURL . 'settings/';
				}
			}
		}
		else{
			$newURL = $homeURL . 'log_in/';
		}
	if($currentURL != $newURL){
		return( "<script>window.location.replace('" . $newURL . "');</script>" );
	}

	}

?>
