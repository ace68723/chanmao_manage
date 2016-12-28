<?php
  
	namespace App\Http\Controllers;

	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;

	class CSRoleController extends Controller{
	  

	    public function index(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$CSRole = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
				
				$temp = DB::select('CALL CSInfo');

				foreach ($temp as &$value){
					if(!strcmp($value->bp,"MONITOR")){
						$obj = (object) array('uid' => $value->uid, 'username' => $value->username);
						array_push($CSRole, $obj);
					}
				}
				
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $CSRole
		  	];

		    return response()->json($response, $error_code);

	    }
	  
	}
?>
