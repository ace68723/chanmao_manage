<?php 
	
	namespace App;
  
	use Illuminate\Database\Eloquent\Model;
	  
	class Cm_rr_close extends Model
	{

	    protected $table = 'cm_rr_close';
	    protected $fillable = ['id', 'rid', 'start_time', 'end_time'];
	    public $timestamps = false; 
	}
?>