<?php

	namespace App;

	class Varification{

		public static function exceed($word, $slen, $field){

			if(strlen($word) > $slen){
				return "$field exceeded $slen characters";
			}
			return '';
		}
	}
?>