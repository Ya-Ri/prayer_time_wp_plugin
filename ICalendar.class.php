<?php

/*
*   +------------------------------------------------------------------------------+
*       ICalendar : Arabic calendar class project
*   +------------------------------------------------------------------------------+
*       Disclaimer Notice(s)
* 
*	This class is based on the work of Johannes Thomann from the Orientalisches
*	Seminar der Universit‰t Z¸rich (http://www.ori.unizh.ch/)
* 
* 	The use of any code included into this website is prohibed without a written
* 	authorisation from the author. This class is released for Educational purpose.
* 
*
*   +------------------------------------------------------------------------------+
*       Updates
*   +------------------------------------------------------------------------------+
* 		2002 :  first release of the class written by Hatem
* 		4/09/2002; 10:04 :  updated copyright and original algorithm author name
* 	thanks to Nadim Attari (acenad at intnet dot mu).
*		2/01/2007 : Update to support arabic utf-8
*   +------------------------------------------------------------------------------+
*       Todos
*   +------------------------------------------------------------------------------+
*		
*		The current implementation is 100% compatible with Um-Al-Qura algorithm
*		however a manual fix is required to get the exact date, which is at +/- 1day
*
*   +------------------------------------------------------------------------------+
*       @author Ben Yacoub Hatem <hatem at php dot net>
*		@website http://phpmagazine.net
*   +------------------------------------------------------------------------------+
*/


class ICalendar {


	/**
	* @param	encoding	windows-1256 or utf-8
	*/
	var $encoding = 'utf-8';

	var $ac_date = array(
		'year' => "",
		'month' => "",
		'monthname' => "",
		'day' => "",
		'dayname' => ""
	);
	var $ac_date_g = array(
		'year' => "",
		'month' => "",
		'monthname' => "",
		'day' => "",
		'dayname' => ""
	);
	
    var $year;
	var $month;
	var $day;
	var $monthname;
	var $dayname;

	var $julianday;

	var $AC_Adaynames = array(
        '0' => "«·√Õœ",
        '1' => "«·«À‰Ì‰",
        '2' => "·À·«À«¡",
        '3' => "«·√—»⁄«¡",
        '4' => "«·Œ„Ì”",
        '5' => "«·Ã„⁄…",
        '6' => "«·√Õœ"
    );
	var $AC_Gdaynames = array(
        '0' => "Sunday",
        '1' => "Monday",
        '2' => "Tuesday",
        '3' => "Wednesday",
        '4' => "Thirsday",
        '5' => "Friday",
        '6' => "Saturday"
    );
	var $AC_AMonthName = array(
	     '1' => "„Õ—„",
		 '2' => "’›—",
		 '3' => "—»Ì⁄ «·√Ê·",
		 '4' => "—»Ì⁄ «·À«‰Ì",
		 '5' => "Ã„«œ… «·√Ê·",
		 '6' => "Ã„«œ… «·À«‰Ì",
		 '7' => "—Ã»",
		 '8' => "‘⁄»«‰",
		 '9' => "—„÷«‰",
		 '10' => "‘Ê«·",
		 '11' => "–Ê «·ﬁ⁄œ…",
		 '12' => "–Ê «·ÕÃ…"
	);   

  
  /*
		Returns True if is an islamic Leap Year
		@param $ayear : a year !==0.
  */
   function IslamicLeapYear($aYear)
   {
		if ((((11 * $aYear) + 14) % 30) < 11)
			return true;
		else 
			return false;   
   }

  /*
		Returns Last day in month during year on the Islamic calendar.
		@param $ayear : a year !==0.
  */
   function LastDayOfIslamicMonth ($Year, $aMonth)
   {
		if ((($aMonth % 2) == 1) or (($aMonth == 12) and IslamicLeapYear($aYear)))
			return 30;
		else
			return 29;
   }
   
   /*
      Constructs a day with a given year, month, and day.
      @param aYear a year !== 0
      @param aMonth a month between 1 and 12
      @param aDate a date between 1 and 31
   */
   function Ac_Day($aYear, $aMonth, $aDate)
   {
		$this->year  = $aYear;
		$this->month = $aMonth;
		$this->acdate  = $aDate;
   }

