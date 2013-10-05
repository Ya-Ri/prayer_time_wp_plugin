<?php

class MA_Shortcode_class{
	public function register_shortcode(){
		add_shortcode('ma_prayer_viewer',array($this,'ma_prayer_viewer_output'));
	}
	public function ma_prayer_viewer_output(){
			
		$class = new MA_main();
		if(isset($_POST['date_changer'])){
			$dateArr = explode('/', $_POST['date']);
			$date['day'] = $dateArr[1];
			$date['month'] = $dateArr[0];
			$date['year'] = $dateArr[2];
		}else{
		$date['day'] = date('j',time());
		$date['month'] = date('n',time());
		$date['year'] = date('Y',time());
		}
		$remoteIP = $_SERVER['REMOTE_ADDR'];
		if(isset($_POST['custom_search'])){
			$address = urlencode($_POST['address']);
			$loc = MA_Core::getUrlContent("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
			$loc = json_decode($loc);
			$loc = $loc->results[0];
			$location = new stdClass();
			$location->formatted_address = $loc->formatted_address;
			$location->latitude = $loc->geometry->location->lat;
			$location->longitude = $loc->geometry->location->lng;
		}else{
		$location = MA_Core::getUrlContent("http://freegeoip.net/json/$remoteIP"); //Secondary Service
		$location = json_decode($location);
		$location2 = MA_Core::getUrlContent("http://smart-ip.net/geoip-json/$remoteIP"); //Primary Service
		$location2 = json_decode($location2);
		$mergeLocation = new stdClass();
		$mergeLocation->country_name = (!empty($location2->countryName))?$location2->countryName:$location->country_name;
		$mergeLocation->city = (!empty($location2->city))?$location2->city:$location->city;
		$mergeLocation->latitude = (!empty($location2->latitude))?$location2->latitude:$location->latitude;
		$mergeLocation->longitude = (!empty($location2->longitude))?$location2->longitude:$location->longitude;
		$location = $mergeLocation;
		}
		return $class->get_pryaer_time(FALSE,$date,$location);
	}
}