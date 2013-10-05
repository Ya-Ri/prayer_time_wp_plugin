<?php
/*
Plugin Name: Prayer Time
Description: Allow visitors and user to see prayer time with respect to thier current location or can be customized. Shortcode is <strong>[ma_prayer_viewer]</strong>.
Author: Muhammad Asif (Capripio)
Author URI: http://facebook.com/capripio
Plugin URI: http://fiverr.com/capripio2
License: GNU
*/

add_action('widgets_init','ma_widget_init');
add_action('init','ma_prayer_init');
add_action('wp_enqueue_scripts','ma_enqueue_stylesheet');
define(AM_Plugin_dir,dirname(__FILE__));

require_once AM_Plugin_dir . "/core.class.php";
require_once AM_Plugin_dir . "/ICalendar.class.php";
require_once AM_Plugin_dir . "/PrayTime.class.php";
require_once AM_Plugin_dir . "/shortcode.class.php";
require_once AM_Plugin_dir . "/widget.class.php";
require_once AM_Plugin_dir . "/ma_main.class.php";



function ma_prayer_init(){
	$class = new MA_Shortcode_class();
	$class->register_shortcode();
}

function ma_widget_init(){
	register_widget('MA_prayer_time_widget');
}

function ma_enqueue_stylesheet(){
	wp_enqueue_style('ma_default_style',plugins_url('style.css',__FILE__));
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	wp_enqueue_script('ajax_form_submit',plugins_url('ajax_form_submit.js',__FILE__),array('jquery'));
	wp_enqueue_script('ma_default_script',plugins_url('functions.js',__FILE__),array('jquery'));
	
	
}

//Ajax Actions

add_action('wp_ajax_ma_prayer_change', 'ma_prayer_change_cb');
add_action('wp_ajax_no_priv_ma_prayer_change', 'ma_prayer_change_cb');

function ma_prayer_change_cb(){
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
	echo $class->get_pryaer_time(FALSE,$date,$location);
	die();
}