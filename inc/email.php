<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed


class Emogic_IMOK_Email{
 
  public static function imok_mail($email_to , $subject , $message){
   $options = get_option( 'imok_admin_settings' );
   $from_email = $options['imok_from_email_field'];
   //$headers = ["From: imok <$from_email>"];
   //force html
   $headers = ["From: imok <$from_email>" , 'Content-Type: text/html; charset=UTF-8'];
   //add_filter( 'wp_mail_content_type', function() {return 'text/html';} );
   #$headers = array( 'Content-Type: text/html; charset=UTF-8' );
   $result = wp_mail( $email_to , $subject , $message , $headers );
   return $result;
 }
 
 public static function template_mail($email_to , $template_page_name){
		$user = wp_get_current_user();
		//get email template
		$posts = get_posts(
		array( 'post_type' => 'page',
			'title' => $template_page_name,
			'post_status' => 'all', ) );
		$page_got_by_title = null;		 
		if ( ! empty( $posts ) ) {
			$page_got_by_title = $posts[0];
		}		
		$message = $page_got_by_title->post_content;
    global $shortcode_tags;
		$message = do_shortcode( $message );
		//find the subject tag value
		$tags = new WP_HTML_Tag_Processor( $message );
		$tags->next_tag( 'subject' );
		$subject = $tags->get_attribute( 'value' );

		$result = Emogic_IMOK_Email::imok_mail( $email_to , $subject , $message );
  
  return $result;
 }
  
   public static function get_dist_list($user_id){
    $user = get_user_by( 'ID' , $user_id );
    $email_to = array();
    array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_1', true ) );
    array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_2', true ) );
    array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_3', true ) );
    array_push( $email_to , $user->user_email ); //add user account email
    $email_to_str = implode( ",", $email_to ); //converts to "email1,email2,..." so wp_mail can send to all and they can all see each other's email
    return $email_to_str;
  }

}

?>
