<?php
class MA_main{
	public function get_pryaer_time($isWidget,$date,$location){
		
		$acalend = new ICalendar();
		$acdate=$acalend->GregorianToIslamic($date['year'], $date['month'], $date['day']);
		$hijreDate =$acalend->ac_date;
		$timeZoneInfo = file_get_contents("http://www.earthtools.org/timezone/$location->latitude/$location->longitude");
		$xmlDoc = simplexml_load_string($timeZoneInfo);
		$timeZoneOffSet = $xmlDoc->offset;
		$prayerClass = new PrayTime();
		$prayerClass->setTimeFormat(1);
		$prayerInfos = 	$prayerClass->getDatePrayerTimes($date['year'], $date['month'], $date['day'], $location->latitude, $location->longitude, $timeZoneOffSet);
		//Months Naming
		$gMonth;
		$iMonth;
		switch ($date['month']){
			case 1:
				$iMonth = "Muharram";
				$gMonth = "January";
				break;
			case 2:
				$iMonth = "Safar";
				$gMonth = "February";
				break;
			case 3:
				$iMonth = "Rabi-Ul-Awwal";
				$gMonth = "March";
				break;
			case 4:
				$iMonth = "Rabi-Ul-Akhir";
				$gMonth = "April";
				break;
			case 5:
				$iMonth = "Jumaada-Al-Oola";
				$gMonth = "May";
				break;
			case 6:
				$iMonth = "Jumaada-Ath-Thani";
				$gMonth = "June";
				break;
			case 7:
				$iMonth = "Rajab";
				$gMonth = "July";
				break;
			case 8:
				$iMonth = "Sha'baan";
				$gMonth = "August";
				break;
			case 9:
				$iMonth = "Ramadaan";
				$gMonth = "September";
				break;
			case 10:
				$iMonth = "Shawwaal";
				$gMonth = "October";
				break;
			case 11:
				$iMonth = "Zul-Qa'dah";
				$gMonth = "November";
				break;
			case 12:
				$iMonth = "Zul-Hijjah";
				$gMonth = "December";
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
				<td>Sunrise</td>
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
				<td>Sunset</td>
				<td><?= strtoupper($prayerInfos[4]); ?></td>
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
	<a href="#" id="revealMeAnchore">Change Location or Date?</a>
	<form id="locForm" style="text-align:center;display:none;" method="post">
		<label for="location">Search For Location:</label><br>
		<input type="text" name="address" id="location" />
		<input type="hidden" name="action" value="ma_prayer_change">
		<input type="hidden" name="custom_search">
		<small style="display:block;">Search with City Name,Zip Code & etc</small>
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