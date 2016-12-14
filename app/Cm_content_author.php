<?php 
	
	namespace App;
  
	use Illuminate\Database\Eloquent\Model;
	  
	class Cm_content_author extends Model
	{

		protected $primaryKey = 'author_id';
	    protected $table = 'cm_content_author';
	    protected $fillable = ['author_id', 'team', 'bio_name', 'bio_pic', 'bio_desc', 'content_desc', 'status'];
	    public $timestamps = false; 
	}
?>