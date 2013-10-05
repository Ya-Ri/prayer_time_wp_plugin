<?php
 class MA_prayer_time_widget extends WP_Widget {

	public function __construct() {
		parent::__construct('ma_prayer_time_widget','Prayer Widget');
	}

	public function widget( $args, $instance ) {
		extract($instance);
		extract($args);
		echo $before_widget;
			if(isset($title))
				echo $before_title.$title.$after_title;
			//Outer Method Start
		$class = new MA_main();
		$date['day'] = date('j',time());
		$date['month'] = date('n',time());
		$date['year'] = date('Y',time());
		$remoteIP = $_SERVER['REMOTE_ADDR'];
//		$ipDetails = MA_Core::getUrlContent("http://freegeoip.net/json/105.17.202.77");
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
		echo $class->get_pryaer_time(TRUE,$date,$location,$timeZoneOffSet);
		//Outer Method End
		echo $after_widget;
	}

 	public function form( $instance ) {
 		$title = "";
 		if(isset($instance['title'])){
 			$title = $instance['title'];
 		}
 	 ?>
 	<label for="<?= $this->get_field_id('title') ?>">Title:</label>
 	<input type="text" id="<?= $this->get_field_id('title') ?>" name="<?= $this->get_field_name('title') ?>" value="<?= $title ?>">
	<?php }

	public function update( $new_instance, $old_instance ) {
		$instance['title']=empty($new_instance['title'])? "" :$new_instance['title'];
		return $instance;
	}
} 