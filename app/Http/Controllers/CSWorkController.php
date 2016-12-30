<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Authentication;
	use App\Calculation;
	

	class CSWorkController extends Controller{


	    public function index(){


			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$result = array();
			

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
				
				$result = DB::select('SELECT cs.id, cs.uid, cr.username, cs.valid_from, cs.valid_to, cs.zone
										FROM cm_cs_zone_user cs left join cm_user cr ON cs.uid = cr.uid
										WHERE cs.status = 0
										ORDER BY id DESC');


				foreach ($result as &$work){
					$work->valid_from = date('Y-m-d H:i:s', $work->valid_from);
					$work->valid_to = date('Y-m-d H:i:s', $work->valid_to);
				} 
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $result
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function createCSWork(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}

			elseif(sizeof($request->all()) != 4){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}

			elseif(!array_key_exists("uid", $request->all()) || !array_key_exists("valid_from", $request->all()) || !array_key_exists("valid_to", $request->all()) || !array_key_exists("zone", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->uid) || !is_string($request->valid_from) || !is_string($request->valid_to) || !is_int($request->zone)) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			else{

				$obj = array('uid' => $request->uid, 'valid_from' => strtotime($request->valid_from), 'valid_to' => strtotime($request->valid_to), 'zone' => $request->zone);

				DB::statement('INSERT INTO 
					cm_cs_zone_user(uid, valid_from, valid_to, zone, status, updated_at, updated_by)
					VALUES (?, ?, ?, ?, 0, 0, 0)', array_values($obj));
			}

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	  
	    }
	  
	  
	    public function updateCSWork(Request $request){

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

			elseif(!array_key_exists("id", $request->all()) || !array_key_exists("uid", $request->all()) || !array_key_exists("valid_from", $request->all()) || !array_key_exists("valid_to", $request->all()) || !array_key_exists("zone", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->id) || !is_int($request->uid) || !is_string($request->valid_from) || !is_string($request->valid_to) || !is_int($request->zone) ) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			else{

				$obj = array('uid' => $request->uid, 'valid_from' => strtotime($request->valid_from), 'valid_to' => strtotime($request->valid_to), 'zone' => $request->zone, 'id' => $request->id);
				
				DB::statement('UPDATE cm_cs_zone_user
								SET 
								uid = ?,
								valid_from = ?,
								valid_to = ?,
								zone = ?
								WHERE ? = id', array_values($obj));
			}

	        $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);


	    }


	    public function deleteCSWork(Request $request){

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
				DB::statement('UPDATE cm_cs_zone_user SET status = 1 WHERE id = ?', array_values($request->all()));
			}
	
	        $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);


	    }
	  
	}
?>
