<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;

	class DriverPayController extends Controller{

	    public function getDriverTaxes(Request $request){


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
				
				$data = DB::select("SELECT EXPT1.driver_id, EXPT2.driver_name, EXPT1.dlexp_sum, 
					EXPT1.dlexp_sum * (SELECT taxrate FROM cm_sys_taxrate WHERE prvn = 'ON') / 100 AS dltax 
					FROM (SELECT driver_id, sum(dlexp) AS dlexp_sum 
						FROM (SELECT T1.oid AS oid, T1.driver_id AS driver_id, T2.dlexp as dlexp 
							FROM (SELECT oid, driver_id FROM cm_order_trace AS co WHERE `create` >= 1488384162 AND `create` <= 1489128953) AS T1
							JOIN (SELECT oid, dlexp FROM cm_order_base WHERE (dltype = 1 OR dltype = 3) AND status = 40) AS T2 
							ON T1.oid = T2.oid) AS EXPT1 GROUP BY driver_id) AS EXPT1 
					JOIN (SELECT driver_id, driver_name FROM cm_driver_base) AS EXPT2 ON EXPT1.driver_id = EXPT2.driver_id");

				//$cm_od = DB::select("SELECT oid, wid FROM cm_order_delivery WHERE request >= '2017-03-01 11:02:42' AND request <= '2017-03-10 01:55:53'", array(date('Y-m-d H:i:s', $request->all()->valid_from), date('Y-m-d H:i:s', $request->all()->valid_to)));

			}




		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data
		  	];

		    return response()->json($response, $error_code);

	    }

	    public function getOneDriverTax(Request $request){


			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();
			$result = array();
			$total = (object)[];

			/* Authentication Failed */
			if($error_msg){

				$ev_result = 1;
				$error_code = 401;

			}


			elseif(sizeof($request->all()) != 3){
					
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Number of Invalid amount of arguments';

			}

			elseif(!array_key_exists("id", $request->all()) || 
					!array_key_exists("valid_from", $request->all()) || 
					!array_key_exists("valid_to", $request->all())){
				
				$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';

			}

			elseif(!is_int($request->id) || 
					!is_string($request->valid_from) || 
					!is_string($request->valid_to)) {
				
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
				
				$id = $request->id;
				$valid_from = strtotime($request->valid_from);
				$valid_to = strtotime($request->valid_to);
				


				$data = DB::select("SELECT T1.oid AS oid, T2.dlexp as dlexp, T1.create as `create`
							FROM (SELECT oid, driver_id, `create` FROM cm_order_trace AS co WHERE `create` >= ? AND `create` <= ? AND `driver_id` = ?) AS T1
							JOIN (SELECT oid, dlexp FROM cm_order_base WHERE (dltype = 1 OR dltype = 3) AND status = 40) AS T2 
							ON T1.oid = T2.oid", array($valid_from, $valid_to, $id));

				$tax = DB::select("SELECT taxrate FROM cm_sys_taxrate WHERE prvn = 'ON'")[0]->taxrate/100;
				

				$total_dlexp = 0;
				$total_dlexp_tax = 0;
				$total_count = 0;


				$start = $valid_from;
				while($start < $valid_to + 86399){

					$temp_class = (object)[];
					$temp_class->date = date('Y-m-d', $start);
					$temp_class->dlexp = 0;
					$temp_class->dlexp_tax = 0;
					$temp_class->count = 0;


					if($start == $valid_from){
						
						$end = $start + 93600;
						
					}
					else{

						$end = $start + 86400;

					}

					foreach ($data as &$orders){

						if($orders->create >= $start && $orders->create <= $end){

							$temp_class->dlexp += $orders->dlexp;
							$temp_class->count += 1;
						}

						if($orders->create > $end){
							break;
						}
					}

					$temp_class->dlexp_tax = $temp_class->dlexp * $tax;
					$total_dlexp += $temp_class->dlexp;
					$total_dlexp_tax += $temp_class->dlexp_tax;	
					$total_count += $temp_class->count;
					$start = $end + 1;
					array_push($result, $temp_class);
				}

				$total->total_dlexp = $total_dlexp;
				$total->total_dlexp_tax = $total_dlexp_tax;
				$total->total_count = $total_count;
				//$cm_od = DB::select("SELECT oid, wid FROM cm_order_delivery WHERE request >= '2017-03-01 11:02:42' AND request <= '2017-03-10 01:55:53'", array(date('Y-m-d H:i:s', $request->all()->valid_from), date('Y-m-d H:i:s', $request->all()->valid_to)));

			}




		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $result,
				'ea_total' => $total
		  	];

		    return response()->json($response, $error_code);

	    }

	}
?>
