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
                        $loc = MA_Core::getUrlContent("https://maps.google.com/maps/api/geocode/json?address=$address");
                        $loc = json_decode($loc);
                        $loc = $loc->results[0];
                        $location = new stdClass();
                        $location->formatted_address = $loc->formatted_address;
                        $location->latitude = $loc->geometry->location->lat;
                        $location->longitude = $loc->geometry->location->lng;
                }else{
//              $location = MA_Core::getUrlContent("http://freegeoip.net/json/$remoteIP"); //Secondary Service
//              $location = MA_Core::getUrlContent("http://ip-api.com/json/$remoteIP"); //Secondary Service

//              $location = json_decode($location);
//              $location2 = MA_Core::getUrlContent("http://smart-ip.net/geoip-json/$remoteIP"); //Primary Service
 $location2 = MA_Core::getUrlContent("http://ip-api.com/json/$remoteIP"); //Primary Service


                $location2 = json_decode($location2);
                $mergeLocation = new stdClass();
                $mergeLocation->country_name = (!empty($location2->country))?$location2->country:$location->country;
                $mergeLocation->city = (!empty($location2->city))?$location2->city:$location->locality;
                $mergeLocation->latitude = (!empty($location2->lat))?$location2->lat:$location->lat;
                $mergeLocation->longitude = (!empty($location2->lon))?$location2->lon:$location->lng;
                $location = $mergeLocation;
                }
                return $class->get_pryaer_time(FALSE,$date,$location);
        }
}
