<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;
	use App\Checkout;

	class TestController extends Controller{

	    public function index(){

	    	// ALTER TABLE cm_user ADD password varchar(32) NOT NULL;

			// UPDATE 
			// ChanMao.username_tb,
			// test_db.password_tb
			// SET ChanMao.username_tb.password = test_db.password_tb.password
			// WHERE ChanMao.username_tb.id = test_db.password_tb.id

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();

			$encrypted = md5(md5('asdwea').'9456c8'); 
			echo($encrypted);

			//$this->login('asd', 'asdasd');	
		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data
		  	];

		    return response()->json($response, $error_code);

	    }

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

	   	private function register($username, $encrypted){


			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();

			$result = DB::select('INSERT INTO cm_user(username, email, activekey, displayname, status, lastadid, created, token, expired) VALUES (?,?,?,?,?,?,?,?,?)', array());

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data
		  	];

		    return response()->json($response, $error_code);


	   	}

	   	public function test(){

	   		return Checkout::refund();
	   	}



	}
?>
