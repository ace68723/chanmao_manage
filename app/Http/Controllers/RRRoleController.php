œ	<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Authentication;

	class RRRoleController extends Controller{
	  

	    public function roleCheck(){

			$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$ev_role = 3;


			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$ev_role = 0;
				$error_code = 401;

		    }

		    $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ev_role' => $ev_role
		  	];

		    return response()->json($response, $error_code);

	    }

	  
	}
?>
