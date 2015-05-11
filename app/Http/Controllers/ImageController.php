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
use App\Models\Image;
use Aws\S3\S3Client;


class ImageController extends Controller
{

	/** @var  ImageRepository */
	private $imageRepository;

	function __construct(ImageRepository $imageRepo)
	{
        $this->middleware('auth');
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
        	dd($e->getMessage());
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

			# UPDLOAD IMAGE INTO S3 BUCKETS
			list($S3FileName,$S3Url) = $this->uploadToS3($fileName, config('app.ImgUploadFolder').$fileName);

			#Process the TAGS into array type
			$Tags = explode(" ", $input['Tags']); 	

			#Save Image metadata into DB
			$imageFile = new Image();
			$imageFile->FileName 		= $fileName;
			$imageFile->Description 	= $input['Description'];
			$imageFile->MimeType 		= $file->getClientMimeType();
			$imageFile->OriginalName 	= $file->getClientOriginalName();
			$imageFile->Size 			= $file->getClientSize();
			$imageFile->S3Url 			= $S3Url;
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
	 * UPLOAD A FILE INTO AWS S3
	 *
	 * @return
	 */
	private function uploadToS3($keyname, $realpath)
    {
    	/*There MUST BE 3 buckets and select 1 of them to upload the img*/
        $buckets = [ config('app.AWS.bucket1'),
       				 config('app.AWS.bucket2'),
        			 config('app.AWS.bucket3')
        		];
		/*Select 1 bucket randomly */		
		$bucket = $buckets[ array_rand( $buckets ) ];

        $pathToFile = $realpath;

        $s3 = S3Client::factory(array(
            'key' => config('app.AWS.key'),
            'secret' => config('app.AWS.secret'),
            'region' => config('app.AWS.region'),

        ));

        try {
            // Upload data.
            $result = $s3->putObject(array(
                'Bucket' => $bucket,
                'Key' => $keyname,
                'Body' => fopen($pathToFile, 'r+'),
            ));

            // Print the URL to the object.
            return array($keyname,$result['ObjectURL']);
        } catch (S3Exception $e) {
            //$cr = new CommonResponse(500, $e->getMessage());

            return response()->json($e->getMessage(), $e->getCode());
        }
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
