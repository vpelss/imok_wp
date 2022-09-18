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
		return imok();
	}
	elseif($response == 'imnotok'){
		return imnotok();
	}
	elseif(1){//no command calculate and show countdown
		return countdown();
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

function imok(){
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

function countdown(){
	$user = wp_get_current_user();
	$unix_day = 60 * 60 * 24; //seconds in a day

	//get current unix time
	$now = current_time("timestamp" , 0); //in unix time no gmt

	//get users data
	//$imok_alert_interval_unix_time = $unix_day * get_user_meta( $user->ID, 'imok_alert_interval', true );
	$imok_alert_date =  get_user_meta( $user->ID, 'imok_alert_date', true );
	$imok_alert_time = get_user_meta( $user->ID, 'imok_alert_time', true );
	//convert user settings to unix time
	$imok_alert_date_time_string = $imok_alert_date . ' ' . $imok_alert_time;
	$imok_alert_unix_time = strtotime( $imok_alert_date_time_string ); //convert to unix time

	if($imok_alert_unix_time <= $now){#alarm was/is triggered
		$msg = "You had not responded by the Alert time. An alert was likely sent out. Please let your contacts know you are all right.";
		}
	else{
		$msg = "
		Push 'IM OK' before:<br>
		<font color='red'>{$imok_alert_date_time_string}</font><br>
		<div id='countdown'>countdown</div>
		<script>
		var trigger_time = $imok_alert_unix_time;
		
		</script>
		";
		}

	return $msg;

}

/*
				<script type="text/javascript">

						var trigger_time = parseInt( document.getElementById("trigger_time").value ) * 1000;

						function countdown() {
								var now = Date.now();
								var difference = trigger_time - now;
								var difference_seconds = difference / 1000;
								if(difference <= 0) {
												document.getElementById('countdown').innerHTML = "Timeout exceeded. Alerts are being sent.";
												}
								else{
												var days = Math.floor( difference_seconds / (60 * 60 * 24) );
												var day_seconds =  days * (60 * 60 * 24);
												difference_seconds = difference_seconds - day_seconds;
												var hours = Math.floor( difference_seconds / (60 * 60) );
												var hour_seconds = hours * (60 * 60);
												difference_seconds = difference_seconds - hour_seconds;
												var minutes = Math.floor( difference_seconds / (60) );
												var minute_seconds = minutes * (60);
												difference_seconds = Math.floor( difference_seconds - minute_seconds );

												var countdown_string = '' + days + ' days ' + hours + ' hours ' + minutes + ' minutes ' + difference_seconds + ' seconds';
												document.getElementById('countdown').innerHTML = countdown_string;
												}

						}

						setInterval( countdown , 5000 * 1 ); //update every 15 seconds
						countdown();

			</script>
	*/

?>
