<?php

add_shortcode( 'imok_pushed', 'imok_pushed_func' );
function imok_pushed_func(){
		$user = wp_get_current_user();

		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			$response = $_POST['command'];
		}
		else{
			$response = $_GET['command'];
		}

		if($response == 'imok'){
			return 'Button pushed!';
		}
		else{
			return 'Button not pushed!';
		}
		
	}


?>
