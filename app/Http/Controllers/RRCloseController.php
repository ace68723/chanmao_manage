<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Cm_rr_close;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;

	class RRCloseController extends Controller{
	  

	    public function index(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$RRClose = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
				
				$RRClose = DB::select('CALL get_rrclose');
				
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ev_data' => $RRClose
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function createRRClose(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}

			else{
				
				/* Number of parameter exceeded 3 */
				if(sizeof($request->all()) != 3){
					
					$ev_result = 1;
					$error_code = 401;
					$error_msg = 'Number of Invalid amount of arguments';
				}

				else{

					DB::statement('CALL insert_rrclose(?, ?, ?)', array_values($request->all()));

				}
				
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	  
	    }
	  
	  
	    public function updateRRClose(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}
			else{

				/* Number of parameter exceeded 3 */
				if(sizeof($request->all()) != 4){
					
					$ev_result = 1;
					$error_code = 401;
					$error_msg = 'Number of Invalid amount of arguments';
				}


				else{

			    	//$update_array = array_merge(array_values($request->all()), array($close_id));
					DB::statement('CALL update_rrclose(?, ?, ?, ?)', array_values($request->all()));
		  		}
	        
	        }

	        $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);


	    }
	  
	}
?>
