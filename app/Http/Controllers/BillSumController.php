<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Authentication;
	use App\Calculation;
	

	class BillSumController extends Controller{

		public function printBills(Request $request){

			list($response, $error_code) = self::getBills($request);

			return response()->json($response, $error_code);
		}

	    private function getBills(Request $request){
			
			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$data = array();
			
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

		    elseif(!array_key_exists("all_bills", $request->all()) ||
		    		!array_key_exists("start_time", $request->all()) ||
		    		!array_key_exists("end_time", $request->all()) ||
		    		!array_key_exists("rids", $request->all())) {
		    	
		    	$ev_result = 1;
				$error_code = 401;
				$error_msg = 'Field name incorrect';
		    }

			elseif(!is_int($request['all_bills']) || 
						!is_string($request['start_time']) || 
						!is_string($request['end_time']))
			{
					
					$ev_result = 1;
					$error_code = 401;
					$error_msg = 'Field type incorrect';	

			}

			// Wants all bills
		    elseif($request['all_bills'] == 1)
		    {

				$start = $request["start_time"] . " 00:00:00";
				$end = $request["end_time"] . " 23:59:59";

				$data = DB::select('SELECT T1.rid, T1.pretax, T2.name, T3.rate 
					FROM (SELECT rid, sum(pretax) AS pretax FROM cm_order_base WHERE dltype <= 10 AND (dltype = 0 OR dltype = 1 OR dltype = 3) AND status <= 40 AND created >= ? and created <= ? GROUP BY rid) AS T1 
					JOIN (SELECT rid, name FROM cm_rr_base) AS T2 ON T1.rid = T2.rid 
					JOIN (SELECT rid, rate FROM cm_rr_attr) AS T3 ON T2.rid = T3.rid', array($start, $end));

				foreach ($data as &$bills){

					$bills->income = $bills->pretax * 1.13;
					$bills->bill = $bills->income * $bills->rate / 100;	
					$bills->start_date = $start;
					$bills->end_date = $end;
				} 
		    }

		    // Only some, scan through rids
		    else{

		    	$rids = $request["rids"];

		    	foreach ($rids as &$rid){

		    		$start = $request["start_time"] . " 00:00:00";
					$end = $request["end_time"] . " 23:59:59";

					$one_data = DB::select('SELECT T1.rid, T1.pretax, T2.name, T3.rate 
						FROM (SELECT rid, sum(pretax) AS pretax FROM cm_order_base WHERE dltype <= 10 AND (dltype = 0 OR dltype = 1 OR dltype = 3) AND status <= 40 AND created >= ? and created <= ? GROUP BY rid) AS T1 
						JOIN (SELECT rid, name FROM cm_rr_base) AS T2 ON T1.rid = T2.rid 
						JOIN (SELECT rid, rate FROM cm_rr_attr) AS T3 ON T2.rid = T3.rid
						WHERE T2.rid = ?', array($start, $end, $rid));

					foreach ($one_data as &$bills){

						$bills->income = $bills->pretax * 1.13;
						$bills->bill = $bills->income * $bills->rate / 100;	
						$bills->start_date = $start;
						$bills->end_date = $end;
					} 

					$data = array_merge($one_data, $data);
		    	}
		    }
		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $data
		  	];

		    return array($response, $error_code);

	    }

	    public function addBills(Request $request){

	    	list($response, $error_code) = self::getBills($request);

	    	if($error_code == 200){

			    foreach ($response['ea_data'] as $data){
			    	DB::select('INSERT INTO cm_rr_bills_temp(rid, start_date, end_date, income, bill) 
			    				VALUES (?, ?, ?, ?, ?)', array($data->rid, $data->start_date,
			    				 $data->end_date, $data->income, $data->bill));
			    }

	    	}

		  	return response()->json($response, $error_code);
		}


	  
	}
?>
