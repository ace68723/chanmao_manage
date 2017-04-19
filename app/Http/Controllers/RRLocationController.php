<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Authentication;

	class RRLocationController extends Controller{
	

		public function getRRLocation($rid){


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
				
				$data = DB::select('SELECT *
										FROM cm_rr_loc
										WHERE rid = ? 
										ORDER BY area ', array($rid));

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data
		  	];

		    return response()->json($response, $error_code);

	    }

	    private function getRRLocationArea($rid, $area){

		
			return DB::select('SELECT *
										FROM cm_rr_loc
										WHERE rid = ? AND area = ?
										ORDER BY area ', array($rid, $area));


	    }

	    public function createRRLocation(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */ww
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}
            
                
			elseif(sizeof($request->all()) != 12){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}


			elseif(!array_key_exists("rid", $request->all()) || 
					!array_key_exists("area", $request->all()) || 
					!array_key_exists("rr_la", $request->all()) || 
					!array_key_exists("rr_lo", $request->all()) ||
					!array_key_exists("ds_la_nw", $request->all()) ||
					!array_key_exists("ds_lo_nw", $request->all()) ||
					!array_key_exists("ds_la_ne", $request->all()) || 
					!array_key_exists("ds_lo_ne", $request->all()) || 
					!array_key_exists("ds_la_sw", $request->all()) || 
					!array_key_exists("ds_lo_sw", $request->all()) ||
					!array_key_exists("ds_la_se", $request->all()) ||
					!array_key_exists("ds_lo_se", $request->all())
					){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}



			elseif(!is_int($request->rid) || 
					!is_int($request->area) || 
					!is_float($request->rr_la) || 
					!is_float($request->rr_lo) ||
					!is_float($request->ds_la_nw) || 
					!is_float($request->ds_lo_nw) || 
					!is_float($request->ds_la_ne) || 
					!is_float($request->ds_lo_ne) ||
					!is_float($request->ds_la_sw) || 
					!is_float($request->ds_lo_sw) || 
					!is_float($request->ds_la_se) || 
					!is_float($request->ds_lo_se))
			{
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}



			// Coordinate correctness, CHECK!!
			// elseif(strtotime($request->valid_from) > strtotime($request->valid_to)){


			// }
			elseif($request-> area < 0 || $request->area > 2){
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Area code must be 1, 2, or 3';
			}

			elseif(self::getRRLocationArea($request->rid, $request->area)){
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Area already exists';
			}

			else{


				$obj = array('rid' => $request->rid, 
					'area' => $request->area, 
					'rr_la' => $request->rr_la, 
					'rr_lo' => $request->rr_lo, 
					'ds_la_nw' => $request->ds_la_nw,
					'ds_lo_nw' => $request->ds_lo_nw, 
					'ds_la_ne' => $request->ds_la_ne, 
					'ds_lo_ne' => $request->ds_lo_ne, 
					'ds_la_sw' => $request->ds_la_sw,
					'ds_lo_sw' => $request->ds_lo_sw, 
					'ds_la_se' => $request->ds_la_se, 
					'ds_lo_se' => $request->ds_lo_se
					);


				DB::statement('INSERT INTO cm_rr_loc 
								(rid, area, rr_la, rr_lo, ds_la_nw, ds_lo_nw, ds_la_ne, ds_lo_ne,
								 ds_la_sw, ds_lo_sw, ds_la_se, ds_lo_se)
								VALUES 
								(?, 
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								?,
								?
								)', array_values($obj));
			}

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	  
	    }

	    public function updateRRLocation(Request $request){

	    	$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}
            
                
			elseif(sizeof($request->all()) != 13){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}


			elseif(!array_key_exists("id", $request->all()) || 
					!array_key_exists("rid", $request->all()) || 
					!array_key_exists("area", $request->all()) || 
					!array_key_exists("rr_la", $request->all()) || 
					!array_key_exists("rr_lo", $request->all()) ||
					!array_key_exists("ds_la_nw", $request->all()) ||
					!array_key_exists("ds_lo_nw", $request->all()) ||
					!array_key_exists("ds_la_ne", $request->all()) || 
					!array_key_exists("ds_lo_ne", $request->all()) || 
					!array_key_exists("ds_la_sw", $request->all()) || 
					!array_key_exists("ds_lo_sw", $request->all()) ||
					!array_key_exists("ds_la_se", $request->all()) ||
					!array_key_exists("ds_lo_se", $request->all())

					){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}



			elseif(!is_int($request->id) ||
					!is_int($request->rid) || 
					!is_int($request->area) || 
					!is_float($request->rr_la) || 
					!is_float($request->rr_lo) ||
					!is_float($request->ds_la_nw) || 
					!is_float($request->ds_lo_nw) || 
					!is_float($request->ds_la_ne) || 
					!is_float($request->ds_lo_ne) ||
					!is_float($request->ds_la_sw) || 
					!is_float($request->ds_lo_sw) || 
					!is_float($request->ds_la_se) || 
					!is_float($request->ds_lo_se))
			{
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}



			// Coordinate correctness, CHECK!!
			// elseif(strtotime($request->valid_from) > strtotime($request->valid_to)){


			// }

			else{
				$obj = array( 'rid' => $request->rid, 
					'area' => $request->area, 
					'rr_la' => $request->rr_la, 
					'rr_lo' => $request->rr_lo, 
					'ds_la_nw' => $request->ds_la_nw,
					'ds_lo_nw' => $request->ds_lo_nw, 
					'ds_la_ne' => $request->ds_la_ne, 
					'ds_lo_ne' => $request->ds_lo_ne, 
					'ds_la_sw' => $request->ds_la_sw,
					'ds_lo_sw' => $request->ds_lo_sw, 
					'ds_la_se' => $request->ds_la_se, 
					'ds_lo_se' => $request->ds_lo_se,
					'id' => $request->id
					);


				DB::statement('UPDATE cm_rr_loc 
								SET rid = ?, 
								area = ?, 
								rr_la = ?,
								rr_lo = ?,
								ds_la_nw = ?,
								ds_lo_nw = ?,
								ds_la_ne = ?,
								ds_lo_ne = ?,
								ds_la_sw = ?,
								ds_lo_sw = ?,
								ds_la_se = ?,
								ds_lo_se = ?
								WHERE id = ?', array_values($obj));
			}

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	  
	    }
	  
	}
?>
