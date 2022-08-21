<?php

class imokPluginDeactivate{
	
	public static function deactivate(){
		flush_rewrite_rules();
	}

}
