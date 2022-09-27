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
	$newURL  = $currentURL; //in cases where we need no action
	$page = get_page_by_title("IMOK Redirector");
	$homeURL = get_permalink($page->ID);
	//$homeURL = IMOK_ROOT_URL . "/";
	if( is_user_logged_in() ){
			$user = wp_get_current_user();
			if( $currentURL == $homeURL ){ //we are on main page
				if( get_user_meta( $user->ID, 'imok_contact_email_1', true ) == true ) { //we have set up our settings already
					$page = get_page_by_title("IMOK Logged In");
					$newURL = get_permalink($page->ID);
					//$newURL = $homeURL . 'imok-logged-in/';
				}
				else{ //we need to set up our settings. 1st login?
					$page = get_page_by_title("IMOK Settings");
					$newURL = get_permalink($page->ID);
					//$newURL = $homeURL . 'imok-settings/';
				}
			}
		}
		else{
			$page = get_page_by_title("IMOK Log In");
			$newURL = get_permalink($page->ID);
			//$newURL = $homeURL . 'imok-log-in/';
		}
	if($currentURL != $newURL){
		return( "<script>
			   const d = new Date();
				let timezone= d.getTimezoneOffset();
				//document.getElementById('imok_timezone').value = timezone;
				window.location.replace('$newURL');</script>" );
	}
				//window.location.replace('$newURL' + '?timezone=' + timezone);</script>" ); //add timezome info to update or compare with stored timezone

}

?>
