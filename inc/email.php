<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed


class Emogic_IMOK_Email{
 
  public static function imok_mail($email_to , $subject , $message){
   //$email_to = $user->user_email;
   //$subject = "IMOK pre-alert";
      //$message = "Your IMOK Alert will be triggered and sent to your contact list at $imok_alert_date_time_string_local. Stop it by pushing IMOK button at " . IMOK_ROOT_URL;
   $options = get_option( 'imok_admin_settings' );
   $from_email = $options['imok_from_email_field'];
   $headers = "From: imok <$from_email>";
   $result = wp_mail( $email_to , $subject , $message , $headers  );
   return $result;
 }
 
 public static function template_mail($email_to , $template_page_name){
		$user = wp_get_current_user();
		
		//get email template
		$posts = get_posts(
		array( 'post_type'              => 'page',
			'title'                  => $template_page_name,
			'post_status'            => 'all', ) );
		$page_got_by_title = null;		 
		if ( ! empty( $posts ) ) {
			$page_got_by_title = $posts[0];
		}		
		$message = $page_got_by_title->post_content;
		$message = do_shortcode( $message );
		//find the subject tag
		$tags = new WP_HTML_Tag_Processor( $message );
		$tags->next_tag( 'subject' );
		$subject = $tags->get_attribute( 'value' );

		$result = Emogic_IMOK_Email::imok_mail( $email_to , $subject , $message );
  
  return $result;
 }
  
 
}



?>
