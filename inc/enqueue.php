<?php

class enqueue{

	static function initialize(){
		//wp_enqueue_script('imokscript' , plugins_url('/assets/imok.js' , __FILE__));
		$r = IMOK_PLUGIN_PATH . '/assets/imok.js';
		wp_enqueue_script('imokscript' , IMOK_PLUGIN_PATH . '/assets/imok.js' );
	}

}

enqueue::initialize();


