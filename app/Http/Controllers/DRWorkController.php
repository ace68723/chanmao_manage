<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;

	class DRWorkController extends Controller{

	    public function index(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$CSWork = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
				
				$CSWork = DB::select('SELECT id, driver_id, wid, valid_from, valid_to, zone, comment
										FROM cm_driver_zone_schedule');


				foreach ($CSWork as &$work){
					$work->valid_from = date('Y-m-d H:i:s', $work->valid_from);
					$work->valid_to = date('Y-m-d H:i:s', $work->valid_to);
				} 
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $CSWork
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function createDRWork(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}
            
            
                
			elseif(sizeof($request->all()) != 5){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}

			elseif(!array_key_exists("driver_id", $request->all()) || !array_key_exists("valid_from", $request->all()) || !array_key_exists("valid_to", $request->all()) || !array_key_exists("zone", $request->all()) || !array_key_exists("comment", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->driver_id) || !is_string($request->valid_from) || !is_string($request->valid_to) || !is_int($request->zone) || !is_string($request->comment)) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			else{
				$obj = array('driver_id' => $request->driver_id, 'driver_id2' => $request->driver_id, 'valid_from' => strtotime($request->valid_from), 'valid_to' => strtotime($request->valid_to), 'zone' => $request->zone, 'comment' => $request->comment);

				DB::statement('INSERT INTO cm_driver_zone_schedule (driver_id, wid, valid_from, valid_to, zone, status, updated_by, updated_at, comment)
								VALUES 
								(?, 
								(SELECT wid FROM cm_driver_base WHERE ? = driver_id),
								?,
								?,
								?,
								0,
								0,
								0,
								?)', array_values($obj));
			}

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	  
	    }
	  
	  
	    public function updateDRWork(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}

			elseif(sizeof($request->all()) != 6){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}

			elseif(!array_key_exists("id", $request->all()) || !array_key_exists("driver_id", $request->all()) || !array_key_exists("valid_from", $request->all()) || !array_key_exists("valid_to", $request->all()) || !array_key_exists("zone", $request->all()) || !array_key_exists("comment", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->id) || !is_int($request->driver_id) || !is_string($request->valid_from) || !is_string($request->valid_to) || !is_int($request->zone) || !is_string($request->comment)) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			else{

				$obj = array('driver_id' => $request->driver_id, 'driver_id2' => $request->driver_id, 'valid_from' => strtotime($request->valid_from), 'valid_to' => strtotime($request->valid_to), 'zone' => $request->zone, 'comment' => $request->comment, 'id' => $request->id);
				
				DB::statement('UPDATE cm_driver_zone_schedule
								SET 
								driver_id = ?, 
								wid = (SELECT wid FROM cm_driver_base WHERE ? = driver_id),
								valid_from = ?,
								valid_to = ?,
								zone = ?,
								comment = ?
								WHERE id = ?', array_values($obj));
			}

	        $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);


	    }


	    public function deleteDRWork(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();


			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}

			elseif(sizeof($request->all()) != 1){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}

			elseif(!array_key_exists("id", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->id)) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			else{
				DB::statement('UPDATE cm_driver_zone_schedule SET status = 1 WHERE id = ?', array_values($request->all()));
			}
	
	        $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);


	    }
	  
	}
?>