   /*
	  Convert from Georgian date to Islamic date.
      @param GYear a year !== 0
      @param GMonth a month between 1 and 12
      @param GDate a date between 1 and 31
   */
   function GregorianToIslamic($GYear, $GMonth, $GDay)
   {
		$y = $GYear;   
		$m = $GMonth;
		$d = $GDay;
		if (( $y > 1582 ) || (( $y == 1582 ) && ( $m > 10 )) || (( $y == 1582 ) && ( $m == 10 ) && ( $d > 14 ))) 
		{
			$jd = (int)(( 1461 * ( $y + 4800 + (int)(( $m - 14 ) / 12 )))/ 4) + (int)(( 367 * ( $m - 2 - 12 * ((int)(( $m - 14 ) / 12)))) / 12) - (int)(( 3 * ((int)(( $y + 4900+ (int)(( $m - 14) / 12) ) / 100))) / 4)+ $d - 32075;
		} else {
			$jd = 367 * $y - (int)(( 7 * ( $y + 5001 + (int)(( $m - 9 ) / 7))) / 4) + (int)(( 275 * $m) / 9) + $d + 1729777;
		}
		$this->julianday = $jd;
		$this->ac_date[dayname] = $this->Ac_getDayName( $jd % 7 );
		$l = $jd - 1948440 + 10632;
		$n = (int)(( $l - 1 ) / 10631);
		$l = $l - 10631 * $n + 354;
		$j = ( (int)(( 10985 - $l ) / 5316)) * ( (int)(( 50 * $l) / 17719)) + ( (int)( $l / 5670 )) * ( (int)(( 43 * $l ) / 15238 ));
		$l = $l - ( (int)(( 30 - $j ) / 15 )) * ( (int)(( 17719 * $j ) / 50)) - ( (int)( $j / 16 )) * ( (int)(( 15238 * $j ) / 43 )) + 29;
		$m = (int)(( 24 * $l ) / 709 );
		$d = $l - (int)(( 709 * $m ) / 24);
		$y = 30 * $n + $j - 30;
		
		$this->ac_date[year] = $y;
		$this->ac_date[month] = $m;
		$this->ac_date[day] = $d;
		$this->ac_date[monthname] = $this->Ac_getMonthName($m);
		
		return $this->ac_date;
   }

   /*
	  Convert from Islamic date to Georgian date.
      @param IYear a year !== 0
      @param IMonth a month between 1 and 12
      @param IDate a date between 1 and 31
   */
   function IslamicToGregorian($IYear, $IMonth, $IDay)
   {
		$y = $IYear;   
		$m = $IMonth;
		$d = $IDay;
		
		$jd = (int)((11*$y+3)/30)+354*$y+30*$m-(int)(($m-1)/2)+$d+1948440-385;
		$this->julianday = $jd;
		$this->ac_date_g[dayname] = $this->Ac_getGDayName( $jd % 7 );
		if ($jd> 2299160 )
		{
			$l=$jd+68569;
			$n=(int)((4*$l)/146097);
			$l=$l-(int)((146097*$n+3)/4);
			$i=(int)((4000*($l+1))/1461001);
			$l=$l-(int)((1461*$i)/4)+31;
			$j=(int)((80*$l)/2447);
			$d=$l-(int)((2447*$j)/80);
			$l=(int)($j/11);
			$m=$j+2-12*$l;
			$y=100*($n-49)+$i+$l;
		} else {
			$j=$jd+1402;
			$k=(int)(($j-1)/1461);
			$l=$j-1461*$k;
			$n=(int)(($l-1)/365)-(int)($l/1461);
			$i=$l-365*$n+30;
			$j=(int)((80*$i)/2447);
			$d=$i-(int)((2447*$j)/80);
			$i=(int)($j/11);
			$m=$j+2-12*$i;
			$y=4*$k+$n+$i-4716;
		}

		$this->ac_date_g[day] = $d;
		$this->ac_date_g[month] = $m;
		$this->ac_date_g[year] = $y;
		$this->ac_date_g[monthname] = $this->Ac_getGMonthName($m);
		
		return $this->ac_date_g;
   }

   /**
      Returns the Julian day 
      @return the year
   */
   function Ac_getJulianDay()
   {
		return $this->julianday;
   }
   
   /**
      Returns the year of this day
      @return the year
   */
   function Ac_getYear()
   {
		return $this->year;
   }

   /**
      Returns the month of this day
      @return the month
   */
   function Ac_getMonth()
   {
		return $this->month;   
   }

