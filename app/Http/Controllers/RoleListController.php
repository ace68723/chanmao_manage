<?php
  
	namespace App\Http\Controllers;

	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;

	class RoleListController extends Controller{
	  

	    public function CSRoles(){

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
				
				$temp = DB::select('CALL CSInfo(1)');

				foreach ($temp as &$value){
					$obj = (object) array('uid' => $value->uid, 'username' => $value->username);
					array_push($CSRole, $obj);
					
				}
				
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $CSRole
		  	];

		    return response()->json($response, $error_code);

	    }


	   	public function DRList(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$drivers = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
				
				$drivers = DB::select('SELECT driver_id, driver_name FROM cm_driver_base ORDER BY driver_name');

				
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $drivers
		  	];

		    return response()->json($response, $error_code);

	    }
	  
	}
?>
