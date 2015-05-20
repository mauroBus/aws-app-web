<?php namespace App\Models;

use Aws\DynamoDb\DynamoDbClient;

class ImageDynamoDB {

	/** @var  awsClient */
	private $awsClient;

	public function __construct($key, $secret, $region)
	{
	    $this->awsClient = DynamoDbClient::factory(array(
		    'key' 	 => $key,
		    'secret' => $secret,
		    'region' => $region,
		));
	
	}

	#Add a document into DynamoDB
	public function addItem($id,$FileName,$Date,$Width,$Height,$Tags,$Description,$S3Url){

		#Insert into Dynamo DB
		$result = $this->awsClient->putItem(array(
		    'TableName' => 'images',
		    'Item' => array(
		        'id'      	=> array('S' => $id),
		        'FileName'  => array('S' => $FileName),
		        'Date'    	=> array('N' => $Date), #timestamp
		        'Width'    	=> array('N' => $Width),
		        'Height'    => array('N' => $Height),
		        'Tags'    	=> array('SS' => $Tags), #array
		        'Description'   => array('S' => $Description),
		        'S3Url' 	=> array('S' => $S3Url)
		    )
		));

		return $result;
	}



}