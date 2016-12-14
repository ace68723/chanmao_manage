<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Cm_content_author;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;

	class AuthorController extends Controller{
	  
	    public function index(){
	  
	  		$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$Author = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{

	        	$Author = Cm_content_author::all();
	        }

	        $response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ev_data' => $Author
		  	];

	        return response()->json($response, $error_code);
	  
	    }
	  
	    public function getAuthor($author_id){

	    	$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$Author = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
	  
	        	$Author = Cm_content_author::find($author_id);
	  		}

	       	$response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg,
				'ev_data' => $Author
		  	];

	        return response()->json($response, $error_code);

	    }
	  
	    public function createAuthor(Request $request){
	  
	    	$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$Author = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{

		  		/* Varify length */
		    	$error_msg = $this->checkLength($request);

		    	if($error_msg){

					$ev_result = 1;
					$error_code = 401;
		    	
		    	}
		    	else{

					DB::statement('CALL insert_author(?, ?, ?, ?, ?)', array_values($request->all()));
				
				}
			}

	       	$response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);
	  
	    }
	  
	    public function deleteAuthor($author_id){

	    	$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$Author = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{

				$Author = DB::statement('CALL del_author(?)', array($author_id));
			}

			$response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	        return response()->json($response, $error_code);

	    }
	  
	    public function updateAuthor(Request $request,$author_id){


	    	$ev_result = 0;
			$error_code = 200;
			$error_msg = Authentication::decodeToeken();
			$Author = array();

			/* Authentication Failed */
			if($error_msg){
				
				$ev_result = 1;
				$error_code = 401;

		    }
		    else{
		    	/* Varification */
		    	$error_msg = $this->checkLength($request);

		    	if($error_msg){
					$ev_result = 1;
					$error_code = 401;
		    	}

		    	else{

		    		$update_array = array_merge(array($author_id), array_values($request->all()));
					DB::statement('CALL update_author(?, ?, ?, ?, ?, ?)', $update_array);
				
				}
			}

			$response = [
			  	'ev_result' => $ev_result,
				'ev_message' => $error_msg
		  	];

	       	return response()->json($response, $error_code);
	    }


	    public function checkLength(Request $request){


	    	$team = Varification::exceed($request->input('team'), 1, 'team');

	    	if($team){
	    		return $team;
	    	}

	  		$bio_name = Varification::exceed($request->input('bio_name'), 100, 'bio_name');

	  		if($bio_name){
	  			return $bio_name;
	  		}

	  		$bio_pic = Varification::exceed($request->input('bio_pic'), 250, 'bio_pic');

	  		if($bio_pic){
	  			return $bio_pic;
	  		}

	  		$bio_desc = Varification::exceed($request->input('bio_desc'), 500, 'bio_desc');

	  		if($bio_desc){
	  			return $bio_desc;
	  		}

	  		$content_desc = Varification::exceed($request->input('content_desc'), 500, 'content_desc');
	  		if($content_desc){
	  			return $content_desc;
	  		}
	    }
	  
	}
?>
