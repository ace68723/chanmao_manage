<?php

	namespace App;

	class Calculation{

		public static function getThisWeek(){

			$firstDay = mktime(0, 0, 0, date("n"), date("j") - date("N") + 1);
			$lastDay = mktime(0, 0, 0, date("n"), date("j") - date("N") + 7);
			$ts = array($firstDay, $lastDay);
			print_r($ts);
			return $ts;
		}

		public static function getNextWeek(){

			$firstDay = mktime(0, 0, 0, date("n"), date("j") - date("N") + 8);
			$lastDay = mktime(0, 0, 0, date("n"), date("j") - date("N") + 14);
			$ts = array($firstDay, $lastDay);
			print_r($ts);
			return $ts;
		}


	}
?>