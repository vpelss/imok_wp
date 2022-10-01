<?php
//root page redirect. JS redirect code is returned from our shortcode
//most pages redirect back here. we verify user is logged in and chose which page to redirect to based on that

// [redirector] is only on IMOK Redirector, IMOK Logged In, IMOK Settings

//-If we are logged in
//	IMOK Setting page
//		stay
//	IMOK Redirector page
//		if no settings -> IMOK Settings page
//		if settings -> IMOK Logged In page
//	IMOK Logged In page
//		if no settings -> IMOK Settings page
//		if settings -> IMOK Logged In page
//-If we are not logged in
//	IMOK Redirector page
//		-> IMOK Logged In page
//	IMOK Setting page
//		-> IMOK Logged In page
//	IMOK Logged In page
//		-> IMOK Logged In page

add_shortcode( 'imok_redirector', 'imok_redirector_func' );
function imok_redirector_func(){
	$currentURL = get_permalink();
	$newURL = $currentURL; //assume we are already on the correct page. test this assumption below
	$page = get_page_by_title("IMOK Settings");
	$imokSettingsURL = get_permalink($page->ID);
	if( is_user_logged_in() ){
			$user = wp_get_current_user();
			if( $currentURL != $imokSettingsURL ){ //then we are on IMOK Redirector page or IMOK Logged In page. IMOK Log In should NOT have shortcode!
				if( get_user_meta( $user->ID, 'imok_contact_email_1', true ) == true ) { //we have set up our settings already
					$page = get_page_by_title("IMOK Logged In");
					$newURL = get_permalink($page->ID);
				}
				else{ //we need to set up our settings. 1st login?
					$page = get_page_by_title("IMOK Settings");
					$newURL = get_permalink($page->ID);
				}
			}
		}
		else{
			$page = get_page_by_title("IMOK Log In");
			$newURL = get_permalink($page->ID);
		}
	if($currentURL != $newURL){//only redirect if we are changing pages. compare current URL with redirected one so we don't loop
		return( "<script>window.location.replace('$newURL');</script>" );
	}
}

?>
