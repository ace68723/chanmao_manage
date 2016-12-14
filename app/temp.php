
<?
		public static function decodeToken(Request $request){



			$result = array();
        	$result['ev_result'] = 0;
        	$result['ev_message'] = ''; 
        	$secret = self::getSecret();

			$authHeader = $request->getHeader('authorization');

			if ($authHeader) {
		        /*
		         * Extract the jwt from the Bearer
		         */
		        list($jwt) = sscanf( $authHeader->toString(), 'Authorization: Bearer %s');

		        if ($jwt) {

		        	try {
                		$config = Factory::fromFile('config/config.php', true);

                		//$asset = base64_encode(file_get_contents('http://lorempixel.com/200/300/cats/'));

                		header('Content-type: application/json');

                		// echo the thing you want

                	}
                	catch (Exception $e) {
	                /*
	                 * the token was not able to be decoded.
	                 * this is likely because the signature was not able to be verified (tampered token)
	                 */
	                header('HTTP/1.0 401 Unauthorized');
	            	}
		        } 

		        else {
		            /*
		             * No token was able to be extracted from the authorization header
		             */
		            header('HTTP/1.0 400 Bad Request');

		        }
		    } 

		    else {
		        /*
		         * The request lacks the authorization token
		         */
		        header('HTTP/1.0 400 Bad Request');
		        echo 'Token not found in request';
		    }
		

		/*

	        try {
	            $result['ev_info'] = JWT::decode($token, $secret, array('HS256'));
	        } catch (Exception $e) {
	            $result['ev_result'] = 1;
	            $result['ev_message'] = $e->getMessage();
	        }
	        */
			return $result;

		}