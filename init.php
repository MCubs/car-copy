<?php
$URL = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

if($URL[0] == 'geo'){

	$geo = new Geo();
	if(empty($URL[2])){
		die(json_encode(array('success' => 'false')));
	}
	switch($URL[2]){
		case "coords":
			if(empty($_REQUEST['city'])){
				die(json_encode(array('success' => 'false')));
			}
			$result = $geo->coords($_REQUEST['city']);
			break;

		case "cities":
			if(empty($_REQUEST['lat']) || empty($_REQUEST['long'])){
				die(json_encode(array('success' => 'false')));
			}
			$maxDist = isset($_REQUEST['distance']) ? (int)$_REQUEST['distance'] : 30000;
			$result = $geo->search($_REQUEST['lat'], $_REQUEST['long'], $maxDist);
			break;
	}
	echo (is_array($result) ? json_encode($result) : json_encode(array('success' => 'false')));
	exit;
}

?>