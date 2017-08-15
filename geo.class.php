<?php
define('EARTH_RADIUS', 6372795);

class Geo {

	private $connect = null;

	public function __construct()
	{
		$this->connect = new mysqli('localhost', 'sergeykiry', 'SaO5208730460', 'sergeykiry');
		$this->connect->set_charset("utf8");
	}

	private function getDistance($fA, $aA, $fB, $aB)
	{
	    $lat1 = $fA * M_PI / 180;
	    $lat2 = $fB * M_PI / 180;
	    $long1 = $aA * M_PI / 180;
	    $long2 = $aB * M_PI / 180;
	    $cl1 = cos($lat1);
	    $cl2 = cos($lat2);
	    $sl1 = sin($lat1);
	    $sl2 = sin($lat2);
	    $delta = $long2 - $long1;
	    $cdelta = cos($delta);
	    $sdelta = sin($delta);
	    $y = sqrt(pow($cl2 * $sdelta, 2) + pow($cl1 * $sl2 - $sl1 * $cl2 * $cdelta, 2));
	    $x = $sl1 * $sl2 + $cl1 * $cl2 * $cdelta;
	    $ad = atan2($y, $x);
	    $dist = $ad * EARTH_RADIUS;
	    return $dist;
	}

	public function search($geoLat, $geoLong, $maxDist = 30000)
	{
		$return = array();
		$res = $this->connect->query("SELECT * FROM cities;");
		while($result = $res->fetch_assoc()){
			if($this->getDistance((float)$geoLat, (float)$geoLong, $result['lat'], $result['long']) <= $maxDist){
				$return[] = $result;
			}
		}
		return (sizeof($return) > 0 ? $return : false);
	}

	public function coords($city)
	{
		$city = $this->connect->real_escape_string(trim(urldecode($city)));
		if(empty($city)){
			return false;
		}
		$arResult = array();
		$res = $this->connect -> query("SELECT * FROM `cities` WHERE `city` LIKE '". $city ."%'");
		while ($result=$res->fetch_assoc()){
			$arResult[] = $result;
		}
		return (sizeof($arResult) > 0 ? $arResult : false);
	}

}

?>