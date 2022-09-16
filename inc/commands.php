<?php

add_shortcode( 'imok_commands', 'imok_commands_func' );
function imok_commands_func(){
	$user = wp_get_current_user();

	if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$response = $_POST['command'];
	}
	else{
		$response = $_GET['command'];
	}

	if($response == 'imok'){
		return imok_pushed();
	}

	if($response == 'imnotok'){
		return imnotok();
	}

	}

function imnotok(){
	$user = wp_get_current_user();

	$email_from = 'From: imok <imok@emogic.com>';
	$email_to = array();
	array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_1', true ) );
	array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_2', true ) );
	array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_3', true ) );
	$email_to_str = implode( " : ", $email_to );

	$subject = "IM Not OK";
	$message = $user->display_name . ' ' . $user->user_email . " pushed the IM Not OK button. Please check on them.";
	$headers = $email_from;
	$result = wp_mail( $email_to , $subject , $message , $headers  );
	return "IM Not OK Alert sent to your contact list:<br> {$email_to_str} <br><br>The following was sent to your contact list:<br>{$message}";
	}

function imok_pushed(){
	$user = wp_get_current_user();
	$unix_day = 60 * 60 * 24; //seconds in a day

	//get current unix time
	$now = current_time("timestamp" , 0); //in unix time no gmt

	//get users data
	$imok_alert_interval_unix_time = $unix_day * get_user_meta( $user->ID, 'imok_alert_interval', true );
	$imok_alert_date =  get_user_meta( $user->ID, 'imok_alert_date', true );
	$imok_alert_time = get_user_meta( $user->ID, 'imok_alert_time', true );
	//convert user settings to unix time
	$imok_alert_date_time_string = $imok_alert_date . ' ' . $imok_alert_time;
	$imok_alert_unix_time = strtotime( $imok_alert_date_time_string ); //convert to unix time

	//compare and reset alert time
	if( ($imok_alert_unix_time - $imok_alert_interval_unix_time) > $now  ){# we are a full alert interval before the alert date time. do nothing
		//return 1;
		}#do nothing
	if($imok_alert_unix_time <= $now){#alarm was/is triggered
		while( $imok_alert_unix_time <= $now ){
			$imok_alert_unix_time = $imok_alert_unix_time + $imok_alert_interval_unix_time;
		};
		$msg = "You had not responded by the Alert time. An alert was likely sent out. Please let your contacts know you are all right.";
	}
	elseif( ($imok_alert_unix_time - $imok_alert_interval_unix_time) <= $now ){# we are clicking just before alarm will trigger in the window of the alert interval
		$imok_alert_unix_time = $imok_alert_unix_time + $imok_alert_interval_unix_time; //one ping please
	}

	//set in db
	$imok_alert_date = date("Y-m-d"  , $imok_alert_unix_time); //convert to string
	update_user_meta( $user->ID , 'imok_alert_date' , $imok_alert_date ) ;
	$imok_alert_time = date("H:i" , $imok_alert_unix_time); //convert to string
	update_user_meta( $user->ID , 'imok_alert_time' , $imok_alert_time ) ;

	//Send email to ? user

	//return and display message
	$now_str = date( "Y-m-d H:i", $now);
	$new_alert_date_time = date( $imok_alert_date . " " . $imok_alert_time , $imok_alert_unix_time);
	return "{$msg}<br>Start alert time: {$imok_alert_date_time_string}<br>Now: {$now_str}<br>New alert time: {$new_alert_date_time}";

}

?>
