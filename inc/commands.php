<?php
/*
checks $_REQUEST['command'] on the IMOK main page and calls functions based on the command.
functions will return $msg to display
*/

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//shortcode [EMOGIC_IMOK_COMMANDS_AND_MSG] checks $_REQUEST['command'] and calls functions based on command
add_shortcode( 'EMOGIC_IMOK_COMMANDS_AND_MSG', ['Emogic_IMOK_Commands' , 'commands_and_msg_shortcode'] );

//shortcode log_out_everywhere_else link user on settings page. it is in command.php and not settings.php as it is a ?command= and takes us back to IMOK main page
add_shortcode( 'EMOGIC_IMOK_LOG_OUT_EVERYWHERE_ELSE_URL', ['Emogic_IMOK_Commands' , 'log_out_everywhere_else_url_shortcode'] );

//leave in commands as to not conflict with cron version
add_shortcode( 'EMOGIC_IMNOTOK_USER_MESSAGE', ['Emogic_IMOK_Commands' , 'EMOGIC_IMNOTOK_USER_MESSAGE_SHORTCODE'] );
add_shortcode( 'EMOGIC_IMOK_USER_EMAIL', ['Emogic_IMOK_Commands' , 'EMOGIC_IMOK_USER_EMAIL_SHORTCODE'] );

//returns sthe countdown js logic and display code
add_shortcode( 'EMOGIC_IMOK_COUNTDOWN', ['Emogic_IMOK_Commands' , 'countdown_shortcode'] );

class Emogic_IMOK_Commands{

	public static function imok(){
		$user = wp_get_current_user();
		$unix_day = 60 * 60 * 24; //seconds in a day
	
		//get current unix time
		$now = current_time("timestamp" , 0); //in unix time no UTC
	
		//get users data
		$imok_alert_interval_unix_time = $unix_day * get_user_meta( $user->ID, 'imok_alert_interval', true );
		$imok_alert_date =  get_user_meta( $user->ID, 'imok_alert_date', true );
		$imok_alert_time = get_user_meta( $user->ID, 'imok_alert_time', true );
		//convert user settings to unix time
		$imok_alert_date_time_string = $imok_alert_date . ' ' . $imok_alert_time;
		$imok_alert_unix_time = strtotime( $imok_alert_date_time_string ); //convert to unix time
	
		// reset alert time
		if( ($imok_alert_unix_time - $imok_alert_interval_unix_time) > $now  ){ 
			// we are at least one full alert interval before the alert date time. do nothing
			} 
		if($imok_alert_unix_time <= $now){ //alarm was triggered, so set a new alert time
			while( $imok_alert_unix_time <= $now ){
				$imok_alert_unix_time = $imok_alert_unix_time + $imok_alert_interval_unix_time;
			};
		}
		elseif( ($imok_alert_unix_time - $imok_alert_interval_unix_time) <= $now ){ // we are clicking just before alarm will trigger in the window of the alert interval
			$imok_alert_unix_time = $imok_alert_unix_time + $imok_alert_interval_unix_time; //one ping only please
		}
	
		//set new alert time in the db
		$imok_alert_date = date("Y-m-d"  , $imok_alert_unix_time); //convert to string
		update_user_meta( $user->ID , 'imok_alert_date' , $imok_alert_date ) ;
		$imok_alert_time = date("H:i" , $imok_alert_unix_time); //convert to string
		update_user_meta( $user->ID , 'imok_alert_time' , $imok_alert_time ) ;
	
		//return and display a new message
		$now_str = date( "Y-m-d H:i", $now);
		$new_alert_date_time = date( $imok_alert_date . " " . $imok_alert_time , $imok_alert_unix_time);
		$msg = "You pushed the IMOK button";
		return $msg;
	}

	public static function imnotok(){ 
		require_once IMOK_PLUGIN_PATH . 'inc/email.php'; 
		$user = wp_get_current_user();
		$template_page_name = 'IMOK Email IMNOTOK';
		$email_to_str =	EMOGIC_IMOK_Email::get_dist_list($user->ID);
		$result = Emogic_IMOK_Email::template_mail($email_to_str , $template_page_name);
		$msg =  "An IM NOT OK Alert was sent to your contact list.";
		return $msg;
	}

	//set shortcode for [imok_log_out_everywhere_else_url]
	public static function log_out_everywhere_else_url_shortcode(){
		$page = get_posts( ['post_type' => 'page' , 'title'=> IMOK_MAIN_PAGE] )[0]; 
		$newURL = get_permalink($page->ID);
		$newURL = $newURL . "?command=log_out_everywhere_else";
		return( $newURL );
	}

