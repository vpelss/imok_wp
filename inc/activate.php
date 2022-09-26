<?php

class activate{

	public function activate_plugin(){
		flush_rewrite_rules();
		//add_action( 'init' , 'activate::custom_post_type' );
		//do_action( 'activate::custom_post_type' );
		//do_action( 'activated_plugin', "imok/inc/get_set_pages.php" );
		//add_option( 'Activated_Plugin', 'Plugin-Slug' );

	}

	static function custom_post_type(){
		register_post_type( 'imok_posts' , ['public' => true , 'label' => 'IMOK_POSTS'] );
	}

}

register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'activate' , 'activate_plugin') );

//add_action( 'init' , 'activate::custom_post_type' );
//add_action( 'activated_plugin' , 'activate::custom_post_type' );

//do_action( 'activated_plugin', "imok/inc/get_set_pages.php" );

/*
function load_plugin() {
    if ( is_admin() && get_option( 'Activated_Plugin' ) == 'Plugin-Slug' ) {

        delete_option( 'Activated_Plugin' );
		add_action( 'init' , 'activate::custom_post_type' );
        // do stuff once right after activation
        // example: add_action( 'init', 'my_init_function' );
    }
}
add_action( 'admin_init', 'load_plugin' );
*/

?>
