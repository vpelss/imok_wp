<?php

if ( ! defined( 'ABSPATH' ) ) {	exit($staus='ABSPATH not defn'); } //exit if directly accessed


class Emogic_IMOK_Email{
 
  public static function imok_mail($email_to , $subject , $message){
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
		array( 'post_type' => 'page',
			'title' => $template_page_name,
			'post_status' => 'all', ) );
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
  
   public static function get_dist_list($user_id){
    $user = get_user_by( 'ID' , $user_id );
    $email_to = array();
    array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_1', true ) );
    array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_2', true ) );
    array_push($email_to , get_user_meta( $user->ID, 'imok_contact_email_3', true ) );
    array_push( $email_to , $user->user_email );
    $email_to_str = implode( ",", $email_to );
    return $email_to_str;
  }

  /*
    public static function get_ser_email(){
		$user = wp_get_current_user();
        return $user->email;
    }
	*/	
 
}

?>
