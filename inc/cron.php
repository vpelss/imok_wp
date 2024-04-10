<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

//set a wp_cron interval
add_filter( 'cron_schedules', ['Emogic_IMOK_Chron' , 'imok_add_cron_interval'] );

//create my hook
add_action( 'EMOGIC_IMOK_cron_hook', ['Emogic_IMOK_Chron' , 'imok_cron_exec'] );

add_shortcode( 'EMOGIC_IMOK_CURRENT_USER_EMAIL', ['Emogic_IMOK_Chron' , 'EMOGIC_IMOK_CURRENT_USER_EMAIL_func'] );
add_shortcode( 'EMOGIC_IMOK_ALERT_DATE_TIME_STR_LOCAL', ['Emogic_IMOK_Chron' , 'EMOGIC_IMOK_ALERT_DATE_TIME_STR_LOCAL_func'] );
add_shortcode( 'EMOGIC_IMOK__ROOT_URL', ['Emogic_IMOK_Chron' , 'EMOGIC_IMOK__ROOT_URL_func'] );

//scheduled our cron
if ( ! wp_next_scheduled( 'EMOGIC_IMOK_cron_hook' ) ) {
    //set to exactly the 1/4 hour
    $time = time();
    //so convert to 1/4 hours
    $time = round($time / 60 / 15);
    //and then back
    $time = $time * 60 * 15;
    wp_schedule_event( $time , 'EMOGIC_IMOK_fifteen_minutes', 'EMOGIC_IMOK_cron_hook' );
}

class Emogic_IMOK_Chron{
    
    public static function EMOGIC_IMOK__ROOT_URL_func(){
        return IMOK_ROOT_URL;
    }
    
     public static function EMOGIC_IMOK_ALERT_DATE_TIME_STR_LOCAL_func(){
            $userID = $user->ID;          
            //$imok_timezone = 60 * get_user_meta( $user->ID , 'imok_timezone', true ); //in minutes * 60
            //$now_UTC = current_time("timestamp" , 1); //now in UTC time
            $imok_alert_date = get_user_meta( $userID, 'imok_alert_date', true );
            $imok_alert_time = get_user_meta( $userID, 'imok_alert_time', true );
            $imok_alert_date_time_string_local = $imok_alert_date . ' ' . $imok_alert_time;
            return $imok_alert_date_time_string_local;
     }
    
     public static function EMOGIC_IMOK_CURRENT_USER_EMAIL_func(){
        return get_user_meta( $userID , 'imok_email_form', true );
     }
    
    public static function imok_add_cron_interval( $schedules ) {
        $schedules['EMOGIC_IMOK_fifteen_minutes'] = array(
            'interval' => 900,
            'display'  => esc_html__( 'EMOGIC IMOK Every Fifteen Minutes' ), );
        return $schedules;
    }

    public static function imok_cron_exec(){
        //get an array of our users
        $users = get_users();
        $msg = '';
        $msg1 = '';
        
        foreach ( $users as $user ) {
            $userID = $user->ID;
            $imok_contact_email_1 = get_user_meta( $userID , 'imok_contact_email_1', true ); // imok_contact_email_1
            if( ! get_user_meta( $user->ID , 'imok_timezone', true ) ){ continue; } //did user set settings? if not, next user
            $imok_timezone = 60 * get_user_meta( $user->ID , 'imok_timezone', true ); //in minutes * 60
        
            //if( is_email($imok_contact_email_1) and $imok_timezone){ //has user saved settings?
            $now_UTC = current_time("timestamp" , 1); //now in UTC time
        
            $imok_alert_date = get_user_meta( $userID, 'imok_alert_date', true );
            $imok_alert_time = get_user_meta( $userID, 'imok_alert_time', true );
        
            $imok_alert_date_time_string_local = $imok_alert_date . ' ' . $imok_alert_time;
            $imok_alert_unix_time =  strtotime( $imok_alert_date_time_string_local ) + $imok_timezone; //converts time (ignoring timezone) , need to add users timezone so we can convert to GMT to compare
        
            $message;
            $result;
            apply_filters( 'wp_mail_content_type',  "text/html" );
            if($imok_alert_unix_time <= $now_UTC){#alarm was/is triggered , email to list                       
            	$template_page_name = 'IMOK Email Missed Check In';
            	$email_to_str =	EMOGIC_IMOK_Email::gett_dist_list();
                $result = Emogic_IMOK_Email::template_mail($email_to_str , $template_page_name);   
                }
            elseif( $now_UTC > ($imok_alert_unix_time - (3600 * get_user_meta( $userID , 'imok_pre_warn_time', true )) ) ){ //pre-alert time , email to client
                $email_to_str = $user->user_email;
            	$template_page_name = 'IMOK Email Pre Alert';
                $result = Emogic_IMOK_Email::template_mail($email_to_str , $template_page_name);   
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
            //}
        
            return $msg;       
    }    
    
}





?>
