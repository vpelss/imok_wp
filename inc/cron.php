<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed

// MOVE TO activate?

//set a wp_cron interval
add_filter( 'cron_schedules', ['Emogic_IMOK_Chron' , 'imok_add_cron_interval'] );

//create my hook
add_action( 'EMOGIC_IMOK_cron_hook', ['Emogic_IMOK_Chron' , 'imok_cron_exec'] );

add_shortcode( 'EMOGIC_IMOK_CURRENT_USER_FORM', ['Emogic_IMOK_Chron' , 'EMOGIC_IMOK_CURRENT_USER_FORM_func'] );
add_shortcode( 'EMOGIC_IMOK_ALERT_DATE_TIME_STR_LOCAL', ['Emogic_IMOK_Chron' , 'EMOGIC_IMOK_ALERT_DATE_TIME_STR_LOCAL_func'] );
add_shortcode( 'EMOGIC_IMOK_ROOT_URL', ['Emogic_IMOK_Chron' , 'EMOGIC_IMOK_ROOT_URL_func'] );
add_shortcode( 'EMOGIC_IMOK_CURRENT_CRON_USER_EMAIL', ['Emogic_IMOK_Chron' , 'EMOGIC_IMOK_CURRENT_CRON_USER_EMAIL_func'] );

//scheduled our cron
if ( ! wp_next_scheduled( 'EMOGIC_IMOK_cron_hook' ) ) {
    //set to exactly the 1/4 hour (0,15,30,45)
    $time = time();
    $time = round($time / 60 / 15);     //so round/convert $time to 1/4 hours
    $time = $time * 60 * 15; //and then convert back to milisecond again
    wp_schedule_event( $time , 'EMOGIC_IMOK_fifteen_minutes', 'EMOGIC_IMOK_cron_hook' );
}

class Emogic_IMOK_Chron{
    
    static public $current_user_email = null;
    static public $current_user_id = null;
    
    public static function EMOGIC_IMOK_ROOT_URL_func(){
        return IMOK_ROOT_URL;
    }
    
     public static function EMOGIC_IMOK_ALERT_DATE_TIME_STR_LOCAL_func(){
            //$userID = $user->ID;
            $userID = self::$current_user_id;
            //$imok_timezone = 60 * get_user_meta( $user->ID , 'imok_timezone', true ); //in minutes * 60
            //$now_UTC = current_time("timestamp" , 1); //now in UTC time
            $imok_alert_date = get_user_meta( $userID, 'imok_alert_date', true );
            $imok_alert_time = get_user_meta( $userID, 'imok_alert_time', true );
            $imok_alert_date_time_string_local = $imok_alert_date . ' ' . $imok_alert_time;
            return $imok_alert_date_time_string_local;
     }
    
     public static function EMOGIC_IMOK_CURRENT_USER_FORM_func(){
       $userID = self::$current_user_id;
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
            self::$current_user_email = $user->user_email;
            self::$current_user_id = $userID;
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
            	$email_to_str =	EMOGIC_IMOK_Email::get_dist_list($userID);
                $result = Emogic_IMOK_Email::template_mail($email_to_str , $template_page_name);   
                }
            elseif( $now_UTC > ($imok_alert_unix_time - (3600 * get_user_meta( $userID , 'imok_pre_warn_time', true )) ) ){ //pre-alert time , email to client
                $email_to_str = $user->user_email;
            	$template_page_name = 'IMOK Email Pre Alert';
                $result = Emogic_IMOK_Email::template_mail($email_to_str , $template_page_name);   
               }
            }    
    }
    
    public static function EMOGIC_IMOK_CURRENT_CRON_USER_EMAIL_func(){
        return self::$current_user_email;
    }
    
}





?>
