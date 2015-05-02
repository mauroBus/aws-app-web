<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateImageRequest;
use App\Libraries\Repositories\ImageRepository;
use Response;

use Flash;
use Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
//use Illuminate\Support\Image;
use App\Models\Image;


class ImageController extends Controller
{

	/** @var  ImageRepository */
	private $imageRepository;

	function __construct(ImageRepository $imageRepo)
	{
		$this->imageRepository = $imageRepo;
	}

	/**
	 * Display a listing of all images.
	 *
	 * @return Response
	 */
	public function index()
	{
		$images = $this->imageRepository->all();

		return view('images.index')->with('images', $images);
	}

	/**
	 * Show the form for upload a new Image.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('images.create');
	}

	/**
	 * Store a newly uploaded Image in storage.
	 *
	 * @param CreateImageRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateImageRequest $request)
	{
        #All the fields that were submited
		$input = $request->all();
		#Check for File field
		$file = Request::file('File');
		#Look for file extension
		$ext = $file->getClientOriginalExtension();
		#Determine Thubnail name
		$thumbName = str_replace(".$ext", "_thumb.$ext", $file->getClientOriginalName());
		#Save uploaded file
		Storage::disk('local')->put($file->getClientOriginalName(),  File::get($file));
		#Save thumbnail
		/*
 		 * TODO: Thumbnail Creation
		 */

		#Moving the files into public folder
		$file->move('ImgUploads/', $file->getClientOriginalName());
		
		/*
 		 * TODO: S3 Management
		 */
		/* S3
			$s3 = Storage::disk('s3');
		 	$s3->put('your/s3/path/photo.jpg', file_get_contents($uploadedFile));
		 */

		#Save Image metadata into DB
		$imageFile = new Image();
		//$imageFile->mime = $file->getClientMimeType();
		$imageFile->Filename = $file->getClientOriginalName();

		$imageFile->Descripcion = $input['Descripcion'];
		$imageFile->Tags = $input['Tags'];

		$imageFile->save();

		#Message
		Flash::message('Image uploaded.');
		return redirect(route('images.index'));
		
	}

	/**
	 * Display the specified image.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$image = $this->imageRepository->findImageById($id);

		if(empty($image))
		{
			Flash::error('Image not found.');
			return redirect(route('images.index'));
		}

		return view('images.show')->with('image', $image);
	}

	/**
	 * Show the form for editing the specified image.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$image = $this->imageRepository->findImageById($id);

		if(empty($image))
		{
			Flash::error('Image not found');
			return redirect(route('images.index'));
		}

		return view('images.edit')->with('image', $image);
	}

	/**
	 * Update the specified Image in storage.
	 *
	 * @param  int    $id
	 * @param CreateImageRequest $request
	 *
	 * @return Response
	 */
	public function update($id, CreateImageRequest $request)
	{
		$image = $this->imageRepository->findImageById($id);

		if(empty($image))
		{
			Flash::error('Image not found');
			return redirect(route('images.index'));
		}

		$image = $this->imageRepository->update($image, $request->all());

		Flash::message('Image updated.');

		return redirect(route('images.index'));
	}

	/**
	 * Remove the specified Image from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$image = $this->imageRepository->findImageById($id);

		if(empty($image))
		{
			Flash::error('Image not found');
			return redirect(route('images.index'));
		}

		$image->delete();

		Flash::message('Image deleted.');

		return redirect(route('images.index'));
	}

}
