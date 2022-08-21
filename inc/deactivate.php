<?php

class deactivate{

	public static function deactivate_plugin(){
		flush_rewrite_rules();
	}

}

register_activation_hook( IMOK_PLUGIN_PATH_AND_FILENAME , array( 'deactivate' , 'deactivate_plugin') );
