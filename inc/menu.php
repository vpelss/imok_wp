<?php

global $imok_menu_name;
$imok_menu_name = 'imok_menu';

add_action('init', 'imok_nav_creation_primary');
function imok_nav_creation_primary(){
global $imok_menu_name;
$menu_exists = wp_get_nav_menu_object( $imok_menu_name );
if( !$menu_exists){ // If it doesn't exist, let's create it.
    $menu_id = wp_create_nav_menu( $imok_menu_name );

	$dir = IMOK_PLUGIN_PATH . "/pages/";
	$files = scandir($dir);

	foreach ($files as $file) {
		if( ! str_starts_with( $file, 'IMOK' ) ){continue;}
		$page = get_posts( ['post_type' => 'page' , 'title'=> $file] )[0];
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
function shutdown_funct(){ //maybe move to deactivate
	global $imok_menu_name;
	wp_delete_nav_menu( $imok_menu_name );
};

add_shortcode( 'imok_menu', 'imok_menu_func' );
function imok_menu_func(){
	global $imok_menu_name;

	$page_links = wp_nav_menu( array( 'menu'=> $imok_menu_name ,
									'menu_id' => 'imok_menu_id',
									'menu_class' => "menu",
									'echo'=>false ,
									'container'			=> "div", // (string) Whether to wrap the ul, and what to wrap it with. Default 'div'.
									'container_id'		=> "imok_menu",
									'container_class'	=> "imok_menu",
									//'items_wrap' => '%3$s'
								  ) );

    $frills = "
    <!--
https://www.woolston.com.au/so-simple-collapsible-responsive-navigation-using-only-css/
https://www.w3schools.com/howto/howto_js_fullscreen_overlay.asp
-->

<style>
.overlay {
  max-width: 2000px;
  height: 100%;
  width: 100%;
  display: none;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: white;
  _background-color: rgb(0,0,0);
  _background-color: rgba(0,0,0, 0.9);
}

#imok_menu_id{
    list-style-type: none;
}

.overlay-content , #imok_menu{
  position: relative;
  top: 20%;
  //25%
  width: 100%;
  text-align: center;
  margin-top: 30px;
}

.overlay a {
  padding: 8px;
  text-decoration: none;
  font-size: 30px;
  //36px;
  _color: #818181;
  display: block;
  transition: 0.3s;
}

.overlay a:hover, .overlay a:focus {
  color: #f1f1f1;
}

.overlay .closebtn {
  position: absolute;
  top: 20px;
  right: 45px;
  font-size: 60px;
}

@media screen and (max-height: 550px) {
  .overlay a {font-size: 20px}
  .overlay .closebtn {
  font-size: 40px;
  top: 15px;
  right: 35px;
  }
}
</style>

<center>
<span style='font-size:30px;cursor:pointer;' onclick='openNav()'>☰</span>
</center>

<div id='myNav' class='overlay'>
<a href='javascript:void(0)' class='closebtn' onclick='closeNav()'>×</a>
{$page_links}
</div>

<script>
function openNav() {
  document.getElementById('myNav').style.display = 'block';
}

function closeNav() {
  document.getElementById('myNav').style.display = 'none';
}
</script>
";

	return $frills;
	}

?>
