<?php
  
	namespace App\Http\Controllers;
	  
	use DB;
	use App\Cm_content_post;
	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Varification;
	use App\Authentication;

	class PostController extends Controller{
	  
	  
	    public function index(){
	  
	        $Post = Cm_content_post::all();
	  		
	        return response()->json($Post, 200);
	  
	    }
	  
	    public function getPost($Post_id){
	  
	        $Post = Cm_content_post::find($Post_id);
	  
	        return response()->json($Post, 200);
	    }
	  
	    public function createPost(Request $request){

	    	/* Varification */
	    	$error_msg = $this->checkLength($request);

	    	if($error_msg){
	    		$response = [
			  			'result' => 0,
			  			'msg' => 'Create Post Failed',
			  			'error' => $error_msg
			  			];

		        return response()->json($response, 401);
	    	}

			$Post = DB::statement('CALL insert_post(?, ?, ?, ?, ?)', array_values($request->all()));

	  		$response = [
	  			'msg' => 'Post Created',
	  			'Success' => $Post
	  		];

	  		
	        return response()->json($response, 201);
	  
	    }
	  
	    public function deletePost($post_id){

			$Post = DB::statement('CALL del_post(?)', array($post_id));

	  		$response = [
	  			'msg' => 'Post Deleted',
	  			'Success' => $Post
	  		];

	 
	        return response()->json($response, 201);
	    }
	  
	    public function updatePost(Request $request, $post_id){

	    	/* Varification */
	    	$error_msg = $this->checkLength($request);

	    	if($error_msg){
	    		$response = [
			  			'result' => 0,
			  			'msg' => 'Update Post Failed',
			  			'error' => $error_msg
			  			];

		        return response()->json($response, 401);
	    	}

			$update_array = array_merge(array($post_id), array_values($request->all()));

			$Post = DB::statement('CALL update_post(?, ?, ?, ?, ?, ?)', $update_array);

	  		$response = [
	  			'msg' => 'Post Updated',
	  			'Success' => $Post
	  		];
	  		
	        return response()->json($response, 201);
	    }

	    public function checkLength(Request $request){

	    	$error_msg = '';
	    	if(Varification::exceed($request->input('title'), 100)){
	  			$error_msg = 'Length exceed';
	  		}
	  		if(Varification::exceed($request->input('summary'), 500)){
	  			$error_msg = 'Length exceed';
	  		}
	  		if(Varification::exceed($request->input('icon_url'), 1024)){
	  			$error_msg = 'Length exceed';
	  		}
	  		if(Varification::exceed($request->input('content_url'), 1024)){
	  			$error_msg = 'Length exceed';
	  		}


	  		return $error_msg;
	    }
	  
	}
?>
