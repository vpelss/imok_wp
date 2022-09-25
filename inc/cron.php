<?php

//set a wp_cron interval
add_filter( 'cron_schedules', 'imok_add_cron_interval' );
function imok_add_cron_interval( $schedules ) {
    $schedules['fifteen_minutes'] = array(
        'interval' => 900,
        'display'  => esc_html__( 'Every Fifteen Minutes' ), );
    return $schedules;
}

//create my hook
add_action( 'imok_cron_hook', 'imok_cron_exec' );

//scheduled our cron
if ( ! wp_next_scheduled( 'imok_cron_hook' ) ) {
    wp_schedule_event( time(), 'fifteen_minutes', 'imok_cron_hook' );
}

function imok_cron_exec(){
//get an array of our users
$users = get_users();
$msg = '';
$msg1 = '';

foreach ( $users as $user ) {
    $userID = $user->ID;
    $imok_contact_email_1 = get_user_meta( $userID , 'imok_contact_email_1', true ); // imok_contact_email_1
    if( ! get_user_meta( $user->ID , 'imok_timezone', true ) ){ continue; }
    $imok_timezone = 60 * get_user_meta( $user->ID , 'imok_timezone', true ); //in minutes * 60

    if( is_email($imok_contact_email_1) and $imok_timezone){ //did we save settings
       	$now_UTC = current_time("timestamp" , 1); //now in UTC time

        $imok_alert_date = get_user_meta( $userID, 'imok_alert_date', true );
        $imok_alert_time = get_user_meta( $userID, 'imok_alert_time', true );

        $imok_alert_date_time_string_local = $imok_alert_date . ' ' . $imok_alert_time;
        $imok_alert_unix_time =  strtotime( $imok_alert_date_time_string_local ) + $imok_timezone; //converts time (ignoring timezone) , need to add users timezone so we can convert to GMT to compare

        $message;
        $result;
        apply_filters( 'wp_mail_content_type',  "text/html" );
        if($imok_alert_unix_time <= $now_UTC){#alarm was/is triggered , email to list
            $email_from = 'From: imok <imok@emogic.com>';
            $email_to = array();
            array_push( $email_to , get_user_meta( $userID , 'imok_contact_email_1', true ) );
            array_push( $email_to , get_user_meta( $userID , 'imok_contact_email_2', true ) );
            array_push( $email_to , get_user_meta( $userID , 'imok_contact_email_3', true ) );
            $subject = "IMOK alert";
            $message = get_user_meta( $userID , 'imok_email_form', true );
            $headers = $email_from;
            //Content-type: text/html
            $result = wp_mail( $email_to , $subject , $message , $headers  );
            }
        elseif( $now_UTC > ($imok_alert_unix_time - (3600 * get_user_meta( $userID , 'imok_pre_warn_time', true )) ) ){ //pre-alert time , email to client
            $email_from = 'From: imok <imok@emogic.com>';
             $email_to = $user->user_email;
            //$email_to = array();
            $subject = "IMOK pre-alert";
            $message = "Your IMOK Alert will be triggered at $imok_alert_date_time_string_local. Stop it at " . IMOK_ROOT_URL . "</br>";
            $headers = $email_from;
            $result = wp_mail( $email_to , $subject , $message , $headers  );
            }
        apply_filters( 'wp_mail_content_type',  "text/plain" );

        $imok_alert_unix_time_string = date("Y-m-d H:i"  , $imok_alert_unix_time); //convert to string
        $now_UTC_string = date("Y-m-d H:i"  , $now_UTC); //convert to string

        $msg1 = "user_id : {$user->ID} <br>
        mail result: {$result} <br>
        $message <br>
        imok_alert_unix_time_string : {$imok_alert_unix_time_string}<br>
		now_UTC_string : $now_UTC_string <br>";

        $msg = $msg . $msg1;
        }
    }

    return $msg;

    //has this account triggered?

}

/* sub cron(){
  &write_to_log("start of cron");
  my @filenames = glob("$path_to_users*");#get the list of files in the users directory
  foreach my $filename (@filenames){
   #$message = '';
    &write_to_log("Looking at $filename");
				my $user = $AuthorizeMeObj->db_to_hash($filename); #open file get details
    $email_list = &make_email_list($user);
    my $timestamp = &get_time_stamp($filename);

    my $t = time();
    &write_to_log("Time is $t and timestamp is $timestamp and pretime is $user->{'pre_warn_time'} and last email sent at $user->{'last_email_sent_at'}");

    if( ( time() > ($timestamp - $user->{'pre_warn_time_sec'}) ) && ($timestamp > time()) ){#send prewarn email to self
         $AuthorizeMeObj->{'settings'}->{'email_to'} = $user->{'email'};
         $AuthorizeMeObj->{'settings'}->{'email_subject'} = "IMOK Pre Alert";
         #$AuthorizeMeObj->{'settings'}->{'email_message'} = "$pre_warn_email_template <p> Alert was sent on behalf of $user->{'email'} </p>";
         $AuthorizeMeObj->{'settings'}->{'email_message'} = $pre_warn_email_template;
         my $result = $AuthorizeMeObj->email();
         }
    if($timestamp > time()){
       next;
       }#we are not alarming
    if(defined $user->{'last_email_sent_at'}){
      if($user->{'last_email_sent_at'} > (time()-(60 * 60)) ){
         next;
      }#we are waiting an hour before sending another alert email!
    }

    # &write_to_log("Result of user db $result user $user->{'user_id'}");
    #send alert emails
    $AuthorizeMeObj->{'settings'}->{'email_to'} = $email_list;
    $AuthorizeMeObj->{'settings'}->{'email_subject'} = "IMOK Alert";
    $AuthorizeMeObj->{'settings'}->{'email_message'} = "$user->{'email_form'} <p> Alert was sent on behalf of $user->{'email'} </p>"; #add users email at end of message in case they do not provide any identification in the email
    #my $uu = $AuthorizeMeObj->{'settings'}->{'email_message'} ;
    my $result = $AuthorizeMeObj->email();
    &write_to_log("sendmail result : $result : $user->{'email_contact_1'} : $user->{'email'}");
    #$user->{'timestamp'} = (60 * 60) + $timestamp; #set time stamp ahead one hour. So we do not send an email for another hour

    #$user->{'last_email_sent_at'} = time();
    #$user->{'alerts_sent'} = 1 + $user->{'alerts_sent'};  #increase email file count
    #$AuthorizeMeObj->hash_to_db($user , $filename); #save file
    #&set_time_stamp($timestamp , $filename);#restore old time stamp : so db update does not change it.

    #$message = "$message $AuthorizeMeObj->{'settings'}->{'email_message'}";
    &write_to_log("$filename Alert to $email_list at $t :  $AuthorizeMeObj->{'settings'}->{'email_message'}");
    }
&write_to_log("end of cron");
}*/

?>
