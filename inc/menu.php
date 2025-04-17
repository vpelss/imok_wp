<?php

add_shortcode( 'imok_menu', ['Emogic_IMOK_Menu' , 'imok_menu_shortcode_func'] );

class Emogic_IMOK_Menu{
 
    // this should only run from require_once IMOK_PLUGIN_PATH . 'inc/menu.php' in activate.php
    //read all pages/publish files and make a menu link for them
    public static function imok_nav_creation_primary(){
        $menu_exists = wp_get_nav_menu_object( IMOK_MENU_NAME );
        if( !$menu_exists){ // If it doesn't exist, let's create it. We don't want to clober an existing menu from the original site 
            $menu_id = wp_create_nav_menu( IMOK_MENU_NAME );
        
            $dir = IMOK_PLUGIN_PATH . "/pages/publish/";
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
    }
   
    //run from deactivate
    public static function shutdown_funct(){ 
       wp_delete_nav_menu( IMOK_MENU_NAME );
    }
    
    //run when shortcode on page
    public static function imok_menu_shortcode_func(){   
        $page_links = wp_nav_menu( array( 'menu'=> IMOK_MENU_NAME ,
                                        'menu_id' => 'imok_menu_wp',
                                        'menu_class' => "imok_menu_wp",
                                        'echo'=>false 
                                      ) );
    
        $frilly_menu = "
     
        
               <center>
        <span style='font-size:30px;cursor:pointer;' onclick='imok_menu_openNav()'>☰</span>

        <div id='imok_menu_myNav' class='imok_menu_overlay'>
          <a href='javascript:void(0)' class='imok_menu_closebtn' onclick='imok_menu_closeNav()'>×</a>
          <div onclick='imok_menu_spinner()'>
          {$page_links}
          </div>
        </div>

        <div id='imok_spinner_overlay' class='imok_spinner_overlay'>
        <center>
          <p>&nbsp;</p>   
          <p>&nbsp;</p>   
          <p>&nbsp;</p>       
          <p>&nbsp;</p>   
          <p id='imok_spinner' class='imok_spinner'>Loading
          </p>
          </center>
        </div>

        </center>
        
        <script></script>
        ";
        
        return $frilly_menu;
    }
   
    
}

?>
