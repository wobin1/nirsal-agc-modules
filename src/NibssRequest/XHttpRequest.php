<?php declare(strict_types=1);
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 *
 * This file is part of the NIRSAL AGC project by Skylab, please read the license document
 * available in the root level of the project
 */
namespace Skylab\Nirsal\NibssRequest;

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
		$username = Constant::getGlobals()["nibss-server-username"];
		$password = Constant::getGlobals()["nibss-server-password"];

		self::$iv = Constant::getGlobals()["nibss-server-iv"];
		self::$key = Constant::getGlobals()["nibss-server-key"];

		self::$cloudUrl = Constant::getGlobals()["nibss-server-url"];
		self::$authorization = base64_encode($username.":".$password);
		self::$signature = hash("sha256", $username.date("Ymd").$password);


		self::$headers = [
			"Authorization"=>self::$authorization,
			"SIGNATURE"=>self::$signature;
			"SIGNATURE_METHOD"=>self::$signatureMethod,
			"Content-Type"=>self::$contentType,
			"Accept"=>self::$acceptType
		];

	}

	public function httpPostRequest($url, $data){
		$encryptedPayload = AesCipher::encrypt(self::$key, self::$iv, json_encode($data));

		$endpoint = self::$cloudUrl.$url;

		$request = HTTPRequest::post($endpoint, $encryptedPayload, self::$headers);

		$decryptedBody = AesCipher::decrypt($key, $iv, $request->body);

		$response = [
			"status_code"=>$request->status_code,
			"headers"=>$request->headers,
			"body"=>json_decode($decryptedBody)
		];

		return $response;
	}
}