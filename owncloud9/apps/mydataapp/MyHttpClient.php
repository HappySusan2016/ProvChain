<?php

namespace OCA\MyDataApp;

use OCP\Http\Client\IClient;

class MyHttpClient implements IClient {
	
	/** @var  IRequest*/
	protected $HttpRequest;
	
	//protected $data;
	
	//protected $url;
	
	//public function __construct() {
		//$this->data = $data;
		//$this->url = $url;
	//}
	
	public function SendRequest($url, $data) {
		$this->post($url, [
				'RecordEntry' => $data,
		]);
		//if ($this->HttpRequest)
	}
}