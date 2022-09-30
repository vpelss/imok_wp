<?php

class enqueue{

	static function initialize(){
		$r = plugins_url( 'imok/assets/imok.js');
		wp_enqueue_script('imokJS' , $r );
	}

}

enqueue::initialize();
