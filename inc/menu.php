<?php

global $imok_menu;
$imok_menu = 'imok_menu';

add_action('init', 'auto_nav_creation_primary');
function auto_nav_creation_primary(){

global $imok_menu;

$menu_exists = wp_get_nav_menu_object( $imok_menu );

if( !$menu_exists){ // If it doesn't exist, let's create it.
    $menu_id = wp_create_nav_menu( $imok_menu );

	$dir = IMOK_PLUGIN_PATH . "/pages/";
	$files = scandir($dir);
	$menu_names = array();
	foreach ($files as $file) {
		if( ! str_starts_with( $file, 'IMOK' ) ){continue;}
		$page = get_page_by_title($file);
		$post_title = $page->post_title;
		$guid = $page->guid;

		wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => $post_title,
        'menu-item-classes' => '',
        'menu-item-url' => $guid,
        'menu-item-status' => 'publish'));
	}

}

};

add_action('shutdown', 'shutdown_funct');
function shutdown_funct(){
	global $imok_menu;
	wp_delete_nav_menu( $imok_menu );
};


add_shortcode( 'imok_menu', 'imok_menu_func' );
function imok_menu_func(){
	global $imok_menu;

	$txt = wp_nav_menu( array( 'menu'=> $imok_menu ,
									'menu_id' => 'imok_menu_id',
									'menu_class' => "menu",
									'echo'=>false ,
									'container'			=> "div", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
									'container_id'		=> "imok_menu",
									'container_class'	=> "imok_menu",
									//'items_wrap' => '%3$s'
								  ) );

	return $txt;

	}

	/*
	$rrr = wp_nav_menu( array(
	'menu'				=> 'imok_menu', // (int|string|WP_Term) Desired menu. Accepts a menu ID, slug, name, or object.
	'menu_class'		=> "", // (string) CSS class to use for the ul element which forms the menu. Default 'menu'.
	'menu_id'			=> "", // (string) The ID that is applied to the ul element which forms the menu. Default is the menu slug, incremented.
	'container'			=> "", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
	'container_class'	=> "", // (string) Class that is applied to the container. Default 'menu-{menu slug}-container'.
	'container_id'		=> "", // (string) The ID that is applied to the container.
	'fallback_cb'		=> "", // (callable|bool) If the menu doesn't exists, a callback function will fire. Default is 'wp_page_menu'. Set to false for no fallback.
	'before'			=> "", // (string) Text before the link markup.
	'after'				=> "", // (string) Text after the link markup.
	'link_before'		=> "", // (string) Text before the link text.
	'link_after'		=> "", // (string) Text after the link text.
	'echo'				=> "", // (bool) Whether to echo the menu or return it. Default true.
	'depth'				=> "", // (int) How many levels of the hierarchy are to be included. 0 means all. Default 0.
	'walker'			=> "", // (object) Instance of a custom walker class.
	'theme_location'	=> "", // (string) Theme location to be used. Must be registered with register_nav_menu() in order to be selectable by the user.
	'items_wrap'		=> "", // (string) How the list items should be wrapped. Default is a ul with an id and class. Uses printf() format with numbered placeholders.
	'item_spacing'		=> "", // (string) Whether to preserve whitespace within the menu's HTML. Accepts 'preserve' or 'discard'. Default 'preserve'.
) );
*/

?>