   /**
      Returns the name of the day
      @return the day name
   */
   function Ac_getDayName($aDay)
   {
   
   
		switch ($aDay)
		{
			case 0: $day = $this->AC_Adaynames[0];
			break;

			case 1: $day = $this->AC_Adaynames[1];
			break;

			case 2: $day = $this->AC_Adaynames[2];
			break;
			
			case 3: $day = $this->AC_Adaynames[3];
			break;
			
			case 4: $day = $this->AC_Adaynames[4];
			break;
			
			case 5: $day = $this->AC_Adaynames[5];
			break;
			
			case 6: $day = $this->AC_Adaynames[6];
			break;
		}

		switch($this->encoding) {
		
			case 'utf-8': // convert to utf-8
				$day = $this->iconvWin2UTF($day);
			break;
		
			default: // do nothing
			case 'windows-1256':
			
			break;
		}
		
		return $day;
   }

   /**
      Returns the English name of the day
      @return the day name
   */
   function Ac_getGDayName($aDay)
   {
		switch ($aDay)
		{
			case 0: return $this->AC_Gdaynames[0];
			break;

			case 1: return $this->AC_Gdaynames[1];
			break;

			case 2: return $this->AC_Gdaynames[2];
			break;
			
			case 3: return $this->AC_Gdaynames[3];
			break;
			
			case 4: return $this->AC_Gdaynames[4];
			break;
			
			case 5: return $this->AC_Gdaynames[5];
			break;
			
			case 6: return $this->AC_Gdaynames[6];
			break;
		}
   }
   
   /**
      Returns the name of the islamic month
      @return the day name
   */
   function Ac_getMonthName($aMonth)
   {
		switch ($aMonth)
		{
			case 1: $month = $this->AC_AMonthName[1];
			break;

			case 2: $month = $this->AC_AMonthName[2];
			break;
			
			case 3: $month = $this->AC_AMonthName[3];
			break;
			
			case 4: $month = $this->AC_AMonthName[4];
			break;
			
			case 5: $month = $this->AC_AMonthName[5];
			break;
			
			case 6: $month = $this->AC_AMonthName[6];
			break;
			
			case 7: $month = $this->AC_AMonthName[7];
			break;
			
			case 8: $month = $this->AC_AMonthName[8];
			break;
			
			case 9: $month = $this->AC_AMonthName[9];
			break;
			
			case 10: $month = $this->AC_AMonthName[10];
			break;
			
			case 11: $month = $this->AC_AMonthName[11];
			break;

			case 12: $month = $this->AC_AMonthName[12];
			break;
		}
		
		switch($this->encoding) {
		
			case 'utf-8': // convert to utf-8
				$month = $this->iconvWin2UTF($month);
			break;
		
			default: // do nothing
			case 'windows-1256':
			break;
		}
		
		return $month;
   }
   

   /**
      Returns the month number
      @return the day name
   */
   function Ac_getGMonthName($aMonth)
   {
		switch ($aMonth)
		{
			case 1: return 1;
			break;

			case 2: return 2;
			break;
			
			case 3: return 3;
			break;
			
			case 4: return 4;
			break;
			
			case 5: return 5;
			break;
			
			case 6: return 6;
			break;
			
			case 7: return 7;
			break;
			
			case 8: return 8;
			break;
			
			case 9: return 9;
			break;
			
			case 10: return 10;
			break;
			
			case 11: return 11;
			break;

			case 12: return 12;
			break;
		}
   }
   
   
   /**
      Returns the date of this day
      @return the date
   */
   function Ac_getDate()
   {
		return $this->year;   
   }

   /**
      Returns a day that is a certain number of days away from
      this day
      @param n the number of days, can be negative
      @return a day that is n days away from this one
   */
   function Ac_addDays($n)
   {
   
   }

   /**
      Returns the number of days between this day and another
      day
      @param other the other day
      @return the number of days that this day is away from 
      the other (>0 if this day comes later)
   */
   function Ac_daysFrom($Dayother)
   {
   }

