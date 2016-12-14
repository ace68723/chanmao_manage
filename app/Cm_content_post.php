<?php 
	
	namespace App;
  
	use Illuminate\Database\Eloquent\Model;
	  
	class Cm_content_post extends Model
	{

	    protected $table = 'cm_content_post';
	    protected $fillable = ['id', 'author_id', 'cid', 'title', 'summary', 'icon_url', 'content_url', 'created_at', 'created_by', 'status'];
	    public $timestamps = false; 
	}
?>