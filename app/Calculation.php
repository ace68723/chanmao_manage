<?php

	namespace App;

	use DB;

	class Calculation{

		public static function getThisWeek(){

			// Current day of the week
			$cur = DB::select('SELECT WEEKDAY((SELECT CURDATE())) as cur')[0]->cur;

			// SQL find this monday
			$fir = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?+1 DAY) as fir', array($cur))[0]->fir;
			$firstDay = DB::select('SELECT UNIX_TIMESTAMP(?) as firstDay', array($fir))[0]->firstDay;

			// SQL find this sunday
			$lst = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-5 DAY) as lst', array($cur))[0]->lst;
			$lastDay = DB::select('SELECT UNIX_TIMESTAMP(?) as lastDay', array($lst))[0]->lastDay;
			
			// Number, String
			return array(array($firstDay, $lastDay+ 86399), array($fir, $lst));
		}

		public static function getNextWeek(){

			// Current day of the week
			$cur = DB::select('SELECT WEEKDAY((SELECT CURDATE())) as cur')[0]->cur;

			// SQL find this monday
			$fir = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-6 DAY) as fir', array($cur))[0]->fir;
			$firstDay = DB::select('SELECT UNIX_TIMESTAMP(?) as firstDay', array($fir))[0]->firstDay;

			// SQL find this sunday
			$lst = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-12 DAY) as lst', array($cur))[0]->lst;
			$lastDay = DB::select('SELECT UNIX_TIMESTAMP(?) as lastDay', array($lst))[0]->lastDay;

			// Number, String
			return array(array($firstDay, $lastDay + 86399), array($fir, $lst));

		}

		public static function getThisWeekDates(){

			$cur = DB::select('SELECT WEEKDAY((SELECT CURDATE())) as cur')[0]->cur;

			// SQL find this monday
			$day1 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?+1 DAY) as day', array($cur))[0]->day;
			$day2 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ? DAY) as day', array($cur))[0]->day;
			$day3 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-1 DAY) as day', array($cur))[0]->day;
			$day4 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-2 DAY) as day', array($cur))[0]->day;
			$day5 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-3 DAY) as day', array($cur))[0]->day;
			$day6 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-4 DAY) as day', array($cur))[0]->day;
			$day7 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-5 DAY) as day', array($cur))[0]->day;

			
			return array($day1, $day2, $day3, $day4, $day5, $day6, $day7);
		}

		public static function getNextWeekDates(){

			$cur = DB::select('SELECT WEEKDAY((SELECT CURDATE())) as cur')[0]->cur;

			// SQL find this monday
			$day1 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-6 DAY) as day', array($cur))[0]->day;
			$day2 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-7 DAY) as day', array($cur))[0]->day;
			$day3 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-8 DAY) as day', array($cur))[0]->day;
			$day4 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-9 DAY) as day', array($cur))[0]->day;
			$day5 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-10 DAY) as day', array($cur))[0]->day;
			$day6 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-11 DAY) as day', array($cur))[0]->day;
			$day7 = DB::select('SELECT DATE_SUB( (SELECT CURDATE()), INTERVAL ?-12 DAY) as day', array($cur))[0]->day;

			
			return array($day1, $day2, $day3, $day4, $day5, $day6, $day7);
		}



	}
?>