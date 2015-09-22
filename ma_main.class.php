<?php
class MA_main{
	public function get_pryaer_time($isWidget,$date,$location){
		$acalend = new ICalendar();
		$acdate=$acalend->GregorianToIslamic($date['year'], $date['month'], $date['day']);
		$hijreDate =$acalend->ac_date;
                try{
//		$timeZoneInfo = file_get_contents("http://www.earthtools.org/timezone/$location->latitude/$location->longitude");
                $timeZoneInfo = file_get_contents("http://api.geonames.org/timezoneJSON?lat=$location->latitude&lng=$location->longitude&username=yamar");


                }catch(Exception $ex){
//                  $timeZoneInfo =  getUrlContent("http://www.earthtools.org/timezone/$location->latitude/$location->longitude");
                  $timeZoneInfo =  getUrlContent("http://api.geonames.org/timezoneJSON?lat=$location->latitude&lng=$location->longitude&username=yamar");

                }


		$timeStamp = time();
                try{
		$timeZoneInfoGoogle = file_get_contents("https://maps.googleapis.com/maps/api/timezone/json?location=$location->latitude,$location->longitude&timestamp=$timeStamp");

                }catch(Exception $ex){
                    $timeZoneInfoGoogle = getUrlContent("https://maps.googleapis.com/maps/api/timezone/json?location=$location->latitude,$location->longitude&timestamp=$timeStamp");

                }
		$timeZoneInfoGoogle = json_decode($timeZoneInfoGoogle);
                $timeZoneInfo = json_decode($timeZoneInfo);

//		$xmlDoc = simplexml_load_string($timeZoneInfo);
//		$timeZoneOffSet = $xmlDoc->dstOffset;
 $timeZoneOffSet = $timeZoneInfo->gmtOffset;
                   //     $timeZoneOffSet = 2.0;

		if($timeZoneInfoGoogle->dstOffset != 0)
			$timeZoneOffSet = $timeZoneOffSet+1;
		$prayerClass = new PrayTime(2);
		$prayerClass->setTimeFormat(0);
		$prayerInfos = 	$prayerClass->getDatePrayerTimes($date['year'], $date['month'], $date['day'], $location->latitude, $location->longitude, $timeZoneOffSet);
		//Months Naming
		$gMonth;
		$iMonth;
		switch ($date['month']){
			case 1:
				$gMonth = "Janvier";
				break;
			case 2:
				$gMonth = "Février";
				break;
			case 3:
				$gMonth = "Mars";
				break;
			case 4:
				$gMonth = "Avril";
				break;
			case 5:
				$gMonth = "Mai";
				break;
			case 6:
				$gMonth = "Juin";
				break;
			case 7:
				$gMonth = "Juillet";
				break;
			case 8:
				$gMonth = "Aout";
				break;
			case 9:
				$gMonth = "Septembre";
				break;
			case 10:
				$gMonth = "Octobre";
				break;
			case 11:
				$gMonth = "Novembre";
				break;
			case 12:
				$gMonth = "Decembre";
				break;
		}
		
		
		switch ($acdate['month']){
			case 1:
				$iMonth = "Muharram";
				break;
			case 2:
				$iMonth = "Safar";
				break;
			case 3:
				$iMonth = "Rabi-Ul-Awwal";
				break;
			case 4:
				$iMonth = "Rabi-Ul-Akhir";
				break;
			case 5:
				$iMonth = "Jumaada-Al-Oola";
				break;
			case 6:
				$iMonth = "Jumaada-Ath-Thani";
				break;
			case 7:
				$iMonth = "Rajab";
				break;
			case 8:
				$iMonth = "Sha'baan";
				break;
			case 9:
				$iMonth = "Ramadaan";
				break;
			case 10:
				$iMonth = "Shawwaal";
				break;
			case 11:
				$iMonth = "Zul-Qa'dah";
				break;
			case 12:
				$iMonth = "Zul-Hijjah";
				break;
		}
	 ob_start();
	
	 ?>
	<div class="ma-cal">
	<div class="cal-cont">
	<?php if(!$isWidget):	?>
		<a id="cal" class="cal">
			<p><?= $gMonth." ".$date['day'].", ".$date['year'] ?></p>
			<p><?= $iMonth." ".$hijreDate['day'].", ".$hijreDate['year'] ?></p>
		</a>
		<form id="cal-form" style="display:none;" method="post">
			<input type="text" id="ma-date" name="date">
			<input type="hidden" name="action" value="ma_prayer_change">
			<input type="hidden" name="date_changer" value="ma_prayer_change">
			<input type="submit" name="date_changer" value="Change">
		</form>
		<?php else: ?>
			<p><?= $gMonth." ".$date['day'].", ".$date['year'] ?></p>
			<p><?= $iMonth." ".$hijreDate['day'].", ".$hijreDate['year'] ?></p>
		<?php endif; ?>
	</div>
		<h4><?php if(isset($location->country_name)) :?>
		<?= $location->city ?>, <?= $location->country_name ?>
		<?php else: ?>
		 <?= $location->formatted_address ?>
		 <?php endif; ?>
		</h4>
		
		<table style="width:100%">
			<tr>
				<td>Fajr</td>
				<td><?= strtoupper($prayerInfos[0]); ?></td>
			</tr>
			<tr>
				<td>Lever Soleil</td>
				<td><?= strtoupper($prayerInfos[1]); ?></td>
			</tr>
			<tr>
				<td>Dhuhr</td>
				<td><?= strtoupper($prayerInfos[2]); ?></td>
			</tr>
			<tr>
				<td>Asr</td>
				<td><?= strtoupper($prayerInfos[3]); ?></td>
			</tr>
			<tr>
				<td>Maghrib</td>
			<td><?= strtoupper($prayerInfos[5]); ?></td>
			</tr>
			<tr>
				<td>Isha</td>
				<td><?= strtoupper($prayerInfos[6]); ?></td>
			</tr>
		</table>
		<?php if (!$isWidget): ?>
	<a href="#" id="revealMeAnchore">Changer le Lieu ou la Date?</a>
	<form id="locForm" style="text-align:center;display:none;" method="post">
		<label for="location">Lieu à rechercher:</label><br>
		<input type="text" name="address" id="location" />
		<input type="hidden" name="action" value="ma_prayer_change">
		<input type="hidden" name="custom_search">
		<small style="display:block;">Tapez le nom d'une ville ou un code postal ... Pour la date c'est tout en haut</small>
		<br>
		<input type="submit" name="custom_search" value="Search"/>
	</form>
	<script>
	var ajax_url = "<?= admin_url('admin-ajax.php'); ?>";
	</script>
	<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
	}


}
