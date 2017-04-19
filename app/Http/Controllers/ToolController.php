<?php
  
	namespace App\Http\Controllers;

	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;
	use App\Calculation;

	class ToolController extends Controller{
	  

	    public function getThisWeekDates(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$dates = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
				
		    	$dates = Calculation::getThisWeekDates();

				
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $dates
		  	];

		    return response()->json($response, $error_code);

	    }

	   	public function getNextWeekDates(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$dates = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
				
		    	$dates = Calculation::getNextWeekDates();

				
		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ea_data' => $dates
		  	];

		    return response()->json($response, $error_code);

	    }

	  
	}
?>
