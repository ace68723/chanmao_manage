<?php

	namespace App;

	use DB;
	use \Firebase\JWT\JWT;

	class Authentication{

		
		private static function getSecret(){

			$param = 'CM_TOKEN_SECRET';
			$result = DB::select('SELECT value FROM cm_sys_param WHERE param=?', array($param));

			return $result[0]->value;

		}

		public static function decodeToeken(){

        	$secret = self::getSecret();
			
			$headers = apache_request_headers();
			
			// Do Error check
			try{
				$jwt = $headers['Authortoken'];
			}
			catch(\Exception $e) {

				try{
					$jwt = $headers['authortoken'];
				}
				catch(\Exception $e) {
					return 'Cannot find header: Authortoken/authortoken';
				}
				
			}

			try{
				$decoded = JWT::decode($jwt, $secret, array('HS256'));
			}
			catch(\Exception $e) {
				return 'Authrorization Failed: invalid token';
				
			}

			try{
				$Auth = DB::select('SELECT * FROM cm_user_group_link WHERE uid = ? AND gid = 3 AND status = 0', array($decoded->uid));
				if($Auth == []){
					return 'Authorization Failed: Permission denied';
				}
			}
			
			catch(\Exception $e) {
				return 'Authorization Failed: unrecognized user';
				
			}
			
	  		return '';
		}



	}

?>


