<?php

namespace App\Libraries\Repositories;

use App\Models\Image;

class ImageRepository
{

	/**
	 * Returns all Servicios
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
	public function store($input)
	{

		return Image::create($input);
	}

	/**
	 * Find Image by given id
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Support\Collection|null|static|Image
	 */
	public function findImageById($id)
	{
		return Image::find($id);
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