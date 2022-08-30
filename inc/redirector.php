<?php

//root page redirector shortcode

add_shortcode( 'redirector', 'redirector_func' );
function redirector_func(){
		if( is_user_logged_in() ){
			$user = wp_get_current_user();
			if( get_user_meta( $user->ID, 'imok_contact_email_1', true ) == true ){ //we have set up our settings already
				return( "<script>window.location.replace('./logged_in');</script>" );
			}
			else{ //we need to set up our settings. 1st login?
				//return( "<script>window.location.replace('./wp-admin/profile.php/#settings_top');</script>" );
				return( "<script>window.location.replace('./settings');</script>" );
			}
		}
		else{
			return( "<script>window.location.replace('./log_in');</script>" );
		}
	}

?>
