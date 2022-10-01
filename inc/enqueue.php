<?php

class enqueue{

	static function initialize(){
		$src = IMOK_PLUGIN_LOCATION_URL . '/assets/imok.js';
		wp_enqueue_script('imokJS' , $src );
	}

}

enqueue::initialize();
