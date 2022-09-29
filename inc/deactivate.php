<?php

class deactivate{

	public static function deactivate_plugin(){

		$pages = array("IMOK Log In" , "IMOK Logged In" , "IMOK Redirector" ,  "IMOK Settings");

		foreach ($pages as $page_name) {
			$page = get_page_by_title($page_name);
			wp_delete_post($page->ID , 1);
			}

		flush_rewrite_rules();
	}

}

register_deactivation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'deactivate' , 'deactivate_plugin') );
