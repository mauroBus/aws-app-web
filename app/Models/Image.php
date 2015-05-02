<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model {

	public $table = "images";

	//public $primaryKey = "id";
    
	public $timestamps = true;

	public $fillable = [
	    "Descripcion",
		"FileName",
		"File",
		"Tags"
	];

	public static $rules = [
	    "Descripcion" 	=> "required",
		"File" 			=> "required"
	];
}