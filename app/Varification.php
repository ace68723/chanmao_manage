<?php

	namespace App;

	class Varification{

		public static function exceed($word, $slen, $field){

			if(strlen($word) > $slen){
				return "$field exceeded $slen characters";
			}
			return '';
		}

		public static function convert($string, $type){

			if($type == 'int'){
				return intval($string);
			}

			if($type == 'time'){
				$date = date_create_from_format('Y-m-d H:i:s', $string);
				return $date->getTimestamp();
			}
		}
	}
?>