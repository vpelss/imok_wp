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

//login mods
// custom login for theme
function childtheme_custom_login() {
    echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/customlogin.css" />';
}

add_action('login_head', 'childtheme_custom_login');

/**
 * Custom login image URL
 */
function my_custom_login_url( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_custom_login_url', 10, 1 );


