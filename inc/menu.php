<?php

add_shortcode('EMOGIC_IMOK_MENU', ['Emogic_IMOK_Menu', 'menu_shortcode']);
add_shortcode('EMOGIC_IMOK_SPINNER', ['Emogic_IMOK_Menu', 'spinner_shortcode']);

class Emogic_IMOK_Menu
{
  // read all pages/publish files and make a menu link for them
  // this should only run from require_once IMOK_PLUGIN_PATH . 'inc/menu.php' in activate.php
  public static function imok_nav_creation_primary()
  {
    $menu_exists = wp_get_nav_menu_object(IMOK_MENU_NAME);
    if (!$menu_exists) { // If it doesn't exist, let's create it. We don't want to clober an existing menu from the original site 
      $menu_id = wp_create_nav_menu(IMOK_MENU_NAME);

      $dir = IMOK_PLUGIN_PATH . "/pages/publish/";
      $files = scandir($dir);

      foreach ($files as $file) {
        if (! str_starts_with($file, 'IMOK')) {
          continue;
        }
        $page = get_posts(['post_type' => 'page', 'title' => $file])[0];
        $post_title = $page->post_title;
        $guid = $page->guid;

        wp_update_nav_menu_item($menu_id, 0, array(
          'menu-item-title' => $post_title,
          'menu-item-classes' => '',
          'menu-item-url' => $guid,
          'menu-item-status' => 'publish'
        ));
      }
    }
  }

  //run from deactivate.php
  public static function shutdown_funct()
  {
    wp_delete_nav_menu(IMOK_MENU_NAME);
  }

  public static function spinner_shortcode()
  {
    $spinner_string = "<center>
      <div id='imok_spinner_overlay' class='imok_spinner_overlay'>
      <center>
        <p>&nbsp;</p>   
        <p>&nbsp;</p>   
        <p>&nbsp;</p>       
        <p>&nbsp;</p>   
        <p id='imok_spinner' class='imok_spinner'></p>
        <p>Working<br>Really<br>Hard</p>
        </center>
      </div>
      </center>";

    return $spinner_string;
  }

  public static function menu_shortcode()
  {
    $page_links = wp_nav_menu(array(
      'menu' => IMOK_MENU_NAME,
      'menu_id' => 'imok_menu_wp',
      'menu_class' => "imok_menu_wp",
      'echo' => false
    ));

    $menu_string = "<center>
        <span style='font-size:30px;cursor:pointer;' onclick='imok_menu_openNav()'>☰</span>

        <div id='imok_menu_myNav' class='imok_menu_overlay'>
          <a href='javascript:void(0)' class='no_spinner imok_menu_closebtn' onclick='imok_menu_closeNav()'>×</>
          <div>
          {$page_links}
          </div>
        </div>
        </center>";

    return $menu_string;
  }
}
