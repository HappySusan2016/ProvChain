<?php
/**
 * ownCloud - mydataapp
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author susan <liangxueping2015@gmail.com>
 * @copyright susan 2016
 */

namespace OCA\MyDataApp\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\Http\Client\IClient;

class PageController extends Controller {


	/** @var \OCP\Http\Client\IClient */
	protected $httpRequest;
	
	private $userId;
	
	private $url="https://tierion.com/form/submit";

	/** 
	 * Constructor
	 * @param IClient $httpRequest	
	*/
	public function __construct($AppName, IRequest $request, $UserId, IClient $httpRequest){
		parent::__construct($AppName, $request);
		$this->userId = $UserId;
		$this->httpRequest = $httpRequest;
	}

	/**
	 * CAUTION: the @Stuff turns off security checks; for this page no admin is
	 *          required and no CSRF check. If you don't know what CSRF is, read
	 *          it up in the docs or you might create a security hole. This is
	 *          basically the only required method to add this exemption, don't
	 *          add it to any other method if you don't exactly know what it does
	 *
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function index() {
		$params = ['user' => $this->userId];
		return new TemplateResponse('mydataapp', 'main', $params);  // templates/main.php
	}

	/**
	 * Simply method that posts back the payload of the request
	 * @NoAdminRequired
	 */
	public function doEcho($echo) {
		return new DataResponse(['echo' => $echo]);
	}

	public function UploadRecord($upload) {
		$this->httpRequest->post($url, ['_key' => 'aK_x5rCds0K1R8ISviIEbA',
				'data' => $upload]);		
	}

}