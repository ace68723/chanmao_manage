<?php

	namespace App;

	class Login{


		private function login($username, $encrypted){

	    	// 0 false, 1 true
	    	$valid = 0;


	    	$result = DB::select('SELECT username 
	    							FROM cm_user 
	    							WHERE username = ? AND 
	    							password = ?', array($username, $encrypted));

	    	if($result){
	    		$valid = 1;
	    	}

	    	return $valid;
	    }

	}
?>