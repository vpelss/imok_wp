<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

function imok_email($email_to , $subject , $message){

	$email_from = "From: imok <$from_email>";
    $email_to = $user->user_email;
	$subject = "IMOK pre-alert";
    $message = "Your IMOK Alert will be triggered and sent to your contact list at $imok_alert_date_time_string_local. Stop it by pushing IMOK button at " . IMOK_ROOT_URL;
    $headers = $email_from;
    $result = wp_mail( $email_to , $subject , $message , $headers  );

}


?>
