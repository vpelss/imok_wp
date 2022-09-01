<?php

//set a wp_cron interval
add_filter( 'cron_schedules', 'imok_add_cron_interval' );
function imok_add_cron_interval( $schedules ) {
    $schedules['fifteen_minutes'] = array(
        'interval' => 54000,
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


//check dates

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
