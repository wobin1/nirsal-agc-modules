<?php declare(strict_types=1);
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 *
 * This file is part of the NIRSAL AGC project by Skylab, please read the license document
 * available in the root level of the project
 */
namespace Skylab\NirsalAgc\Plugins\NibssRequest;

use EmmetBlue\Core\Factory\DatabaseConnectionFactory as DBConnectionFactory;
use EmmetBlue\Core\Builder\QueryBuilder\QueryBuilder as QB;
use EmmetBlue\Core\Constant;
use EmmetBlue\Core\Factory\HTTPRequestFactory as HTTPRequest;

date_default_timezone_set('Africa/Lagos');

/**
 * class XHttpRequest.
 *
 * XHttpRequest Controller
 *
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since v0.0.1 07/07/2021 14:233
 */
class XHttpRequest {
	private $cloudUrl = "";
	private $authorization = "";
	private $signature = "";
	private $signatureMethod = "SHA256";
	private $acceptType = "application/json";
	private $contentType = "application/json";

	private $iv = "";
	private $key = "";

	private $headers = [];

	public function __construct(){
		$globals = Constant::getGlobals();

		$username = $globals["nibss-server-username"];
		$password = $globals["nibss-server-password"];

		$this->iv = $globals["nibss-server-iv"];
		$this->key = $globals["nibss-server-key"];

		$this->cloudUrl = $globals["nibss-server-url"];

		$this->authorization = base64_encode($username.":".$password);
		$this->signature = hash("sha256", $username.date("Ymd").$password);


		$this->headers = [
			"Authorization"=>$this->authorization,
			"SIGNATURE"=>$this->signature,
			"SIGNATURE_METH"=>$this->signatureMethod,
			"Content-Type"=>$this->contentType,
			"Accept"=>$this->acceptType
		];

	}

	public function httpPostRequest($url, $data){
		$encryptedPayload = AesCipher::encrypt($this->key, $this->iv, json_encode($data));

		$endpoint = $this->cloudUrl.$url;

		$request = HTTPRequest::post($endpoint, $encryptedPayload, $this->headers);
		
		$decryptedBody = AesCipher::decrypt($this->key, $this->iv, $request->body);

		$response = [
			"status_code"=>$request->status_code,
			"headers"=>$request->headers,
			"body"=>json_decode($decryptedBody)
		];

		return $response;
	}
}