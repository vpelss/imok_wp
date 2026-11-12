<?php

if (! defined('ABSPATH')) {
    exit($status = 'ABSPATH not defn');
} //exit if directly accessed

//host cron should run cron.php every 15 min

//set a wp_cron time interval. why are cron_schedules temporary?
add_filter('cron_schedules', ['Emogic_IMOK_Chron', 'imok_add_cron_interval']);
//create my cron hook. it is not scheduled yet
add_action('EMOGIC_IMOK_CRON_HOOK', ['Emogic_IMOK_Chron', 'imok_cron_exec']);

//for im not ok emails
add_shortcode('EMOGIC_IMOK_CRON_USER_EMAIL', ['Emogic_IMOK_Chron', 'EMOGIC_IMOK_USER_EMAIL_SHORTCODE']);
add_shortcode('EMOGIC_IMNOTOK_CRON_USER_MESSAGE', ['Emogic_IMOK_Chron', 'EMOGIC_IMNOTOK_USER_MESSAGE_SHORTCODE']);
//for pre alert emails
add_shortcode('EMOGIC_IMOK_CRON_GET_ALERT_DATE_TIME_STR', ['Emogic_IMOK_Chron', 'EMOGIC_IMOK_CRON_GET_ALERT_DATE_TIME_STR_SHORTCODE']); 
add_shortcode('EMOGIC_IMOK_ROOT_URL', ['Emogic_IMOK_Chron', 'EMOGIC_IMOK_ROOT_URL_SHORTCODE']);

//scheduled our wp cron (no our host cron)
//wp_next_scheduled( string $hook, array $args = array() ): int|false
if (! wp_next_scheduled('EMOGIC_IMOK_CRON_HOOK')) {
    //set to exactly the hour 
    $time_seconds = time();
    $time_hours = round($time_seconds / 60 / 60); //round $time to the previous hour
    $time = $time_hours * 60 * 60; //and then convert back to seconds again
    //wp_schedule_event( int $timestamp, string $recurrence, string $hook, array $args = array(), bool $wp_error = false ): bool|WP_Error
    wp_schedule_event($time, 'EMOGIC_IMOK_CRON_TIME', 'EMOGIC_IMOK_CRON_HOOK');
}

class Emogic_IMOK_Chron
{
    static public $current_user_email = null;
    static public $current_user_id = null;

    public static function EMOGIC_IMOK_ROOT_URL_SHORTCODE()
    {
        return IMOK_ROOT_URL;
    }

    public static function EMOGIC_IMOK_CRON_GET_ALERT_DATE_TIME_STR_SHORTCODE()
    {
        $userID = self::$current_user_id;
        $imok_alert_date = get_user_meta($userID, 'imok_alert_date', true);
        $imok_alert_time = get_user_meta($userID, 'imok_alert_time', true);
        $imok_alert_date_time_string_local = $imok_alert_date . ' ' . $imok_alert_time;
        return $imok_alert_date_time_string_local;
    }

    public static function imok_add_cron_interval($schedules)
    {

        $options = get_option( 'imok_admin_options' );
		$cron_time = $options['imok_cron_schedule_field'];
        $schedules['EMOGIC_IMOK_CRON_TIME'] =
            array(
                'interval' => $cron_time, //in seconds
                'display'  => 'EMOGIC_IMOK_CRON_TIME'
            );
        return $schedules;
    }

    public static function imok_cron_exec()
    {
        //get an array of our users
        $users = get_users();

        foreach ($users as $user) {
            $userID = $user->ID;
            self::$current_user_email = $user->user_email;
            self::$current_user_id = $userID;
            if (! get_user_meta($user->ID, 'imok_timezone', true)) {
                continue;
            } //did user set settings? if not, next user
            //$imok_timezone = 60 * get_user_meta($user->ID, 'imok_timezone', true); //was stored in minutes; we have converted to seconds
            $imok_timezone_minutes = get_user_meta($user->ID, 'imok_timezone', true);
            $imok_timezone = 60 * intval($imok_timezone_minutes); //was stored in minutes, we have converted to seconds

            $now_UTC = current_time("timestamp", 1); //now in UTC time

            $imok_alert_date = get_user_meta($userID, 'imok_alert_date', true);
            $imok_alert_time = get_user_meta($userID, 'imok_alert_time', true);

            $imok_alert_date_time_string_local = $imok_alert_date . ' ' . $imok_alert_time;
            //take client's desired alert date and time, then add the time zone that the client's web browser saved on the settings page. this value will be compared to servers GMT time to trigger an alert email
            $imok_alert_unix_time =  strtotime($imok_alert_date_time_string_local) + $imok_timezone; 

            $result = true;
            //apply_filters('wp_mail_content_type',  "text/html");
            add_filter('wp_mail_content_type', function() { return "text/html"; });

            if ($imok_alert_unix_time <= $now_UTC) { #alarm was/is triggered , email to list                       
                require_once IMOK_PLUGIN_PATH . 'inc/email.php';
                $template_page_name = 'IMOK Email Missed Check In';
                $email_to_str =    EMOGIC_IMOK_Email::get_dist_list($userID);
                $result = Emogic_IMOK_Email::template_mail($email_to_str, $template_page_name);
            } elseif ($now_UTC > ($imok_alert_unix_time - (3600 * get_user_meta($userID, 'imok_pre_warn_time', true)))) { //pre-alert time , email to client
                require_once IMOK_PLUGIN_PATH . 'inc/email.php';
                $email_to_str = $user->user_email;
                $template_page_name = 'IMOK Email Pre Alert';
                $result = Emogic_IMOK_Email::template_mail($email_to_str, $template_page_name);
            }
            if (! $result) {
                wp_trigger_error('EMOGIC_IMOK_Chron::imok_cron_exec', 'Email was NOT sent.');
            }
        }
    }

    public static function EMOGIC_IMOK_USER_EMAIL_SHORTCODE()
    {
        return self::$current_user_email;
    }

    public static function EMOGIC_IMNOTOK_USER_MESSAGE_SHORTCODE()
    {
        $userID = self::$current_user_id;
        return get_user_meta($userID, 'imok_email_message', true);
    }
}
