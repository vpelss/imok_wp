<?php

class activate{

	public function activate_plugin(){
		flush_rewrite_rules();
	}

	static function custom_post_type(){
		register_post_type( 'imok_posts' , ['public' => true , 'label' => 'IMOK_POSTS'] );
	}

}

register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'activate' , 'activate_plugin') );

add_action( 'init' , 'activate::custom_post_type' );

?>
