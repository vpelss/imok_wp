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
            <!--
        https://www.woolston.com.au/so-simple-collapsible-responsive-navigation-using-only-css/
        https://www.w3schools.com/howto/howto_js_fullscreen_overlay.asp
        -->
        
        <style>
        .imok_menu_overlay {
          max-width: 2000px;
          height: 100%;
          width: 100%;
          display: none;
          position: fixed;
          z-index: 1;
          top: 0;
          left: 0;
          background-color: white;
        }
  
       .imok_menu_overlay-content {
          position: relative;
          top: 20%;
          width: 100%;
          text-align: center;
          margin-top: 30px;
        }
        
        .imok_menu_overlay a {
          padding: 8px;
          text-decoration: none;
          font-size: 30px;
          display: block;
          transition: 0.3s;
        }
        
        .imok_menu_overlay a:hover, .imok_menu_overlay a:focus {
          color: #f1f1f1;
        }
        
        .imok_menu_overlay .imok_menu_closebtn {
          position: absolute;
          top: 20px;
          right: 45px;
          font-size: 60px;
        }
        
        @media screen and (max-height: 550px) {
          .imok_menu_overlay a {font-size: 20px}
          .imok_menu_overlay .imok_menu_closebtn {
          font-size: 40px;
          top: 15px;
          right: 35px;
          }
        }

        .imok_spinner_overlay {
          display: none;
          height: 100%;
          width: 100%;
          position: fixed;
          z-index: 1;
          top: 0;
          left: 0;
          background-color: white;
          text-align: center;
        }

        .imok_spinner {
          display: none;
          border: 16px solid #f3f3f3; /* Light grey */
          border-top: 16px solid #3498db; /* Blue */
          border-radius: 50%;
          width: 120px;
          height: 120px;
          animation: imok_spin 2s linear infinite;
        }
  
        @keyframes imok_spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }        

        </style>
        
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
        
        <script>
        // https://codepen.io/paulobrien/pen/paNEZW
        // https://stackoverflow.com/questions/4588759/how-do-you-set-a-javascript-onclick-event-to-a-class-with-css

        function imok_menu_openNav() {
          document.getElementById('imok_menu_myNav').style.display = 'block';
        }
        
        function imok_menu_closeNav() {
          document.getElementById('imok_menu_myNav').style.display = 'none';
        }

        function imok_menu_spinner(){
          document.getElementById('imok_spinner').style.display = 'block';
          document.getElementById('imok_spinner_overlay').style.display = 'block';
          document.getElementById('imok_menu_myNav').style.display = 'none';
        }

        </script>
        ";
        
        return $frilly_menu;
    }
   
    
}

?>
