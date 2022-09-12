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
		return 'I am not OK';
	}

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
	//$to = "";
	//wp_mail( string|string[] $to, string $subject, string $message, string|string[] $headers = '', string|string[] $attachments = array() ): bool


	//return and display message
	$now_str = date( "Y-m-d H:i", $now);
	$new_alert_date_time = date( $imok_alert_date . " " . $imok_alert_time , $imok_alert_unix_time);
	return $msg . '<br>Start alert time: ' . $imok_alert_date_time_string . '<br>Now: ' . $now_str . '<br>New alert time: ' . $new_alert_date_time ;

}

?>
