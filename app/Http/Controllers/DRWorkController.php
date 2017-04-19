<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;
	use App\Calculation;

	class DRWorkController extends Controller{

	    public function index(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{

		    	
		    	// find today's date
		    	$today = DB::select('SELECT UNIX_TIMESTAMP((SELECT CURDATE())) as `today`');
				
				$data = DB::select('SELECT ds.id, ds.driver_id, ds.wid, ds.valid_from, 
										ds.valid_to, ds.zone, ds.comment, db.driver_name, dz.name
										FROM cm_driver_zone_schedule ds
										LEFT JOIN cm_driver_base db ON ds.driver_id = db.driver_id
										LEFT JOIN cm_driver_zone_base dz ON ds.zone = dz.zone
										WHERE ds.status = 0 AND ds.valid_to >= ?
										ORDER BY ds.valid_from DESC', array($today[0]->today));


				
				foreach ($data as &$work){
					$work->valid_from = date('Y-m-d H:i:s', $work->valid_from);
					$work->valid_to = date('Y-m-d H:i:s', $work->valid_to);

				}

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function getDRThisWeek(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{

		    	$thisWeek = Calculation::getThisWeek()[0];
		    	
				$data = DB::select('SELECT ds.id, ds.driver_id, ds.wid, ds.valid_from, 
										ds.valid_to, ds.zone, ds.comment, db.driver_name, dz.name
										FROM cm_driver_zone_schedule ds
										LEFT JOIN cm_driver_base db ON ds.driver_id = db.driver_id
										LEFT JOIN cm_driver_zone_base dz ON ds.zone = dz.zone
										WHERE ds.status = 0 AND ds.valid_from >= ? AND ds.valid_to <= ?
										ORDER BY ds.valid_from DESC', $thisWeek);


				
				foreach ($data as &$work){
					$work->week_index = floor(($work->valid_from-$thisWeek[0])/86400);
					$work->valid_from = date('Y-m-d H:i:s', $work->valid_from);
					$work->valid_to = date('Y-m-d H:i:s', $work->valid_to);
					$work->zone = array('code' => $work->zone, 'name' => $work->name);
					unset($work->name);

				}

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function getOneDRThisWeek($driver_id){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();
			$week = '';

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{

		    	$weekInfo = Calculation::getThisWeek();
		    	$thisWeek = $weekInfo[0];
				$week = $weekInfo[1];

				$merged = array_merge(array($driver_id), $thisWeek);

				$data = DB::select('SELECT ds.id, ds.driver_id, ds.wid, ds.valid_from, 
										ds.valid_to, ds.zone, ds.comment, db.driver_name, dz.name
										FROM cm_driver_zone_schedule ds
										LEFT JOIN cm_driver_base db ON ds.driver_id = db.driver_id
										LEFT JOIN cm_driver_zone_base dz ON ds.zone = dz.zone
										WHERE ds.status = 0 AND ds.driver_id = ? 
										AND ds.valid_from >= ? AND ds.valid_to <= ?
										ORDER BY ds.valid_from DESC', $merged);


				foreach ($data as &$work){
					$work->week_index = floor(($work->valid_from-$thisWeek[0])/86400);
					$work->valid_from = date('Y-m-d H:i:s', $work->valid_from);
					$work->valid_to = date('Y-m-d H:i:s', $work->valid_to);
					$work->zone = array('code' => $work->zone, 'name' => $work->name);
					unset($work->name);

				}

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data,
				'ea_week' => $week
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function getOneDRNextWeek($driver_id){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();
			$week = '';

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{


		    	$weekInfo = Calculation::getNextWeek();
		    	$nextWeek = $weekInfo[0];
				$week = $weekInfo[1];
		    	
				$merged = array_merge(array($driver_id), $nextWeek);

				$data = DB::select('SELECT ds.id, ds.driver_id, ds.wid, ds.valid_from, 
										ds.valid_to, ds.zone, ds.comment, db.driver_name, dz.name
										FROM cm_driver_zone_schedule ds
										LEFT JOIN cm_driver_base db ON ds.driver_id = db.driver_id
										LEFT JOIN cm_driver_zone_base dz ON ds.zone = dz.zone
										WHERE ds.status = 0 AND ds.driver_id = ? 
										AND ds.valid_from >= ? AND ds.valid_to <= ?
										ORDER BY ds.valid_from DESC', $merged);


				
				foreach ($data as &$work){
					$work->week_index = floor(($work->valid_from-$nextWeek[0])/86400);
					$work->valid_from = date('Y-m-d H:i:s', $work->valid_from);
					$work->valid_to = date('Y-m-d H:i:s', $work->valid_to);
					$work->zone = array('code' => $work->zone, 'name' => $work->name);
					unset($work->name);

				}

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data,
				'ea_week' => $week
		  	];

		    return response()->json($response, $error_code);

	    }



	  //   public function createDRWork_BU(Request $request){

	  //   	$ev_result = 0;
	  //   	$error_code = 200;
			// $error_msg = Authentication::decodeToeken();

			// /* Authentication Failed */
			// if($error_msg){

			// 	$ev_result = 1;
			// 	$error_code = 401;

			// }
            
            
                
			// elseif(sizeof($request->all()) != 4){
					
			// 	$ev_result = 1;
			// 	$error_code = 401;
			// 	$error_msg = 'Number of Invalid amount of arguments';

			// }

			// elseif(!array_key_exists("driver_id", $request->all()) || 
			// 		!array_key_exists("valid_from", $request->all()) || 
			// 		!array_key_exists("valid_to", $request->all()) || 
			// 		!array_key_exists("zone", $request->all())){
				
			// 	$ev_result = 1;
			// 	$error_code = 401;
			// 	$error_msg = 'Field name incorrect';

			// }

			// elseif(!is_int($request->driver_id) || 
			// 		!is_string($request->valid_from) || 
			// 		!is_string($request->valid_to) || 
			// 		!is_int($request->zone)) {
				
			// 	$ev_result = 1;
			// 	$error_code = 401;
			// 	$error_msg = 'Field type incorrect';	

			// }

			// elseif(strtotime($request->valid_from) > strtotime($request->valid_to)){

			// 	$ev_result = 1;
			// 	$error_code = 401;
			// 	$error_msg = 'Start time greater than end time';
			// }

			// else{
			// 	$obj = array('driver_id' => $request->driver_id, 
			// 		'driver_id2' => $request->driver_id, 
			// 		'valid_from' => strtotime($request->valid_from), 
			// 		'valid_to' => strtotime($request->valid_to), 'zone' => $request->zone);

			// 	DB::statement('INSERT INTO cm_driver_zone_schedule 
			// 					(driver_id, wid, valid_from, valid_to, zone, status, updated_by, updated_at)
			// 					VALUES 
			// 					(?, 
			// 					(SELECT wid FROM cm_driver_base WHERE ? = driver_id),
			// 					?,
			// 					?,
			// 					?,
			// 					0,
			// 					0,
			// 					0
			// 					)', array_values($obj));
			// }

		 //    $response = [
			//   	'ev_result' => $ev_result,
			// 	'ev_message' => $error_msg,
		 //  	];

	  //       return response()->json($response, $error_code);
	  
	  //   }
	  

	  public function createDRWork(Request $all_request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}
            
            foreach($all_request->all() as &$request){
                

				if(sizeof($request) != 4){
						
					$ev_result = 1;
					$error_code = 401;
					$error_msg = 'Number of Invalid amount of arguments';
					break;

				}

				elseif(!array_key_exists("driver_id", $request) || 
						!array_key_exists("valid_from", $request) || 
						!array_key_exists("valid_to", $request) || 
						!array_key_exists("zone", $request)){
					
					$ev_result = 1;
					$error_code = 401;
					$error_msg = 'Field name incorrect';
					break;

				}

				elseif(!is_int($request['driver_id']) || 
						!is_string($request['valid_from']) || 
						!is_string($request['valid_to']) || 
						!is_int($request['zone'])) {
					
					$ev_result = 1;
					$error_code = 401;
					$error_msg = 'Field type incorrect';	
					break;

				}

				elseif(strtotime($request['valid_from']) > strtotime($request['valid_to'])){

					$ev_result = 1;
					$error_code = 401;
					$error_msg = 'Start time greater than end time';
					break;
				}

				else{
					$obj = array('driver_id' => $request['driver_id'], 
						'driver_id2' => $request['driver_id'], 
						'valid_from' => strtotime($request['valid_from']), 
						'valid_to' => strtotime($request['valid_to']), 'zone' => $request['zone']);

					DB::statement('INSERT INTO cm_driver_zone_schedule 
									(driver_id, wid, valid_from, valid_to, zone, status, updated_by, updated_at)
									VALUES 
									(?, 
									(SELECT wid FROM cm_driver_base WHERE ? = driver_id),
									?,
									?,
									?,
									0,
									0,
									0
									)', array_values($obj));
				}
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

			elseif(sizeof($request->all()) != 5){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}

			elseif(!array_key_exists("id", $request->all()) || 
					!array_key_exists("driver_id", $request->all()) || 
					!array_key_exists("valid_from", $request->all()) || 
					!array_key_exists("valid_to", $request->all()) || 
					!array_key_exists("zone", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->id) || 
					!is_int($request->driver_id) || 
					!is_string($request->valid_from) || 
					!is_string($request->valid_to) || 
					!is_int($request->zone)) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			elseif(strtotime($request->valid_from) > strtotime($request->valid_to)){

				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Start time greater than end time';
			}

			else{

				$obj = array('driver_id' => $request->driver_id, 
							'driver_id2' => $request->driver_id, 
							'valid_from' => strtotime($request->valid_from), 
							'valid_to' => strtotime($request->valid_to), 
							'zone' => $request->zone, 
							'id' => $request->id);
				
				DB::statement('UPDATE cm_driver_zone_schedule
								SET 
								driver_id = ?, 
								wid = (SELECT wid FROM cm_driver_base WHERE ? = driver_id),
								valid_from = ?,
								valid_to = ?,
								zone = ?
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
				DB::statement('UPDATE cm_driver_zone_schedule 
								SET status = 1 
								WHERE id = ?', array_values($request->all()));
			}
	
	        $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);


	    }



	  
	}
?>