	/**
	 * Convert windows-1256 to utf-8 - require iconv
	 */
	function iconvWin2UTF($text) {
		
		$codetable = array();
		$table = '30,%1E,31,%1F,32,%20,33,%21,34,%22,35,%23,36,%24,37,%25,38,%26,39,%27,40,%28,41,%29,42,%2A,43,%2B,44,%2C,45,-,46,.,47,%2F,48,0,49,1,50,2,51,3,52,4,53,5,54,6,55,7,56,8,57,9,58,%3A,59,%3B,60,%3C,61,%3D,62,%3E,63,%3F,64,%40,65,A,66,B,67,C,68,D,69,E,70,F,71,G,72,H,73,I,74,J,75,K,76,L,77,M,78,N,79,O,80,P,81,Q,82,R,83,S,84,T,85,U,86,V,87,W,88,X,89,Y,90,Z,91,%5B,92,%5C,93,%5D,94,%5E,95,_,96,%60,97,a,98,b,99,c,100,d,101,e,102,f,103,g,104,h,105,i,106,j,107,k,108,l,109,m,110,n,111,o,112,p,113,q,114,r,115,s,116,t,117,u,118,v,119,w,120,x,121,y,122,z,123,%7B,124,%7C,125,%7D,126,%7E,127,%7F,128,%E2%82%AC,129,%D9%BE,130,%E2%80%9A,131,%C6%92,132,%E2%80%9E,133,%E2%80%A6,134,%E2%80%A0,135,%E2%80%A1,136,%CB%86,137,%E2%80%B0,138,%D9%B9,139,%E2%80%B9,140,%C5%92,141,%DA%86,142,%DA%98,143,%DA%88,144,%DA%AF,145,%E2%80%98,146,%E2%80%99,147,%E2%80%9C,148,%E2%80%9D,149,%E2%80%A2,150,%E2%80%93,151,%E2%80%94,152,%DA%A9,153,%E2%84%A2,154,%DA%91,155,%E2%80%BA,156,%C5%93,157,%E2%80%8C,158,%E2%80%8D,159,%DA%BA,160,%C2%A0,161,%D8%8C,162,%C2%A2,163,%C2%A3,164,%C2%A4,165,%C2%A5,166,%C2%A6,167,%C2%A7,168,%C2%A8,169,%C2%A9,170,%DA%BE,171,%C2%AB,172,%C2%AC,173,%C2%AD,174,%C2%AE,175,%C2%AF,176,%C2%B0,177,%C2%B1,178,%C2%B2,179,%C2%B3,180,%C2%B4,181,%C2%B5,182,%C2%B6,183,%C2%B7,184,%C2%B8,185,%C2%B9,186,%D8%9B,187,%C2%BB,188,%C2%BC,189,%C2%BD,190,%C2%BE,191,%D8%9F,192,%DB%81,193,%D8%A1,194,%D8%A2,195,%D8%A3,196,%D8%A4,197,%D8%A5,198,%D8%A6,199,%D8%A7,200,%D8%A8,201,%D8%A9,202,%D8%AA,203,%D8%AB,204,%D8%AC,205,%D8%AD,206,%D8%AE,207,%D8%AF,208,%D8%B0,209,%D8%B1,210,%D8%B2,211,%D8%B3,212,%D8%B4,213,%D8%B5,214,%D8%B6,215,%C3%97,216,%D8%B7,217,%D8%B8,218,%D8%B9,219,%D8%BA,220,%D9%80,221,%D9%81,222,%D9%82,223,%D9%83,224,%C3%A0,225,%D9%84,226,%C3%A2,227,%D9%85,228,%D9%86,229,%D9%87,230,%D9%88,231,%C3%A7,232,%C3%A8,233,%C3%A9,234,%C3%AA,235,%C3%AB,236,%D9%89,237,%D9%8A,238,%C3%AE,239,%C3%AF,240,%D9%8B,241,%D9%8C,242,%D9%8D,243,%D9%8E,244,%C3%B4,245,%D9%8F,246,%D9%90,247,%C3%B7,248,%D9%91,249,%C3%B9,250,%D9%92,251,%C3%BB,252,%C3%BC,253,%E2%80%8E,254,%E2%80%8F';
		$tablex = explode(',', $table);
		for($i=0; $i<count($tablex); $i++) {
			$codetable[$tablex[$i]] = $tablex[$i+1];
			$i++;
		}

		$converted = '';
		for($i=0; $i<strlen($text); $i++) {
			$ix = ord($text[$i]);
			if (isset($codetable[$ix])) {
				$converted.=$codetable[$ix];
			} else {
				$converted.= $text[$i];
			}
		}
		return rawurldecode($converted);
		//*/
		//return iconv('windows-1256','utf-8',$text);
		
	}
	 

}


?>