	// all $_REQUEST['command'] are fed here then the appropriate function (below) is called
	// we can return $msg to show after call
	public static function commands_and_msg_shortcode(){
		$user = wp_get_current_user();
	
		$response = 'none';
		if( isset($_REQUEST['command']) )
			$response = $_REQUEST['command'];
	
		if($response == 'imok'){
			return self::imok();
		}
		elseif($response == 'imnotok'){
			return self::imnotok();
		}
		/* remove as it is an DOS vector
		elseif($response == 'cron'){
			Emogic_IMOK_Chron::imok_cron_exec();
			//return imok_cron_exec();
		}
			*/
		elseif($response == 'log_out_everywhere_else'){
			$user = wp_get_current_user();
			$sessions = WP_Session_Tokens::get_instance( $user->ID );
			$sessions->destroy_others(  wp_get_session_token() );
			$msg = 'You have logged out everywhere else.</br></br>';
			return $msg;
		}	
	}
		
	public static function countdown_shortcode(){
		$user = wp_get_current_user();
	
		if($user->ID == 0){//we are not logged in
			return "Not logged in";
		}

		/*
		NOTE: Server and user PC are in different time zones. 
		So when comparing server an PC times on the server, convert all user pc times and server times to a common UTC time zone
		JS routines, when recieving any unix timestamp, see them as UTC (no timezone), 
		but Date() returns a unix timestamp based on the PC's timezone
		so on the user PC using JS, convert all times to UTC
		*/

		$now_UTC = current_time("timestamp" , 1); //get server time in UTC tz
	
		//get users alert date, time, and tz 
		$imok_alert_date =  get_user_meta( $user->ID, 'imok_alert_date', true );
		$imok_alert_time = get_user_meta( $user->ID, 'imok_alert_time', true );
		if( get_user_meta( $user->ID , 'imok_timezone', true ) ) {
			$imok_timezone = 60 * get_user_meta( $user->ID , 'imok_timezone', true );
			} //tz was store in minutes, so we convert to seconds
	
		//convert the users alert time to their PC's tz
		$imok_alert_date_time_string_local = $imok_alert_date . ' ' . $imok_alert_time;
		if( $imok_alert_date_time_string_local ){
			//converts time (ignoring timezone) , need to add users timezone so we can convert to GMT to compare
			$imok_alert_unix_time =  strtotime( $imok_alert_date_time_string_local ) + $imok_timezone; 
		}
		
		$IMOK_PLUGIN_LOCATION_URL = IMOK_PLUGIN_LOCATION_URL;
		if($imok_alert_unix_time <= $now_UTC){#alarm was/is triggered
			$msg = "You had not responded by the Alert time. An alert was likely sent out. Please let your contacts know you are all right.
			<audio id='imok_alarm' src='$IMOK_PLUGIN_LOCATION_URL/audio/Windows-Notify-Calendar.wav'></audio>
	
			<script>
			setInterval( alarm_me , 5000 * 1 ); //update every 15 seconds
	
			function alarm_me(){
				imok_alarm.play();
				}
			</script>";
			}
		else{
			$msg = "Push 'IM OK' before:<br>
			<font color='red'>{$imok_alert_date_time_string_local}</font><br>
			<font id='countdown'>countdown</font><br>
			<font id='timezone_error' color='orange'></font>
	
			<audio id='imok_alarm' src='$IMOK_PLUGIN_LOCATION_URL/audio/Windows-Notify-Calendar.wav'></audio>
	
			<script>
			var trigger_time = $imok_alert_unix_time;
			var imok_alarm = document.getElementById('imok_alarm');
	
			function countdown() {
				var now = Date.now() / 1000; //in seconds
				var difference_seconds = trigger_time - now;
				if(difference_seconds <= 0){
					document.getElementById('countdown').innerHTML = 'Timeout exceeded. Alerts are being sent.';
					imok_alarm.play();
					}
				else{
					var days = Math.floor( difference_seconds / (60 * 60 * 24) );
					var hours = Math.floor( (difference_seconds / (60 * 60)) % 24);
					var minutes = Math.floor( (difference_seconds / (60)) % 60 );
					var seconds = Math.floor( difference_seconds % 60 );
	
					var countdown_string = '' + days + ' days ' + hours + ' hours ' + minutes + ' minutes ' + seconds + ' seconds';
					document.getElementById('countdown').innerHTML = countdown_string;
					}
			}
			setInterval( countdown , 5000 * 1 ); //update every 15 seconds
			countdown(); //run now
	
			//test for wrong timezone by using js and comparing with stored timezone
			const d = new Date();
			let timezone= d.getTimezoneOffset();
			if((timezone * 60) != $imok_timezone){
				document.getElementById('timezone_error').innerHTML	= `Your device timezone differs from your timezone on the server.
				To update, go to settings and click save.`;
			}
			</script>
			";
			}
		return $msg;
	}

	public static function EMOGIC_IMNOTOK_USER_MESSAGE_SHORTCODE(){
		$user = wp_get_current_user();
		$userID = $user->ID;
		$message = get_user_meta($userID, 'imok_email_message', true);
		return get_user_meta($userID, 'imok_email_message', true);
	  }
	
	  public static function EMOGIC_IMOK_USER_EMAIL_SHORTCODE(){
	  $user = wp_get_current_user(  );
		  return $user->user_email;
	  }
	
		
}




?>
