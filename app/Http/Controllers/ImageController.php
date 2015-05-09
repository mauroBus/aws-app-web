<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateImageRequest;
use App\Http\Requests\ListImageRequest;
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
        try{
            $images = $this->imageRepository->all();
        } catch (\Exception $e) {
            $images = false;
        }

        if(!$images || $images->count() == 0 ){
            return redirect('images/create');
        }

		return view('images.index')->with('images', $images)->with('SearchCriteria','');
	}

	/**
	 * Display a listing of certain images.
	 *
	 * @return Response
	 */
	public function report(ListImageRequest $request)
	{ 
		 #All the fields that were submited
		$input = $request->all();
		$SearchCriteria = $input['SearchCriteria'];
		if( $SearchCriteria !== '' ) {  
			$images = $this->imageRepository->find(
				array( 
			     '$or' => array( 
			      array('Tags' => array('$regex' => $input['SearchCriteria'] )), /*Serch with LIKE*/
			      array('Description' => array('$regex' => $input['SearchCriteria'] )) /*Serch with LIKE*/
			     )
			 	)
			);

		} else {
			$images = $this->imageRepository->all();
		}

		return view('images.index',compact('images','SearchCriteria'));
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

		if ( Request::hasFile('File') and Request::file('File')->isValid() ) {
			#Check for File field
			$file = Request::file('File');

			#Validate it is a valide image extension
			 #It was done with $rules on request

			#Creating a random name of image to store it
			$fileName = rand(11111,99999).'_'.$file->getClientOriginalName(); // renaming image
			
			#Save thumbnail
			/*
	 		 * This step should be done on AWS: Thumbnail Creation
			 */

			#Moving the files into public folder
			$file->move(config('app.ImgUploadFolder'), $fileName);

			#Get image metadata
			list($width, $height, $type, $attr) = getimagesize(config('app.ImgUploadFolder').$fileName);

			/*
	 		 * TODO: S3 Management
			 */
			/* S3
				$s3 = Storage::disk('s3');
			 	$s3->put('your/s3/path/photo.jpg', file_get_contents($uploadedFile));
			 */

			#Process the TAGS into array type
			$Tags = explode(" ", $input['Tags']); 	

			#Save Image metadata into DB
			$imageFile = new Image();
			$imageFile->FileName 		= $fileName;
			$imageFile->Description 	= $input['Description'];
			$imageFile->MimeType 		= $file->getClientMimeType();
			$imageFile->OriginalName 	= $file->getClientOriginalName();
			$imageFile->Size 			= $file->getClientSize();
			$imageFile->Tags 			= $Tags;
		    $imageFile->Date 			= strtotime("Today");
		    $imageFile->Width 			= $width;
		    $imageFile->Height			= $height;

			$imageFile->save();

			#Message
			Flash::message('Image uploaded.');
		} else {
			Flash::message('Image NOT uploaded.');
		}
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
