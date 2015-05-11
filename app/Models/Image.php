<?php namespace App\Models;

//use Illuminate\Database\Eloquent\Model; //MYSQL Model
use \Purekid\Mongodm\Model; //MONGO Model

class Image extends Model {

/*
 * MYSQL MODEL
 */
/*
	public $table = "images";

	//public $primaryKey = "id";
    
	public $timestamps = true;

	public $fillable = [
	    "Description",
		"FileName",
		"File",
		"Tags"
	];
*/

/*
 * MONGO MODEL
 */
 	static $collection = "images";

    /** specific definition for attributes, not necessary! **/
    protected static $attrs = array(
        'FileName' 	=> array('type'=>'string'),
        'Date' 		=> array('type'=>'timestamp'),
        'Width' 	=> array('default'=>0,'type'=>'integer'),
        'Height' 	=> array('default'=>0,'type'=>'integer'),
        'Tags' 		=> array('type'=>'array'),
        'Description' => array('type'=>'string'),
        'S3Url' 	=> array('type'=>'string')
    );

/*
 * RULES FOR BOTH MODELS
 */
    public static $rules = [
	    "Description" 	=> "required",
		"File" 			=> "required|mimes:jpeg,jpg,bmp,png,gif"
	];	
}