<?php

namespace App\Libraries\Repositories;

use App\Models\Image;

class ImageRepository
{

	/**
	 * Returns all Images that match with a criteria
	 *
	 * @return 
	 */
	public function find($params)
	{
		return Image::find($params);
	}

 	/**
	 * Returns all Images
	 *
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function all()
	{
		return Image::all();
	}

	/**
	 * Stores Image into database
	 *
	 * @param array $input
	 *
	 * @return Image
	 */
	/*public function store($input)
	{

		//return Image::create($input);
		$image = new Image();
	    $image->name 		= $input[''];
	    $image->date 		= $input[''];
	    $image->width 		= $input[''];
	    $image->height		= $input[''];
	    $image->tags 		= $input['Tags'];
	    $image->description = $input['Descripcion'];
	    $image->save();
	}*/

	/**
	 * Find Image by given id
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Support\Collection|null|static|Image
	 */
	public function findImageById($id)
	{
		return Image::id($id);
	}

	/**
	 * Updates Image into database
	 *
	 * @param Image $image
	 * @param array $input
	 *
	 * @return Image
	 */
	public function update($image, $input)
	{
		$image->fill($input);
		$image->save();

		return $image;
	}
}