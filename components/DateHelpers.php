<?php
namespace app\components;
// namespace app\components; // For Yii2 Basic (app folder won't actually exist)
class DateHelpers
{
    public static function hello($name) {
        return "Hello $name";
    }
	//with current month
	public static function getDateAfterYearDateTimeForRegexp($date_dt) {
        $wordsArray = explode("-", $date_dt);
				$year = $wordsArray[0];
				$month = $wordsArray[1];
				// Start date: first day of January of the given year
				$start = new \DateTimeImmutable("$year-$month-01");
				$start1 = $start->modify('+1 month');
				// End date: first day of January of the *next* year
				//++$year;
				$end = new \DateTimeImmutable("$year-12-31");
				// Interval of one month
				$interval = new \DateInterval('P1M');
				// Create a DatePeriod
				$period = new \DatePeriod($start, $interval, $end);
				//$months = [];
				$datesrexep = '';

				foreach ($period as $date) {
					//$months[] = sprintf('%02d',(int)$date->format('n')); // Numeric month without leading zeros
					$datesrexep.= "|$year-".    sprintf('%02d',(int)$date->format('n')); // Numeric month without leading zeros	
				}
				$datesrexep = mb_substr( $datesrexep,1);
				return $datesrexep;
    }
	public static function getDateYearDateTimeForRegexp($date_dt) {
        $wordsArray = explode("-", $date_dt);
				$year = $wordsArray[0];
				$month = $wordsArray[1];
				// Start date: first day of January of the given year
				$start = new \DateTimeImmutable("$year-01-01");
				$start1 = $start->modify('+1 month');
				// End date: first day of January of the *next* year
				//++$year;
				$end = new \DateTimeImmutable("$year-12-31");
				// Interval of one month
				$interval = new \DateInterval('P1M');
				// Create a DatePeriod
				$period = new \DatePeriod($start, $interval, $end);
				//$months = [];
				$datesrexep = '';

				foreach ($period as $date) {
					//$months[] = sprintf('%02d',(int)$date->format('n')); // Numeric month without leading zeros
					$datesrexep.= "|$year-".    sprintf('%02d',(int)$date->format('n')); // Numeric month without leading zeros	
				}
				$datesrexep = mb_substr( $datesrexep,1);
				return $datesrexep;
    }
	//without current month
	public static function getDateBeforeYearDateTimeForRegexp($date_dt) {
        $wordsArray = explode("-", $date_dt);
			$year = $wordsArray[0];
			$month = $wordsArray[1];
			// Start date: first day of January of the given year
			$start = new \DateTimeImmutable("$year-01-01");
			// End date: first day of January of the *next* year
			$end = new \DateTimeImmutable("$year-$month"."-01");
			// Interval of one month
			$interval = new \DateInterval('P1M');
			// Create a DatePeriod
			$period = new \DatePeriod($start, $interval, $end);
			$months = [];
			$datesrexep = "";

			foreach ($period as $date) {
			   
				$datesrexep.= "|$year-".    sprintf('%02d',(int)$date->format('n')); // Numeric month without leading zeros
			   
			}
			$datesrexep = mb_substr( $datesrexep,1);
			return $datesrexep;
    }
	
	public static function getDateBeforeYearDateTimeForArray($date_dt) {
        $wordsArray = explode("-", $date_dt);
			$year = $wordsArray[0];
			$month = $wordsArray[1];
			// Start date: first day of January of the given year
			$start = new \DateTimeImmutable("$year-01-01");
			// End date: first day of January of the *next* year
			$end = new \DateTimeImmutable("$year-$month"."-01");
			// Interval of one month
			$interval = new \DateInterval('P1M');
			// Create a DatePeriod
			$period = new \DatePeriod($start, $interval, $end);
			$months = [];
			//$datesrexep = "$year-00";

			foreach ($period as $date) {
				$months[] = "$year-".    sprintf('%02d',(int)$date->format('n'));
				//$datesrexep.= "|$year-".    sprintf('%02d',(int)$date->format('n')); // Numeric month without leading zeros
			   
			}
			return $months;
    }
	
	public static function getDateMonthYearDateTimeForArray($date_dt) {
        $wordsArray = explode("-", $date_dt);
			$year = $wordsArray[0];
			$month = $wordsArray[1];
			// Start date: first day of January of the given year
			$start = new \DateTimeImmutable("$year-01-01");
			++$year;
			// End date: first day of January of the *next* year
			$end = new \DateTimeImmutable("$year-01"."-01");
			// Interval of one month
			$interval = new \DateInterval('P1M');
			// Create a DatePeriod
			$period = new \DatePeriod($start, $interval, $end);
			$months = [];
			//$datesrexep = "$year-00";

			foreach ($period as $date) {
				$months[] = "$year-".    sprintf('%02d',(int)$date->format('n'));
				//$datesrexep.= "|$year-".    sprintf('%02d',(int)$date->format('n')); // Numeric month without leading zeros
			   
			}
			return $months;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}