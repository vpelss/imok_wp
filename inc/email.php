<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

 function imok_mail($email_to , $subject , $message){

    //$email_to = $user->user_email;
	//$subject = "IMOK pre-alert";
    //$message = "Your IMOK Alert will be triggered and sent to your contact list at $imok_alert_date_time_string_local. Stop it by pushing IMOK button at " . IMOK_ROOT_URL;
	$options = get_option( 'imok_admin_settings' );
	$from_email = $options['imok_from_email_field'];
	$headers = "From: imok <$from_email>";
 $result = wp_mail( $email_to , $subject , $message , $headers  );
	return $result;
}


?>
