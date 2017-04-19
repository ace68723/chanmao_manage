<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Authentication;
	

	class AdvertisementController extends Controller{


	    public function getLaunch(){


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
				
				$data = DB::select('SELECT id, navigation, status, avlb_from, avlb_to, image
										FROM cm_ad_launch
										where status = 0');

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_ad_launch' => $data
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function addLaunch(Request $request){
			
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

			elseif(!array_key_exists("id", $request->all()) || 
					!array_key_exists("navigation", $request->all()) || 
					!array_key_exists("status", $request->all()) || 
					!array_key_exists("avlb_from", $request->all()) || 
					!array_key_exists("avlb_to", $request->all()) || 
					!array_key_exists("image", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->id) || 
					!is_string($request->navigation) || 
					!is_int($request->status) || 
					!is_int($request->avlb_from) || 
					!is_int($request->avlb_to) || 
					!is_string($request->image)) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			else{
				DB::statement('INSERT INTO cm_ad_launch(id, navigation, status, avlb_from, avlb_to, image) 
								VALUES(?, ?, ?, ?, ?, ?)', array_values($request->all()));
			}

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	    }


	    public function getTop(){


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
				
				$data = DB::select('SELECT zone, name as zone_description FROM cm_app_zone_base');

				foreach ($data as &$zone){


					$zone->adList = DB::select('SELECT id, rid, zone, rank, startdate as start_date, enddate as end_date
										FROM cm_rr_ad
										where status = 0 AND zone = ?', array($zone->zone));

				}



		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_ad_rr' => $data
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function addTop(Request $all_request){
			
			$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}

			else{

				DB::statement('UPDATE cm_rr_ad SET status = 1');
				foreach($all_request->all() as &$request){

					if(sizeof($request) != 7){
							
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Number of Invalid amount of arguments';
						break;

					}

					elseif(!array_key_exists("id", $request) || 
							!array_key_exists("rid", $request) || 
							!array_key_exists("zone", $request) || 
							!array_key_exists("rank", $request) || 
							!array_key_exists("start_date", $request) || 
							!array_key_exists("end_date", $request) || 
							!array_key_exists("status", $request)) {
						
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Field name incorrect';
						break;

					}

					elseif(!is_int($request['id']) || 
							!is_int($request['rid']) || 
							!is_int($request['zone']) || 
							!is_int($request['rank']) || 
							!is_int($request['start_date']) || 
							!is_int($request['end_date']) || 
							!is_int($request['status'])) {
						
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Field type incorrect';
						break;	

					}

					else{

						$temp_array = array();
						$temp_array = array_merge($temp_array, array_values($request), array_values($request));

						DB::statement('INSERT INTO cm_rr_ad(id, rid, zone, rank, startdate, enddate, status, scenario, lastuid, lastchange) 
										VALUES(?, ?, ?, ?, ?, ?, ?, "APPAD", 0, 0)
										ON DUPLICATE KEY UPDATE id = ?,
										rid = ?,
										zone = ?,
										rank = ?, 
										startdate = ?,
										enddate = ?, 
										status = ?,
										scenario = "APPAD", 
										lastuid = 0,
										lastchange = 0', $temp_array);

					}
				}

			}
		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	    }
	   	
	   	public function getHome(){


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
				
				$ad_home1 = DB::select('SELECT id, navitype, navi_url, image, navi_rid, sequence
										FROM cm_ad_apphome
										where status = 0 AND zone = 1');

				$ad_home2 = DB::select('SELECT id, navitype, navi_url, image, navi_rid, sequence
										FROM cm_ad_apphome
										where status = 0 AND zone = 2');

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_ad_home1' => $ad_home1,
				"ea_ad_home2" => $ad_home2
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function addHome(Request $request){
			
			$ev_result = 0;
	    	$error_code = 200;
			$error_msg = Authentication::decodeToeken();

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}

			elseif(sizeof($request->all()) != 2) {
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}

			elseif(!array_key_exists("ad_home1", $request->all()) || 
					!array_key_exists("ad_home2", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_array($request->ad_home1) || 
					!is_array($request->ad_home2)) {
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field type incorrect';	

			}

			else{

				DB::statement('UPDATE cm_rr_ad SET status = 1');

				foreach ($request->ad_home1 as $ad1){

					if(sizeof($ad1) != 6){
					
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Number of Invalid amount of arguments';
						break;

					}

					elseif(!array_key_exists("id", $ad1) || 
							!array_key_exists("navitype", $ad1) || 
							!array_key_exists("navi_url", $ad1) || 
							!array_key_exists("image", $ad1) || 
							!array_key_exists("navi_rid", $ad1) || 
							!array_key_exists("sequence", $ad1)) {
						
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Field name incorrect';
						break;

					}

					elseif(!is_int($ad1['id']) || 
							!is_int($ad1['navitype']) || 
							!is_string($ad1['navi_url']) || 
							!is_string($ad1['image']) || 
							!is_int($ad1['navi_rid']) || 
							!is_int($ad1['sequence'])) {
						
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Field type incorrect';
						break;	

					}

					else{


						$temp_array = array();
						$temp_array = array_merge($temp_array, array_values($ad1), array_values($ad1));

						DB::statement('INSERT INTO cm_ad_apphome(id, navitype, navi_url, image, navi_rid, sequence) 
										VALUES(?, ?, ?, ?, ?, ?)
										ON DUPLICATE KEY 
										UPDATE id = ?,
										navitype = ?, 
										navi_url = ?, 
										image = ?, 
										navi_rid = ?, 
										sequence = ?', $temp_array);
					}
				}
				
				foreach ($request->ad_home2 as $ad2){

					if(sizeof($ad2) != 6){
					
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Number of Invalid amount of arguments';
						break;

					}

					elseif(!array_key_exists("id", $ad2) || 
							!array_key_exists("navitype", $ad2) || 
							!array_key_exists("navi_url", $ad2) || 
							!array_key_exists("image", $ad2) || 
							!array_key_exists("navi_rid", $ad2) || 
							!array_key_exists("sequence", $ad2)) {
						
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Field name incorrect';
						break;

					}

					elseif(!is_int($ad2['id']) || 
							!is_int($ad2['navitype']) || 
							!is_string($ad2['navi_url']) || 
							!is_string($ad2['image']) || 
							!is_int($ad2['navi_rid']) || 
							!is_int($ad2['sequence'])) {
						
						$ev_result = 1;
						$error_code = 401;
						$error_msg = 'Field type incorrect';
						break;	

					}

					else{


						$temp_array = array();
						$temp_array = array_merge($temp_array, array_values($ad2), array_values($ad2));

						DB::statement('INSERT INTO cm_ad_apphome(id, navitype, navi_url, image, navi_rid, sequence) 
										VALUES(?, ?, ?, ?, ?, ?)
										ON DUPLICATE KEY 
										UPDATE id = ?,
										navitype = ?, 
										navi_url = ?, 
										image = ?, 
										navi_rid = ?, 
										sequence = ?', $temp_array);
					}

				
				}

			}

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
		  	];

	        return response()->json($response, $error_code);
	    }
	    
	  
	}
?>
